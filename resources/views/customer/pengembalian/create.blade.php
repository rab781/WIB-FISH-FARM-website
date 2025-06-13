@extends('layouts.app')

@section('title', 'Ajukan Refund')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css">
<style>
    :root {
        --primary-color: #f97316;
        --primary-dark: #ea580c;
        --primary-light: #fed7aa;
        --secondary-color: #64748b;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --border-color: #e2e8f0;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --bg-light: #f8fafc;
        --shadow-light: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-medium: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-large: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .gradient-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        position: relative;
        overflow: hidden;
    }

    .gradient-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='12' cy='12' r='3'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        animation: float 20s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .modern-card {
        background: white;
        border-radius: 16px;
        box-shadow: var(--shadow-light);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .modern-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modern-card:hover {
        box-shadow: var(--shadow-large);
        transform: translateY(-2px);
    }

    .modern-card:hover::before {
        opacity: 1;
    }

    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        letter-spacing: 0.025em;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background-color: #ffffff;
        position: relative;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        transform: translateY(-1px);
    }

    .form-input:hover {
        border-color: var(--primary-light);
    }

    /* Upload Area Styles */
    .upload-area {
        border: 2px dashed var(--border-color);
        border-radius: 16px;
        padding: 3rem 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        position: relative;
        overflow: hidden;
        user-select: none;
    }

    .upload-area:hover {
        border-color: var(--primary-color) !important;
        background-color: #fff7ed !important;
        cursor: pointer !important;
    }

    .upload-area:active {
        transform: scale(0.98);
        background-color: #fed7aa !important;
    }

    .upload-area.dragover {
        border-color: var(--primary-color) !important;
        background-color: #fff7ed !important;
        box-shadow: 0 0 20px rgba(249, 115, 22, 0.2);
    }

    .preview-image {
        position: relative;
        display: inline-block;
        margin: 0.5rem;
        transition: all 0.3s ease;
    }

    .preview-image:hover {
        transform: scale(1.05);
    }

    .preview-image img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        border: 3px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .preview-image:hover img {
        border-color: var(--primary-color);
        box-shadow: var(--shadow-medium);
    }

    .preview-image .remove-btn {
        position: absolute;
        top: -8px;
        right: -8px;
        background: linear-gradient(135deg, var(--danger-color), #dc2626);
        color: white;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-medium);
    }

    .preview-image .remove-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .refund-method-option {
        position: relative;
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border: 2px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        overflow: hidden;
    }

    .refund-method-option::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(249, 115, 22, 0.05) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .refund-method-option:hover {
        border-color: var(--primary-color);
        background: linear-gradient(145deg, #fff7ed, #ffffff);
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    .refund-method-option:hover::before {
        opacity: 1;
    }

    .refund-method-option .radio-indicator {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 20px;
        height: 20px;
        border: 2px solid var(--border-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .refund-method-option .radio-indicator div {
        width: 12px;
        height: 12px;
        background: var(--primary-color);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .radio-option {
        position: relative;
        cursor: pointer;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        transition: all 0.3s ease;
        background: #ffffff;
        overflow: hidden;
    }

    .radio-option:hover {
        border-color: #f97316;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.15);
    }

    .radio-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .refund-method-option {
        position: relative;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        overflow: hidden;
    }

    .refund-method-option:hover {
        border-color: #f97316;
        background: #fff7ed;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.15);
    }

    .refund-method-option .radio-indicator {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .refund-method-option .radio-indicator div {
        width: 10px;
        height: 10px;
        background: #f97316;
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        font-size: 0.875rem;
        letter-spacing: 0.025em;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-large);
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    .btn-secondary {
        background: linear-gradient(145deg, #f1f5f9, #e2e8f0);
        color: var(--text-primary);
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid var(--border-color);
        cursor: pointer;
        font-size: 0.875rem;
        letter-spacing: 0.025em;
    }

    .btn-secondary:hover {
        background: linear-gradient(145deg, #e2e8f0, #cbd5e1);
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
        z-index: 9999;
        transition: all 0.3s ease;
    }

    .loading-overlay.active {
        display: flex !important;
        justify-content: center;
        align-items: center;
    }

    .loading-content {
        background: white;
        padding: 2rem 3rem;
        border-radius: 20px;
        box-shadow: var(--shadow-large);
        text-align: center;
        max-width: 300px;
        animation: slideUp 0.5s ease;
    }

    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 4px solid #f3f4f6;
        border-radius: 50%;
        border-top: 4px solid var(--primary-color);
        border-right: 4px solid var(--primary-color);
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
        filter: drop-shadow(0 0 20px rgba(249, 115, 22, 0.3));
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .form-field-invalid {
        animation: shake 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
        border-color: var(--danger-color) !important;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15);
    }

    @keyframes shake {
        10%, 90% { transform: translate3d(-1px, 0, 0); }
        20%, 80% { transform: translate3d(2px, 0, 0); }
        30%, 50%, 70% { transform: translate3d(-3px, 0, 0); }
        40%, 60% { transform: translate3d(3px, 0, 0); }
    }

    .section-divider {
        height: 2px;
        background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
        border-radius: 2px;
        margin: 2rem 0;
        position: relative;
    }

    .section-divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 12px;
        height: 12px;
        background: white;
        border: 3px solid var(--primary-color);
        border-radius: 50%;
    }

    .info-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .product-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        box-shadow: var(--shadow-medium);
    }

    .product-image-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(249, 115, 22, 0.1), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-image-container:hover::before {
        opacity: 1;
    }

    .status-indicator {
        position: relative;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .animate-pulse-soft {
        animation: pulseSoft 2s infinite;
    }

    @keyframes pulseSoft {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
</style>
@endpush

@section('content')
<!-- CSRF Meta Tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Loading overlay removed - using SweetAlert for user feedback instead -->

<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-50">
    <!-- Modern Header with Gradient -->
    <div class="gradient-header py-12 mb-8">
        <div class="container mx-auto px-4">
            <div class="text-center relative z-10">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mb-6 backdrop-blur-sm">
                    <i class="fas fa-undo text-3xl text-white"></i>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">Ajukan Refund</h1>
                <div class="flex items-center justify-center space-x-2 text-orange-100">
                    <span>Pesanan:</span>
                    <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full font-semibold backdrop-blur-sm">
                        {{ $pesanan->nomor_pesanan }}
                    </span>
                </div>
                <div class="mt-6 w-24 h-1 bg-white bg-opacity-50 mx-auto rounded-full"></div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 pb-12">
        <!-- Order Information Card -->
        <div class="modern-card p-8 mb-8 fade-in">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-shopping-bag text-white text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Informasi Pesanan</h3>
            </div>

            <!-- Professional Order Information Card -->
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm mb-6">
                <!-- Header with Order ID and Status -->
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="text-lg font-bold">Pesanan #{{ $pesanan->id_pesanan }}</h4>
                            <p class="text-orange-100 text-sm">{{ $pesanan->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                        <div class="text-right">
                            @php
                                $statusBadge = [
                                    'Menunggu Pembayaran' => 'bg-yellow-100 text-yellow-800',
                                    'Pembayaran Dikonfirmasi' => 'bg-blue-100 text-blue-800',
                                    'Diproses' => 'bg-indigo-100 text-indigo-800',
                                    'Dikirim' => 'bg-purple-100 text-purple-800',
                                    'Selesai' => 'bg-green-100 text-green-800',
                                    'Dibatalkan' => 'bg-red-100 text-red-800',
                                ][$pesanan->status_pesanan] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $statusBadge }}">
                                {{ $pesanan->status_pesanan }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="p-6">
                    @foreach($pesanan->detailPesanan as $detail)
                        <div class="flex items-start space-x-4 {{ !$loop->last ? 'border-b border-gray-100 pb-4 mb-4' : '' }}">
                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                @if($detail->produk && $detail->produk->gambar)
                                    <img src="{{ asset($detail->produk->gambar) }}"
                                         alt="{{ $detail->produk->nama_ikan }}"
                                         class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                @else
                                    <div class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-fish text-2xl text-gray-400"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="flex-grow">
                                <h5 class="font-semibold text-gray-900 mb-1">{{ $detail->produk->nama_ikan ?? 'Produk' }}</h5>
                                <p class="text-sm text-gray-600 mb-2">{{ $detail->produk->deskripsi ?? '' }}</p>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4 text-sm">
                                        <span class="flex items-center text-gray-600">
                                            <i class="fas fa-fish text-orange-500 mr-1"></i>
                                            {{ $detail->kuantitas }} ekor
                                        </span>
                                        <span class="flex items-center text-gray-600">
                                            <i class="fas fa-tag text-orange-500 mr-1"></i>
                                            Rp {{ number_format($detail->harga, 0, ',', '.') }}/ekor
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Order Summary -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal ({{ $pesanan->detailPesanan->sum('kuantitas') }} produk)</span>
                                <span class="font-medium">Rp {{ number_format($pesanan->detailPesanan->sum('subtotal'), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Ongkos Kirim</span>
                                <span class="font-medium">Rp {{ number_format($pesanan->ongkir_biaya ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                                <span class="font-semibold text-gray-900">Total Pembayaran</span>
                                <span class="font-bold text-lg text-orange-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Refund Form -->
        <div class="modern-card p-8 fade-in">
            <div class="flex items-center mb-8">
                <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-file-alt text-white text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Form Permintaan Refund</h3>
            </div>

            <form id="refundForm" action="{{ route('pengembalian.store', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_pesanan" value="{{ $pesanan->id_pesanan }}">

                <!-- Reason Selection -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                        Alasan Refund <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="radio-option p-6 hover:shadow-lg transition-all duration-300">
                            <input type="radio" name="jenis_keluhan" value="Barang Rusak" required class="sr-only">
                            <div class="text-center">
                                <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-3"></i>
                                <div class="font-bold text-gray-900 mb-2">Produk Rusak/Cacat</div>
                                <div class="text-sm text-gray-600">Ikan yang diterima dalam kondisi rusak atau cacat</div>
                            </div>
                        </label>

                        <label class="radio-option p-6 hover:shadow-lg transition-all duration-300">
                            <input type="radio" name="jenis_keluhan" value="Barang Tidak Sesuai" required class="sr-only">
                            <div class="text-center">
                                <i class="fas fa-times-circle text-3xl text-orange-500 mb-3"></i>
                                <div class="font-bold text-gray-900 mb-2">Barang Tidak Sesuai</div>
                                <div class="text-sm text-gray-600">Ikan yang diterima tidak sesuai pesanan</div>
                            </div>
                        </label>

                        <label class="radio-option p-6 hover:shadow-lg transition-all duration-300">
                            <input type="radio" name="jenis_keluhan" value="Barang Kurang" required class="sr-only">
                            <div class="text-center">
                                <i class="fas fa-minus-circle text-3xl text-yellow-500 mb-3"></i>
                                <div class="font-bold text-gray-900 mb-2">Barang Kurang</div>
                                <div class="text-sm text-gray-600">Jumlah ikan yang diterima kurang dari pesanan</div>
                            </div>
                        </label>

                        <label class="radio-option p-6 hover:shadow-lg transition-all duration-300">
                            <input type="radio" name="jenis_keluhan" value="Kualitas Buruk" required class="sr-only">
                            <div class="text-center">
                                <i class="fas fa-thumbs-down text-3xl text-purple-500 mb-3"></i>
                                <div class="font-bold text-gray-900 mb-2">Kualitas Buruk</div>
                                <div class="text-sm text-gray-600">Kualitas ikan tidak sesuai ekspektasi</div>
                            </div>
                        </label>

                        <label class="radio-option p-6 hover:shadow-lg transition-all duration-300 md:col-span-2">
                            <input type="radio" name="jenis_keluhan" value="Lainnya" required class="sr-only">
                            <div class="text-center">
                                <i class="fas fa-ellipsis-h text-3xl text-gray-500 mb-3"></i>
                                <div class="font-bold text-gray-900 mb-2">Lainnya</div>
                            <div class="text-sm text-gray-600">Alasan lain (akan dijelaskan di deskripsi)</div>
                        </div>
                    </label>
                </div>
                @error('jenis_keluhan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div class="mb-6">
                <label for="jumlah_klaim" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Refund <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input type="number" id="amount" name="jumlah_klaim" value="{{ old('jumlah_klaim', $pesanan->total_harga) }}"
                           min="1" max="{{ $pesanan->total_harga }}" required
                           class="pl-10 w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm hover:border-orange-300">
                </div>
                <div class="mt-1 text-sm text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1 text-orange-500"></i>
                    Maksimal: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                </div>
                @error('jumlah_klaim')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="deskripsi_masalah" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Masalah <span class="text-red-500">*</span></label>
                <textarea id="description" name="deskripsi_masalah" rows="4" required
                          placeholder="Jelaskan secara detail masalah yang Anda alami dengan produk ini..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm hover:border-orange-300">{{ old('deskripsi_masalah') }}</textarea>
                <div class="mt-1 text-sm text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1 text-orange-500"></i>
                    Minimum 20 karakter. Semakin detail, semakin baik untuk proses review.
                </div>
                @error('deskripsi_masalah')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Evidence Photos with Working Upload -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-camera text-orange-500 mr-2"></i>
                    Bukti Foto (Opsional)
                </label>                <!-- Upload Area -->
                <div class="upload-area border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-orange-400 transition-all cursor-pointer bg-gray-50 hover:bg-orange-50"
                     id="uploadArea">
                    <input type="file" id="photoInput" name="foto_bukti[]" multiple accept="image/jpeg,image/png,image/jpg" class="hidden">
                    <div class="upload-content" id="uploadContent">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-700 mb-2">Klik untuk upload foto bukti</p>
                        <p class="text-sm text-gray-500 mb-1">Atau seret dan letakkan foto di sini</p>
                        <p class="text-xs text-gray-400">Maksimal 5 foto, masing-masing maksimal 2MB</p>
                        <p class="text-xs text-gray-400">Format: JPG, PNG, JPEG</p>
                    </div>
                </div>

                <!-- Photo Previews -->
                <div id="photoPreview" class="mt-4" style="display: none;">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-images text-orange-500 mr-2"></i>
                            Foto yang Dipilih (<span id="photoCount">0</span>)
                        </h4>
                        <div id="photoGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3"></div>
                    </div>
                </div>

                <div class="mt-2 text-sm text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1 text-orange-500"></i>
                    Foto bukti akan membantu mempercepat proses review refund Anda
                </div>
                @error('foto_bukti')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('foto_bukti.*')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Refund Method -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Metode Refund Pilihan <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="refund-method-option relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-all hover:border-orange-500 hover:shadow-sm">
                        <input type="radio" name="metode_refund" value="bank_transfer" required class="sr-only">
                        <div class="text-center w-full">
                            <i class="fas fa-university text-3xl text-blue-500 mb-2"></i>
                            <div class="font-medium text-gray-900">Transfer Bank</div>
                        </div>
                        <div class="radio-indicator absolute top-2 right-2 w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center">
                            <div class="w-3 h-3 bg-orange-500 rounded-full opacity-0 transition-opacity"></div>
                        </div>
                    </label>

                    <label class="refund-method-option relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-all hover:border-orange-500 hover:shadow-sm">
                        <input type="radio" name="metode_refund" value="e_wallet" required class="sr-only">
                        <div class="text-center w-full">
                            <i class="fas fa-mobile-alt text-3xl text-green-500 mb-2"></i>
                            <div class="font-medium text-gray-900">E-Wallet</div>
                        </div>
                        <div class="radio-indicator absolute top-2 right-2 w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center">
                            <div class="w-3 h-3 bg-orange-500 rounded-full opacity-0 transition-opacity"></div>
                        </div>
                    </label>
                </div>
                <div class="mt-2 text-sm text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1 text-orange-500"></i>
                    Metode ini adalah preferensi Anda. Tim kami akan mengonfirmasi metode final saat memproses refund.
                </div>
                @error('metode_refund')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bank Account Information -->
            <div class="form-group" id="bankForm" style="display: none;">
                <label class="form-label">
                    <i class="fas fa-university text-orange-500 mr-2"></i>
                    Informasi Rekening Bank <span class="text-red-500">*</span>
                </label>
                <div class="space-y-4">
                    <div>
                        <label for="nama_bank" class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                        <input type="text" id="nama_bank" name="nama_bank" value="{{ old('nama_bank') }}"
                               placeholder="Contoh: Bank BCA, Bank Mandiri, Bank BRI"
                               class="form-input">
                        @error('nama_bank')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nomor_rekening" class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                        <input type="text" id="nomor_rekening" name="nomor_rekening" value="{{ old('nomor_rekening') }}"
                               placeholder="Masukkan nomor rekening tanpa spasi"
                               class="form-input">
                        @error('nomor_rekening')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama_pemilik_rekening" class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik Rekening</label>
                        <input type="text" id="nama_pemilik_rekening" name="nama_pemilik_rekening" value="{{ old('nama_pemilik_rekening') }}"
                               placeholder="Nama lengkap sesuai rekening bank"
                               class="form-input">
                        @error('nama_pemilik_rekening')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-2 text-sm text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1 text-orange-500"></i>
                    Pastikan informasi rekening benar untuk proses refund yang lancar.
                </div>
            </div>

            <!-- E-Wallet Information -->
            <div class="form-group" id="ewalletForm" style="display: none;">
                <label class="form-label">
                    <i class="fas fa-mobile-alt text-orange-500 mr-2"></i>
                    Informasi E-Wallet <span class="text-red-500">*</span>
                </label>
                <div class="space-y-4">
                    <div>
                        <label for="nama_ewallet" class="block text-sm font-medium text-gray-700 mb-1">Nama E-Wallet</label>
                        <select id="nama_ewallet" name="nama_ewallet" class="form-input">
                            <option value="">Pilih E-Wallet</option>
                            <option value="GoPay">GoPay</option>
                            <option value="OVO">OVO</option>
                            <option value="DANA">DANA</option>
                            <option value="ShopeePay">ShopeePay</option>
                            <option value="LinkAja">LinkAja</option>
                        </select>
                        @error('nama_ewallet')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nomor_ewallet" class="block text-sm font-medium text-gray-700 mb-1">Nomor E-Wallet</label>
                        <input type="text" id="nomor_ewallet" name="nomor_ewallet" value="{{ old('nomor_ewallet') }}"
                               placeholder="Masukkan nomor telepon atau ID e-wallet"
                               class="form-input">
                        @error('nomor_ewallet')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama_pemilik_ewallet" class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik E-Wallet</label>
                        <input type="text" id="nama_pemilik_ewallet" name="nama_pemilik_ewallet" value="{{ old('nama_pemilik_ewallet') }}"
                               placeholder="Nama lengkap sesuai akun e-wallet"
                               class="form-input">
                        @error('nama_pemilik_ewallet')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-2 text-sm text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1 text-orange-500"></i>
                    Pastikan informasi e-wallet benar untuk proses refund yang lancar.
                </div>
            </div>

            <!-- Syarat dan Ketentuan -->
            <div class="mb-6">
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-orange-800 mb-4 flex items-center">
                        <i class="fas fa-file-contract text-orange-600 mr-3"></i>
                        Syarat dan Ketentuan
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 mt-1 flex-shrink-0">1</div>
                            <p class="text-gray-700 leading-relaxed">
                                Pengajuan pengembalian dapat diajukan dengan menyertakan bukti berupa foto/video.
                            </p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 mt-1 flex-shrink-0">2</div>
                            <p class="text-gray-700 leading-relaxed">
                                Owner akan menghubungi pelanggan untuk melakukan kesepakatan proses pengembalian.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms Agreement -->
            <div class="mb-6">
                <label class="flex items-start">
                    <input type="checkbox" name="agree_terms" required class="mt-1 mr-3 accent-orange-500">
                    <span class="text-sm text-gray-700">
                        Saya menyetujui <a href="#" onclick="showTermsModal(event); return false;" class="text-orange-600 hover:text-orange-800 underline">syarat dan ketentuan refund</a>
                        dan memahami bahwa informasi yang saya berikan adalah benar dan dapat dipertanggungjawabkan.
                    </span>
                </label>
                @error('agree_terms')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>



            <!-- Submit Buttons -->
            <div class="flex flex-wrap gap-4 justify-end mt-8 pt-4 border-t border-gray-200">
                <a href="{{ route('pesanan.show', $pesanan) }}"
                   class="inline-flex items-center px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all shadow-sm hover:shadow">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit"
                        id="submitRefund"
                        class="inline-flex items-center px-6 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-all focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 shadow-sm hover:shadow font-medium">
                    <i class="fas fa-paper-plane mr-2"></i>
                    <span>Ajukan Refund</span>
                </button>
            </div>

        </form>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div id="termsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden shadow-2xl">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-file-contract mr-3"></i>
                    Syarat dan Ketentuan Pengembalian
                </h2>
                <button onclick="closeTermsModal()" class="text-white hover:text-gray-200 text-2xl font-bold">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
            <div class="space-y-6">
                <!-- Ketentuan Umum -->
                <section>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        <i class="fas fa-list-ul text-orange-500 mr-2"></i>
                        Ketentuan Umum
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 mt-1">1</div>
                            <p class="text-gray-700 leading-relaxed">
                                Pengajuan pengembalian dapat diajukan dengan menyertakan bukti berupa foto/video yang menunjukkan kondisi produk atau masalah yang dialami.
                            </p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 mt-1">2</div>
                            <p class="text-gray-700 leading-relaxed">
                                Owner akan menghubungi pelanggan untuk melakukan kesepakatan proses pengembalian setelah pengajuan direview dan disetujui.
                            </p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 mt-1">3</div>
                            <p class="text-gray-700 leading-relaxed">
                                Pengajuan pengembalian akan diproses dalam waktu 1-3 hari kerja setelah diterima.
                            </p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-orange-500 text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 mt-1">4</div>
                            <p class="text-gray-700 leading-relaxed">
                                Informasi yang diberikan dalam pengajuan harus benar dan dapat dipertanggungjawabkan.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Proses Komunikasi -->
                <section>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        <i class="fas fa-phone text-orange-500 mr-2"></i>
                        Proses Komunikasi
                    </h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mr-3 mt-1"></i>
                            <div>
                                <p class="text-blue-800 font-semibold mb-2">Langkah-langkah Komunikasi:</p>
                                <ol class="list-decimal list-inside space-y-2 text-blue-700">
                                    <li>Tim review akan memeriksa pengajuan pengembalian Anda</li>
                                    <li>Jika disetujui, owner akan menghubungi melalui nomor telepon yang terdaftar</li>
                                    <li>Kesepakatan proses pengembalian akan didiskusikan bersama</li>
                                    <li>Proses pengembalian dana akan dilakukan sesuai kesepakatan</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-500 mr-3 mt-1"></i>
                            <div>
                                <p class="text-yellow-800 font-semibold mb-1">Penting!</p>
                                <p class="text-yellow-700">
                                    Pastikan nomor telepon dan informasi kontak Anda dapat dihubungi untuk mempercepat proses koordinasi pengembalian.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Bukti Pendukung -->
                <section>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        <i class="fas fa-camera text-orange-500 mr-2"></i>
                        Bukti Pendukung
                    </h3>
                    <div class="space-y-3">
                        <p class="text-gray-700 leading-relaxed">
                            Untuk mempercepat proses review, disarankan untuk melampirkan:
                        </p>
                        <ul class="space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span class="text-gray-700">Foto kondisi produk yang bermasalah</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span class="text-gray-700">Video yang menunjukkan masalah (jika diperlukan)</span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span class="text-gray-700">Foto kemasan atau label produk</span>
                            </li>
                        </ul>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mt-4">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                                <strong>Tips:</strong> Foto yang jelas dan detail akan membantu tim review memahami masalah dengan lebih baik dan mempercepat proses persetujuan.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Hak dan Kewajiban -->
                <section>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">
                        <i class="fas fa-balance-scale text-orange-500 mr-2"></i>
                        Hak dan Kewajiban
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-green-700 mb-3 flex items-center">
                                <i class="fas fa-user-check mr-2"></i>
                                Hak Pelanggan
                            </h4>
                            <ul class="space-y-2">
                                <li class="flex items-start">
                                    <i class="fas fa-dot-circle text-green-500 mr-2 mt-1 text-xs"></i>
                                    <span class="text-sm text-gray-700">Mendapat penjelasan yang jelas tentang status pengajuan</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-dot-circle text-green-500 mr-2 mt-1 text-xs"></i>
                                    <span class="text-sm text-gray-700">Mendapat komunikasi yang transparan dari tim</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-dot-circle text-green-500 mr-2 mt-1 text-xs"></i>
                                    <span class="text-sm text-gray-700">Mendapat pengembalian dana sesuai kesepakatan</span>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-blue-700 mb-3 flex items-center">
                                <i class="fas fa-user-edit mr-2"></i>
                                Kewajiban Pelanggan
                            </h4>
                            <ul class="space-y-2">
                                <li class="flex items-start">
                                    <i class="fas fa-dot-circle text-blue-500 mr-2 mt-1 text-xs"></i>
                                    <span class="text-sm text-gray-700">Memberikan informasi yang akurat dan jujur</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-dot-circle text-blue-500 mr-2 mt-1 text-xs"></i>
                                    <span class="text-sm text-gray-700">Menyediakan bukti yang relevan dan valid</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-dot-circle text-blue-500 mr-2 mt-1 text-xs"></i>
                                    <span class="text-sm text-gray-700">Dapat dihubungi untuk komunikasi lebih lanjut</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-200">
            <button onclick="closeTermsModal()" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-semibold transition-all">
                <i class="fas fa-check mr-2"></i>
                Mengerti
            </button>
        </div>
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Refund form script initialized');

    const uploadArea = document.getElementById('uploadArea');
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    const photoGrid = document.getElementById('photoGrid');
    const refundForm = document.getElementById('refundForm');
    const submitBtn = document.getElementById('submitRefund');
    let selectedFiles = [];

    // Initialize all handlers
    initializeRefundReasonHandlers();
    initializeRefundMethodHandlers();
    initializePhotoUpload();
    initializeFormSubmission();
    initializeAmountValidation();

    // Additional fallback initialization with delay
    setTimeout(function() {
        console.log('Fallback upload initialization...');
        const fallbackUploadArea = document.getElementById('uploadArea');
        const fallbackPhotoInput = document.getElementById('photoInput');

        if (fallbackUploadArea && fallbackPhotoInput) {
            console.log('Setting up fallback click handler');

            // Remove any existing listeners and add fresh one
            const newUploadArea = fallbackUploadArea.cloneNode(true);
            fallbackUploadArea.parentNode.replaceChild(newUploadArea, fallbackUploadArea);

            newUploadArea.addEventListener('click', function(e) {
                console.log('FALLBACK: Upload area clicked');
                e.preventDefault();
                e.stopPropagation();
                fallbackPhotoInput.click();
            });

            // Also add to the content div
            const contentDiv = newUploadArea.querySelector('#uploadContent');
            if (contentDiv) {
                contentDiv.addEventListener('click', function(e) {
                    console.log('FALLBACK: Content div clicked');
                    e.preventDefault();
                    e.stopPropagation();
                    fallbackPhotoInput.click();
                });
            }
        }
    }, 1000);

    // Initialize refund reason selection handlers
    function initializeRefundReasonHandlers() {
        const reasonOptions = document.querySelectorAll('.radio-option');
        console.log('Found reason options:', reasonOptions.length);

        reasonOptions.forEach(option => {
            option.addEventListener('click', function() {
                console.log('Reason option clicked');

                // Reset all options
                reasonOptions.forEach(opt => {
                    opt.style.borderColor = '#e2e8f0';
                    opt.style.backgroundColor = '';
                    const radio = opt.querySelector('input[type="radio"]');
                    if (radio) radio.checked = false;
                });

                // Select current option
                this.style.borderColor = '#f97316';
                this.style.backgroundColor = '#fff7ed';
                const radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                    console.log('Selected reason:', radio.value);
                }
            });
        });
    }

    // Initialize refund method selection handlers
    function initializeRefundMethodHandlers() {
        const refundMethodOptions = document.querySelectorAll('.refund-method-option');
        console.log('Found refund method options:', refundMethodOptions.length);

        refundMethodOptions.forEach(option => {
            option.addEventListener('click', function() {
                console.log('Refund method clicked');

                const radio = this.querySelector('input[type="radio"]');
                if (!radio) return;

                // Reset all options
                refundMethodOptions.forEach(opt => {
                    const r = opt.querySelector('input[type="radio"]');
                    const indicator = opt.querySelector('.radio-indicator div');

                    if (r) r.checked = false;
                    if (indicator) indicator.style.opacity = '0';
                    opt.style.borderColor = '#d1d5db';
                    opt.style.backgroundColor = '';
                });

                // Select current option
                radio.checked = true;
                const indicator = this.querySelector('.radio-indicator div');
                if (indicator) indicator.style.opacity = '1';
                this.style.borderColor = '#f97316';
                this.style.backgroundColor = '#fff7ed';

                console.log('Selected refund method:', radio.value);
                toggleRefundForms(radio.value);
            });
        });
    }

    function toggleRefundForms(selectedMethod) {
        const bankForm = document.getElementById('bankForm');
        const ewalletForm = document.getElementById('ewalletForm');

        console.log('Toggling forms for method:', selectedMethod);

        if (!bankForm || !ewalletForm) {
            console.error('Bank or e-wallet form not found');
            return;
        }

        // Reset both forms
        bankForm.style.display = 'none';
        ewalletForm.style.display = 'none';

        // Clear required attributes
        ['nama_bank', 'nomor_rekening', 'nama_pemilik_rekening'].forEach(field => {
            const element = document.getElementById(field);
            if (element) element.required = false;
        });

        ['nama_ewallet', 'nomor_ewallet', 'nama_pemilik_ewallet'].forEach(field => {
            const element = document.getElementById(field);
            if (element) element.required = false;
        });

        if (selectedMethod === 'bank_transfer') {
            bankForm.style.display = 'block';

            // Set required attributes for bank fields
            ['nama_bank', 'nomor_rekening', 'nama_pemilik_rekening'].forEach(field => {
                const element = document.getElementById(field);
                if (element) element.required = true;
            });

            console.log('Bank form shown');
        } else if (selectedMethod === 'e_wallet') {
            ewalletForm.style.display = 'block';

            // Set required attributes for e-wallet fields
            ['nama_ewallet', 'nomor_ewallet', 'nama_pemilik_ewallet'].forEach(field => {
                const element = document.getElementById(field);
                if (element) element.required = true;
            });

            console.log('E-wallet form shown');
        }
    }    // Initialize photo upload functionality
    function initializePhotoUpload() {
        console.log('Initializing photo upload...');

        if (!uploadArea || !photoInput) {
            console.error('Photo upload elements not found');
            return;
        }

        console.log('Photo upload elements found - setting up handlers');

        // Simple, direct click handler - this should work
        function handleUploadClick(e) {
            console.log('Upload area clicked!');
            e.preventDefault();
            e.stopPropagation();

            try {
                photoInput.click();
                console.log('File input triggered successfully');
            } catch (error) {
                console.error('Error triggering file input:', error);
            }
        }

        // Remove any existing event listeners by cloning the element
        const newUploadArea = uploadArea.cloneNode(true);
        uploadArea.parentNode.replaceChild(newUploadArea, uploadArea);

        // Update reference to the new element
        const freshUploadArea = document.getElementById('uploadArea');
        const freshPhotoInput = document.getElementById('photoInput');

        // Add event listeners to the fresh element
        freshUploadArea.addEventListener('click', handleUploadClick);

        // Also add to all child elements to ensure click is captured
        const allElements = freshUploadArea.querySelectorAll('*');
        allElements.forEach(element => {
            element.addEventListener('click', handleUploadClick);
        });

        // Make sure the photo input change handler is also attached to fresh element
        freshPhotoInput.addEventListener('change', function() {
            console.log('Files selected:', this.files.length);
            handleFiles(this.files);
        });

        // Drag and drop events for fresh element
        freshUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('dragover');
            console.log('Drag over detected');
        });

        freshUploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('dragover');
            console.log('Drag leave detected');
        });

        freshUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('dragover');
            console.log('Files dropped:', e.dataTransfer.files.length);
            const files = e.dataTransfer.files;
            handleFiles(files);
        });

        console.log('Upload functionality initialized with fresh elements');
    }

    function handleFiles(files) {
        console.log('Handling files:', files.length);

        for (let file of files) {
            if (selectedFiles.length >= 5) {
                Swal.fire({
                    title: 'Perhatian',
                    text: 'Maksimal 5 foto yang dapat diunggah',
                    icon: 'warning',
                    confirmButtonColor: '#f97316'
                });
                break;
            }

            if (!file.type.startsWith('image/')) {
                Swal.fire({
                    title: 'Format File Tidak Valid',
                    text: 'Hanya file gambar yang diperbolehkan (JPG, PNG, JPEG)',
                    icon: 'error',
                    confirmButtonColor: '#f97316'
                });
                continue;
            }

            if (file.size > 2 * 1024 * 1024) { // 2MB limit
                Swal.fire({
                    title: 'Ukuran File Terlalu Besar',
                    text: 'Ukuran file maksimal 2MB',
                    icon: 'error',
                    confirmButtonColor: '#f97316'
                });
                continue;
            }

            selectedFiles.push(file);
            displayPreview(file, selectedFiles.length - 1);
            console.log('File added. Total files:', selectedFiles.length);
        }
        updateFileInput();
    }

    function displayPreview(file, index) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'preview-image relative group';
            previewDiv.innerHTML = `
                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 hover:border-orange-400 transition-all duration-300">
                    <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-20 object-cover">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                        <button type="button"
                                onclick="removePhoto(${index})"
                                class="opacity-0 group-hover:opacity-100 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold transition-all duration-300 transform scale-90 group-hover:scale-100">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center cursor-pointer hover:bg-red-600 text-xs shadow-lg" onclick="removePhoto(${index})">
                    <i class="fas fa-times"></i>
                </div>
            `;

            const photoGrid = document.getElementById('photoGrid');
            photoGrid.appendChild(previewDiv);

            const photoPreview = document.getElementById('photoPreview');
            const photoCount = document.getElementById('photoCount');

            photoPreview.style.display = 'block';
            photoCount.textContent = selectedFiles.length;
        };
        reader.readAsDataURL(file);
    }

    // Global function for removing photos
    window.removePhoto = function(index) {
        console.log('Removing photo at index:', index);
        selectedFiles.splice(index, 1);
        updateFileInput();
        refreshPreviews();
    };

    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        photoInput.files = dt.files;
        console.log('Updated file input with', selectedFiles.length, 'files');
    }

    function refreshPreviews() {
        const photoGrid = document.getElementById('photoGrid');
        const photoPreview = document.getElementById('photoPreview');
        const photoCount = document.getElementById('photoCount');

        photoGrid.innerHTML = '';

        if (selectedFiles.length === 0) {
            photoPreview.style.display = 'none';
            return;
        }

        selectedFiles.forEach((file, index) => {
            displayPreview(file, index);
        });

        photoCount.textContent = selectedFiles.length;
    }

    // Form submission handler
    function initializeFormSubmission() {
        if (!refundForm || !submitBtn) {
            console.error('Form or submit button not found');
            return;
        }

        refundForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('Form submission attempted');

            // Validate form
            if (!validateForm()) {
                return;
            }

            // Show confirmation dialog
            const confirmed = await showConfirmationDialog();
            if (!confirmed) {
                return;
            }

            // Submit form
            await submitFormData();
        });
    }

    function validateForm() {
        // Check reason selection
        const reason = document.querySelector('input[name="jenis_keluhan"]:checked');
        if (!reason) {
            Swal.fire({
                title: 'Error',
                text: 'Silakan pilih alasan refund',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return false;
        }

        // Check description
        const description = document.getElementById('description');
        if (!description || description.value.trim().length < 20) {
            Swal.fire({
                title: 'Error',
                text: 'Deskripsi masalah harus minimal 20 karakter',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return false;
        }

        // Check amount
        const amount = document.getElementById('amount');
        if (!amount || !amount.value || parseFloat(amount.value) <= 0) {
            Swal.fire({
                title: 'Error',
                text: 'Jumlah refund harus lebih dari 0',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return false;
        }

        // Check refund method
        const refundMethod = document.querySelector('input[name="metode_refund"]:checked');
        if (!refundMethod) {
            Swal.fire({
                title: 'Error',
                text: 'Silakan pilih metode refund',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return false;
        }

        // Validate method-specific fields
        if (refundMethod.value === 'bank_transfer') {
            const namaBank = document.getElementById('nama_bank');
            const nomorRekening = document.getElementById('nomor_rekening');
            const namaPemilik = document.getElementById('nama_pemilik_rekening');

            if (!namaBank || namaBank.value.trim().length < 3) {
                Swal.fire({
                    title: 'Error',
                    text: 'Nama bank harus diisi dengan benar',
                    icon: 'error',
                    confirmButtonColor: '#f97316'
                });
                return false;
            }

            if (!nomorRekening || nomorRekening.value.trim().length < 5) {
                Swal.fire({
                    title: 'Error',
                    text: 'Nomor rekening harus diisi dengan benar',
                    icon: 'error',
                    confirmButtonColor: '#f97316'
                });
                return false;
            }

            if (!namaPemilik || namaPemilik.value.trim().length < 3) {
                Swal.fire({
                    title: 'Error',
                    text: 'Nama pemilik rekening harus diisi dengan benar',
                    icon: 'error',
                    confirmButtonColor: '#f97316'
                });
                return false;
            }
        } else if (refundMethod.value === 'e_wallet') {
            const namaEwallet = document.getElementById('nama_ewallet');
            const nomorEwallet = document.getElementById('nomor_ewallet');
            const namaPemilikEwallet = document.getElementById('nama_pemilik_ewallet');

            if (!namaEwallet || !namaEwallet.value) {
                Swal.fire({
                    title: 'Error',
                    text: 'Silakan pilih jenis e-wallet',
                    icon: 'error',
                    confirmButtonColor: '#f97316'
                });
                return false;
            }

            if (!nomorEwallet || nomorEwallet.value.trim().length < 5) {
                Swal.fire({
                    title: 'Error',
                    text: 'Nomor e-wallet harus diisi dengan benar',
                    icon: 'error',
                    confirmButtonColor: '#f97316'
                });
                return false;
            }

            if (!namaPemilikEwallet || namaPemilikEwallet.value.trim().length < 3) {
                Swal.fire({
                    title: 'Error',
                    text: 'Nama pemilik e-wallet harus diisi dengan benar',
                    icon: 'error',
                    confirmButtonColor: '#f97316'
                });
                return false;
            }
        }

        // Check terms agreement
        const agreeTerms = document.querySelector('input[name="agree_terms"]:checked');
        if (!agreeTerms) {
            Swal.fire({
                title: 'Error',
                text: 'Anda harus menyetujui syarat dan ketentuan',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return false;
        }

        console.log('Form validation passed');
        return true;
    }

    async function showConfirmationDialog() {
        const refundMethod = document.querySelector('input[name="metode_refund"]:checked');
        const reason = document.querySelector('input[name="jenis_keluhan"]:checked');
        const description = document.getElementById('description');
        const amount = document.getElementById('amount');

        let paymentDetails = '';
        if (refundMethod.value === 'bank_transfer') {
            const bankName = document.getElementById('nama_bank').value;
            const accountNumber = document.getElementById('nomor_rekening').value;
            const accountHolder = document.getElementById('nama_pemilik_rekening').value;
            paymentDetails = `
                <div class="text-left bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                    <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                        <i class="fas fa-university mr-2"></i>Transfer Bank
                    </h4>
                    <div class="space-y-1 text-sm text-blue-700">
                        <p><span class="font-medium">Bank:</span> ${bankName}</p>
                        <p><span class="font-medium">Rekening:</span> ${accountNumber}</p>
                        <p><span class="font-medium">Atas Nama:</span> ${accountHolder}</p>
                    </div>
                </div>
            `;
        } else if (refundMethod.value === 'e_wallet') {
            const walletName = document.getElementById('nama_ewallet').value;
            const walletNumber = document.getElementById('nomor_ewallet').value;
            const walletHolder = document.getElementById('nama_pemilik_ewallet').value;
            paymentDetails = `
                <div class="text-left bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                    <h4 class="font-semibold text-green-800 mb-2 flex items-center">
                        <i class="fas fa-mobile-alt mr-2"></i>E-Wallet
                    </h4>
                    <div class="space-y-1 text-sm text-green-700">
                        <p><span class="font-medium">Platform:</span> ${walletName}</p>
                        <p><span class="font-medium">Nomor:</span> ${walletNumber}</p>
                        <p><span class="font-medium">Atas Nama:</span> ${walletHolder}</p>
                    </div>
                </div>
            `;
        }

        // Generate photo preview HTML for SweetAlert
        let photoPreviewHtml = '';
        if (selectedFiles.length > 0) {
            const photoThumbnails = await Promise.all(
                selectedFiles.map((file, index) => {
                    return new Promise((resolve) => {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            resolve(`
                                <div class="inline-block m-1">
                                    <img src="${e.target.result}" alt="Foto ${index + 1}" class="w-16 h-16 object-cover rounded-lg border border-gray-300 shadow-sm">
                                </div>
                            `);
                        };
                        reader.readAsDataURL(file);
                    });
                })
            );

            photoPreviewHtml = `
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-3">
                    <h4 class="font-semibold text-orange-800 mb-2 flex items-center">
                        <i class="fas fa-camera mr-2"></i>Foto Bukti (${selectedFiles.length})
                    </h4>
                    <div class="flex flex-wrap justify-start">
                        ${photoThumbnails.join('')}
                    </div>
                </div>
            `;
        }

        const result = await Swal.fire({
            title: 'Konfirmasi Pengajuan Refund',
            html: `
                <div class="text-left space-y-4">
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <h4 class="font-semibold text-orange-800 mb-3 flex items-center">
                            <i class="fas fa-file-alt mr-2"></i>Detail Pengajuan
                        </h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between py-1 border-b border-orange-100">
                                <span class="text-gray-600">Pesanan:</span>
                                <span class="font-medium">#{{ $pesanan->nomor_pesanan }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-orange-100">
                                <span class="text-gray-600">Alasan:</span>
                                <span class="font-medium">${reason.value}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-orange-100">
                                <span class="text-gray-600">Jumlah Refund:</span>
                                <span class="font-medium text-orange-600">Rp ${parseInt(amount.value).toLocaleString('id-ID')}</span>
                            </div>
                        </div>
                    </div>

                    ${photoPreviewHtml}

                    ${paymentDetails}

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                        <h4 class="font-semibold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-comment-alt mr-2"></i>Deskripsi Masalah
                        </h4>
                        <p class="text-sm text-gray-700 italic bg-white p-2 rounded border">"${description.value}"</p>
                    </div>

                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-yellow-400 mt-0.5 mr-2"></i>
                            <div>
                                <p class="text-sm font-medium text-yellow-800">Perhatian</p>
                                <p class="text-xs text-yellow-700 mt-1">
                                    Pastikan semua informasi sudah benar. Pengajuan yang sudah dikirim tidak dapat diubah.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-paper-plane mr-2"></i>Ya, Ajukan Refund',
            cancelButtonText: '<i class="fas fa-times mr-2"></i>Batalkan',
            width: '650px',
            customClass: {
                popup: 'rounded-xl shadow-2xl',
                title: 'text-lg font-bold text-gray-800',
                content: 'text-left'
            }
        });

        return result.isConfirmed;
    }

    async function submitFormData() {
        // Show loading state on button only
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

        try {
            const formData = new FormData(refundForm);
            const token = document.querySelector('meta[name="csrf-token"]').content;

            const response = await fetch(refundForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            let result;
            try {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    const text = await response.text();
                    try {
                        result = JSON.parse(text);
                    } catch (e) {
                        console.error("Response is not JSON:", text);
                        result = { message: 'Server returned an invalid response format' };
                    }
                }
            } catch (parseError) {
                console.error("Error parsing response:", parseError);
                throw new Error('Terjadi kesalahan saat memproses respons dari server');
            }

            if (response.ok) {
                await Swal.fire({
                    title: 'Sukses',
                    html: `
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-6">
                                <i class="fas fa-check-circle text-green-600 text-4xl"></i>
                            </div>
                            <p class="mb-4">Pengajuan refund berhasil dikirim. Tim kami akan segera memprosesnya.</p>
                            <p class="text-sm text-gray-600">Pengajuan akan diproses dalam 1-3 hari kerja.</p>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonColor: '#f97316'
                });
                // Use redirect_url from response if available, otherwise fallback to pesanan show
                if (result && result.redirect_url) {
                    window.location.href = result.redirect_url;
                } else {
                    window.location.href = '{{ route("pesanan.show", $pesanan) }}';
                }
            } else {
                console.error("Response error:", result);
                if (result && result.errors) {
                    const errorMessages = Object.values(result.errors).flat().join('<br>');
                    throw new Error(errorMessages);
                }
                throw new Error((result && result.message) ? result.message : 'Terjadi kesalahan saat memproses pengajuan refund');
            }
        } catch (error) {
            console.error("Form submission error:", error);
            let errorMsg = error.message || 'Terjadi kesalahan saat mengirim pengajuan refund';

            Swal.fire({
                title: 'Error',
                text: errorMsg,
                icon: 'error',
                confirmButtonColor: '#f97316'
            });

            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Ajukan Refund';
        }
    }

    // Initialize amount validation
    function initializeAmountValidation() {
        const amountField = document.getElementById('amount');
        if (amountField) {
            amountField.addEventListener('input', function() {
                const amount = parseInt(this.value) || 0;
                const maxAmount = {{ $pesanan->total_harga }};

                if (amount > maxAmount) {
                    this.value = maxAmount;
                    this.classList.add('form-field-invalid');
                    setTimeout(() => {
                        this.classList.remove('form-field-invalid');
                    }, 800);
                }
            });
        }
    }
    // Terms Modal Functions
    function showTermsModal(event) {
        event.preventDefault();
        const modal = document.getElementById('termsModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeTermsModal() {
        const modal = document.getElementById('termsModal');
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Close modal when clicking outside
    document.getElementById('termsModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeTermsModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('termsModal');
        if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
            closeTermsModal();
        }
    });

    // Make functions globally available
    window.showTermsModal = showTermsModal;
    window.closeTermsModal = closeTermsModal;
});
</script>
@endpush

@endsection
