@extends('layouts.customer')

@section('content')
<div class="container mx-auto px-6 py-8">
    <nav class="flex mb-5" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-orange-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a href="{{ route('produk') }}" class="ml-1 text-gray-700 hover:text-orange-600 md:ml-2">Produk</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-gray-500 md:ml-2">{{ $produk->nama_ikan }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Notification Alert -->
    <div id="cartAlert" class="hidden fixed top-4 right-4 z-50 max-w-sm">
        <div id="successAlert" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline ml-2">Produk ditambahkan ke keranjang.</span>
            </div>
            <button type="button" class="absolute top-0 right-0 p-2" onclick="hideAlert('successAlert')">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 011.414 1.414L11.414 10l4.293 4.293a1 1 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        <div id="errorAlert" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <strong class="font-bold">Gagal!</strong>
                <span id="errorMessage" class="block sm:inline ml-2">Terjadi kesalahan.</span>
            </div>
            <button type="button" class="absolute top-0 right-0 p-2" onclick="hideAlert('errorAlert')">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 011.414 1.414L11.414 10l4.293 4.293a1 1 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>

    <div class="flex flex-col md:flex-row -mx-4">
        <!-- Product Image Gallery -->
        <div class="md:w-1/2 px-4 mb-6 md:mb-0" data-aos="fade-right">
            <div class="bg-white rounded-lg overflow-hidden shadow-md p-4">
                <div class="bg-gray-100 rounded-lg overflow-hidden h-96 relative">
                    @if(Str::startsWith($produk->gambar, 'uploads/'))
                        <img src="{{ asset($produk->gambar) }}" class="w-full h-full object-contain" alt="{{ $produk->nama_ikan }}">
                    @else
                        <img src="{{ asset('storage/' . $produk->gambar) }}" class="w-full h-full object-contain" alt="{{ $produk->nama_ikan }}">
                    @endif
                    @if($produk->popularity >= 4)
                    <div class="absolute top-4 right-4">
                        <span class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full">Populer</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="md:w-1/2 px-4" data-aos="fade-left">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $produk->nama_ikan }}</h1>

            <div class="flex items-center mb-4">
                <div class="flex text-orange-400">
                    @for($i = 0; $i < 5; $i++)
                        @if($i < floor($avgRating ?? 0))
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg>
                        @elseif($i < ($avgRating ?? 0))
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27z"></path></svg>
                        @else
                            <svg class="w-5 h-5 fill-current text-gray-300" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg>
                        @endif
                    @endfor
                </div>
                <span class="ml-2 text-sm text-gray-600">{{ ($ulasan && $ulasan instanceof Countable) ? $ulasan->count() : 0 }} ulasan</span>
            </div>

            <div class="text-3xl font-bold text-gray-900 mb-6">
                Rp {{ number_format($produk->harga, 0, ',', '.') }}
            </div>

            <div class="border-t border-gray-200 py-4">
                <h2 class="text-lg font-semibold mb-2">Deskripsi</h2>
                <p class="text-gray-700 mb-4">{{ $produk->deskripsi }}</p>
            </div>

            <div class="flex flex-wrap border-t border-gray-200 py-4">
                <div class="w-1/2 mb-4">
                    <h3 class="text-sm text-gray-500">Jenis Ikan</h3>
                    <p class="font-medium text-gray-900">{{ $produk->jenis_ikan }}</p>
                </div>
                <div class="w-1/2 mb-4">
                    <h3 class="text-sm text-gray-500">Stok</h3>
                    <p class="font-medium text-gray-900">{{ $produk->stok }} tersedia</p>
                </div>
            </div>

            <div class="mt-6 border-t border-gray-200 pt-4">
                @auth
                <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm" class="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $produk->id_Produk }}">

                    <div class="flex items-center mb-4">
                        <label for="quantity" class="w-1/4 text-gray-700">Jumlah:</label>
                        <div class="flex items-center">
                            <button type="button" class="bg-gray-200 text-gray-700 hover:bg-gray-300 h-10 w-10 rounded-l-md flex items-center justify-center" onclick="decrementQuantity()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            </button>
                            <input type="number" name="quantity" id="quantity" min="1" max="{{ $produk->stok }}" value="1" class="h-10 w-16 border-gray-200 text-center focus:ring-orange-500 focus:border-orange-500">
                            <button type="button" class="bg-gray-200 text-gray-700 hover:bg-gray-300 h-10 w-10 rounded-r-md flex items-center justify-center" onclick="incrementQuantity()">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="addToCartBtn" class="w-full bg-orange-600 hover:bg-orange-700 text-white py-3 px-6 rounded-md font-medium transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Tambah ke Keranjang
                    </button>
                </form>
                @else
                <button onclick="showAuthModal()" class="w-full bg-orange-600 hover:bg-orange-700 text-white py-3 px-6 rounded-md font-medium transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Tambah ke Keranjang
                </button>

                <!-- Auth Modal -->
                <div x-data="{ showAuthModal: false, modalMessage: 'Untuk membeli produk, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.' }"
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
                @endauth
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-12 border-t border-gray-200 pt-8">
        <h2 class="text-2xl font-bold mb-6">Ulasan Pelanggan</h2>

        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold">Rating Rata-rata</h3>
                    <div class="flex items-center mt-2">
                        <div class="flex text-orange-400">
                            @for($i = 0; $i < 5; $i++)
                                @if($i < floor($avgRating ?? 0))
                                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27z"></path></svg>
                                @elseif($i < ($avgRating ?? 0))
                                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27z"></path></svg>
                                @else
                                    <svg class="w-6 h-6 fill-current text-gray-300" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg>
                                @endif
                            @endfor
                            <span class="ml-2 text-lg font-bold text-gray-900">{{ number_format($avgRating ?? 0, 1) }}</span>
                        </div>
                        <span class="ml-4 text-gray-600">dari {{ ($ulasan && $ulasan instanceof Countable) ? $ulasan->count() : 0 }} ulasan</span>
                    </div>
                </div>

                @auth
                    @if($userHasPurchased && !$userHasReviewed)
                    <div>
                        <button onclick="document.getElementById('reviewForm').classList.toggle('hidden')" class="bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-md font-medium transition">
                            Tulis Ulasan
                        </button>
                    </div>
                    @endif
                @endauth
            </div>

            @auth
                @if($userHasPurchased && !$userHasReviewed)
                <div id="reviewForm" class="hidden border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-medium mb-4">Bagikan pengalaman Anda</h4>
                    <form action="{{ route('ulasan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_produk" value="{{ $produk->id_Produk }}">

                        <div class="mb-4">
                            <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})" class="star-btn p-1">
                                    <svg id="star-{{ $i }}" class="w-8 h-8 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                                    </svg>
                                </button>
                                @endfor
                                <input type="hidden" name="rating" id="rating-input" value="0">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="komentar" class="block text-sm font-medium text-gray-700 mb-1">Komentar</label>
                            <textarea name="komentar" id="komentar" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500" placeholder="Bagikan pengalaman Anda dengan produk ini"></textarea>
                        </div>

                        <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-md font-medium transition">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
                @endif
            @endauth

            <!-- List of reviews -->
            <div class="mt-8 space-y-6">
                @forelse($ulasan as $review)
                <div class="border-b border-gray-200 pb-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center">
                                <div class="flex text-orange-400">
                                    @for($i = 0; $i < 5; $i++)
                                        @if($i < $review->rating)
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg>
                                        @else
                                            <svg class="w-5 h-5 fill-current text-gray-300" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-2 text-sm font-bold text-gray-900">{{ $review->user->name }}</span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="text-gray-700">{{ $review->komentar }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    <p class="mt-2 text-gray-500">Belum ada ulasan untuk produk ini</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if(isset($relatedProducts) && is_countable($relatedProducts) && count($relatedProducts) > 0)
    <div class="mt-12 border-t border-gray-200 pt-8">
        <h2 class="text-2xl font-bold mb-6">Produk Terkait</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
            <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300">
                <a href="{{ route('detailProduk', $related->id_Produk) }}">
                    <div class="h-48 bg-gray-200 relative overflow-hidden">
                        <img src="{{ asset('storage/' . $related->gambar) }}" alt="{{ $related->nama_ikan }}" class="w-full h-full object-cover">
                        @if(isset($related->popularity) && $related->popularity >= 4)
                            <div class="absolute top-2 right-2">
                                <span class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full">Populer</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <h3 class="font-medium text-gray-900 mb-1">{{ $related->nama_ikan }}</h3>
                        <p class="text-gray-900 font-bold">Rp {{ number_format($related->harga, 0, ',', '.') }}</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Auth Modal for Guest Users -->
@guest
<div x-data="{ showAuthModal: false, modalMessage: 'Untuk membeli produk, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.' }"
    x-show="showAuthModal"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-90"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-90"
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-200/90 backdrop-filter backdrop-blur-md"
    @click.self="showAuthModal = false"
    id="authModal"
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
@endguest

<script>
    function setRating(value) {
        document.getElementById('rating-input').value = value;

        // Update star colors
        for (let i = 1; i <= 5; i++) {
            const star = document.getElementById('star-' + i);
            if (i <= value) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-orange-400');
            } else {
                star.classList.remove('text-orange-400');
                star.classList.add('text-gray-300');
            }
        }
    }

    function incrementQuantity() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.getAttribute('max'));
        const current = parseInt(input.value);

        if (current < max) {
            input.value = current + 1;
        }
    }

    function decrementQuantity() {
        const input = document.getElementById('quantity');
        const current = parseInt(input.value);

        if (current > 1) {
            input.value = current - 1;
        }
    }

    function showAuthModal() {
        // Menggunakan ID spesifik untuk mendapatkan modal auth yang benar
        const authModal = document.getElementById('authModal');
        if (authModal && typeof Alpine !== 'undefined') {
            // Mendapatkan data Alpine dari elemen modal
            const data = Alpine.$data(authModal);
            if (data) {
                data.showAuthModal = true;
            }
        }
    }

    function hideAuthModal() {
        document.querySelector('[x-data]').__x.$data.showAuthModal = false;
    }

    function showAlert(type, message = '') {
        const cartAlert = document.getElementById('cartAlert');
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');

        cartAlert.classList.remove('hidden');

        if (type === 'success') {
            successAlert.classList.remove('hidden');
            errorAlert.classList.add('hidden');
        } else {
            errorAlert.classList.remove('hidden');
            successAlert.classList.add('hidden');
            if (message) {
                document.getElementById('errorMessage').textContent = message;
            }
        }

        // Auto hide after 4 seconds
        setTimeout(() => {
            hideAlert(type === 'success' ? 'successAlert' : 'errorAlert');
        }, 4000);
    }

    function hideAlert(alertId) {
        document.getElementById(alertId).classList.add('hidden');
        const cartAlert = document.getElementById('cartAlert');

        // Check if both alerts are hidden
        if (document.getElementById('successAlert').classList.contains('hidden') &&
            document.getElementById('errorAlert').classList.contains('hidden')) {
            cartAlert.classList.add('hidden');
        }
    }

    // Handle the form submission for adding to cart
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('addToCartForm');

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Detail form submitted', this.action); // Debug: memastikan event handler terpanggil

                // Ambil CSRF token dari meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams(new FormData(this))
                })
                .then(response => {
                    console.log('Response status:', response.status); // Debug response status
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data); // Debug response data
                    if (data.success) {
                        showAlert('success');
                        // Update cart count badge
                        if (data.count !== undefined) {
                            updateCartCount(data.count);
                        }
                        // Dispatch event to update cart badge
                        window.dispatchEvent(new Event('cartUpdated'));
                    } else {
                        showAlert('error', data.message || 'Terjadi kesalahan saat menambahkan produk ke keranjang.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Terjadi kesalahan. Silakan coba lagi.');
                });
            });
        }
    });

    function updateCartCount(count) {
        const cartBadge = document.querySelector('.cart-count-badge');
        if (cartBadge) {
            cartBadge.textContent = count;
            cartBadge.classList.remove('hidden');
        }
    }
</script>
@endsection
