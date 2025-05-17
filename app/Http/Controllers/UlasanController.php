<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NotificationController;

class UlasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ulasan = Ulasan::where('user_id', Auth::id())
            ->with('produk')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('ulasan.index', compact('ulasan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $produkId = $request->input('produk_id');

        if (!$produkId) {
            return redirect()->back()->with('error', 'ID Produk tidak ditemukan');
        }

        $produk = Produk::findOrFail($produkId);

        // Cek apakah pengguna pernah membeli produk ini
        $hasPurchased = DetailPesanan::whereHas('pesanan', function($query) {
                $query->where('user_id', Auth::id())
                    ->where('status', 'selesai');
            })
            ->where('produk_id', $produkId)
            ->exists();

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'Anda hanya dapat memberikan ulasan untuk produk yang telah Anda beli');
        }

        // Cek apakah sudah pernah memberikan ulasan
        $hasReviewed = Ulasan::where('user_id', Auth::id())
            ->where('produk_id', $produkId)
            ->exists();

        if ($hasReviewed) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini');
        }

        return view('ulasan.create', compact('produk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:10|max:500',
        ]);

        try {
            // Validate user has purchased the product
            $hasPurchased = DetailPesanan::whereHas('pesanan', function($query) {
                    $query->where('user_id', Auth::id())
                        ->where('status', 'selesai');
                })
                ->where('produk_id', $request->produk_id)
                ->exists();

            if (!$hasPurchased) {
                return redirect()->back()->with('error', 'Anda hanya dapat memberikan ulasan untuk produk yang telah Anda beli');
            }

            // Check if already reviewed
            $hasReviewed = Ulasan::where('user_id', Auth::id())
                ->where('produk_id', $request->produk_id)
                ->exists();

            if ($hasReviewed) {
                return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini');
            }

            // Create review
            $ulasan = Ulasan::create([
                'user_id' => Auth::id(),
                'produk_id' => $request->produk_id,
                'rating' => $request->rating,
                'komentar' => $request->komentar,
            ]);

            // Update product average rating
            $produk = Produk::find($request->produk_id);
            $avgRating = Ulasan::where('produk_id', $request->produk_id)->avg('rating');
            $produk->rating = round($avgRating, 1);
            $produk->save();

            // Notify admin about new review
            NotificationController::notifyAdmins([
                'type' => 'review',
                'title' => 'Ulasan Produk Baru',
                'message' => 'Pelanggan telah memberikan ulasan untuk produk "' . $produk->nama_ikan . '"',
                'data' => [
                    'review_id' => $ulasan->id,
                    'product_id' => $produk->id,
                    'rating' => $request->rating,
                    'url' => route('admin.ulasan.index')
                ]
            ]);

            return redirect()->route('produk', ['id' => $request->produk_id])
                ->with('success', 'Ulasan berhasil dikirim. Terima kasih atas masukan Anda!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengirim ulasan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ulasan = Ulasan::with(['produk', 'user'])->findOrFail($id);

        // Jika bukan admin dan bukan pemilik ulasan, batasi akses
        if (!Auth::user()->is_admin && $ulasan->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('ulasan.show', compact('ulasan'));
    }

    /**
     * Delete the specified resource.
     */
    public function destroy(string $id)
    {
        try {
            $ulasan = Ulasan::findOrFail($id);

            // Jika bukan admin dan bukan pemilik ulasan, batasi akses
            if (!Auth::user()->is_admin && $ulasan->user_id != Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            $produkId = $ulasan->produk_id;

            // Delete review
            $ulasan->delete();

            // Update product average rating
            $produk = Produk::find($produkId);
            $avgRating = Ulasan::where('produk_id', $produkId)->avg('rating') ?? 0;
            $produk->rating = round($avgRating, 1);
            $produk->save();

            if (Auth::user()->is_admin) {
                return redirect()->route('admin.ulasan.index')
                    ->with('success', 'Ulasan berhasil dihapus');
            }

            return redirect()->route('ulasan.index')
                ->with('success', 'Ulasan berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus ulasan: ' . $e->getMessage());
        }
    }
}
