@extends('admin.layouts.app')

@section('title', 'Manajemen Pengembalian')

@push('styles')
{{-- Modern CSS Design System --}}
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

    /* Modern Cards */
    .card-modern {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        transition: var(--transition);
        animation: fadeInUp 0.6s ease-out;
        position: relative;
    }

    .card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
    }

    .card-modern:hover {
        transform: translateY(-2px);
        box-shadow: var(--box-shadow-lg);
    }

    .card-header-modern {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        position: relative;
    }

    /* Status Cards */
    .stat-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--box-shadow-lg);
    }

    .stat-card.border-blue-500::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--gradient-info);
    }

    .stat-card.border-yellow-500::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--gradient-warning);
    }

    .stat-card.border-indigo-500::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    }

    .stat-card.border-green-500::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--gradient-success);
    }

    .stat-card.border-gray-500::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--gradient-secondary);
    }

    .stat-card.border-red-500::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--gradient-danger);
    }

    /* Status Badges with Gradients */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 1.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-align: center;
        position: relative;
        overflow: hidden;
        animation: shimmer 2s infinite;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        display: inline-block;
        margin: 0.125rem;
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

    /* Table Enhancements */
    .table-header-custom {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: var(--secondary-color);
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.05em;
    }

    .table-row-hover:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        transition: var(--transition);
    }

    /* Form Inputs */
    .filter-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: var(--border-radius);
        transition: var(--transition);
        font-size: 0.875rem;
    }

    .filter-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.1);
    }

    /* Buttons with Gradients */
    .btn-primary-custom {
        background: var(--gradient-primary);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: var(--border-radius);
        font-weight: 600;
        transition: var(--transition);
        box-shadow: var(--box-shadow);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        position: relative;
        overflow: hidden;
    }

    .btn-primary-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: var(--box-shadow-lg);
    }

    .btn-primary-custom:hover::before {
        left: 100%;
    }

    .btn-secondary-custom {
        background: var(--gradient-secondary);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: var(--border-radius);
        font-weight: 600;
        transition: var(--transition);
        box-shadow: var(--box-shadow);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-secondary-custom:hover {
        transform: translateY(-2px);
        box-shadow: var(--box-shadow-lg);
    }

    /* Action Buttons */
    .btn-action-outline {
        padding: 0.5rem;
        border-radius: var(--border-radius);
        border: 1px solid;
        font-size: 0.875rem;
        font-weight: 500;
        transition: var(--transition);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
    }

    .btn-action-outline-primary {
        border-color: #3b82f6;
        color: #3b82f6;
        background: rgba(59, 130, 246, 0.05);
    }

    .btn-action-outline-primary:hover {
        background: rgba(59, 130, 246, 0.1);
        transform: scale(1.05);
    }

    .btn-action-outline-success {
        border-color: #10b981;
        color: #10b981;
        background: rgba(16, 185, 129, 0.05);
    }

    .btn-action-outline-success:hover {
        background: rgba(16, 185, 129, 0.1);
        transform: scale(1.05);
    }

    .btn-action-outline-danger {
        border-color: #ef4444;
        color: #ef4444;
        background: rgba(239, 68, 68, 0.05);
    }

    .btn-action-outline-danger:hover {
        background: rgba(239, 68, 68, 0.1);
        transform: scale(1.05);
    }

    /* Avatar */
    .user-avatar {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.875rem;
        font-weight: 600;
        margin-right: 0.5rem;
        box-shadow: var(--box-shadow);
    }

    /* Modal Enhancements */
    .modal-backdrop {
        backdrop-filter: blur(8px);
        transition: var(--transition);
    }

    .modal-content {
        animation: modalSlideIn 0.3s ease-out;
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

    /* Toast notifications */
    .toast-notification {
        min-width: 250px;
        max-width: 350px;
        transition: var(--transition);
        box-shadow: var(--box-shadow-lg);
        border-radius: var(--border-radius);
    }

    /* Grid Layouts */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
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

    /* Action Buttons */
    .btn-action-outline {
        padding: 0.5rem;
        border-radius: var(--border-radius);
        border: 1px solid #d1d5db;
        background: white;
        color: #6b7280;
        transition: var(--transition);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2rem;
        min-height: 2rem;
        font-size: 0.875rem;
    }

    .btn-action-outline:hover {
        transform: translateY(-1px);
        box-shadow: var(--box-shadow);
    }

    .btn-action-outline-primary {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .btn-action-outline-primary:hover {
        background: var(--primary-color);
        color: white;
    }

    .btn-action-outline-success {
        border-color: var(--accent-color);
        color: var(--accent-color);
    }

    .btn-action-outline-success:hover {
        background: var(--accent-color);
        color: white;
    }

    .btn-action-outline-danger {
        border-color: var(--danger-color);
        color: var(--danger-color);
    }

    .btn-action-outline-danger:hover {
        background: var(--danger-color);
        color: white;
    }

    /* User Avatar */
    .user-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background: var(--gradient-primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.875rem;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    /* Pagination */
    .pagination-modern {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        padding: 1.5rem 0;
    }

    .pagination-modern .page-link {
        padding: 0.5rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: var(--border-radius);
        color: #374151;
        text-decoration: none;
        transition: var(--transition);
        background: white;
        min-width: 2.5rem;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .pagination-modern .page-link:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        transform: translateY(-1px);
    }

    .pagination-modern .page-link.active {
        background: var(--gradient-primary);
        color: white;
        border-color: var(--primary-color);
    }

    .pagination-modern .page-link.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .pagination-modern .page-link.disabled:hover {
        background: white;
        color: #374151;
        transform: none;
    }

    /* Modal Enhancements */
    .modal-backdrop {
        backdrop-filter: blur(4px);
        animation: fadeIn 0.3s ease-out;
    }

    .modal-content-modern {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow-lg);
        overflow: hidden;
        animation: modalSlideIn 0.4s ease-out;
        max-width: 32rem;
        width: 100%;
        margin: 0 auto;
    }

    .modal-header-modern {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    .modal-header-success {
        background: var(--gradient-success);
        color: white;
    }

    .modal-header-danger {
        background: var(--gradient-danger);
        color: white;
    }

    .modal-header-primary {
        background: var(--gradient-primary);
        color: white;
    }

    .modal-body-modern {
        padding: 1.5rem;
    }

    .modal-footer-modern {
        padding: 1.5rem;
        background: #f8f9fa;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .form-input-modern {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: var(--border-radius);
        transition: var(--transition);
        font-size: 0.875rem;
        resize: vertical;
    }

    .form-input-modern:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.1);
    }

    .form-label-modern {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }

    .form-help-text {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endpush

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 1.5rem;">
    <div class="page-header">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; position: relative; z-index: 2;">
            <div>
                <h1 style="font-size: 2rem; font-weight: bold; color: white; margin-bottom: 0.5rem;">Manajemen Pengembalian</h1>
                <p style="color: rgba(255, 255, 255, 0.9); margin: 0;">Kelola semua pengajuan pengembalian dana dari pelanggan Anda.</p>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <button class="btn-secondary-custom" onclick="refreshData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn-primary-custom" onclick="exportData()">
                    <i class="fas fa-download"></i> Export Data
                </button>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card border-blue-500">
            <div style="display: flex; flex-direction: column;">
                <span style="font-size: 0.75rem; font-weight: 500; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Total</span>
                <span style="font-size: 2rem; font-weight: bold; color: var(--secondary-color);">{{ $stats['total'] }}</span>
            </div>
        </div>
        <div class="stat-card border-yellow-500">
            <div style="display: flex; flex-direction: column;">
                <span style="font-size: 0.75rem; font-weight: 500; color: #f59e0b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Menunggu Review</span>
                <span style="font-size: 2rem; font-weight: bold; color: var(--secondary-color);">{{ $stats['pending'] }}</span>
            </div>
        </div>
        <div class="stat-card border-indigo-500">
            <div style="display: flex; flex-direction: column;">
                <span style="font-size: 0.75rem; font-weight: 500; color: #6366f1; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Dalam Review</span>
                <span style="font-size: 2rem; font-weight: bold; color: var(--secondary-color);">{{ $stats['in_review'] }}</span>
            </div>
        </div>
        <div class="stat-card border-green-500">
            <div style="display: flex; flex-direction: column;">
                <span style="font-size: 0.75rem; font-weight: 500; color: #10b981; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Disetujui</span>
                <span style="font-size: 2rem; font-weight: bold; color: var(--secondary-color);">{{ $stats['approved'] }}</span>
            </div>
        </div>
        <div class="stat-card border-gray-500">
            <div style="display: flex; flex-direction: column;">
                <span style="font-size: 0.75rem; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Selesai</span>
                <span style="font-size: 2rem; font-weight: bold; color: var(--secondary-color);">{{ $stats['completed'] }}</span>
            </div>
        </div>
         <div class="stat-card border-red-500">
            <div style="display: flex; flex-direction: column;">
                <span style="font-size: 0.75rem; font-weight: 500; color: #ef4444; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">Ditolak / Lainnya</span>
                <span style="font-size: 2rem; font-weight: bold; color: var(--secondary-color);">{{ $stats['total'] - $stats['pending'] - $stats['in_review'] - $stats['approved'] - $stats['completed'] }}</span>
            </div>
        </div>
    </div>

    <div class="section-divider"></div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--secondary-color); margin: 0;">Filter Pengajuan</h3>
        </div>
        <div style="padding: 1.5rem;">
            <form method="GET" action="{{ route('admin.pengembalian.index') }}">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; align-items: end;">
                    <div>
                        <label for="status" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--secondary-color); margin-bottom: 0.25rem;">Status</label>
                        <select name="status" id="status" class="filter-input">
                            <option value="">Semua Status</option>
                            <option value="Menunggu Review" {{ request('status') === 'Menunggu Review' ? 'selected' : '' }}>Menunggu Review</option>
                            <option value="Dalam Review" {{ request('status') === 'Dalam Review' ? 'selected' : '' }}>Dalam Review</option>
                            <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="Dana Dikembalikan" {{ request('status') === 'Dana Dikembalikan' ? 'selected' : '' }}>Dana Dikembalikan</option>
                            <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div>
                        <label for="search" style="display: block; font-size: 0.875rem; font-weight: 500; color: var(--secondary-color); margin-bottom: 0.25rem;">Cari</label>
                        <input type="text" name="search" id="search" class="filter-input"
                               value="{{ request('search') }}"
                               placeholder="ID pesanan, nama pelanggan, atau keluhan...">
                    </div>
                    <div style="display: flex; gap: 0.75rem;">
                        <button type="submit" class="btn-primary-custom" style="flex: 1;">
                            <i class="fas fa-filter"></i> Terapkan
                        </button>
                        <a href="{{ route('admin.pengembalian.index') }}" class="btn-secondary-custom">
                            <i class="fas fa-redo-alt"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
        </div>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--secondary-color); margin: 0;">Daftar Pengajuan Pengembalian</h3>
        </div>
        <div style="padding: 1.5rem;">
            @if($refunds->isEmpty())
                <div style="text-align: center; padding: 2rem 0; color: #6b7280;">
                    <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 0.75rem; opacity: 0.5;"></i>
                    <h5 style="font-size: 1.125rem; font-weight: 500; color: var(--secondary-color); margin-bottom: 0.5rem;">Tidak Ada Pengajuan Pengembalian</h5>
                    <p style="color: #6b7280; margin: 0;">Belum ada pengajuan pengembalian yang masuk sesuai filter Anda.</p>
                </div>
            @else
                <div style="overflow-x: auto;">
                    <table style="min-width: 100%; border-collapse: separate; border-spacing: 0;">
                        <thead class="table-header-custom">
                            <tr>
                                <th style="padding: 1rem 1.5rem; text-align: left;">ID</th>
                                <th style="padding: 1rem 1.5rem; text-align: left;">Pelanggan</th>
                                <th style="padding: 1rem 1.5rem; text-align: left;">Pesanan</th>
                                <th style="padding: 1rem 1.5rem; text-align: left;">Keluhan</th>
                                <th style="padding: 1rem 1.5rem; text-align: right;">Jumlah Refund</th>
                                <th style="padding: 1rem 1.5rem; text-align: center;">Status</th>
                                <th style="padding: 1rem 1.5rem; text-align: left;">Tanggal</th>
                                <th style="padding: 1rem 1.5rem; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody style="background: white;">
                            @foreach($refunds as $item)
                                <tr class="table-row-hover" style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem; font-weight: 500; color: var(--secondary-color);">
                                        #{{ $item->id }}
                                    </td>
                                    <td style="padding: 1rem 1.5rem; white-space: nowrap;">
                                        <div style="display: flex; align-items: center;">
                                            <div class="user-avatar">
                                                {{ substr($item->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div style="font-size: 0.875rem; font-weight: 500; color: var(--secondary-color);">{{ $item->user->name }}</div>
                                                <div style="font-size: 0.75rem; color: #6b7280;">{{ $item->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 1rem 1.5rem; white-space: nowrap;">
                                        <a href="{{ route('admin.pesanan.show', $item->pesanan->id_pesanan) }}"
                                           style="color: var(--primary-color); text-decoration: none; font-size: 0.875rem; font-weight: 500; transition: var(--transition);"
                                           onmouseover="this.style.color='var(--primary-dark)'"
                                           onmouseout="this.style.color='var(--primary-color)'">
                                            #{{ $item->pesanan->id_pesanan }}
                                        </a>
                                        <div style="font-size: 0.75rem; color: #6b7280;">Rp {{ number_format($item->pesanan->total_harga, 0, ',', '.') }}</div>
                                    </td>
                                    <td style="padding: 1rem 1.5rem;">
                                        <span class="status-badge bg-blue-100 text-blue-800">{{ $item->jenis_keluhan }}</span>
                                        <span class="status-badge bg-gray-100 text-gray-800">{{ $item->jenis_pengembalian }}</span>
                                        @if($item->deskripsi_keluhan)
                                            <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">{{ Str::limit($item->deskripsi_keluhan, 40) }}</div>
                                        @endif
                                        @if($item->foto_bukti)
                                            <div style="font-size: 0.75rem; color: var(--primary-color); margin-top: 0.25rem;">
                                                <i class="fas fa-camera" style="margin-right: 0.25rem;"></i>Ada bukti foto
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem 1.5rem; white-space: nowrap; text-align: right; font-size: 0.875rem; font-weight: bold; color: #10b981;">
                                        Rp {{ number_format($item->jumlah_refund, 0, ',', '.') }}
                                    </td>
                                    <td style="padding: 1rem 1.5rem; white-space: nowrap; text-align: center;">
                                        @php
                                            $statusClass = strtolower(str_replace(' ', '-', $item->status_pengembalian));
                                        @endphp
                                        <span class="status-badge status-{{ $statusClass }}">
                                            {{ $item->status_pengembalian }}
                                        </span>
                                        @if($item->status_pengembalian === 'Menunggu Review')
                                            <div style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;">{{ $item->created_at->diffForHumans() }}</div>
                                        @endif
                                    </td>
                                    <td style="padding: 1rem 1.5rem; white-space: nowrap; font-size: 0.875rem; color: #374151;">
                                        {{ $item->created_at->format('d M Y') }}
                                        <div style="font-size: 0.75rem; color: #6b7280;">{{ $item->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td style="padding: 1rem 1.5rem; white-space: nowrap; text-align: center; font-size: 0.875rem; font-weight: 500;">
                                        <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                            <a href="{{ route('admin.pengembalian.show', $item->id) }}"
                                               class="btn-action-outline btn-action-outline-primary" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($item->status_pengembalian === 'Menunggu Review')
                                                <button class="btn-action-outline btn-action-outline-success"
                                                        onclick="showQuickApproveModal({{ $item->id }})" title="Setujui Cepat">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn-action-outline btn-action-outline-danger"
                                                        onclick="showQuickRejectModal({{ $item->id }})" title="Tolak Cepat">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @elseif($item->status_pengembalian === 'Disetujui')
                                                <button class="btn-action-outline btn-action-outline-primary"
                                                        onclick="showRefundModal({{ $item->id }})" title="Konfirmasi Pengembalian Dana">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($refunds->hasPages())
                <div class="section-divider"></div>
                <div class="pagination-modern">
                    {{ $refunds->appends(request()->query())->links('pagination::simple-default') }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Quick Approve Modal - Modern Styled -->
<div id="quickApproveModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="quickApproveModalLabel" aria-modal="true" role="dialog" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 50; overflow-y: auto; display: none;">
    <div class="modal-backdrop" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5);"></div>

    <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
        <div class="modal-content-modern">
            <div class="modal-header-modern modal-header-success">
                <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 0.5rem;" id="quickApproveModalLabel">
                    <i class="fas fa-check-circle"></i>Setujui Pengajuan Pengembalian
                </h3>
                <button type="button" class="modal-close" data-bs-dismiss="modal" style="background: none; border: none; color: white; font-size: 1.25rem; cursor: pointer; padding: 0.25rem; border-radius: 0.25rem; transition: var(--transition);" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'" onmouseout="this.style.backgroundColor='transparent'">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="quickApproveForm" method="POST">
                @csrf
                <div class="modal-body-modern">
                    <div style="margin-bottom: 1rem;">
                        <label for="approveNote" class="form-label-modern">
                            Catatan (Opsional)
                        </label>
                        <textarea
                            name="catatan_admin"
                            id="approveNote"
                            rows="3"
                            class="form-input-modern"
                            placeholder="Berikan catatan jika diperlukan..."></textarea>
                        <p class="form-help-text">
                            Catatan ini akan ditampilkan kepada pelanggan saat melihat detail pengembalian
                        </p>
                    </div>
                </div>

                <div class="modal-footer-modern">
                    <button type="button" class="btn-secondary-custom modal-close" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary-custom" style="background: var(--gradient-success);">
                        <i class="fas fa-check"></i> Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Reject Modal - Modern Styled -->
<div id="quickRejectModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="quickRejectModalLabel" aria-modal="true" role="dialog" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 50; overflow-y: auto; display: none;">
    <div class="modal-backdrop" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5);"></div>

    <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
        <div class="modal-content-modern">
            <div class="modal-header-modern modal-header-danger">
                <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 0.5rem;" id="quickRejectModalLabel">
                    <i class="fas fa-times-circle"></i>Tolak Pengajuan Pengembalian
                </h3>
                <button type="button" class="modal-close" data-bs-dismiss="modal" style="background: none; border: none; color: white; font-size: 1.25rem; cursor: pointer; padding: 0.25rem; border-radius: 0.25rem; transition: var(--transition);" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'" onmouseout="this.style.backgroundColor='transparent'">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="quickRejectForm" method="POST">
                @csrf
                <div class="modal-body-modern">
                    <div style="margin-bottom: 1rem;">
                        <div style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                            <label for="rejectNote" class="form-label-modern" style="margin-bottom: 0;">
                                Alasan Penolakan
                            </label>
                            <span style="margin-left: 0.25rem; color: var(--danger-color); font-size: 0.875rem;">*</span>
                        </div>
                        <textarea
                            name="catatan_admin"
                            id="rejectNote"
                            rows="3"
                            class="form-input-modern"
                            placeholder="Jelaskan alasan penolakan secara detail..."
                            required></textarea>
                        <p class="form-help-text">
                            Berikan alasan yang jelas dan proses selanjutnya yang dapat dilakukan pelanggan
                        </p>
                    </div>
                </div>

                <div class="modal-footer-modern">
                    <button type="button" class="btn-secondary-custom modal-close" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary-custom" style="background: var(--gradient-danger);">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Refund Modal - Modern Styled -->
<div id="refundModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="refundModalLabel" aria-modal="true" role="dialog" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 50; overflow-y: auto; display: none;">
    <div class="modal-backdrop" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5);"></div>

    <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">
        <div class="modal-content-modern">
            <div class="modal-header-modern modal-header-primary">
                <h3 style="font-size: 1.125rem; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 0.5rem;" id="refundModalLabel">
                    <i class="fas fa-money-bill-wave"></i>Konfirmasi Pengembalian Dana
                </h3>
                <button type="button" class="modal-close" data-bs-dismiss="modal" style="background: none; border: none; color: white; font-size: 1.25rem; cursor: pointer; padding: 0.25rem; border-radius: 0.25rem; transition: var(--transition);" onmouseover="this.style.backgroundColor='rgba(255,255,255,0.1)'" onmouseout="this.style.backgroundColor='transparent'">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="refundForm" method="POST">
                @csrf
                <div class="modal-body-modern">
                    <div style="margin-bottom: 1rem;">
                        <label for="refundNote" class="form-label-modern">
                            Catatan Pengembalian Dana
                        </label>
                        <textarea
                            name="catatan_admin"
                            id="refundNote"
                            rows="3"
                            class="form-input-modern"
                            placeholder="Berikan catatan mengenai proses pengembalian dana..."></textarea>
                        <p class="form-help-text">
                            Informasi ini akan membantu pelanggan memahami proses pengembalian dana
                        </p>
                    </div>
                </div>

                <div class="modal-footer-modern">
                    <button type="button" class="btn-secondary-custom modal-close" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary-custom">
                        <i class="fas fa-money-bill-wave"></i> Konfirmasi Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
                    <div class="mb-4">
                        <label for="refundNote" class="block text-sm font-medium text-gray-700 mb-1">
                            Catatan (Opsional)
                        </label>
                        <textarea
                            name="catatan_admin"
                            id="refundNote"
                            rows="2"
                            class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                            placeholder="Catatan tambahan tentang transfer..."></textarea>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-money-bill-wave mr-2"></i> Konfirmasi Transfer
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm modal-close" data-bs-dismiss="modal">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Modal utility functions
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('hidden');

    // Trigger animation
    setTimeout(() => {
        const backdrop = modal.querySelector('.modal-backdrop');
        const dialog = modal.querySelector('.relative');

        backdrop.classList.add('opacity-100');
        dialog.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        dialog.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
    }, 10);

    // Add event listeners
    setupModalListeners(modalId);
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('hidden');

    // Remove event listeners
    const closeButtons = modal.querySelectorAll('.modal-close');
    closeButtons.forEach(button => {
        button.removeEventListener('click', () => hideModal(modalId));
    });
}

function setupModalListeners(modalId) {
    const modal = document.getElementById(modalId);

    // Close buttons
    const closeButtons = modal.querySelectorAll('.modal-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', () => hideModal(modalId));
    });

    // Backdrop click
    const backdrop = modal.querySelector('.modal-backdrop');
    backdrop.addEventListener('click', () => hideModal(modalId));

    // Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            hideModal(modalId);
        }
    });
}

// Show modals with proper setup
function showQuickApproveModal(id) {
    const form = document.getElementById('quickApproveForm');
    form.action = `/admin/pengembalian/${id}/approve`;

    // Get return details for the modal title
    const returnRow = document.querySelector(`tr[data-id="${id}"]`);
    const returnIdText = returnRow ? `#${id}` : `#${id}`;

    // Update modal title with return ID
    const modalTitle = document.getElementById('quickApproveModalLabel');
    if (modalTitle) {
        modalTitle.innerHTML = `<i class="fas fa-check-circle mr-2"></i>Setujui Pengajuan ${returnIdText}`;
    }

    showModal('quickApproveModal');
}

function showQuickRejectModal(id) {
    const form = document.getElementById('quickRejectForm');
    form.action = `/admin/pengembalian/${id}/reject`;

    // Get return details for the modal title
    const returnRow = document.querySelector(`tr[data-id="${id}"]`);
    const returnIdText = returnRow ? `#${id}` : `#${id}`;

    // Update modal title with return ID
    const modalTitle = document.getElementById('quickRejectModalLabel');
    if (modalTitle) {
        modalTitle.innerHTML = `<i class="fas fa-times-circle mr-2"></i>Tolak Pengajuan ${returnIdText}`;
    }

    showModal('quickRejectModal');
}

function showRefundModal(id) {
    const form = document.getElementById('refundForm');
    form.action = `/admin/pengembalian/${id}/mark-refunded`;

    // Get return details for the modal title
    const returnRow = document.querySelector(`tr[data-id="${id}"]`);
    const returnIdText = returnRow ? `#${id}` : `#${id}`;

    // Update modal title with return ID
    const modalTitle = document.getElementById('refundModalLabel');
    if (modalTitle) {
        modalTitle.innerHTML = `<i class="fas fa-money-bill-wave mr-2"></i>Konfirmasi Pengembalian Dana ${returnIdText}`;
    }

    // Set today's date as default
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('refundDate').value = today;

    showModal('refundModal');
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('#quickApproveForm, #quickRejectForm, #refundForm');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');

                    // Add error message if not exists
                    const errorId = `${field.id}-error`;
                    if (!document.getElementById(errorId)) {
                        const errorMsg = document.createElement('p');
                        errorMsg.id = errorId;
                        errorMsg.className = 'mt-1 text-sm text-red-600';
                        errorMsg.textContent = 'Bidang ini wajib diisi';
                        field.parentNode.appendChild(errorMsg);
                    }
                } else {
                    field.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    const errorMsg = document.getElementById(`${field.id}-error`);
                    if (errorMsg) errorMsg.remove();
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    });
});

function refreshData() {
    // Show loading indicator
    const loadingToast = createToast('Memuat ulang data...', 'loading');

    // Reload page
    window.location.reload();
}

function exportData() {
    // Create toast notification
    createToast('Mempersiapkan data untuk ekspor...', 'info');

    // Future implementation - replace with actual export logic
    setTimeout(() => {
        createToast('Fitur export data akan segera diimplementasikan!', 'warning');
    }, 1000);
}

// Toast notification system
function createToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach((toast, index) => {
        // Stagger removal to prevent visual glitches
        setTimeout(() => {
            toast.classList.add('opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, index * 100);
    });

    // Create toast container if not exists
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'fixed bottom-4 right-4 z-50 flex flex-col space-y-2';
        document.body.appendChild(toastContainer);
    }

    // Create toast
    const toast = document.createElement('div');
    toast.className = 'toast-notification bg-white rounded-lg shadow-lg border-l-4 p-4 transform transition-all duration-300 opacity-0 translate-x-2';

    // Set toast style based on type
    let icon = '';
    switch (type) {
        case 'success':
            toast.classList.add('border-green-500');
            icon = '<i class="fas fa-check-circle text-green-500 mr-2"></i>';
            break;
        case 'error':
            toast.classList.add('border-red-500');
            icon = '<i class="fas fa-times-circle text-red-500 mr-2"></i>';
            break;
        case 'warning':
            toast.classList.add('border-yellow-500');
            icon = '<i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>';
            break;
        case 'loading':
            toast.classList.add('border-blue-500');
            icon = '<i class="fas fa-circle-notch fa-spin text-blue-500 mr-2"></i>';
            break;
        default:
            toast.classList.add('border-orange-500');
            icon = '<i class="fas fa-info-circle text-orange-500 mr-2"></i>';
    }

    toast.innerHTML = `
        <div class="flex items-center">
            ${icon}
            <span class="text-sm font-medium text-gray-800">${message}</span>
        </div>
    `;

    toastContainer.appendChild(toast);

    // Show the toast
    setTimeout(() => {
        toast.classList.remove('opacity-0', 'translate-x-2');
    }, 10);

    // Auto hide after 4 seconds (except for loading)
    if (type !== 'loading') {
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-x-2');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 4000);
    }

    return toast;
}

// Auto-refresh for pending reviews every 30 seconds
setInterval(function() {
    if (window.location.search.includes('status=Menunggu Review') || window.location.search === '') {
        const pendingCount = {{ $stats['pending'] }};
        if (pendingCount > 0) {
            // Check for new returns via fetch API
            fetch('/admin/pengembalian/check-new')
                .then(response => response.json())
                .then(data => {
                    if (data.hasNew) {
                        createToast(`${data.count} pengajuan pengembalian baru menunggu review!`, 'info');
                    }
                })
                .catch(() => console.log('Failed to check for new returns'));
        }
    }
}, 30000);

// Add data-id attributes to rows for modal reference
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const idCell = row.querySelector('td:first-child');
        if (idCell) {
            const idText = idCell.textContent.trim();
            const id = idText.replace('#', '');
            row.setAttribute('data-id', id);
        }
    });
});
</script>
@endpush

@endsection
