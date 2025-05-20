<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanan = Pesanan::with(['user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.pesanan.index', compact('pesanan'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk', 'user', 'ongkir'])
            ->where('id_pesanan', $id)
            ->firstOrFail();

        return view('admin.pesanan.show', compact('pesanan'));
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status_pesanan' => 'required|string|in:Menunggu Pembayaran,Pembayaran Dikonfirmasi,Diproses,Dikirim,Selesai,Dibatalkan'
        ]);

        try {
            $pesanan = Pesanan::findOrFail($id);
            $oldStatus = $pesanan->status_pesanan;
            $newStatus = $request->status_pesanan;

            // Update status
            $pesanan->status_pesanan = $newStatus;
            $pesanan->save();

            // Notify customer about status change
            NotificationController::notifyCustomer($pesanan->user_id, [
                'type' => 'order',
                'title' => 'Status Pesanan Diperbarui',
                'message' => 'Status pesanan #' . $pesanan->id_pesanan . ' telah diperbarui menjadi ' . $newStatus,
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
     * Force expiration of order to test auto-cancellation
     */
    public function forceExpireOrder(string $id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);

            if ($pesanan->status_pesanan != 'Menunggu Pembayaran') {
                return redirect()->back()
                    ->with('error', 'Hanya pesanan dengan status Menunggu Pembayaran yang dapat diuji kedaluwarsa.');
            }

            // Update batas waktu to one hour ago
            $pesanan->batas_waktu = now()->subHour();
            $pesanan->save();

            // Run the expired orders check command
            \Illuminate\Support\Facades\Artisan::call('app:check-expired-orders');

            // Refresh the order data
            $pesanan->refresh();

            if ($pesanan->status_pesanan === 'Dibatalkan') {
                return redirect()->back()
                    ->with('success', 'Pesanan berhasil diuji kedaluwarsa dan telah dibatalkan.');
            } else {
                return redirect()->back()
                    ->with('warning', 'Pesanan telah diset kedaluwarsa tetapi tidak dibatalkan secara otomatis. Status saat ini: ' . $pesanan->status_pesanan);
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menguji kedaluwarsa pesanan: ' . $e->getMessage());
        }
    }
}
