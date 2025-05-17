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
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item->produk_id,
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
                'message' => 'Pesanan baru #' . $pesanan->id . ' telah dibuat dan menunggu konfirmasi pembayaran.',
                'data' => [
                    'order_id' => $pesanan->id,
                    'url' => route('admin.pesanan.show', $pesanan->id)
                ]
            ]);

            // Notify customer about their order
            NotificationController::notifyCustomer(Auth::id(), [
                'type' => 'order',
                'title' => 'Pesanan Berhasil Dibuat',
                'message' => 'Pesanan #' . $pesanan->id . ' telah berhasil dibuat. Silakan lakukan pembayaran.',
                'data' => [
                    'order_id' => $pesanan->id,
                    'url' => route('pesanan.show', $pesanan->id)
                ]
            ]);

            DB::commit();

            return redirect()->route('pesanan.show', $pesanan->id)
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
            ->where('id', $id)
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
                'message' => 'Status pesanan #' . $pesanan->id . ' telah diperbarui menjadi ' .
                            ($statusTranslations[$newStatus] ?? $newStatus),
                'data' => [
                    'order_id' => $pesanan->id,
                    'url' => route('pesanan.show', $pesanan->id),
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
     * Submit payment proof
     */
    public function submitPayment(Request $request, string $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $pesanan = Pesanan::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if ($pesanan->status != 'menunggu_pembayaran') {
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
                'message' => 'Pelanggan telah mengunggah bukti pembayaran untuk pesanan #' . $pesanan->id,
                'data' => [
                    'order_id' => $pesanan->id,
                    'url' => route('admin.pesanan.show', $pesanan->id)
                ]
            ]);

            return redirect()->back()
                ->with('success', 'Bukti pembayaran berhasil diunggah. Mohon tunggu konfirmasi dari admin.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengunggah bukti pembayaran: ' . $e->getMessage());
        }
    }
}
