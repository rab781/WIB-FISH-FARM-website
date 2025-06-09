<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Pesanan;
use App\Models\Expense;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengembalianController extends Controller
{
    /**
     * Display a listing of customer's returns
     */
    public function index()
    {
        $refunds = Pengembalian::where('user_id', Auth::id())
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

        return view('customer.pengembalian.index', compact('refunds', 'stats'));
    }

    /**
     * Show the form for creating a new return request
     */
    public function create($pesanan)
    {
        // If pesanan is passed as string ID, find the model
        if (is_string($pesanan)) {
            $pesanan = Pesanan::with('detailPesanan.produk')
                ->where('id_pesanan', $pesanan)
                ->where('user_id', Auth::id())
                ->firstOrFail();
        } else {
            // If pesanan is already a model instance, ensure user ownership
            if ($pesanan->user_id !== Auth::id()) {
                abort(403);
            }
            $pesanan->load('detailPesanan.produk');
        }

        // Check if order is eligible for return
        if (!in_array($pesanan->status_pesanan, ['Selesai', 'Dikirim'])) {
            return redirect()->back()->with('error', 'Hanya pesanan yang sudah selesai atau dikirim yang dapat diajukan pengembalian.');
        }

        // Check if return already exists
        $existingReturn = Pengembalian::where('id_pesanan', $pesanan->id_pesanan)->first();
        if ($existingReturn) {
            return redirect()->route('pengembalian.show', $existingReturn->id_pengembalian)
                ->with('info', 'Anda sudah mengajukan pengembalian untuk pesanan ini.');
        }

        return view('customer.pengembalian.create', compact('pesanan'));
    }

    /**
     * Store a newly created return request
     */
    public function store(Request $request, $pesanan = null)
    {
        // Dynamic validation based on refund method
        $rules = [
            'id_pesanan' => 'sometimes|required|exists:pesanan,id_pesanan',
            'jenis_keluhan' => 'required|in:Barang Rusak,Barang Tidak Sesuai,Barang Kurang,Kualitas Buruk,Lainnya',
            'deskripsi_masalah' => 'required|string|min:10|max:1000',
            'jumlah_klaim' => 'required|numeric|min:1000',
            'metode_refund' => 'required|in:bank_transfer,e_wallet',
            'foto_bukti.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];

        // Add conditional validation based on refund method
        if ($request->metode_refund === 'bank_transfer') {
            $rules['nama_bank'] = 'required|string|max:100';
            $rules['nomor_rekening'] = 'required|string|max:50';
            $rules['nama_pemilik_rekening'] = 'required|string|max:100';
        } elseif ($request->metode_refund === 'e_wallet') {
            $rules['nama_ewallet'] = 'required|string|in:GoPay,OVO,DANA,ShopeePay,LinkAja';
            $rules['nomor_ewallet'] = 'required|string|max:50';
            $rules['nama_pemilik_ewallet'] = 'required|string|max:100';
        }

        $request->validate($rules);

        // Handle pesanan parameter from route or form data
        if ($pesanan) {
            // From route parameter
            if (is_string($pesanan)) {
                $pesananModel = Pesanan::where('id_pesanan', $pesanan)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();
            } else {
                $pesananModel = $pesanan;
                if ($pesananModel->user_id !== Auth::id()) {
                    abort(403);
                }
            }
        } else {
            // From form data (backward compatibility)
            $pesananModel = Pesanan::where('id_pesanan', $request->id_pesanan)
                ->where('user_id', Auth::id())
                ->firstOrFail();
        }

        // Check if return already exists
        if (Pengembalian::where('id_pesanan', $pesananModel->id_pesanan)->exists()) {
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

        // Prepare data array for creation
        $pengembalianData = [
            'id_pesanan' => $pesananModel->id_pesanan,
            'user_id' => Auth::id(),
            'jenis_keluhan' => $request->jenis_keluhan,
            'deskripsi_masalah' => $request->deskripsi_masalah,
            'foto_bukti' => $fotoPaths,
            'jumlah_klaim' => $request->jumlah_klaim,
            'metode_refund' => $request->metode_refund,
            'status_pengembalian' => 'Menunggu Review'
        ];

        // Add bank or e-wallet specific fields
        if ($request->metode_refund === 'bank_transfer') {
            $pengembalianData['nama_bank'] = $request->nama_bank;
            $pengembalianData['nomor_rekening'] = $request->nomor_rekening;
            $pengembalianData['nama_pemilik_rekening'] = $request->nama_pemilik_rekening;
        } elseif ($request->metode_refund === 'e_wallet') {
            $pengembalianData['nama_ewallet'] = $request->nama_ewallet;
            $pengembalianData['nomor_ewallet'] = $request->nomor_ewallet;
            $pengembalianData['nama_pemilik_ewallet'] = $request->nama_pemilik_ewallet;
        }

        // Create return request
        $pengembalian = Pengembalian::create($pengembalianData);

        // Send notification to admin
        $this->notifyAdminNewRefund($pengembalian);

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

        // Pass as $refund to match the view expectations
        $refund = $pengembalian;
        return view('customer.pengembalian.show', compact('refund'));
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
        // Only check return period if tanggal_diterima is set, otherwise allow return for "Selesai" orders
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
        $refunds = Pengembalian::with(['pesanan.detailPesanan.produk', 'user'])
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

        return view('admin.pengembalian.index', compact('refunds', 'stats'));
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
        $request->validate([
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $pengembalian = Pengembalian::with(['pesanan', 'user'])
                ->where('id_pengembalian', $id)
                ->firstOrFail();

            // Update return status
            $pengembalian->update([
                'status_pengembalian' => 'Disetujui',
                'catatan_admin' => $request->catatan_admin,
                'reviewed_by' => Auth::id(),
                'tanggal_review' => now()
            ]);

            // Add timeline entry
            $this->addReturnTimeline($pengembalian, 'approved', $request->catatan_admin);

            // Create expense record for financial tracking
            $this->createRefundExpense($pengembalian);

            // Send notification to customer
            $this->notifyCustomerRefundUpdate($pengembalian, 'approved');

            DB::commit();

            return redirect()->route('admin.pengembalian.show', $pengembalian->id_pengembalian)
                ->with('success', 'Pengembalian berhasil disetujui dan dicatat dalam sistem keuangan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.pengembalian.show', $id)
                ->with('error', 'Terjadi kesalahan saat memproses persetujuan: ' . $e->getMessage());
        }
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

        // Send notification to customer
        $this->notifyCustomerRefundUpdate($pengembalian, 'rejected');

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

        // Update order status
        $pengembalian->pesanan->update([
            'status_pesanan' => 'Pengembalian',
            'updated_at' => now()
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

        if ($pengembalian->deskripsi_masalah) {
            $notes .= "Deskripsi: " . substr($pengembalian->deskripsi_masalah, 0, 200) . (strlen($pengembalian->deskripsi_masalah) > 200 ? '...' : '');
        }

        Expense::create([
            'category' => 'Pengembalian Dana',
            'description' => $description,
            'amount' => $pengembalian->jumlah_klaim,
            'expense_date' => now()->toDateString(),
            'notes' => $notes,
        ]);
    }

    /**
     * Send notification to admin for new refund request
     */
    private function notifyAdminNewRefund(Pengembalian $pengembalian)
    {
        $customerName = $pengembalian->user->name ?? 'Pelanggan';
        $orderId = $pengembalian->pesanan->id_pesanan ?? 'N/A';

        Notification::create([
            'user_id' => null, // For admin notifications
            'type' => 'refund_request',
            'title' => 'Pengajuan Pengembalian Baru',
            'message' => "Pengajuan pengembalian baru dari {$customerName} untuk pesanan #{$orderId}",
            'data' => [
                'refund_id' => $pengembalian->id_pengembalian,
                'order_id' => $orderId,
                'customer_name' => $customerName,
                'amount' => $pengembalian->jumlah_klaim,
                'complaint_type' => $pengembalian->jenis_keluhan,
                'url' => route('admin.pengembalian.show', $pengembalian->id_pengembalian)
            ],
            'is_read' => false,
            'for_admin' => true
        ]);
    }

    /**
     * Send notification to customer about refund status update
     */
    private function notifyCustomerRefundUpdate(Pengembalian $pengembalian, string $status)
    {
        $statusMessages = [
            'approved' => 'Pengajuan pengembalian Anda telah disetujui',
            'rejected' => 'Pengajuan pengembalian Anda ditolak',
            'refunded' => 'Dana pengembalian telah ditransfer'
        ];

        $message = $statusMessages[$status] ?? 'Status pengembalian Anda telah diperbarui';
        $orderId = $pengembalian->pesanan->id_pesanan ?? 'N/A';

        Notification::create([
            'user_id' => $pengembalian->user_id,
            'type' => 'refund_status_update',
            'title' => 'Update Status Pengembalian',
            'message' => "{$message} untuk pesanan #{$orderId}",
            'data' => [
                'refund_id' => $pengembalian->id_pengembalian,
                'order_id' => $orderId,
                'status' => $pengembalian->status_pengembalian,
                'amount' => $pengembalian->jumlah_klaim,
                'url' => route('pengembalian.show', $pengembalian->id_pengembalian)
            ],
            'is_read' => false,
            'for_admin' => false
        ]);
    }
}
