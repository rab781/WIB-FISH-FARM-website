@extends('layouts.customer')

@section('content')
<div class="container mx-auto px-4">
    <!-- Notification alert -->
    <div id="cartAlert" class="hidden fixed top-4 right-4 z-50 max-w-sm">
        <div id="successAlert" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline ml-2">Produk ditambahkan ke keranjang.</span>
            </div>
            <button type="button" class="absolute top-0 right-0 p-2" onclick="hideAlert('successAlert')">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        <div id="errorAlert" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <strong class="font-bold">Gagal!</strong>
                <span id="errorMessage" class="block sm:inline ml-2">Terjadi kesalahan.</span>
            </div>
            <button type="button" class="absolute top-0 right-0 p-2" onclick="hideAlert('errorAlert')">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>

    <div class="mb-6 mt-6">
        <h1 class="text-2xl font-bold mb-2">Produk</h1>
        <p class="text-gray-600">Berbagai jenis ikan hias untuk koleksi Anda.</p>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-md shadow-sm p-4 mb-6" x-data="{
        activeFilter: 'semua',
        priceSort: 'default',
        fishType: 'semua',
        searchQuery: '',
        showResults: true,

        resetFilters() {
            this.fishType = 'semua';
            this.priceSort = 'default';
            this.activeFilter = 'semua';
            this.searchQuery = '';

            // Reset ke tampilan default (ikan terakhir ditambahkan)
            document.querySelectorAll('.product-item').forEach(item => {
                item.classList.add('filtered');
                item.classList.remove('hidden');
            });

            // Urutkan produk berdasarkan tanggal (descending)
            const products = Array.from(document.querySelectorAll('.product-item'));
            const productsGrid = document.querySelector('.products-grid');

            // Sort by date added (id_Produk descending sebagai proxy untuk tanggal)
            products.sort((a, b) => {
                const idA = parseInt(a.getAttribute('data-id') || 0);
                const idB = parseInt(b.getAttribute('data-id') || 0);
                return idB - idA; // Larger ID (newer product) first
            });

            // Reattach sorted products
            products.forEach(product => {
                productsGrid.appendChild(product);
            });

            this.showResults = true;
        },

        filterProducts() {
            // First, apply filters to determine visible products
            document.querySelectorAll('.product-item').forEach(item => {
                let nameMatch = true;
                let typeMatch = true;
                let popularityMatch = true;
                let stockMatch = true;

                // Stock filter - hide products with 0 or negative stock
                const stock = parseInt(item.getAttribute('data-stock') || 0);
                stockMatch = stock > 0;

                // Search query filter
                if (this.searchQuery.trim() !== '') {
                    const name = item.getAttribute('data-name').toLowerCase();
                    nameMatch = name.includes(this.searchQuery.toLowerCase());
                }

                // Fish type filter
                if (this.fishType !== 'semua') {
                    const type = item.getAttribute('data-type');
                    typeMatch = type === this.fishType;
                }

                // Popularity filter - reset to true by default
                popularityMatch = true;

                // Apply filters including stock to show/hide products
                if (nameMatch && typeMatch && stockMatch) {
                    item.classList.add('filtered');
                    item.classList.remove('hidden');
                } else {
                    item.classList.remove('filtered');
                    item.classList.add('hidden');
                }
            });

            // Now apply sorting to visible products
            const visibleProducts = Array.from(document.querySelectorAll('.product-item.filtered'));
            const productsGrid = document.querySelector('.products-grid');

            // Sort the products based on selected filter
            if (this.activeFilter === 'popular') {
                // Sort by popularity (order count) - highest sales first
                visibleProducts.sort((a, b) => {
                    const popularityA = parseInt(a.getAttribute('data-popularity') || 0);
                    const popularityB = parseInt(b.getAttribute('data-popularity') || 0);
                    return popularityB - popularityA; // Higher order count first
                });
            } else if (this.priceSort === 'low-to-high') {
                // Sort by price low to high
                visibleProducts.sort((a, b) => {
                    const priceA = parseFloat(a.getAttribute('data-price'));
                    const priceB = parseFloat(b.getAttribute('data-price'));
                    return priceA - priceB;
                });
            } else if (this.priceSort === 'high-to-low') {
                // Sort by price high to low
                visibleProducts.sort((a, b) => {
                    const priceA = parseFloat(a.getAttribute('data-price'));
                    const priceB = parseFloat(b.getAttribute('data-price'));
                    return priceB - priceA;
                });
            }

            // Reattach the sorted products to the grid
            visibleProducts.forEach(product => {
                productsGrid.appendChild(product);
            });

            // Check if there are any visible products
            this.showResults = document.querySelectorAll('.product-item.filtered').length > 0;
        }
    }" x-init="filterProducts()">
        <!-- Search Bar -->
        <div class="mb-4">
            <div class="relative">
                <input
                    type="search"
                    class="w-full border border-gray-300 rounded-md px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-orange-500"
                    placeholder="Cari produk..."
                    x-model="searchQuery"
                    @input="filterProducts()"
                >
                <button class="absolute left-3 top-1/2 transform -translate-y-1/2" type="button">
                    <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Filter Options -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Fish Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Ikan</label>
                <select x-model="fishType" @change="filterProducts()" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <option value="semua">Semua Jenis</option>
                    @foreach($jenisIkan as $jenis)
                    <option value="{{ strtolower($jenis) }}">{{ $jenis }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Price Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Urutkan Harga</label>
                <select x-model="priceSort" @change="filterProducts()" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <option value="default">Pilih Urutan</option>
                    <option value="low-to-high">Harga Terendah</option>
                    <option value="high-to-low">Harga Tertinggi</option>
                </select>
            </div>

            <!-- Popularity Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Produk Paling Laku</label>
                <select x-model="activeFilter" @change="filterProducts()" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <option value="semua">Semua Produk</option>
                    <option value="popular">Paling Laku</option>
                </select>
            </div>

            <!-- Clear Filters Button -->
            <div class="flex items-end">
                <button
                    @click="resetFilters()"
                    class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md transition">
                    Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="products-grid grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
        @foreach($produk as $p)
            @if($p->stok > 0)
            <div class="product-item filtered bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300"
                data-name="{{ $p->nama_ikan }}"
                data-price="{{ $p->harga }}"
                data-type="{{ strtolower($p->jenis_ikan ?? 'lainnya') }}"
                data-popularity="{{ isset($p->detail_pesanan_count) ? $p->detail_pesanan_count : $p->order_count }}"
                data-id="{{ $p->id_Produk }}"
                data-stock="{{ $p->stok }}">

                <!-- Clickable product image and header -->
                <a href="{{ route('detailProduk', $p->id_Produk) }}" class="block">
                    <div class="h-48 bg-gray-200 relative overflow-hidden">
                        @if($p->gambar)
                            @if(Str::startsWith($p->gambar, ['http://', 'https://']))
                                <img src="{{ $p->gambar }}" alt="{{ $p->nama_ikan }}" class="w-full h-full object-cover">
                            @elseif(Str::startsWith($p->gambar, 'uploads/'))
                                <img src="{{ asset($p->gambar) }}" alt="{{ $p->nama_ikan }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('storage/' . $p->gambar) }}" alt="{{ $p->nama_ikan }}" class="w-full h-full object-cover">
                            @endif
                        @else
                            <img src="{{ asset('Images/Default-fish.png') }}" alt="{{ $p->nama_ikan }}" class="w-full h-full object-contain opacity-80">
                        @endif
                        @if((isset($p->detail_pesanan_count) && $p->detail_pesanan_count > 5) || (isset($p->order_count) && $p->order_count > 5))
                            <div class="absolute top-2 right-2">
                                <span class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full">Populer</span>
                            </div>
                        @endif
                        @if($p->stok > 0 && $p->stok <= 5)
                            <div class="absolute top-2 left-2">
                                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">Stok Terbatas</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-4 pb-0">
                        <h3 class="font-medium text-gray-900 mb-1 hover:text-orange-600">{{ $p->nama_ikan }}</h3>
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-gray-900 font-bold">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                            <div class="flex items-center">
                                <p class="text-sm mr-2 {{ $p->stok <= 5 ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                    Stok: {{ $p->stok }}
                                </p>
                                @if(isset($p->detail_pesanan_count) && $p->detail_pesanan_count > 0)
                                <span class="text-xs text-gray-500">({{ $p->detail_pesanan_count }} terjual)</span>
                                @elseif(isset($p->order_count) && $p->order_count > 0)
                                <span class="text-xs text-gray-500">({{ $p->order_count }} terjual)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>

                <div class="p-4 pt-0">
                    @if($p->stok > 0)
                        @auth
                            <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $p->id_Produk }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        @else
                            <button
                                onclick="showAuthModal()"
                                class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                                Tambah ke Keranjang
                            </button>
                        @endauth
                    @else
                        <button disabled class="w-full bg-gray-400 text-white py-2 px-3 rounded-md text-sm font-medium cursor-not-allowed">
                            Stok Habis
                        </button>
                    @endif
                </div>
            </div>
            @endif
        @endforeach
    </div>

    <!-- Empty state when no products match filters -->
    <div
        class="text-center py-12 bg-white rounded-lg shadow-sm"
        x-data="{ get showResultsValue() { return document.querySelectorAll('.product-item.filtered:not(.hidden)').length > 0 } }"
        x-show="!showResultsValue"
    >
        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada produk tersedia</h3>
        <p class="mt-2 text-gray-500">Tidak ada produk yang sesuai dengan filter pencarian atau semua produk sedang habis stok</p>
        <p class="mt-1 text-sm text-gray-400">Coba ubah filter pencarian atau periksa kembali nanti</p>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $produk->links() }}
    </div>
</div>

<!-- Auth Modal for Guest Users -->
@guest
<div x-data="{ showAuthModal: false, modalMessage: 'Untuk membeli produk, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.' }"
    x-show="showAuthModal"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-200/90 backdrop-filter backdrop-blur-md"
    @click.self="showAuthModal = false"
    style="display: none;">
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full backdrop-filter-none">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Akses Terbatas</h3>
            <button @click="showAuthModal = false" class="text-gray-500 hover:text-gray-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <p class="text-gray-600 mb-6" x-text="modalMessage"></p>
        <div class="flex flex-col space-y-3">
            <a href="{{ route('login') }}" class="bg-orange-600 text-white py-2 px-4 rounded-md text-center hover:bg-orange-700 transition flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                Masuk
            </a>
            <a href="{{ route('register') }}" class="border border-gray-300 py-2 px-4 rounded-md text-center hover:bg-gray-50 transition flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Daftar
            </a>
        </div>
    </div>
</div>
@endguest

<script>
    function showAlert(type, message = '') {
        const cartAlert = document.getElementById('cartAlert');
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');

        cartAlert.classList.remove('hidden');

        if (type === 'success') {
            successAlert.classList.remove('hidden');
            errorAlert.classList.add('hidden');
        } else {
            errorAlert.classList.remove('hidden');
            successAlert.classList.add('hidden');
            if (message) {
                document.getElementById('errorMessage').textContent = message;
            }
        }

        // Auto hide after 4 seconds
        setTimeout(() => {
            hideAlert(type === 'success' ? 'successAlert' : 'errorAlert');
        }, 4000);
    }

    function hideAlert(alertId) {
        document.getElementById(alertId).classList.add('hidden');
        const cartAlert = document.getElementById('cartAlert');

        // Check if both alerts are hidden
        if (document.getElementById('successAlert').classList.contains('hidden') &&
            document.getElementById('errorAlert').classList.contains('hidden')) {
            cartAlert.classList.add('hidden');
        }
    }

    // Handle add to cart forms
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.add-to-cart-form');
        console.log('Forms found:', forms.length); // Debug: melihat berapa form yang ditemukan

        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Form submitted', this.action); // Debug: memastikan event handler terpanggil

                // Ambil CSRF token dari meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams(new FormData(this))
                })
                .then(response => {
                    console.log('Response status:', response.status); // Debug response status
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data); // Debug response data
                    if (data.success) {
                        showAlert('success');
                        // Update cart count badge
                        if (data.count !== undefined) {
                            updateCartCount(data.count);
                        }
                        // Dispatch event to update cart badge
                        window.dispatchEvent(new Event('cartUpdated'));
                    } else {
                        showAlert('error', data.message || 'Terjadi kesalahan saat menambahkan produk ke keranjang.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                });
            });
        });
    });

    function updateCartCount(count) {
        const cartBadge = document.querySelector('.cart-count-badge');
        if (cartBadge) {
            cartBadge.textContent = count;
            cartBadge.classList.remove('hidden');
        }
    }

    function showAuthModal() {
        if (typeof Alpine !== 'undefined') {
            const modal = document.querySelector('[x-data*="showAuthModal"]');
            if (modal) {
                Alpine.$data(modal).showAuthModal = true;
            }
        }
    }
</script>
@endsection
