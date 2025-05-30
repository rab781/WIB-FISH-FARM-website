<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RefundController extends Controller
{
    public function index()
    {
        $refunds = RefundRequest::with(['pesanan.user', 'reviewer'])
                               ->orderBy('created_at', 'desc')
                               ->paginate(10);

        return view('admin.refunds.index', compact('refunds'));
    }

    public function show(RefundRequest $refund)
    {
        $refund->load(['pesanan.user', 'pesanan.detailPesanan.produk', 'reviewer']);
        return view('admin.refunds.show', compact('refund'));
    }

    public function store(Request $request, Pesanan $pesanan)
    {
        $validator = Validator::make($request->all(), [
            'jenis_refund' => 'required|in:kerusakan,keterlambatan,tidak_sesuai,kematian_ikan,lainnya',
            'deskripsi_masalah' => 'required|string|min:10',
            'jumlah_diminta' => 'required|numeric|min:0|max:' . $pesanan->total_harga,
            'metode_refund' => 'required|string',
            'detail_refund' => 'required|string',
            'bukti_pendukung.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (!$pesanan->canRequestRefund()) {
            return back()->with('error', 'Pesanan tidak dapat mengajukan refund');
        }

        $buktiFiles = [];
        if ($request->hasFile('bukti_pendukung')) {
            foreach ($request->file('bukti_pendukung') as $file) {
                $path = $file->store('refunds', 'public');
                $buktiFiles[] = $path;
            }
        }

        $refundData = $request->only([
            'jenis_refund', 'deskripsi_masalah', 'jumlah_diminta',
            'metode_refund', 'detail_refund'
        ]);

        $refundData['bukti_pendukung'] = $buktiFiles;

        $pesanan->requestRefund($refundData);

        return back()->with('success', 'Permintaan refund berhasil diajukan');
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
        $refund->pesanan->addTimelineEntry(
            'Refund ' . ucfirst($request->status),
            'Refund ' . ($request->status === 'approved' ? 'Disetujui' : 'Ditolak'),
            $request->catatan_admin
        );

        $statusText = $request->status === 'approved' ? 'disetujui' : 'ditolak';
        return back()->with('success', "Permintaan refund berhasil {$statusText}");
    }

    public function process(RefundRequest $refund)
    {
        if ($refund->status !== 'approved') {
            return back()->with('error', 'Hanya refund yang disetujui yang dapat diproses');
        }

        $refund->update([
            'status' => 'processed',
            'processed_at' => now()
        ]);

        $refund->pesanan->update([
            'status_refund' => 'processed',
            'tanggal_refund_processed' => now()
        ]);

        // Add timeline entry
        $refund->pesanan->addTimelineEntry(
            'Refund Processed',
            'Refund Diproses',
            'Refund telah diproses dan dana akan segera dikembalikan'
        );

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

        if (!$pesanan->canRequestRefund()) {
            return back()->with('error', 'Pesanan tidak dapat mengajukan refund');
        }

        return view('customer.refunds.create', compact('pesanan'));
    }
}
