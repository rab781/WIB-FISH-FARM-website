<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
{
    /**
     * Display a listing of all return requests
     */
    public function index(Request $request)
    {
        $query = Pengembalian::with(['pesanan.detailPesanan.produk', 'user', 'reviewedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_pengembalian', $request->status);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('pesanan', function($q2) use ($search) {
                    $q2->where('id_pesanan', 'like', "%{$search}%");
                })
                ->orWhereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('jenis_keluhan', 'like', "%{$search}%");
            });
        }

        $pengembalian = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total' => Pengembalian::count(),
            'pending' => Pengembalian::where('status_pengembalian', 'Menunggu Review')->count(),
            'in_review' => Pengembalian::where('status_pengembalian', 'Dalam Review')->count(),
            'approved' => Pengembalian::where('status_pengembalian', 'Disetujui')->count(),
            'completed' => Pengembalian::where('status_pengembalian', 'Selesai')->count(),
        ];

        return view('admin.pengembalian.index', compact('pengembalian', 'stats'));
    }

    /**
     * Display the specified return request
     */
    public function show($id)
    {
        $pengembalian = Pengembalian::with(['pesanan.detailPesanan.produk', 'user', 'reviewedBy'])
            ->findOrFail($id);

        return view('admin.pengembalian.show', compact('pengembalian'));
    }

    /**
     * Update the status of return request
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pengembalian' => 'required|in:Menunggu Review,Dalam Review,Disetujui,Ditolak,Dana Dikembalikan,Selesai',
            'catatan_admin' => 'nullable|string|max:1000',
            'nomor_transaksi_pengembalian' => 'nullable|string|max:100'
        ]);

        $pengembalian = Pengembalian::findOrFail($id);

        $updateData = [
            'status_pengembalian' => $request->status_pengembalian,
            'catatan_admin' => $request->catatan_admin,
            'reviewed_by' => Auth::id(),
            'tanggal_review' => now(),
        ];

        // If status is "Dana Dikembalikan", add transaction details
        if ($request->status_pengembalian === 'Dana Dikembalikan') {
            $updateData['tanggal_pengembalian_dana'] = now();
            $updateData['nomor_transaksi_pengembalian'] = $request->nomor_transaksi_pengembalian;
        }

        $pengembalian->update($updateData);

        // Send notification to user (you can implement email notification here)

        $message = match($request->status_pengembalian) {
            'Dalam Review' => 'Pengajuan pengembalian sedang ditinjau oleh tim kami.',
            'Disetujui' => 'Pengajuan pengembalian Anda telah disetujui. Dana akan segera dikembalikan.',
            'Ditolak' => 'Pengajuan pengembalian ditolak. Silakan lihat catatan admin untuk detail.',
            'Dana Dikembalikan' => 'Dana pengembalian telah ditransfer ke rekening Anda.',
            'Selesai' => 'Proses pengembalian telah selesai.',
            default => 'Status pengembalian telah diperbarui.'
        };

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Approve return request
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string|max:1000'
        ]);

        $pengembalian = Pengembalian::findOrFail($id);

        $pengembalian->update([
            'status_pengembalian' => 'Disetujui',
            'catatan_admin' => $request->catatan_admin,
            'reviewed_by' => Auth::id(),
            'tanggal_review' => now(),
        ]);

        return redirect()->back()->with('success', 'Pengajuan pengembalian telah disetujui.');
    }

    /**
     * Reject return request
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:1000'
        ]);

        $pengembalian = Pengembalian::findOrFail($id);

        $pengembalian->update([
            'status_pengembalian' => 'Ditolak',
            'catatan_admin' => $request->catatan_admin,
            'reviewed_by' => Auth::id(),
            'tanggal_review' => now(),
        ]);

        return redirect()->back()->with('success', 'Pengajuan pengembalian telah ditolak.');
    }

    /**
     * Mark refund as transferred
     */
    public function markRefunded(Request $request, $id)
    {
        $request->validate([
            'nomor_transaksi_pengembalian' => 'required|string|max:100',
            'catatan_admin' => 'nullable|string|max:1000'
        ]);

        $pengembalian = Pengembalian::findOrFail($id);

        $pengembalian->update([
            'status_pengembalian' => 'Dana Dikembalikan',
            'nomor_transaksi_pengembalian' => $request->nomor_transaksi_pengembalian,
            'tanggal_pengembalian_dana' => now(),
            'catatan_admin' => $request->catatan_admin,
            'reviewed_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Dana pengembalian berhasil ditandai sebagai sudah ditransfer.');
    }
}
