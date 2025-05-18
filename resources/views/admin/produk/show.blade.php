@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="flex flex-col md:flex-row">
            <div class="md:w-1/2 bg-gray-100 flex items-center justify-center p-6">
                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_ikan }}" class="rounded-lg shadow-md max-h-72 object-contain w-full">
            </div>
            <div class="md:w-1/2 p-6 flex flex-col justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-2 text-gray-800">{{ $produk->nama_ikan }}</h2>
                    <span class="inline-block px-3 py-1 mb-4 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">{{ $produk->jenis_ikan }}</span>
                    <p class="mb-4 text-gray-600">{{ $produk->deskripsi }}</p>
                    <div class="mb-4">
                        <span class="text-lg font-semibold text-orange-600">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="mb-4">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $produk->stok > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            Stok: {{ $produk->stok }}
                        </span>
                    </div>
                </div>
                <div class="flex space-x-2 mt-6">
                    <a href="{{ route('admin.produk.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded font-semibold transition">Kembali</a>
                    <a href="{{ route('admin.produk.edit', $produk->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-semibold transition">Edit</a>
                    <form action="{{ route('admin.produk.destroy', $produk->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded font-semibold transition">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
