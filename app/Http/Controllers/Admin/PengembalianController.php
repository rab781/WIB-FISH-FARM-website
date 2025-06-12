<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\User;
use App\Models\Expense;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        // Get statistics with efficient query
        $statsRaw = Pengembalian::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status_pengembalian = "Menunggu Review" THEN 1 ELSE 0 END) as menunggu,
            SUM(CASE WHEN status_pengembalian = "Dalam Review" THEN 1 ELSE 0 END) as dalam_review,
            SUM(CASE WHEN status_pengembalian = "Disetujui" THEN 1 ELSE 0 END) as disetujui,
            SUM(CASE WHEN status_pengembalian = "Selesai" THEN 1 ELSE 0 END) as selesai,
            SUM(CASE WHEN status_pengembalian = "Ditolak" THEN 1 ELSE 0 END) as ditolak
        ')->first();

        $stats = [
            'total' => $statsRaw->total ?? 0,
            'menunggu' => $statsRaw->menunggu ?? 0,
            'dalam_review' => $statsRaw->dalam_review ?? 0,
            'disetujui' => $statsRaw->disetujui ?? 0,
            'selesai' => $statsRaw->selesai ?? 0,
            'ditolak' => $statsRaw->ditolak ?? 0,
        ];

        $header = 'Manajemen Pengembalian';

        return view('admin.pengembalian.index', compact('pengembalian', 'stats', 'header'));
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

        try {
            DB::beginTransaction();

            $pengembalian = Pengembalian::with(['pesanan', 'user'])->findOrFail($id);

            // Update pengembalian status
            $pengembalian->update([
                'status_pengembalian' => 'Disetujui',
                'catatan_admin' => $request->catatan_admin,
                'reviewed_by' => Auth::id(),
                'tanggal_review' => now(),
            ]);

            // Create expense record for financial tracking
            $this->createRefundExpense($pengembalian);

            DB::commit();

            return redirect()->back()->with('success', 'Pengajuan pengembalian telah disetujui dan dicatat dalam sistem keuangan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses persetujuan: ' . $e->getMessage());
        }
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

        try {
            DB::beginTransaction();

            $pengembalian = Pengembalian::with(['pesanan', 'user'])->findOrFail($id);

            $pengembalian->update([
                'status_pengembalian' => 'Dana Dikembalikan',
                'nomor_transaksi_pengembalian' => $request->nomor_transaksi_pengembalian,
                'tanggal_pengembalian_dana' => now(),
                'catatan_admin' => $request->catatan_admin,
                'reviewed_by' => Auth::id(),
            ]);

            // Create expense record if not already created (for cases where refund was approved without expense tracking)
            $existingExpense = Expense::where('description', 'like', "%Order #{$pengembalian->pesanan->id_pesanan}%")
                                    ->where('category', 'Pengembalian Dana')
                                    ->where('amount', $pengembalian->jumlah_klaim)
                                    ->first();

            if (!$existingExpense) {
                $this->createRefundExpense($pengembalian);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Dana pengembalian berhasil ditandai sebagai sudah ditransfer dan dicatat dalam sistem keuangan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Create expense record for approved refund
     */
    private function createRefundExpense(Pengembalian $pengembalian)
    {
        $customerName = $pengembalian->user->name ?? 'Pelanggan';
        $orderId = $pengembalian->pesanan->id_pesanan ?? 'N/A';

        $description = "Pengembalian Dana - Order #{$orderId}";
        $notes = "Pengembalian dana untuk pelanggan: {$customerName}\n";
        $notes .= "Jenis Keluhan: {$pengembalian->jenis_keluhan}\n";
        $notes .= "ID Pengembalian: #{$pengembalian->id_pengembalian}\n";

        if ($pengembalian->catatan_admin) {
            $notes .= "Catatan Admin: {$pengembalian->catatan_admin}\n";
        }

        if ($pengembalian->deskripsi_keluhan) {
            $notes .= "Deskripsi: " . substr($pengembalian->deskripsi_keluhan, 0, 200) . (strlen($pengembalian->deskripsi_keluhan) > 200 ? '...' : '');
        }

        Expense::create([
            'category' => 'Pengembalian Dana',
            'description' => $description,
            'amount' => $pengembalian->jumlah_klaim,
            'expense_date' => now()->toDateString(),
            'notes' => $notes,
        ]);
    }
}
