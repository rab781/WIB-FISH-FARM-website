<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Tambahkan CSRF Token untuk AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    <title>{{ $title ?? 'WIB Fish Farm' }}</title>

    @stack('styles')
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

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
    <!-- Tambahkan yield scripts agar section scripts bisa dirender -->
    @yield('scripts')

    <!-- Script keranjang-->
    <script>
    function handleQuantityChange(cartItemId, action) {
        const quantityInput = document.getElementById(`quantity-${cartItemId}`);
        let currentQuantity = parseInt(quantityInput.value);

        if (action === 'increase') {
            currentQuantity += 1;
        } else if (action === 'decrease') {
            if (currentQuantity > 1) {
                currentQuantity -= 1;
            } else {
                if (confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                    const deleteForm = document.querySelector(`.flex.flex-col.md\\:flex-row input[value="${cartItemId}"]`)
                        .closest('.flex.flex-col.md\\:flex-row')
                        .querySelector('.delete-form');
                    if (deleteForm) {
                        deleteForm.submit();
                    }
                }
                return;
            }
        }

        // Update nilai di input
        quantityInput.value = currentQuantity;

        // Kirim request update ke server
        const formData = new FormData();
        formData.append('jumlah', currentQuantity);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch(`/keranjang/${cartItemId}/update`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Sukses, reload halaman untuk memperbarui data
                window.location.reload();
            } else {
                // Gagal, tampilkan pesan error
                alert(data.message || 'Terjadi kesalahan saat memperbarui kuantitas');
                // Kembalikan nilai input ke nilai semula
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memperbarui kuantitas');
            window.location.reload();
        });
    }

    // Fungsi lama yang mungkin masih digunakan di tempat lain
    function updateQuantity(cartItemId, increase) {
        handleQuantityChange(cartItemId, increase ? 'increase' : 'decrease');
    }
    </script>
</body>
</html>
