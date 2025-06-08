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
                                    @else
                                        <span class="inline-flex items-center px-4 py-2 border border-green-300 rounded-sm text-sm font-medium text-green-700 bg-green-50">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Sudah Diulas
                                        </span>
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
                        <div class="product-review-data" 
                             data-product-id="{{ $detailPesanan->id_Produk }}"
                             data-product-name="{{ $detailPesanan->produk->nama_ikan ?? 'Produk tidak tersedia' }}"
                             data-product-image="@if($detailPesanan->produk && $detailPesanan->produk->gambar)@if(Str::startsWith($detailPesanan->produk->gambar, 'uploads/')){{ asset($detailPesanan->produk->gambar) }}@elseif(Str::startsWith($detailPesanan->produk->gambar, 'storage/')){{ asset($detailPesanan->produk->gambar) }}@else{{ asset('storage/' . $detailPesanan->produk->gambar) }}@endif@endif"
                             data-product-size="@if($detailPesanan->ukuran_id && isset($detailPesanan->ukuran)){{ $detailPesanan->ukuran->ukuran }}@endif"
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
@endsection

@push('scripts')
<script>
    // Toggle review section visibility
    function toggleReviewSection(orderId) {
        const reviewSection = document.getElementById(`review-section-${orderId}`);
        if (reviewSection.classList.contains('hidden')) {
            reviewSection.classList.remove('hidden');
            // Scroll to review section
            reviewSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            reviewSection.classList.add('hidden');
        }
    }

    // Set rating for product
    function setRating(orderId, productIndex, rating) {
        // Update hidden input
        document.getElementById(`rating-input-${orderId}-${productIndex}`).value = rating;
        
        // Update star colors
        const stars = document.querySelectorAll(`.rating-star-${orderId}-${productIndex}`);
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-orange-400');
            } else {
                star.classList.remove('text-orange-400');
                star.classList.add('text-gray-300');
            }
        });

        // Hide error message
        document.querySelector(`.rating-error-${orderId}-${productIndex}`).classList.add('hidden');
    }

    // Update character count for comments
    function updateCharCount(orderId, productIndex) {
        const textarea = document.getElementById(`comment-${orderId}-${productIndex}`);
        const charCountSpan = document.querySelector(`.char-count-${orderId}-${productIndex}`);
        const length = textarea.value.length;
        
        charCountSpan.textContent = `${length}/500`;
        
        if (length >= 10) {
            document.querySelector(`.comment-error-${orderId}-${productIndex}`).classList.add('hidden');
        }
    }

    // Form validation and submission
    document.addEventListener('DOMContentLoaded', function() {
        // Handle form submissions
        document.querySelectorAll('form[id^="review-form-"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const orderId = this.id.split('-')[2]; // Extract order ID from form ID
                let isValid = true;
                
                // Validate each product review
                const products = this.querySelectorAll('input[name$="[id_produk]"]');
                products.forEach((productInput, index) => {
                    const ratingInput = document.getElementById(`rating-input-${orderId}-${index}`);
                    const commentTextarea = document.getElementById(`comment-${orderId}-${index}`);
                    
                    // Validate rating
                    if (!ratingInput.value) {
                        document.querySelector(`.rating-error-${orderId}-${index}`).classList.remove('hidden');
                        isValid = false;
                    }
                    
                    // Validate comment
                    if (commentTextarea.value.length < 10) {
                        document.querySelector(`.comment-error-${orderId}-${index}`).classList.remove('hidden');
                        isValid = false;
                    }
                });
                
                if (isValid) {
                    // Show loading state
                    const submitBtn = document.getElementById(`submit-review-${orderId}`);
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Mengirim...';
                    
                    // Submit form
                    this.submit();
                }
            });
        });
    });

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

    // Confirm create review function - DEPRECATED, now using inline forms
    function confirmCreateReview(orderId) {
        // Toggle the review section instead of redirecting
        toggleReviewSection(orderId);
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
