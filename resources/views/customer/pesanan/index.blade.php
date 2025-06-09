@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Shopee-style Status Filter Tabs -->
    <div class="bg-white shadow-sm overflow-hidden">
        <div class="flex overflow-x-auto border-b border-gray-200">
            <a href="{{ route('pesanan.index') }}" class="px-6 py-4 text-center whitespace-nowrap font-medium {{ !request('status') ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-600 hover:text-gray-900' }}">
                Semua
            </a>
            <a href="{{ route('pesanan.index', ['status' => 'Menunggu Pembayaran']) }}" class="px-6 py-4 text-center whitespace-nowrap font-medium {{ request('status') == 'Menunggu Pembayaran' ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-600 hover:text-gray-900' }}">
                Belum Bayar
            </a>
            <a href="{{ route('pesanan.index', ['status' => 'Pembayaran Dikonfirmasi']) }}" class="px-6 py-4 text-center whitespace-nowrap font-medium {{ in_array(request('status'), ['Pembayaran Dikonfirmasi', 'Diproses']) ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-600 hover:text-gray-900' }}">
                Sedang Dikemas
            </a>
            <a href="{{ route('pesanan.index', ['status' => 'Dikirim']) }}" class="px-6 py-4 text-center whitespace-nowrap font-medium {{ request('status') == 'Dikirim' ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-600 hover:text-gray-900' }}">
                Dikirim
            </a>
            <a href="{{ route('pesanan.index', ['status' => 'Selesai']) }}" class="px-6 py-4 text-center whitespace-nowrap font-medium {{ request('status') == 'Selesai' ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-600 hover:text-gray-900' }}">
                Selesai
            </a>
            <a href="{{ route('pesanan.index', ['status' => 'Dibatalkan']) }}" class="px-6 py-4 text-center whitespace-nowrap font-medium {{ request('status') == 'Dibatalkan' ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-600 hover:text-gray-900' }}">
                Dibatalkan
            </a>
            <a href="{{ route('pesanan.index', ['status' => 'Pengembalian']) }}" class="px-6 py-4 text-center whitespace-nowrap font-medium {{ request('status') == 'Pengembalian' ? 'text-orange-500 border-b-2 border-orange-500' : 'text-gray-600 hover:text-gray-900' }}">
                Pengembalian Barang
            </a>
        </div>

        <!-- Shopee-style Search Bar -->
        <div class="px-4 py-3 border-b border-gray-200">
            <form action="{{ route('pesanan.index') }}" method="GET" class="flex">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="relative flex-grow">
                    <input type="text" name="search" placeholder="Kamu bisa cari berdasarkan Nama Produk atau No. Pesanan"
                           value="{{ request('search') }}"
                           class="w-full border border-gray-200 px-4 py-2 pl-10 rounded-full text-sm focus:outline-none focus:border-orange-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <button type="submit" class="ml-2 bg-orange-500 text-white px-5 py-2 rounded-full text-sm font-medium hover:bg-orange-600 focus:outline-none">
                    Cari
                </button>
            </form>
        </div>
    </div>

    @if($pesanan->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-10 text-center">
            <div class="flex flex-col items-center justify-center">
                <svg class="w-20 h-20 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-700 mb-1">Belum ada pesanan</h3>
                <p class="text-gray-500 mb-6">Pesanan yang Anda buat akan muncul di sini</p>
                <a href="{{ route('produk.index') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 focus:outline-none">
                    Mulai Belanja
                </a>
            </div>
        </div>
    @else
        <!-- Order List (Shopee Style) -->
        @foreach($pesanan as $p)
            <div class="bg-white shadow-sm mb-4 overflow-hidden">
                <!-- Order Header - Shop Info -->
                <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-600 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        <span class="font-medium">WIB Store</span>
                    </div>

                    @php
                        $statusText = $p->status_pesanan;
                        $statusColor = 'text-gray-500'; // Default color

                        if ($p->status_pesanan == 'Menunggu Pembayaran' && !$p->bukti_pembayaran) {
                            $statusText = 'BELUM BAYAR';
                            $statusColor = 'text-red-500';
                        } elseif ($p->status_pesanan == 'Menunggu Pembayaran' && $p->bukti_pembayaran) {
                            $statusText = 'MENUNGGU KONFIRMASI';
                            $statusColor = 'text-blue-500';
                        } elseif (in_array($p->status_pesanan, ['Pembayaran Dikonfirmasi', 'Diproses'])) {
                            $statusText = 'SEDANG DIKEMAS';
                            $statusColor = 'text-blue-500';
                        } elseif ($p->status_pesanan == 'Dikirim') {
                            $statusText = 'DIKIRIM';
                            $statusColor = 'text-purple-500';
                        } elseif ($p->status_pesanan == 'Selesai') {
                            $statusText = 'SELESAI';
                            $statusColor = 'text-green-500';
                        } elseif ($p->status_pesanan == 'Dibatalkan') {
                            $statusText = 'DIBATALKAN';
                            $statusColor = 'text-red-500';
                        } elseif ($p->status_pesanan == 'Pengembalian') {
                            $statusText = 'PENGEMBALIAN';
                            $statusColor = 'text-yellow-500';
                        } elseif ($p->status_pesanan == 'Pengembalian') {
                            $statusText = 'PENGEMBALIAN';
                            $statusColor = 'text-orange-500';
                        }
                    @endphp

                    <span class="font-medium {{ $statusColor }}">{{ $statusText }}</span>
                </div>

                <!-- Cancellation Notes -->
                @if($p->status_pesanan == 'Dibatalkan' && $p->alasan_pembatalan)
                    <div class="px-4 py-3 bg-red-50 border-l-4 border-red-400">
                        <div class="flex items-start space-x-2">
                            <svg class="w-4 h-4 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-red-800">Alasan Pembatalan:</p>
                                <p class="text-sm text-red-700">{{ $p->alasan_pembatalan }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Order Items Preview -->
                <div>
                    @foreach($p->detailPesanan->take(1) as $detail)
                        <div class="flex p-4">
                            <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded overflow-hidden">
                                @if($detail->produk && $detail->produk->gambar)
                                    @if(Str::startsWith($detail->produk->gambar, 'uploads/'))
                                        <img src="{{ asset($detail->produk->gambar) }}" alt="{{ $detail->produk->nama_ikan }}" class="w-full h-full object-cover">
                                    @elseif(Str::startsWith($detail->produk->gambar, 'storage/'))
                                        <img src="{{ asset($detail->produk->gambar) }}" alt="{{ $detail->produk->nama_ikan }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ asset('storage/' . $detail->produk->gambar) }}" alt="{{ $detail->produk->nama_ikan }}" class="w-full h-full object-cover">
                                    @endif
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-grow">
                                <h4 class="font-medium text-gray-800 text-sm mb-2 line-clamp-2">
                                    {{ $detail->produk->nama_ikan ?? 'Produk tidak tersedia' }}
                                </h4>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span>x{{ $detail->kuantitas }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">Rp{{ number_format($detail->harga, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @endforeach

                    @if($p->detailPesanan->count() > 1)
                        <div class="px-4 pb-3 text-sm text-gray-500">
                            dan {{ $p->detailPesanan->count() - 1 }} produk lainnya
                        </div>
                    @endif
                </div>

                <!-- Order Footer -->
                <div class="border-t border-gray-200">
                    <div class="px-4 py-3 flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            {{ $p->created_at->format('d M Y') }} | #{{ $p->id_pesanan }}
                        </div>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500 mr-2">Total Pesanan:</span>
                            <span class="text-lg font-bold text-orange-600">Rp{{ number_format($p->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Reviews Display Section -->
                    @php
                        $allReviews = collect();
                        foreach($p->detailPesanan as $detail) {
                            if(isset($detail->userReviews) && $detail->userReviews->isNotEmpty()) {
                                $allReviews = $allReviews->concat($detail->userReviews);
                            }
                        }
                    @endphp
                    @if($p->status_pesanan == 'Selesai' && $allReviews->isNotEmpty())
                        <div class="px-4 py-3 border-t border-gray-100">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Ulasan Produk:</h4>
                            @foreach($allReviews as $review)
                                <div class="mb-4 pb-4 border-b border-gray-100 last:border-b-0">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-12 h-12 bg-gray-100 rounded overflow-hidden flex-shrink-0">
                                            @if($review->produk && $review->produk->gambar)
                                                @if(Str::startsWith($review->produk->gambar, 'uploads/'))
                                                    <img src="{{ asset($review->produk->gambar) }}" alt="{{ $review->produk->nama_ikan }}" class="w-full h-full object-cover">
                                                @elseif(Str::startsWith($review->produk->gambar, 'storage/'))
                                                    <img src="{{ asset($review->produk->gambar) }}" alt="{{ $review->produk->nama_ikan }}" class="w-full h-full object-cover">
                                                @else
                                                    <img src="{{ asset('storage/' . $review->produk->gambar) }}" alt="{{ $review->produk->nama_ikan }}" class="w-full h-full object-cover">
                                                @endif
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <h5 class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $review->produk ? $review->produk->nama_ikan : 'Produk tidak tersedia' }}
                                                </h5>
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            @if($review->komentar)
                                                <p class="text-sm text-gray-600 mb-2">{{ $review->komentar }}</p>
                                            @endif
                                            @if($review->foto_review && is_array($review->foto_review))
                                                <div class="flex space-x-2 mb-2">
                                                    @foreach($review->foto_review as $foto)
                                                        <div class="w-16 h-16 bg-gray-100 rounded overflow-hidden">
                                                            <img src="{{ asset('storage/' . $foto) }}" alt="Review Photo" class="w-full h-full object-cover">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if($review->balasan_admin)
                                                <div class="mt-2 p-2 bg-blue-50 border-l-4 border-blue-400 rounded">
                                                    <div class="flex items-center space-x-1 mb-1">
                                                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                                        </svg>
                                                        <span class="text-xs font-medium text-blue-900">Balasan Toko</span>
                                                    </div>
                                                    <p class="text-xs text-blue-800">{{ $review->balasan_admin }}</p>
                                                </div>
                                            @endif
                                            <span class="text-xs text-gray-400">{{ $review->created_at->format('d M Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 flex justify-end">
                        <div class="flex flex-wrap gap-2">
                            <!-- Detail Pesanan selalu ditampilkan -->
                            <a href="{{ route('pesanan.show', $p->id_pesanan) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Detail Pesanan
                            </a>

                            @if($p->status_pesanan == 'Menunggu Pembayaran' && !$p->bukti_pembayaran)
                                <!-- Tampilkan tombol Bayar hanya jika belum ada bukti pembayaran -->
                                <a href="{{ route('pesanan.show', $p->id_pesanan) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                                    Bayar Sekarang
                                </a>
                            @elseif($p->status_pesanan == 'Dikirim')
                                <!-- Tombol konfirmasi penerimaan dengan SweetAlert -->
                                <button type="button" onclick="confirmOrderReceived({{ $p->id_pesanan }})" class="inline-flex items-center px-4 py-2 border border-transparent rounded-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                                    Pesanan Diterima
                                </button>
                            @elseif($p->status_pesanan == 'Selesai')
                                <!-- Opsi setelah pesanan selesai -->
                                <div class="flex gap-2 flex-wrap">
                                    <!-- Check if there are reviewable products -->
                                    @if($p->reviewable_products->isNotEmpty())
                                        <button type="button" onclick="showReviewModal({{ $p->id_pesanan }})" class="inline-flex items-center px-4 py-2 border border-transparent rounded-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                            Beri Ulasan ({{ $p->reviewable_products->count() }})
                                        </button>
                                    @endif

                                    <!-- Button Ajukan Pengembalian - cek apakah masih dalam 24 jam -->
                                    @if($p->tanggal_diterima && $p->tanggal_diterima->addHours(24)->isFuture())
                                        <button type="button" onclick="confirmCreateRefund({{ $p->id_pesanan }})" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3"></path>
                                            </svg>
                                            Ajukan Pengembalian
                                        </button>
                                    @elseif(!$p->tanggal_diterima)
                                        <!-- Jika tanggal_diterima belum ada, anggap masih bisa refund -->
                                        <button type="button" onclick="confirmCreateRefund({{ $p->id_pesanan }})" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3"></path>
                                            </svg>
                                            Ajukan Pengembalian
                                        </button>
                                    @endif

                                    <a href="{{ route('produk.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Beli Lagi
                                    </a>
                                </div>
                            @elseif($p->status_pesanan == 'Pengembalian')
                                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-orange-700 bg-orange-100 rounded-sm">
                                    Proses Pengembalian
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Hidden review data for modal -->
                <div id="review-data-{{ $p->id_pesanan }}" style="display: none;" data-pesanan-id="{{ $p->id_pesanan }}">
                    @foreach($p->reviewable_products as $index => $detailPesanan)
                        @php
                            $imageUrl = '';
                            if($detailPesanan->produk && $detailPesanan->produk->gambar) {
                                if(Str::startsWith($detailPesanan->produk->gambar, 'uploads/')) {
                                    $imageUrl = asset($detailPesanan->produk->gambar);
                                } elseif(Str::startsWith($detailPesanan->produk->gambar, 'storage/')) {
                                    $imageUrl = asset($detailPesanan->produk->gambar);
                                } else {
                                    $imageUrl = asset('storage/' . $detailPesanan->produk->gambar);
                                }
                            }
                        @endphp
                        <div class="product-review-data"
                             data-product-id="{{ $detailPesanan->id_Produk }}"
                             data-product-name="{{ $detailPesanan->produk->nama_ikan ?? 'Produk tidak tersedia' }}"
                             data-product-image="{{ $imageUrl }}"
                             data-product-qty="{{ $detailPesanan->kuantitas }}"
                             data-index="{{ $index }}">
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-6">
            {{ $pesanan->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Global variables for review modal
    let currentOrderId = null;
    let reviewsData = {};

    // Show SweetAlert modal for review
    function showReviewModal(orderId) {
        currentOrderId = orderId;
        const reviewDataContainer = document.getElementById(`review-data-${orderId}`);
        const products = reviewDataContainer.querySelectorAll('.product-review-data');

        // Initialize reviews data
        reviewsData = {};

        // Build HTML for multiple products
        let productsHtml = '';

        products.forEach((product, index) => {
            const productId = product.dataset.productId;
            const productName = product.dataset.productName;
            const productImage = product.dataset.productImage;
            const productQty = product.dataset.productQty;

            // Initialize review data
            reviewsData[index] = {
                productId: productId,
                rating: 0,
                comment: '',
                photos: []
            };

            productsHtml += `
                <div class="border border-gray-200 rounded-lg p-4 mb-4 bg-white">
                    <!-- Product Header -->
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                            ${productImage ? `<img src="${productImage}" alt="${productName}" class="w-full h-full object-cover">` : `
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            `}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 text-sm">${productName}</h3>
                            <p class="text-xs text-gray-500 mt-1">Qty: ${productQty}</p>
                        </div>
                    </div>

                    <!-- Rating Section -->
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Kualitas produk</p>
                        <div class="flex items-center space-x-1 mb-2">
                            ${[1,2,3,4,5].map(star => `
                                <button type="button"
                                        onclick="setModalRating(${index}, ${star})"
                                        class="star-${index}-${star} text-2xl transition-colors duration-200 text-gray-300 hover:text-orange-400 focus:outline-none"
                                        data-rating="${star}">
                                    ⭐
                                </button>
                            `).join('')}
                        </div>
                        <div class="rating-text-${index} text-sm text-gray-500 hidden"></div>
                        <div class="rating-error-${index} text-red-500 text-sm hidden">Silakan berikan rating</div>
                    </div>

                    <!-- Comment Section -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bagikan pengalaman kamu tentang produk ini
                        </label>
                        <textarea id="comment-${index}"
                                  placeholder="Tulis ulasan kamu di sini..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none"
                                  rows="4"
                                  maxlength="1000"
                                  oninput="updateModalComment(${index})"></textarea>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-xs text-gray-500">Minimal 10 karakter</span>
                            <span class="char-count-${index} text-xs text-gray-400">0/1000</span>
                        </div>
                        <div class="comment-error-${index} text-red-500 text-sm hidden">Ulasan minimal 10 karakter</div>
                    </div>

                    <!-- Photo Upload Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tambahkan foto (opsional)
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors">
                            <input type="file"
                                   id="photos-${index}"
                                   multiple
                                   accept="image/*"
                                   onchange="handlePhotoUpload(${index})"
                                   class="hidden">
                            <button type="button"
                                    onclick="document.getElementById('photos-${index}').click()"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Pilih Foto
                            </button>
                            <p class="text-xs text-gray-500 mt-2">PNG, JPG, JPEG max 2MB (max 3 foto)</p>
                        </div>
                        <div class="photo-preview-${index} mt-2 flex flex-wrap gap-2"></div>
                    </div>
                </div>
            `;
        });

        Swal.fire({
            title: '<div class="text-lg font-semibold text-gray-900">Beri Ulasan</div>',
            html: `
                <div class="text-left max-h-96 overflow-y-auto">
                    <div class="mb-4">
                        <p class="text-sm text-gray-600">Yuk, ceritain pengalaman kamu belanja di sini</p>
                    </div>
                    ${productsHtml}
                </div>
            `,
            width: '600px',
            showCancelButton: true,
            confirmButtonText: 'Kirim Ulasan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ea580c',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-xl',
                content: 'text-left'
            },
            preConfirm: () => {
                return validateAndSubmitReviews();
            },
            allowOutsideClick: false
        });
    }

    // Set rating in modal
    function setModalRating(productIndex, rating) {
        reviewsData[productIndex].rating = rating;

        // Update star colors
        for (let i = 1; i <= 5; i++) {
            const star = document.querySelector(`.star-${productIndex}-${i}`);
            if (star) {
                if (i <= rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-orange-400');
                } else {
                    star.classList.remove('text-orange-400');
                    star.classList.add('text-gray-300');
                }
            }
        }

        // Show rating text
        const ratingTexts = ['', 'Sangat Buruk', 'Buruk', 'Cukup', 'Baik', 'Sangat Baik'];
        const ratingTextEl = document.querySelector(`.rating-text-${productIndex}`);
        if (ratingTextEl) {
            ratingTextEl.textContent = ratingTexts[rating];
            ratingTextEl.classList.remove('hidden');
        }

        // Hide error
        const errorEl = document.querySelector(`.rating-error-${productIndex}`);
        if (errorEl) {
            errorEl.classList.add('hidden');
        }
    }

    // Update comment in modal
    function updateModalComment(productIndex) {
        const textarea = document.getElementById(`comment-${productIndex}`);
        const charCount = document.querySelector(`.char-count-${productIndex}`);
        const errorEl = document.querySelector(`.comment-error-${productIndex}`);

        if (textarea && charCount) {
            const length = textarea.value.length;
            charCount.textContent = `${length}/1000`;
            reviewsData[productIndex].comment = textarea.value;

            // Hide error if comment is valid
            if (length >= 10 && errorEl) {
                errorEl.classList.add('hidden');
            }
        }
    }

    // Handle photo upload
    function handlePhotoUpload(productIndex) {
        const input = document.getElementById(`photos-${productIndex}`);
        const previewContainer = document.querySelector(`.photo-preview-${productIndex}`);

        if (input.files.length > 3) {
            Swal.showValidationMessage('Maksimal 3 foto yang dapat diunggah');
            input.value = '';
            return;
        }

        previewContainer.innerHTML = '';
        reviewsData[productIndex].photos = [];

        Array.from(input.files).forEach((file, index) => {
            if (file.size > 2 * 1024 * 1024) {
                Swal.showValidationMessage('Ukuran file maksimal 2MB');
                return;
            }

            reviewsData[productIndex].photos.push(file);

            const reader = new FileReader();
            reader.onload = function(e) {
                const imgPreview = document.createElement('div');
                imgPreview.className = 'relative inline-block';
                imgPreview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="w-16 h-16 object-cover rounded-lg border">
                    <button type="button"
                            onclick="removePhoto(${productIndex}, ${index})"
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                        ×
                    </button>
                `;
                previewContainer.appendChild(imgPreview);
            };
            reader.readAsDataURL(file);
        });
    }

    // Remove photo
    function removePhoto(productIndex, photoIndex) {
        reviewsData[productIndex].photos.splice(photoIndex, 1);

        // Re-render photo previews
        const input = document.getElementById(`photos-${productIndex}`);
        const dt = new DataTransfer();

        reviewsData[productIndex].photos.forEach(file => {
            dt.items.add(file);
        });

        input.files = dt.files;
        handlePhotoUpload(productIndex);
    }

    // Validate and submit reviews
    function validateAndSubmitReviews() {
        let isValid = true;

        // Validate each product review
        Object.keys(reviewsData).forEach(index => {
            const review = reviewsData[index];

            // Validate rating
            if (!review.rating || review.rating < 1) {
                document.querySelector(`.rating-error-${index}`).classList.remove('hidden');
                isValid = false;
            }

            // Validate comment
            if (!review.comment || review.comment.length < 10) {
                document.querySelector(`.comment-error-${index}`).classList.remove('hidden');
                isValid = false;
            }
        });

        if (!isValid) {
            return false;
        }

        // Submit the form
        submitReviewForm();
        return true;
    }

    // Submit review form
    function submitReviewForm() {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('pesanan_id', currentOrderId);

        // Add review data
        Object.keys(reviewsData).forEach(index => {
            const review = reviewsData[index];
            formData.append(`reviews[${index}][id_produk]`, review.productId);
            formData.append(`reviews[${index}][rating]`, review.rating);
            formData.append(`reviews[${index}][komentar]`, review.comment);

            // Add photos
            review.photos.forEach((photo, photoIndex) => {
                formData.append(`reviews[${index}][foto_review][]`, photo);
            });
        });

        // Show loading
        Swal.fire({
            title: 'Mengirim Ulasan...',
            html: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Send request
        fetch(`/pesanan/${currentOrderId}/reviews`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Ulasan berhasil dikirim. Terima kasih atas feedback Anda!',
                    icon: 'success',
                    confirmButtonColor: '#ea580c'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                throw new Error(data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat mengirim ulasan. Silakan coba lagi.',
                icon: 'error',
                confirmButtonColor: '#ea580c'
            });
        });
    }

    // Confirm order received function
    function confirmOrderReceived(orderId) {
        Swal.fire({
            title: 'Konfirmasi Pesanan Diterima',
            text: 'Apakah Anda sudah menerima pesanan ini dengan baik?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ea580c',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Sudah Diterima',
            cancelButtonText: 'Belum',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Memproses...',
                    html: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Create form and submit to confirm receipt
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/pesanan/${orderId}/konfirmasi`;

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Confirm create refund function
    function confirmCreateRefund(orderId) {
        Swal.fire({
            title: 'Ajukan Pengembalian',
            html: `
                <div class="text-left">
                    <p class="mb-3">Anda dapat mengajukan pengembalian untuk pesanan ini.</p>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3">
                        <p class="text-sm text-yellow-800">
                            <strong>Catatan:</strong> Pengembalian hanya bisa dilakukan dalam 24 jam setelah pesanan diterima
                        </p>
                    </div>
                    <p class="text-sm text-gray-600">Pastikan produk masih dalam kondisi baik dan sesuai syarat pengembalian.</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ajukan Pengembalian',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to pengembalian create page
                window.location.href = `/pesanan/${orderId}/pengembalian/create`;
            }
        });
    }
</script>
@endpush
