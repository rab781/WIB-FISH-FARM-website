@extends('layouts.customer')

@section('content')
<!-- Auth Modal -->
@guest
    <div x-data="{ showAuthModal: false, modalMessage: 'Untuk mengakses fitur ini, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.' }"
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

    <script>
        window.showLoginModal = function(message) {
            if (typeof Alpine !== 'undefined') {
                const modal = document.querySelector('[x-data*="showAuthModal"]');
                if (modal) {
                    const scope = Alpine.$data(modal);
                    if (message) {
                        scope.modalMessage = message;
                    }
                    scope.showAuthModal = true;
                }
            }
        };
    </script>
@endguest

<!-- Hero Section -->
<section class="relative h-screen overflow-hidden">
    <!-- Video Background -->
    <div class="absolute inset-0 z-0">
        <video class="w-full h-full object-cover zoom-bg" autoplay muted loop playsinline>
            <source src="{{ asset('videos/background.mp4') }}" type="video/mp4">
            <!-- Fallback content for browsers that do not support video -->
            Your browser does not support the video tag.
        </video>
        <!-- Enhanced Overlay -->
        <div class="absolute inset-0 hero-gradient z-10">
            <div class="absolute inset-0 section-bg-pattern"></div>
        </div>
    </div>

    <!-- Content positioned above video -->
    <div class="relative z-20 container mx-auto px-12 py-4 h-full flex items-center">
        <div class="max-w-2xl text-white">
            <div class="mb-6 opacity-0 fade-in-up">
                <span class="inline-block px-4 py-2 bg-orange-500/20 backdrop-blur-sm border border-orange-300/30 rounded-full text-orange-200 text-sm font-medium mb-4">
                    🐠 Toko Ikan Hias Terpercaya
                </span>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 opacity-0 fade-in-up delay-200 leading-tight">
                WIB FISH
                <span class="text-orange-400 relative">
                    FARM
                    <div class="absolute -bottom-2 left-0 w-full h-1 bg-gradient-to-r from-orange-400 to-orange-600 rounded"></div>
                </span>
            </h1>
            <p class="text-xl mb-8 opacity-0 fade-in-up delay-400 leading-relaxed text-gray-200">
                Temukan keindahan ikan hias berkualitas untuk akuarium dan kolam Anda. Kami menyediakan berbagai jenis ikan <strong class="text-orange-300">Koi</strong> dan <strong class="text-orange-300">Koki</strong> pilihan dengan pengiriman yang aman dan terjamin ke seluruh Indonesia.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 opacity-0 fade-in-up delay-600">
                @auth
                    <a href="/produk" class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-900 font-semibold rounded-lg hover-lift shadow-lg group">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Belanja Sekarang
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 btn-gradient text-white font-semibold rounded-lg hover-lift shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Daftar Gratis
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white/30 text-white font-semibold rounded-lg hover:bg-white/10 hover-lift backdrop-blur-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Masuk
                    </a>
                @endauth
            </div>

            <!-- Stats -->
            <div class="flex flex-wrap gap-6 mt-12 opacity-0 fade-in-up delay-600">
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-400" data-count-to="{{ $stats['total_customers'] }}">0</div>
                    <div class="text-sm text-gray-300">Pelanggan</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-400" data-count-to="{{ $stats['total_products'] }}">0</div>
                    <div class="text-sm text-gray-300">Produk Ikan</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-400" data-count-to="{{ $stats['total_orders'] }}">0</div>
                    <div class="text-sm text-gray-300">Pesanan</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-400" data-count-to="{{ $stats['total_reviews'] }}">0</div>
                    <div class="text-sm text-gray-300">Ulasan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 opacity-0 fade-in-up delay-600">
        <div class="animate-pulse-custom">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
            <g fill="none" fill-rule="evenodd">
                <g fill="#f97316" fill-opacity="0.4">
                    <circle cx="36" cy="24" r="4"/>
                </g>
            </g>
        </svg>
    </div>

    <div class="container mx-auto px-6 relative">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <!-- Left Content -->
            <div class="w-full space-y-8" data-aos="fade-right">
                <div>
                    <span class="inline-block px-4 py-2 bg-orange-100 text-orange-800 rounded-full text-sm font-medium mb-4">
                        Tentang Kami
                    </span>
                    <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        WIB Fish Farm:
                        <span class="text-orange-600">Solusi Terbaik</span>
                        untuk Kebutuhan Ikan Hias Anda
                    </h2>
                    <p class="text-lg text-gray-600 leading-relaxed mb-8">
                        WIB Fish Farm adalah platform online yang memudahkan Anda dalam menemukan dan membeli ikan hias berkualitas. Dengan antarmuka yang user-friendly, kami menawarkan informasi lengkap tentang produk ikan terbaik untuk koleksi Anda.
                    </p>
                </div>

                <!-- Features -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="about-card p-6 rounded-xl hover-scale">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Kualitas Terjamin</h3>
                        <p class="text-gray-600 text-sm">Ikan berkualitas tinggi dengan kesehatan terjamin</p>
                    </div>

                    <div class="about-card p-6 rounded-xl hover-scale">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Harga Terjangkau</h3>
                        <p class="text-gray-600 text-sm">Penawaran harga terbaik untuk semua kalangan</p>
                    </div>

                    <div class="about-card p-6 rounded-xl hover-scale">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Pengiriman Aman</h3>
                        <p class="text-gray-600 text-sm">Sistem packaging khusus untuk keamanan ikan</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 cta-gradient text-white relative overflow-hidden" data-aos="fade-up">
    <!-- Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 w-72 h-72 bg-orange-500 rounded-full opacity-10 -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-orange-600 rounded-full opacity-5 translate-x-1/3 translate-y-1/3"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="mb-8">
                <span class="inline-block px-6 py-3 bg-orange-500/20 backdrop-blur-sm border border-orange-300/30 rounded-full text-orange-200 text-sm font-medium mb-6">
                    🚀 Mulai Perjalanan Anda
                </span>
                <h2 class="text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                    Lakukan Pembelian
                    <span class="text-orange-400">Sekarang</span>
                </h2>
                <p class="text-xl text-gray-300 leading-relaxed max-w-2xl mx-auto">
                    Bergabunglah dengan ribuan pelanggan yang telah mempercayai kami untuk memenuhi kebutuhan ikan hias mereka. Temukan berbagai jenis ikan <strong class="text-orange-300">Koi</strong> dan <strong class="text-orange-300">Koki</strong> berkualitas tinggi yang siap menghiasi akuarium atau kolam Anda.
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                <div class="text-center p-6 bg-white/5 backdrop-blur-sm rounded-xl border border-white/10">
                    <div class="w-16 h-16 bg-orange-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Varietas Lengkap</h3>
                    <p class="text-gray-400 text-sm">50+ jenis ikan hias pilihan</p>
                </div>

                <div class="text-center p-6 bg-white/5 backdrop-blur-sm rounded-xl border border-white/10">
                    <div class="w-16 h-16 bg-orange-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Garansi Kualitas</h3>
                    <p class="text-gray-400 text-sm">100% ikan sehat terjamin</p>
                </div>

                <div class="text-center p-6 bg-white/5 backdrop-blur-sm rounded-xl border border-white/10">
                    <div class="w-16 h-16 bg-orange-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Pengiriman Cepat</h3>
                    <p class="text-gray-400 text-sm">1-3 hari ke seluruh Indonesia</p>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="/produk" class="inline-flex items-center justify-center px-8 py-4 bg-white text-gray-900 font-semibold rounded-lg hover-lift shadow-xl group">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Lihat Produk
                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 btn-gradient text-white font-semibold rounded-lg hover-lift shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Daftar Gratis
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-transparent border-2 border-white/30 text-white font-semibold rounded-lg hover:bg-white/10 hover-lift backdrop-blur-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>

<!--Review section-->
<section class="py-20 bg-gradient-to-br from-gray-50 to-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-block px-4 py-2 bg-orange-100 text-orange-800 rounded-full text-sm font-medium mb-4">
                💬 Testimoni Pelanggan
            </span>
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">Ulasan Terbaru</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Lihat apa yang pelanggan kami katakan tentang produk dan layanan kami</p>
        </div>

        @if($ulasan->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($ulasan as $u)
                    <div class="review-card rounded-2xl p-8 shadow-lg hover-lift" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-6">
                            <!-- Rating -->
                            <div class="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($u->rating))
                                        <span class="star text-yellow-400 text-xl">★</span>
                                    @elseif($i == ceil($u->rating) && $u->rating - floor($u->rating) >= 0.5)
                                        <span class="star text-yellow-400 text-xl">★</span>
                                    @else
                                        <span class="star text-gray-300 text-xl">☆</span>
                                    @endif
                                @endfor
                            </div>
                            <!-- Verified Badge -->
                            @if($u->is_verified_purchase)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Verified
                                </span>
                            @endif
                        </div>

                        <!-- Review Text -->
                        <blockquote class="text-gray-700 text-lg leading-relaxed mb-6 line-clamp-3 italic">
                            "{{ $u->komentar }}"
                        </blockquote>

                        <!-- User Info -->
                        <div class="flex items-center mb-4">
                            <div class="h-14 w-14 rounded-full overflow-hidden flex-shrink-0 ring-4 ring-orange-100">
                                @php
                                    $colors = ['bg-blue-500', 'bg-red-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500', 'bg-indigo-500', 'bg-teal-500'];
                                    $colorIndex = ord(strtoupper(substr($u->user->name, 0, 1))) % count($colors);
                                    $initial = strtoupper(substr($u->user->name, 0, 1));
                                @endphp
                                <div class="{{ $colors[$colorIndex] }} w-full h-full flex items-center justify-center text-white font-bold text-xl">
                                    {{ $initial }}
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-gray-900 text-lg">{{ $u->user->name }}</p>
                                <p class="text-sm text-gray-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    {{ $u->produk->nama_ikan }}
                                </p>
                            </div>
                        </div>

                        <!-- Admin Reply -->
                        @if($u->balasan_admin)
                            <div class="mt-6 p-4 bg-orange-50 rounded-lg border-l-4 border-orange-400">
                                <div class="flex items-center mb-2">
                                    <div class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="ml-2 text-sm font-medium text-orange-800">Balasan Admin</span>
                                </div>
                                <p class="text-sm text-orange-700 italic">"{{ $u->balasan_admin }}"</p>
                            </div>
                        @endif

                        <!-- Review Photos -->
                        @if($u->foto_review && count($u->foto_review) > 0)
                            <div class="mt-4">
                                <div class="flex space-x-2 overflow-x-auto">
                                    @foreach($u->foto_review as $foto)
                                        @php
                                            // Handle different foto path formats
                                            $fotoPath = $foto;
                                            if (!str_starts_with($foto, 'uploads/')) {
                                                $fotoPath = 'uploads/' . $foto;
                                            }
                                        @endphp
                                        <img src="{{ asset($fotoPath) }}" alt="Review Photo" class="w-16 h-16 object-cover rounded-lg flex-shrink-0 hover:scale-110 transition-transform cursor-pointer">
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Date -->
                        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-100">
                            <p class="text-xs text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($u->created_at)->diffForHumans() }}
                            </p>

                            <!-- Rating Score -->
                            <div class="flex items-center">
                                <span class="text-2xl font-bold text-orange-500">{{ number_format($u->rating, 1) }}</span>
                                <span class="text-sm text-gray-500 ml-1">/5</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16" data-aos="fade-up">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Ulasan</h3>
                <p class="text-gray-600 mb-6">Jadilah yang pertama memberikan ulasan untuk produk kami!</p>
                @auth
                    <a href="/produk" class="inline-flex items-center px-6 py-3 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 transition duration-300">
                        Mulai Berbelanja
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endauth
            </div>
        @endif
    </div>
</section>

<!-- Contact Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-6">
        <div class="mb-12 text-center" data-aos="fade-up">
            <span class="inline-block px-4 py-2 bg-orange-100 text-orange-800 rounded-full text-sm font-medium mb-4">
                📞 Hubungi Kami
            </span>
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">Kontak Kami</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Silakan hubungi kami untuk pertanyaan lebih lanjut mengenai produk kami yang anda minati</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-12">
            <div class="w-full lg:w-1/3 space-y-8" data-aos="fade-right">
                <!-- Contact Information -->
                <div class="space-y-6">
                    <!-- Telepon -->
                    <div class="contact-item group">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 p-3 bg-orange-100 rounded-xl group-hover:bg-orange-200 transition-colors">
                                <svg class="h-6 w-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-semibold text-gray-900 mb-1">Telepon</p>
                                <a href="tel:08113550570" class="text-gray-600 hover:text-orange-600 transition-colors text-lg">+62 878-8942-6560</a>
                                <p class="text-sm text-gray-500 mt-1">Senin - Jum'at, 08:00 - 20:00</p>
                            </div>
                        </div>
                    </div>

                    <!-- WhatsApp -->
                    <div class="contact-item group">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 p-3 bg-green-100 rounded-xl group-hover:bg-green-200 transition-colors">
                                <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.108"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-semibold text-gray-900 mb-1">WhatsApp</p>
                                <a href="https://wa.me/6287889426560" target="_blank" class="text-gray-600 hover:text-green-600 transition-colors text-lg">+62 878-8942-6560</a>
                                <p class="text-sm text-gray-500 mt-1">Chat langsung dengan kami</p>
                            </div>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="contact-item group">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 p-3 bg-blue-100 rounded-xl group-hover:bg-blue-200 transition-colors">
                                <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-semibold text-gray-900 mb-1">Alamat Toko</p>
                                <p class="text-gray-600 text-lg leading-relaxed">Jl. Danau Toba 2 No.79, Lingkungan Panji, Tegalgede, Kec. Sumbersari, Kabupaten Jember, Jawa Timur 68124</p>
                                <p class="text-sm text-gray-500 mt-2">Kunjungi toko fisik kami</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="pt-8 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Ikuti Kami</h3>
                    <div class="flex space-x-4">

                        <a href="https://www.instagram.com/wibfishfarm/" class="p-3 bg-pink-100 rounded-lg hover:bg-pink-200 transition-colors group">
                            <svg class="w-5 h-5 text-pink-600 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                            </svg>
                        </a>

                    </div>
                </div>
            </div>

            <!-- Map -->
            <div class="w-full lg:w-2/3" data-aos="fade-left">
                <div class="h-full min-h-[600px] bg-white rounded-2xl overflow-hidden shadow-xl ring-1 ring-gray-200">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.4519863014166!2d113.7166225!3d-8.1571334!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd6953d1d2d4225%3A0x5414bafb1b538df8!2sFarm%20kesayangan%20bapak%20(budidaya%20ikan%20hias)!5e0!3m2!1sid!2sid!4v1744817323612!5m2!1sid!2sid"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="hover:scale-105 transition-transform duration-700">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
