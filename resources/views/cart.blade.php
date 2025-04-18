<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="//unpkg.com/alpinejs" defer></script>

    <title>Cart - WIB Fish Farm</title>
</head>
<body class="bg-gray-50" x-data="{ scrolled: false }" x-init="window.addEventListener('scroll', () => { scrolled = window.pageYOffset > 20 })">

    <!-- Include Navbar -->
    @include('partials.navbar')

    <main class="py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl font-bold mb-8">Keranjang Belanja</h1>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(count($cart) > 0)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Cart Header -->
                    <div class="grid grid-cols-12 gap-4 p-4 bg-gray-100 font-semibold text-gray-700">
                        <div class="col-span-6 sm:col-span-6">Produk</div>
                        <div class="col-span-2 sm:col-span-2 text-center">Harga</div>
                        <div class="col-span-2 sm:col-span-2 text-center">Jumlah</div>
                        <div class="col-span-2 sm:col-span-2 text-right">Subtotal</div>
                    </div>

                    <!-- Cart Items -->
                    @foreach($cart as $id => $item)
                        <div class="grid grid-cols-12 gap-4 p-4 border-b border-gray-200 items-center">
                            <div class="col-span-6 sm:col-span-6 flex items-center">
                                <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 mr-4">
                                    <img src="{{ $item['image'] ?? asset('images/placeholder.jpg') }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover object-center">
                                </div>
                                <div>
                                    <h3 class="text-gray-900 font-medium">{{ $item['name'] }}</h3>
                                    <p class="text-gray-500 text-sm">ID: {{ $item['id'] ?? $id }}</p>
                                </div>
                            </div>
                            <div class="col-span-2 sm:col-span-2 text-center">
                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </div>
                            <div class="col-span-2 sm:col-span-2 text-center">
                                {{ $item['quantity'] }}
                            </div>
                            <div class="col-span-2 sm:col-span-2 text-right">
                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                <form action="{{ route('cart.remove') }}" method="POST" class="mt-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $id }}">
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    <!-- Cart Footer -->
                    <div class="p-4 bg-gray-50">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-lg font-semibold text-gray-900">Total:</p>
                                <p class="text-gray-500">Termasuk pajak dan biaya layanan</p>
                            </div>
                            <div class="text-xl font-bold text-gray-900">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="mt-6">
                            <a href="#" class="w-full sm:w-auto bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 px-6 rounded-md text-center block sm:inline-block">
                                Lanjutkan ke Pembayaran
                            </a>
                            <a href="{{ route('products') }}" class="w-full sm:w-auto mt-4 sm:mt-0 sm:ml-4 bg-white border border-gray-300 text-gray-700 font-semibold py-3 px-6 rounded-md text-center block sm:inline-block">
                                Lanjutkan Belanja
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-gray-500 mb-4">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-medium text-gray-900 mb-2">Keranjang Anda kosong</h2>
                    <p class="text-gray-600 mb-6">Sepertinya Anda belum menambahkan apa pun ke keranjang Anda</p>
                    <a href="{{ route('products') }}" class="bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded-md">
                        Mulai Belanja
                    </a>
                </div>
            @endif
        </div>
    </main>

    <!-- Include Footer -->
    @include('partials.footer')

</body>
</html>
