<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\User;
use App\Models\Keranjang;
use App\Models\DetailPesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\NotificationController;

// Global Laravel helper functions
use function view;
use function redirect;
use function response;
use function request;
use function route;
use function public_path;
use function storage_path;
use function env;
use function now;
use function abort;
use function back;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pesanan::with(['detailPesanan.produk'])
            ->where('user_id', Auth::id());

        // Status filter
        if ($request->status) {
            if ($request->status == 'Sedang Diproses') {
                $query->whereIn('status_pesanan', ['Sedang Diproses', 'Diproses', 'Karantina']);
            } elseif ($request->status == 'Dikirim') {
                $query->whereIn('status_pesanan', ['Sedang Dikirim', 'Dikirim']);
            } else {
                $query->where('status_pesanan', $request->status);
            }
        }

        // Search filter for product name or order number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id_pesanan', 'like', '%' . $search . '%')
                  ->orWhereHas('detailPesanan.produk', function($pq) use ($search) {
                      $pq->where('nama_ikan', 'like', '%' . $search . '%');
                  });
            });
        }

        // Order by latest first
        $query->orderBy('created_at', 'desc');

        $pesanan = $query->paginate(10);
        return view('pesanan.index', compact('pesanan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $keranjang = Keranjang::where('user_id', Auth::id())->with('produk')->get();

        if ($keranjang->isEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Keranjang belanja kosong, silakan tambahkan produk terlebih dahulu.');
        }

        return view('pesanan.create', compact('keranjang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'alamat_pengiriman' => 'required|string',
            'metode_pembayaran' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Get cart items
            $keranjangItems = Keranjang::where('user_id', Auth::id())->with('produk')->get();

            if ($keranjangItems->isEmpty()) {
                return redirect()->route('keranjang.index')
                    ->with('error', 'Keranjang belanja kosong, silakan tambahkan produk terlebih dahulu.');
            }

            // Calculate total
            $total = 0;
            foreach ($keranjangItems as $item) {
                $total += $item->produk->harga * $item->kuantitas;
            }

            // Create order
            $pesanan = Pesanan::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'status' => 'menunggu_pembayaran',
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'metode_pembayaran' => $request->metode_pembayaran,
            ]);

            // Create order details
            foreach ($keranjangItems as $item) {
                DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_Produk' => $item->produk_id,
                    'kuantitas' => $item->kuantitas,
                    'harga' => $item->produk->harga,
                    'subtotal' => $item->produk->harga * $item->kuantitas,
                ]);

                // Update stock
                $produk = Produk::find($item->produk_id);
                $produk->stok -= $item->kuantitas;
                $produk->save();
            }

            // Clear cart
            Keranjang::where('user_id', Auth::id())->delete();

            // Notify admin about new order
            NotificationController::notifyAdmins([
                'type' => 'order',
                'title' => 'Pesanan Baru',
                'message' => 'Pesanan baru #' . $pesanan->id_pesanan . ' telah dibuat dan menunggu konfirmasi pembayaran.',
                'data' => [
                    'order_id' => $pesanan->id_pesanan,
                    'url' => route('admin.pesanan.show', $pesanan->id_pesanan)
                ]
            ]);

            // Notify customer about their order
            NotificationController::notifyCustomer(Auth::id(), [
                'type' => 'order',
                'title' => 'Pesanan Berhasil Dibuat',
                'message' => 'Pesanan #' . $pesanan->id_pesanan . ' telah berhasil dibuat. Silakan lakukan pembayaran.',
                'data' => [
                    'order_id' => $pesanan->id_pesanan,
                    'url' => route('pesanan.show', $pesanan->id_pesanan)
                ]
            ]);

            DB::commit();

            return redirect()->route('pesanan.show', $pesanan->id_pesanan)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk', 'user'])
            ->where('id_pesanan', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('pesanan.show', compact('pesanan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // This would be used to update order status, add payment proof, etc.
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|string|in:menunggu_pembayaran,pembayaran_dikonfirmasi,diproses,dikirim,selesai,dibatalkan'
        ]);

        try {
            $pesanan = Pesanan::findOrFail($id);
            $oldStatus = $pesanan->status;
            $newStatus = $request->status;

            // Update status
            $pesanan->status = $newStatus;
            $pesanan->save();

            // Translate status for notification
            $statusTranslations = [
                'menunggu_pembayaran' => 'Menunggu Pembayaran',
                'pembayaran_dikonfirmasi' => 'Pembayaran Dikonfirmasi',
                'diproses' => 'Sedang Diproses',
                'dikirim' => 'Sedang Dikirim',
                'selesai' => 'Selesai',
                'dibatalkan' => 'Dibatalkan'
            ];

            // Notify customer about status change
            NotificationController::notifyCustomer($pesanan->user_id, [
                'type' => 'order',
                'title' => 'Status Pesanan Diperbarui',
                'message' => 'Status pesanan #' . $pesanan->id_pesanan . ' telah diperbarui menjadi ' .
                            ($statusTranslations[$newStatus] ?? $newStatus),
                'data' => [
                    'order_id' => $pesanan->id_pesanan,
                    'url' => route('pesanan.show', $pesanan->id_pesanan),
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status pesanan berhasil diperbarui'
                ]);
            }

            return redirect()->back()
                ->with('success', 'Status pesanan berhasil diperbarui');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui status pesanan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal memperbarui status pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Konfirmasi pesanan telah diterima
     */
    public function konfirmasiPesanan(string $id)
    {
        try {
            $pesanan = Pesanan::where('id_pesanan', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($pesanan->status_pesanan != 'Dikirim') {
                return redirect()->back()
                    ->with('error', 'Pesanan hanya dapat dikonfirmasi jika statusnya Dikirim.');
            }

            // Update status pesanan menjadi selesai
            $pesanan->status_pesanan = 'Selesai';
            $pesanan->save();

            // Notify admin about order completion
            NotificationController::notifyAdmins([
                'type' => 'order',
                'title' => 'Pesanan Selesai',
                'message' => 'Pesanan #' . $pesanan->id_pesanan . ' telah dikonfirmasi selesai oleh pelanggan.',
                'data' => [
                    'order_id' => $pesanan->id_pesanan,
                    'url' => route('admin.pesanan.show', $pesanan->id_pesanan)
                ]
            ]);

            return redirect()->route('pesanan.show', $pesanan->id_pesanan)
                ->with('success', 'Pesanan berhasil dikonfirmasi. Terima kasih telah berbelanja!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengonfirmasi pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Show payment upload form
     */
    public function showPaymentUpload(string $id)
    {
        $pesanan = Pesanan::where('id_pesanan', $id)
            ->where('user_id', Auth::id())
            ->with(['detailPesanan.produk', 'user'])
            ->firstOrFail();

        if ($pesanan->status_pesanan != 'Menunggu Pembayaran') {
            return redirect()->route('pesanan.show', $id)
                ->with('error', 'Tidak dapat mengunggah bukti pembayaran karena status pesanan tidak valid.');
        }

        return view('pesanan.payment-upload', compact('pesanan'));
    }

    /**
     * View payment proof directly
     */
    public function viewPaymentProof(string $id)
    {
        try {
            $pesanan = Pesanan::where('id_pesanan', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if (!$pesanan->bukti_pembayaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bukti pembayaran tidak ditemukan'
                ], 404);
            }

            $fileName = basename($pesanan->bukti_pembayaran);
            $imagePath = '';
            $pathsChecked = [];

            // Check all possible paths with proper logging for debugging

            // First priority: Check uploads/payments directory with the filename
            $uploadsPaymentPath = public_path('uploads/payments/' . $fileName);
            $pathsChecked[] = $uploadsPaymentPath;
            if (file_exists($uploadsPaymentPath)) {
                $imagePath = $uploadsPaymentPath;
            }

            // Second: Check if the bukti_pembayaran is a full path that exists
            if (!$imagePath) {
                $directPath = public_path($pesanan->bukti_pembayaran);
                $pathsChecked[] = $directPath;
                if (file_exists($directPath)) {
                    $imagePath = $directPath;
                }
            }

            // Third: Check storage/app/public path
            if (!$imagePath) {
                $storagePath = storage_path('app/public/' . $pesanan->bukti_pembayaran);
                $pathsChecked[] = $storagePath;
                if (file_exists($storagePath)) {
                    $imagePath = $storagePath;
                }
            }

            // Fourth: Check storage/app/public/payment_proofs path
            if (!$imagePath) {
                $storagePaymentsPath = storage_path('app/public/payment_proofs/' . $fileName);
                $pathsChecked[] = $storagePaymentsPath;
                if (file_exists($storagePaymentsPath)) {
                    $imagePath = $storagePaymentsPath;
                }
            }

            // If still not found, check if there's a direct uploads/payments path stored
            if (!$imagePath && strpos($pesanan->bukti_pembayaran, 'uploads/payments/') !== false) {
                $uploadsDirectPath = public_path($pesanan->bukti_pembayaran);
                $pathsChecked[] = $uploadsDirectPath;
                if (file_exists($uploadsDirectPath)) {
                    $imagePath = $uploadsDirectPath;
                }
            }

            // If we still don't have an image path, return 404 with debugging info
            if (!$imagePath || !file_exists($imagePath)) {
                // Log the error for server-side diagnostics
                \Illuminate\Support\Facades\Log::warning('Payment proof image not found (customer view)', [
                    'pesanan_id' => $pesanan->id_pesanan,
                    'bukti_pembayaran' => $pesanan->bukti_pembayaran,
                    'paths_checked' => $pathsChecked
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'File bukti pembayaran tidak ditemukan di server',
                    'paths_checked' => $pathsChecked,
                    'file_name' => $fileName,
                    'bukti_pembayaran_path' => $pesanan->bukti_pembayaran
                ], 404);
            }

            // Get file extension to determine content type
            $ext = pathinfo($imagePath, PATHINFO_EXTENSION);
            $contentType = 'image/jpeg'; // Default

            if ($ext == 'png') {
                $contentType = 'image/png';
            } elseif ($ext == 'gif') {
                $contentType = 'image/gif';
            } elseif ($ext == 'jpg' || $ext == 'jpeg') {
                $contentType = 'image/jpeg';
            } elseif ($ext == 'pdf') {
                $contentType = 'application/pdf';
            } elseif ($ext == 'webp') {
                $contentType = 'image/webp';
            }

            // Return the file with proper content type
            return response()->file($imagePath, ['Content-Type' => $contentType]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error viewing payment proof (customer view)', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit payment proof
     */
    public function submitPayment(Request $request, string $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $pesanan = Pesanan::where('id_pesanan', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($pesanan->status_pesanan != 'Menunggu Pembayaran') {
                return redirect()->back()
                    ->with('error', 'Tidak dapat mengunggah bukti pembayaran karena status pesanan tidak valid.');
            }

            // Upload payment proof
            $file = $request->file('bukti_pembayaran');
            $filename = 'payment_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payments'), $filename);

            // Update order
            $pesanan->bukti_pembayaran = 'uploads/payments/' . $filename;
            $pesanan->save();

            // Notify admin about new payment proof
            NotificationController::notifyAdmins([
                'type' => 'order',
                'title' => 'Bukti Pembayaran Baru',
                'message' => 'Pelanggan telah mengunggah bukti pembayaran untuk pesanan #' . $pesanan->id_pesanan,
                'data' => [
                    'order_id' => $pesanan->id_pesanan,
                    'url' => route('admin.pesanan.show', $pesanan->id_pesanan)
                ]
            ]);

            return redirect()->back()
                ->with('success', 'Bukti pembayaran berhasil diunggah. Mohon tunggu konfirmasi dari admin.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengunggah bukti pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Halaman checkout pesanan (lanjutan dari keranjang)
     */
    public function checkout(Request $request)
    {
        // Validasi input dari selected_items
        $request->validate([
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:keranjang,id_keranjang',
        ]);

        $user = Auth::user();

        // Mengambil item keranjang yang dipilih saja
        $keranjang = Keranjang::whereIn('id_keranjang', $request->selected_items)
                    ->where('user_id', Auth::id())
                    ->with('produk', 'ukuran')
                    ->get();

        // Cek jika keranjang kosong
        if ($keranjang->isEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Tidak ada produk yang dipilih. Silakan pilih produk terlebih dahulu.');
        }

        // Menghitung subtotal dan total
        $subtotal = $keranjang->sum('total_harga');
        $ongkir = 0; // Default ongkir, nanti diupdate via AJAX
        $total = $subtotal + $ongkir;

        // Cek apakah user sudah memiliki alamat lengkap
        $alamatLengkap = null;
        if ($user->alamat_id && $user->alamat_jalan) {
            // Ambil data lengkap alamat dengan eager loading
            $userWithRelations = User::with(['alamat'])
                ->where('id', $user->id)
                ->first();

            if ($userWithRelations->alamat) {
                $alamatLengkap = [
                    'provinsi' => $userWithRelations->alamat->provinsi,
                    'kabupaten' => $userWithRelations->alamat->kabupaten,
                    'kecamatan' => $userWithRelations->alamat->kecamatan,
                    'kode_pos' => $userWithRelations->alamat->kode_pos,
                    'jalan' => $user->alamat_jalan,
                    'alamat_id' => $user->alamat_id, // Untuk perhitungan ongkir
                ];
            }
        }

        // Ambil daftar metode pembayaran
        $metodePembayaran = [
            'transfer_bank' => 'Transfer Bank',
            'qris' => 'QRIS',
        ];

        return view('pesanan.checkout', compact('keranjang', 'alamatLengkap', 'subtotal', 'ongkir', 'total', 'metodePembayaran'));
    }

    /**
     * Halaman tambah alamat untuk checkout
     */
    public function tambahAlamat()
    {
        $user = Auth::user();

        // Tidak perlu mendapatkan provinsi lagi karena kita menggunakan RajaOngkir API
        return view('pesanan.tambah_alamat', compact('user'));
    }

    /**
     * Proses simpan alamat untuk checkout
     */
    public function simpanAlamat(Request $request)
    {
        $request->validate([
            'alamat_id' => 'required|exists:alamat,id',
            'alamat_jalan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15',
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:keranjang,id_keranjang',
        ]);

        // Update data alamat user
        User::where('id', Auth::id())->update([
            'alamat_id' => $request->alamat_id,
            'alamat_jalan' => $request->alamat_jalan,
            'no_hp' => $request->no_hp
        ]);

        // Redirect ke halaman checkout dengan meneruskan selected_items
        return redirect()->route('checkout', ['selected_items' => $request->selected_items])
            ->with('success', 'Alamat berhasil disimpan');
    }

    /**
     * Get ongkir berdasarkan alamat dengan RajaOngkir API
     */
    public function getOngkir($alamatId)
    {
        try {
            // Cek apakah ada cache untuk request ini
            $cacheKey = 'ongkir_' . $alamatId . '_' . implode('_', request('selected_items', []));

            // Coba ambil dari cache dulu
            if (\Illuminate\Support\Facades\Cache::has($cacheKey)) {
                return response()->json(\Illuminate\Support\Facades\Cache::get($cacheKey));
            }

            // Ambil items yang dipilih untuk dihitung total jumlah dan berat
            $selectedItems = request('selected_items', []);
            $totalJumlah = 0;
            $totalBerat = 0; // dalam gram
            $jumlahIkan = 0; // khusus untuk menghitung jumlah ikan

            if (!empty($selectedItems)) {
                $keranjangItems = Keranjang::whereIn('id_keranjang', $selectedItems)
                                ->where('user_id', Auth::id())
                                ->with('produk')
                                ->get();

                foreach ($keranjangItems as $item) {
                    $totalJumlah += $item->jumlah;

                    // Semua produk adalah ikan hias, jadi kita hitung jumlah ikan
                    $jumlahIkan += $item->jumlah;
                }

                // Hitung jumlah box yang dibutuhkan (3 ikan per box)
                $jumlahBox = ceil($jumlahIkan / 3);

                // Setiap box memiliki berat 10kg (10.000 gram)
                $totalBerat = $jumlahBox * 10000;
            } else {
                // Return empty result if no items
                return response()->json([
                    'success' => false,
                    'message' => 'No items selected for shipping calculation',
                    'jumlah_total' => 0,
                    'berat_total' => 0,
                    'jumlah_box' => 0
                ]);

                // Simpan ke cache selama 15 menit
                \Illuminate\Support\Facades\Cache::put($cacheKey, $fallbackResult, now()->addMinutes(15));

                return response()->json($fallbackResult);
            }

            // Ambil alamat lengkap
            $alamat = \App\Models\Alamat::find($alamatId);
            if (!$alamat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alamat tidak ditemukan',
                ], 404);
            }

            // Gunakan RajaOngkir API untuk menghitung ongkos kirim dengan timeout
            $originId = env('STORE_LOCATION_ID', 30957); // ID lokasi toko

            // Persiapkan parameter untuk API RajaOngkir
            $params = [
                'origin' => $originId,
                'destination' => $alamatId,
                'weight' => max(1000, $totalBerat), // Minimal 1000 gram (1 kg)
                'courier' => 'tiki', // Default courier untuk pengiriman ikan hias
            ];

            // Call RajaOngkir API through RajaOngkirController
            $rajaOngkirController = new \App\Http\Controllers\RajaOngkirController();
            $ongkirRequest = new \Illuminate\Http\Request();
            $ongkirRequest->merge([
                'origin' => $originId,
                'destination' => $alamatId,
                'weight' => max(1000, $totalBerat),
                'courier' => 'tiki'
            ]);

            $response = $rajaOngkirController->cekOngkir($ongkirRequest);
            $responseData = $response->getData(true);

            if ($response->status() === 200 && $responseData['success']) {

                // Proses data hasil API
                if (isset($responseData['data']) && !empty($responseData['data'])) {
                    // Ambil opsi pengiriman termurah sebagai default
                    $ongkirOptions = [];
                    $defaultOngkir = PHP_INT_MAX;

                    foreach ($responseData['data'] as $courier) {
                        if (isset($courier['costs']) && is_array($courier['costs'])) {
                            foreach ($courier['costs'] as $cost) {
                                $biaya = $cost['cost'][0]['value'] ?? 0;
                                $estimasi = $cost['cost'][0]['etd'] ?? '-';
                                $layanan = $cost['service'] ?? '';

                                $ongkirOptions[] = [
                                    'courier' => $courier['code'],
                                    'courier_name' => $courier['name'],
                                    'service' => $layanan,
                                    'cost' => $biaya,
                                    'etd' => $estimasi
                                ];

                                // Update ongkir termurah
                                if ($biaya < $defaultOngkir) {
                                    $defaultOngkir = $biaya;
                                }
                            }
                        }
                    }

                    // Tambahan biaya berdasarkan jumlah item (sesuai dengan logika sebelumnya)
                    $tambahan = 0;
                    if ($totalJumlah > 3) {
                        $tambahan = ceil(($totalJumlah - 3) / 3) * 2000;
                    }

                    $finalOngkir = $defaultOngkir + $tambahan;

                    $result = [                    'success' => true,
                    'ongkir' => $responseData['data']['recommended']['cost'],
                    'ongkir_options' => $responseData['data']['shipping_options'],
                    'jumlah_total' => $totalJumlah,
                        'berat_total' => $totalBerat,
                        'jumlah_box' => $jumlahBox,
                        'ikan_per_box' => 3,
                        'berat_per_box' => 10, // dalam kg
                        'biaya_tambahan' => $tambahan,
                        'box_info' => [
                            'ukuran_box' => '40x40x40 cm',
                            'deskripsi' => 'Box khusus pengiriman ikan hias dengan sistem aerasi',
                            'max_kapasitas' => 3,
                            'rekomendasi' => 'Pengiriman menggunakan kurir TIKI untuk menjaga keamanan dan kesegaran ikan'
                        ]
                    ];

                    // Simpan ke cache selama 30 menit
                    \Illuminate\Support\Facades\Cache::put($cacheKey, $result, now()->addMinutes(30));

                    return response()->json($result);
                }
            }

            // Fallback jika API gagal
            $fallbackResult = [
                'success' => true,
                'ongkir' => 50000, // Default ongkir
                'ongkir_options' => [
                    [
                        'courier' => 'tiki',
                        'courier_name' => 'TIKI',
                        'service' => 'REG',
                        'cost' => 50000,
                        'etd' => '1-3'
                    ]
                ],
                'jumlah_total' => $totalJumlah,
                'berat_total' => $totalBerat,
                'jumlah_box' => $jumlahBox,
                'ikan_per_box' => 3,
                'berat_per_box' => 10, // dalam kg
                'box_info' => [
                    'ukuran_box' => '40x40x40 cm',
                    'deskripsi' => 'Box khusus pengiriman ikan hias dengan sistem aerasi',
                    'max_kapasitas' => 3,
                    'rekomendasi' => 'Pengiriman menggunakan kurir TIKI untuk menjaga keamanan dan kesegaran ikan'
                ]
            ];

            // Simpan fallback ke cache selama 15 menit
            \Illuminate\Support\Facades\Cache::put($cacheKey, $fallbackResult, now()->addMinutes(15));

            return response()->json($fallbackResult);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in getOngkir: ' . $e->getMessage());

            return response()->json([
                'success' => true,
                'message' => 'Menggunakan tarif standar: ' . $e->getMessage(),
                'ongkir' => 50000, // Default ongkir jika gagal
                'ongkir_options' => [
                    [
                        'courier' => 'tiki',
                        'courier_name' => 'TIKI',
                        'service' => 'REG',
                        'cost' => 50000,
                        'etd' => '1-3'
                    ]
                ],
                'jumlah_total' => $totalJumlah ?? 0,
                'berat_total' => isset($jumlahBox) ? $jumlahBox * 10000 : 10000,
                'jumlah_box' => $jumlahBox ?? 1,
                'ikan_per_box' => 3,
                'berat_per_box' => 10, // dalam kg
                'box_info' => [
                    'ukuran_box' => '40x40x40 cm',
                    'deskripsi' => 'Box khusus pengiriman ikan hias dengan sistem aerasi',
                    'max_kapasitas' => 3,
                    'rekomendasi' => 'Pengiriman menggunakan kurir TIKI untuk menjaga keamanan dan kesegaran ikan'
                ]
            ]);
        }
    }

    /**
     * Process checkout to create order
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:keranjang,id_keranjang',
            'metode_pembayaran' => 'required|string|in:transfer_bank,qris',
            'courier' => 'sometimes|required|string',
            'courier_service' => 'sometimes|required|string',
        ]);

        // Validate that only TIKI courier is used for fish shipments
        if ($request->courier && $request->courier !== 'tiki') {
            return redirect()->back()
                ->with('error', 'Pengiriman ikan hias hanya dapat menggunakan kurir TIKI untuk menjaga keamanan dan kualitas ikan.');
        }

        try {
            DB::beginTransaction();

            // Get selected cart items
            $keranjangItems = Keranjang::whereIn('id_keranjang', $request->selected_items)
                            ->where('user_id', Auth::id())
                            ->with('produk', 'ukuran')
                            ->get();

            if ($keranjangItems->isEmpty()) {
                return redirect()->route('keranjang.index')
                    ->with('error', 'Tidak ada produk yang dipilih. Silakan pilih produk terlebih dahulu.');
            }

            // Get user's address
            $user = Auth::user();
            if (!$user->alamat_jalan || !$user->alamat_id) {
                return redirect()->route('alamat.tambah', ['selected_items' => $request->selected_items])
                    ->with('error', 'Harap lengkapi alamat pengiriman terlebih dahulu.');
            }

            // Get user's address with relations
            $userWithRelations = User::with(['alamat'])
                ->where('id', Auth::id())
                ->first();

            // Construct complete address
            $alamatPengiriman = $userWithRelations->alamat_jalan . ', ' .
                                $userWithRelations->alamat->kecamatan . ', ' .
                                $userWithRelations->alamat->kabupaten . ', ' .
                                $userWithRelations->alamat->provinsi . ' ' .
                                $userWithRelations->alamat->kode_pos;

            // Calculate subtotal
            $subtotal = $keranjangItems->sum('total_harga');

            // Calculate total quantity and weight
            $totalJumlah = $keranjangItems->sum('jumlah');
            $totalBerat = 0;
            $jumlahIkan = 0; // Khusus untuk menghitung jumlah ikan

            // Hitung jumlah ikan
            foreach ($keranjangItems as $item) {
                $jumlahIkan += $item->jumlah;
            }

            // Hitung jumlah box yang dibutuhkan (3 ikan per box)
            $jumlahBox = ceil($jumlahIkan / 3);

            // Setiap box memiliki berat 10kg (10.000 gram)
            $totalBerat = $jumlahBox * 10000;

            // Use RajaOngkir API for shipping cost calculation
            $originId = env('STORE_LOCATION_ID', 1); // ID lokasi toko

            try {
                // Call RajaOngkir API
                $response = \Illuminate\Support\Facades\Http::withHeaders([
                    'key' => env('RAJA_ONGKIR_API_KEY'),
                ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                    'origin' => $originId,
                    'destination' => $user->alamat_id,
                    'weight' => max(1000, $totalBerat), // Minimum 1kg
                    'courier' => 'tiki', // Selalu menggunakan TIKI untuk pengiriman ikan hias
                ]);

                if ($response->successful()) {
                    $responseData = $response->json();

                    // Get shipping cost from API response
                    if (isset($responseData['data']) && !empty($responseData['data'])) {
                        // Get specified courier and service from request
                        $selectedCourier = $request->courier ?? 'jne';
                        $selectedService = $request->courier_service ?? '';

                        $ongkirBiaya = 50000; // Default if not found

                        // Cari layanan TIKI dalam respons API
                        foreach ($responseData['data'] as $courier) {
                            if ($courier['code'] == 'tiki') {
                                if (isset($courier['costs']) && !empty($courier['costs'])) {
                                    // Default ke layanan REG (reguler) jika tersedia
                                    $regService = null;
                                    $bestService = null;

                                    foreach ($courier['costs'] as $cost) {
                                        if ($cost['service'] == 'REG') {
                                            $regService = $cost;
                                            break;
                                        } else {
                                            $bestService = $cost;
                                        }
                                    }

                                    // Gunakan layanan REG jika tersedia, atau layanan terbaik lainnya
                                    if ($regService) {
                                        $ongkirBiaya = $regService['cost'][0]['value'] ?? $ongkirBiaya;
                                        $selectedService = 'REG';
                                    } else if ($bestService) {
                                        $ongkirBiaya = $bestService['cost'][0]['value'] ?? $ongkirBiaya;
                                        $selectedService = $bestService['service'] ?? '';
                                    }
                                }
                                break;
                            }
                        }
                    }
                } else {
                    // Fallback to database ongkir if API fails
                    $ongkir = \App\Models\Ongkir::where('alamat_id', $user->alamat_id)->first();
                    $ongkirBiaya = $ongkir ? $ongkir->biaya : 50000;
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error calculating shipping cost: ' . $e->getMessage());
                // Fallback to database ongkir if API fails
                $ongkir = \App\Models\Ongkir::where('alamat_id', $user->alamat_id)->first();
                $ongkirBiaya = $ongkir ? $ongkir->biaya : 50000;
            }

            // Tambahan biaya berdasarkan jumlah
            if ($totalJumlah > 3) {
                $tambahan = ceil(($totalJumlah - 3) / 3) * 2000;
                $ongkirBiaya += $tambahan;
            }

            // Calculate total
            $total = $subtotal + $ongkirBiaya;

            // Create order dengan batas waktu 1 jam
            $pesanan = Pesanan::create([
                'user_id' => Auth::id(),
                'id_ongkir' => $ongkir ? $ongkir->id_ongkir : 1, // Use the ongkir id or a default value
                'total_harga' => $total,
                'status_pesanan' => 'Menunggu Pembayaran', // Ubah status menjadi Menunggu Pembayaran
                'alamat_pengiriman' => $alamatPengiriman,
                'metode_pembayaran' => $request->metode_pembayaran,
                'batas_waktu' => now()->addHour(), // Tambahkan batas waktu 1 jam dari sekarang
                'alamat_id' => $user->alamat_id, // Save the alamat_id for future reference
                'kurir' => 'tiki', // Menggunakan TIKI untuk pengiriman ikan hias
                'kurir_service' => $selectedService ?? 'REG', // Informasi layanan kurir (default REG)
                'ongkir_biaya' => $ongkirBiaya, // Simpan biaya ongkir terpisah
                'berat_total' => $totalBerat, // Simpan berat total
                'jumlah_box' => $jumlahBox, // Simpan jumlah box yang dibutuhkan
            ]);                // Create order details
            foreach ($keranjangItems as $item) {
                DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_Produk' => $item->id_Produk,
                    'ukuran_id' => $item->ukuran_id,
                    'kuantitas' => $item->jumlah,
                    'harga' => $item->ukuran && $item->ukuran->harga ? $item->ukuran->harga : $item->produk->harga,
                    'subtotal' => $item->total_harga,
                ]);

                // Update stock
                if ($item->ukuran_id) {
                    // Update ukuran stock
                    $ukuran = \App\Models\ProdukUkuran::find($item->ukuran_id);
                    $ukuran->stok -= $item->jumlah;
                    $ukuran->save();
                } else {
                    // Update product stock
                    $produk = Produk::find($item->id_Produk);
                    $produk->stok -= $item->jumlah;
                    $produk->save();
                }
            }

            // Delete selected cart items
            Keranjang::whereIn('id_keranjang', $request->selected_items)
                    ->where('user_id', Auth::id())
                    ->delete();

            // Notify admin about new order
            NotificationController::notifyAdmins([
                'type' => 'order',
                'title' => 'Pesanan Baru',
                'message' => 'Pesanan baru #' . $pesanan->id_pesanan . ' telah dibuat dan menunggu konfirmasi pembayaran.',
                'data' => [
                    'order_id' => $pesanan->id_pesanan,
                    'url' => route('admin.pesanan.show', $pesanan->id_pesanan)
                ]
            ]);

            // Special notification for fish shipment
            if ($jumlahBox > 0) {
                NotificationController::notifyAdmins([
                    'type' => 'fish-shipment',
                    'title' => 'Pengiriman Ikan Hias Memerlukan Perhatian Khusus',
                    'message' => 'Pesanan #' . $pesanan->id_pesanan . ' berisi ' . $jumlahIkan . ' ekor ikan hias yang memerlukan ' .
                                 $jumlahBox . ' box khusus. Pastikan persiapan pengiriman dilakukan dengan benar.',
                    'data' => [
                        'order_id' => $pesanan->id_pesanan,
                        'url' => route('admin.pesanan.show', $pesanan->id_pesanan),
                        'jumlah_ikan' => $jumlahIkan,
                        'jumlah_box' => $jumlahBox,
                        'kurir' => 'TIKI'
                    ]
                ]);
            }

            // Notify customer about their order
            NotificationController::notifyCustomer(Auth::id(), [
                'type' => 'order',
                'title' => 'Pesanan Berhasil Dibuat',
                'message' => 'Pesanan #' . $pesanan->id_pesanan . ' telah berhasil dibuat. Silakan lakukan pembayaran.',
                'data' => [
                    'order_id' => $pesanan->id_pesanan,
                    'url' => route('pesanan.show', $pesanan->id_pesanan)
                ]
            ]);

            DB::commit();

            return redirect()->route('pesanan.show', $pesanan->id_pesanan)
                ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal membuat pesanan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show detailed order tracking with timeline
     */
    public function tracking(Pesanan $pesanan)
    {
        // Ensure user can only view their own orders
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        $pesanan->load(['timeline', 'quarantineLog', 'refundRequests']);

        return view('pesanan.tracking', compact('pesanan'));
    }

    /**
     * Update order delivery status
     */
    public function updateDeliveryStatus(Request $request, Pesanan $pesanan)
    {
        // Ensure user can only update their own orders
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'kondisi_diterima' => 'required|in:baik,rusak',
            'catatan_penerimaan' => 'nullable|string|max:500'
        ]);

        if ($pesanan->status_pesanan !== 'Dikirim') {
            return back()->with('error', 'Pesanan belum dikirim');
        }

        $pesanan->markAsDelivered(
            $request->kondisi_diterima,
            $request->catatan_penerimaan
        );

        $message = $request->kondisi_diterima === 'baik' ?
            'Terima kasih! Pesanan telah dikonfirmasi diterima dalam kondisi baik.' :
            'Pesanan dikonfirmasi diterima dalam kondisi rusak. Tim kami akan menghubungi Anda segera.';

        return back()->with('success', $message);
    }

    /**
     * Track order using shipping API
     */
    public function trackShipping(Pesanan $pesanan)
    {
        // Ensure user can only track their own orders
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$pesanan->is_trackable) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan belum dapat dilacak'
            ]);
        }

        try {
            // Mock tracking data - replace with real TIKI API call
            $trackingData = [
                'status' => 'success',
                'data' => [
                    'waybill' => $pesanan->no_resi,
                    'status' => 'DELIVERED',
                    'history' => [
                        [
                            'date' => now()->subDays(2)->format('Y-m-d H:i:s'),
                            'description' => 'Paket telah diterima di kantor pos',
                            'location' => 'Jakarta Hub'
                        ],
                        [
                            'date' => now()->subDays(1)->format('Y-m-d H:i:s'),
                            'description' => 'Paket dalam perjalanan',
                            'location' => 'Dalam perjalanan ke ' . $pesanan->alamat->kecamatan
                        ],
                        [
                            'date' => now()->format('Y-m-d H:i:s'),
                            'description' => 'Paket telah diterima',
                            'location' => $pesanan->alamat->kecamatan
                        ]
                    ]
                ]
            ];

            // Update tracking history
            $pesanan->updateTracking($trackingData['data']);

            return response()->json([
                'success' => true,
                'data' => $trackingData['data']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melacak pesanan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show review form for completed orders
     */
    public function review(Pesanan $pesanan)
    {
        // Ensure user can only review their own orders
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        // Redirect to the ReviewController's create method
        return redirect()->route('reviews.create', $pesanan);
    }

    /**
     * Get order statistics for dashboard
     */
    public function statistics()
    {
        $userId = Auth::id();

        $stats = [
            'total_orders' => Pesanan::where('user_id', $userId)->count(),
            'pending_payment' => Pesanan::where('user_id', $userId)
                                       ->where('status_pesanan', 'Menunggu Pembayaran')
                                       ->count(),
            'in_process' => Pesanan::where('user_id', $userId)
                                  ->whereIn('status_pesanan', ['Diproses', 'Karantina'])
                                  ->count(),
            'shipped' => Pesanan::where('user_id', $userId)
                               ->where('status_pesanan', 'Dikirim')
                               ->count(),
            'completed' => Pesanan::where('user_id', $userId)
                                 ->where('status_pesanan', 'Selesai')
                                 ->count(),
            'refunded' => Pesanan::where('user_id', $userId)
                                ->where('status_refund', 'processed')
                                ->count()
        ];

        $recentOrders = Pesanan::where('user_id', $userId)
                              ->with(['detailPesanan.produk'])
                              ->orderBy('created_at', 'desc')
                              ->limit(5)
                              ->get();

        return response()->json([
            'stats' => $stats,
            'recent_orders' => $recentOrders
        ]);
    }

    /**
     * Download order invoice/receipt
     */
    public function downloadInvoice(Pesanan $pesanan)
    {
        // Ensure user can only download their own invoices
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        $pesanan->load(['detailPesanan.produk', 'user', 'alamat']);

        // For now, return a view instead of PDF until PDF package is installed
        return view('pesanan.invoice', compact('pesanan'));
    }

    /**
     * Cancel order (only if payment not confirmed)
     */
    public function cancel(Request $request, Pesanan $pesanan)
    {
        // Ensure user can only cancel their own orders
        if ($pesanan->user_id !== Auth::id()) {
            abort(403);
        }

        if ($pesanan->status_pesanan !== 'Menunggu Pembayaran') {
            return back()->with('error', 'Pesanan hanya dapat dibatalkan saat status "Menunggu Pembayaran"');
        }

        $request->validate([
            'alasan_pembatalan' => 'required|string|min:10'
        ]);

        $pesanan->update([
            'status_pesanan' => 'Dibatalkan',
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        $pesanan->addTimelineEntry(
            'Dibatalkan',
            'Pesanan Dibatalkan',
            'Pesanan dibatalkan oleh customer: ' . $request->alasan_pembatalan
        );

        // Notify admin about cancellation
        NotificationController::notifyAdmins([
            'type' => 'order-cancelled',
            'title' => 'Pesanan Dibatalkan',
            'message' => 'Pesanan #' . $pesanan->id_pesanan . ' telah dibatalkan oleh customer.',
            'data' => [
                'order_id' => $pesanan->id_pesanan,
                'reason' => $request->alasan_pembatalan
            ]
        ]);

        return redirect()->route('pesanan.index')
                        ->with('success', 'Pesanan berhasil dibatalkan');
    }

    /**
     * Show form for editing order shipping address
     */
    public function editAlamat(string $id)
    {
        $pesanan = Pesanan::where('id_pesanan', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Only allow editing address if order is still in initial stages
        if (!in_array($pesanan->status_pesanan, ['Menunggu Pembayaran', 'Pembayaran Dikonfirmasi', 'Diproses'])) {
            return redirect()->route('pesanan.show', $id)
                ->with('error', 'Alamat pengiriman tidak dapat diubah karena status pesanan sudah ' . $pesanan->status_pesanan);
        }

        return view('pesanan.edit_alamat', compact('pesanan'));
    }

    /**
     * Update order shipping address
     */
    public function updateAlamat(Request $request, string $id)
    {
        $pesanan = Pesanan::where('id_pesanan', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Only allow editing address if order is still in initial stages
        if (!in_array($pesanan->status_pesanan, ['Menunggu Pembayaran', 'Pembayaran Dikonfirmasi', 'Diproses'])) {
            return redirect()->route('pesanan.show', $id)
                ->with('error', 'Alamat pengiriman tidak dapat diubah karena status pesanan sudah ' . $pesanan->status_pesanan);
        }

        $request->validate([
            'alamat_id' => 'required|exists:alamat,id',
            'alamat_jalan' => 'required|string|max:255',
            'no_hp' => 'required|string|max:15'
        ]);

        // Get full address details
        $alamat = \App\Models\Alamat::find($request->alamat_id);
        $alamatPengiriman = $request->alamat_jalan . ', ' .
            $alamat->kecamatan . ', ' .
            $alamat->kabupaten . ', ' .
            $alamat->provinsi . ' ' .
            $alamat->kode_pos;

        // Update pesanan with new address
        $pesanan->update([
            'alamat_pengiriman' => $alamatPengiriman,
            'alamat_id' => $request->alamat_id,
            'no_hp' => $request->no_hp
        ]);

        // Log the address change in timeline
        $pesanan->addTimelineEntry(
            'alamat_diubah',
            'Alamat Pengiriman Diubah',
            'Alamat pengiriman telah diperbarui'
        );

        // Notify admin about address change
        NotificationController::notifyAdmins([
            'type' => 'order',
            'title' => 'Alamat Pengiriman Diubah',
            'message' => 'Alamat pengiriman untuk pesanan #' . $pesanan->id_pesanan . ' telah diubah oleh pelanggan.',
            'data' => [
                'order_id' => $pesanan->id_pesanan,
                'url' => route('admin.pesanan.show', $pesanan->id_pesanan)
            ]
        ]);

        return redirect()->route('pesanan.show', $id)
            ->with('success', 'Alamat pengiriman berhasil diperbarui.');
    }

    /**
     * Show form for editing shipping address during checkout
     */
    public function editCheckoutAlamat()
    {
        // Get user with address details
        $user = User::with(['alamat'])
            ->where('id', Auth::id())
            ->first();

        // Get the current address if it exists
        $alamat = null;
        if ($user->alamat_id) {
            $alamat = \App\Models\Alamat::find($user->alamat_id);
        }

        return view('pesanan.tambah_alamat', [
            'user' => $user,
            'alamat' => $alamat,
            'selected_items' => request('selected_items', [])
        ]);
    }
}
