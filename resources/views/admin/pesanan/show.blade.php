{{-- resources/views/admin/pesanan/show.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detail Pesanan #' . $pesanan->id_pesanan)

@section('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
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
        background-color: white;
        border-color: #ea580c;
        box-shadow: 0 0 0 3px rgba(234,88,12,0.2);
    }

    .step.pending:before {
        background-color: white;
        border-color: #e5e7eb;
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

    /* Modal system - complete overhaul with improved animations */
    .modal-hidden {
        display: none !important;
    }

    /* Fixed z-index to ensure modals appear above everything */
    /* Adjust z-index if needed to be higher than filament or other elements */
    #orderActionModal, #paymentProofModal {
        z-index: 2050;
        /* Remove opacity: 0 to fix visibility issue */
        /* opacity: 0; */
        transition: opacity 0.2s ease;
    }

    /* When modals are hidden, ensure they are not displayed */
    #orderActionModal.hidden, #paymentProofModal.hidden {
        display: none !important;
    }

    #orderActionModal.show, #paymentProofModal.show {
        opacity: 1 !important;
        display: flex !important;
    }

    /* Dialog animation */
    .modal-content {
        transform: scale(0.95);
        transition: transform 0.2s ease;
    }

    .show .modal-content {
        transform: scale(1);
    }

    /* Make sure modals are clickable */
    /* .modal-backdrop, .modal-container, .modal-content {
        pointer-events: auto !important;
    } */

    /* Improved modal styling */
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(2px);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

    .modal-container {
        display: flex;
        align-items: center;
        justify-content: center;
        position: fixed;
        inset: 0;
        z-index: 50;
    }

    .modal-content {
        background: white;
        border-radius: 8px;
        max-width: 600px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    #orderActionModal.show, #paymentProofModal.show {
        animation: fadeIn 0.2s forwards;
    }

    /* Modal transforms */
    #orderActionModal .modal-content, #paymentProofModal .modal-content {
        transform: scale(0.9);
        transition: transform 0.2s ease-out;
    }

    #orderActionModal.show .modal-content, #paymentProofModal.show .modal-content {
        transform: scale(1);
    }
</style>
@endsection

@section('content')
<div class="container mx-auto">
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
    </div>

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
                        'desc' => 'Pembayaran telah dikonfirmasi. Ikan sedang dalam proses karantina (± 1 minggu) untuk memastikan kualitas terbaik sebelum pengiriman.'
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
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

            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="border-b border-gray-200 px-5 py-4">
                    <h3 class="text-base font-semibold text-orange-600 flex items-center">
                        <i class="fas fa-history mr-2"></i> Log Aktivitas Pesanan
                    </h3>
                </div>
                <div class="p-6">
                    <div class="timeline relative">
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
                                        Pesanan <span class="font-medium">#{{ $pesanan->id_pesanan }}</span> sedang dalam proses karantina ikan (± 1 minggu) untuk memastikan kualitas terbaik sebelum pengiriman.
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
                                    @if($pesanan->nomor_resi)
                                    <div class="mt-3 pt-3 border-t border-purple-200">
                                        <div class="flex items-center justify-between">
                                            <div class="text-sm text-gray-600">
                                                <i class="fas fa-barcode mr-2 text-purple-500"></i>
                                                <span class="font-medium">Nomor Resi:</span> {{ $pesanan->nomor_resi }}
                                            </div>
                                            <a href="https://www.tiki.id/id/tracking"
                                               target="_blank"
                                               class="inline-flex items-center px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-medium rounded transition duration-150">
                                                <i class="fas fa-external-link-alt mr-1"></i>
                                                Cek Resi TIKI
                                            </a>
                                        </div>
                                    </div>
                                    @endif
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

            {{-- Review Section --}}
            @if($pesanan->status_pesanan == 'Selesai')
                @php
                    $reviews = $pesanan->getOrderReviews();
                @endphp

                @if($reviews->isNotEmpty())
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        <div class="border-b border-gray-200 px-5 py-4">
                            <h3 class="text-base font-semibold text-orange-600 flex items-center">
                                <i class="fas fa-star mr-2"></i> Review Pelanggan
                            </h3>
                        </div>
                        <div class="p-6">
                            @foreach($reviews as $review)
                                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                            <div class="ml-3">
                                                <h4 class="font-semibold text-gray-900">{{ $review->user->name ?? 'Pelanggan' }}</h4>
                                                <p class="text-sm text-gray-600">{{ $review->produk->nama_ikan ?? 'Produk' }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="flex items-center mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star text-yellow-400"></i>
                                                    @else
                                                        <i class="far fa-star text-gray-300"></i>
                                                    @endif
                                                @endfor
                                                <span class="ml-2 text-sm font-medium text-gray-700">{{ $review->rating }}/5</span>
                                            </div>
                                            <p class="text-xs text-gray-500">{{ $review->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <p class="text-gray-700 leading-relaxed">{{ $review->komentar }}</p>
                                    </div>

                                    @if($review->foto_review)
                                        @php
                                            $photos = is_array($review->foto_review) ? $review->foto_review : [$review->foto_review];
                                        @endphp
                                        <div class="mb-3">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($photos as $photo)
                                                    <div class="relative">
                                                        @php
                                                            $photoUrl = '';
                                                            if (Str::startsWith($photo, ['http://', 'https://'])) {
                                                                $photoUrl = $photo;
                                                            } elseif (Str::startsWith($photo, 'uploads/')) {
                                                                $photoUrl = asset($photo);
                                                            } elseif (Str::startsWith($photo, 'storage/')) {
                                                                $photoUrl = asset($photo);
                                                            } else {
                                                                $photoUrl = asset('storage/' . $photo);
                                                            }
                                                        @endphp
                                                        <img src="{{ $photoUrl }}"
                                                             alt="Foto Review"
                                                             class="w-16 h-16 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-75 transition-opacity"
                                                             onclick="openPhotoModal('{{ $photoUrl }}')">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if($review->balasan_admin)
                                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded-r-lg">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-reply text-blue-600 mt-1"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-blue-800">Balasan Admin:</p>
                                                    <p class="text-sm text-blue-700 mt-1">{{ $review->balasan_admin }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex justify-end space-x-2 mt-4">
                                        <button onclick="openReplyModalSwal({{ $review->id }}, '{{ addslashes($review->komentar) }}', '{{ addslashes($review->balasan_admin ?? '') }}')"
                                                class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 text-sm font-medium rounded-md hover:bg-blue-200 transition-colors">
                                            <i class="fas fa-reply mr-1"></i>
                                            {{ $review->balasan_admin ? 'Edit Balasan' : 'Balas' }}
                                        </button>
                                        <button onclick="viewReviewDetail({{ $review->id }})"
                                                class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors">
                                            <i class="fas fa-eye mr-1"></i>
                                            Detail
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <div class="lg:col-span-1">
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
                                @if($pesanan->user && $pesanan->user->no_hp) {{-- Changed from 'phone' to 'no_hp' based on profile file --}}
                                    <div class="text-sm text-gray-600">{{ $pesanan->user->no_hp }}</div>
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
                                    <button type="button" class="view-payment-proof inline-flex items-center px-4 py-2 bg-orange-100 text-orange-700 border border-orange-300 text-sm font-medium rounded-md hover:bg-orange-200 transition-colors duration-150" data-image-url="{{ route('admin.pesanan.payment-proof', ['id' => $pesanan->id_pesanan]) }}" data-file-name="{{ basename($pesanan->bukti_pembayaran) }}">
                                        <i class="fas fa-image fa-sm mr-2"></i> Lihat Bukti Pembayaran
                                    </button>
                                    <div class="text-xs text-gray-500 mt-1">Klik untuk melihat bukti pembayaran</div>
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
                                        {{-- Memindahkan onclick ke admin-modal.js --}}
                                        <button type="button" class="copy-resi-button inline-flex items-center px-3 py-1.5 border border-l-0 border-gray-300 bg-gray-50 text-gray-500 rounded-r-md hover:bg-gray-100">
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
                                {{-- Menghapus onclick dan menambahkan kelas untuk JS --}}
                                <button type="button" class="w-full flex justify-center items-center py-2.5 px-4 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition duration-150 confirm-payment" data-id="{{ $pesanan->id_pesanan }}">
                                    <i class="fas fa-check-circle mr-2"></i> Konfirmasi Pembayaran
                                </button>
                                {{-- Menghapus onclick dan menambahkan kelas untuk JS --}}
                                <button type="button" class="w-full flex justify-center items-center py-2.5 px-4 border border-red-500 text-red-500 hover:bg-red-50 text-sm font-medium rounded-md transition duration-150 cancel-order" data-id="{{ $pesanan->id_pesanan }}">
                                    <i class="fas fa-times-circle mr-2"></i> Batalkan Pesanan
                                </button>
                            @else
                                {{-- Menghapus onclick dan menambahkan kelas untuk JS --}}
                                <button type="button" class="w-full flex justify-center items-center py-2.5 px-4 border border-red-500 text-red-500 hover:bg-red-50 text-sm font-medium rounded-md transition duration-150 cancel-order" data-id="{{ $pesanan->id_pesanan }}">
                                    <i class="fas fa-times-circle mr-2"></i> Batalkan Pesanan
                                </button>
                            @endif
                        @elseif($pesanan->status_pesanan == 'Pembayaran Dikonfirmasi')
                            {{-- Menghapus onclick dan menambahkan kelas untuk JS --}}
                            <button type="button" class="w-full flex justify-center items-center py-2.5 px-4 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition duration-150 process-order" data-id="{{ $pesanan->id_pesanan }}">
                                <i class="fas fa-box mr-2"></i> Proses Pesanan
                            </button>
                        @elseif($pesanan->status_pesanan == 'Diproses')
                            {{-- Menghapus onclick dan menambahkan kelas untuk JS --}}
                            <button type="button" class="w-full flex justify-center items-center py-2.5 px-4 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition duration-150 ship-order" data-id="{{ $pesanan->id_pesanan }}">
                                <i class="fas fa-shipping-fast mr-2"></i> Kirim Pesanan
                            </button>
                        @elseif($pesanan->status_pesanan == 'Dikirim')
                            {{-- Menghapus onclick dan menambahkan kelas untuk JS --}}
                            <button type="button" class="w-full flex justify-center items-center py-2.5 px-4 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition duration-150 complete-order" data-id="{{ $pesanan->id_pesanan }}">
                                <i class="fas fa-check mr-2"></i> Tandai Selesai
                            </button>
                        @elseif($pesanan->status_pesanan == 'Selesai' || $pesanan->status_pesanan == 'Dibatalkan')
                            <div class="p-4 bg-gray-100 rounded-md text-center text-gray-600 text-sm">
                                <i class="fas fa-info-circle mr-1"></i> Pesanan sudah selesai atau dibatalkan. Tidak ada tindakan lebih lanjut.
                            </div>
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

    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="border-b border-gray-200 px-5 py-4 bg-gray-50">
            <h3 class="text-base font-semibold text-gray-800 flex items-center">
                <i class="fas fa-bug mr-2"></i> Debug Info (Admin Only)
                <button type="button" id="clearLog" class="ml-auto px-2 py-1 text-xs bg-red-50 text-red-600 rounded hover:bg-red-100">
                    Clear Log
                </button>
            </h3>
        </div>
        <div class="p-5">
            <div id="debugLog" class="text-xs font-mono bg-gray-900 text-green-500 p-3 rounded-lg h-48 overflow-y-auto">
                <div>[LOG] Debug mode active - {{ now() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // SweetAlert2 confirmation for order actions

    // View Payment Proof
    document.querySelectorAll('.view-payment-proof').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const imageUrl = this.dataset.imageUrl;
            const fileName = this.dataset.fileName;

            Swal.fire({
                title: 'Bukti Pembayaran',
                html: `
                    <div class="text-center">
                        <div class="bg-gray-100 p-3 rounded-lg mb-4">
                            <img src="${imageUrl}" alt="Bukti Pembayaran" class="max-w-full h-auto rounded-lg shadow-md mx-auto"
                                 style="max-height: 400px;" onerror="this.onerror=null;this.src='{{ asset('Images/image-not-found.png') }}';this.nextElementSibling.classList.remove('hidden');">
                            <div class="hidden mt-4 p-3 bg-yellow-50 text-yellow-800 rounded-lg border border-yellow-200">
                                <p class="flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Gambar tidak dapat ditampilkan
                                </p>
                                <a href="${imageUrl}" target="_blank" class="text-blue-600 hover:underline mt-1 block">
                                    Buka gambar di tab baru
                                </a>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 bg-white p-3 rounded-lg border border-gray-200">
                            <p><strong>File:</strong> ${fileName}</p>
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <a href="${imageUrl}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-orange-100 text-orange-700 rounded-md hover:bg-orange-200 transition-colors text-xs">
                                    <i class="fas fa-external-link-alt mr-1.5"></i> Buka di Tab Baru
                                </a>
                            </div>
                        </div>
                    </div>
                `,
                showCancelButton: false,
                confirmButtonColor: '#f97316',
                confirmButtonText: 'Tutup',
                width: '600px',
                customClass: {
                    popup: 'rounded-lg',
                    confirmButton: 'rounded-md'
                }
            });
        });
    });

    // Copy Resi Number
    document.querySelectorAll('.copy-resi-button').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const resiInput = document.getElementById('resiNumber');
            if (resiInput) {
                resiInput.select();
                resiInput.setSelectionRange(0, 99999); // For mobile devices

                try {
                    document.execCommand('copy');

                    // Show success toast
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Nomor resi berhasil disalin!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } catch (err) {
                    // Fallback for browsers that don't support execCommand
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Gagal menyalin nomor resi',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            }
        });
    });

    // Confirm Payment
    document.querySelectorAll('.confirm-payment').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const orderId = this.dataset.id;

            Swal.fire({
                title: 'Konfirmasi Pembayaran?',
                text: 'Pembayaran akan dikonfirmasi dan pesanan akan masuk ke proses karantina ikan.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f97316',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Konfirmasi!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-lg',
                    confirmButton: 'rounded-md',
                    cancelButton: 'rounded-md'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading alert
                    Swal.fire({
                        title: 'Mengkonfirmasi Pembayaran...',
                        text: 'Sedang memproses konfirmasi pembayaran.',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    updateOrderStatus(orderId, 'confirm-payment');
                }
            });
        });
    });

    // Process Order
    document.querySelectorAll('.process-order').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const orderId = this.dataset.id;

            Swal.fire({
                title: 'Proses Pesanan?',
                text: 'Pesanan akan diubah ke status "Diproses" dan siap untuk dikirim.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#f97316',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Proses!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-lg',
                    confirmButton: 'rounded-md',
                    cancelButton: 'rounded-md'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading alert
                    Swal.fire({
                        title: 'Memproses Pesanan...',
                        text: 'Sedang mengubah status pesanan ke "Diproses".',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    updateOrderStatus(orderId, 'process');
                }
            });
        });
    });

    // Ship Order
    document.querySelectorAll('.ship-order').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const orderId = this.dataset.id;

            Swal.fire({
                title: 'Kirim Pesanan',
                html: `
                    <div class="text-left">
                        <p class="mb-4 text-center text-gray-700">Pesanan akan ditandai sebagai "Dikirim"</p>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Resi Pengiriman <span class="text-red-500">*</span></label>
                            <input type="text" id="swal-resi-input" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Contoh: JNE123456789" autocomplete="off">
                            <p class="text-xs text-gray-500 mt-1">Masukkan nomor resi dari kurir pengiriman</p>
                        </div>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded">
                            <p class="text-sm text-blue-700">
                                <i class="fas fa-info-circle mr-1"></i>
                                Pastikan produk sudah dikemas dan siap dikirim sebelum melanjutkan.
                            </p>
                        </div>
                    </div>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#f97316',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Kirim Pesanan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusConfirm: false,
                customClass: {
                    popup: 'rounded-lg',
                    confirmButton: 'rounded-md',
                    cancelButton: 'rounded-md'
                },
                didOpen: () => {
                    // Focus pada input nomor resi
                    document.getElementById('swal-resi-input').focus();
                },
                preConfirm: () => {
                    const resi = document.getElementById('swal-resi-input').value.trim();

                    if (!resi) {
                        Swal.showValidationMessage('Nomor resi harus diisi!');
                        return false;
                    }

                    if (resi.length < 5) {
                        Swal.showValidationMessage('Nomor resi terlalu pendek! Minimal 5 karakter.');
                        return false;
                    }

                    if (resi.length > 50) {
                        Swal.showValidationMessage('Nomor resi terlalu panjang! Maksimal 50 karakter.');
                        return false;
                    }

                    return resi;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading alert
                    Swal.fire({
                        title: 'Mengirim Pesanan...',
                        text: 'Sedang memproses pengiriman pesanan dengan nomor resi: ' + result.value,
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    updateOrderStatusWithResi(orderId, 'ship', result.value);
                }
            });
        });
    });

    // Complete Order
    document.querySelectorAll('.complete-order').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const orderId = this.dataset.id;

            Swal.fire({
                title: 'Selesaikan Pesanan?',
                text: 'Pesanan akan ditandai sebagai "Selesai". Tindakan ini tidak dapat dibatalkan.',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-lg',
                    confirmButton: 'rounded-md',
                    cancelButton: 'rounded-md'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading alert
                    Swal.fire({
                        title: 'Menyelesaikan Pesanan...',
                        text: 'Sedang menandai pesanan sebagai selesai.',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    updateOrderStatus(orderId, 'complete');
                }
            });
        });
    });

    // Cancel Order
    document.querySelectorAll('.cancel-order').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const orderId = this.dataset.id;

            Swal.fire({
                title: 'Batalkan Pesanan?',
                html: `
                    <div class="text-center mb-4">
                        <p class="text-red-600 font-semibold mb-2">⚠️ PERINGATAN ⚠️</p>
                        <p class="mb-4">Pesanan akan dibatalkan dan <strong>tidak dapat dikembalikan</strong>!</p>
                    </div>
                    <div class="text-left">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pembatalan *</label>
                        <textarea id="swal-cancel-reason" rows="3" required
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-red-500 focus:border-red-500"
                                  placeholder="Masukkan alasan pembatalan pesanan (minimal 10 karakter)..."></textarea>
                    </div>
                `,
                icon: 'warning',
                width: '600px',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Tidak',
                reverseButtons: true,
                focusConfirm: false,
                customClass: {
                    popup: 'rounded-lg',
                    confirmButton: 'rounded-md',
                    cancelButton: 'rounded-md',
                    content: 'text-left'
                },
                didOpen: () => {
                    // Focus pada textarea
                    document.getElementById('swal-cancel-reason').focus();
                },
                preConfirm: () => {
                    const reason = document.getElementById('swal-cancel-reason').value.trim();

                    if (!reason) {
                        Swal.showValidationMessage('Alasan pembatalan harus diisi!');
                        return false;
                    }

                    if (reason.length < 10) {
                        Swal.showValidationMessage('Alasan pembatalan minimal 10 karakter!');
                        return false;
                    }

                    if (reason.length > 500) {
                        Swal.showValidationMessage('Alasan pembatalan maksimal 500 karakter!');
                        return false;
                    }

                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading alert
                    Swal.fire({
                        title: 'Membatalkan Pesanan...',
                        text: 'Sedang memproses pembatalan pesanan.',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    updateOrderStatusWithReason(orderId, 'cancel', result.value);
                }
            });
        });
    });

    // Helper function to update order status
    function updateOrderStatus(orderId, action) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/pesanan/${orderId}/${action}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }

    // Helper function to update order status with additional data (like reason)
    function updateOrderStatusWithReason(orderId, action, reason) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/pesanan/${orderId}/${action}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'alasan_pembatalan';
        reasonInput.value = reason;

        form.appendChild(csrfToken);
        form.appendChild(reasonInput);
        document.body.appendChild(form);
        form.submit();
    }

    // Helper function to update order status with shipping number
    function updateOrderStatusWithResi(orderId, action, resi) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/pesanan/${orderId}/${action}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const resiInput = document.createElement('input');
        resiInput.type = 'hidden';
        resiInput.name = 'resi';
        resiInput.value = resi;

        form.appendChild(csrfToken);
        form.appendChild(resiInput);
        document.body.appendChild(form);
        form.submit();
    }

    // Photo modal for review images
    function openPhotoModal(photoUrl) {
        Swal.fire({
            html: `<img src="${photoUrl}" alt="Foto Review" class="max-w-full h-auto rounded-lg shadow-md">`,
            showConfirmButton: false,
            showCloseButton: true,
            width: '600px',
            customClass: {
                popup: 'rounded-lg',
                closeButton: 'text-gray-500 hover:text-gray-700'
            }
        });
    }

    // View review detail
    function viewReviewDetail(reviewId) {
        // Show loading while fetching data
        Swal.fire({
            title: 'Memuat Detail Review...',
            text: 'Sedang mengambil informasi review.',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Fetch review details
        fetch(`/admin/reviews/${reviewId}/detail`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showReviewDetailModal(data.review);
            } else {
                throw new Error(data.message || 'Gagal memuat detail review');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Gagal memuat detail review: ' + error.message,
                icon: 'error',
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'rounded-lg',
                    confirmButton: 'rounded-md'
                }
            });
        });
    }

    // Show detailed review modal
    function showReviewDetailModal(review) {
        // Build star rating HTML
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= review.rating) {
                starsHtml += '<i class="fas fa-star text-yellow-400"></i>';
            } else {
                starsHtml += '<i class="far fa-star text-gray-300"></i>';
            }
        }

        // Build photo gallery HTML
        let photosHtml = '';
        if (review.photos && review.photos.length > 0) {
            photosHtml = `
                <div class="mb-4">
                    <h5 class="font-medium text-gray-800 mb-3">Foto Review (${review.photos.length})</h5>
                    <div class="grid grid-cols-3 gap-2">
                        ${review.photos.map((photo, index) => `
                            <div class="relative group cursor-pointer" onclick="openPhotoGallery(${review.id}, ${index})">
                                <img src="${photo}" alt="Review Photo ${index + 1}"
                                     class="w-full h-20 object-cover rounded-lg border border-gray-200 group-hover:opacity-75 transition-opacity">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 rounded-lg transition-all duration-200 flex items-center justify-center">
                                    <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        // Build admin reply HTML
        let adminReplyHtml = '';
        if (review.admin_reply) {
            adminReplyHtml = `
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-reply text-blue-600 mr-2"></i>
                        <span class="font-medium text-blue-800">Balasan Admin</span>
                        <span class="text-sm text-blue-600 ml-auto">${review.admin_reply_date || ''}</span>
                    </div>
                    <p class="text-blue-800">${review.admin_reply}</p>
                </div>
            `;
        }

        // Build interaction stats HTML
        let interactionHtml = '';
        if (review.helpful_count || review.not_helpful_count) {
            interactionHtml = `
                <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                    <h5 class="font-medium text-gray-800 mb-2">Interaksi</h5>
                    <div class="flex space-x-4 text-sm text-gray-600">
                        <span><i class="fas fa-thumbs-up text-green-500 mr-1"></i>${review.helpful_count || 0} Helpful</span>
                        <span><i class="fas fa-thumbs-down text-red-500 mr-1"></i>${review.not_helpful_count || 0} Not Helpful</span>
                    </div>
                </div>
            `;
        }

        // Status badge
        let statusBadge = '';
        if (review.status === 'approved') {
            statusBadge = '<span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Disetujui</span>';
        } else if (review.status === 'pending') {
            statusBadge = '<span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Menunggu</span>';
        } else if (review.status === 'rejected') {
            statusBadge = '<span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">Ditolak</span>';
        }

        Swal.fire({
            title: '<div class="text-left"><h3 class="text-xl font-bold text-gray-800">Detail Review</h3></div>',
            html: `
                <div class="text-left space-y-4">
                    <!-- Review Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                <span class="text-orange-600 font-bold">${review.user_name ? review.user_name.charAt(0).toUpperCase() : 'U'}</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">${review.user_name || 'Pengguna'}</h4>
                                <p class="text-sm text-gray-500">${review.created_at || ''}</p>
                                ${review.is_verified ? '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1"><i class="fas fa-check-circle mr-1"></i>Terverifikasi</span>' : ''}
                            </div>
                        </div>
                        ${statusBadge}
                    </div>

                    <!-- Product Info -->
                    <div class="p-3 bg-gray-50 rounded-lg border">
                        <div class="flex items-center space-x-3">
                            <img src="${review.product_image || '/images/no-image.png'}"
                                 alt="${review.product_name || 'Produk'}"
                                 class="w-16 h-16 object-cover rounded-lg border border-gray-200"
                                 onerror="this.src='/images/no-image.png'">
                            <div>
                                <h5 class="font-medium text-gray-900">${review.product_name || 'Produk tidak tersedia'}</h5>
                                <p class="text-sm text-gray-600">Pesanan: #${review.order_id || 'N/A'}</p>
                                ${review.product_price ? `<p class="text-sm font-medium text-orange-600">Rp ${new Intl.NumberFormat('id-ID').format(review.product_price)}</p>` : ''}
                            </div>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="flex items-center space-x-2">
                        <div class="flex">${starsHtml}</div>
                        <span class="font-semibold text-gray-900">${review.rating}/5</span>
                    </div>

                    <!-- Review Content -->
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h5 class="font-medium text-gray-800 mb-2">Review:</h5>
                        <p class="text-gray-700 leading-relaxed">${review.comment || 'Tidak ada komentar'}</p>
                    </div>

                    ${photosHtml}
                    ${adminReplyHtml}
                    ${interactionHtml}
                </div>
            `,
            showCancelButton: true,
            cancelButtonText: 'Tutup',
            confirmButtonText: review.admin_reply ? 'Edit Balasan' : 'Balas Review',
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
            width: '800px',
            customClass: {
                popup: 'rounded-lg',
                confirmButton: 'rounded-md',
                cancelButton: 'rounded-md',
                htmlContainer: 'swal2-html-container-custom'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                openReplyModalSwal(review.id, review.comment, review.admin_reply || '');
            }
        });
    }

    // Photo gallery modal
    function openPhotoGallery(reviewId, photoIndex = 0) {
        // Fetch review photos
        fetch(`/admin/reviews/${reviewId}/photos`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.photos && data.photos.length > 0) {
                showPhotoGalleryModal(data.photos, photoIndex);
            } else {
                Swal.fire({
                    title: 'Tidak Ada Foto',
                    text: 'Tidak ada foto yang tersedia untuk review ini.',
                    icon: 'info',
                    confirmButtonColor: '#f97316',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Gagal memuat foto review.',
                icon: 'error',
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'OK'
            });
        });
    }

    // Show photo gallery modal
    function showPhotoGalleryModal(photos, currentIndex = 0) {
        let currentPhotoIndex = currentIndex;

        function updatePhotoModal() {
            const photo = photos[currentPhotoIndex];
            const photoCounter = `${currentPhotoIndex + 1} dari ${photos.length}`;

            const prevDisabled = currentPhotoIndex === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-200';
            const nextDisabled = currentPhotoIndex === photos.length - 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-200';

            return `
                <div class="relative">
                    <!-- Photo Counter -->
                    <div class="absolute top-4 right-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded-full text-sm z-10">
                        ${photoCounter}
                    </div>

                    <!-- Main Photo -->
                    <div class="flex justify-center items-center bg-gray-100 rounded-lg" style="min-height: 400px;">
                        <img src="${photo}" alt="Review Photo ${currentPhotoIndex + 1}"
                             class="max-w-full max-h-96 object-contain rounded-lg shadow-lg"
                             onerror="this.src='/images/no-image.png'">
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between items-center mt-4">
                        <button onclick="previousPhoto()"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg transition-colors ${prevDisabled}"
                                ${currentPhotoIndex === 0 ? 'disabled' : ''}>
                            <i class="fas fa-chevron-left mr-2"></i>Sebelumnya
                        </button>

                        <div class="flex space-x-2">
                            ${photos.map((_, index) => `
                                <button onclick="goToPhoto(${index})"
                                        class="w-3 h-3 rounded-full transition-colors ${index === currentPhotoIndex ? 'bg-orange-500' : 'bg-gray-300 hover:bg-gray-400'}">
                                </button>
                            `).join('')}
                        </div>

                        <button onclick="nextPhoto()"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg transition-colors ${nextDisabled}"
                                ${currentPhotoIndex === photos.length - 1 ? 'disabled' : ''}>
                            Selanjutnya<i class="fas fa-chevron-right ml-2"></i>
                        </button>
                    </div>

                    <!-- Thumbnail Strip -->
                    ${photos.length > 1 ? `
                        <div class="mt-4 overflow-x-auto">
                            <div class="flex space-x-2 pb-2">
                                ${photos.map((photo, index) => `
                                    <button onclick="goToPhoto(${index})"
                                            class="flex-shrink-0 w-16 h-16 border-2 rounded-lg overflow-hidden transition-all ${index === currentPhotoIndex ? 'border-orange-500' : 'border-gray-300 hover:border-gray-400'}">
                                        <img src="${photo}" alt="Thumbnail ${index + 1}"
                                             class="w-full h-full object-cover">
                                    </button>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        // Show initial modal
        Swal.fire({
            title: '<div class="text-left"><h3 class="text-xl font-bold text-gray-800">Galeri Foto Review</h3></div>',
            html: updatePhotoModal(),
            showCloseButton: true,
            showConfirmButton: false,
            width: '800px',
            customClass: {
                popup: 'rounded-lg',
                htmlContainer: 'swal2-html-container-custom'
            },
            didOpen: () => {
                // Add global functions for navigation
                window.previousPhoto = () => {
                    if (currentPhotoIndex > 0) {
                        currentPhotoIndex--;
                        Swal.update({
                            html: updatePhotoModal()
                        });
                    }
                };

                window.nextPhoto = () => {
                    if (currentPhotoIndex < photos.length - 1) {
                        currentPhotoIndex++;
                        Swal.update({
                            html: updatePhotoModal()
                        });
                    }
                };

                window.goToPhoto = (index) => {
                    currentPhotoIndex = index;
                    Swal.update({
                        html: updatePhotoModal()
                    });
                };

                // Keyboard navigation
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'ArrowLeft') {
                        window.previousPhoto();
                    } else if (e.key === 'ArrowRight') {
                        window.nextPhoto();
                    }
                });
            },
            willClose: () => {
                // Clean up global functions
                delete window.previousPhoto;
                delete window.nextPhoto;
                delete window.goToPhoto;
            }
        });
    }

    // Open reply modal with SweetAlert
    function openReplyModalSwal(reviewId, reviewText, currentReply = '') {
        const isEdit = currentReply.length > 0;

        Swal.fire({
            title: isEdit ? 'Edit Balasan Admin' : 'Balas Review',
            html: `
                <div class="text-left">
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg border">
                        <h5 class="font-medium text-gray-800 mb-2">Review Pelanggan:</h5>
                        <p class="text-sm text-gray-600">"${reviewText}"</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            ${isEdit ? 'Edit Balasan' : 'Balasan Admin'} <span class="text-red-500">*</span>
                        </label>
                        <textarea id="swal-reply-input"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  rows="4"
                                  placeholder="Tulis balasan untuk review ini...">${currentReply}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 500 karakter</p>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: isEdit ? 'Update Balasan' : 'Kirim Balasan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            focusConfirm: false,
            customClass: {
                popup: 'rounded-lg',
                confirmButton: 'rounded-md',
                cancelButton: 'rounded-md'
            },
            didOpen: () => {
                document.getElementById('swal-reply-input').focus();
            },
            preConfirm: () => {
                const reply = document.getElementById('swal-reply-input').value.trim();

                if (!reply) {
                    Swal.showValidationMessage('Balasan tidak boleh kosong!');
                    return false;
                }

                if (reply.length > 500) {
                    Swal.showValidationMessage('Balasan terlalu panjang! Maksimal 500 karakter.');
                    return false;
                }

                return reply;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                submitReply(reviewId, result.value, isEdit);
            }
        });
    }

    // Submit reply function
    function submitReply(reviewId, reply, isEdit = false) {
        // Show loading
        Swal.fire({
            title: isEdit ? 'Mengupdate Balasan...' : 'Mengirim Balasan...',
            text: 'Sedang memproses balasan Anda.',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Create form data
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('balasan_admin', reply);

        // Submit via fetch
        fetch(`/admin/reviews/${reviewId}/reply`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Berhasil!',
                    text: isEdit ? 'Balasan berhasil diupdate!' : 'Balasan berhasil dikirim!',
                    icon: 'success',
                    confirmButtonColor: '#059669',
                    confirmButtonText: 'OK',
                    customClass: {
                        popup: 'rounded-lg',
                        confirmButton: 'rounded-md'
                    }
                }).then(() => {
                    // Reload page to show updated reply
                    window.location.reload();
                });
            } else {
                throw new Error(data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat memproses balasan: ' + error.message,
                icon: 'error',
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'rounded-lg',
                    confirmButton: 'rounded-md'
                }
            });
        });
    }

    // Helper function to update order status with resi
    function updateOrderStatusWithResi(orderId, action, resi) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/pesanan/${orderId}/${action}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const resiInput = document.createElement('input');
        resiInput.type = 'hidden';
        resiInput.name = 'resi';
        resiInput.value = resi;

        form.appendChild(csrfToken);
        form.appendChild(resiInput);
        document.body.appendChild(form);
        form.submit();
    }
});
</script>
@endpush
