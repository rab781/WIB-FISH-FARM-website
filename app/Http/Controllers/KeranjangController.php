<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil keranjang belanja milik user yang sedang login
        $keranjang = Keranjang::where('user_id', Auth::id())
                    ->with('produk')
                    ->get();
        
        // Menghitung total harga keranjang
        $totalHarga = $keranjang->sum('total_harga');
        
        return view('keranjang.index', compact('keranjang', 'totalHarga'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_Produk' => 'required|exists:produk,id_Produk',
            'jumlah' => 'required|integer|min:1',
        ]);
        
        // Ambil data produk
        $produk = Produk::findOrFail($request->id_Produk);
        
        // Cek stok
        if ($produk->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi');
        }
        
        // Cek apakah produk sudah ada di keranjang
        $keranjang = Keranjang::where('user_id', Auth::id())
                    ->where('id_Produk', $request->id_Produk)
                    ->first();
        
        // Jika sudah ada, update jumlah dan total harga
        if ($keranjang) {
            $keranjang->jumlah += $request->jumlah;
            $keranjang->total_harga = $keranjang->jumlah * $produk->harga;
            $keranjang->save();
        } else {
            // Jika belum ada, buat baru
            Keranjang::create([
                'user_id' => Auth::id(),
                'id_Produk' => $request->id_Produk,
                'jumlah' => $request->jumlah,
                'total_harga' => $request->jumlah * $produk->harga,
            ]);
        }
        
        return redirect()->route('keranjang.index')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
        ]);
        
        $keranjang = Keranjang::findOrFail($id);
        
        // Cek kepemilikan
        if ($keranjang->user_id != Auth::id()) {
            return redirect()->route('keranjang.index')->with('error', 'Anda tidak memiliki akses');
        }
        
        // Ambil data produk
        $produk = Produk::findOrFail($keranjang->id_Produk);
        
        // Cek stok
        if ($produk->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi');
        }
        
        // Update jumlah dan total harga
        $keranjang->jumlah = $request->jumlah;
        $keranjang->total_harga = $request->jumlah * $produk->harga;
        $keranjang->save();
        
        return redirect()->route('keranjang.index')->with('success', 'Keranjang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $keranjang = Keranjang::findOrFail($id);
        
        // Cek kepemilikan
        if ($keranjang->user_id != Auth::id()) {
            return redirect()->route('keranjang.index')->with('error', 'Anda tidak memiliki akses');
        }
        
        // Hapus item dari keranjang
        $keranjang->delete();
        
        return redirect()->route('keranjang.index')->with('success', 'Produk berhasil dihapus dari keranjang');
    }
}
