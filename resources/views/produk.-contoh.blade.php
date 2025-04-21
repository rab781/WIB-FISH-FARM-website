<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- ...existing code for tailwind CSS... -->
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <title>Produk - WIB Fish Farm</title>

    <style>
        /* ...existing styles... */
    </style>
</head>
<body class="bg-gray-50" x-data="{
    scrolled: false,
    showAuthModal: false,
    modalMessage: '',
    activeFilter: 'semua',
    priceSort: 'default',
    fishType: 'semua',
    searchQuery: '',
    showAuthWithMessage(message) {
        this.modalMessage = message;
        this.showAuthModal = true;
    },
    products: [
        { id: 1, name: 'Ikan Koki Merah', price: 150000, type: 'koki', image: '{{ asset('images/placeholder.jpg') }}', popularity: 5, stock: 10 },
        { id: 2, name: 'Ikan Koki Calico', price: 175000, type: 'koki', image: '{{ asset('images/placeholder.jpg') }}', popularity: 3, stock: 8 },
        { id: 3, name: 'Koi Kohaku', price: 250000, type: 'koi', image: '{{ asset('images/placeholder.jpg') }}', popularity: 4, stock: 5 },
        { id: 4, name: 'Koi Showa', price: 350000, type: 'koi', image: '{{ asset('images/placeholder.jpg') }}', popularity: 5, stock: 3 },
        { id: 5, name: 'Ikan Koki Oranda', price: 200000, type: 'koki', image: '{{ asset('images/placeholder.jpg') }}', popularity: 4, stock: 7 },
        { id: 6, name: 'Koi Sanke', price: 275000, type: 'koi', image: '{{ asset('images/placeholder.jpg') }}', popularity: 4, stock: 6 },
        { id: 7, name: 'Ikan Koki Ranchu', price: 225000, type: 'koki', image: '{{ asset('images/placeholder.jpg') }}', popularity: 3, stock: 9 },
        { id: 8, name: 'Koi Bekko', price: 230000, type: 'koi', image: '{{ asset('images/placeholder.jpg') }}', popularity: 2, stock: 4 }
    ]
}"
x-init="() => { AOS.init({duration: 800, easing: 'ease', once: false}); window.addEventListener('scroll', () => { scrolled = window.pageYOffset > 20 }) }">

    <!-- Auth Modal -->
    <div x-show="showAuthModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-90"
         class="fixed inset-0 z-50 flex items-center justify-center bg-gray-200/90 backdrop-filter backdrop-blur-md"
         @click.self="showAuthModal = false">
        <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full backdrop-filter-none">
            <h3 class="text-xl font-bold mb-4 text-gray-800">Akses Terbatas</h3>
            <p class="text-gray-600 mb-6" x-text="modalMessage"></p>
            <div class="flex flex-col space-y-3">
                <a href="{{ route('login') }}" class="bg-orange-600 text-white py-2 px-4 rounded-md text-center hover:bg-orange-700 transition">Masuk</a>
                <a href="{{ route('register') }}" class="border border-gray-300 py-2 px-4 rounded-md text-center hover:bg-gray-50 transition">Daftar</a>
                <button @click="showAuthModal = false" class="text-gray-500 hover:text-gray-800 text-sm mt-2">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Main Content -->
    <main class="pt-6 pb-16">
        <div class="container mx-auto px-4">
            <div class="mb-6">
                <h1 class="text-2xl font-bold mb-2">Produk</h1>
                <p class="text-gray-600">Berbagai jenis ikan hias untuk koleksi Anda.</p>
            </div>

            <!-- Search and Filter Section -->
            <div class="bg-white rounded-md shadow-sm p-4 mb-6">
                <!-- Search Bar -->
                <div class="mb-4">
                    <div class="relative">
                        <input
                            type="search"
                            class="w-full border border-gray-300 rounded-md px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-orange-500"
                            placeholder="Cari produk..."
                            x-model="searchQuery"
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
                        <select x-model="fishType" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="semua">Semua Jenis</option>
                            <option value="koki">Ikan Koki</option>
                            <option value="koi">Ikan Koi</option>
                        </select>
                    </div>

                    <!-- Price Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Urutkan Harga</label>
                        <select x-model="priceSort" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="default">Pilih Urutan</option>
                            <option value="low-to-high">Harga Terendah</option>
                            <option value="high-to-low">Harga Tertinggi</option>
                        </select>
                    </div>

                    <!-- Popularity Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Popularitas</label>
                        <select x-model="activeFilter" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="semua">Semua</option>
                            <option value="popular">Paling Laku</option>
                        </select>
                    </div>

                    <!-- Clear Filters Button -->
                    <div class="flex items-end">
                        <button
                            @click="fishType = 'semua'; priceSort = 'default'; activeFilter = 'semua'; searchQuery = ''"
                            class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-4 rounded-md transition">
                            Reset Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <template x-for="product in products
                    .filter(p => fishType === 'semua' || p.type === fishType)
                    .filter(p => activeFilter === 'semua' || (activeFilter === 'popular' && p.popularity >= 4))
                    .filter(p => searchQuery === '' || p.name.toLowerCase().includes(searchQuery.toLowerCase()))
                    .sort((a, b) => {
                        if (priceSort === 'low-to-high') return a.price - b.price;
                        if (priceSort === 'high-to-low') return b.price - a.price;
                        return 0;
                    })" :key="product.id">

                    @auth
                        <!-- For authenticated users - Direct add to cart form -->
                        <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                            <div class="h-48 bg-gray-200 relative overflow-hidden">
                                <img :src="product.image" :alt="product.name" class="w-full h-full object-cover">
                                <div class="absolute top-2 right-2">
                                    <span x-show="product.popularity >= 4" class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full">Populer</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium text-gray-900 mb-1" x-text="product.name"></h3>
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-gray-900 font-bold" x-text="`Rp ${product.price.toLocaleString()}`"></p>
                                    <p class="text-sm text-gray-600" x-text="`Stok: ${product.stock}`"></p>
                                </div>
                                <form :action="'{{ route('cart.add') }}'" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" :value="product.id">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="name" :value="product.name">
                                    <input type="hidden" name="price" :value="product.price">
                                    <input type="hidden" name="image" :value="product.image">
                                    <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                                        Tambah ke Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- For guests - Auth modal trigger -->
                        <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                            <div class="h-48 bg-gray-200 relative overflow-hidden">
                                <img :src="product.image" :alt="product.name" class="w-full h-full object-cover">
                                <div class="absolute top-2 right-2">
                                    <span x-show="product.popularity >= 4" class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full">Populer</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-medium text-gray-900 mb-1" x-text="product.name"></h3>
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-gray-900 font-bold" x-text="`Rp ${product.price.toLocaleString()}`"></p>
                                    <p class="text-sm text-gray-600" x-text="`Stok: ${product.stock}`"></p>
                                </div>
                                <button
                                    @click="showAuthModal = true; modalMessage = 'Untuk membeli produk, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.'"
                                    class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                                    Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    @endauth
                </template>

                <!-- Empty state when no products match filters -->
                <div
                    class="col-span-full text-center py-12"
                    x-show="products
                        .filter(p => fishType === 'semua' || p.type === fishType)
                        .filter(p => activeFilter === 'semua' || (activeFilter === 'popular' && p.popularity >= 4))
                        .filter(p => searchQuery === '' || p.name.toLowerCase().includes(searchQuery.toLowerCase()))
                        .length === 0"
                >
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada produk ditemukan</h3>
                    <p class="mt-2 text-gray-500">Coba ubah filter pencarian Anda</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Initialize AOS
            AOS.init({
                duration: 800,
                easing: 'ease',
                once: false,
                mirror: true
            });
        });
    </script>
</body>
</html>
