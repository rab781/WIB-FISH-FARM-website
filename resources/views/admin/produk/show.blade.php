@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="flex flex-col md:flex-row">
            <div class="md:w-1/2 bg-gray-100 flex items-center justify-center p-6">
                @if($produk->gambar)
                    @if(Str::startsWith($produk->gambar, 'uploads/'))
                        <img src="{{ asset($produk->gambar) }}" alt="{{ $produk->nama_ikan }}" class="rounded-lg shadow-md max-h-72 object-contain w-full">
                    @else
                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_ikan }}" class="rounded-lg shadow-md max-h-72 object-contain w-full">
                    @endif
                @else
                    <div class="rounded-lg shadow-md max-h-72 w-full bg-gray-200 flex items-center justify-center">
                        <svg class="h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
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
                    <a href="{{ route('admin.produk.edit', $produk->id_Produk) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-semibold transition">Edit</a>
                    <form id="deleteProductForm" action="{{ route('admin.produk.destroy', $produk->id_Produk) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" id="deleteProductBtn" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded font-semibold transition">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('deleteProductBtn').addEventListener('click', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Hapus Produk?',
        text: 'Produk "{{ $produk->nama_produk }}" akan dihapus. Tindakan ini dapat dibatalkan nanti.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-lg',
            confirmButton: 'rounded-md',
            cancelButton: 'rounded-md'
        },
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve();
                }, 500);
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Menghapus...',
                text: 'Sedang memproses penghapusan produk.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            document.getElementById('deleteProductForm').submit();
        }
    });
});
</script>
@endpush

@endsection
