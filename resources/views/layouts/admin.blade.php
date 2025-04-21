<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- External stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('Images/Logo_WIB_FISH_FARM.png') }}" type="image/x-icon">

    <title>{{ $title ?? 'Admin Dashboard - WIB Fish Farm' }}</title>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('styles')
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white">
            <div class="p-4 flex items-center space-x-2">
                <img src="{{ asset('Images/Logo_WIB_FISH_FARM.png') }}" alt="Logo" class="h-8 w-8">
                <div>
                    <p class="text-lg font-bold">Admin Panel</p>
                </div>
            </div>

            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <div class="flex items-center">
                        <i class="fas fa-tachometer-alt w-6"></i>
                        <span>Dashboard</span>
                    </div>
                </a>

                <a href="{{ route('admin.produk.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.produk.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                    <div class="flex items-center">
                        <i class="fas fa-fish w-6"></i>
                        <span>Manajemen Produk</span>
                    </div>
                </a>

                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    <div class="flex items-center">
                        <i class="fas fa-shopping-cart w-6"></i>
                        <span>Pesanan</span>
                    </div>
                </a>

                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    <div class="flex items-center">
                        <i class="fas fa- w-6"></i>
                        <span>Catatan Keuangan</span>
                    </div>
                </a>

                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    <div class="flex items-center">
                        <i class="fas fa-users w-6"></i>
                        <span>Ulasan</span>
                    </div>
                </a>

                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    <div class="flex items-center">
                        <i class="fas fa-chart-bar w-6"></i>
                        <span>Keluhan</span>
                    </div>
                </a>

                <a href="#" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                    <div class="flex items-center">
                        <i class="fas fa-cog w-6"></i>
                        <span>Pengaturan</span>
                    </div>
                </a>

                <!-- Logout button in sidebar -->
                <div class="mt-8 px-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center py-2.5 px-4 rounded text-white bg-red-600 hover:bg-red-700 transition duration-200">
                            <i class="fas fa-sign-out-alt w-6"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow">
                <div class="flex items-center justify-between p-4">
                    <div>
                        <h2 class="text-xl font-semibold">{{ $header ?? 'Dashboard' }}</h2>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-1 text-gray-700 focus:outline-none">
                                <div class="h-8 w-8 rounded-full bg-orange-500 flex items-center justify-center text-white">
                                    <span>{{ auth()->user()->name[0] ?? 'A' }}</span>
                                </div>
                                <span class="font-medium">{{ auth()->user()->name ?? 'Admin' }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open"
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @if(session('success') || session('error'))
                <div id="notification" class="{{ session('success') ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700' }} px-4 py-3 rounded relative mb-4 border" role="alert">
                    <strong class="font-bold">{{ session('success') ? 'Berhasil!' : 'Gagal!' }}</strong>
                    <span class="block sm:inline">{{ session('success') ?? session('error') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                        <svg onclick="document.getElementById('notification').style.display='none'" class="fill-current h-6 w-6 cursor-pointer" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                        </svg>
                    </span>
                </div>
                
                <script>
                    // Auto-hide notification after 5 seconds
                    setTimeout(function() {
                        const notification = document.getElementById('notification');
                        if (notification) {
                            notification.style.opacity = '0';
                            notification.style.transition = 'opacity 1s';
                            setTimeout(function() {
                                notification.style.display = 'none';
                            }, 1000);
                        }
                    }, 5000);
                </script>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
