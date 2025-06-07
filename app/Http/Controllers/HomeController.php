<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Global Laravel helper functions
use function view;
use function redirect;
use function response;
use function route;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil produk-produk untuk ditampilkan di landing page
        $produk = Produk::withCount('detailPesanan')
                ->orderBy('detail_pesanan_count', 'desc')
                ->take(8)
                ->get();

        // Mengambil ulasan-ulasan terbaru untuk ditampilkan di landing page
        $ulasan = Ulasan::with(['user', 'produk'])
                    ->latest()
                    ->take(3)
                    ->get();

        return view('home.index', compact('produk', 'ulasan'));
    }

    public function produk(Request $request)
    {
        // Inisialisasi query builder
        $query = Produk::query();

        // Filter berdasarkan parameter GET jika ada
        if ($request->has('sort')) {
            if ($request->sort === 'popularity') {
                $query->withCount('detailPesanan')->orderBy('detail_pesanan_count', 'desc');
            } elseif ($request->sort === 'price_low') {
                $query->orderBy('harga', 'asc');
            } elseif ($request->sort === 'price_high') {
                $query->orderBy('harga', 'desc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        // Mengambil semua produk untuk halaman produk
        $produk = $query->paginate(12)->withQueryString();

        // Tambahkan data order_count untuk setiap produk
        $produk->each(function ($item) {
            $item->order_count = $item->detailPesanan()->count();
        });

        // Mengambil semua jenis ikan unik dari database
        $jenisIkan = Produk::distinct('jenis_ikan')
                    ->whereNotNull('jenis_ikan')
                    ->pluck('jenis_ikan')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

        return view('home.produk', compact('produk', 'jenisIkan'));
    }

    public function detailProduk($id)
    {
        // Mengambil detail produk berdasarkan ID
        $produk = Produk::findOrFail($id);

        // Mengambil ulasan untuk produk ini
        $ulasan = Ulasan::where('id_Produk', $id)
                    ->with('user')
                    ->latest()
                    ->get();

        // Menghitung rating rata-rata
        $avgRating = $ulasan->avg('rating');

        // Inisialisasi variabel untuk user yang sudah login
        $userHasPurchased = false;
        $userHasReviewed = false;

        // Cek apakah user sudah login
        if (Auth::check()) {
            $userId = Auth::id();

            // Cek apakah user sudah membeli produk ini
            $userHasPurchased = \App\Models\DetailPesanan::whereHas('pesanan', function($query) use ($userId) {
                $query->where('user_id', $userId)->whereIn('status_pesanan', ['selesai', 'dikirim']);
            })->where('id_Produk', $id)->exists();

            // Cek apakah user sudah memberikan ulasan
            $userHasReviewed = Ulasan::where('user_id', $userId)
                                ->where('id_Produk', $id)
                                ->exists();
        }

        // Produk terkait - produk dengan jenis yang sama
        $relatedProducts = Produk::where('jenis_ikan', $produk->jenis_ikan)
                            ->where('id_Produk', '!=', $id)
                            ->take(4)
                            ->get();

        return view('home.detail', compact('produk', 'ulasan', 'avgRating', 'userHasPurchased', 'userHasReviewed', 'relatedProducts'));
    }

    function tentangKami()
    {
        return view('home.tentang-kami');
    }
}
