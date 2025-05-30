@extends('admin.layouts.app')

@section('title', 'Detail Pesanan #' . $pesanan->id_pesanan)

@section('styles')
<style>
    /* Tailwind utility classes for order details page */
    /* Color variables */
    .color-orange {
        color: #ea580c; /* text-orange-600 */
    }
    .bg-orange-light {
        background-color: #fff7ed; /* bg-orange-50 */
    }
    .border-orange {
        border-color: #ea580c; /* border-orange-600 */
    }
    .color-green {
        color: #16a34a; /* text-green-600 */
    }
    .bg-green-light {
        background-color: #f0fdf4; /* bg-green-50 */
    }
    .border-green {
        border-color: #16a34a; /* border-green-600 */
    }

    /* Status badges */
    .status-badge {
        padding-left: 1rem;
        padding-right: 1rem;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .status-menunggu-pembayaran {
        background-color: #fef9c3;
        color: #a16207;
        border: 1px solid #fef08a;
    }
    .status-menunggu-konfirmasi {
        background-color: #dbeafe;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }
    .status-pembayaran-dikonfirmasi {
        background-color: #dbeafe;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }
    .status-diproses {
        background-color: #e0e7ff;
        color: #4338ca;
        border: 1px solid #c7d2fe;
    }
    .status-dikirim {
        background-color: #f3e8ff;
        color: #7e22ce;
        border: 1px solid #e9d5ff;
    }
    .status-selesai {
        background-color: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .status-dibatalkan {
        background-color: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    /* Timeline styles */
    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    /* We don't need this anymore as we use a custom line in the HTML
    .timeline:before {
        content: '';
        position: absolute;
        left: 9px;
        top: 0;
        height: 100%;
        width: 2px;
        background-color: #e5e7eb;
    }
    */

    /* These styles are now applied directly in the HTML with Tailwind classes
    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item:before {
        content: '';
        position: absolute;
        left: -2rem;
        top: 0;
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 9999px;
        background-color: white;
        border: 2px solid #2563eb;
    }

    .timeline-item.completed:before {
        background-color: #16a34a;
        border-color: #16a34a;
    }

    .timeline-item.current:before {
        background-color: white;
        border-color: #ea580c;
        box-shadow: 0 0 0 3px rgba(234,88,12,0.2);
    }

    .timeline-item.pending:before {
        background-color: white;
        border-color: #e5e7eb;
    }
    */

    /* We keep these for compatibility with other parts of the page */
    .timeline-date {
        font-size: 0.75rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .timeline-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
        color: #1f2937;
    }

    .timeline-body {
        font-size: 0.875rem;
        color: #4b5563;
    }

    /* Product listing */
    .product-item {
        transition: all 200ms;
        border-bottom: 1px solid #f3f4f6;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }

    .product-item:last-child {
        border-bottom: none;
    }

    .product-item:hover {
        background-color: #f9fafb;
    }

    /* Information cards */
    .card-border-info {
        border-left-width: 4px;
        border-left-color: #3b82f6;
    }

    .card-border-orange {
        border-left-width: 4px;
        border-left-color: #ea580c;
    }

    .card-border-green {
        border-left-width: 4px;
        border-left-color: #16a34a;
    }

    /* Status stepper */
    .status-stepper {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-top: 2rem;
        margin-bottom: 2rem;
    }

    .status-stepper:before {
        content: '';
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e5e7eb;
        z-index: 1;
    }

    .step {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }

    .step-icon {
        width: 30px;
        height: 30px;
        border-radius: 9999px;
        background-color: white;
        border: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
    }

    .step.completed .step-icon {
        background-color: #16a34a;
        border-color: #16a34a;
        color: white;
    }

    .step.current .step-icon {
        border-color: #ea580c;
        border-width: 3px;
        color: #ea580c;
    }

    .step-label {
        font-size: 0.75rem;
        color: #6b7280;
        text-align: center;
        margin-top: 0.25rem;
    }

    .step.completed .step-label,
    .step.current .step-label {
        font-weight: 600;
        color: #ea580c;
    }

    /* Action buttons */
    .btn-orange {
        background-color: #ea580c;
        border: 1px solid #ea580c;
        color: white;
    }

    .btn-orange:hover {
        background-color: #c2410c;
        border-color: #c2410c;
    }

    .btn-outline-orange {
        border: 1px solid #ea580c;
        color: #ea580c;
    }

    .btn-outline-orange:hover {
        background-color: #ea580c;
        color: white;
    }

    /* Modal system - complete overhaul */
    .modal-hidden {
        display: none !important;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
        overflow-y: auto;
    }

    .modal-overlay.show {
        display: flex !important;
        animation: fadeIn 0.2s ease-out forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-dialog {
        background: white;
        border-radius: 8px;
        max-width: 600px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        transform: translateY(0);
        transition: transform 0.3s ease;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        transform: scale(0.9);
        transition: transform 0.2s ease-out;
    }

    .modal-overlay.show .modal-dialog {
        transform: scale(1);
    }
</style>
@endsection

@section('content')
<div class="container mx-auto">
    <!-- Page Heading -->
    <div class="flex items-center justify-between mb-4">
        <div>
            <a href="{{ route('admin.pesanan.index') }}" class="inline-flex items-center px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded mb-2 transition duration-150">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <h1 class="text-2xl font-semibold text-gray-800">Detail Pesanan #{{ $pesanan->id_pesanan }}</h1>
            <div class="mt-1 text-sm text-gray-500">
                Dibuat pada {{ $pesanan->created_at->format('d M Y, H:i') }} WIB
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="inline-flex items-center px-3 py-1.5 border border-orange-500 text-orange-500 hover:bg-orange-50 text-sm font-medium rounded shadow-sm transition duration-150">
                <i class="fas fa-print fa-sm mr-1"></i> Cetak Invoice
            </button>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="inline-flex items-center px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded shadow-sm transition duration-150">
                    <i class="fas fa-cog fa-sm mr-1"></i> Tindakan
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open"
                     @click.away="open = false"
                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95">
                    <div class="py-1">
                        @if($pesanan->status_pesanan == 'Menunggu Pembayaran')
                            @if($pesanan->bukti_pembayaran)
                                <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 confirm-payment" href="#" data-id="{{ $pesanan->id_pesanan }}">
                                    <i class="fas fa-check-circle fa-sm fa-fw mr-2 text-gray-400"></i> Konfirmasi Pembayaran
                                </a>
                            @endif
                            <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cancel-order" href="#" data-id="{{ $pesanan->id_pesanan }}">
                                <i class="fas fa-times-circle fa-sm fa-fw mr-2 text-gray-400"></i> Batalkan Pesanan
                            </a>
                        @elseif($pesanan->status_pesanan == 'Pembayaran Dikonfirmasi')
                            <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 process-order" href="#" data-id="{{ $pesanan->id_pesanan }}">
                                <i class="fas fa-box fa-sm fa-fw mr-2 text-gray-400"></i> Proses Pesanan
                            </a>
                        @elseif($pesanan->status_pesanan == 'Diproses')
                            <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 ship-order" href="#" data-id="{{ $pesanan->id_pesanan }}">
                                <i class="fas fa-shipping-fast fa-sm fa-fw mr-2 text-gray-400"></i> Kirim Pesanan
                            </a>
                        @endif
                        <div class="border-t border-gray-100 my-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Header -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="bg-orange-600 text-white px-5 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <i class="fas fa-shopping-bag mr-2"></i>
                <span class="font-bold">PESANAN {{ strtoupper($pesanan->id_pesanan) }}</span>
            </div>
            <div class="flex items-center">
                @php
                    $statusMap = [
                        'Menunggu Pembayaran' => 'BELUM BAYAR',
                        'Pembayaran Dikonfirmasi' => 'SEDANG DIKEMAS',
                        'Diproses' => 'SEDANG DIKEMAS',
                        'Dikirim' => 'DIKIRIM',
                        'Selesai' => 'SELESAI',
                        'Dibatalkan' => 'DIBATALKAN',
                        'Karantina' => 'KARANTINA',
                        'Pengembalian' => 'PENGEMBALIAN',
                    ];

                    // Special case for when payment proof uploaded but not yet confirmed
                    if ($pesanan->status_pesanan == 'Menunggu Pembayaran' && $pesanan->bukti_pembayaran) {
                        $status = 'MENUNGGU KONFIRMASI';
                    } else {
                        $status = $statusMap[$pesanan->status_pesanan] ?? $pesanan->status_pesanan;
                    }
                @endphp
                <span class="bg-white text-gray-800 px-3 py-1 rounded-full text-xs font-bold">{{ $status }}</span>

                @if($pesanan->status_pesanan == 'Menunggu Pembayaran' && !$pesanan->bukti_pembayaran && $pesanan->batas_waktu)
                    @php
                        $batasWaktu = $pesanan->batas_waktu instanceof \Carbon\Carbon
                            ? $pesanan->batas_waktu
                            : \Carbon\Carbon::parse($pesanan->batas_waktu);
                    @endphp

                    @if($batasWaktu->isPast())
                        <span class="bg-red-100 text-red-800 ml-2 px-3 py-1 rounded-full text-xs font-bold">Batas waktu habis</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-800 ml-2 px-3 py-1 rounded-full text-xs font-bold">Batas waktu: {{ $batasWaktu->diffForHumans() }}</span>
                    @endif
                @endif
            </div>
        </div>

        <!-- Order Flow Status -->
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-800 flex items-center">
                    <span class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center mr-2">
                        <i class="fas fa-route text-orange-600"></i>
                    </span>
                    Status Perjalanan Pesanan
                </h2>
                <p class="text-sm text-gray-500 mt-1 ml-10">Berikut adalah status dan perjalanan pesanan dari awal hingga selesai</p>
            </div>
            <style>
                .order-flow-tracker {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    position: relative;
                    margin: 2rem 0;
                    padding: 0 1rem;
                }

                /* Track Line */
                .order-flow-tracker::before {
                    content: '';
                    position: absolute;
                    top: 2.5rem;
                    left: 0;
                    width: 100%;
                    height: 0.25rem;
                    background: #f3f4f6;
                    border-radius: 1rem;
                    box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
                    z-index: 1;
                }

                /* Progress Bar */
                .order-flow-tracker .progress {
                    position: absolute;
                    top: 2.5rem;
                    left: 0;
                    height: 0.25rem;
                    background: linear-gradient(90deg, #f97316, #f59e0b, #eab308);
                    border-radius: 1rem;
                    transition: width 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
                    box-shadow: 0 1px 3px rgba(249, 115, 22, 0.4);
                    z-index: 2;
                }

                /* Status Step Container */
                .order-flow-tracker .status-step {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    position: relative;
                    z-index: 3;
                    width: 25%;
                    transition: all 0.3s ease;
                }

                /* Status Icon */
                .order-flow-tracker .status-icon {
                    width: 5rem;
                    height: 5rem;
                    border-radius: 50%;
                    background: white;
                    border: 5px solid #e5e7eb;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-bottom: 0.75rem;
                    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
                    transition: all 0.5s ease;
                    position: relative;
                    overflow: hidden;
                }

                .order-flow-tracker .status-icon::after {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 100%);
                    border-radius: 50%;
                }

                .order-flow-tracker .status-icon i {
                    font-size: 1.75rem;
                    color: #9ca3af;
                    transition: all 0.3s ease;
                    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
                }

                /* Status Text */
                .order-flow-tracker .status-label {
                    font-size: 1rem;
                    font-weight: 600;
                    color: #4b5563;
                    text-align: center;
                    margin-top: 0.75rem;
                    transition: all 0.3s ease;
                }

                .order-flow-tracker .status-date {
                    font-size: 0.875rem;
                    color: #6b7280;
                    margin-top: 0.25rem;
                    background-color: rgba(243, 244, 246, 0.7);
                    padding: 0.25rem 0.75rem;
                    border-radius: 1rem;
                    transition: all 0.3s ease;
                }

                /* Completed Step */
                .order-flow-tracker .completed .status-icon {
                    background: linear-gradient(135deg, #f97316, #fb923c);
                    border-color: #fdba74;
                    box-shadow: 0 0 20px rgba(249, 115, 22, 0.3);
                    transform: scale(1.05);
                }

                .order-flow-tracker .completed .status-icon i {
                    color: white;
                }

                .order-flow-tracker .completed .status-label {
                    color: #f97316;
                }

                .order-flow-tracker .completed .status-date {
                    color: #7c2d12;
                    background-color: rgba(254, 215, 170, 0.3);
                    font-weight: 500;
                }

                /* Current Step */
                .order-flow-tracker .current .status-icon {
                    background: white;
                    border-color: #f97316;
                    animation: pulse 3s infinite;
                    transform: scale(1.1);
                }

                .order-flow-tracker .current .status-icon::before {
                    content: '';
                    position: absolute;
                    width: 100%;
                    height: 100%;
                    border-radius: 50%;
                    border: 3px solid #f97316;
                    animation: ripple 2s linear infinite;
                    opacity: 0;
                }

                .order-flow-tracker .current .status-icon i {
                    color: #f97316;
                    animation: bounce 2s ease infinite;
                }

                .order-flow-tracker .current .status-label {
                    color: #f97316;
                    font-weight: 700;
                    font-size: 1.1rem;
                }

                .order-flow-tracker .current .status-date {
                    color: #7c2d12;
                    background-color: rgba(254, 215, 170, 0.5);
                    font-weight: 600;
                    box-shadow: 0 2px 4px rgba(249, 115, 22, 0.2);
                }

                /* Animations */
                @keyframes pulse {
                    0% { box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.7); }
                    70% { box-shadow: 0 0 0 15px rgba(249, 115, 22, 0); }
                    100% { box-shadow: 0 0 0 0 rgba(249, 115, 22, 0); }
                }

                @keyframes ripple {
                    0% { transform: scale(1); opacity: 0.7; }
                    100% { transform: scale(1.5); opacity: 0; }
                }

                @keyframes bounce {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-5px); }
                }

                /* Cancelled Order */
                .cancelled-order {
                    background-color: #fef2f2;
                    background-image: repeating-linear-gradient(-45deg, #fee2e2 0px, #fee2e2 20px, #fef2f2 20px, #fef2f2 40px);
                    border: 3px dashed #ef4444;
                    border-radius: 1rem;
                    padding: 2.5rem;
                    position: relative;
                    box-shadow: inset 0 0 10px rgba(239, 68, 68, 0.1), 0 5px 15px rgba(0, 0, 0, 0.05);
                    overflow: hidden;
                }

                .cancelled-icon {
                    width: 6rem;
                    height: 6rem;
                    background: white;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
                    margin: 0 auto 1.5rem;
                    border: 5px solid #fee2e2;
                    position: relative;
                    transform: perspective(800px) rotateX(10deg);
                }

                .cancelled-icon i {
                    font-size: 3rem;
                    color: #ef4444;
                }

                .cancelled-stamp {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%) rotate(-30deg);
                    border: 6px solid #ef4444;
                    color: #ef4444;
                    font-size: 2rem;
                    font-weight: 900;
                    text-transform: uppercase;
                    padding: 0.75rem 2.5rem;
                    border-radius: 0.75rem;
                    opacity: 0.15;
                    pointer-events: none;
                    letter-spacing: 2px;
                    text-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
                }
            </style>

            @php
                $statuses = [
                    'Menunggu Pembayaran' => [
                        'icon' => 'fas fa-clock',
                        'color' => 'warning',
                        'desc' => 'Menunggu konfirmasi pembayaran dari pelanggan. Pesanan akan diproses setelah pembayaran terverifikasi.'
                    ],
                    'Diproses' => [
                        'icon' => 'fas fa-box-open',
                        'color' => 'primary',
                        'desc' => 'Pembayaran telah dikonfirmasi. Tim kami sedang mempersiapkan pesanan untuk pengiriman.'
                    ],
                    'Dikirim' => [
                        'icon' => 'fas fa-shipping-fast',
                        'color' => 'secondary',
                        'desc' => 'Pesanan telah diserahkan ke kurir dan sedang dalam perjalanan menuju alamat pengiriman.'
                    ],
                    'Selesai' => [
                        'icon' => 'fas fa-check-circle',
                        'color' => 'success',
                        'desc' => 'Pesanan telah diterima oleh pelanggan dan transaksi telah selesai.'
                    ],
                ];

                // Map 'Pembayaran Dikonfirmasi' to 'Diproses' for timeline
                $currentStatus = $pesanan->status_pesanan;
                if ($currentStatus == 'Pembayaran Dikonfirmasi') {
                    $currentStatus = 'Diproses';
                }

                $statusKeys = array_keys($statuses);
                $currentStatusIndex = array_search($currentStatus, $statusKeys);
                $isCancelled = $pesanan->status_pesanan === 'Dibatalkan';

                // Calculate progress percentage based on current status
                $progressPercentage = $currentStatusIndex !== false
                    ? ($currentStatusIndex / (count($statusKeys) - 1)) * 100
                    : 0;
            @endphp

            @if($isCancelled)
                <div class="cancelled-order">
                    <div class="cancelled-stamp">Dibatalkan</div>
                    <div class="cancelled-icon">
                        <i class="fas fa-times"></i>
                    </div>
                    <h5 class="text-2xl font-bold text-red-600 text-center mb-3">Pesanan Dibatalkan</h5>
                    <p class="text-red-500 text-center max-w-lg mx-auto text-lg">
                        {{ $pesanan->keterangan_status ?? 'Pesanan ini telah dibatalkan' }}
                    </p>

                    <div class="flex justify-center mt-6 space-x-4">
                        <div class="bg-white px-5 py-3 rounded-xl shadow-md text-sm border border-red-100">
                            <div class="flex items-center">
                                <i class="far fa-calendar-times text-red-500 mr-2"></i>
                                <span class="font-medium text-gray-700">Dibatalkan pada:</span>
                                <span class="ml-1 text-red-600 font-semibold">{{ $pesanan->updated_at->format('d M Y, H:i') }} WIB</span>
                            </div>
                        </div>

                        <div class="bg-white px-5 py-3 rounded-xl shadow-md text-sm border border-red-100">
                            <div class="flex items-center">
                                <i class="fas fa-tag text-red-500 mr-2"></i>
                                <span class="font-medium text-gray-700">ID Pesanan:</span>
                                <span class="ml-1 text-red-600 font-semibold">#{{ $pesanan->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="order-flow-tracker">
                    <div class="progress" style="width: {{ $progressPercentage }}%;"></div>

                    @foreach($statuses as $status => $details)
                        @php
                            $index = array_search($status, $statusKeys);
                            $isCompleted = $index < $currentStatusIndex;
                            $isCurrent = $index === $currentStatusIndex;
                            $stepClass = $isCompleted ? 'completed' : ($isCurrent ? 'current' : '');
                        @endphp
                        <div class="status-step {{ $stepClass }}">
                            <div class="status-icon">
                                @if($isCompleted)
                                    <i class="fas fa-check"></i>
                                @else
                                    <i class="{{ $details['icon'] }}"></i>
                                @endif
                            </div>
                            <div class="status-label">{{ $status }}</div>
                            <div class="status-date">
                                @if($isCompleted || $isCurrent)
                                    @if($index == 0)
                                        {{ $pesanan->created_at->format('d M Y') }}
                                    @else
                                        {{ $pesanan->updated_at->format('d M Y') }}
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 p-5 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl border border-orange-100 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center shadow-lg">
                                <i class="{{ $statuses[$currentStatus]['icon'] }} text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-base font-bold text-gray-800">Status Pesanan:
                                <span class="ml-1 text-orange-600 border-b-2 border-orange-300 pb-0.5">{{ $currentStatus }}</span>
                            </h4>
                            <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $statuses[$currentStatus]['desc'] }}</p>

                            <div class="flex mt-3 space-x-3">
                                <div class="px-3 py-1.5 bg-white rounded-lg shadow-sm border border-gray-100">
                                    <span class="text-xs text-gray-500">Waktu Update</span>
                                    <div class="text-sm font-medium text-gray-700 flex items-center mt-0.5">
                                        <i class="far fa-clock mr-1.5 text-orange-500"></i>
                                        {{ $pesanan->updated_at->format('d M Y, H:i') }} WIB
                                    </div>
                                </div>

                                <div class="px-3 py-1.5 bg-white rounded-lg shadow-sm border border-gray-100">
                                    <span class="text-xs text-gray-500">ID Pesanan</span>
                                    <div class="text-sm font-medium text-gray-700 flex items-center mt-0.5">
                                        <i class="fas fa-tag mr-1.5 text-orange-500"></i>
                                        #{{ $pesanan->id }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Content Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main content area -->
        <div class="lg:col-span-2">
            <!-- Product Details -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="px-5 py-4 flex justify-between items-center border-b border-gray-200">
                    <h3 class="text-base font-semibold text-orange-600 flex items-center">
                        <i class="fas fa-box mr-2"></i> Detail Produk
                    </h3>
                </div>
                <div>
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <div class="flex items-center">
                            <i class="fas fa-store mr-2 text-gray-600"></i>
                            <span class="font-medium">WIB Store</span>
                        </div>
                    </div>

                    <!-- Product List -->
                    <div>
                        @foreach($pesanan->detailPesanan as $detail)
                            <div class="product-item">
                                <div class="flex flex-wrap md:flex-nowrap p-4">
                                    <div class="flex-shrink-0">
                                        @if($detail->produk && $detail->produk->gambar)
                                            @if(Str::startsWith($detail->produk->gambar, 'uploads/'))
                                                <img src="{{ asset($detail->produk->gambar) }}" alt="{{ $detail->produk->nama_ikan }}" class="h-16 w-16 object-cover border border-gray-200 rounded">
                                            @elseif(Str::startsWith($detail->produk->gambar, 'storage/'))
                                                <img src="{{ asset($detail->produk->gambar) }}" alt="{{ $detail->produk->nama_ikan }}" class="h-16 w-16 object-cover border border-gray-200 rounded">
                                            @else
                                                <img src="{{ asset('storage/' . $detail->produk->gambar) }}" alt="{{ $detail->produk->nama_ikan }}" class="h-16 w-16 object-cover border border-gray-200 rounded">
                                            @endif
                                        @else
                                            <div class="flex items-center justify-center bg-gray-100 text-gray-400 rounded h-16 w-16">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-grow min-w-0">
                                        <div class="font-medium">
                                            @if($detail->produk)
                                                {{ $detail->produk->nama_ikan }}
                                            @else
                                                Produk tidak tersedia
                                            @endif
                                        </div>
                                        @if($detail->ukuran_id && isset($detail->ukuran))
                                            <div class="text-sm text-gray-500">Ukuran: {{ $detail->ukuran->ukuran }}</div>
                                        @endif
                                    </div>
                                    <div class="mt-2 md:mt-0 md:ml-3 text-center" style="min-width: 60px;">
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs font-medium">
                                            x{{ $detail->kuantitas }}
                                        </span>
                                    </div>
                                    <div class="mt-2 md:mt-0 md:ml-3 text-right" style="min-width: 120px;">
                                        <div class="text-sm text-gray-500">
                                            <span>Harga:</span>
                                            <div>Rp {{ number_format($detail->harga, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                    <div class="mt-2 md:mt-0 md:ml-3 text-right" style="min-width: 120px;">
                                        <div class="font-semibold text-orange-600">
                                            <span class="text-sm text-gray-500">Subtotal:</span>
                                            <div>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Summary -->
                    <div class="p-4 bg-gray-50 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2">
                            <div class="md:col-start-2">
                                <div class="flex justify-between mb-2">
                                    <div class="text-gray-500">Subtotal:</div>
                                    <div>Rp {{ number_format($pesanan->detailPesanan->sum('subtotal'), 0, ',', '.') }}</div>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <div class="text-gray-500">Ongkir:</div>
                                    <div>Rp {{ number_format($pesanan->ongkir_biaya ?? ($pesanan->ongkir->biaya ?? 0), 0, ',', '.') }}</div>
                                </div>
                                <hr class="my-2 border-gray-200">
                                <div class="flex justify-between">
                                    <div class="font-bold">Total:</div>
                                    <div class="font-bold text-orange-600 text-lg">
                                        Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="border-b border-gray-200 px-5 py-4">
                    <h3 class="text-base font-semibold text-orange-600 flex items-center">
                        <i class="fas fa-history mr-2"></i> Log Aktivitas Pesanan
                    </h3>
                </div>
                <div class="p-6">
                    <div class="timeline relative">
                        <!-- Line running through timeline -->
                        @php
                            $timelineItemCount = 0;
                            if($pesanan->created_at) $timelineItemCount++;
                            if($pesanan->status_pesanan != 'Menunggu Pembayaran') $timelineItemCount++;
                            if(in_array($pesanan->status_pesanan, ['Diproses', 'Dikirim', 'Selesai'])) $timelineItemCount++;
                            if(in_array($pesanan->status_pesanan, ['Dikirim', 'Selesai'])) $timelineItemCount++;
                            if($pesanan->status_pesanan == 'Selesai') $timelineItemCount++;
                            if($pesanan->status_pesanan == 'Dibatalkan') $timelineItemCount++;
                        @endphp

                        @if($timelineItemCount > 1)
                        <div class="absolute left-9 top-5 bottom-5 w-0.5 bg-gradient-to-b from-green-500 via-blue-500 to-gray-200"></div>
                        @endif

                        @if($pesanan->created_at)
                            <div class="timeline-item relative pl-14 pb-8 flex">
                                <div class="absolute left-0 flex items-center justify-center w-7 h-7 rounded-full bg-green-500 text-white z-10 border-4 border-white">
                                    <i class="fas fa-shopping-cart text-xs"></i>
                                </div>
                                <div class="flex-1 bg-green-50 rounded-lg p-4 shadow-sm border border-green-100">
                                    <div class="flex flex-wrap justify-between items-center mb-2">
                                        <div class="timeline-date text-xs text-gray-500 font-semibold">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ $pesanan->created_at->format('d M Y, H:i') }}
                                        </div>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Selesai</span>
                                    </div>
                                    <div class="timeline-title text-base text-gray-800 font-bold mb-1">Pesanan Dibuat</div>
                                    <div class="timeline-body text-sm text-gray-600">
                                        Pesanan baru <span class="font-medium">#{{ $pesanan->id_pesanan }}</span> telah dibuat oleh {{ $pesanan->user->name ?? 'Pelanggan' }}.
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($pesanan->status_pesanan != 'Menunggu Pembayaran')
                            <div class="timeline-item relative pl-14 pb-8 flex">
                                <div class="absolute left-0 flex items-center justify-center w-7 h-7 rounded-full bg-blue-500 text-white z-10 border-4 border-white">
                                    <i class="fas fa-money-bill-wave text-xs"></i>
                                </div>
                                <div class="flex-1 bg-blue-50 rounded-lg p-4 shadow-sm border border-blue-100">
                                    <div class="flex flex-wrap justify-between items-center mb-2">
                                        <div class="timeline-date text-xs text-gray-500 font-semibold">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ $pesanan->updated_at->format('d M Y, H:i') }}
                                        </div>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Selesai</span>
                                    </div>
                                    <div class="timeline-title text-base text-gray-800 font-bold mb-1">Pembayaran Dikonfirmasi</div>
                                    <div class="timeline-body text-sm text-gray-600">
                                        Pembayaran untuk pesanan <span class="font-medium">#{{ $pesanan->id_pesanan }}</span> telah dikonfirmasi.
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(in_array($pesanan->status_pesanan, ['Diproses', 'Dikirim', 'Selesai']))
                            <div class="timeline-item relative pl-14 pb-8 flex">
                                <div class="absolute left-0 flex items-center justify-center w-7 h-7 rounded-full bg-indigo-500 text-white z-10 border-4 border-white">
                                    <i class="fas fa-box text-xs"></i>
                                </div>
                                <div class="flex-1 bg-indigo-50 rounded-lg p-4 shadow-sm border border-indigo-100">
                                    <div class="flex flex-wrap justify-between items-center mb-2">
                                        <div class="timeline-date text-xs text-gray-500 font-semibold">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ $pesanan->updated_at->format('d M Y, H:i') }}
                                        </div>
                                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-full">Selesai</span>
                                    </div>
                                    <div class="timeline-title text-base text-gray-800 font-bold mb-1">Pesanan Diproses</div>
                                    <div class="timeline-body text-sm text-gray-600">
                                        Pesanan <span class="font-medium">#{{ $pesanan->id_pesanan }}</span> sedang diproses dan disiapkan untuk pengiriman.
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(in_array($pesanan->status_pesanan, ['Dikirim', 'Selesai']))
                            <div class="timeline-item relative pl-14 pb-8 flex">
                                <div class="absolute left-0 flex items-center justify-center w-7 h-7 rounded-full bg-purple-500 text-white z-10 border-4 border-white">
                                    <i class="fas fa-shipping-fast text-xs"></i>
                                </div>
                                <div class="flex-1 bg-purple-50 rounded-lg p-4 shadow-sm border border-purple-100">
                                    <div class="flex flex-wrap justify-between items-center mb-2">
                                        <div class="timeline-date text-xs text-gray-500 font-semibold">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ $pesanan->updated_at->format('d M Y, H:i') }}
                                        </div>
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded-full">Selesai</span>
                                    </div>
                                    <div class="timeline-title text-base text-gray-800 font-bold mb-1">Pesanan Dikirim</div>
                                    <div class="timeline-body text-sm text-gray-600">
                                        Pesanan <span class="font-medium">#{{ $pesanan->id_pesanan }}</span> telah dikirim
                                        @if($pesanan->nomor_resi)
                                        dengan nomor resi <span class="font-medium text-purple-700">{{ $pesanan->nomor_resi }}</span>
                                        @endif.
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($pesanan->status_pesanan == 'Selesai')
                            <div class="timeline-item relative pl-14 pb-8 flex">
                                <div class="absolute left-0 flex items-center justify-center w-7 h-7 rounded-full bg-green-600 text-white z-10 border-4 border-white">
                                    <i class="fas fa-check text-xs"></i>
                                </div>
                                <div class="flex-1 bg-green-50 rounded-lg p-4 shadow-sm border border-green-100">
                                    <div class="flex flex-wrap justify-between items-center mb-2">
                                        <div class="timeline-date text-xs text-gray-500 font-semibold">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ $pesanan->updated_at->format('d M Y, H:i') }}
                                        </div>
                                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Selesai</span>
                                    </div>
                                    <div class="timeline-title text-base text-gray-800 font-bold mb-1">Pesanan Selesai</div>
                                    <div class="timeline-body text-sm text-gray-600">
                                        Pesanan <span class="font-medium">#{{ $pesanan->id_pesanan }}</span> telah diterima oleh pelanggan.
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($pesanan->status_pesanan == 'Dibatalkan')
                            <div class="timeline-item relative pl-14 flex">
                                <div class="absolute left-0 flex items-center justify-center w-7 h-7 rounded-full bg-red-500 text-white z-10 border-4 border-white">
                                    <i class="fas fa-times text-xs"></i>
                                </div>
                                <div class="flex-1 bg-red-50 rounded-lg p-4 shadow-sm border border-red-100">
                                    <div class="flex flex-wrap justify-between items-center mb-2">
                                        <div class="timeline-date text-xs text-gray-500 font-semibold">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ $pesanan->updated_at->format('d M Y, H:i') }}
                                        </div>
                                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Dibatalkan</span>
                                    </div>
                                    <div class="timeline-title text-base text-red-700 font-bold mb-1">Pesanan Dibatalkan</div>
                                    <div class="timeline-body text-sm text-red-600">
                                        {{ $pesanan->keterangan_status ?? 'Pesanan ini telah dibatalkan.' }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Content -->
        <div class="lg:col-span-1">
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="border-b border-gray-200 px-5 py-4">
                    <h3 class="text-base font-semibold text-orange-600 flex items-center">
                        <i class="fas fa-user mr-2"></i> Informasi Pelanggan
                    </h3>
                </div>
                <div class="p-5">
                    <div class="p-4 mb-4 rounded-md bg-green-50 border-l-4 border-green-500">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-500 text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <h4 class="font-medium text-lg text-gray-900">{{ $pesanan->user->name ?? 'Pelanggan' }}</h4>
                                <div class="text-sm text-gray-600">{{ $pesanan->user->email ?? 'Email tidak tersedia' }}</div>
                                @if($pesanan->user && $pesanan->user->phone)
                                    <div class="text-sm text-gray-600">{{ $pesanan->user->phone }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h4 class="font-medium mb-2 text-gray-700">Alamat Pengiriman</h4>
                        <div class="p-3 rounded-md bg-gray-50 border border-gray-200 text-sm">
                            {{ $pesanan->alamat_pengiriman ?? 'Alamat tidak tersedia' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment & Shipping Info -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="border-b border-gray-200 px-5 py-4">
                    <h3 class="text-base font-semibold text-orange-600 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i> Informasi Pembayaran & Pengiriman
                    </h3>
                </div>
                <div class="p-5">
                    <div class="p-4 mb-4 rounded-md bg-orange-50 border-l-4 border-orange-500">
                        <h4 class="font-medium text-gray-800 mb-3">Detail Pembayaran</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <div class="text-sm text-gray-600">Metode Pembayaran</div>
                                <div>
                                    @if($pesanan->metode_pembayaran == 'transfer_bank')
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">Transfer Bank</span>
                                    @elseif($pesanan->metode_pembayaran == 'qris')
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">QRIS</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">{{ $pesanan->metode_pembayaran ?? 'Tidak tersedia' }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <div class="text-sm text-gray-600">Status Pembayaran</div>
                                <div>
                                    @if($pesanan->status_pesanan == 'Menunggu Pembayaran' && !$pesanan->bukti_pembayaran)
                                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Menunggu Pembayaran</span>
                                    @elseif($pesanan->status_pesanan == 'Menunggu Pembayaran' && $pesanan->bukti_pembayaran)
                                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full">Menunggu Konfirmasi</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Dibayar</span>
                                    @endif
                                </div>
                            </div>
                            @if($pesanan->bukti_pembayaran)
                                <div class="mt-3 text-center">
                                    <button type="button" class="btnLihatBuktiPembayaran inline-flex items-center px-4 py-2 bg-orange-100 text-orange-700 border border-orange-300 text-sm font-medium rounded-md hover:bg-orange-200 transition-colors duration-150 shadow-sm">
                                        <i class="fas fa-image fa-sm mr-2"></i> Lihat Bukti Pembayaran
                                    </button>
                                    <div class="text-xs text-gray-500 mt-1">Klik untuk melihat bukti pembayaran</div>

                                    <div class="border border-orange-200 rounded-md p-2 mt-2 bg-orange-50">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-gray-500">File: {{ basename($pesanan->bukti_pembayaran) }}</span>
                                            <a href="{{ route('admin.pesanan.payment-proof', ['id' => $pesanan->id_pesanan]) }}" target="_blank" class="text-xs text-blue-600 hover:underline">
                                                <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                                            </a>
                                        </div>

                                        <div class="mt-2 pt-2 border-t border-orange-200">
                                            <a href="{{ route('admin.diagnostic.payment-proofs', ['id' => $pesanan->id_pesanan]) }}" target="_blank" class="text-xs text-gray-600 hover:text-orange-600 flex items-center justify-center">
                                                <i class="fas fa-tools mr-1"></i> Diagnosa Gambar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 rounded-md bg-blue-50 border-l-4 border-blue-500">
                        <h4 class="font-medium text-gray-800 mb-3">Detail Pengiriman</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <div class="text-sm text-gray-600">Kurir</div>
                                <div>
                                    <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">{{ strtoupper($pesanan->kurir ?? 'TIKI') }}</span>
                                    @if($pesanan->kurir_service)
                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full ml-1">{{ $pesanan->kurir_service }}</span>
                                    @endif
                                </div>
                            </div>

                            @if($pesanan->berat_total)
                                <div class="flex justify-between">
                                    <div class="text-sm text-gray-600">Berat Total</div>
                                    <div class="text-sm">{{ number_format($pesanan->berat_total/1000, 1) }} kg</div>
                                </div>
                            @endif

                            @if($pesanan->nomor_resi)
                                <div class="mt-2">
                                    <label for="resiNumber" class="block text-sm text-gray-600 mb-1">Nomor Resi</label>
                                    <div class="flex rounded-md shadow-sm">
                                        <input type="text" id="resiNumber" value="{{ $pesanan->nomor_resi }}" readonly class="flex-grow min-w-0 block w-full px-3 py-1.5 text-sm border border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500" />
                                        <button type="button" onclick="copyResi()" class="inline-flex items-center px-3 py-1.5 border border-l-0 border-gray-300 bg-gray-50 text-gray-500 rounded-r-md hover:bg-gray-100">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-3 text-center">
                                    <a href="https://cekresi.com/?noresi={{ $pesanan->nomor_resi }}" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-blue-500 text-blue-500 text-sm font-medium rounded-md hover:bg-blue-50">
                                        <i class="fas fa-search fa-sm mr-1.5"></i> Lacak Pengiriman
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Actions -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="border-b border-gray-200 px-5 py-4">
                    <h3 class="text-base font-semibold text-orange-600 flex items-center">
                        <i class="fas fa-cog mr-2"></i> Tindakan Admin
                    </h3>
                </div>
                <div class="p-5">
                    <div class="space-y-3">
                        @if($pesanan->status_pesanan == 'Menunggu Pembayaran')
                            @if($pesanan->bukti_pembayaran)
                                <button class="w-full flex justify-center items-center py-2.5 px-4 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition duration-150 confirm-payment" data-id="{{ $pesanan->id_pesanan }}">
                                    <i class="fas fa-check-circle mr-2"></i> Konfirmasi Pembayaran
                                </button>
                                <button class="w-full flex justify-center items-center py-2.5 px-4 border border-red-500 text-red-500 hover:bg-red-50 text-sm font-medium rounded-md transition duration-150 cancel-order" data-id="{{ $pesanan->id_pesanan }}">
                                    <i class="fas fa-times-circle mr-2"></i> Batalkan Pesanan
                                </button>
                            @else
                                <button class="w-full flex justify-center items-center py-2.5 px-4 border border-red-500 text-red-500 hover:bg-red-50 text-sm font-medium rounded-md transition duration-150 cancel-order" data-id="{{ $pesanan->id_pesanan }}">
                                    <i class="fas fa-times-circle mr-2"></i> Batalkan Pesanan
                                </button>
                            @endif
                        @elseif($pesanan->status_pesanan == 'Pembayaran Dikonfirmasi')
                            <button class="w-full flex justify-center items-center py-2.5 px-4 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition duration-150 process-order" data-id="{{ $pesanan->id_pesanan }}">
                                <i class="fas fa-box mr-2"></i> Proses Pesanan
                            </button>
                        @elseif($pesanan->status_pesanan == 'Diproses')
                            <button class="w-full flex justify-center items-center py-2.5 px-4 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition duration-150 ship-order" data-id="{{ $pesanan->id_pesanan }}">
                                <i class="fas fa-shipping-fast mr-2"></i> Kirim Pesanan
                            </button>
                        @endif

                        <div class="border-t border-gray-200 my-4"></div>

                        <a href="{{ route('admin.pesanan.index') }}" class="w-full flex justify-center items-center py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-md transition duration-150">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Proof Modal -->
<div id="paymentProofModal" class="modal-hidden">
    <div class="modal-dialog">
        <div class="bg-gradient-to-r from-orange-600 to-orange-500 px-4 py-3 flex justify-between items-center rounded-t-lg">
            <h3 class="text-lg leading-6 font-medium text-white flex items-center" id="paymentProofModalLabel">
                <i class="fas fa-receipt mr-2"></i>
                Bukti Pembayaran
            </h3>
            <button type="button" class="text-white hover:text-gray-200 modal-close focus:outline-none focus:ring-2 focus:ring-white rounded-full p-1" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            @if($pesanan->bukti_pembayaran)
                <div class="text-center">
                    @php
                        // Try to determine correct image URL with better path handling
                        $fileName = basename($pesanan->bukti_pembayaran);

                        // Use our direct route for viewing payment proof images
                        $imageUrl = route('admin.pesanan.payment-proof', ['id' => $pesanan->id_pesanan]);

                        // Also prepare regular URLs for fallback purposes
                        $regularImageUrl = '';

                        // First check the most common case - uploads/payments directory
                        if(Str::startsWith($pesanan->bukti_pembayaran, 'uploads/payments/')) {
                            $regularImageUrl = asset($pesanan->bukti_pembayaran);
                        }
                        // Then check if the file exists directly in uploads/payments
                        elseif(file_exists(public_path('uploads/payments/' . $fileName))) {
                            $regularImageUrl = asset('uploads/payments/' . $fileName);
                        }
                        // Check other common paths
                        elseif(Str::startsWith($pesanan->bukti_pembayaran, 'storage/')) {
                            $regularImageUrl = asset($pesanan->bukti_pembayaran);
                        }
                        elseif(Str::startsWith($pesanan->bukti_pembayaran, 'payment_proofs/')) {
                            $regularImageUrl = asset('storage/' . $pesanan->bukti_pembayaran);
                        }
                        // Fallback to the original path
                        else {
                            $regularImageUrl = asset($pesanan->bukti_pembayaran);
                        }
                    @endphp

                    <div class="bg-gray-100 p-3 rounded-lg mb-4">
                        <img src="{{ $imageUrl }}" alt="Bukti Pembayaran" class="max-w-full h-auto rounded-lg shadow-md mx-auto"
                            style="max-height: 500px;" id="paymentProofImage" onerror="this.onerror=null;this.src='{{ asset('Images/image-not-found.png') }}';document.getElementById('imageError').classList.remove('hidden')">
                    </div>

                    <div id="imageError" class="hidden mb-4 p-3 bg-yellow-50 text-yellow-800 rounded-lg border border-yellow-200">
                        <p class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Gambar tidak dapat ditampilkan. Coba buka langsung melalui link berikut:
                        </p>
                        <a href="{{ $imageUrl }}" target="_blank" class="text-blue-600 hover:underline mt-1 block">
                            Buka gambar di tab baru
                        </a>

                        <div class="mt-3 pt-3 border-t border-yellow-200 text-xs text-yellow-700">
                            <details>
                                <summary class="cursor-pointer">Informasi Debug</summary>
                                <div class="mt-2 space-y-1 text-left">
                                    <p><strong>Path yang dicoba:</strong></p>
                                    <ul class="list-disc pl-4">
                                        <li>Asli: <code>{{ $pesanan->bukti_pembayaran }}</code></li>
                                        <li>URL Generated: <code>{{ $imageUrl }}</code></li>
                                        <li>Public path: <code>{{ public_path('uploads/payments/' . basename($pesanan->bukti_pembayaran)) }}</code></li>
                                        <li>File exists: <code>{{ file_exists(public_path('uploads/payments/' . basename($pesanan->bukti_pembayaran))) ? 'Ya' : 'Tidak' }}</code></li>
                                    </ul>
                                </div>
                            </details>
                        </div>
                    </div>

                    <div class="mt-4 text-sm text-gray-600 bg-white p-3 rounded-lg border border-gray-200">
                        <div class="flex justify-between mb-2">
                            <p><strong>File:</strong> {{ $fileName }}</p>
                            <p><strong>Upload:</strong> {{ $pesanan->updated_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Path: {{ $pesanan->bukti_pembayaran }}</p>

                        <div class="mt-3 pt-3 border-t border-gray-200 flex justify-center">
                            <a href="{{ $imageUrl }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 rounded-md hover:bg-orange-200 transition-colors text-xs">
                                <i class="fas fa-external-link-alt mr-1.5"></i> Buka di Tab Baru
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-image fa-4x mb-3 opacity-25"></i>
                    <p class="text-lg">Belum ada bukti pembayaran yang diupload.</p>
                </div>
            @endif
        </div>
        <div class="bg-gray-50 px-4 py-3 flex justify-end rounded-b-lg border-t border-gray-200">
            <button type="button" class="px-4 py-2 bg-orange-100 text-orange-700 rounded-md hover:bg-orange-200 transition-colors duration-150 font-medium modal-close focus:outline-none focus:ring-2 focus:ring-orange-500">
                <i class="fas fa-times mr-1"></i> Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal for Order Actions -->
<div id="orderActionModal" class="modal-hidden">
    <div class="modal-dialog">
        <div class="bg-orange-600 px-4 py-3 flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-white" id="orderActionModalLabel">Update Status Pesanan</h3>
            <button type="button" class="text-white hover:text-gray-200 modal-close">
                <i class="fas fa-times"></i>
            </button>
        </div>        <form id="orderActionForm" method="POST">
                                @csrf
                                <!-- No method spoofing, always use POST -->
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <p id="orderActionText" class="text-gray-700 mb-4">Apakah Anda yakin ingin mengubah status pesanan ini?</p>

                <div id="orderCancelReasonContainer" class="mb-4 hidden">
                    <label for="alasan_pembatalan" class="block text-sm font-medium text-gray-700 mb-1">Alasan Pembatalan</label>
                    <textarea id="alasan_pembatalan" name="alasan_pembatalan" rows="3" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Masukkan alasan pembatalan pesanan"></textarea>
                </div>

                <div id="orderShippingContainer" class="mb-4 hidden">
                    <label for="nomor_resi" class="block text-sm font-medium text-gray-700 mb-1">Nomor Resi Pengiriman</label>
                    <input type="text" id="nomor_resi" name="resi" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Masukkan nomor resi pengiriman">
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm" id="orderActionButton">
                    Konfirmasi
                </button>
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm modal-close">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Modal functionality
    const orderActionModal = document.getElementById('orderActionModal');
    const paymentProofModal = document.getElementById('paymentProofModal');

    // Close modal buttons
    document.querySelectorAll('.modal-close').forEach(button => {
        button.addEventListener('click', function() {
            closeAllModals();
        });
    });

    // Close modal when clicking on backdrop
    if (orderActionModal) {
        orderActionModal.addEventListener('click', function(e) {
            if (e.target === orderActionModal) {
                closeAllModals();
            }
        });
    }

    if (paymentProofModal) {
        paymentProofModal.addEventListener('click', function(e) {
            if (e.target === paymentProofModal) {
                closeAllModals();
            }
        });
    }

    // Function to close all modals
    function closeAllModals() {
        if (orderActionModal) {
            orderActionModal.classList.remove('modal-overlay', 'show');
            orderActionModal.classList.add('modal-hidden');
        }
        if (paymentProofModal) {
            paymentProofModal.classList.remove('modal-overlay', 'show');
            paymentProofModal.classList.add('modal-hidden');
        }

        // Remove any body scroll lock
        document.body.style.overflow = '';
    }

    // Function to show modal with enhanced handling and error recovery
    function showModal(modal) {
        if (!modal) {
            console.error('Modal element is null or undefined');
            return;
        }

        // Close other modals first
        closeAllModals();

        try {
            // Force modal to be visible through multiple methods for redundancy
            // Method 1: Use our custom classes
            modal.classList.remove('modal-hidden');
            modal.classList.add('modal-overlay', 'show');

            // Method 2: Direct style manipulation as backup
            modal.style.display = 'flex';
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.right = '0';
            modal.style.bottom = '0';
            modal.style.zIndex = '9999';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            modal.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';

            // Ensure dialog part is visible too
            const dialog = modal.querySelector('.modal-dialog');
            if (dialog) {
                dialog.style.backgroundColor = 'white';
                dialog.style.borderRadius = '8px';
                dialog.style.maxWidth = '600px';
                dialog.style.width = '100%';
                dialog.style.boxShadow = '0 10px 25px -5px rgba(0, 0, 0, 0.3)';
            }

            // Debugging - inspect style properties that might be causing issues
            console.log('Modal visibility: ' + getComputedStyle(modal).display);
            console.log('Modal z-index: ' + getComputedStyle(modal).zIndex);
            console.log('Modal position: ' + getComputedStyle(modal).position);

            // Check if modal is really visible by checking its computed style
            if (getComputedStyle(modal).display === 'none') {
                console.warn('Modal still not visible after CSS class changes. Forcing display style.');
                // Emergency approach - add style tag to document
                const styleTag = document.createElement('style');
                styleTag.textContent = '#' + modal.id + '.show { display: flex !important; z-index: 9999 !important; }';
                document.head.appendChild(styleTag);

                // Try once more
                modal.classList.remove('modal-hidden');
                modal.classList.add('modal-overlay', 'show');
            }

            // Prevent body scroll
            document.body.style.overflow = 'hidden';

            // Focus management for accessibility
            const firstFocusable = modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            if (firstFocusable) {
                setTimeout(() => firstFocusable.focus(), 100);
            }

            // For payment proof modal, check if image loaded correctly
            if (modal.id === 'paymentProofModal') {
                const img = modal.querySelector('img');
                if (img) {
                    img.onload = function() {
                        console.log('Payment proof image loaded successfully.');
                    };
                    img.onerror = function() {
                        console.error('Payment proof image failed to load.');
                        document.getElementById('imageError').classList.remove('hidden');
                    };
                }
            }

            console.log('showModal completed');
        } catch (err) {
            console.error('Error showing modal:', err);
            alert('Terjadi kesalahan saat menampilkan bukti pembayaran. Silakan coba buka gambar langsung di tab baru.');
        }
    }

    // ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });

    // Open payment proof modal - Enhanced handling with better debugging and fallback
    $(document).on('click', '.btnLihatBuktiPembayaran', function(e) {
        e.preventDefault();
        e.stopPropagation();

        console.log('Lihat Bukti Pembayaran button clicked');

        if (paymentProofModal) {
            console.log('Modal element found, showing modal');

            // Make sure the modal is visible with inline style as backup
            paymentProofModal.style.display = 'flex';
            paymentProofModal.style.position = 'fixed';
            paymentProofModal.style.zIndex = '9999';
            paymentProofModal.style.backgroundColor = 'rgba(0, 0, 0, 0.7)';

            showModal(paymentProofModal);

            // Get the payment proof image element
            const imgElement = document.getElementById('paymentProofImage');
            if (imgElement) {
                console.log('Image source:', imgElement.src);

                // Add event listeners for image loading success/failure
                imgElement.onload = function() {
                    console.log('Image loaded successfully');
                    // Hide any error message if it was previously shown
                    const errorElement = document.getElementById('imageError');
                    if (errorElement) errorElement.classList.add('hidden');
                };

                imgElement.onerror = function() {
                    console.error('Image failed to load from', imgElement.src);

                    // Show error message
                    const errorElement = document.getElementById('imageError');
                    if (errorElement) {
                        errorElement.classList.remove('hidden');

                        // Set fallback image
                        this.onerror = null; // prevent infinite loop
                        this.src = '/Images/image-not-found.png';

                        // Get the direct link for opening in new tab
                        const directLink = document.querySelector('#imageError a');
                        if (directLink) {
                            // Make sure it's actually clickable
                            directLink.style.pointerEvents = 'auto';
                            directLink.style.cursor = 'pointer';
                        }
                    }
                };

                // Force reload the image to ensure proper loading
                const originalSrc = imgElement.src;
                imgElement.src = originalSrc + '?t=' + new Date().getTime();
            }
        } else {
            console.error('Payment proof modal not found!');

            // Try to find the modal by tag instead of ID as fallback
            const modalFallback = document.querySelector('#paymentProofModal');
            if (modalFallback) {
                console.log('Found modal via querySelector, showing it');
                modalFallback.style.display = 'flex';
                modalFallback.classList.remove('modal-hidden');
                modalFallback.classList.add('modal-overlay', 'show');
            } else {
                alert('Terjadi kesalahan saat membuka modal. Silakan refresh halaman.');
            }
        }
    });

    // Handle order action buttons
    $('.process-order').click(function(e) {
        e.preventDefault();
               const orderId = $(this).data('id');
        setupOrderActionModal(
            orderId,
            'Proses Pesanan',
            'Apakah Anda yakin ingin memproses pesanan #' + orderId + '?',
            'Proses Pesanan',
            '{{ route("admin.pesanan.process", ["id" => "__id__"]) }}'.replace('__id__', orderId)
        );
    });

    $('.ship-order').click(function(e) {
        e.preventDefault();
        const orderId = $(this).data('id');
        setupOrderActionModal(
            orderId,
            'Kirim Pesanan',
            'Masukkan nomor resi pengiriman untuk pesanan #' + orderId,
            'Kirim Pesanan',
            '{{ route("admin.pesanan.ship", ["id" => "__id__"]) }}'.replace('__id__', orderId),
            'shipping'
        );
    });

    $('.confirm-payment').click(function(e) {
        e.preventDefault();
        console.log('Confirm payment clicked!');
        const orderId = $(this).data('id');
        console.log('Order ID:', orderId);

        if (!orderActionModal) {
            console.error('orderActionModal not found!');
            alert('Modal tidak ditemukan. Silakan refresh halaman.');
            return;
        }

        setupOrderActionModal(
            orderId,
            'Konfirmasi Pembayaran',
            'Apakah Anda yakin ingin mengkonfirmasi pembayaran untuk pesanan #' + orderId + '?',
            'Konfirmasi Pembayaran',
            '{{ route("admin.pesanan.confirm-payment", ["id" => "__id__"]) }}'.replace('__id__', orderId)
        );
    });

    $('.cancel-order').click(function(e) {
        e.preventDefault();
        const orderId = $(this).data('id');
        setupOrderActionModal(
            orderId,
            'Batalkan Pesanan',
            'Apakah Anda yakin ingin membatalkan pesanan #' + orderId + '?',
            'Batalkan Pesanan',
            '{{ route("admin.pesanan.cancel", ["id" => "__id__"]) }}'.replace('__id__', orderId),
            'cancel'
        );
    });

    function setupOrderActionModal(orderId, title, text, buttonText, formAction, type = null) {
        console.log('setupOrderActionModal called with:', {orderId, title, text, buttonText, formAction, type});

        $('#orderActionModalLabel').text(title);
        $('#orderActionText').text(text);
        $('#orderActionButton').text(buttonText);
        $('#orderActionForm').attr('action', formAction);

        // Reset containers
        $('#orderCancelReasonContainer').addClass('hidden');
        $('#orderShippingContainer').addClass('hidden');

        // Show specific container based on type
        if(type === 'cancel') {
            $('#orderCancelReasonContainer').removeClass('hidden');
        } else if(type === 'shipping') {
            $('#orderShippingContainer').removeClass('hidden');
        }

        console.log('About to show modal, orderActionModal element:', orderActionModal);
        // Show modal
        showModal(orderActionModal);

        // Handle form submission
        $('#orderActionForm').off('submit').on('submit', function(e) {
            e.preventDefault();

            const url = $(this).attr('action');
            const formData = new FormData(this);

            // Log the form action for debugging
            console.log('Submitting form to URL:', url);

            // Remove any _method field if it exists (prevent method spoofing)
            const formMethodInput = formData.get('_method');
            if (formMethodInput) {
                console.log('Removing _method field with value:', formMethodInput);
                formData.delete('_method');
            }

            $.ajax({
                url: url,
                type: 'POST', // Always use POST method for AJAX calls
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Show success notification
                        showNotification(response.message || 'Tindakan berhasil dilakukan');

                        // Close modal
                        closeAllModals();

                        // Reload page after a short delay
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showNotification('Terjadi kesalahan: ' + (response.message || 'Unknown error'), 'error');
                    }
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON && xhr.responseJSON.message
                        ? xhr.responseJSON.message
                        : 'Terjadi kesalahan saat memproses permintaan';

                    showNotification(errorMsg, 'error');
                }
            });
        });
    }
});

// Copy tracking number to clipboard
function copyResi() {
    const resiNumber = document.getElementById("resiNumber");
    resiNumber.select();

    try {
        // Modern clipboard API
        if (navigator.clipboard) {
            navigator.clipboard.writeText(resiNumber.value)
                .then(() => {
                    showNotification('Nomor resi berhasil disalin!');
                })
                .catch(err => {
                    console.error('Could not copy text: ', err);
                    // Fall back to the old method
                    document.execCommand('copy');
                    showNotification('Nomor resi berhasil disalin!');
                });
        } else {
            // Fallback for older browsers
            document.execCommand('copy');
            showNotification('Nomor resi berhasil disalin!');
        }
    } catch (err) {
        console.error('Could not copy text: ', err);
        alert('Nomor resi berhasil disalin!');
    }
}

// Show a temporary notification
function showNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');

    // Set style based on type
    if (type === 'error') {
        notification.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-md transition-opacity duration-300';
    } else {
        notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-md transition-opacity duration-300';
    }

    notification.style.zIndex = '9999';
    notification.textContent = message;

    // Add to page
    document.body.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>
@endsection
