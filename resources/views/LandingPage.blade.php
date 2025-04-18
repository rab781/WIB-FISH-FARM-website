<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <!-- External stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <!-- Custom styles -->
    <link href="{{ asset('css/landing-animations.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="{{ asset('js/landing.js') }}"></script>

    <title>WIB Fish Farm</title>
</head>
<body class="bg-white"
      x-data="appState()"
      x-init="init()">

    <!-- Auth Modal -->
    @guest
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
                <a href="{{ route('login') }}" class="bg-blue-600 text-white py-2 px-4 rounded-md text-center hover:bg-blue-700 transition">Masuk</a>
                <a href="{{ route('register') }}" class="border border-gray-300 py-2 px-4 rounded-md text-center hover:bg-gray-50 transition">Daftar</a>
                <button @click="showAuthModal = false" class="text-gray-500 hover:text-gray-800 text-sm mt-2">Tutup</button>
            </div>
        </div>
    </div>
    @endguest

    <!-- Navbar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 transition-all duration-300" :class="{'shadow-lg': scrolled}">
        <div class="container mx-auto px-4 py-2">
            <div class="flex items-center justify-between dir='rtl'">

                <!-- Navigation Links - For Desktop -->
                <nav class="hidden md:flex  space-x-8 gap-x-2 items-center">
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
                    @auth
                        <!-- Cart button for authenticated users -->
                        <a href="#" class="text-gray-700 hover:text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </a>

                        <!-- Notification button for authenticated users -->
                        <a href="#" class="text-gray-700 hover:text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </a>

                        <!-- Profile dropdown menu for authenticated users -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center focus:outline-none">
                                <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white overflow-hidden border-2 border-white">
                                    @if(auth()->user()->profile_photo_path)
                                        <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                                    @else
                                        <span class="text-sm font-medium">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                            </button>

                            <!-- Dropdown menu -->
                            <div x-show="open"
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 py-2 bg-white rounded-md shadow-xl z-10">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ __('Logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Buttons for guests -->
                        <button @click="showAuthWithMessage('Untuk mengakses keranjang belanja, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.')" class="text-gray-700 hover:text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </button>
                        <button @click="showAuthWithMessage('Untuk mengakses notifikasi, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.')" class="text-gray-700 hover:text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </button>
                        <a href="{{ route('register') }}" class="border border-gray-300 px-4 py-1 rounded text-gray-700 hover:border-blue-600 hover:text-blue-600">Daftar</a>
                        <a href="{{ route('login') }}" class="bg-black text-white px-4 py-1 rounded hover:border-blue-600 hover:text-blue-600 border-white-300 hover:bg-gray-300">Masuk</a>
                    @endauth
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

    <!-- Hero Section -->
    <section class="relative h-screen overflow-hidden">
        <!-- Video Background -->
        <div class="absolute inset-0 z-0">
            <video class="w-full h-full object-cover zoom-bg" autoplay muted loop playsinline>
                <source src="{{ asset('videos/background.mp4') }}" type="video/mp4">
                <!-- Fallback content for browsers that do not support video -->
                Your browser does not support the video tag.
            </video>
            <!-- Overlay to improve text readability -->
            <div class="absolute inset-0 bg-black opacity-50 z-10 mix-blend-multiply">
                <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent"></div>
            </div>
        </div>

        <!-- Content positioned above video -->
        <div class="relative z-10 container mx-auto px-12 py-4 h-full flex items-center">
            <div class="max-w-xl text-white">
                <h1 class="text-4xl font-bold mb-4 opacity-0 fade-in-up">WIB FISH FARM</h1>
                <p class="mb-6 opacity-0 fade-in-up delay-200">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat.</p>
                <div class="flex space-x-4 opacity-0 fade-in-up delay-400">
                    @auth
                        <a href="/dashboard" class="bg-white text-black px-6 py-2 rounded hover:bg-gray-200 transition duration-300 hover-lift">Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="bg-white text-black px-6 py-2 rounded hover:bg-gray-200 transition duration-300 hover-lift">Daftar</a>
                        <a href="{{ route('login') }}" class="bg-transparent border border-gray-300 text-white px-6 py-2 rounded hover:bg-white/10 transition duration-300 hover-lift">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center">
                <!-- Left Content -->
                <div class="w-full md:w-1/2 mb-10 md:mb-0" data-aos="fade-right">
                    <h2 class="text-3xl font-bold text-black mb-4">WIB Fish Farm: Solusi Terbaik untuk Kebutuhan Ikan Hias Anda</h2>
                    <p class="text-gray-600">WIB Fish Farm adalah platform online yang memudahkan Anda dalam menemukan dan membeli ikan hias berkualitas. Dengan antarmuka yang user-friendly, kami menawarkan informasi lengkap tentang produk ikan.</p>
                </div>

                <!-- Right Image -->
                <div class="w-120 md:pl-10" data-aos="fade-left">
                    <img src="{{ asset('Images/Logo_WIB_FISH_FARM.png') }}" alt="WIB Fish Farm" class="animate-float">
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-black text-white" data-aos="fade-up">
        <div class="container mx-auto px-6">
            <div class="max-w-xl">
                <h2 class="text-3xl font-bold mb-4">Lakukan Pembelian Sekarang</h2>
                <p class="mb-6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique.</p>
                <div class="flex space-x-4">
                    @auth
                        <a href="/dashboard" class="bg-white text-black px-6 py-2 rounded hover:bg-gray-200 transition duration-300 hover-lift">Dashboard</a>
                    @else
                        <a href="{{ route('register') }}" class="bg-white text-black px-6 py-2 rounded hover:bg-gray-200 transition duration-300 hover-lift">Daftar</a>
                        <a href="{{ route('login') }}" class="bg-transparent border border-gray-300 text-white px-6 py-2 rounded hover:bg-white/10 transition duration-300 hover-lift">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between mb-8" data-aos="fade-up">
                <div>
                    <h2 class="text-2xl font-bold">Produk Unggulan</h2>
                    <p class="text-gray-600">Koleksi ikan hias berkualitas terbaik untuk Anda</p>
                </div>
                <a href="/produk" class="border border-gray-300 px-4 py-2 rounded text-sm hover-lift">Lihat Semua</a>
            </div>

            <div x-data="productSlider()" class="product-slider">
                <!-- Product Grid -->
                <div class="relative overflow-hidden">
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                        :class="{'slide-out-left': slideDirection === 'right' && isAnimating, 'slide-out-right': slideDirection === 'left' && isAnimating}"
                        x-show="!isAnimating || (isAnimating && (slideDirection === 'left' || slideDirection === 'right'))"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100">
                        <template x-for="product in visibleProducts()" :key="product.id">
                            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 hover-lift" data-aos="fade-up">
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
                                        @click="$parent.showAuthWithMessage('Untuk membeli produk, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.')"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 absolute top-0 left-0 w-full"
                        :class="{'slide-in-right': slideDirection === 'left' && isAnimating, 'slide-in-left': slideDirection === 'right' && isAnimating}"
                        x-show="isAnimating"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100">
                        <template x-if="slideDirection === 'right'">
                            <template x-for="product in nextPageProducts()" :key="product.id">
                                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 hover-lift">
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
                                            @click="$parent.showAuthWithMessage('Untuk membeli produk, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.')"
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                                            Tambah ke Keranjang
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </template>
                        <template x-if="slideDirection === 'left'">
                            <template x-for="product in prevPageProducts()" :key="product.id">
                                <!-- Sama seperti template di atas -->
                                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 hover-lift">
                                    <!-- ...konten sama seperti di atas... -->
                                </div>
                            </template>
                        </template>
                    </div>
                </div>

                <!-- Pagination Dots -->
                <div class="flex justify-center mt-8" data-aos="fade-up">
                    <template x-for="(page, index) in totalPages()" :key="index">
                        <button
                            @click="goToPage(index)"
                            :class="{'bg-black': currentPage === index, 'bg-gray-300': currentPage !== index}"
                            class="h-2 w-2 mx-1 rounded-full transition-colors duration-200">
                        </button>
                    </template>
                </div>

                <!-- Navigation Arrows -->
                <div class="flex justify-end mt-4 space-x-2">
                    <button @click="prevPage" :disabled="isAnimating" class="h-8 w-8 border border-gray-300 rounded-full flex items-center justify-center hover:bg-gray-100 transition disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button @click="nextPage" :disabled="isAnimating" class="h-8 w-8 border border-gray-300 rounded-full flex items-center justify-center hover:bg-gray-100 transition disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-2xl font-bold">Testimoni Pelanggan</h2>
                <p class="text-gray-600">Kami sangat senang dengan layanan yang diberikan</p>
            </div>

            <div x-data="testimonialSlider()">
                <div class="relative overflow-hidden">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6"
                        :class="{'slide-out-left': slideDirection === 'right' && isAnimating, 'slide-out-right': slideDirection === 'left' && isAnimating}"
                        x-show="!isAnimating || (isAnimating && (slideDirection === 'left' || slideDirection === 'right'))"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100">
                        <template x-for="testimonial in visibleTestimonials()" :key="testimonial.id">
                            <div class="border border-gray-200 rounded-md p-6 bg-white hover-lift" data-aos="fade-up">
                                <div class="flex items-center mb-2">
                                    <span class="text-yellow-400">★★★★★</span>
                                </div>
                                <p class="text-gray-700 mb-4" x-text="testimonial.text"></p>
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex-shrink-0"></div>
                                    <div class="ml-3">
                                        <p class="font-medium" x-text="testimonial.name"></p>
                                        <p class="text-sm text-gray-500" x-text="testimonial.location"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 absolute top-0 left-0 w-full"
                        :class="{'slide-in-right': slideDirection === 'left' && isAnimating, 'slide-in-left': slideDirection === 'right' && isAnimating}"
                        x-show="isAnimating"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100">
                        <template x-if="slideDirection === 'right'">
                            <template x-for="testimonial in nextPageTestimonials()" :key="testimonial.id">
                                <div class="border border-gray-200 rounded-md p-6 bg-white hover-lift">
                                    <!-- ...konten testimonial... -->
                                </div>
                            </template>
                        </template>
                        <template x-if="slideDirection === 'left'">
                            <template x-for="testimonial in prevPageTestimonials()" :key="testimonial.id">
                                <div class="border border-gray-200 rounded-md p-6 bg-white hover-lift">
                                    <!-- ...konten testimonial... -->
                                </div>
                            </template>
                        </template>
                    </div>
                </div>

                <!-- Pagination Dots -->
                <div class="flex justify-center mt-8" data-aos="fade-up">
                    <template x-for="(page, index) in totalPages()" :key="index">
                        <button
                            @click="goToPage(index)"
                            :class="{'bg-black': currentPage === index, 'bg-gray-300': currentPage !== index}"
                            class="h-2 w-2 mx-1 rounded-full transition-colors duration-200">
                        </button>
                    </template>
                </div>

                <!-- Navigation Arrows -->
                <div class="flex justify-between mt-8" data-aos="fade-up">
                    <button @click="prevPage" :disabled="isAnimating" class="h-8 w-8 border border-gray-300 rounded-full flex items-center justify-center hover:bg-gray-100 transition disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button @click="nextPage" :disabled="isAnimating" class="h-8 w-8 border border-gray-300 rounded-full flex items-center justify-center hover:bg-gray-100 transition disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="mb-8" data-aos="fade-up">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Hubungi</h3>
                <h2 class="text-3xl font-bold text-gray-900">Kontak Kami</h2>
                <p class="mt-2 text-gray-600">Silakan hubungi kami untuk pertanyaan lebih lanjut mengenai produk kami yang anda minati.</p>
            </div>

            <div class="flex flex-col md:flex-row">
                <div class="w-full md:w-1/3 mb-8 md:mb-0" data-aos="fade-right">
                    <div class="space-y-6">
                        <!-- Telepon -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Telepon</p>
                                <p class="text-sm text-gray-500">08113550570</p>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Toko</p>
                                <p class="text-sm text-gray-500">Jl. Danau Toba 2 No.79, Lingkungan Panji, Tegalgede, Kec. Sumbersari, Kabupaten Jember, Jawa Timur 68124</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-auto px-24" data-aos="fade-left">
                    <!-- Map Placeholder -->
                    <div class="w-auto h-[300px] bg-white rounded-lg overflow-hidden">
                        <!-- You can embed Google Maps here -->
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.4519863014166!2d113.7166225!3d-8.1571334!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd6953d1d2d4225%3A0x5414bafb1b538df8!2sFarm%20kesayangan%20bapak%20(budidaya%20ikan%20hias)!5e0!3m2!1sid!2sid!4v1744817323612!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white border-t border-gray-800 py-12">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap">
                <div class="w-full md:w-1/4 mb-8 md:mb-0">
                    <img src="{{ asset('Images/Logo_WIB_FISH_FARM.png') }}" alt="Logo" class="h-10 mb-4">
                    <p class="text-sm text-gray-400 mb-4">WIB Fish Farm adalah platform online khusus untuk memudahkan Anda dalam membeli ikan hias berkualitas tinggi.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Instagram</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
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
                        <li><a href="#" class="text-base text-gray-400 hover:text-gray-500">Ikan Koi</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-gray-500">Ikan Mas</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-gray-500">Ikan Arwana</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-gray-500">Aksesoris</a></li>
                    </ul>
                </div>

                <div class="w-full md:w-1/4 mb-8 md:mb-0">
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase mb-4">Perusahaan</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-base text-gray-400 hover:text-gray-500">Tentang Kami</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-gray-500">Karir</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-gray-500">Blog</a></li>
                    </ul>
                </div>

                <div class="w-full md:w-1/4">
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase mb-4">Hubungi Kami</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-base text-gray-400 hover:text-gray-500">Kontak</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-gray-500">FAQ</a></li>
                        <li><a href="#" class="text-base text-gray-400 hover:text-gray-500">Layanan Pelanggan</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 mt-8 text-center">
                <p class="text-sm text-gray-400">&copy; 2025 WIB Fish Farm. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
