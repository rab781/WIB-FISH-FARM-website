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

    <!-- jQuery first, then other scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('styles')
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="p-4 flex items-center space-x-2 border-b border-gray-700">
                <img src="{{ asset('Images/Logo_WIB_FISH_FARM.png') }}" alt="Logo" class="h-8 w-8">
                <div>
                    <p class="text-lg font-bold">Admin Panel</p>
                    <p class="text-xs text-gray-400">WIB Fish Farm</p>
                </div>
            </div>

            <nav class="mt-4 flex flex-col h-full">
                <div class="flex-1">
                    <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <div class="flex items-center">
                            <i class="fas fa-tachometer-alt w-6 mr-3"></i>
                            <span>Dashboard</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.produk.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.produk.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                        <div class="flex items-center">
                            <i class="fas fa-fish w-6 mr-3"></i>
                            <span>Manajemen Produk</span>
                        </div>
                    </a>

                    <!-- Enhanced Order Management -->
                    <div class="py-2">
                        <div class="px-4 text-xs uppercase text-gray-400 font-semibold mb-2">Order Management</div>

                        <a href="{{ route('admin.pesanan.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.pesanan.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                            <div class="flex items-center">
                                <i class="fas fa-box w-6 mr-3"></i>
                                <span>Pesanan</span>
                            </div>
                        </a>

                        <a href="{{ route('admin.pengembalian.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.pengembalian.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                            <div class="flex items-center">
                                <i class="fas fa-undo w-6 mr-3"></i>
                                <span>Pengembalian</span>
                            </div>
                        </a>

                        <a href="{{ route('admin.reviews.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.reviews.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                            <div class="flex items-center">
                                <i class="fas fa-star w-6 mr-3"></i>
                                <span>Ulasan</span>
                            </div>
                        </a>
                    </div>

                    <!-- Reports & Analytics -->
                    <div class="py-2">
                        <div class="px-4 text-xs uppercase text-gray-400 font-semibold mb-2">Reports</div>

                        <a href="{{ route('admin.reports.sales') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.reports.sales') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                            <div class="flex items-center">
                                <i class="fas fa-shopping-cart w-6 mr-3"></i>
                                <span>Laporan Penjualan</span>
                            </div>
                        </a>

                        <a href="{{ route('admin.reports.financial') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.reports.financial') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                            <div class="flex items-center">
                                <i class="fas fa-money-bill-wave w-6 mr-3"></i>
                                <span>Catatan Keuangan</span>
                            </div>
                        </a>
                    </div>

                    <!-- Customer Management -->
                    <div class="py-2">
                        <div class="px-4 text-xs uppercase text-gray-400 font-semibold mb-2">Customer</div>

                        <a href="{{ route('admin.keluhan.index') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('admin.keluhan.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }}">
                            <div class="flex items-center">
                                <i class="fas fa-comment-alt w-6 mr-3"></i>
                                <span>Keluhan</span>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Logout button at bottom -->
                <div class="mt-auto p-4 border-t border-gray-700">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center py-3 px-4 rounded-lg text-white bg-red-600 hover:bg-red-700 transition duration-200 font-medium shadow-lg hover:shadow-xl">
                            <i class="fas fa-sign-out-alt w-5 mr-3"></i>
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
                        <!-- Admin Notification Component -->
                        @include('components.admin-notification-dropdown')

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
                                    <a href="{{ route('admin.profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
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
                            <path d="M14.348 14.849a1.2 1.2 1 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
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

    <!-- Admin modal system - globally available -->
    <script src="{{ asset('js/admin-modal.js') }}"></script>

    @stack('scripts')
    <script>
        window.updateStatus = function(orderId, status) {
            console.log('updateStatus function is called with orderId:', orderId, 'and status:', status);
            const statusMapping = {
                'Menunggu Konfirmasi': 'Pembayaran Dikonfirmasi',
                'Sedang Diproses': 'Diproses',
                'Sedang Dikirim': 'Dikirim',
                'Dibatalkan': 'Dibatalkan',
                'Karantina': 'Karantina',
                'Pengembalian': 'Pengembalian'
            };

            const actualStatus = statusMapping[status] || status;

            if (confirm('Apakah Anda yakin ingin mengubah status pesanan #' + orderId + ' menjadi "' + status + '"?')) {
                const form = document.getElementById('statusForm-' + orderId);
                // Use the POST route which is properly defined
                console.log('Setting form action and method');
                form.setAttribute('action', '{{ route("admin.pesanan.updateStatus", "__id__") }}'.replace('__id__', orderId));
                form.setAttribute('method', 'POST');
                console.log('Form method after setting:', form.getAttribute('method'));

                // Clear out any existing hidden fields to prevent duplicates
                const hiddenInputs = form.querySelectorAll('input[type="hidden"]');
                console.log('Found hidden inputs:', hiddenInputs.length);

                hiddenInputs.forEach(input => {
                    console.log('Input field:', input.name, input.value);
                    if (input.name !== '_token') {
                        console.log('Removing input:', input.name);
                        input.remove();
                    }
                });

                // Make sure we have CSRF token
                if (!form.querySelector('input[name="_token"]')) {
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                }

                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status_pesanan';
                statusInput.value = actualStatus;
                form.appendChild(statusInput);

                // Ensure there is no _method field (this would enable method spoofing)
                if (form.querySelector('input[name="_method"]')) {
                    console.log('Found _method field, removing it');
                    form.querySelector('input[name="_method"]').remove();
                }

                console.log('Final form elements before submission:');
                const allInputs = form.querySelectorAll('input[type="hidden"]');
                allInputs.forEach(input => {
                    console.log('- Input:', input.name, '=', input.value);
                });

                form.submit();
            }
        };

        console.log('updateStatus function is globally accessible:', typeof window.updateStatus === 'function');
    </script>
</body>

</html>
