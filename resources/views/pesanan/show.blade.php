@extends('layouts.app')

@php
use Carbon\Carbon;
@endphp

@section('title', 'Detail Pesanan #' . $pesanan->id_pesanan)

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('pesanan.index') }}" class="flex items-center text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Kembali ke Daftar Pesanan</span>
        </a>
    </div>

    <h1 class="text-2xl font-bold text-gray-900 mb-4">Detail Pesanan #{{ $pesanan->id_pesanan }}</h1>

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

    <div class="grid grid-cols-1 lg:grid-cols-6 gap-6">
        <div class="lg:col-span-5">
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
                            @if($pesanan->status_pesanan == 'Menunggu Pembayaran' && $pesanan->batas_waktu)
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 ml-2">
                                            Telah Berakhir
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2">
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
                                        $statusColor = 'gray';
                                        $statusBg = 'bg-gray-100';
                                        $statusText = 'text-gray-800';

                                        if ($pesanan->status_pesanan == 'Menunggu Pembayaran') {
                                            $statusColor = 'yellow';
                                            $statusBg = 'bg-yellow-100';
                                            $statusText = 'text-yellow-800';
                                        } elseif ($pesanan->status_pesanan == 'Pembayaran Dikonfirmasi') {
                                            $statusColor = 'blue';
                                            $statusBg = 'bg-blue-100';
                                            $statusText = 'text-blue-800';
                                        } elseif ($pesanan->status_pesanan == 'Diproses') {
                                            $statusColor = 'indigo';
                                            $statusBg = 'bg-indigo-100';
                                            $statusText = 'text-indigo-800';
                                        } elseif ($pesanan->status_pesanan == 'Dikirim') {
                                            $statusColor = 'cyan';
                                            $statusBg = 'bg-cyan-100';
                                            $statusText = 'text-cyan-800';
                                        } elseif ($pesanan->status_pesanan == 'Selesai') {
                                            $statusColor = 'green';
                                            $statusBg = 'bg-green-100';
                                            $statusText = 'text-green-800';
                                        } elseif ($pesanan->status_pesanan == 'Dibatalkan') {
                                            $statusColor = 'red';
                                            $statusBg = 'bg-red-100';
                                            $statusText = 'text-red-800';
                                        }
                                    @endphp

                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBg }} {{ $statusText }}">
                                        {{ $pesanan->status_pesanan }}
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
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Detail Produk</h3>
                </div>
                <div class="px-4 py-5 sm:px-6">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-orange-50 to-white">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ukuran</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($pesanan->detailPesanan as $detail)
                                <tr class="hover:bg-orange-50 transition duration-150">
                                    <td class="px-6 py-4">
                                        @if($detail->produk)
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border border-gray-200 bg-gray-100">
                                                    @if($detail->produk->gambar)
                                                        <img src="{{ asset($detail->produk->gambar) }}"
                                                             alt="{{ $detail->produk->nama_ikan }}"
                                                             class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                                            <img src="{{ asset('Images/Default-fish.png') }}"
                                                                 class="w-12 h-12 opacity-50"
                                                                 alt="Default Fish">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex flex-col">
                                                    <div class="text-sm font-medium text-gray-900">{{ $detail->produk->nama_ikan }}</div>
                                                    <div class="text-xs text-gray-500 mt-1">Kode: #{{ $detail->produk->id }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="text-sm text-gray-500">Produk tidak tersedia</div>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($detail->ukuran_id && isset($detail->ukuran))
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ $detail->ukuran->ukuran }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="font-medium text-gray-900">
                                            Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-orange-50 text-orange-700 border border-orange-100">
                                            {{ $detail->kuantitas }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Ringkasan Pembayaran</h3>
                </div>
                <div class="px-4 py-5 sm:px-6">
                    <div class="mb-4">
                        <h4 class="text-base font-medium text-gray-900 mb-3">Informasi Pengiriman</h4>
                        <dl class="grid grid-cols-2 gap-x-4 gap-y-2">
                            <dt class="text-sm font-medium text-gray-500">Kurir</dt>
                            <dd class="text-sm text-gray-900">
                                <span class="uppercase">{{ $pesanan->kurir ?? 'TIKI' }}</span>
                                @if($pesanan->kurir_service)
                                    <span class="uppercase"> ({{ $pesanan->kurir_service }})</span>
                                @endif
                            </dd>

                            @if($pesanan->berat_total)
                            <dt class="text-sm font-medium text-gray-500">Berat Total</dt>
                            <dd class="text-sm text-gray-900">{{ number_format($pesanan->berat_total/1000, 1) }} kg</dd>
                            @endif

                            @if($pesanan->jumlah_box)
                            <dt class="text-sm font-medium text-gray-500">Jumlah Box</dt>
                            <dd class="text-sm text-gray-900">{{ $pesanan->jumlah_box }} box (maks. 3 ikan/box)</dd>
                            @endif
                        </dl>

                        @if($pesanan->jumlah_box)
                        <div class="mt-3 px-4 py-3 bg-blue-50 rounded-md">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                                        <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Informasi Box Pengiriman Ikan</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Pesanan membutuhkan {{ $pesanan->jumlah_box }} box pengiriman khusus ikan.</p>
                                        <p>Setiap box dapat menampung maksimal 3 ekor ikan dengan ukuran 40x40x40 cm.</p>
                                        <p>Berat total: {{ number_format($pesanan->berat_total/1000, 1) }} kg ({{ number_format($pesanan->berat_total/1000/$pesanan->jumlah_box, 1) }} kg per box)</p>
                                        <div class="mt-2 p-2 border border-blue-200 bg-blue-100 rounded">
                                            <p class="font-medium">Pengiriman menggunakan TIKI untuk menjaga keamanan ikan:</p>
                                            <ul class="list-disc ml-5 mt-1">
                                                <li>Box khusus dengan aerasi dan media air</li>
                                                <li>Penanganan khusus untuk ikan hidup</li>
                                                <li>Ikan akan dipuasakan selama 7 hari terlebih dahulu</li>
                                                <li>Pengiriman akan dilakukan paling lama 3 hari setelah dipuasakan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preparation instructions -->
                        @if(in_array($pesanan->status_pesanan, ['Pembayaran Dikonfirmasi', 'Diproses', 'Dikirim']))
                        <div class="mt-3 px-4 py-3 bg-green-50 rounded-md">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Persiapan Menerima Ikan</h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <p class="font-medium">Siapkan hal-hal berikut untuk menerima ikan:</p>
                                        <ul class="list-disc ml-5 mt-1">
                                            <li>Akuarium atau kolam yang sudah siap pakai</li>
                                            <li>Air yang sudah diendapkan minimal 24 jam</li>
                                            <li>Perhatikan perubahan suhu secara bertahap</li>
                                            <li>Siapkan garam ikan untuk mencegah stres</li>
                                        </ul>
                                        <p class="mt-2">Setelah ikan datang, biarkan kantong/box beradaptasi di air selama 15-20 menit sebelum melepaskan ikan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <div class="flow-root">
                    <dl class="-my-4 divide-y divide-gray-200">
                        <div class="py-4 flex items-center justify-between">
                            <dt class="text-sm font-medium text-gray-900">Subtotal Produk</dt>
                            <dd class="text-sm font-medium text-gray-900">Rp {{ number_format($pesanan->detailPesanan->sum('subtotal'), 0, ',', '.') }}</dd>
                        </div>
                        <div class="py-4 flex items-center justify-between">
                            <dt class="text-sm font-medium text-gray-900">Ongkos Kirim</dt>
                            <dd class="text-sm font-medium text-gray-900">
                                Rp {{ number_format($pesanan->ongkir_biaya ?? ($pesanan->ongkir->biaya ?? 0), 0, ',', '.') }}
                            </dd>
                        </div>
                        <div class="py-4 flex items-center justify-between bg-gray-50 -mx-4 px-4">
                            <dt class="text-base font-medium text-gray-900">Total Pembayaran</dt>
                            <dd class="text-base font-bold text-gray-900">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        @if($pesanan->status_pesanan == 'Menunggu Pembayaran')
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Detail Pembayaran</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    {{ $pesanan->status_pesanan }}
                </span>
            </div>
            <div class="px-4 py-5 sm:px-6">
                @php
                    $batasWaktu = $pesanan->batas_waktu instanceof \Carbon\Carbon
                        ? $pesanan->batas_waktu
                        : \Carbon\Carbon::parse($pesanan->batas_waktu);
                @endphp

                <!-- Batas Waktu Pembayaran Alert -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Silakan lakukan pembayaran sebelum <strong>{{ $batasWaktu->format('d M Y, H:i') }} WIB</strong>
                                <span class="block">({{ $batasWaktu->diffForHumans() }})</span>
                            </p>
                        </div>
                    </div>
                </div>

                @if($pesanan->batas_waktu && $batasWaktu->isPast())
                    <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    <strong>Batas waktu pembayaran telah berakhir.</strong> Pesanan ini akan otomatis dibatalkan.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Payment Method Instructions -->
                    @if($pesanan->metode_pembayaran == 'transfer_bank')
                        <h4 class="text-base font-medium text-gray-900 mb-3">Transfer Bank</h4>
                        <div class="mb-4 border border-gray-200 rounded-md p-3 bg-gray-50">
                            <div class="flex justify-between items-center mb-2">
                                <div class="font-medium text-gray-900">BCA</div>
                                <button type="button" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 copy-button" data-clipboard="0240934507">
                                    Salin
                                </button>
                            </div>
                            <div class="font-mono font-medium text-lg mb-1">0240934507</div>
                            <div class="text-sm text-gray-500">a.n PT TOKO IKAN SEGAR</div>
                        </div>

                        <div class="mb-4 border border-gray-200 rounded-md p-3 bg-gray-50">
                            <div class="flex justify-between items-center mb-2">
                                <div class="font-medium text-gray-900">Mandiri</div>
                                <button type="button" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 copy-button" data-clipboard="1301234567890">
                                    Salin
                                </button>
                            </div>
                            <div class="font-mono font-medium text-lg mb-1">1301234567890</div>
                            <div class="text-sm text-gray-500">a.n PT TOKO IKAN SEGAR</div>
                        </div>
                    @elseif($pesanan->metode_pembayaran == 'qris')
                        <h4 class="text-base font-medium text-gray-900 mb-3">QRIS</h4>
                        <div class="text-center border border-gray-200 rounded-md p-4 mb-4 bg-gray-50">
                            <div class="mb-3">
                                <img src="{{ asset('Images\Qris-code.png') }}" alt="QRIS Code" height="250" width="250" class="mx-auto" id="qrisImage" onerror="this.onerror=null; showQRISError();">
                            </div>
                            <div id="qrisInfo">
                                <p class="text-sm text-gray-600 mb-1">Scan QR code di atas menggunakan aplikasi e-wallet atau m-banking Anda</p>
                                <p class="text-sm text-gray-600">Total Pembayaran: <strong>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong></p>
                                <div class="mt-3 p-2 bg-yellow-50 border border-yellow-100 rounded text-sm">
                                    <p class="font-medium text-yellow-800">Merchant: WIB</p>
                                </div>
                            </div>
                            <div id="qrisError" class="hidden">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">Informasi QRIS</h3>
                                            <p class="text-sm text-yellow-700">Silakan hubungi admin untuk mendapatkan kode QRIS yang valid. Transfer tepat sejumlah: <strong>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Upload Bukti -->
                    @if(!$pesanan->bukti_pembayaran)
                    <div class="pt-4 border-t border-gray-200 mt-4">
                        <h4 class="text-base font-medium text-gray-900 mb-3">Upload Bukti Pembayaran</h4>
                        <form action="{{ route('pesanan.payment', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                            @csrf
                            <div class="mb-4">
                                <div class="relative">
                                    <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-orange-500 transition-colors duration-200 ease-in-out cursor-pointer bg-gray-50">
                                        <input type="file" class="hidden" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/jpeg,image/png,image/jpg" required>
                                        <div class="space-y-2" id="dropzone-text">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="text-sm text-gray-600">
                                                <span class="font-medium text-orange-600">Klik untuk upload</span> atau drag and drop
                                            </div>
                                            <p class="text-xs text-gray-500">Format: JPG, PNG, JPEG (Maks. 2MB)</p>
                                        </div>
                                        <div id="preview" class="hidden mt-4">
                                            <img id="image-preview" class="mx-auto max-h-48 rounded-lg" src="" alt="Preview">
                                            <button type="button" id="remove-image" class="mt-2 text-sm text-red-600 hover:text-red-800">
                                                Hapus Gambar
                                            </button>
                                        </div>
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

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const dropzone = document.getElementById('dropzone');
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

                            function preventDefaults (e) {
                                e.preventDefault();
                                e.stopPropagation();
                            }

                            function highlight(e) {
                                dropzone.classList.add('border-orange-500', 'bg-orange-50');
                            }

                            function unhighlight(e) {
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
                        });
                    </script>
                    @endif
                @endif
            </div>
        @endif

        @if($pesanan->bukti_pembayaran)
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Bukti Pembayaran</h3>
            </div>
            <div class="px-4 py-5 sm:px-6 text-center">
                <div class="border border-gray-200 rounded-md p-2 mb-3 bg-gray-50 inline-block">
                    <img src="{{ asset($pesanan->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="max-h-[300px]">
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4 flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="ml-3 text-sm text-blue-700">
                        Bukti pembayaran telah diunggah. Harap tunggu konfirmasi dari admin.
                    </p>
                </div>
            </div>
        </div>
        @endif

        @if($pesanan->status_pesanan == 'Dikirim')
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Pesanan</h3>
            </div>
            <div class="px-4 py-5 sm:px-6">
                <div class="bg-cyan-50 border border-cyan-200 rounded-md p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-cyan-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-cyan-700">
                                    Apakah Anda sudah menerima pesanan ini?
                                </p>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('pesanan.konfirmasi', $pesanan->id_pesanan) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Pesanan Diterima
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Tindakan</h3>
            </div>
            <div class="px-4 py-5 sm:px-6">
                <a href="{{ route('pesanan.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modern clipboard API with fallback
    document.querySelectorAll('.copy-button').forEach(button => {
        button.addEventListener('click', async function() {
            const text = this.dataset.clipboard;
            const originalText = this.textContent.trim();
            const originalClasses = [...this.classList];

            try {
                // Use modern clipboard API if available
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    await navigator.clipboard.writeText(text);
                } else {
                    // Fallback for browsers that don't support clipboard API
                    const tempInput = document.createElement('input');
                    tempInput.value = text;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    document.execCommand('copy');
                    document.body.removeChild(tempInput);
                }

                // Visual feedback
                const checkIcon = document.createElement('span');
                checkIcon.innerHTML = `
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;

                this.textContent = 'Tersalin!';
                this.classList.add('bg-green-100', 'text-green-800', 'border-green-300');
                this.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                this.prepend(checkIcon);

                setTimeout(() => {
                    this.textContent = originalText;
                    this.classList.remove('bg-green-100', 'text-green-800', 'border-green-300');
                    this.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
                }, 2000);
            } catch (err) {
                console.error('Failed to copy text: ', err);
            }
        });
    });

    // Payment countdown timer with improved visualization
    @if(isset($pesanan) && $pesanan->status_pesanan == 'Menunggu Pembayaran' && $pesanan->batas_waktu)
        const batasWaktu = new Date('{{ $pesanan->batas_waktu }}').getTime();
        const countdownEls = document.querySelectorAll('.payment-countdown');

        if (countdownEls.length > 0) {
            const updateCountdown = () => {
                const now = new Date().getTime();
                const distance = batasWaktu - now;

                if (distance < 0) {
                    countdownEls.forEach(el => {
                        el.innerHTML = '<span class="text-red-600 font-medium">Waktu pembayaran telah habis</span>';
                        el.classList.add('expired');
                    });
                    return false;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                let countdownHTML = '';

                if (days > 0) {
                    countdownHTML += `<span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">${days}d</span> `;
                }

                countdownHTML += `
                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">${hours.toString().padStart(2, '0')}</span>:
                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">${minutes.toString().padStart(2, '0')}</span>:
                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded">${seconds.toString().padStart(2, '0')}</span>
                `;

                countdownEls.forEach(el => {
                    el.innerHTML = countdownHTML;
                });

                return true;
            };

            if (updateCountdown()) {
                const countdown = setInterval(() => {
                    if (!updateCountdown()) {
                        clearInterval(countdown);
                    }
                }, 1000);
            }
        }
    @endif
});
</script>
@endsection
