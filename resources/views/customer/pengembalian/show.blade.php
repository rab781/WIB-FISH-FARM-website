@extends('layouts.app')

@section('title', 'Detail Refund')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css">
<style>
    /* CSS Custom Properties */
    :root {
        --primary-color: #ff8c00;
        --primary-dark: #e67e00;
        --primary-light: #ffb74d;
        --secondary-color: #1a1a1a;
        --accent-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --info-color: #17a2b8;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
        --gradient-primary: linear-gradient(135deg, #ff8c00 0%, #e67e00 100%);
        --gradient-secondary: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        --gradient-success: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        --gradient-warning: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        --gradient-danger: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        --gradient-info: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --box-shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --border-radius: 0.75rem;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Header with Animated Background */
    .page-header {
        background: var(--gradient-primary);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        animation: slideInDown 0.6s ease-out;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image:
            radial-gradient(circle at 20% 80%, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 50%);
        animation: backgroundShift 8s ease-in-out infinite;
    }

    .page-header .container {
        position: relative;
        z-index: 2;
    }

    /* Modern Cards */
    .dashboard-card {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 1.5rem;
        transition: var(--transition);
        animation: fadeInUp 0.6s ease-out;
        position: relative;
        overflow: hidden;
    }

    .dashboard-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    .dashboard-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--box-shadow-lg);
    }

    .detail-section {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: var(--transition);
        animation: fadeInUp 0.6s ease-out;
        position: relative;
        overflow: hidden;
    }

    .detail-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        border-radius: var(--border-radius) var(--border-radius) 0 0;
    }

    .detail-section:hover {
        transform: translateY(-2px);
        box-shadow: var(--box-shadow-lg);
    }

    .detail-header {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
        position: relative;
    }

    .detail-header::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 60px;
        height: 2px;
        background: var(--gradient-primary);
        border-radius: 1px;
    }

    /* Status Badges with Gradients */
    .status-badge {
        padding: 0.75rem 1.5rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-align: center;
        position: relative;
        overflow: hidden;
        animation: shimmer 2s infinite;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
    }

    .status-pending {
        background: var(--gradient-warning);
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .status-approved {
        background: var(--gradient-success);
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .status-rejected {
        background: var(--gradient-danger);
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .status-processing {
        background: var(--gradient-info);
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .status-completed {
        background: var(--gradient-secondary);
        color: white;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    /* Evidence Photos */
    .evidence-photo {
        width: 8rem;
        height: 8rem;
        object-fit: cover;
        border-radius: var(--border-radius);
        border: 2px solid #e5e7eb;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: var(--box-shadow);
    }

    .evidence-photo:hover {
        transform: scale(1.05);
        box-shadow: var(--box-shadow-lg);
        border-color: var(--primary-color);
    }

    /* Buttons with Gradients */
    .btn-primary {
        background: var(--gradient-primary);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: var(--border-radius);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        transition: var(--transition);
        box-shadow: var(--box-shadow);
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--box-shadow-lg);
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    .btn-secondary {
        background: var(--gradient-secondary);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: var(--border-radius);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        transition: var(--transition);
        box-shadow: var(--box-shadow);
        border: none;
        cursor: pointer;
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: var(--box-shadow-lg);
    }

    /* Status Information Cards */
    .status-info-card {
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out;
    }

    .status-info-pending {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 1px solid #ffc107;
        color: #856404;
    }

    .status-info-approved {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border: 1px solid #28a745;
        color: #155724;
    }

    .status-info-rejected {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border: 1px solid #dc3545;
        color: #721c24;
    }

    .status-info-processing {
        background: linear-gradient(135deg, #cce7f0 0%, #b8daff 100%);
        border: 1px solid #17a2b8;
        color: #0c5460;
    }

    .status-info-completed {
        background: linear-gradient(135deg, #e2e3e5 0%, #d6d8db 100%);
        border: 1px solid #6c757d;
        color: #383d41;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes shimmer {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }

    @keyframes backgroundShift {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }

    /* Enhanced Info Boxes */
    .info-box {
        background: white;
        padding: 1rem;
        border-radius: var(--border-radius);
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        font-size: 0.875rem;
        color: var(--secondary-color);
    }

    .admin-note {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border: 1px solid #2196f3;
        color: #0d47a1;
        padding: 1rem;
        border-radius: var(--border-radius);
        font-size: 0.875rem;
        position: relative;
        overflow: hidden;
    }

    .admin-note::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--gradient-info);
    }

    /* Product Image Container */
    .product-image {
        width: 4rem;
        height: 4rem;
        object-fit: cover;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        transition: var(--transition);
    }

    .product-image:hover {
        transform: scale(1.1);
        box-shadow: var(--box-shadow-lg);
    }

    /* Modal Enhancements */
    #photoModal {
        backdrop-filter: blur(10px);
    }

    .modal-content {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-lg);
        overflow: hidden;
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-50px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    /* Responsive Grid */
    .status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    /* Section Dividers */
    .section-divider {
        height: 2px;
        background: var(--gradient-primary);
        border-radius: 1px;
        margin: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .section-divider::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
        animation: shimmerLine 2s infinite;
    }

    @keyframes shimmerLine {
        0% { left: -100%; }
        100% { left: 100%; }
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="page-header flex flex-wrap justify-between items-center mb-8">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Detail Refund</h1>
                    <p class="text-orange-100">ID: #{{ $refund->id }} - Pesanan: {{ $refund->pesanan->nomor_pesanan }}</p>
                </div>
                <div class="space-x-2">
                    <a href="{{ route('refunds.index') }}" style="background: white; color: var(--primary-color); padding: 0.5rem 1rem; border-radius: var(--border-radius); text-decoration: none; transition: var(--transition); display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 500;" onmouseover="this.style.background='#fff3e0'" onmouseout="this.style.background='white'">
                        <i class="fas fa-arrow-left"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="status-grid">
        <!-- Current Status -->
        <div class="dashboard-card">
            <h3 class="detail-header">Status Saat Ini</h3>
            <div class="text-center">
                <span class="status-badge status-{{ $refund->status }}">
                    @switch($refund->status)
                        @case('pending') Menunggu Review @break
                        @case('approved') Disetujui @break
                        @case('rejected') Ditolak @break
                        @case('processing') Sedang Diproses @break
                        @case('completed') Selesai @break
                        @default {{ ucfirst($refund->status) }}
                    @endswitch
                </span>
                <div style="margin-top: 0.75rem; font-size: 0.875rem; color: #6b7280;">
                    @if($refund->status === 'pending')
                        Permintaan refund Anda sedang direview oleh tim kami
                    @elseif($refund->status === 'approved')
                        Refund disetujui dan akan diproses segera
                    @elseif($refund->status === 'rejected')
                        Maaf, permintaan refund ditolak
                    @elseif($refund->status === 'processing')
                        Dana refund sedang diproses
                    @elseif($refund->status === 'completed')
                        Refund telah selesai diproses
                    @endif
                </div>
            </div>
        </div>

        <!-- Amount Info -->
        <div class="dashboard-card">
            <h3 class="detail-header">Jumlah Refund</h3>
            <div class="text-center">
                <div style="font-size: 2rem; font-weight: bold; color: var(--primary-color); margin-bottom: 0.5rem;">
                    Rp {{ number_format($refund->amount, 0, ',', '.') }}
                </div>
                <div style="font-size: 0.875rem; color: #6b7280;">
                    dari total pesanan<br>
                    Rp {{ number_format($refund->pesanan->total_harga, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="dashboard-card">
            <h3 class="detail-header">Timeline</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.875rem;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #6b7280;">Diajukan:</span>
                    <span style="font-weight: 500;">{{ $refund->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($refund->processed_at)
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #6b7280;">Diproses:</span>
                    <span style="font-weight: 500;">{{ $refund->processed_at->format('d/m/Y H:i') }}</span>
                </div>
                @endif
                @if($refund->completed_at)
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #6b7280;">Selesai:</span>
                    <span style="font-weight: 500;">{{ $refund->completed_at->format('d/m/Y H:i') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-orange-600 mb-2">
                    Rp {{ number_format($refund->amount, 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-600">
                    dari total pesanan<br>
                    Rp {{ number_format($refund->pesanan->total_harga, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="dashboard-card">
            <h3 class="detail-header">Timeline</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Diajukan:</span>
                    <span class="font-medium">{{ $refund->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($refund->processed_at)
                <div class="flex justify-between">
                    <span class="text-gray-600">Diproses:</span>
                    <span class="font-medium">{{ $refund->processed_at->format('d/m/Y H:i') }}</span>
                </div>
                @endif
                @if($refund->completed_at)
                <div class="flex justify-between">
                    <span class="text-gray-600">Selesai:</span>
                    <span class="font-medium">{{ $refund->completed_at->format('d/m/Y H:i') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Refund Details -->
    <div class="detail-grid">
        <!-- Reason & Description -->
        <div class="detail-section">
            <h3 class="detail-header">Detail Permintaan</h3>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--secondary-color); margin-bottom: 0.5rem;">Alasan Refund</label>
                    <div class="info-box">
                        @switch($refund->reason)
                            @case('defective') Produk Rusak/Cacat @break
                            @case('wrong_item') Barang yang Diterima Salah @break
                            @case('not_as_described') Tidak Sesuai Deskripsi @break
                            @case('dead_fish') Ikan Mati saat Diterima @break
                            @case('other') Lainnya @break
                            @default {{ ucfirst($refund->reason) }}
                        @endswitch
                    </div>
                </div>

                @if($refund->description)
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--secondary-color); margin-bottom: 0.5rem;">Deskripsi Masalah</label>
                    <div class="info-box">
                        {{ $refund->description }}
                    </div>
                </div>
                @endif

                @if($refund->refund_method)
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--secondary-color); margin-bottom: 0.5rem;">Metode Refund</label>
                    <div class="info-box">
                        @switch($refund->refund_method)
                            @case('bank_transfer') Transfer Bank @break
                            @case('wallet') E-Wallet @break
                            @case('store_credit') Kredit Toko @break
                            @default {{ ucfirst($refund->refund_method) }}
                        @endswitch
                    </div>
                </div>
                @endif

                @if($refund->admin_notes)
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--secondary-color); margin-bottom: 0.5rem;">Catatan dari Admin</label>
                    <div class="admin-note">
                        {{ $refund->admin_notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Evidence Photos -->
        <div class="detail-section">
            <h3 class="detail-header">Bukti Foto</h3>

            @if($refund->evidence_photos && count($refund->evidence_photos) > 0)
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                    @foreach($refund->evidence_photos as $photo)
                    <div style="position: relative;">
                        <img src="{{ asset('storage/' . $photo) }}"
                             alt="Bukti foto refund"
                             class="evidence-photo"
                             onclick="openPhotoModal('{{ asset('storage/' . $photo) }}')">
                        <div style="position: absolute; inset: 0; background: rgba(0, 0, 0, 0); transition: var(--transition); border-radius: var(--border-radius); display: flex; align-items: center; justify-content: center; cursor: pointer;"
                             onmouseover="this.style.background='rgba(0, 0, 0, 0.2)'; this.querySelector('i').style.opacity='1'"
                             onmouseout="this.style.background='rgba(0, 0, 0, 0)'; this.querySelector('i').style.opacity='0'">
                            <i class="fas fa-search-plus" style="color: white; opacity: 0; transition: var(--transition);"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 2rem 0; color: #6b7280;">
                    <i class="fas fa-camera" style="font-size: 2.5rem; margin-bottom: 1rem; color: #d1d5db;"></i>
                    <p>Tidak ada bukti foto yang diunggah.</p>
                </div>
            @endif
        </div>
    </div>
                             class="evidence-photo"
                             onclick="openPhotoModal('{{ asset('storage/' . $photo) }}')">
                        <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-search-plus text-white opacity-0 hover:opacity-100"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-camera text-4xl mb-4"></i>
                    <p>Tidak ada bukti foto yang diunggah.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Order Information -->
    <div class="detail-section">
        <h3 class="detail-header">Informasi Pesanan</h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
                <span style="font-size: 0.875rem; color: #6b7280;">Nomor Pesanan:</span>
                <div style="font-size: 0.875rem; font-weight: 500; color: var(--secondary-color);">{{ $refund->pesanan->nomor_pesanan }}</div>
            </div>
            <div>
                <span style="font-size: 0.875rem; color: #6b7280;">Tanggal Pesanan:</span>
                <div style="font-size: 0.875rem; font-weight: 500; color: var(--secondary-color);">{{ $refund->pesanan->created_at->format('d/m/Y') }}</div>
            </div>
            <div>
                <span style="font-size: 0.875rem; color: #6b7280;">Status Pesanan:</span>
                <div style="font-size: 0.875rem; font-weight: 500; color: var(--secondary-color);">{{ $refund->pesanan->status_pesanan }}</div>
            </div>
            <div>
                <span style="font-size: 0.875rem; color: #6b7280;">Total Harga:</span>
                <div style="font-size: 0.875rem; font-weight: 500; color: var(--secondary-color);">Rp {{ number_format($refund->pesanan->total_harga, 0, ',', '.') }}</div>
            </div>
        </div>

        @if($refund->pesanan->produk)
        <div style="border-top: 2px solid #e5e7eb; padding-top: 1.5rem;">
            <h4 style="font-size: 1rem; font-weight: 500; color: var(--secondary-color); margin-bottom: 1rem;">Produk</h4>
            <div style="display: flex; align-items: center; gap: 1rem;">
                @if($refund->pesanan->produk->gambar)
                <img src="{{ asset('storage/' . $refund->pesanan->produk->gambar) }}"
                     alt="{{ $refund->pesanan->produk->nama }}"
                     class="product-image">
                @endif
                <div>
                    <div style="font-size: 0.875rem; font-weight: 500; color: var(--secondary-color);">{{ $refund->pesanan->produk->nama }}</div>
                    <div style="font-size: 0.875rem; color: #6b7280;">{{ $refund->pesanan->produk->deskripsi }}</div>
                    <div style="font-size: 0.875rem; color: #6b7280;">Kuantitas: {{ $refund->pesanan->kuantitas }} ekor</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="section-divider"></div>

    <!-- Status Information -->
    @if($refund->status === 'pending')
    <div class="status-info-card status-info-pending">
        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
            <div style="flex-shrink: 0;">
                <i class="fas fa-clock" style="color: #f59e0b; font-size: 1.25rem;"></i>
            </div>
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 500; margin-bottom: 0.5rem;">Menunggu Review</h3>
                <p style="margin: 0;">
                    Permintaan refund Anda sedang direview oleh tim kami.
                    Proses review biasanya memakan waktu 1-2 hari kerja.
                    Kami akan menghubungi Anda segera setelah review selesai.
                </p>
            </div>
        </div>
    </div>
    @elseif($refund->status === 'approved')
    <div class="status-info-card status-info-approved">
        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
            <div style="flex-shrink: 0;">
                <i class="fas fa-check-circle" style="color: #10b981; font-size: 1.25rem;"></i>
            </div>
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 500; margin-bottom: 0.5rem;">Refund Disetujui</h3>
                <p style="margin: 0;">
                    Selamat! Permintaan refund Anda telah disetujui.
                    Dana akan segera diproses dan dikembalikan sesuai dengan metode yang dipilih.
                </p>
            </div>
        </div>
    </div>
    @elseif($refund->status === 'rejected')
    <div class="status-info-card status-info-rejected">
        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
            <div style="flex-shrink: 0;">
                <i class="fas fa-times-circle" style="color: #ef4444; font-size: 1.25rem;"></i>
            </div>
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 500; margin-bottom: 0.5rem;">Refund Ditolak</h3>
                <p style="margin: 0;">
                    Maaf, permintaan refund Anda ditolak.
                    Silakan lihat catatan admin di atas untuk informasi lebih lanjut.
                    Jika Anda memiliki pertanyaan, silakan hubungi customer service kami.
                </p>
            </div>
        </div>
    </div>
    @elseif($refund->status === 'processing')
    <div class="status-info-card status-info-processing">
        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
            <div style="flex-shrink: 0;">
                <i class="fas fa-cogs" style="color: #3b82f6; font-size: 1.25rem;"></i>
            </div>
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 500; margin-bottom: 0.5rem;">Sedang Diproses</h3>
                <p style="margin: 0;">
                    Dana refund Anda sedang diproses.
                    Proses ini biasanya memakan waktu 3-5 hari kerja tergantung metode refund yang dipilih.
                </p>
            </div>
        </div>
    </div>
    @elseif($refund->status === 'completed')
    <div class="status-info-card status-info-completed">
        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
            <div style="flex-shrink: 0;">
                <i class="fas fa-check-double" style="color: #6b7280; font-size: 1.25rem;"></i>
            </div>
            <div>
                <h3 style="font-size: 1.125rem; font-weight: 500; margin-bottom: 0.5rem;">Refund Selesai</h3>
                <p style="margin: 0;">
                    Refund telah selesai diproses. Dana sudah dikembalikan sesuai dengan metode yang dipilih.
                    Terima kasih telah berbelanja di WIB Fish Farm.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div style="text-align: center; display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="{{ route('pesanan.show', $refund->pesanan) }}" class="btn-primary">
            <i class="fas fa-box"></i>
            Lihat Detail Pesanan
        </a>

        <a href="{{ route('refunds.index') }}" class="btn-secondary">
            <i class="fas fa-list"></i>
            Lihat Semua Refund
        </a>
    </div>
                    Maaf, permintaan refund Anda ditolak.
                    Silakan lihat catatan admin di atas untuk informasi lebih lanjut.
                    Jika Anda memiliki pertanyaan, silakan hubungi customer service kami.
                </p>
            </div>
        </div>
    </div>
    @elseif($refund->status === 'processing')
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <i class="fas fa-cogs text-blue-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-medium text-blue-800 mb-2">Sedang Diproses</h3>
                <p class="text-blue-700">
                    Dana refund Anda sedang diproses.
                    Proses ini biasanya memakan waktu 3-5 hari kerja tergantung metode refund yang dipilih.
                </p>
            </div>
        </div>
    </div>
    @elseif($refund->status === 'completed')
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <i class="fas fa-check-double text-gray-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-800 mb-2">Refund Selesai</h3>
                <p class="text-gray-700">
                    Refund telah selesai diproses. Dana sudah dikembalikan sesuai dengan metode yang dipilih.
                    Terima kasih telah berbelanja di WIB Fish Farm.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="text-center space-x-4">
        <a href="{{ route('pesanan.show', $refund->pesanan) }}"
           class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition-colors inline-flex items-center">
            <i class="fas fa-box mr-2"></i>
            Lihat Detail Pesanan
        </a>

        <a href="{{ route('refunds.index') }}"
           class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors inline-flex items-center">
            <i class="fas fa-list mr-2"></i>
            Lihat Semua Refund
        </a>
    </div>
</div>

<!-- Photo Modal -->
<div id="photoModal" style="position: fixed; inset: 0; z-index: 50; overflow-y: auto; display: none;">
    <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem; text-align: center;">
        <div style="position: fixed; inset: 0; transition: opacity 0.3s;" aria-hidden="true">
            <div style="position: absolute; inset: 0; background: rgba(0, 0, 0, 0.75); backdrop-filter: blur(4px);" onclick="closePhotoModal()"></div>
        </div>
        <div class="modal-content" style="display: inline-block; align-items: center; background: white; overflow: hidden; transform: translateY(0); transition: var(--transition); max-width: 64rem; width: 100%; position: relative;">
            <div style="background: white;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.125rem; font-weight: 500; color: var(--secondary-color); margin: 0;">Bukti Foto</h3>
                    <button onclick="closePhotoModal()" style="color: #9ca3af; background: none; border: none; cursor: pointer; transition: var(--transition);" onmouseover="this.style.color='#6b7280'" onmouseout="this.style.color='#9ca3af'">
                        <i class="fas fa-times" style="font-size: 1.25rem;"></i>
                    </button>
                </div>
                <div style="padding: 1rem;">
                    <img id="modalPhoto" src="" alt="Bukti foto" style="width: 100%; height: auto; max-height: 24rem; object-fit: contain; border-radius: var(--border-radius);">
                </div>
                <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 0.75rem 1rem; display: flex; justify-content: flex-end;">
                    <button type="button" class="btn-secondary" onclick="closePhotoModal()">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
function openPhotoModal(photoUrl) {
    document.getElementById('modalPhoto').src = photoUrl;
    const modal = document.getElementById('photoModal');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closePhotoModal() {
    const modal = document.getElementById('photoModal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

// Close modal when clicking outside
document.getElementById('photoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePhotoModal();
    }
});

// Escape key closes modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhotoModal();
    }
});
</script>
@endpush
