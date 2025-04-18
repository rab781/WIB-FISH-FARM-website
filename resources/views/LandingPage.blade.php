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

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('Images/Logo_WIB_FISH_FARM.png') }}" type="image/x-icon">

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
                <a href="{{ route('login') }}" class="bg-orange-600 text-white py-2 px-4 rounded-md text-center hover:bg-orange-700 transition">Masuk</a>
                <a href="{{ route('register') }}" class="border border-gray-300 py-2 px-4 rounded-md text-center hover:bg-gray-50 transition">Daftar</a>
                <button @click="showAuthModal = false" class="text-gray-500 hover:text-gray-800 text-sm mt-2">Tutup</button>
            </div>
        </div>
    </div>
    @endguest

    <!-- Navbar -->
    @include('partials.navbar')

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
            <div class="flex items-center justify-between mb-8">
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
                        :class="{'slide-out-right': isAnimating}"
                        x-show="!isAnimating || (isAnimating)"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100">
                        <template x-for="product in visibleProducts()" :key="product.id">
                            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 hover-lift" data-aos="fade-up">
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
                                        @click="$parent.showAuthWithMessage('Untuk membeli produk, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.')"
                                        class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 absolute top-0 left-0 w-full"
                        :class="{'slide-in-right': isAnimating}"
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
                                            @click="$parent.showAuthWithMessage('Untuk membeli produk, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.')"
                                            class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                                            Tambah ke Keranjang
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </template>
                        <template x-if="slideDirection === 'left'">
                            <template x-for="product in prevPageProducts()" :key="product.id">
                                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 hover-lift">
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
                                            @click="$parent.showAuthWithMessage('Untuk membeli produk, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.')"
                                            class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                                            Tambah ke Keranjang
                                        </button>
                                    </div>
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
                                    <span class="text-orange-400">★★★★★</span>
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
                    <div class="w-auto h-[600px] bg-white rounded-lg overflow-hidden">
                        <!-- You can embed Google Maps here -->
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.4519863014166!2d113.7166225!3d-8.1571334!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd6953d1d2d4225%3A0x5414bafb1b538df8!2sFarm%20kesayangan%20bapak%20(budidaya%20ikan%20hias)!5e0!3m2!1sid!2sid!4v1744817323612!5m2!1sid!2sid" width="600" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('partials.footer')
</body>
</html>
