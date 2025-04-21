@extends('layouts.admin')

@section('content')
<div class="container mx-auto">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="py-4 px-6 bg-gray-50 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Edit Produk: {{ $produk->nama_ikan }}</h2>
                <a href="{{ route('admin.produk.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md flex items-center">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <form action="{{ route('admin.produk.update', $produk->id_Produk) }}" method="POST" enctype="multipart/form-data" class="py-6 px-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <label for="nama_ikan" class="block text-gray-700 font-medium mb-2">Nama Ikan</label>
                        <input type="text" name="nama_ikan" id="nama_ikan" value="{{ old('nama_ikan', $produk->nama_ikan) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_ikan') border-red-500 @enderror">
                        @error('nama_ikan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="jenis_ikan" class="block text-gray-700 font-medium mb-2">Jenis Ikan</label>
                        <select name="jenis_ikan" id="jenis_ikan" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jenis_ikan') border-red-500 @enderror">
                            <option value="">Pilih Jenis Ikan</option>
                            <option value="Koi" {{ old('jenis_ikan', $produk->jenis_ikan) == 'Koi' ? 'selected' : '' }}>Koi</option>
                            <option value="Koki" {{ old('jenis_ikan', $produk->jenis_ikan) == 'Koki' ? 'selected' : '' }}>Koki</option>
                            <option value="Cupang" {{ old('jenis_ikan', $produk->jenis_ikan) == 'Cupang' ? 'selected' : '' }}>Cupang</option>
                            <option value="Arwana" {{ old('jenis_ikan', $produk->jenis_ikan) == 'Arwana' ? 'selected' : '' }}>Arwana</option>
                            <option value="Lainnya" {{ old('jenis_ikan', $produk->jenis_ikan) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis_ikan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="harga" class="block text-gray-700 font-medium mb-2">Harga</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">Rp</span>
                            </div>
                            <input type="number" name="harga" id="harga" value="{{ old('harga', $produk->harga) }}" required min="0"
                                class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('harga') border-red-500 @enderror">
                        </div>
                        @error('harga')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="stok" class="block text-gray-700 font-medium mb-2">Stok</label>
                        <input type="number" name="stok" id="stok" value="{{ old('stok', $produk->stok) }}" required min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('stok') border-red-500 @enderror">
                        @error('stok')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="mb-4">
                        <label for="deskripsi" class="block text-gray-700 font-medium mb-2">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="gambar" class="block text-gray-700 font-medium mb-2">Gambar Produk</label>
                        <div class="mt-1 flex items-center">
                            <div id="preview" class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-md flex items-center justify-center mb-2 overflow-hidden">
                                @if($produk->gambar)
                                    <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_ikan }}" class="h-full w-full object-cover">
                                @else
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <input type="file" name="gambar" id="gambar" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('gambar') border-red-500 @enderror">
                        <p class="text-sm text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah gambar</p>
                        @error('gambar')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.produk.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded-md">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Image preview
    document.getElementById('gambar').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        preview.innerHTML = '';

        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = document.createElement('img');
                img.src = event.target.result;
                img.className = 'h-full w-full object-cover';
                preview.appendChild(img);
            }
            reader.readAsDataURL(file);
        } else {
            // Jika user batalkan pilihan file, kembalikan ke gambar sebelumnya jika ada
            @if($produk->gambar)
            preview.innerHTML = `<img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_ikan }}" class="h-full w-full object-cover">`;
            @else
            preview.innerHTML = `
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            `;
            @endif
        }
    });
</script>
@endpush
@endsection
