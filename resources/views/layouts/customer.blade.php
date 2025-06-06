<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('Images/Logo_WIB_FISH_FARM.png') }}" type="image/x-icon">

    <!-- Preload critical fonts -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>

    <!-- External stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" media="print" onload="this.media='all'">

    <!-- Preload critical CSS -->
    <link rel="preload" href="{{ asset('css/landing-animations.css') }}" as="style">
    <link href="{{ asset('css/landing-animations.css') }}" rel="stylesheet">

    <!-- Load AOS CSS directly to prevent FOUC -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Title -->
    <title>{{ $title ?? 'WIB Fish Farm' }}</title>

    <!-- Scripts - Deferred to improve page load speed -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="{{ asset('js/landing.js') }}" defer></script>
    <script>
        // Menyediakan data produk dari database ke JavaScript
        const productData = @json($produk ?? []);
        const testimonialData = @json(isset($ulasan) ? $ulasan : []);

        // Initialize AOS immediately
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-out',
                once: true,
                mirror: false,
                disable: window.innerWidth < 768 ? true : false
            });
        });
    </script>

    @stack('styles')
</head>
<body class="bg-white"
      x-data="appState()"
      x-init="init()">

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    <!-- Auth Modal for Guest Users -->
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
        id="authGlobalModal"
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
        function showAuthWithMessage(message) {
            const modal = document.getElementById('authGlobalModal');
            if (modal && typeof Alpine !== 'undefined') {
                const data = Alpine.$data(modal);
                if (data) {
                    if (message) {
                        data.modalMessage = message;
                    }
                    data.showAuthModal = true;
                }
            }
        }
    </script>
    @endguest

    <!-- Lazy-load the footer content -->
    @include('partials.footer')

    @stack('scripts')
</body>
</html>
