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
    }

    .upload-area::before {
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

    .upload-area:hover {
        border-color: var(--primary-color);
        background: linear-gradient(145deg, #fff7ed, #ffffff);
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    .upload-area:hover::before {
        opacity: 1;
    }

    .upload-area.dragover {
        border-color: var(--primary-color);
        background: linear-gradient(145deg, #fff7ed, #ffffff);
        box-shadow: var(--shadow-large);
    }

    .preview-image {
        position: relative;
        display: inline-block;
        margin: 0.5rem;
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
        box-shadow: var(--shadow-large);
    }

    .radio-option {
        position: relative;
        cursor: pointer;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        transition: all 0.3s ease;
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        overflow: hidden;
    }

    .radio-option::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(249, 115, 22, 0.1), transparent);
        transition: left 0.5s ease;
    }

    .radio-option:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    .radio-option:hover::before {
        left: 100%;
    }

    .radio-option input[type="radio"]:checked + div {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
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

    @keyframes spin {
        to { transform: rotate(360deg); }
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
</style>
@endpush

@section('content')
<!-- CSRF Meta Tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-content">
        <div class="loading-spinner"></div>
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Memproses pengajuan refund...</h3>
        <p class="text-gray-600">Mohon tunggu sebentar</p>
    </div>
</div>

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

            <div class="flex flex-col md:flex-row items-start space-y-4 md:space-y-0 md:space-x-6 p-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-2xl">
                @if($pesanan->produk && $pesanan->produk->gambar)
                <div class="product-image-container flex-shrink-0">
                    <img src="{{ asset('storage/' . $pesanan->produk->gambar) }}"
                         alt="{{ $pesanan->produk->nama }}"
                         class="w-32 h-32 object-cover">
                </div>
                @else
                <div class="w-32 h-32 bg-gradient-to-br from-gray-200 to-gray-300 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-fish text-4xl text-gray-400"></i>
                </div>
                @endif

                <div class="flex-grow space-y-3">
                    <h4 class="text-xl font-bold text-gray-900">{{ $pesanan->produk->nama ?? 'Produk' }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-fish text-orange-500 mr-2"></i>
                            <span class="text-gray-600">Kuantitas:</span>
                            <span class="font-semibold ml-1">{{ $pesanan->kuantitas }} ekor</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-money-bill-wave text-orange-500 mr-2"></i>
                            <span class="text-gray-600">Total:</span>
                            <span class="font-bold text-orange-600 ml-1">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-orange-500 mr-2"></i>
                            <span class="text-gray-600">Status:</span>
                            <span class="status-indicator bg-blue-100 text-blue-800 ml-1">{{ $pesanan->status_pesanan }}</span>
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
                        <label class="radio-option p-6">
                            <input type="radio" name="jenis_refund" value="kerusakan" required class="sr-only">
                            <div class="text-center">
                                <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-3"></i>
                                <div class="font-bold text-gray-900 mb-2">Produk Rusak/Cacat</div>
                                <div class="text-sm text-gray-600">Ikan yang diterima dalam kondisi rusak atau cacat</div>
                            </div>
                        </label>

                        <label class="radio-option p-6">
                            <input type="radio" name="jenis_refund" value="tidak_sesuai" required class="sr-only">
                            <div class="text-center">
                                <i class="fas fa-times-circle text-3xl text-orange-500 mb-3"></i>
                                <div class="font-bold text-gray-900 mb-2">Barang yang Diterima Salah</div>
                                <div class="text-sm text-gray-600">Ikan yang diterima tidak sesuai pesanan</div>
                            </div>
                        </label>

                        <label class="radio-option p-6">
                            <input type="radio" name="jenis_refund" value="tidak_sesuai_deskripsi" required class="sr-only">
                            <div class="text-center">
                                <i class="fas fa-file-alt text-3xl text-blue-500 mb-3"></i>
                                <div class="font-bold text-gray-900 mb-2">Tidak Sesuai Deskripsi</div>
                                <div class="text-sm text-gray-600">Ikan tidak sesuai dengan deskripsi produk</div>
                            </div>
                        </label>

                        <label class="radio-option p-6">
                            <input type="radio" name="jenis_refund" value="kematian_ikan" required class="sr-only">
                            <div class="text-center">
                                <i class="fas fa-skull text-3xl text-gray-500 mb-3"></i>
                                <div class="font-bold text-gray-900 mb-2">Ikan Mati saat Diterima</div>
                                <div class="text-sm text-gray-600">Ikan sudah mati saat paket diterima</div>
                            </div>
                        </label>
                    </div>
                    @error('jenis_refund')
                    <p class="text-sm text-red-500 mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="section-divider"></div>

                <!-- Amount Section -->
                <div class="form-group">
                    <label for="jumlah_diminta" class="form-label">
                        <i class="fas fa-money-bill-wave text-orange-500 mr-2"></i>
                        Jumlah Refund <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-medium">Rp</span>
                        </div>
                        <input type="number" id="amount" name="jumlah_diminta" value="{{ $pesanan->total_harga }}"
                               min="1" max="{{ $pesanan->total_harga }}" required
                               class="form-input pl-12">
                    </div>
                    <div class="info-badge mt-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        Maksimal: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                    </div>
                    @error('jumlah_diminta')
                    <p class="text-sm text-red-500 mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Description Section -->
                <div class="form-group">
                    <label for="deskripsi_masalah" class="form-label">
                        <i class="fas fa-edit text-orange-500 mr-2"></i>
                        Deskripsi Masalah <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" name="deskripsi_masalah" rows="5" required
                              placeholder="Jelaskan secara detail masalah yang Anda alami dengan produk ini..."
                              class="form-input resize-none">{{ old('deskripsi_masalah') }}</textarea>
                    <div class="info-badge mt-3">
                        <i class="fas fa-lightbulb mr-2"></i>
                        Minimum 20 karakter. Semakin detail, semakin baik untuk proses review.
                    </div>
                    @error('deskripsi_masalah')
                    <p class="text-sm text-red-500 mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Evidence Photos Section -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-camera text-orange-500 mr-2"></i>
                        Bukti Foto <span class="text-gray-400 text-xs">(Opsional)</span>
                    </label>
                    <div class="upload-area" id="uploadArea">
                        <input type="file" id="photoInput" name="bukti_pendukung[]" multiple accept="image/*" class="hidden">
                        <div class="relative z-10">
                            <i class="fas fa-cloud-upload-alt text-5xl text-orange-400 mb-4"></i>
                            <h4 class="text-xl font-bold text-gray-700 mb-2">Klik atau seret foto ke sini</h4>
                            <p class="text-sm text-gray-500 mb-2">Maksimal 5 foto, ukuran masing-masing maksimal 5MB</p>
                            <p class="text-xs text-gray-400">Format: JPG, PNG, JPEG</p>
                        </div>
                    </div>

                    <!-- Photo Previews -->
                    <div id="photoPreview" class="mt-6 flex flex-wrap gap-4"></div>

                    <div class="info-badge mt-4">
                        <i class="fas fa-camera mr-2"></i>
                        Foto bukti akan membantu mempercepat proses review refund Anda
                    </div>
                    @error('bukti_pendukung')
                    <p class="text-sm text-red-500 mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                    @error('bukti_pendukung.*')
                    <p class="text-sm text-red-500 mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="section-divider"></div>

                <!-- Refund Method Section -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-credit-card text-orange-500 mr-2"></i>
                        Metode Refund <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="radio-option p-6">
                            <input type="radio" name="metode_refund" value="transfer_bank" required class="sr-only">
                            <div class="text-center">
                                <i class="fas fa-university text-3xl text-blue-500 mb-3"></i>
                                <div class="font-bold text-gray-900">Transfer Bank</div>
                            </div>
                        </label>

                        <label class="radio-option p-6">
                            <input type="radio" name="metode_refund" value="e_wallet" required class="sr-only">
                            <div class="text-center">
                                <i class="fas fa-mobile-alt text-3xl text-green-500 mb-3"></i>
                                <div class="font-bold text-gray-900">E-Wallet</div>
                            </div>
                        </label>
                    </div>
                    <div class="info-badge mt-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        Metode ini adalah preferensi Anda. Tim kami akan mengonfirmasi metode final saat memproses refund.
                    </div>
                    @error('metode_refund')
                    <p class="text-sm text-red-500 mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Refund Details Section -->
                <div class="form-group">
                    <label for="detail_refund" class="form-label">
                        <i class="fas fa-info text-orange-500 mr-2"></i>
                        Detail Refund <span class="text-red-500">*</span>
                    </label>
                    <textarea id="detail_refund" name="detail_refund" rows="3" required
                              placeholder="Masukkan detail metode refund (contoh: nomor rekening bank, nomor e-wallet, dll)..."
                              class="form-input resize-none">{{ old('detail_refund') }}</textarea>
                    <div class="info-badge mt-3">
                        <i class="fas fa-university mr-2"></i>
                        Untuk transfer bank, masukkan nama bank, nomor rekening, dan nama pemilik rekening.
                    </div>
                    @error('detail_refund')
                    <p class="text-sm text-red-500 mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Terms Agreement -->
                <div class="form-group">
                    <label class="flex items-start p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-2xl cursor-pointer hover:shadow-md transition-all">
                        <input type="checkbox" name="agree_terms" required class="mt-1 mr-3 accent-orange-500 w-5 h-5">
                        <span class="text-sm text-gray-700 leading-relaxed">
                            Saya menyetujui <a href="#" class="text-orange-600 hover:text-orange-800 font-semibold">syarat dan ketentuan refund</a>
                            dan memahami bahwa informasi yang saya berikan adalah benar dan dapat dipertanggungjawabkan.
                        </span>
                    </label>
                    @error('agree_terms')
                    <p class="text-sm text-red-500 mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col md:flex-row gap-4 justify-end pt-8 border-t border-gray-200">
                    <a href="{{ route('pesanan.show', $pesanan) }}" class="btn-secondary text-center">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" id="submitRefund" class="btn-primary">
                        <i class="fas fa-paper-plane mr-2"></i>
                        <span>Ajukan Refund</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    const loadingOverlay = document.getElementById('loadingOverlay');
    let selectedFiles = [];

    // Radio option selection
    document.querySelectorAll('.radio-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                // Clear all selections first
                document.querySelectorAll('.radio-option').forEach(opt => {
                    opt.style.borderColor = 'var(--border-color)';
                    opt.style.boxShadow = 'var(--shadow-light)';
                    const div = opt.querySelector('div');
                    if (div) {
                        div.style.background = '';
                        div.style.color = '';
                    }
                });

                // Select this option
                radio.checked = true;
                this.style.borderColor = 'var(--primary-color)';
                this.style.boxShadow = 'var(--shadow-medium)';
                const div = this.querySelector('div');
                if (div) {
                    div.style.background = 'linear-gradient(135deg, var(--primary-color), var(--primary-dark))';
                    div.style.color = 'white';
                }
            }
        });
    });

    // Click to upload
    uploadArea.addEventListener('click', function() {
        photoInput.click();
    });

    // Drag and drop events
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    // File input change
    photoInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
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

            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({
                    title: 'Ukuran File Terlalu Besar',
                    text: 'Ukuran file maksimal 5MB',
                    icon: 'error',
                    confirmButtonColor: '#f97316'
                });
                continue;
            }

            selectedFiles.push(file);
            displayPreview(file, selectedFiles.length - 1);
        }
        updateFileInput();
    }

    function displayPreview(file, index) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'preview-image';
            previewDiv.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <div class="remove-btn" onclick="removePhoto(${index})">
                    <i class="fas fa-times"></i>
                </div>
            `;
            photoPreview.appendChild(previewDiv);
        };
        reader.readAsDataURL(file);
    }

    window.removePhoto = function(index) {
        selectedFiles.splice(index, 1);
        updateFileInput();
        refreshPreviews();
    };

    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        photoInput.files = dt.files;
    }

    function refreshPreviews() {
        photoPreview.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            displayPreview(file, index);
        });
    }

    // Form submission
    const refundForm = document.getElementById('refundForm');
    const submitBtn = document.getElementById('submitRefund');

    refundForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validate form
        const reason = document.querySelector('input[name="jenis_refund"]:checked');
        if (!reason) {
            Swal.fire({
                title: 'Error',
                text: 'Silakan pilih alasan refund',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        const description = document.getElementById('description').value;
        if (description.length < 20) {
            Swal.fire({
                title: 'Error',
                text: 'Deskripsi masalah harus minimal 20 karakter',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        const amount = document.getElementById('amount').value;
        if (!amount || amount <= 0) {
            Swal.fire({
                title: 'Error',
                text: 'Jumlah refund harus lebih dari 0',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        const detailRefund = document.getElementById('detail_refund').value;
        if (!detailRefund || detailRefund.length < 5) {
            Swal.fire({
                title: 'Error',
                text: 'Detail refund harus diisi dengan lengkap',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        const refundMethod = document.querySelector('input[name="metode_refund"]:checked');
        if (!refundMethod) {
            Swal.fire({
                title: 'Error',
                text: 'Silakan pilih metode refund',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        const agreeTerms = document.querySelector('input[name="agree_terms"]:checked');
        if (!agreeTerms) {
            Swal.fire({
                title: 'Error',
                text: 'Anda harus menyetujui syarat dan ketentuan',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        // Show confirmation
        const result = await Swal.fire({
            title: 'Konfirmasi Pengajuan Refund',
            text: 'Apakah Anda yakin ingin mengajukan refund untuk pesanan ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Ajukan',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) {
            return;
        }

        // Show loading state
        loadingOverlay.classList.add('active');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

        // Submit form
        this.submit();
    });

    // Update amount display
    document.getElementById('amount').addEventListener('input', function() {
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
});
</script>
@endpush
