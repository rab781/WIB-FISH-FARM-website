<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Ulasan;
use Illuminate\Http\Request;

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
        
        return view('LandingPage', compact('produk', 'ulasan'));
    }
    
    public function produk()
    {
        // Mengambil semua produk untuk halaman produk
        $produk = Produk::latest()->paginate(12);
        
        return view('Produk', compact('produk'));
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
        
        return view('home.detail', compact('produk', 'ulasan', 'avgRating'));
    }
}
