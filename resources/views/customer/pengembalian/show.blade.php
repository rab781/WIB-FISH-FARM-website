@extends('layouts.app')

@section('title', 'Detail Refund')

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
        --info-color: #3b82f6;
        --border-color: #e2e8f0;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --bg-light: #f8fafc;
        --shadow-light: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-medium: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-large: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    .gradient-bg {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%);
        min-height: 100vh;
        position: relative;
    }

    .gradient-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f97316' fill-opacity='0.03'%3E%3Ccircle cx='40' cy='40' r='2'/%3E%3Ccircle cx='20' cy='20' r='1'/%3E%3Ccircle cx='60' cy='60' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        animation: float 30s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(180deg); }
    }

    .hero-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: 24px;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-xl);
    }

    .hero-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Ccircle cx='12' cy='12' r='2'/%3E%3Ccircle cx='48' cy='48' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        animation: heroFloat 25s ease-in-out infinite;
    }

    @keyframes heroFloat {
        0%, 100% { transform: translateX(0px) translateY(0px); }
        33% { transform: translateX(-20px) translateY(-10px); }
        66% { transform: translateX(20px) translateY(10px); }
    }

    .modern-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-light);
        margin-bottom: 2rem;
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
        transform: translateY(-4px);
        box-shadow: var(--shadow-large);
        border-color: var(--primary-light);
    }

    .modern-card:hover::before {
        opacity: 1;
    }

    .detail-header {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .detail-header i {
        color: var(--primary-color);
        font-size: 1.5rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-medium);
    }

    .status-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .status-badge:hover::before {
        left: 100%;
    }

    .status-pending {
        background: linear-gradient(135deg, #fef3c7, #fbbf24);
        color: #92400e;
    }

    .status-approved {
        background: linear-gradient(135deg, #d1fae5, #10b981);
        color: #065f46;
    }

    .status-rejected {
        background: linear-gradient(135deg, #fee2e2, #ef4444);
        color: #991b1b;
    }

    .status-processed {
        background: linear-gradient(135deg, #dbeafe, #3b82f6);
        color: #1e40af;
    }

    .status-completed {
        background: linear-gradient(135deg, #e0e7ff, #8b5cf6);
        color: #5b21b6;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: var(--bg-light);
        border-radius: 12px;
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }

    .info-row:hover {
        background: white;
        border-color: var(--primary-light);
        transform: translateX(4px);
    }

    .info-label {
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .info-value {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .timeline-container {
        position: relative;
        padding-left: 2rem;
    }

    .timeline-line {
        position: absolute;
        left: 1rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary-color), var(--primary-light));
        border-radius: 2px;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        box-shadow: var(--shadow-light);
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2.25rem;
        top: 1.5rem;
        width: 1rem;
        height: 1rem;
        background: var(--primary-color);
        border: 3px solid white;
        border-radius: 50%;
        box-shadow: 0 0 0 3px var(--primary-light);
        z-index: 2;
    }

    .timeline-item:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
        border-color: var(--primary-light);
    }

    .timeline-item.completed::before {
        background: var(--success-color);
        box-shadow: 0 0 0 3px #d1fae5;
    }

    .timeline-item.pending::before {
        background: var(--warning-color);
        box-shadow: 0 0 0 3px #fef3c7;
    }

    .timeline-title {
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 1.125rem;
    }

    .timeline-date {
        font-size: 0.875rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .timeline-description {
        margin-top: 0.75rem;
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.6;
    }

    .evidence-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .evidence-photo {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid var(--border-color);
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-light);
    }

    .evidence-photo:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-large);
        border-color: var(--primary-color);
    }

    .btn-modern {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        font-size: 0.875rem;
        box-shadow: var(--shadow-medium);
    }

    .btn-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-large);
        text-decoration: none;
        color: white;
    }

    .btn-modern:hover::before {
        left: 100%;
    }

    .btn-secondary {
        background: linear-gradient(145deg, #f1f5f9, #e2e8f0);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
    }

    .btn-secondary:hover {
        background: linear-gradient(145deg, #e2e8f0, #cbd5e1);
        color: var(--text-primary);
    }

    .amount-display {
        background: linear-gradient(135deg, #fff7ed, #fed7aa);
        border: 2px solid var(--primary-light);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .amount-display::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(249, 115, 22, 0.1) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }

    .amount-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .amount-label {
        color: var(--text-secondary);
        font-weight: 600;
        position: relative;
        z-index: 1;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .slide-up {
        animation: slideUp 0.4s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
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
<div class="gradient-bg">
    <div class="container mx-auto px-4 py-8">
        <!-- Hero Header -->
        <div class="hero-header p-8 mb-8 fade-in">
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-white mb-2">Detail Refund</h1>
                        <p class="text-orange-100 text-lg">ID: #{{ $refund->id_pengembalian }} • Pesanan: #{{ $refund->pesanan->nomor_pesanan }}</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('pengembalian.index') }}" class="btn-modern btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status & Amount -->
                <div class="modern-card slide-up">
                    <div class="detail-header">
                        <i class="fas fa-info-circle"></i>
                        Status & Jumlah Refund
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status -->
                        <div class="text-center">
                            @php
                                $statusClasses = [
                                    'Menunggu Review' => 'status-pending',
                                    'Dalam Review' => 'status-processed',
                                    'Disetujui' => 'status-approved',
                                    'Ditolak' => 'status-rejected',
                                    'Dana Dikembalikan' => 'status-approved',
                                    'Selesai' => 'status-completed'
                                ];
                                $statusClass = $statusClasses[$refund->status_pengembalian] ?? 'status-pending';
                            @endphp

                            <div class="status-badge {{ $statusClass }} mb-4">
                                @switch($refund->status_pengembalian)
                                    @case('Menunggu Review')
                                        <i class="fas fa-clock mr-2"></i>Menunggu Review
                                        @break
                                    @case('Dalam Review')
                                        <i class="fas fa-search mr-2"></i>Dalam Review
                                        @break
                                    @case('Disetujui')
                                        <i class="fas fa-check mr-2"></i>Disetujui
                                        @break
                                    @case('Ditolak')
                                        <i class="fas fa-times mr-2"></i>Ditolak
                                        @break
                                    @case('Dana Dikembalikan')
                                        <i class="fas fa-money-bill mr-2"></i>Dana Dikembalikan
                                        @break
                                    @case('Selesai')
                                        <i class="fas fa-star mr-2"></i>Selesai
                                        @break
                                    @default
                                        <i class="fas fa-question mr-2"></i>{{ $refund->status_pengembalian }}
                                @endswitch
                            </div>
                            <p class="text-sm text-gray-600">
                                @if($refund->status_pengembalian === 'Menunggu Review')
                                    Permintaan refund sedang direview
                                @elseif($refund->status_pengembalian === 'Dalam Review')
                                    Tim kami sedang memproses refund Anda
                                @elseif($refund->status_pengembalian === 'Disetujui')
                                    Refund telah disetujui dan akan diproses
                                @elseif($refund->status_pengembalian === 'Ditolak')
                                    Refund tidak memenuhi syarat dan ketentuan
                                @elseif($refund->status_pengembalian === 'Dana Dikembalikan')
                                    Dana telah dikembalikan ke rekening Anda
                                @else
                                    Status refund: {{ $refund->status_pengembalian }}
                                @endif
                            </p>
                        </div>

                        <!-- Amount -->
                        <div class="amount-display">
                            <div class="amount-value">
                                Rp {{ number_format($refund->jumlah_klaim, 0, ',', '.') }}
                            </div>
                            <div class="amount-label">
                                Jumlah Refund
                            </div>
                            <div class="text-xs text-gray-500 mt-2">
                                dari total pesanan Rp {{ number_format($refund->pesanan->total_harga, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Refund Details -->
                <div class="modern-card slide-up">
                    <div class="detail-header">
                        <i class="fas fa-clipboard-list"></i>
                        Detail Permintaan
                    </div>

                    <div class="space-y-3">
                        <div class="info-row">
                            <span class="info-label">Jenis Keluhan</span>
                            <span class="info-value">
                                @switch($refund->jenis_keluhan)
                                    @case('Barang Rusak') Produk Rusak/Cacat @break
                                    @case('Barang Tidak Sesuai') Barang yang Diterima Salah @break
                                    @case('Barang Kurang') Tidak Sesuai Deskripsi @break
                                    @case('Kualitas Buruk') Ikan Mati saat Diterima @break
                                    @case('Lainnya') Lainnya @break
                                    @default {{ $refund->jenis_keluhan }}
                                @endswitch
                            </span>
                        </div>

                        @if($refund->deskripsi_masalah)
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Masalah</label>
                            <p class="text-gray-900 leading-relaxed">{{ $refund->deskripsi_masalah }}</p>
                        </div>
                        @endif

                        @if($refund->metode_refund)
                        <div class="info-row">
                            <span class="info-label">Metode Refund</span>
                            <span class="info-value">
                                @if($refund->metode_refund === 'bank_transfer')
                                    <i class="fas fa-university mr-1"></i>Transfer Bank
                                @else
                                    <i class="fas fa-mobile-alt mr-1"></i>E-Wallet
                                @endif
                            </span>
                        </div>

                        @if($refund->metode_refund === 'bank_transfer')
                            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                                <h4 class="font-semibold text-blue-900 mb-3">Detail Rekening Bank</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <span class="text-blue-700 font-medium">Bank:</span>
                                        <span class="text-blue-900">{{ $refund->nama_bank ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-blue-700 font-medium">No. Rekening:</span>
                                        <span class="text-blue-900">{{ $refund->nomor_rekening ?? '-' }}</span>
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="text-blue-700 font-medium">Nama Pemilik:</span>
                                        <span class="text-blue-900">{{ $refund->nama_pemilik_rekening ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        @elseif($refund->metode_refund === 'e_wallet')
                            <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                                <h4 class="font-semibold text-green-900 mb-3">Detail E-Wallet</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                    <div>
                                        <span class="text-green-700 font-medium">E-Wallet:</span>
                                        <span class="text-green-900">{{ $refund->nama_ewallet ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-green-700 font-medium">Nomor:</span>
                                        <span class="text-green-900">{{ $refund->nomor_ewallet ?? '-' }}</span>
                                    </div>
                                    <div class="md:col-span-2">
                                        <span class="text-green-700 font-medium">Nama Pemilik:</span>
                                        <span class="text-green-900">{{ $refund->nama_pemilik_ewallet ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @endif
                    </div>
                </div>

                <!-- Evidence Photos -->
                @if($refund->foto_bukti)
                <div class="modern-card slide-up">
                    <div class="detail-header">
                        <i class="fas fa-camera"></i>
                        Foto Bukti
                    </div>

                    <div class="evidence-grid">
                        @php
                            $fotoBukti = is_string($refund->foto_bukti) ? json_decode($refund->foto_bukti) : $refund->foto_bukti;
                        @endphp
                        @foreach($fotoBukti as $index => $foto)
                            <img src="{{ asset($foto) }}"
                                 alt="Bukti {{ $index + 1 }}"
                                 class="evidence-photo"
                                 onclick="openPhotoModal('{{ asset($foto) }}', 'Bukti {{ $index + 1 }}')">
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Admin Notes -->
                @if($refund->catatan_admin)
                <div class="modern-card slide-up">
                    <div class="detail-header">
                        <i class="fas fa-sticky-note"></i>
                        Catatan Admin
                    </div>

                    <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                        <p class="text-yellow-900 leading-relaxed">{{ $refund->catatan_admin }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Timeline -->
                <div class="modern-card slide-up">
                    <div class="detail-header">
                        <i class="fas fa-history"></i>
                        Timeline Proses
                    </div>

                    <div class="timeline-container">
                        <div class="timeline-line"></div>

                        <!-- Submitted -->
                        <div class="timeline-item completed">
                            <div class="timeline-title">Pengajuan Diterima</div>
                            <div class="timeline-date">{{ $refund->created_at->format('d M Y, H:i') }}</div>
                            <div class="timeline-description">
                                Permintaan refund telah berhasil diajukan dan menunggu review dari tim kami.
                            </div>
                        </div>

                        <!-- Review Process -->
                        @if($refund->tanggal_review)
                        <div class="timeline-item {{ in_array($refund->status_pengembalian, ['Disetujui', 'Ditolak', 'Dana Dikembalikan', 'Selesai']) ? 'completed' : 'pending' }}">
                            <div class="timeline-title">
                                @if($refund->status_pengembalian === 'Ditolak')
                                    Refund Ditolak
                                @else
                                    Review Selesai
                                @endif
                            </div>
                            <div class="timeline-date">{{ $refund->tanggal_review->format('d M Y, H:i') }}</div>
                            <div class="timeline-description">
                                @if($refund->status_pengembalian === 'Ditolak')
                                    Permintaan refund tidak memenuhi syarat dan telah ditolak.
                                @else
                                    Tim telah menyelesaikan review dan menyetujui permintaan refund Anda.
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="timeline-item pending">
                            <div class="timeline-title">Sedang Direview</div>
                            <div class="timeline-date">Menunggu proses</div>
                            <div class="timeline-description">
                                Tim kami sedang meninjau permintaan refund dan bukti yang Anda berikan.
                            </div>
                        </div>
                        @endif

                        <!-- Refund Processed -->
                        @if($refund->tanggal_pengembalian_dana)
                        <div class="timeline-item completed">
                            <div class="timeline-title">Dana Dikembalikan</div>
                            <div class="timeline-date">{{ $refund->tanggal_pengembalian_dana->format('d M Y, H:i') }}</div>
                            <div class="timeline-description">
                                Dana refund telah berhasil dikembalikan ke rekening/e-wallet Anda.
                            </div>
                        </div>
                        @elseif($refund->status_pengembalian === 'Disetujui')
                        <div class="timeline-item pending">
                            <div class="timeline-title">Memproses Pengembalian</div>
                            <div class="timeline-date">Dalam proses</div>
                            <div class="timeline-description">
                                Dana sedang diproses untuk dikembalikan ke rekening/e-wallet Anda.
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Order Information -->
                <div class="modern-card slide-up">
                    <div class="detail-header">
                        <i class="fas fa-shopping-cart"></i>
                        Informasi Pesanan
                    </div>

                    <div class="space-y-3">
                        <div class="info-row">
                            <span class="info-label">Nomor Pesanan</span>
                            <span class="info-value">#{{ $refund->pesanan->nomor_pesanan }}</span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Tanggal Pesanan</span>
                            <span class="info-value">{{ $refund->pesanan->created_at->format('d M Y') }}</span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Total Pesanan</span>
                            <span class="info-value">Rp {{ number_format($refund->pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>

                        <div class="info-row">
                            <span class="info-label">Status Pesanan</span>
                            <span class="info-value">{{ $refund->pesanan->status }}</span>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('pesanan.show', $refund->pesanan->id_pesanan) }}" class="btn-modern w-full justify-center">
                                <i class="fas fa-eye mr-2"></i>
                                Lihat Detail Pesanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

<!-- Photo Modal -->
<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closePhotoModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <i class="fas fa-times text-2xl"></i>
        </button>
        <img id="modalPhoto" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" src="" alt="Bukti">
        <div id="modalCaption" class="text-center text-white mt-4 font-semibold"></div>
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize animations
    const cards = document.querySelectorAll('.slide-up');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

function openPhotoModal(photoUrl, caption = '') {
    document.getElementById('modalPhoto').src = photoUrl;
    document.getElementById('modalCaption').textContent = caption;
    const modal = document.getElementById('photoModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closePhotoModal() {
    const modal = document.getElementById('photoModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
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

// Add smooth scroll for timeline
document.addEventListener('scroll', function() {
    const timelineItems = document.querySelectorAll('.timeline-item');
    const scrollPos = window.scrollY + window.innerHeight * 0.8;

    timelineItems.forEach(item => {
        if (item.offsetTop < scrollPos) {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }
    });
});
</script>
@endpush

@endsection
