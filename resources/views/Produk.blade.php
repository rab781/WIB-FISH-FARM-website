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
                <a href="/masuk" class="bg-blue-600 text-white py-2 px-4 rounded-md text-center hover:bg-blue-700 transition">Masuk</a>
                <a href="/daftar" class="border border-gray-300 py-2 px-4 rounded-md text-center hover:bg-gray-50 transition">Daftar</a>
                <button @click="showAuthModal = false" class="text-gray-500 hover:text-gray-800 text-sm mt-2">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-40 transition-all duration-300" :class="{'shadow-lg': scrolled}">
        <div class="container mx-auto px-4 py-2">
            <div class="flex items-center justify-between dir='rtl'">
                <!-- Navigation Links - For Desktop -->
                <nav class="hidden md:flex space-x-8 gap-x-2 items-center">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center">
                            <img class="h-12" src="{{ asset('Images/Logo_WIB_FISH_FARM.png') }}" alt="WIB Fish Farm">
                        </a>
                    </div>
                    <a href="/produk" class="text-gray-700 hover:text-blue-600 font-medium">Produk</a>
                    <a href="/tentang-kami" class="text-gray-700 hover:text-blue-600 font-medium">Tentang Kami</a>
                </nav>

                <!-- Right Buttons -->
                <div class="flex items-center space-x-2 gap-x-4">
                    <button @click="showAuthModal = true; modalMessage = 'Untuk mengakses keranjang belanja, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.'" class="text-gray-700 hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </button>
                    <button @click="showAuthModal = true; modalMessage = 'Untuk mengakses notifikasi, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.'" class="text-gray-700 hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>
                    <a href="/daftar" class="border border-gray-300 px-4 py-1 rounded text-gray-700 hover:border-blue-600 hover:text-blue-600">Daftar</a>
                    <a href="/masuk" class="bg-black text-white px-4 py-1 rounded hover:border-blue-600 hover:text-blue-600 border-white-300 hover:bg-gray-300">Masuk</a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button class="mobile-menu-button text-gray-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="mobile-menu hidden md:hidden mt-4 pb-2">
                <a href="/produk" class="block py-2 text-gray-700 hover:text-blue-600">Produk</a>
                <a href="/tentang-kami" class="block py-2 text-gray-700 hover:text-blue-600">Tentang Kami</a>
            </div>
        </div>
    </header>

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
                            class="w-full border border-gray-300 rounded-md px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                    <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                        <div class="h-48 bg-gray-200 relative overflow-hidden">
                            <img :src="product.image" :alt="product.name" class="w-full h-full object-cover">
                            <div class="absolute top-2 right-2">
                                <span x-show="product.popularity >= 4" class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">Populer</span>
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
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                                Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
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
    <footer class="bg-gray-900 text-white border-t border-gray-800 py-12">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap">
                <div class="w-full md:w-1/4 mb-8 md:mb-0">
                    <img src="{{ asset('Images/Logo_WIB_FISH_FARM.png') }}" alt="Logo" class="h-10 mb-4">
                    <p class="text-sm text-gray-400 mb-4">WIB Fish Farm adalah platform online khusus untuk memudahkan Anda dalam membeli ikan hias berkualitas tinggi.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-gray-300">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-300">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-300">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="w-full md:w-1/4 mb-8 md:mb-0">
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase mb-4">Produk</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-base text-gray-400 hover:text-gray-300">Ikan Koki</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-gray-300">Ikan Koi</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-gray-300">Aksesoris</a></li>
                    </ul>
                </div>

                <!-- ...additional footer content... -->
            </div>

            <div class="border-t border-gray-800 pt-8 mt-8 text-center">
                <p class="text-sm text-gray-400">&copy; 2025 WIB Fish Farm. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-button').addEventListener('click', function() {
            document.querySelector('.mobile-menu').classList.toggle('hidden');
        });

        // Initialize AOS on load
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease',
                once: false,
                mirror: true
            });
        });

        // Close modal on ESC key
        document.onkeydown = function(event) {
        event = event || window.event;
        if (event.keyCode === 27) {
            document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
            let modals = document.getElementsByClassName('modal');
            Array.prototype.slice.call(modals).forEach(i => {
                i.style.display = 'none'
            })
        }
        };
    </script>
</body>
</html>
