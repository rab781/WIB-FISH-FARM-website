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
use App\Http\Controllers\NotificationController;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanan = Pesanan::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
        if ($user->provinsi_id && $user->kabupaten_id && $user->kecamatan_id && $user->alamat_jalan) {
            // Ambil data lengkap provinsi, kabupaten, dan kecamatan dengan eager loading
            $userWithRelations = User::with(['provinsi', 'kabupaten', 'kecamatan'])
                ->where('id', $user->id)
                ->first();

            $alamatLengkap = [
                'provinsi' => $userWithRelations->provinsi->nama_provinsi,
                'kabupaten' => $userWithRelations->kabupaten->nama_kabupaten,
                'kecamatan' => $userWithRelations->kecamatan->nama_kecamatan,
                'jalan' => $user->alamat_jalan,
                'kabupaten_id' => $user->kabupaten_id, // Untuk perhitungan ongkir
            ];
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

        // Ambil data provinsi untuk dropdown
        $provinsi = \App\Models\Provinsi::orderBy('nama_provinsi')->get();

        return view('pesanan.tambah_alamat', compact('user', 'provinsi'));
    }

    /**
     * Proses simpan alamat untuk checkout
     */
    public function simpanAlamat(Request $request)
    {
        $request->validate([
            'provinsi_id' => 'required|exists:provinsi,id',
            'kabupaten_id' => 'required|exists:kabupaten,id',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'alamat_jalan' => 'required|string|max:255',
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:keranjang,id_keranjang',
        ]);

        // Update data alamat user
        User::where('id', Auth::id())->update([
            'provinsi_id' => $request->provinsi_id,
            'kabupaten_id' => $request->kabupaten_id,
            'kecamatan_id' => $request->kecamatan_id,
            'alamat_jalan' => $request->alamat_jalan
        ]);

        // Redirect ke halaman checkout dengan meneruskan selected_items
        return redirect()->route('checkout', ['selected_items' => $request->selected_items])
            ->with('success', 'Alamat berhasil disimpan');
    }

    /**
     * Get ongkir berdasarkan kabupaten
     */
    public function getOngkir($kabupatanId)
    {
        // Ambil data ongkir berdasarkan kabupaten_id
        $ongkir = \App\Models\Ongkir::where('kabupaten_id', $kabupatanId)->first();

        // Ambil items yang dipilih untuk dihitung total jumlah
        $selectedItems = request('selected_items', []);
        $totalJumlah = 0;

        if (!empty($selectedItems)) {
            $keranjangItems = Keranjang::whereIn('id_keranjang', $selectedItems)
                            ->where('user_id', Auth::id())
                            ->get();

            foreach ($keranjangItems as $item) {
                $totalJumlah += $item->jumlah;
            }
        }

        // Hitung biaya ongkir
        $biayaOngkir = 0;
        if ($ongkir) {
            $biayaOngkir = $ongkir->biaya;

            // Jika jumlah ikan lebih dari 3, tambahkan 2000
            // dan tambahkan 2000 lagi untuk setiap kelipatan 3
            if ($totalJumlah > 3) {
                $tambahan = ceil(($totalJumlah - 3) / 3) * 2000;
                $biayaOngkir += $tambahan;
            }
        } else {
            // Default ongkir jika tidak ditemukan
            $biayaOngkir = 50000;
        }

        return response()->json([
            'success' => true,
            'ongkir' => $biayaOngkir,
            'jumlah_total' => $totalJumlah
        ]);
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
        ]);

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
            if (!$user->alamat_jalan || !$user->kabupaten_id) {
                return redirect()->route('alamat.tambah', ['selected_items' => $request->selected_items])
                    ->with('error', 'Harap lengkapi alamat pengiriman terlebih dahulu.');
            }

            // Get user's address with relations
            $userWithRelations = User::with(['provinsi', 'kabupaten', 'kecamatan'])
                ->where('id', Auth::id())
                ->first();

            // Construct complete address
            $alamatPengiriman = $userWithRelations->alamat_jalan . ', ' .
                                $userWithRelations->kecamatan->nama_kecamatan . ', ' .
                                $userWithRelations->kabupaten->nama_kabupaten . ', ' .
                                $userWithRelations->provinsi->nama_provinsi;

            // Calculate subtotal
            $subtotal = $keranjangItems->sum('total_harga');

            // Calculate total quantity
            $totalJumlah = $keranjangItems->sum('jumlah');

            // Get shipping cost
            $ongkir = \App\Models\Ongkir::where('kabupaten_id', $user->kabupaten_id)->first();
            $ongkirBiaya = $ongkir ? $ongkir->biaya : 50000; // Default if not found

            // Jika jumlah ikan lebih dari 3, tambahkan biaya tambahan
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
}
