<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PengembalianController extends Controller
{
    /**
     * Display a listing of customer's returns
     */
    public function index()
    {
        $pengembalian = Pengembalian::where('user_id', Auth::id())
            ->with(['pesanan.detailPesanan.produk'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Statistik untuk halaman index
        $stats = [
            'total' => Pengembalian::where('user_id', Auth::id())->count(),
            'pending' => Pengembalian::where('user_id', Auth::id())
                ->where('status_pengembalian', 'Menunggu Review')->count(),
            'approved' => Pengembalian::where('user_id', Auth::id())
                ->whereIn('status_pengembalian', ['Disetujui', 'Dana Dikembalikan'])->count(),
            'rejected' => Pengembalian::where('user_id', Auth::id())
                ->where('status_pengembalian', 'Ditolak')->count(),
            'processed' => Pengembalian::where('user_id', Auth::id())
                ->where('status_pengembalian', 'Dalam Review')->count(),
            'completed' => Pengembalian::where('user_id', Auth::id())
                ->where('status_pengembalian', 'Selesai')->count(),
        ];

        return view('pengembalian.index', compact('pengembalian', 'stats'));
    }

    /**
     * Show the form for creating a new return request
     */
    public function create($id_pesanan)
    {
        $pesanan = Pesanan::with('detailPesanan.produk')
            ->where('id_pesanan', $id_pesanan)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if order is eligible for return
        if (!in_array($pesanan->status_pesanan, ['Selesai', 'Dikirim'])) {
            return redirect()->back()->with('error', 'Hanya pesanan yang sudah selesai atau dikirim yang dapat diajukan pengembalian.');
        }

        // Check if return already exists
        $existingReturn = Pengembalian::where('id_pesanan', $id_pesanan)->first();
        if ($existingReturn) {
            return redirect()->route('pengembalian.show', $existingReturn->id_pengembalian)
                ->with('info', 'Anda sudah mengajukan pengembalian untuk pesanan ini.');
        }

        return view('pengembalian.create', compact('pesanan'));
    }

    /**
     * Store a newly created return request
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pesanan' => 'required|exists:pesanan,id_pesanan',
            'jenis_keluhan' => 'required|in:Barang Rusak,Barang Tidak Sesuai,Barang Kurang,Kualitas Buruk,Lainnya',
            'deskripsi_masalah' => 'required|string|min:10|max:1000',
            'jumlah_klaim' => 'required|numeric|min:1000',
            'nama_bank' => 'required|string|max:100',
            'nomor_rekening' => 'required|string|max:50',
            'nama_pemilik_rekening' => 'required|string|max:100',
            'foto_bukti.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Verify pesanan ownership
        $pesanan = Pesanan::where('id_pesanan', $request->id_pesanan)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Check if return already exists
        if (Pengembalian::where('id_pesanan', $request->id_pesanan)->exists()) {
            return redirect()->back()->with('error', 'Pengembalian untuk pesanan ini sudah pernah diajukan.');
        }

        // Handle photo uploads
        $fotoPaths = [];
        if ($request->hasFile('foto_bukti')) {
            foreach ($request->file('foto_bukti') as $foto) {
                $filename = 'pengembalian_' . time() . '_' . Str::random(10) . '.' . $foto->getClientOriginalExtension();
                $path = $foto->storeAs('pengembalian', $filename, 'public');
                $fotoPaths[] = $path;
            }
        }

        // Create return request
        $pengembalian = Pengembalian::create([
            'id_pesanan' => $request->id_pesanan,
            'user_id' => Auth::id(),
            'jenis_keluhan' => $request->jenis_keluhan,
            'deskripsi_masalah' => $request->deskripsi_masalah,
            'foto_bukti' => $fotoPaths,
            'jumlah_klaim' => $request->jumlah_klaim,
            'nama_bank' => $request->nama_bank,
            'nomor_rekening' => $request->nomor_rekening,
            'nama_pemilik_rekening' => $request->nama_pemilik_rekening,
            'status_pengembalian' => 'Menunggu Review'
        ]);

        return redirect()->route('pengembalian.show', $pengembalian->id_pengembalian)
            ->with('success', 'Pengajuan pengembalian berhasil dikirim. Tim kami akan meninjau dalam 1-3 hari kerja.');
    }

    /**
     * Display the specified return request
     */
    public function show($id)
    {
        $pengembalian = Pengembalian::with(['pesanan.detailPesanan.produk', 'reviewedBy'])
            ->where('id_pengembalian', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('pengembalian.show', compact('pengembalian'));
    }

    /**
     * Check if order is eligible for return
     */
    public function checkEligibility($id_pesanan)
    {
        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)
            ->where('user_id', Auth::id())
            ->first();

        if (!$pesanan) {
            return response()->json(['eligible' => false, 'message' => 'Pesanan tidak ditemukan.']);
        }

        if (!in_array($pesanan->status_pesanan, ['Selesai', 'Dikirim'])) {
            return response()->json(['eligible' => false, 'message' => 'Hanya pesanan yang sudah selesai yang dapat diajukan pengembalian.']);
        }

        $existingReturn = Pengembalian::where('id_pesanan', $id_pesanan)->first();
        if ($existingReturn) {
            return response()->json(['eligible' => false, 'message' => 'Pengembalian untuk pesanan ini sudah pernah diajukan.']);
        }

        // Check if it's within return period (e.g., 30 days after completion)
        if ($pesanan->tanggal_diterima && $pesanan->tanggal_diterima->addDays(30)->isPast()) {
            return response()->json(['eligible' => false, 'message' => 'Periode pengembalian sudah berakhir (maksimal 30 hari setelah pesanan selesai).']);
        }

        return response()->json(['eligible' => true, 'message' => 'Pesanan dapat diajukan pengembalian.']);
    }

    /**
     * Process timeline entries for refund/return status changes
     */
    private function addReturnTimeline(Pengembalian $pengembalian, string $status, string $notes = null)
    {
        $statusMap = [
            'approved' => ['Return Approved', 'Pengembalian Disetujui'],
            'rejected' => ['Return Rejected', 'Pengembalian Ditolak'],
            'processing' => ['Return Processing', 'Pengembalian Sedang Diproses'],
            'completed' => ['Return Completed', 'Pengembalian Selesai']
        ];

        if (isset($statusMap[$status])) {
            $pengembalian->pesanan->addTimelineEntry(
                $statusMap[$status][0],
                $statusMap[$status][1],
                $notes ?? 'Status pengembalian diperbarui ke ' . $status
            );
        }
    }

    /**
     * Display list of returns in admin dashboard
     */
    public function adminIndex()
    {
        $pengembalian = Pengembalian::with(['pesanan.detailPesanan.produk', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Statistics for admin dashboard
        $stats = [
            'total' => Pengembalian::count(),
            'pending' => Pengembalian::where('status_pengembalian', 'Menunggu Review')->count(),
            'approved' => Pengembalian::whereIn('status_pengembalian', ['Disetujui', 'Dana Dikembalikan'])->count(),
            'rejected' => Pengembalian::where('status_pengembalian', 'Ditolak')->count(),
            'processed' => Pengembalian::where('status_pengembalian', 'Dalam Review')->count(),
            'completed' => Pengembalian::where('status_pengembalian', 'Selesai')->count(),
        ];

        return view('admin.pengembalian.index', compact('pengembalian', 'stats'));
    }

    /**
     * Show details of a return request in admin dashboard
     */
    public function adminShow($id)
    {
        $pengembalian = Pengembalian::with(['pesanan.detailPesanan.produk', 'user', 'reviewedBy'])
            ->where('id_pengembalian', $id)
            ->firstOrFail();

        return view('admin.pengembalian.show', compact('pengembalian'));
    }

    /**
     * Approve a return request
     */
    public function approve(Request $request, $id)
    {
        $pengembalian = Pengembalian::with('pesanan')
            ->where('id_pengembalian', $id)
            ->firstOrFail();

        $request->validate([
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        // Update return status
        $pengembalian->update([
            'status_pengembalian' => 'Disetujui',
            'catatan_admin' => $request->catatan_admin,
            'reviewed_by' => Auth::id(),
            'tanggal_review' => now()
        ]);

        // Add timeline entry
        $this->addReturnTimeline($pengembalian, 'approved', $request->catatan_admin);

        return redirect()->route('admin.pengembalian.show', $pengembalian->id_pengembalian)
            ->with('success', 'Pengembalian berhasil disetujui.');
    }

    /**
     * Reject a return request
     */
    public function reject(Request $request, $id)
    {
        $pengembalian = Pengembalian::with('pesanan')
            ->where('id_pengembalian', $id)
            ->firstOrFail();

        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ]);

        // Update return status
        $pengembalian->update([
            'status_pengembalian' => 'Ditolak',
            'catatan_admin' => $request->catatan_admin,
            'reviewed_by' => Auth::id(),
            'tanggal_review' => now()
        ]);

        // Add timeline entry
        $this->addReturnTimeline($pengembalian, 'rejected', $request->catatan_admin);

        return redirect()->route('admin.pengembalian.show', $pengembalian->id_pengembalian)
            ->with('success', 'Pengembalian telah ditolak.');
    }

    /**
     * Mark a return as refunded
     */
    public function markRefunded(Request $request, $id)
    {
        $pengembalian = Pengembalian::with('pesanan')
            ->where('id_pengembalian', $id)
            ->firstOrFail();

        $request->validate([
            'nomor_transaksi_pengembalian' => 'required|string|max:100',
        ]);

        // Update return status
        $pengembalian->update([
            'status_pengembalian' => 'Dana Dikembalikan',
            'nomor_transaksi_pengembalian' => $request->nomor_transaksi_pengembalian,
            'tanggal_pengembalian_dana' => now(),
        ]);

        // Update order refund status
        $pengembalian->pesanan->update([
            'status_refund' => 'processed',
            'tanggal_refund_processed' => now()
        ]);

        // Add timeline entry
        $this->addReturnTimeline(
            $pengembalian, 
            'completed', 
            'Dana telah dikembalikan. Nomor transaksi: ' . $request->nomor_transaksi_pengembalian
        );

        return redirect()->route('admin.pengembalian.show', $pengembalian->id_pengembalian)
            ->with('success', 'Pengembalian dana berhasil dicatat.');
    }
}
