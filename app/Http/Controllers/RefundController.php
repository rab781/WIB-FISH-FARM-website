<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RefundController extends Controller
{
    /**
     * Process timeline entries for refund status changes
     */
    private function addRefundTimeline(RefundRequest $refund, string $status, string $notes = null)
    {
        $statusMap = [
            'approved' => ['Refund Approved', 'Refund Disetujui'],
            'rejected' => ['Refund Rejected', 'Refund Ditolak'],
            'processing' => ['Refund Processing', 'Refund Sedang Diproses'],
            'completed' => ['Refund Completed', 'Refund Selesai']
        ];

        if (isset($statusMap[$status])) {
            $refund->pesanan->addTimelineEntry(
                $statusMap[$status][0],
                $statusMap[$status][1],
                $notes ?? 'Status refund diperbarui ke ' . $status
            );
        }
    }

    public function store(Request $request, Pesanan $pesanan)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jenis_refund' => 'required|in:kerusakan,keterlambatan,tidak_sesuai,kematian_ikan,lainnya',
                'deskripsi_masalah' => 'required|string|min:10',
                'jumlah_diminta' => 'required|numeric|min:0|max:' . $pesanan->total_harga,
                'metode_refund' => 'required|string',
                'detail_refund' => 'required|string',
                'bukti_pendukung.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            // Remove temporary check when testing is complete
            /*
            // Ensure only completed orders with delivery confirmation can be refunded
            if ($pesanan->status_pesanan !== 'Selesai') {
                $errorMessage = 'Hanya pesanan yang telah selesai yang dapat mengajukan pengembalian';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $errorMessage], 422);
                }
                return back()->with('error', $errorMessage);
            }

            // Ensure order is within 24 hours of delivery
            if (!$pesanan->tanggal_diterima || $pesanan->tanggal_diterima->addHours(24)->isPast()) {
                $errorMessage = 'Permintaan pengembalian hanya dapat diajukan dalam 24 jam setelah pesanan diterima';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $errorMessage], 422);
                }
                return back()->with('error', $errorMessage);
            }

            if (!$pesanan->canRequestRefund()) {
                $errorMessage = 'Pesanan tidak dapat mengajukan refund';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $errorMessage], 422);
                }
                return back()->with('error', $errorMessage);
            }
            */

            $buktiFiles = [];
            if ($request->hasFile('bukti_pendukung')) {
                foreach ($request->file('bukti_pendukung') as $file) {
                    $fileName = 'refund_' . $pesanan->id_pesanan . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('refunds', $fileName, 'public');
                    $buktiFiles[] = $path;
                }
            }

            $refundData = $request->only([
                'jenis_refund', 'deskripsi_masalah', 'jumlah_diminta',
                'metode_refund', 'detail_refund'
            ]);

            $refundData['bukti_pendukung'] = $buktiFiles;
            $refundData['status'] = 'pending'; // Set default status

            // Create refund request
            $refund = $pesanan->requestRefund($refundData);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permintaan refund berhasil diajukan',
                    'refund_id' => $refund->id
                ]);
            }

            return back()->with('success', 'Permintaan refund berhasil diajukan');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Refund request error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
                'pesanan_id' => $pesanan->id_pesanan
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function review(Request $request, RefundRequest $refund)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
            'catatan_admin' => 'required|string|min:10',
            'jumlah_disetujui' => 'nullable|numeric|min:0|max:' . $refund->jumlah_diminta
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
            'reviewed_at' => now(),
            'reviewed_by' => \Illuminate\Support\Facades\Auth::id()
        ];

        if ($request->status === 'approved') {
            $updateData['jumlah_disetujui'] = $request->jumlah_disetujui ?? $refund->jumlah_diminta;
        }

        $refund->update($updateData);

        // Update pesanan status
        $refund->pesanan->update([
            'status_refund' => $request->status,
            'catatan_admin_refund' => $request->catatan_admin,
            'jumlah_refund' => $updateData['jumlah_disetujui'] ?? null
        ]);

        // Add timeline entry
        $this->addRefundTimeline($refund, $request->status, $request->catatan_admin);

        $statusText = $request->status === 'approved' ? 'disetujui' : 'ditolak';
        return back()->with('success', "Permintaan refund berhasil {$statusText}");
    }

    public function process(Request $request, RefundRequest $refund)
    {
        $status = $request->input('status');
        $allowedStatuses = ['approved', 'processing', 'completed'];

        if (!in_array($status, $allowedStatuses)) {
            return back()->with('error', 'Status tidak valid');
        }

        if ($status === 'processing' && $refund->status !== 'approved') {
            return back()->with('error', 'Hanya refund yang disetujui yang dapat diproses');
        }

        if ($status === 'completed' && $refund->status !== 'processing') {
            return back()->with('error', 'Hanya refund yang sedang diproses yang dapat diselesaikan');
        }

        $updateData = ['status' => $status];

        if ($status === 'processing') {
            $updateData['processed_at'] = now();
        } else if ($status === 'completed') {
            $updateData['completed_at'] = now();
        }

        $refund->update($updateData);

        $refund->pesanan->update([
            'status_refund' => $status,
            'tanggal_refund_' . $status => now()
        ]);

        // Add timeline entry
        $this->addRefundTimeline($refund, $status);

        return back()->with('success', 'Refund berhasil diproses');
    }

    public function customerIndex()
    {
        $refunds = RefundRequest::whereHas('pesanan', function($q) {
                                    $q->where('user_id', \Illuminate\Support\Facades\Auth::id());
                                })
                                ->with('pesanan')
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);

        return view('customer.refunds.index', compact('refunds'));
    }

    public function customerShow(RefundRequest $refund)
    {
        // Ensure user can only view their own refunds
        if ($refund->pesanan->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
        }

        $refund->load(['pesanan.detailPesanan.produk', 'reviewer']);
        return view('customer.refunds.show', compact('refund'));
    }

    public function customerCreate(Pesanan $pesanan)
    {
        // Ensure user can only create refund for their own orders
        if ($pesanan->user_id !== \Illuminate\Support\Facades\Auth::id()) {
            abort(403);
        }

        // Check if the order is eligible for return (within 24 hours of delivery)
        if (!$pesanan->is_eligible_for_return) {
            return back()->with('error', 'Permintaan pengembalian hanya dapat diajukan dalam 24 jam setelah pesanan diterima');
        }

        if (!$pesanan->canRequestRefund()) {
            return back()->with('error', 'Pesanan tidak dapat mengajukan refund');
        }

        return view('customer.refunds.create', compact('pesanan'));
    }

    /**
     * Admin view for refunds listing. Displays a paginated list of all refund requests
     * with summary statistics.
     */
    public function adminIndex()
    {
        // Eager load relationships to reduce queries
        $refunds = RefundRequest::with(['pesanan.user', 'reviewer'])
                               ->orderBy('created_at', 'desc')
                               ->paginate(10);

        // Get status counts for statistics
        $stats = [
            'pending' => RefundRequest::where('status', 'pending')->count(),
            'approved' => RefundRequest::where('status', 'approved')->count(),
            'rejected' => RefundRequest::where('status', 'rejected')->count(),
            'processing' => RefundRequest::where('status', 'processing')->count(),
            'completed' => RefundRequest::where('status', 'completed')->count(),
            'total' => RefundRequest::count(),
            'total_amount' => RefundRequest::where('status', ['approved', 'processing', 'completed'])
                                         ->sum('jumlah_disetujui')
        ];

        return view('admin.refunds.index', compact('refunds', 'stats'));
    }

    /**
     * Admin view for single refund request with detailed information
     */
    public function adminShow(RefundRequest $refund)
    {
        // Eager load all necessary relationships
        $refund->load([
            'pesanan.user',
            'pesanan.detailPesanan.produk',
            'pesanan.timeline',
            'reviewer'
        ]);

        $stats = [
            'total_customer_refunds' => RefundRequest::whereHas('pesanan', function($q) use ($refund) {
                $q->where('user_id', $refund->pesanan->user_id);
            })->count(),
            'customer_approved_refunds' => RefundRequest::whereHas('pesanan', function($q) use ($refund) {
                $q->where('user_id', $refund->pesanan->user_id);
            })->where('status', ['approved', 'processing', 'completed'])->count()
        ];

        return view('admin.refunds.show', compact('refund', 'stats'));
    }

    /**
     * Dashboard stats for admin
     */
    public function dashboardStats()
    {
        $stats = [
            'total_refunds' => RefundRequest::count(),
            'pending_refunds' => RefundRequest::where('status', 'pending')->count(),
            'approved_refunds' => RefundRequest::where('status', 'approved')->count(),
            'processed_refunds' => RefundRequest::where('status', 'processed')->count(),
            'rejected_refunds' => RefundRequest::where('status', 'rejected')->count(),
            'total_refund_amount' => RefundRequest::where('status', 'processed')->sum('jumlah_disetujui'),
        ];

        return response()->json($stats);
    }

    /**
     * Export refunds data
     */
    public function export(Request $request)
    {
        $refunds = RefundRequest::with(['pesanan.user', 'reviewer'])
                               ->orderBy('created_at', 'desc')
                               ->get();

        return response()->json(['data' => $refunds]);
    }
}
