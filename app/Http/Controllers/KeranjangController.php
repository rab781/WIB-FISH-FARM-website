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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|exists:produk,id_Produk',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Ambil data produk
        $produk = Produk::findOrFail($request->id_produk);

        // Cek stok
        if ($produk->stok < $request->jumlah) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi'
                ]);
            }
            return redirect()->back()->with('error', 'Stok tidak mencukupi');
        }

        // Cek apakah produk sudah ada di keranjang
        $keranjang = Keranjang::where('user_id', Auth::id())
                    ->where('id_Produk', $request->id_produk)
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
                'id_Produk' => $request->id_produk,
                'jumlah' => $request->jumlah,
                'total_harga' => $request->jumlah * $produk->harga,
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            // Untuk permintaan AJAX, kirim response JSON
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'count' => $this->getCartCount()->original['count']
            ]);
        }

        return redirect()->route('keranjang.index')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    /**
     * Add product to cart (for cart.add route)
     */
    public function addToCart(Request $request)
    {
        // Validasi input
        $request->validate([
            'product_id' => 'required|exists:produk,id_Produk',
            'quantity' => 'required|integer|min:1',
            'size_id' => 'nullable|exists:produk_ukuran,id',
        ]);

        // Ambil data produk
        $produk = Produk::findOrFail($request->product_id);

        // Variabel untuk menyimpan harga dan stok yang akan digunakan
        $hargaProduk = $produk->harga;
        $stokTersedia = $produk->stok;
        $ukuranId = null;

        // Jika ukuran dipilih, gunakan data ukuran
        if ($request->has('size_id') && $request->size_id) {
            $ukuran = \App\Models\ProdukUkuran::findOrFail($request->size_id);

            // Pastikan ukuran ini milik produk yang dipilih
            if ($ukuran->id_produk != $produk->id_Produk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ukuran yang dipilih tidak valid untuk produk ini'
                ], 400);
            }

            // Gunakan stok ukuran untuk validasi
            $stokTersedia = $ukuran->stok;

            // Gunakan harga ukuran jika tersedia, jika tidak, gunakan harga produk
            $hargaProduk = $ukuran->harga ?: $produk->harga;

            // Simpan ukuran_id untuk disimpan ke keranjang
            $ukuranId = $ukuran->id;
        }

        // Cek stok
        if ($stokTersedia < $request->quantity) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi'
                ]);
            }
            return redirect()->back()->with('error', 'Stok tidak mencukupi');
        }

        // Cek apakah produk dengan ukuran yang sama sudah ada di keranjang
        $keranjang = Keranjang::where('user_id', Auth::id())
                   ->where('id_Produk', $request->product_id)
                   ->where('ukuran_id', $ukuranId)
                   ->first();

        // Jika sudah ada, update jumlah dan total harga
        if ($keranjang) {
            $keranjang->jumlah += $request->quantity;
            $keranjang->total_harga = $keranjang->jumlah * $hargaProduk;
            $keranjang->save();
        } else {
            // Jika belum ada, buat baru
            Keranjang::create([
                'user_id' => Auth::id(),
                'id_Produk' => $request->product_id,
                'ukuran_id' => $ukuranId,
                'jumlah' => $request->quantity,
                'total_harga' => $request->quantity * $hargaProduk,
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            // Untuk permintaan AJAX, kirim response JSON
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'count' => $this->getCartCount()->original['count']
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
            'jumlah' => 'required|integer|min:1|max:999',
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
            return redirect()->back()->with('error', 'Stok tidak mencukupi, stok tersedia: ' . $produk->stok);
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

    public function destroyViaPost(string $id)
    {
    $keranjang = Keranjang::findOrFail($id);

    // Cek kepemilikan
    if ($keranjang->user_id != Auth::id()) {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses'], 403);
        }
        return redirect()->route('keranjang.index')->with('error', 'Anda tidak memiliki akses');
    }

    // Hapus item dari keranjang
    $keranjang->delete();

    if (request()->ajax() || request()->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari keranjang',
            'count' => $this->getCartCount()->original['count']
        ]);
    }

    return redirect()->route('keranjang.index')->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    /**
     * Remove multiple items from cart
     */
    public function bulkDelete(Request $request)
    {
        // Start debug logging
        \Illuminate\Support\Facades\Log::info('Bulk Delete Request dimulai', [
            'request_data' => $request->all()
        ]);

        $request->validate([
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:keranjang,id_keranjang',
        ]);

        // Log semua item yang dipilih untuk dihapus
        \Illuminate\Support\Facades\Log::info('Items yang akan dihapus', [
            'selected_items' => $request->selected_items,
            'count' => count($request->selected_items)
        ]);

        // Count items before deletion for messaging
        $itemCount = count($request->selected_items);

        // Delete only items that belong to the authenticated user
        $deleted = 0;

        try {
            // Simpan semua ID yang berhasil dihapus untuk debugging
            $deletedIds = [];

            foreach ($request->selected_items as $itemId) {
                $item = Keranjang::where('id_keranjang', $itemId)
                    ->where('user_id', Auth::id())
                    ->first();

                if ($item) {
                    // Log item sebelum dihapus
                    \Illuminate\Support\Facades\Log::info("Menghapus item keranjang", [
                        'id_keranjang' => $item->id_keranjang,
                        'user_id' => $item->user_id,
                        'produk_id' => $item->id_Produk
                    ]);

                    // Hapus item
                    $item->delete();
                    $deleted++;
                    $deletedIds[] = $itemId;

                    \Illuminate\Support\Facades\Log::info("Item berhasil dihapus: {$itemId}");
                } else {
                    \Illuminate\Support\Facades\Log::warning("Item tidak ditemukan atau bukan milik user: {$itemId}");
                }
            }

            // Log ringkasan hasil penghapusan
            \Illuminate\Support\Facades\Log::info('Bulk delete selesai', [
                'total_requested' => $itemCount,
                'total_deleted' => $deleted,
                'deleted_ids' => $deletedIds
            ]);

        } catch (\Exception $e) {
            // Log jika terjadi error
            \Illuminate\Support\Facades\Log::error('Bulk delete error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('keranjang.index')
                ->with('error', 'Terjadi kesalahan saat menghapus produk');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $deleted . ' produk berhasil dihapus dari keranjang',
                'count' => $this->getCartCount()->original['count']
            ]);
        }

        if ($deleted > 0) {
            return redirect()->route('keranjang.index')
                ->with('success', $deleted . ' produk berhasil dihapus dari keranjang');
        } else {
            return redirect()->route('keranjang.index')
                ->with('error', 'Tidak ada produk yang dihapus');
        }
    }

    /**
     * Get cart item count for notification badge
     */
    public function getCartCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = Keranjang::where('user_id', Auth::id())->sum('jumlah');

        return response()->json(['count' => $count]);
    }
}
