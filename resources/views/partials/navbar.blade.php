<!-- Navbar -->
<header class="bg-white border-b border-gray-200 sticky top-0 z-50 transition-all duration-300" :class="{'shadow-lg': scrolled}">
    <div class="container mx-auto px-4 py-2">
        <div class="flex items-center justify-between">
            <!-- Navigation Links - For Desktop -->
            <nav class="hidden md:flex space-x-8 gap-x-2 items-center">
                <div class="flex items-center">
                    <a href="{{ auth()->check() ? '/produk' : '/' }}" class="flex items-center">
                        <img class="h-12" src="{{ asset('Images/Logo_WIB_FISH_FARM.png') }}" alt="WIB Fish Farm">
                    </a>
                </div>
                <a href="/" class="text-gray-700 hover:text-orange-600 font-medium">Beranda</a>
                <a href="/produk" class="text-gray-700 hover:text-orange-600 font-medium">Produk</a>
                <a href="/tentang-kami" class="text-gray-700 hover:text-orange-600 font-medium">Tentang Kami</a>
            </nav>

            <!-- Right Buttons -->
            <div class="flex items-center space-x-2 gap-x-4">
                @auth
                    <!-- Cart button for authenticated users -->
                    <a href="{{ route('cart.view') }}" class="text-gray-700 hover:text-orange-600 relative" x-data="{ cartCount: 0 }" x-init="
                        fetch('{{ route('cart.count') }}')
                            .then(response => response.json())
                            .then(data => { cartCount = data.count })
                    ">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span x-show="cartCount > 0" x-text="cartCount" class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                        </span>
                    </a>

                    <!-- Notification button for authenticated users -->
                    <a href="#" class="text-gray-700 hover:text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </a>

                    <!-- Profile dropdown menu for authenticated users -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center focus:outline-none">
                            <div class="h-8 w-8 rounded-full bg-orange-500 flex items-center justify-center text-white overflow-hidden border-2 border-white">
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
                    <button @click="showAuthWithMessage('Untuk mengakses keranjang belanja, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.')" class="text-gray-700 hover:text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </button>
                    <button @click="showAuthWithMessage('Untuk mengakses notifikasi, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.')" class="text-gray-700 hover:text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </button>
                    <a href="{{ route('register') }}" class="border border-gray-300 px-4 py-1 rounded text-gray-700 hover:border-orange-600 hover:text-orange-600">Daftar</a>
                    <a href="{{ route('login') }}" class="bg-black text-white px-4 py-1 rounded hover:border-orange-600 hover:text-orange-600 border-white-300 hover:bg-gray-300">Masuk</a>
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
            <a href="/produk" class="block py-2 text-gray-700 hover:text-orange-600">Produk</a>
            <a href="/tentang-kami" class="block py-2 text-gray-700 hover:text-orange-600">Tentang Kami</a>
        </div>
    </div>
</header>
