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
                        } elseif ($p->status_pesanan == 'Karantina') {
                            $statusText = 'KARANTINA';
                            $statusColor = 'text-yellow-500';
                        } elseif ($p->status_pesanan == 'Pengembalian') {
                            $statusText = 'PENGEMBALIAN';
                            $statusColor = 'text-orange-500';
                        }
                    @endphp

                    <span class="font-medium {{ $statusColor }}">{{ $statusText }}</span>
                </div>

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
                                    @if($detail->ukuran_id && isset($detail->ukuran))
                                        <span class="mr-2">Variasi: {{ $detail->ukuran->ukuran }}</span>
                                    @endif
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
                    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 flex justify-end">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('pesanan.show', $p->id_pesanan) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Detail Pesanan
                            </a>

                            @if($p->status_pesanan == 'Menunggu Pembayaran')
                                <a href="{{ route('pesanan.show', $p->id_pesanan) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                                    Bayar Sekarang
                                </a>
                            @elseif($p->status_pesanan == 'Dikirim')
                                <form action="{{ route('pesanan.konfirmasi', $p->id_pesanan) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                                        Pesanan Diterima
                                    </button>
                                </form>
                            @elseif($p->status_pesanan == 'Selesai')
                                <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent rounded-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                                    Beli Lagi
                                </a>
                                <a href="{{ route('pesanan.review', ['pesanan' => $p->id_pesanan]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Beri Ulasan
                                </a>
                            @elseif($p->status_pesanan == 'Karantina')
                                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-yellow-700 bg-yellow-100 rounded-sm">
                                    Dalam Karantina
                                </span>
                            @elseif($p->status_pesanan == 'Pengembalian')
                                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-orange-700 bg-orange-100 rounded-sm">
                                    Proses Pengembalian
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-6">
            {{ $pesanan->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Additional Scripts
</script>
@endpush
