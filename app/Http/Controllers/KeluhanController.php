<?php

namespace App\Http\Controllers;

use App\Models\Keluhan; // Pastikan ini benar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KeluhanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $keluhans = Keluhan::where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        // Path view yang benar berdasarkan controller Anda
        return view('customer.keluhan.index', compact('keluhans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Definisikan jenis keluhan yang tersedia
        $jenisKeluhan = [
            'Sistem' => 'Masalah pada sistem/aplikasi',
            'Produk' => 'Keluhan terkait produk',
            'Pengiriman' => 'Masalah pengiriman',
            'Pelayanan' => 'Keluhan terhadap pelayanan',
            'Pembayaran' => 'Masalah pembayaran',
            'Lainnya' => 'Keluhan lainnya'
        ];

        // Path view yang benar berdasarkan controller Anda
        return view('customer.keluhan.create', compact('jenisKeluhan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'jenis_keluhan' => 'required|string|max:255',
            'keluhan' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload if provided
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('public/keluhan');
            $validatedData['gambar'] = str_replace('public/', '', $gambarPath);
        }

        // Add user_id to validated data
        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 'Belum Diproses'; // Status default saat pertama kali diajukan

        // Create new keluhan
        Keluhan::create($validatedData);

        return redirect()->route('keluhan.index')
            ->with('success', 'Keluhan berhasil dikirim. Kami akan menanggapi secepatnya.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $keluhan = Keluhan::findOrFail($id);

        // Make sure users can only see their own keluhan
        if ($keluhan->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Path view yang benar berdasarkan controller Anda
        return view('customer.keluhan.show', compact('keluhan'));
    }

    // Method edit, update, destroy yang kosong tetap dipertahankan seperti controller Anda
    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
