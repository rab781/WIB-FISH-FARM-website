@extends('layouts.customer')

@section('content')
<div class="bg-white py-12">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Katalog Produk</h1>
            <p class="text-gray-600 max-w-2xl">Jelajahi koleksi ikan hias berkualitas terbaik dari WIB Fish Farm. Kami menyediakan berbagai jenis ikan hias premium dengan harga terjangkau.</p>
        </div>

        <!-- Filter and Search Section -->
        <div class="mb-8 flex flex-col md:flex-row justify-between space-y-4 md:space-y-0">
            <div class="flex flex-wrap gap-2">
                <button class="bg-yellow-500 text-white px-4 py-2 rounded-md">Semua</button>
                <button class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-md hover:bg-gray-50">Ikan Koi</button>
                <button class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-md hover:bg-gray-50">Ikan Mas</button>
                <button class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-md hover:bg-gray-50">Ikan Arwana</button>
                <button class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-md hover:bg-gray-50">Ikan Koki</button>
            </div>
            <div class="relative">
                <input type="text" placeholder="Cari produk..." class="border border-gray-300 rounded-md px-4 py-2 w-full md:w-64 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                <svg class="h-5 w-5 text-gray-400 absolute right-3 top-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">
            <!-- Product Card 1 -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 hover-lift">
                <div class="h-48 bg-gray-200 relative overflow-hidden">
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Ikan Koi Kohaku" class="w-full h-full object-cover">
                    <div class="absolute top-2 right-2">
                        <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">Populer</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">Ikan Koi Kohaku</h3>
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-900 font-bold">Rp 250,000</p>
                        <p class="text-sm text-gray-600">Stok: 5</p>
                    </div>
                    <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>

            <!-- Product Card 2 -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 hover-lift">
                <div class="h-48 bg-gray-200 relative overflow-hidden">
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Ikan Koi Showa" class="w-full h-full object-cover">
                    <div class="absolute top-2 right-2">
                        <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">Populer</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">Ikan Koi Showa</h3>
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-900 font-bold">Rp 350,000</p>
                        <p class="text-sm text-gray-600">Stok: 3</p>
                    </div>
                    <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>

            <!-- Product Card 3 -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 hover-lift">
                <div class="h-48 bg-gray-200 relative overflow-hidden">
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Ikan Koki Merah" class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">Ikan Koki Merah</h3>
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-900 font-bold">Rp 150,000</p>
                        <p class="text-sm text-gray-600">Stok: 10</p>
                    </div>
                    <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>

            <!-- Product Card 4 -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 hover-lift">
                <div class="h-48 bg-gray-200 relative overflow-hidden">
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Ikan Koki Calico" class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">Ikan Koki Calico</h3>
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-900 font-bold">Rp 175,000</p>
                        <p class="text-sm text-gray-600">Stok: 8</p>
                    </div>
                    <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>

            <!-- Product Card 5 -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 hover-lift">
                <div class="h-48 bg-gray-200 relative overflow-hidden">
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Arwana Super Red" class="w-full h-full object-cover">
                    <div class="absolute top-2 right-2">
                        <span class="bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">Populer</span>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">Arwana Super Red</h3>
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-900 font-bold">Rp 1,500,000</p>
                        <p class="text-sm text-gray-600">Stok: 2</p>
                    </div>
                    <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>

            <!-- Product Card 6 -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 hover-lift">
                <div class="h-48 bg-gray-200 relative overflow-hidden">
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Ikan Koi Sanke" class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">Ikan Koi Sanke</h3>
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-900 font-bold">Rp 280,000</p>
                        <p class="text-sm text-gray-600">Stok: 6</p>
                    </div>
                    <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>

            <!-- Product Card 7 -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 hover-lift">
                <div class="h-48 bg-gray-200 relative overflow-hidden">
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Ikan Mas Koki" class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">Ikan Mas Koki</h3>
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-900 font-bold">Rp 120,000</p>
                        <p class="text-sm text-gray-600">Stok: 15</p>
                    </div>
                    <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>

            <!-- Product Card 8 -->
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 hover-lift">
                <div class="h-48 bg-gray-200 relative overflow-hidden">
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Ikan Koi Butterfly" class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">Ikan Koi Butterfly</h3>
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-900 font-bold">Rp 320,000</p>
                        <p class="text-sm text-gray-600">Stok: 4</p>
                    </div>
                    <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            <nav class="flex items-center space-x-1">
                <a href="#" class="px-4 py-2 text-gray-500 bg-white rounded-md hover:bg-yellow-500 hover:text-white">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <a href="#" class="px-4 py-2 text-gray-700 bg-white rounded-md hover:bg-yellow-500 hover:text-white">1</a>
                <a href="#" class="px-4 py-2 text-white bg-yellow-500 rounded-md">2</a>
                <a href="#" class="px-4 py-2 text-gray-700 bg-white rounded-md hover:bg-yellow-500 hover:text-white">3</a>
                <a href="#" class="px-4 py-2 text-gray-700 bg-white rounded-md hover:bg-yellow-500 hover:text-white">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </nav>
        </div>
    </div>
</div>
@endsection
