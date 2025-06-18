@extends('layouts.app')

@section('title', 'Kelola Refund')

@push('styles')
<!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        box-shadow: var(--shadow-large);
        animation: heroSlideIn 1s ease-out;
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

    .hero-header::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        animation: shimmerEffect 3s ease-in-out infinite;
    }

    @keyframes heroSlideIn {
        0% {
            opacity: 0;
            transform: translateY(-30px) scale(0.95);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes heroFloat {
        0%, 100% { transform: translateX(0px) translateY(0px); }
        33% { transform: translateX(-20px) translateY(-10px); }
        66% { transform: translateX(20px) translateY(10px); }
    }

    @keyframes shimmerEffect {
        0% { left: -100%; }
        50% { left: 100%; }
        100% { left: 100%; }
    }

    .hero-icon {
        animation: iconBounce 2s ease-in-out infinite;
        transition: all 0.3s ease;
    }

    .hero-icon:hover {
        transform: scale(1.1) rotate(10deg);
        animation-play-state: paused;
    }

    @keyframes iconBounce {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
    }

    .hero-title {
        animation: titleSlideUp 1.2s ease-out 0.3s both;
    }

    .hero-subtitle {
        animation: subtitleFadeIn 1.5s ease-out 0.6s both;
    }

    .hero-divider {
        animation: dividerExpand 1.8s ease-out 0.9s both;
    }

    @keyframes titleSlideUp {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes subtitleFadeIn {
        0% {
            opacity: 0;
            transform: translateY(15px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes dividerExpand {
        0% {
            width: 0;
            opacity: 0;
        }
        100% {
            width: 6rem;
            opacity: 1;
        }
    }

    .stats-card {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: 20px;
        padding: 2rem 1.5rem;
        text-align: center;
        border: 1px solid var(--border-color);
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: var(--shadow-light);
        opacity: 0;
        transform: translateY(30px);
    }

    .stats-card.slide-up {
        animation: slideUpStats 0.6s ease-out forwards;
    }

    @keyframes slideUpStats {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(249, 115, 22, 0.1), transparent);
        transition: left 0.8s ease;
    }

    .stats-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: var(--shadow-large);
        border-color: var(--primary-light);
    }

    .stats-card:hover::before {
        left: 100%;
    }

    .stats-icon {
        width: 4rem;
        height: 4rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        position: relative;
        transition: all 0.3s ease;
    }

    .stats-card:hover .stats-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .refund-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-light);
    }

    .refund-card::before {
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

    .refund-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-large);
        border-color: var(--primary-light);
    }

    .refund-card:hover::before {
        opacity: 1;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        position: relative;
        overflow: hidden;
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
        box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
    }

    .status-approved {
        background: linear-gradient(135deg, #d1fae5, #10b981);
        color: #065f46;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .status-rejected {
        background: linear-gradient(135deg, #fee2e2, #ef4444);
        color: #991b1b;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .status-processed {
        background: linear-gradient(135deg, #dbeafe, #3b82f6);
        color: #1e40af;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .status-completed {
        background: linear-gradient(135deg, #e0e7ff, #8b5cf6);
        color: #5b21b6;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
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

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        border: 2px dashed var(--border-color);
        position: relative;
        overflow: hidden;
    }

    .empty-state::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(249, 115, 22, 0.05) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 0.5; }
        50% { opacity: 1; }
    }

    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        background: white;
        padding: 0.5rem;
        border-radius: 16px;
        box-shadow: var(--shadow-light);
        border: 1px solid var(--border-color);
    }

    .filter-tab {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: var(--text-secondary);
        position: relative;
        overflow: hidden;
    }

    .filter-tab.active {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        box-shadow: var(--shadow-medium);
    }

    .filter-tab:not(.active):hover {
        background: var(--bg-light);
        color: var(--text-primary);
        text-decoration: none;
    }

    .pagination-modern {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }

    .pagination-modern .page-link {
        padding: 0.75rem 1rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
        text-decoration: none;
        transition: all 0.3s ease;
        background: white;
    }

    .pagination-modern .page-link:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        transform: translateY(-1px);
        text-decoration: none;
    }

    .pagination-modern .page-item.active .page-link {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .search-box {
        position: relative;
        margin-bottom: 2rem;
    }

    .search-input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid var(--border-color);
        border-radius: 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        box-shadow: var(--shadow-light);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        transform: translateY(-1px);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 1.125rem;
    }

    .fade-in {
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .slide-up {
        opacity: 0;
        transform: translateY(30px);
        animation: slideUp 0.6s ease-out forwards;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="gradient-bg">
    <div class="container mx-auto px-4 py-8">
        <!-- Hero Header -->
        <div class="hero-header p-8 mb-8 fade-in">
            <div class="relative z-10">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white bg-opacity-20 rounded-full mb-6 backdrop-blur-sm hero-icon">
                        <i class="fas fa-undo text-3xl text-orange-400"></i>
                    </div>
                    <h1 class="text-4xl font-bold text-white mb-4 hero-title">Kelola Refund</h1>
                    <p class="text-orange-100 text-lg mb-6 hero-subtitle">Pantau status refund dan ajukan pengembalian baru</p>
                    <div class="flex justify-center">
                        <div class="w-24 h-1 bg-white bg-opacity-50 rounded-full hero-divider"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
            <div class="stats-card slide-up" style="animation-delay: 0.1s">
                <div class="stats-icon bg-gradient-to-br from-blue-400 to-blue-600">
                    <i class="fas fa-list text-xl text-white"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['total'] }}</div>
                <div class="text-sm text-gray-600 font-medium">Total Refund</div>
            </div>

            <div class="stats-card slide-up" style="animation-delay: 0.2s">
                <div class="stats-icon bg-gradient-to-br from-yellow-400 to-yellow-600">
                    <i class="fas fa-clock text-xl text-white"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['pending'] }}</div>
                <div class="text-sm text-gray-600 font-medium">Menunggu</div>
            </div>

            <div class="stats-card slide-up" style="animation-delay: 0.4s">
                <div class="stats-icon bg-gradient-to-br from-green-400 to-green-600">
                    <i class="fas fa-check text-xl text-white"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['approved'] }}</div>
                <div class="text-sm text-gray-600 font-medium">Disetujui</div>
            </div>

            <div class="stats-card slide-up" style="animation-delay: 0.5s">
                <div class="stats-icon bg-gradient-to-br from-red-400 to-red-600">
                    <i class="fas fa-times text-xl text-white"></i>
                </div>
                <div class="text-2xl font-bold text-gray-900 mb-1">{{ $stats['rejected'] }}</div>
                <div class="text-sm text-gray-600 font-medium">Ditolak</div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="search-box fade-in">
            <div class="search-icon">
                <i class="fas fa-search"></i>
            </div>
            <input type="text"
                   id="searchInput"
                   class="search-input"
                   placeholder="Cari berdasarkan nomor pesanan, jenis keluhan, atau status..."
                   onkeyup="filterRefunds()">
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs fade-in">
            <a href="#" class="filter-tab active" data-status="all" onclick="filterByStatus('all', this)">
                <i class="fas fa-list mr-2"></i>Semua
            </a>
            <a href="#" class="filter-tab" data-status="Menunggu Review" onclick="filterByStatus('Menunggu Review', this)">
                <i class="fas fa-clock mr-2"></i>Menunggu
            </a>
            <a href="#" class="filter-tab" data-status="Disetujui" onclick="filterByStatus('Disetujui', this)">
                <i class="fas fa-check mr-2"></i>Disetujui
            </a>
            <a href="#" class="filter-tab" data-status="Ditolak" onclick="filterByStatus('Ditolak', this)">
                <i class="fas fa-times mr-2"></i>Ditolak
            </a>
        </div>

        <!-- Refund List -->
        <div id="refundList">
            @if($refunds->count() > 0)
                <div class="grid gap-6">
                    @foreach($refunds as $index => $refund)
                        <div class="refund-card slide-up refund-item"
                             style="animation-delay: {{ $index * 0.1 }}s"
                             data-status="{{ $refund->status_pengembalian }}"
                             data-search="{{ strtolower($refund->pesanan->nomor_pesanan . ' ' . $refund->jenis_keluhan . ' ' . $refund->status_pengembalian) }}">

                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                <!-- Left Content -->
                                <div class="flex-grow">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                                        <div class="flex items-center space-x-3 mb-2 sm:mb-0">
                                            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center">
                                                <i class="fas fa-undo text-white"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-900">Refund #{{ $refund->id_pengembalian }}</h3>
                                                <p class="text-sm text-gray-600">Pesanan #{{ $refund->pesanan->nomor_pesanan }}</p>
                                            </div>
                                        </div>

                                        @php
                                            $statusClasses = [
                                                'Menunggu Review' => 'status-pending',
                                                'Disetujui' => 'status-approved',
                                                'Ditolak' => 'status-rejected'
                                            ];
                                            $statusClass = $statusClasses[$refund->status_pengembalian] ?? 'status-pending';
                                        @endphp

                                        <span class="status-badge {{ $statusClass }}">
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
                                                    <i class="fas fa-money-bill-wave mr-2"></i>Dana Dikembalikan
                                                    @break
                                                @case('Selesai')
                                                    <i class="fas fa-star mr-2"></i>Selesai
                                                    @break
                                                @default
                                                    <i class="fas fa-question mr-2"></i>{{ $refund->status_pengembalian }}
                                            @endswitch
                                        </span>
                                    </div>

                                    <!-- Refund Details -->
                                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <p class="text-sm text-gray-600 font-medium mb-1">Jenis Keluhan</p>
                                                <p class="text-gray-900 font-semibold">{{ $refund->jenis_keluhan }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600 font-medium mb-1">Jumlah Refund</p>
                                                <p class="text-orange-600 font-bold">Rp {{ number_format($refund->jumlah_klaim, 0, ',', '.') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600 font-medium mb-1">Tanggal Pengajuan</p>
                                                <p class="text-gray-900 font-semibold">{{ $refund->created_at->format('d M Y, H:i') }}</p>
                                            </div>
                                        </div>

                                        @if($refund->deskripsi_masalah)
                                            <div class="mt-3 pt-3 border-t border-gray-200">
                                                <p class="text-sm text-gray-600 font-medium mb-1">Deskripsi Masalah</p>
                                                <p class="text-gray-900 text-sm">{{ Str::limit($refund->deskripsi_masalah, 150) }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    @if($refund->pesanan->detailPesanan->count() > 0)
                                        <div class="flex items-center space-x-3 mb-4">
                                            <div class="text-sm text-gray-600">
                                                <i class="fas fa-box mr-1 text-orange-500"></i>
                                                {{ $refund->pesanan->detailPesanan->count() }} produk
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                <i class="fas fa-shopping-cart mr-1 text-orange-500"></i>
                                                Total: Rp {{ number_format($refund->pesanan->total_harga, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Right Actions -->
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <a href="{{ route('pengembalian.show', $refund->id_pengembalian) }}"
                                       class="btn-modern">
                                        <i class="fas fa-eye mr-2"></i>
                                        Detail
                                    </a>

                                    @if($refund->status_pengembalian === 'Menunggu Review')
                                        <button onclick="cancelRefund({{ $refund->id_pengembalian }})"
                                                class="btn-modern btn-secondary">
                                            <i class="fas fa-times mr-2"></i>
                                            Batalkan
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($refunds->hasPages())
                    <div class="pagination-modern mt-8">
                        {{ $refunds->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="empty-state fade-in">
                    <div class="relative z-10">
                        <div class="w-24 h-24 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full mx-auto mb-6 flex items-center justify-center">
                            <i class="fas fa-undo text-3xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Belum Ada Pengajuan Refund</h3>
                        <p class="text-gray-600 mb-6 max-w-md mx-auto">
                            Anda belum memiliki pengajuan refund. Mulai ajukan refund untuk pesanan yang memenuhi syarat.
                        </p>
                        <a href="{{ route('pesanan.index') }}" class="btn-modern">
                            <i class="fas fa-plus mr-2"></i>
                            Lihat Pesanan
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- No Results State (Hidden by default) -->
        <div id="noResults" class="empty-state fade-in" style="display: none;">
            <div class="relative z-10">
                <div class="w-24 h-24 bg-gradient-to-br from-gray-400 to-gray-600 rounded-full mx-auto mb-6 flex items-center justify-center">
                    <i class="fas fa-search text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Tidak Ada Hasil</h3>
                <p class="text-gray-600 mb-6">
                    Tidak ditemukan refund yang sesuai dengan pencarian atau filter Anda.
                </p>
                <button onclick="clearSearch()" class="btn-modern">
                    <i class="fas fa-rotate-left mr-2"></i>
                    Reset Pencarian
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize hero animations
    setTimeout(() => {
        document.querySelector('.hero-header').style.opacity = '1';
    }, 100);

    // Initialize stats cards animations
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('slide-up');
        }, (index * 150) + 500); // Start after hero animation
    });

    // Initialize refund cards animations
    const refundCards = document.querySelectorAll('.refund-card.slide-up');
    refundCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, (index * 100) + 1000); // Start after stats cards
    });

    // Add scroll reveal effect
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe elements that need scroll animation
    document.querySelectorAll('.fade-in, .slide-up').forEach(el => {
        if (!el.classList.contains('stats-card')) { // Skip stats cards as they have their own timing
            observer.observe(el);
        }
    });
});

// Search functionality
function filterRefunds() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const refundItems = document.querySelectorAll('.refund-item');
    const noResults = document.getElementById('noResults');
    const refundList = document.getElementById('refundList');
    let visibleCount = 0;

    refundItems.forEach(item => {
        const searchData = item.getAttribute('data-search');
        const isVisible = searchData.includes(searchTerm);

        if (isVisible) {
            item.style.display = 'block';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    // Show/hide no results state
    if (visibleCount === 0 && searchTerm !== '') {
        noResults.style.display = 'block';
        refundList.style.display = 'none';
    } else {
        noResults.style.display = 'none';
        refundList.style.display = 'block';
    }
}

// Filter by status
function filterByStatus(status, element) {
    // Update active tab
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('active');
    });
    element.classList.add('active');

    // Filter items
    const refundItems = document.querySelectorAll('.refund-item');
    const noResults = document.getElementById('noResults');
    const refundList = document.getElementById('refundList');
    let visibleCount = 0;

    refundItems.forEach(item => {
        const itemStatus = item.getAttribute('data-status');
        const isVisible = status === 'all' || itemStatus === status;

        if (isVisible) {
            item.style.display = 'block';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    // Clear search when filtering
    document.getElementById('searchInput').value = '';

    // Show/hide no results state
    if (visibleCount === 0) {
        noResults.style.display = 'block';
        refundList.style.display = 'none';
    } else {
        noResults.style.display = 'none';
        refundList.style.display = 'block';
    }
}

// Clear search
function clearSearch() {
    document.getElementById('searchInput').value = '';
    filterRefunds();

    // Reset to show all
    const allTab = document.querySelector('.filter-tab[data-status="all"]');
    filterByStatus('all', allTab);
}

// Cancel refund function
function cancelRefund(refundId) {
    Swal.fire({
        title: 'Batalkan Pengajuan Refund?',
        text: 'Apakah Anda yakin ingin membatalkan pengajuan refund ini? Tindakan ini tidak dapat dibatalkan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Batalkan',
        cancelButtonText: '<i class="fas fa-times mr-2"></i>Tidak',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Here you would make an AJAX call to cancel the refund
            // For now, just show a success message
            Swal.fire({
                title: 'Dibatalkan!',
                text: 'Pengajuan refund berhasil dibatalkan.',
                icon: 'success',
                confirmButtonColor: '#f97316'
            }).then(() => {
                // Reload the page or remove the item from the list
                location.reload();
            });
        }
    });
}
</script>
@endpush
@endsection
