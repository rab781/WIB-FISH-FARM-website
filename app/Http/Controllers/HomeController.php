<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil produk-produk untuk ditampilkan di landing page
        $produk = Produk::latest()->take(8)->get();

        // Mengambil ulasan-ulasan terbaru untuk ditampilkan di landing page
        $ulasan = Ulasan::with(['user', 'produk'])
                    ->latest()
                    ->take(4)
                    ->get();

        return view('home.index', compact('produk', 'ulasan'));
    }

    public function produk()
    {
        // Mengambil semua produk untuk halaman produk
        $produk = Produk::latest()->paginate(12);

        return view('home.produk', compact('produk'));
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
}
