<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles.css">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="//unpkg.com/alpinejs" defer></script>
    <title>WIB Fish Farm</title>
</head>
<body class="bg-white">
    <!-- Navbar -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="container mx-auto px-4 py-2">
            <div class="flex items-center justify-between">

                <!-- Navigation Links - For Desktop -->
                <nav class="hidden md:flex  space-x-6 gap-x-4 items-center">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center">
                            <img class="h-12" src="{{ asset('Images/Logo_WIB_FISH_FARM.png') }}" alt="WIB Fish Farm">
                        </a>
                    </div>
                    <a href="/produk" class="text-gray-700 hover:text-blue-600 font-medium">Produk</a>
                    <a href="/edukasi-ikan" class="text-gray-700 hover:text-blue-600 font-medium">Edukasi Ikan</a>
                    <a href="/tentang-kami" class="text-gray-700 hover:text-blue-600 font-medium">Tentang Kami</a>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center text-gray-700 hover:text-blue-600 font-medium">
                            Layanan Kami
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Layanan 1</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Layanan 2</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Layanan 3</a>
                        </div>
                    </div>
                </nav>

                <!-- Right Buttons -->
                <div class="flex items-center space-x-8 gap-x-4">
                    <a href="/cart" class="text-gray-700 hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </a>
                    <a href="/notifications" class="text-gray-700 hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </a>
                    <a href="/daftar" class="border border-gray-300 px-4 py-1 rounded text-gray-700 hover:border-blue-600 hover:text-blue-600">Daftar</a>
                    <a href="/masuk" class="bg-black text-black px-4 py-1 rounded hover:bg-gray-800 border-black-300">Masuk</a>
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
                <a href="/edukasi-ikan" class="block py-2 text-gray-700 hover:text-blue-600">Edukasi Ikan</a>
                <a href="/tentang-kami" class="block py-2 text-gray-700 hover:text-blue-600">Tentang Kami</a>
                <a href="/layanan-kami" class="block py-2 text-gray-700 hover:text-blue-600">Layanan Kami</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative h-screen overflow-hidden">
        <!-- Video Background -->
        <div class="absolute inset-0 z-0">
            <video class="w-full h-full object-cover" autoplay muted loop playsinline>
                <source src="{{ asset('videos/background.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <!-- Overlay to improve text readability -->
            <div class="absolute inset-0 bg-black bg-opacity-70"></div>
        </div>

        <!-- Content positioned above video -->
        <div class="relative z-10 container mx-auto px-4 py-4 h-full flex items-center">
            <div class="max-w-xl text-white">
                <h1 class="text-4xl font-bold mb-4">WIB FISH FARM</h1>
                <p class="mb-6">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse varius enim in eros elementum tristique. Duis cursus, mi quis viverra ornare, eros dolor interdum nulla, ut commodo diam libero vitae erat.</p>
                <div class="flex space-x-4">
                    <button class="bg-white text-black px-6 py-2 rounded hover:bg-gray-100 transition duration-300">Button</button>
                    <button class="bg-transparent border border-white text-black px-6 py-2 rounded hover:bg-white/10 transition duration-300">Button</button>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-button').addEventListener('click', function() {
            document.querySelector('.mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>
</html>
