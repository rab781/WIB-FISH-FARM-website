<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua produk termasuk yang di-soft delete
        $produk = Produk::withTrashed()->latest()->paginate(10);
        $header = 'Manajemen Produk';
        return view('admin.produk.index', compact('produk', 'header'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $header = 'Tambah Produk Baru';
        return view('admin.produk.create', compact('header'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_ikan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'jenis_ikan' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $data = $request->except(['_token', 'gambar']);

            // Coba cara baru untuk menangani upload file
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');

                // Generate nama file yang unik dengan UUID
                $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();

                // Simpan file dengan metode manual
                $file->move(public_path('uploads/produk'), $filename);

                // Set path gambar untuk disimpan ke database
                $data['gambar'] = 'uploads/produk/' . $filename;

                Log::info('File uploaded successfully to: ' . $data['gambar']);
            }

            $produk = Produk::create($data);

            if ($produk) {
                Log::info('Product created successfully: ' . $request->nama_ikan);

                // Send notification to all customers about new product
                $customers = User::where('is_admin', false)->get();
                foreach ($customers as $customer) {
                    NotificationController::notifyCustomer($customer->id, [
                        'type' => 'product',
                        'title' => 'Produk Baru Ditambahkan',
                        'message' => 'Produk baru "' . $produk->nama_ikan . '" telah ditambahkan.',
                        'data' => [
                            'product_id' => $produk->id_Produk,
                            'url' => route('detailProduk', ['id' => $produk->id_Produk])
                        ]
                    ]);
                }

                return redirect()->route('admin.produk.index')
                        ->with('success', 'Produk berhasil ditambahkan');
            } else {
                throw new \Exception('Gagal menyimpan produk ke database');
            }
        } catch (\Exception $e) {
            Log::error('Error adding product: ' . $e->getMessage());
            return redirect()->back()
                    ->with('error', 'Gagal menambahkan produk: ' . $e->getMessage())
                    ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk::withTrashed()->findOrFail($id);
        return view('admin.produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produk.edit', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_ikan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'jenis_ikan' => 'required|string|max:255',
            'popularity' => 'nullable|integer|min:0|max:5',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],
        [
            'nama_ikan.required' => 'Nama ikan harus diisi.',
            'deskripsi.required' => 'Deskripsi harus diisi.',
            'stok.required' => 'Stok harus diisi.',
            'harga.required' => 'Harga harus diisi.',
            'jenis_ikan.required' => 'Jenis ikan harus diisi.',
            'gambar.image' => 'File yang diunggah harus berupa gambar.',
            'gambar.mimes' => 'Gambar harus dalam format jpeg, png, jpg, atau gif.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            $produk = Produk::findOrFail($id);
            $data = $request->except(['_token', '_method', 'gambar']);

            // Coba cara baru untuk menangani upload file
            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');

                // Generate nama file yang unik dengan UUID
                $filename = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();

                // Hapus gambar lama jika ada
                if ($produk->gambar && file_exists(public_path($produk->gambar))) {
                    unlink(public_path($produk->gambar));
                }

                // Simpan file dengan metode manual
                $file->move(public_path('uploads/produk'), $filename);

                // Set path gambar untuk disimpan ke database
                $data['gambar'] = 'uploads/produk/' . $filename;

                Log::info('File updated successfully to: ' . $data['gambar']);
            }

            $updated = $produk->update($data);

            if ($updated) {
                Log::info('Product updated successfully: ' . $produk->nama_ikan);

                // Send notification to all customers about updated product
                $customers = User::where('is_admin', false)->get();
                foreach ($customers as $customer) {
                    NotificationController::notifyCustomer($customer->id, [
                        'type' => 'product',
                        'title' => 'Produk Diperbarui',
                        'message' => 'Produk "' . $produk->nama_ikan . '" telah diperbarui.',
                        'data' => [
                            'product_id' => $produk->id_Produk,
                            'url' => route('detailProduk', ['id' => $produk->id_Produk])
                        ]
                    ]);
                }

                return redirect()->route('admin.produk.index')
                        ->with('success', 'Produk berhasil diperbarui');
            } else {
                throw new \Exception('Gagal memperbarui produk di database');
            }
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->back()
                    ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())
                    ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $produk = Produk::findOrFail($id);
            $produk->delete(); // Soft delete

            Alert::success('Berhasil!', 'Produk berhasil dihapus sementara');
            return redirect()->route('admin.produk.index');
        } catch (\Exception $e) {
            Alert::error('Error!', 'Gagal menghapus produk');
            return redirect()->back();
        }
    }

    /**
     * Restore the soft-deleted resource.
     */
    public function restore(string $id)
    {
        try {
            $produk = Produk::withTrashed()->findOrFail($id);
            $produk->restore();

            Alert::success('Berhasil!', 'Produk berhasil dipulihkan');
            return redirect()->route('admin.produk.index');
        } catch (\Exception $e) {
            Alert::error('Error!', 'Gagal memulihkan produk');
            return redirect()->back();
        }
    }

    /**
     * Permanently delete the soft-deleted resource.
     */
    public function forceDelete(string $id)
    {
        try {
            $produk = Produk::withTrashed()->findOrFail($id);

            // Check if the product has any related detail_pesanan records
            if ($produk->detailPesanan()->count() > 0) {
                Alert::error('Error!', 'Tidak dapat menghapus produk secara permanen karena terkait dengan pesanan.');
                return redirect()->route('admin.produk.index');
            }

            // Hapus gambar jika ada
            if ($produk->gambar && file_exists(public_path($produk->gambar))) {
                unlink(public_path($produk->gambar));
            }

            $produk->forceDelete();

            Alert::success('Berhasil!', 'Produk berhasil dihapus permanen');
            return redirect()->route('admin.produk.index');
        } catch (\Exception $e) {
            Alert::error('Error!', 'Gagal menghapus produk permanen');
            return redirect()->back();
        }
    }
}
