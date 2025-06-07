@extends('layouts.app')

@php
use Carbon\Carbon;
@endphp

@section('title', 'Detail Pesanan #' . $pesanan->id_pesanan)

@section('content')
<div class="max-w-7xl mx-auto py-4 sm:px-6 lg:px-8">
    <!-- Back navigation -->
    <div class="mb-4">
        <a href="{{ route('pesanan.index') }}" class="flex items-center text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Kembali ke Pesanan Saya</span>
        </a>
    </div>

    <!-- Order Status Header (Shopee Style) -->
    <div class="bg-orange-600 text-white p-4 rounded-t-lg">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="font-medium">PESANAN {{ strtoupper($pesanan->id_pesanan) }}</span>
            </div>
            <div>
                @php
                    $statusMap = [
                        'Menunggu Pembayaran' => 'BELUM BAYAR',
                        'Pembayaran Dikonfirmasi' => 'SEDANG DIPROSES',
                        'Diproses' => 'SEDANG DIPROSES',
                        'Dikirim' => 'SEDANG DIKIRIM',
                        'Selesai' => 'SELESAI',
                        'Dibatalkan' => 'DIBATALKAN',
                    ];

                    // Special case for when payment proof uploaded but not yet confirmed
                    if ($pesanan->status_pesanan == 'Menunggu Pembayaran' && $pesanan->bukti_pembayaran) {
                        $status = 'MENUNGGU KONFIRMASI ADMIN';
                    } else {
                        $status = $statusMap[$pesanan->status_pesanan] ?? $pesanan->status_pesanan;
                    }
                @endphp
                <span class="font-medium">{{ $status }}</span>
            </div>
        </div>
    </div>

    <!-- Single column layout for better user experience -->
    <div class="max-w-5xl mx-auto">
        <!-- Status Timeline (Shopee Style) -->
        <div class="bg-white shadow-sm mb-4">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between w-full">
                    @php
                        $statuses = [
                            'Menunggu Pembayaran',
                            'Diproses',
                            'Dikirim',
                            'Selesai'
                        ];

                        // Handle status mapping for timeline display
                        $currentStatus = $pesanan->status_pesanan;

                        // Map 'Pembayaran Dikonfirmasi' to 'Diproses' for timeline display
                        if ($currentStatus == 'Pembayaran Dikonfirmasi') {
                            $currentStatus = 'Diproses';
                        }

                        $currentIndex = array_search($currentStatus, $statuses);

                        // Handle special cases
                        if ($currentIndex === false) {
                            if ($pesanan->status_pesanan == 'Dibatalkan') {
                                $currentIndex = -1;
                            } elseif ($pesanan->status_pesanan == 'Menunggu Pembayaran') {
                                $currentIndex = 0;
                            } else {
                                $currentIndex = 0; // Default to first status
                            }
                        }
                    @endphp

                    @if ($pesanan->status_pesanan == 'Dibatalkan')
                        <div class="w-full flex items-center justify-center">
                            <div class="px-6 py-4 rounded-lg bg-red-50 border border-red-200 text-center">
                                <svg class="w-12 h-12 text-red-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <h4 class="font-medium text-red-700">Pesanan Dibatalkan</h4>
                                <p class="text-sm text-red-600 mt-1">
                                    {{ $pesanan->keterangan_status ?? 'Pesanan ini telah dibatalkan' }}
                                </p>
                            </div>
                        </div>
                        @else
                            <div class="relative flex items-center justify-between w-full">
                                <!-- Container untuk garis penghubung - adjusted positioning -->
                                <div class="absolute left-0 w-full" style="top: 28px; z-index: 0;">
                                    <div class="relative h-1">
                                        <!-- Background line -->
                                        <div class="absolute inset-0 bg-gray-200 rounded-full"></div>
                                        <!-- Progress line -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full transition-all duration-500 ease-in-out"
                                             style="width: {{ ($currentIndex / (count($statuses) - 1)) * 100 }}%"></div>
                                    </div>
                                </div>
                                <!-- Container untuk garis penghubung - adjusted z-index and positioning -->
                                <div class="absolute top-6 left-0 w-full" style="z-index: -1;">
                                    <div class="relative h-2">
                                        <!-- Background line -->
                                        <div class="absolute inset-0 bg-gray-200 rounded-full"></div>
                                        <!-- Progress line -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full transition-all duration-500 ease-in-out"
                                             style="width: {{ ($currentIndex / (count($statuses) - 1)) * 100 }}%"></div>
                                    </div>
                                </div>

                                @foreach($statuses as $index => $status)
                                    <div class="flex flex-col items-center relative z-10 {{ $index > 0 ? 'flex-1' : '' }}">
                                        <!-- Status Circle with Animation -->
                                        <div class="{{ $index <= $currentIndex ? 'bg-gradient-to-r from-green-500 to-emerald-500 animate-pulse-slow shadow-lg' : 'bg-gray-200' }}
                                             rounded-full h-14 w-14 flex items-center justify-center relative z-10
                                             transform transition-all duration-500 ease-in-out hover:scale-110
                                             {{ $index <= $currentIndex ? 'hover:shadow-green-300' : 'hover:shadow-gray-300' }}
                                             border-4 {{ $index <= $currentIndex ? 'border-green-200' : 'border-gray-100' }}">
                                            @if ($index <= $currentIndex)
                                                <svg class="w-7 h-7 text-white transform transition-transform duration-500 ease-in-out hover:rotate-12"
                                                     fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                          clip-rule="evenodd">
                                                    </path>
                                                </svg>
                                            @else
                                                <span class="text-gray-500 font-bold text-lg">{{ $index + 1 }}</span>
                                            @endif
                                        </div>

                                        <!-- Removed old connecting line code as it's now handled by the container above -->

                                        <!-- Status Label with Animation -->
                                        <div class="mt-4 text-center group relative">
                                            <div class="text-sm font-semibold transition-all duration-300
                                                {{ $index <= $currentIndex ? 'text-green-700 group-hover:text-green-800' : 'text-gray-500 group-hover:text-gray-700' }}">
                                                {{ $status }}
                                            </div>
                                            @if ($index <= $currentIndex)
                                                <div class="mt-1 text-xs text-green-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    @switch($index)
                                                        @case(0)
                                                            {{ $pesanan->created_at->format('d M Y H:i') }}
                                                            @break
                                                        @case(1)
                                                            Diproses admin
                                                            @break
                                                        @case(2)
                                                            {{ $pesanan->tanggal_pengiriman ?? 'Sedang dikirim' }}
                                                            @break
                                                        @case(3)
                                                            {{ $pesanan->tanggal_selesai ?? 'Pesanan selesai' }}
                                                            @break
                                                    @endswitch
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Pesanan</h3>
                </div>
                <div class="px-4 py-5 sm:px-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="sm:col-span-1">
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <dt class="text-sm font-medium text-gray-500">ID Pesanan</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900">#{{ $pesanan->id_pesanan }}</dd>
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <dt class="text-sm font-medium text-gray-500">Tanggal Pemesanan</dt>
                                <dd class="mt-1 text-base font-semibold text-gray-900">{{ $pesanan->created_at->format('d M Y, H:i') }} WIB</dd>
                            </div>
                        </div>
                            @if($pesanan->status_pesanan == 'Menunggu Pembayaran' && $pesanan->batas_waktu && !$pesanan->bukti_pembayaran)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Batas Waktu Pembayaran</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @php
                                        $batasWaktu = $pesanan->batas_waktu instanceof \Carbon\Carbon
                                            ? $pesanan->batas_waktu
                                            : \Carbon\Carbon::parse($pesanan->batas_waktu);
                                    @endphp
                                    {{ $batasWaktu->format('d M Y, H:i') }} WIB
                                    @if($batasWaktu->isPast())
                                        <span class="inline-block px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 ml-2">
                                            Waktu habis
                                        </span>
                                    @else
                                        <span class="inline-block px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 ml-2">
                                            {{ $batasWaktu->diffForHumans() }}
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            @endif

                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @php
                                        $statusBg = [
                                            'Menunggu Pembayaran' => 'bg-yellow-100 text-yellow-800',
                                            'Pembayaran Dikonfirmasi' => 'bg-blue-100 text-blue-800',
                                            'Diproses' => 'bg-indigo-100 text-indigo-800',
                                            'Dikirim' => 'bg-purple-100 text-purple-800',
                                            'Selesai' => 'bg-green-100 text-green-800',
                                            'Dibatalkan' => 'bg-red-100 text-red-800',
                                        ][$pesanan->status_pesanan] ?? 'bg-gray-100 text-gray-800';

                                        // Show special status text for when payment proof uploaded but not yet confirmed
                                        if ($pesanan->status_pesanan == 'Menunggu Pembayaran' && $pesanan->bukti_pembayaran) {
                                            $statusText = 'Menunggu Konfirmasi Admin';
                                            $statusBg = 'bg-blue-100 text-blue-800';
                                        } else {
                                            $statusText = $pesanan->status_pesanan;
                                        }
                                    @endphp

                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBg }}">
                                        {{ $statusText }}
                                    </span>
                                </dd>
                            </div>

                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Alamat Pengiriman</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $pesanan->alamat_pengiriman }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Product Details -->
                <!-- Shopee-style Product Details -->
                <div class="bg-white shadow-sm mb-4">
                    <!-- Shop Header -->
                    <div class="p-4 border-b border-gray-200 flex items-center">
                        <svg class="w-5 h-5 text-gray-600 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        <span class="font-medium">WIB Store</span>
                    </div>

                    <!-- Products -->
                    <div>
                        @foreach($pesanan->detailPesanan as $detail)
                            <div class="p-4 flex {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                                <div class="flex-shrink-0 w-16 h-16 bg-gray-100 rounded overflow-hidden">
                                    @if($detail->produk && $detail->produk->gambar)
                                        <img src="{{ asset($detail->produk->gambar) }}" alt="{{ $detail->produk->nama_ikan }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-3 flex-grow">
                                    <h4 class="text-sm font-medium text-gray-900 line-clamp-2">
                                        {{ $detail->produk ? $detail->produk->nama_ikan : 'Produk tidak tersedia' }}
                                    </h4>
                                    <div class="mt-1 text-xs text-gray-500">
                                        @if($detail->produk_ukuran)
                                            <span>Ukuran: {{ $detail->produk_ukuran->ukuran }}</span>
                                            <span class="mx-1">·</span>
                                        @endif
                                        <span>{{ number_format($detail->kuantitas, 0, ',', '.') }} item</span>
                                    </div>
                                </div>
                                <div class="ml-3 text-right">
                                    <div class="text-sm text-gray-500">
                                        Rp{{ number_format($detail->harga, 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500">x{{ $detail->kuantitas }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Summary -->
                    <div class="p-4 border-t border-gray-200 bg-gray-50">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-500">Subtotal ({{ $pesanan->detailPesanan->sum('kuantitas') }} produk)</span>
                            <span class="text-sm">Rp{{ number_format($pesanan->detailPesanan->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-500">Ongkos Kirim</span>
                            <span class="text-sm">Rp{{ number_format($pesanan->ongkir_biaya ?? ($pesanan->ongkir->biaya ?? 0), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-200 mt-2">
                            <span class="font-medium">Total Pembayaran</span>
                            <span class="font-bold text-orange-600">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Shipping Information - Now at bottom for better UX -->
        <div class="bg-white shadow-sm mb-4">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informasi Pengiriman</h3>
            </div>
            <!-- Shipping Info -->
            <div class="p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="flex justify-between sm:block text-sm">
                        <span class="text-gray-500">Kurir</span>
                        <span class="font-medium uppercase sm:block">{{ $pesanan->kurir ?? 'TIKI' }}
                            @if($pesanan->kurir_service)
                                <span class="uppercase"> ({{ $pesanan->kurir_service }})</span>
                            @endif
                        </span>
                    </div>

                    @if($pesanan->berat_total)
                    <div class="flex justify-between sm:block text-sm">
                        <span class="text-gray-500">Berat Total</span>
                        <span class="font-medium sm:block">{{ number_format($pesanan->berat_total/1000, 1) }} kg</span>
                    </div>
                    @endif

                    @if($pesanan->jumlah_box)
                    <div class="flex justify-between sm:block text-sm">
                        <span class="text-gray-500">Jumlah Box</span>
                        <span class="font-medium sm:block">{{ $pesanan->jumlah_box }} box</span>
                    </div>
                    @endif
                </div>

                <!-- Payment Summary -->
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between text-sm mb-3">
                        <span class="text-gray-500">Subtotal Produk</span>
                        <span>Rp{{ number_format($pesanan->detailPesanan->sum('subtotal'), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm mb-3">
                        <span class="text-gray-500">Ongkos Kirim</span>
                        <span>Rp{{ number_format($pesanan->ongkir_biaya ?? ($pesanan->ongkir->biaya ?? 0), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <span class="text-gray-800 font-medium">Total Pembayaran</span>
                        <span class="text-lg font-bold text-orange-600">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>


        <!-- Payment Section - Moved to bottom for better UX -->
        @if($pesanan->status_pesanan == 'Menunggu Pembayaran' && !$pesanan->bukti_pembayaran)
            <!-- Payment Details Section -->
            <div class="bg-white shadow-sm mb-4">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Pembayaran</h3>
                    <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                        Belum Bayar
                    </span>
                </div>
                <div class="p-4">
                    @php
                        $batasWaktu = $pesanan->batas_waktu instanceof \Carbon\Carbon
                            ? $pesanan->batas_waktu
                            : \Carbon\Carbon::parse($pesanan->batas_waktu);
                    @endphp

                    <!-- Countdown Banner - Only shown when no payment proof uploaded -->
                    @if(!$pesanan->bukti_pembayaran)
                    <div class="bg-yellow-50 border border-yellow-100 p-3 rounded-lg mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-grow">
                                <span class="text-sm font-medium text-yellow-800">Batas Pembayaran</span>
                                <p class="text-sm text-yellow-700 mt-1">
                                    {{ $batasWaktu->format('d M Y, H:i') }} WIB
                                    <span class="block">({{ $batasWaktu->diffForHumans() }})</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($batasWaktu->isPast() && !$pesanan->bukti_pembayaran)
                    <div class="bg-red-50 border border-red-100 p-3 rounded-lg mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    <strong>Batas waktu pembayaran telah berakhir</strong><br>
                                    Pesanan ini akan otomatis dibatalkan
                                </p>
                            </div>
                        </div>
                    </div>
                    @elseif(!$pesanan->bukti_pembayaran)
                        <!-- Payment Instructions -->
                        @if($pesanan->metode_pembayaran == 'transfer_bank')
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Transfer Bank</h4>
                                <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                                    <div class="flex justify-between items-center mb-2">
                                        <div class="font-medium">BCA</div>
                                        <button type="button" class="inline-flex items-center px-2.5 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 copy-button" data-clipboard="0240934507">
                                            Salin
                                        </button>
                                    </div>
                                    <div class="font-mono font-medium text-lg mb-1">0240934507</div>
                                    <div class="text-xs text-gray-500">a.n Gamma Setiawan</div>
                                </div>
                            </div>
                        @elseif($pesanan->metode_pembayaran == 'qris')
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">QRIS</h4>
                                <div class="text-center border border-gray-200 rounded-lg p-3 bg-gray-50">
                                    <div class="mb-3">
                                        <img src="{{ asset('Images\Qris-code.png') }}" alt="QRIS Code" height="150" width="150" class="mx-auto" id="qrisImage" onerror="this.onerror=null; showQRISError();">
                                    </div>
                                    <div id="qrisInfo">
                                        <p class="text-xs text-gray-600 mb-1">Scan QR code menggunakan aplikasi e-wallet/m-banking</p>
                                        <p class="text-xs text-gray-600">Total: <strong>Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong></p>
                                        <div class="mt-3 p-2 bg-yellow-50 border border-yellow-100 rounded text-xs">
                                            <p class="font-medium text-yellow-800">Merchant: WIB</p>
                                        </div>
                                    </div>
                                    <div id="qrisError" class="hidden">
                                        <!-- QRIS Error Message -->
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Upload Payment Proof -->
                        @if(!$pesanan->bukti_pembayaran)
                            <div class="pt-4 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Upload Bukti Pembayaran</h4>
                                <form action="{{ route('pesanan.payment', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                                    @csrf
                                    <div class="mb-4">
                                        <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-orange-500 transition-colors duration-200 ease-in-out cursor-pointer bg-gray-50">
                                            <input type="file" class="hidden" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/jpeg,image/png,image/jpg" required>
                                            <div class="space-y-2" id="dropzone-text">
                                                <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <div class="text-xs">
                                                    <span class="font-medium text-orange-600">Klik untuk upload</span> atau drag and drop
                                                </div>
                                                <p class="text-xs text-gray-500">Format: JPG, PNG, JPEG (Maks. 2MB)</p>
                                            </div>
                                            <div id="preview" class="hidden mt-4">
                                                <img id="image-preview" class="mx-auto max-h-48 rounded-lg" src="" alt="Preview">
                                                <button type="button" id="remove-image" class="mt-2 text-xs text-red-600 hover:text-red-800">
                                                    Hapus Gambar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500" id="submit-button" disabled>
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        Upload Bukti Pembayaran
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @endif

        @if($pesanan->status_pesanan == 'Menunggu Pembayaran' && $pesanan->bukti_pembayaran)
            <!-- Payment Confirmation Waiting Section -->
            <div class="bg-white shadow-sm mb-4">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Konfirmasi Pembayaran</h3>
                    <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                        Menunggu Konfirmasi
                    </span>
                </div>
                <div class="p-4">
                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 flex">
                        <svg class="h-6 w-6 text-blue-400 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800 mb-1">Terima kasih atas pembayaran Anda</h4>
                            <p class="text-sm text-blue-700">Bukti pembayaran Anda telah diterima dan sedang menunggu konfirmasi dari admin. Pesanan akan segera diproses setelah pembayaran diverifikasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($pesanan->status_pesanan == 'Dikirim')
            <!-- Order Receipt Confirmation -->
            <div class="bg-white shadow-sm mb-4">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Konfirmasi Pesanan</h3>
                </div>
                <div class="p-4">
                    <div class="bg-cyan-50 border border-cyan-100 rounded-lg p-3 mb-4 flex items-center">
                        <svg class="h-5 w-5 text-cyan-500 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm text-cyan-800">Sudah menerima pesanan Anda?</span>
                    </div>
                    <form action="{{ route('pesanan.konfirmasi', $pesanan->id_pesanan) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Pesanan Diterima
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="bg-white shadow-sm mb-4">
            <div class="p-4">
                <a href="{{ route('pesanan.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Daftar Pesanan
                </a>

                @if($pesanan->status_pesanan == 'Selesai')
                <a href="#" class="w-full mt-2 inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Beli Lagi
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>        // Cancel order confirmation function
        function confirmCancelOrder(orderId) {
            const reason = prompt('Masukkan alasan pembatalan pesanan (minimal 10 karakter):');

            if (reason === null) {
                return; // User cancelled
            }

            if (reason.length < 10) {
                alert('Alasan pembatalan harus minimal 10 karakter');
                return;
            }

            if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
                // Create form and submit to cancel order route
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('pesanan') }}/${orderId}/cancel`;

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Add cancellation reason
                const reasonField = document.createElement('input');
                reasonField.type = 'hidden';
                reasonField.name = 'alasan_pembatalan';
                reasonField.value = reason;
                form.appendChild(reasonField);

        // Add method spoofing for DELETE/PATCH
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'POST';
        form.appendChild(method);

        document.body.appendChild(form);
        form.submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Copy to clipboard functionality
    const copyButtons = document.querySelectorAll('.copy-button');
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const textToCopy = this.getAttribute('data-clipboard');
            navigator.clipboard.writeText(textToCopy).then(() => {
                // Show copied feedback
                const originalText = this.textContent;
                this.textContent = 'Tersalin!';
                this.classList.add('bg-green-50', 'text-green-700', 'border-green-300');

                setTimeout(() => {
                    this.textContent = originalText;
                    this.classList.remove('bg-green-50', 'text-green-700', 'border-green-300');
                }, 2000);
            });
        });
    });

    // Show QRIS error if image fails to load
    function showQRISError() {
        document.getElementById('qrisInfo').classList.add('hidden');
        document.getElementById('qrisError').classList.remove('hidden');
    }

    // Make showQRISError available globally
    window.showQRISError = showQRISError;

    // File upload functionality
    const dropzone = document.getElementById('dropzone');
    if (dropzone) {
        const input = document.getElementById('bukti_pembayaran');
        const preview = document.getElementById('preview');
        const imagePreview = document.getElementById('image-preview');
        const dropzoneText = document.getElementById('dropzone-text');
        const removeButton = document.getElementById('remove-image');
        const submitButton = document.getElementById('submit-button');
        const form = document.getElementById('uploadForm');

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight dropzone when dragging over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dropzone.addEventListener('drop', handleDrop, false);

        // Handle click to upload
        dropzone.addEventListener('click', () => input.click());

        // Handle file input change
        input.addEventListener('change', handleFiles);

        // Handle remove button
        removeButton.addEventListener('click', removeFile);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight() {
            dropzone.classList.add('border-orange-500', 'bg-orange-50');
        }

        function unhighlight() {
            dropzone.classList.remove('border-orange-500', 'bg-orange-50');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles({ target: { files } });
        }

        function handleFiles(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                    alert('Hanya file JPG, PNG, dan JPEG yang diperbolehkan');
                    return;
                }

                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    preview.classList.remove('hidden');
                    dropzoneText.classList.add('hidden');
                    submitButton.disabled = false;
                }
                reader.readAsDataURL(file);
            }
        }

        function removeFile() {
            input.value = '';
            preview.classList.add('hidden');
            dropzoneText.classList.remove('hidden');
            submitButton.disabled = true;
        }

        // Add loading state to form submission
        form.addEventListener('submit', function() {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengupload...
            `;
        });
    }
});
</script>
@endsection

@push('styles')
<style>
    @keyframes pulse-slow {
        0%, 100% {
            opacity: 1;
            transform: scale(1);
        }
        50% {
            opacity: 0.9;
            transform: scale(1.05);
        }
    }

    @keyframes progress-line {
        0% {
            background: linear-gradient(to right, #22c55e 0%, rgb(229, 231, 235) 0%);
        }
        100% {
            background: linear-gradient(to right, #22c55e 100%, rgb(229, 231, 235) 0%);
        }
    }

    .animate-pulse-slow {
        animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    .animate-progress-line {
        animation: progress-line 1.5s ease-in-out forwards;
    }

    /* Hover effects for status items */
    .status-item {
        transition: transform 0.3s ease;
    }

    .status-item:hover {
        transform: translateY(-2px);
    }

    /* Custom shadow for completed status */
    .status-completed {
        box-shadow: 0 0 15px rgba(34, 197, 94, 0.2);
    }
</style>
@endpush
