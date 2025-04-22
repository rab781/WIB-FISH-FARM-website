@extends('layouts.customer')

@php
use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Keranjang Belanja</h1>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    @if($keranjang->count() > 0)
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <form id="bulkDeleteForm" action="{{ route('cart.bulk-delete') }}" method="POST">
            @csrf
            <!-- Header row showing labels/categories -->
            <div class="hidden md:flex border-b border-gray-200 bg-gray-50">
                <div class="w-6 p-4">
                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                </div>
                <div class="flex-1 p-4 text-sm font-medium text-gray-700">Produk</div>
                <div class="w-32 p-4 text-sm font-medium text-gray-700 text-center">Harga Satuan</div>
                <div class="w-32 p-4 text-sm font-medium text-gray-700 text-center">Kuantitas</div>
                <div class="w-32 p-4 text-sm font-medium text-gray-700 text-center">Total Harga</div>
                <div class="w-24 p-4 text-sm font-medium text-gray-700 text-center">Aksi</div>
            </div>

            <!-- Cart items -->
            @foreach($keranjang as $item)
            <div class="flex flex-col md:flex-row border-b border-gray-200 hover:bg-gray-50">
                <div class="md:w-6 p-4 flex items-center justify-center">
                    <input type="checkbox" name="selected_items[]" value="{{ $item->id_keranjang }}" class="cart-item-checkbox rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                </div>

                <div class="flex-1 p-4">
                    <div class="flex flex-col md:flex-row items-start">
                        <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 mb-4 md:mb-0">
                            @if($item->produk->gambar)
                                @if(Str::startsWith($item->produk->gambar, 'uploads/'))
                                    <img src="{{ asset($item->produk->gambar) }}" alt="{{ $item->produk->nama_ikan }}" class="h-full w-full object-contain">
                                @elseif(Str::startsWith($item->produk->gambar, 'storage/'))
                                    <img src="{{ asset($item->produk->gambar) }}" alt="{{ $item->produk->nama_ikan }}" class="h-full w-full object-contain">
                                @else
                                    <img src="{{ asset('storage/' . $item->produk->gambar) }}" alt="{{ $item->produk->nama_ikan }}" class="h-full w-full object-contain">
                                @endif
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="ml-0 md:ml-4">
                            <h3 class="text-base font-medium text-gray-900">{{ $item->produk->nama_ikan }}</h3>
                            <!-- Mobile view only price -->
                            <p class="mt-1 text-sm text-gray-500 md:hidden">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Price column (hidden on mobile) -->
                <div class="w-32 hidden md:flex items-center justify-center p-4">
                    <span class="text-base text-gray-900">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</span>
                </div>

                <!-- Quantity control -->
                <div class="w-full md:w-32 p-4 flex items-center justify-center">
                    <form action="{{ route('keranjang.update', $item->id_keranjang) }}" method="POST" class="quantity-form flex items-center">
                        @csrf
                        @method('PUT')
                        <button type="button" class="w-8 h-8 flex items-center justify-center bg-gray-100 text-gray-600 rounded-l border border-gray-300" onclick="decrementCartItem(this)">
                            <span class="sr-only">Decrease quantity</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <input type="number" name="jumlah" value="{{ $item->jumlah }}" min="1" max="999" class="w-16 h-8 border-t border-b border-gray-300 text-center focus:outline-none focus:ring-1 focus:ring-orange-500">
                        <button type="button" class="w-8 h-8 flex items-center justify-center bg-gray-100 text-gray-600 rounded-r border border-gray-300" onclick="incrementCartItem(this)">
                            <span class="sr-only">Increase quantity</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                        <button type="submit" class="hidden update-quantity-btn">Update</button>
                    </form>
                </div>

                <!-- Total price column -->
                <div class="w-full md:w-32 p-4 flex items-center justify-center">
                    <span class="text-base font-medium text-orange-600">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</span>
                </div>

                <!-- Action buttons -->
                <div class="w-full md:w-24 p-4 flex items-center justify-center">
                    <form action="{{ route('keranjang.destroy', $item->id_keranjang) }}" method="POST" class="inline-block delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="text-gray-500 hover:text-red-600 focus:outline-none delete-btn" title="Hapus">
                            <span class="sr-only">Remove product</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach


            <!-- Summary section -->
            <div class="p-4">
                <div class="flex justify-between items-center">
                    <p class="text-base font-medium">Total ({{ $keranjang->count() }} produk):</p>
                    <p class="text-xl font-bold text-orange-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</p>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" id="deleteSelectedBtn" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Hapus Produk Terpilih
                    </button>
                    <a href="{{ route('produk') }}" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Checkout
                    </a>
                </div>
            </div>
        </form>
    </div>
    @else
    <div class="bg-white p-8 rounded-lg shadow text-center">
        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <h2 class="text-xl font-medium text-gray-900 mb-2">Keranjang Belanja Kosong</h2>
        <p class="text-gray-600 mb-6">Anda belum menambahkan produk apapun ke keranjang belanja.</p>
        <div class="mt-6">
            <a href="{{ route('produk') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                Jelajahi Produk
            </a>
        </div>
    </div>
    @endif
</div>

<script>
    function incrementCartItem(button) {
        let form = button.closest('form');
        let input = form.querySelector('input[type=number]');
        if (input.value < parseInt(input.max) || !input.max) {
            input.value = parseInt(input.value) + 1;
            // Auto submit the form after a short delay
            setTimeout(() => form.submit(), 500);
        }
    }

    function decrementCartItem(button) {
        let form = button.closest('form');
        let input = form.querySelector('input[type=number]');
        if (input.value > 1) {
            input.value = parseInt(input.value) - 1;
            // Auto submit the form after a short delay
            setTimeout(() => form.submit(), 500);
        }
    }

    // Dispatch cart updated event whenever a form is submitted
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.quantity-form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                setTimeout(() => {
                    window.dispatchEvent(new Event('cartUpdated'));
                }, 500);
            });
        });

        // Select all checkbox functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                document.querySelectorAll('.cart-item-checkbox').forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
        }

        // Delete confirmation for single item
        const deleteBtns = document.querySelectorAll('.delete-btn');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                    this.closest('form').submit();
                }
            });
        });

        // Delete selected items functionality
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        if (deleteSelectedBtn) {
            deleteSelectedBtn.addEventListener('click', function() {
                const selectedItems = document.querySelectorAll('.cart-item-checkbox:checked');
                if (selectedItems.length > 0) {
                    if (confirm('Apakah Anda yakin ingin menghapus ' + selectedItems.length + ' produk yang terpilih dari keranjang?')) {
                        document.getElementById('bulkDeleteForm').submit();
                    }
                } else {
                    alert('Tidak ada produk yang dipilih untuk dihapus.');
                }
            });
        }
    });
</script>
@endsection
