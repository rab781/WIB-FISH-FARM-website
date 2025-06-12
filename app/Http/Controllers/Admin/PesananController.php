<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PesananController extends Controller
{
    /**
     * Display payment proof image directly
     */
    public function viewPaymentProof(string $id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);

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
                \Illuminate\Support\Facades\Log::warning('Payment proof image not found', [
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
            \Illuminate\Support\Facades\Log::error('Error viewing payment proof', [
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pesanan::with(['user', 'detailPesanan.produk']);

        // Filter by status if provided
        if ($request->filled('status')) {
            $status = $request->status;

            // Handle grouped statuses for tabs
            if ($status == 'Menunggu Konfirmasi') {
                $query->where('status_pesanan', 'Menunggu Konfirmasi');
            } elseif ($status == 'Sedang Diproses') {
                $query->whereIn('status_pesanan', ['Sedang Diproses', 'Diproses', 'Pembayaran Dikonfirmasi']);
            } elseif ($status == 'Sedang Dikirim') {
                $query->whereIn('status_pesanan', ['Sedang Dikirim', 'Dikirim']);
            } else {
                $query->where('status_pesanan', $status);
            }
        }

        // Filter by search (order ID or customer name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id_pesanan', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter by date range
        if ($request->filled('date_range')) {
            $dateRange = $request->date_range;
            $dates = explode(' - ', $dateRange);

            if (count($dates) == 2) {
                $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[0]))->startOfDay();
                $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', trim($dates[1]))->endOfDay();

                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        // For DataTables, we want more data per page or all data
        // Check if this is an AJAX request for DataTables or normal page load
        $perPage = $request->get('per_page', 100); // Default to 100 for better DataTables performance

        // Always sort by ID desc (newest first) by default - this ensures newest orders appear first
        // Sort by ID first to ensure consistent ordering, then by created_at as secondary sort
        $pesanan = $query->orderBy('id_pesanan', 'desc')->orderBy('created_at', 'desc')->paginate($perPage);

        // Get counts for summary cards (always show total counts, not filtered)
        $totalPesanan = Pesanan::count();
        $menungguPembayaran = Pesanan::where('status_pesanan', 'Menunggu Konfirmasi')->count();
        $pembayaranDikonfirmasi = Pesanan::where('status_pesanan', 'Pembayaran Dikonfirmasi')->count();
        $sedangDiproses = Pesanan::whereIn('status_pesanan', ['Sedang Diproses', 'Diproses'])->count();
        $dikirim = Pesanan::whereIn('status_pesanan', ['Sedang Dikirim', 'Dikirim'])->count();
        $selesai = Pesanan::where('status_pesanan', 'Selesai')->count();
        $dibatalkan = Pesanan::where('status_pesanan', 'Dibatalkan')->count();
        $pengembalian = Pesanan::where('status_pesanan', 'Pengembalian')->count();

        $header = 'Manajemen Pesanan';

        return view('admin.pesanan.index', compact('pesanan', 'totalPesanan', 'menungguPembayaran',
            'pembayaranDikonfirmasi', 'sedangDiproses', 'dikirim', 'selesai', 'dibatalkan', 'pengembalian', 'header'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk', 'user', 'ongkir'])
            ->where('id_pesanan', $id)
            ->firstOrFail();

        // Load reviews for completed orders
        $reviews = collect();
        if ($pesanan->status_pesanan === 'Selesai') {
            $productIds = $pesanan->detailPesanan->pluck('id_Produk');
            $reviews = \App\Models\Ulasan::where('user_id', $pesanan->user_id)
                ->whereIn('id_Produk', $productIds)
                ->with(['user', 'produk', 'adminReplier'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $header = 'Detail Pesanan #' . $pesanan->id_pesanan;

        return view('admin.pesanan.show', compact('pesanan', 'reviews', 'header'));
    }

    /**
     * Process the order (change status to "Diproses") - SIMPLE VERSION
     */
    public function process(string $id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);

            if ($pesanan->status_pesanan !== 'Pembayaran Dikonfirmasi') {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Hanya pesanan dengan status Pembayaran Dikonfirmasi yang dapat diproses.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Hanya pesanan dengan status Pembayaran Dikonfirmasi yang dapat diproses.');
            }

            // Update status using proper quote encapsulation
            $pesanan->update([
                'status_pesanan' => 'Diproses'
            ]);

            // Return JSON response for AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil diproses.'
                ]);
            }

            return redirect()->back()->with('success', 'Pesanan berhasil diproses');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memproses pesanan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }


    /**
     * Mark order as shipped - SIMPLE VERSION
     */
    public function ship(Request $request, string $id)
    {
        $request->validate([
            'resi' => 'required|string|max:50',
        ]);

        try {
            $pesanan = Pesanan::findOrFail($id);

            // Check if order can be shipped - must be processed status
            if (!in_array($pesanan->status_pesanan, ['Diproses', 'Sedang Diproses', 'Pembayaran Dikonfirmasi'])) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Hanya pesanan dengan status Diproses yang dapat dikirim. Status saat ini: ' . $pesanan->status_pesanan
                    ], 400);
                }
                return redirect()->back()->with('error', 'Hanya pesanan dengan status Diproses yang dapat dikirim.');
            }

            // Update status and tracking number
            $pesanan->update([
                'status_pesanan' => 'Dikirim',
                'no_resi' => $request->resi,
                'tanggal_pengiriman' => now()
            ]);

            // Return JSON response for AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dikirim dengan nomor resi ' . $request->resi
                ]);
            }

            return redirect()->back()->with('success', 'Pesanan berhasil dikirim dengan nomor resi ' . $request->resi);

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim pesanan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal mengirim pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Confirm payment for an order - SIMPLE VERSION
     */
    public function confirmPayment(string $id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);

            // Allow confirmation for orders with payment proof uploaded (status still 'Menunggu Pembayaran' but bukti_pembayaran exists)
            if ($pesanan->status_pesanan !== 'Menunggu Pembayaran' || !$pesanan->bukti_pembayaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pesanan dengan bukti pembayaran yang dapat dikonfirmasi.'
                ], 400);
            }

            // Update status to Pembayaran Dikonfirmasi
            $pesanan->status_pesanan = 'Pembayaran Dikonfirmasi';
            $pesanan->tanggal_pembayaran = now();
            $pesanan->save();

            // Return JSON response for AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran pesanan berhasil dikonfirmasi.'
                ]);
            }

            return redirect()->back()->with('success', 'Pembayaran pesanan berhasil dikonfirmasi.');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengkonfirmasi pembayaran: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal mengkonfirmasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Cancel an order - SIMPLE VERSION
     */
    public function cancel(Request $request, string $id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);

            if (in_array($pesanan->status_pesanan, ['Selesai', 'Dibatalkan'])) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pesanan dengan status Selesai atau Dibatalkan tidak dapat dibatalkan.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Pesanan dengan status Selesai atau Dibatalkan tidak dapat dibatalkan.');
            }

            // Update status
            $pesanan->status_pesanan = 'Dibatalkan';
            $pesanan->alasan_pembatalan = $request->alasan_pembatalan ?? 'Dibatalkan oleh admin';
            $pesanan->save();

            // Return JSON response for AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibatalkan.'
                ]);
            }

            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membatalkan pesanan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal membatalkan pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status_pesanan' => 'required|string|in:Menunggu Pembayaran,Pembayaran Dikonfirmasi,Diproses,Dikirim,Selesai,Dibatalkan,Karantina,Pengembalian'
        ]);

        try {
            $pesanan = Pesanan::findOrFail($id);
            $oldStatus = $pesanan->status_pesanan;
            $newStatus = $request->status_pesanan;

            // Update status using proper quote encapsulation
            $pesanan->update([
                'status_pesanan' => $newStatus
            ]);

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

    /**
     * Display enhanced order details with timeline and quarantine info
     */
    public function showEnhanced(string $id)
    {
        $pesanan = Pesanan::with([
            'detailPesanan.produk',
            'user',
            'ongkir',
            'timeline',
            'quarantineLog',
            'refundRequests.reviewer'
        ])->where('id_pesanan', $id)->firstOrFail();

        return view('admin.pesanan.show-enhanced', compact('pesanan'));
    }

    /**
     * Update shipping information
     */
    public function updateShipping(Request $request, string $id)
    {
        $request->validate([
            'no_resi' => 'required|string|max:255',
            'tanggal_pengiriman' => 'nullable|date'
        ]);

        try {
            $pesanan = Pesanan::findOrFail($id);

            if (!in_array($pesanan->status_pesanan, ['Diproses', 'Karantina'])) {
                return back()->with('error', 'Pesanan harus dalam status Diproses atau selesai Karantina untuk dapat dikirim');
            }

            $pesanan->ship($request->no_resi);

            // Notify customer
            NotificationController::notifyCustomer($pesanan->user_id, [
                'type' => 'order',
                'title' => 'Pesanan Dikirim',
                'message' => "Pesanan #{$pesanan->id_pesanan} telah dikirim dengan nomor resi: {$request->no_resi}",
                'data' => [
                    'order_id' => $pesanan->id_pesanan,
                    'tracking_number' => $request->no_resi
                ]
            ]);

            return back()->with('success', 'Informasi pengiriman berhasil diupdate');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update pengiriman: ' . $e->getMessage());
        }
    }

    /**
     * Start quarantine process
     */
    public function startQuarantine(string $id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);

            if ($pesanan->status_pesanan !== 'Diproses') {
                return back()->with('error', 'Pesanan harus dalam status "Diproses" untuk memulai karantina');
            }

            if ($pesanan->is_karantina_active) {
                return back()->with('error', 'Pesanan sudah dalam masa karantina');
            }

            $pesanan->startQuarantine();

            // Notify customer
            NotificationController::notifyCustomer($pesanan->user_id, [
                'type' => 'quarantine',
                'title' => 'Karantina Dimulai',
                'message' => "Ikan dalam pesanan #{$pesanan->id_pesanan} telah memasuki periode karantina 7 hari untuk memastikan kesehatan",
                'data' => [
                    'order_id' => $pesanan->id_pesanan,
                    'quarantine_start' => $pesanan->karantina_mulai,
                    'quarantine_end' => $pesanan->karantina_selesai
                ]
            ]);

            return back()->with('success', 'Karantina 7 hari berhasil dimulai');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memulai karantina: ' . $e->getMessage());
        }
    }

    /**
     * Add manual timeline entry
     */
    public function addTimelineEntry(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'is_customer_visible' => 'boolean'
        ]);

        try {
            $pesanan = Pesanan::findOrFail($id);

            $pesanan->addTimelineEntry(
                $pesanan->status_pesanan,
                $request->title,
                $request->description,
                ['manual_entry' => true],
                $request->boolean('is_customer_visible', true)
            );

            return back()->with('success', 'Entry timeline berhasil ditambahkan');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambah timeline: ' . $e->getMessage());
        }
    }

    /**
     * Dashboard with enhanced statistics
     */
    public function dashboard()
    {
        $stats = [
            'total_orders' => Pesanan::count(),
            'pending_payment' => Pesanan::where('status_pesanan', 'Menunggu Pembayaran')->count(),
            'confirmed_payment' => Pesanan::where('status_pesanan', 'Pembayaran Dikonfirmasi')->count(),
            'in_process' => Pesanan::where('status_pesanan', 'Diproses')->count(),
            'in_quarantine' => Pesanan::where('status_pesanan', 'Karantina')->count(),
            'shipped' => Pesanan::where('status_pesanan', 'Dikirim')->count(),
            'completed' => Pesanan::where('status_pesanan', 'Selesai')->count(),
            'cancelled' => Pesanan::where('status_pesanan', 'Dibatalkan')->count(),
            'refund_requests' => \App\Models\Pengembalian::where('status_pengembalian', 'pending')->count(),
        ];

        $recentOrders = Pesanan::with(['user', 'detailPesanan'])
                              ->orderBy('created_at', 'desc')
                              ->limit(10)
                              ->get();

        $monthlyRevenue = Pesanan::where('status_pesanan', 'Selesai')
                                ->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->sum('total_harga');

        return view('admin.pesanan.dashboard', compact('stats', 'recentOrders', 'monthlyRevenue'));
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $query = Pesanan::with(['user', 'detailPesanan.produk']);

        // Apply filters if provided
        if ($request->status) {
            $query->where('status_pesanan', $request->status);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->get();

        $filename = 'orders_export_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'ID Pesanan', 'Customer', 'Email', 'Total Harga', 'Status',
                'Tanggal Dibuat', 'Tanggal Pembayaran', 'No Resi', 'Status Refund'
            ]);

            // Data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id_pesanan,
                    $order->user->name,
                    $order->user->email,
                    $order->total_harga,
                    $order->status_pesanan,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->tanggal_pembayaran?->format('Y-m-d H:i:s'),
                    $order->no_resi,
                    $order->pengembalian->count() > 0 ? 'has_refund' : 'none'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk update order statuses
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:pesanan,id_pesanan',
            'action' => 'required|in:confirm_payment,process,ship,complete,cancel'
        ]);

        try {
            $orders = Pesanan::whereIn('id_pesanan', $request->order_ids)->get();
            $updated = 0;

            foreach ($orders as $order) {
                switch ($request->action) {
                    case 'confirm_payment':
                        if ($order->status_pesanan === 'Menunggu Pembayaran') {
                            $order->update(['status_pesanan' => 'Pembayaran Dikonfirmasi']);
                            $updated++;
                        }
                        break;
                    case 'process':
                        if ($order->status_pesanan === 'Pembayaran Dikonfirmasi') {
                            $order->update(['status_pesanan' => 'Diproses']);
                            $updated++;
                        }
                        break;
                    case 'complete':
                        if ($order->status_pesanan === 'Dikirim') {
                            $order->markAsDelivered();
                            $updated++;
                        }
                        break;
                    case 'cancel':
                        if (in_array($order->status_pesanan, ['Menunggu Pembayaran', 'Pembayaran Dikonfirmasi'])) {
                            $order->update([
                                'status_pesanan' => 'Dibatalkan',
                                'alasan_pembatalan' => 'Dibatalkan oleh admin (bulk action)'
                            ]);
                            $updated++;
                        }
                        break;
                }
            }

            return back()->with('success', "{$updated} pesanan berhasil diupdate");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal bulk update: ' . $e->getMessage());
        }
    }

    /**
     * Mark an order as completed
     */
    public function complete(string $id)
    {
        try {
            $pesanan = Pesanan::findOrFail($id);

            // Only allow completion for shipped orders
            if ($pesanan->status_pesanan !== 'Dikirim') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pesanan yang sudah dikirim yang dapat diselesaikan.'
                ], 400);
            }

            // Update status to completed
            $pesanan->status_pesanan = 'Selesai';
            $pesanan->tanggal_selesai = now();
            $pesanan->save();

            // Notify customer about completion
            NotificationController::notifyCustomer($pesanan->user_id, [
                'type' => 'order',
                'title' => 'Pesanan Selesai',
                'message' => 'Pesanan #' . $pesanan->id_pesanan . ' telah selesai.',
                'data' => [
                    'order_id' => $pesanan->id_pesanan,
                    'status' => 'Selesai'
                ]
            ]);

            // Return JSON response for AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil diselesaikan.'
                ]);
            }

            return redirect()->back()->with('success', 'Pesanan berhasil diselesaikan.');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyelesaikan pesanan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menyelesaikan pesanan: ' . $e->getMessage());
        }
    }

}
