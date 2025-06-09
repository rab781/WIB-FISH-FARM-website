@extends('layouts.customer')

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
                                @if($pesanan->alasan_pembatalan)
                                    <div class="mt-3 p-3 bg-red-100 rounded-md border-l-4 border-red-400">
                                        <p class="text-sm font-medium text-red-800 mb-1">Alasan Pembatalan:</p>
                                        <p class="text-sm text-red-700">{{ $pesanan->alasan_pembatalan }}</p>
                                    </div>
                                @else
                                    <p class="text-sm text-red-600 mt-1">
                                        {{ $pesanan->keterangan_status ?? 'Pesanan ini telah dibatalkan' }}
                                    </p>
                                @endif
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
                                                            Pemrosesan pesanan (2-3 hari)
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

                <!-- Processing Information for Diproses Status -->
                @if(in_array($pesanan->status_pesanan, ['Diproses', 'Pembayaran Dikonfirmasi']))
                <div class="bg-white shadow-sm mb-4">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Pemrosesan Pesanan
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-semibold text-blue-800 mb-2">Informasi Pemrosesan Pesanan</h4>
                                    <div class="text-sm text-blue-700 space-y-2">
                                        <p><strong>🐠 Persiapan Ikan:</strong> Ikan hias Anda sedang dipersiapkan dengan hati-hati untuk memastikan kualitas terbaik sebelum dikirim.</p>

                                        <p><strong>📋 Tahap Pemrosesan:</strong></p>
                                        <ul class="list-disc list-inside ml-4 space-y-1">
                                            <li>Pemeriksaan kesehatan ikan</li>
                                            <li>Persiapan kemasan khusus</li>
                                            <li>Pengecekan kualitas air</li>
                                            <li>Persiapan pengiriman</li>
                                        </ul>

                                        <p><strong>⏰ Estimasi:</strong> Pesanan akan diproses dalam <strong>2-3 hari kerja</strong> sebelum dikirim.</p>

                                        <p><strong>📞 Informasi:</strong> Kami akan menginformasikan Anda ketika pesanan siap dikirim. Terima kasih atas kesabaran Anda!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

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

        <!-- Shipping Information -->
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

                    @if($pesanan->nomor_resi && in_array($pesanan->status_pesanan, ['Dikirim', 'Selesai']))
                    <div class="flex justify-between sm:block text-sm">
                        <span class="text-gray-500">Nomor Resi</span>
                        <div class="sm:block">
                            <span class="font-medium font-mono">{{ $pesanan->nomor_resi }}</span>
                            <button type="button" onclick="copyToClipboard('{{ $pesanan->nomor_resi }}')" class="ml-2 text-xs text-orange-600 hover:text-orange-800 underline">
                                Salin
                            </button>
                        </div>
                    </div>
                    @endif

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

                <!-- Tracking Info untuk status Dikirim dan Selesai -->
                @if($pesanan->nomor_resi && in_array($pesanan->status_pesanan, ['Dikirim', 'Selesai']))
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <h4 class="text-sm font-semibold text-blue-800">Lacak Paket</h4>
                        </div>
                        <p class="text-sm text-blue-700 mb-3">
                            Gunakan nomor resi <strong>{{ $pesanan->nomor_resi }}</strong> untuk melacak paket Anda di website kurir {{ strtoupper($pesanan->kurir ?? 'TIKI') }}.
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @if(strtolower($pesanan->kurir ?? 'tiki') == 'tiki')
                            <a href="https://www.tiki.id" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-blue-300 text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Lacak di TIKI
                            </a>
                            @elseif(strtolower($pesanan->kurir ?? '') == 'jne')
                            <a href="https://www.jne.co.id/id/tracking/trace" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-blue-300 text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Lacak di JNE
                            </a>
                            @elseif(strtolower($pesanan->kurir ?? '') == 'pos')
                            <a href="https://ongkir.pos.co.id/track" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-blue-300 text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Lacak di POS
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

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

        <!-- Payment Section -->
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
                    <button type="button" onclick="confirmOrderReceived()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Pesanan Diterima
                    </button>
                </div>
            </div>
        @endif

        <!-- Reviews Display Section -->
        @if($pesanan->status_pesanan == 'Selesai')
            @php
                $hasAnyReviews = false;
                foreach($pesanan->detailPesanan as $detail) {
                    if(isset($detail->userReviews) && $detail->userReviews->isNotEmpty()) {
                        $hasAnyReviews = true;
                        break;
                    }
                }
            @endphp

            @if($hasAnyReviews)
                <div class="bg-white shadow-sm mb-4">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Ulasan Produk</h3>
                    </div>
                    <div class="p-4">
                        @foreach($pesanan->detailPesanan as $detail)
                            @if(isset($detail->userReviews) && $detail->userReviews->isNotEmpty())
                                @foreach($detail->userReviews as $review)
                                    <div class="mb-4 pb-4 border-b border-gray-100 last:border-b-0">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-12 h-12 bg-gray-100 rounded overflow-hidden flex-shrink-0">
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
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-2 mb-2">
                                                    <h4 class="text-sm font-medium text-gray-900">{{ $detail->produk->nama_ikan ?? 'Produk' }}</h4>
                                                    <div class="flex text-yellow-400">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            @else
                                                                <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                                </div>

                                                @if($review->komentar)
                                                    <p class="text-sm text-gray-700 mb-2">{{ $review->komentar }}</p>
                                                @endif

                                                @if($review->foto_ulasan)
                                                    <div class="flex space-x-2 mb-2">
                                                        @php
                                                            $photos = is_string($review->foto_ulasan) ? json_decode($review->foto_ulasan, true) : $review->foto_ulasan;
                                                            if(!is_array($photos)) $photos = [$review->foto_ulasan];
                                                        @endphp
                                                        @foreach($photos as $photo)
                                                            @if($photo)
                                                                <div class="w-16 h-16 bg-gray-100 rounded overflow-hidden">
                                                                    <img src="{{ asset('storage/' . $photo) }}" alt="Review photo" class="w-full h-full object-cover">
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif

                                                @if($review->balasan_admin)
                                                    <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                                                        <p class="text-sm text-gray-600">
                                                            <span class="font-medium text-gray-900">Respon Admin:</span>
                                                            {{ $review->balasan_admin }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <!-- Review Modal for Order -->
        @if($pesanan->status_pesanan == 'Selesai')

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
                <a href="{{ route('produk.index') }}" class="w-full mt-2 inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Beli Lagi
                </a>
                @endif

                <!-- Review Button - Visible for completed orders -->
                @if($pesanan->status_pesanan == 'Selesai')
                    @if($pesanan->reviewable_products->isNotEmpty())
                        <button type="button" onclick="showReviewModal({{ $pesanan->id_pesanan }})" class="w-full mt-2 inline-flex justify-center items-center px-4 py-2 border border-orange-300 rounded-md shadow-sm text-sm font-medium text-orange-700 bg-orange-50 hover:bg-orange-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                            Beri Ulasan ({{ $pesanan->reviewable_products->count() }})
                        </button>
                    @endif
                @endif

                <!-- Refund Button - Visible for completed orders within 24 hours -->
                @if($pesanan->status_pesanan == 'Selesai' && $pesanan->tanggal_diterima)
                    @php
                        $timeLeft = $pesanan->tanggal_diterima->addHours(24);
                        $canRefund = $timeLeft->isFuture();
                    @endphp
                    @if($canRefund)
                    <button type="button" onclick="confirmCreateRefund()" class="w-full mt-2 inline-flex justify-center items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"></path>
                        </svg>
                        Ajukan Pengembalian
                    </button>
                    <p class="text-xs text-red-500 mt-1 text-center">Tersedia hingga {{ $timeLeft->format('d M Y, H:i') }} WIB</p>
                    @endif
                @endif
            </div>
        </div>
        @endif

        <!-- Hidden review data for modal -->
        @if($pesanan->status_pesanan == 'Selesai' && $pesanan->reviewable_products->isNotEmpty())
            <div id="review-data-{{ $pesanan->id_pesanan }}" style="display: none;" data-pesanan-id="{{ $pesanan->id_pesanan }}">
                @foreach($pesanan->reviewable_products as $index => $detailPesanan)
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
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
        // Global variables for review modal
        let currentOrderId = null;
        let reviewsData = {};

        // Show SweetAlert modal for review - Make it globally accessible
        window.showReviewModal = function(orderId) {
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

        // Set rating in modal - Make it globally accessible
        window.setModalRating = function(productIndex, rating) {
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

        // Update comment in modal - Make it globally accessible
        window.updateModalComment = function(productIndex) {
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

        // Handle photo upload - Make it globally accessible
        window.handlePhotoUpload = function(productIndex) {
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

        // Remove photo - Make it globally accessible
        window.removePhoto = function(productIndex, photoIndex) {
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
        // Confirm order received function - Make it globally accessible
        window.confirmOrderReceived = function() {
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
                    // Create form and submit to confirm receipt
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('pesanan.konfirmasi', $pesanan->id_pesanan) }}`;

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

        // Confirm create refund function - Make it globally accessible
        window.confirmCreateRefund = function() {
            @php
                $timeLeft = $pesanan->tanggal_diterima ? $pesanan->tanggal_diterima->addHours(24)->diffInHours(now()) : 0;
            @endphp

            Swal.fire({
                title: 'Ajukan Pengembalian',
                html: `
                    <div class="text-left">
                        <p class="mb-3">Anda dapat mengajukan pengembalian untuk pesanan ini.</p>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-3">
                            <p class="text-sm text-yellow-800">
                                <strong>Waktu tersisa:</strong> {{ $timeLeft }} jam lagi
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
                    window.location.href = `{{ route('pengembalian.create', $pesanan->id_pesanan) }}`;
                }
            });
        }

        // Cancel order confirmation function - Make it globally accessible
        window.confirmCancelOrder = function(orderId) {
            Swal.fire({
                title: 'Batalkan Pesanan',
                html: `
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan pembatalan:</label>
                        <textarea id="swal-cancel-reason"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2"
                                  rows="3"
                                  placeholder="Masukkan alasan pembatalan (minimal 10 karakter)..."
                                  maxlength="200"></textarea>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusConfirm: false,
                didOpen: () => {
                    document.getElementById('swal-cancel-reason').focus();
                },
                preConfirm: () => {
                    const reason = document.getElementById('swal-cancel-reason').value.trim();
                    if (!reason) {
                        Swal.showValidationMessage('Alasan pembatalan harus diisi!');
                        return false;
                    }
                    if (reason.length < 10) {
                        Swal.showValidationMessage('Alasan pembatalan harus minimal 10 karakter!');
                        return false;
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const reason = result.value;
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
            });
        }

        // Copy to clipboard function for tracking number - Make it globally accessible
        window.copyToClipboard = function(text) {
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Nomor resi berhasil disalin!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            }).catch(err => {
                // Fallback for browsers that don't support clipboard API
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Gagal menyalin. Silakan salin manual.',
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        }

document.addEventListener('DOMContentLoaded', function() {
    // Copy to clipboard functionality
    const copyButtons = document.querySelectorAll('.copy-button');
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const textToCopy = this.getAttribute('data-clipboard');
            navigator.clipboard.writeText(textToCopy).then(() => {
                // Show success notification with SweetAlert
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Nomor rekening berhasil disalin!',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            }).catch(err => {
                // Fallback for browsers that don't support clipboard API
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Gagal menyalin. Silakan salin manual.',
                    showConfirmButton: false,
                    timer: 3000
                });
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
                    Swal.fire({
                        title: 'File Tidak Valid',
                        text: 'Hanya file JPG, PNG, dan JPEG yang diperbolehkan',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        title: 'File Terlalu Besar',
                        text: 'Ukuran file maksimal 2MB',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
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
@endpush
