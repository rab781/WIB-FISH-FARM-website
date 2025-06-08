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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>{{ $title ?? 'WIB Fish Farm' }}</title>

    @stack('styles')
</head>
<body class="bg-white"
      x-data="appState()"
      x-init="init()">

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
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus produk ini dari keranjang?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const deleteForm = document.querySelector(`.flex.flex-col.md\\:flex-row input[value="${cartItemId}"]`)
                            .closest('.flex.flex-col.md\\:flex-row')
                            .querySelector('.delete-form');
                        if (deleteForm) {
                            deleteForm.submit();
                        }
                    }
                });
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
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Terjadi kesalahan saat memperbarui kuantitas',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                // Kembalikan nilai input ke nilai semula
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Terjadi kesalahan saat memperbarui kuantitas',
                icon: 'error',
                confirmButtonText: 'OK'
            });
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
