@extends('admin.layouts.app')

@section('title', 'Detail Pengajuan Pengembalian #' . $pengembalian->id)

@push('styles')
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
        --dark-color: #1f2937;
        --border-color: #e2e8f0;
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --text-muted: #9ca3af;
        --bg-light: #f8fafc;
        --bg-white: #ffffff;
        --shadow-light: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-medium: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-large: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --gradient-primary: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        --gradient-success: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
        --gradient-danger: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
        --gradient-warning: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
        --gradient-info: linear-gradient(135deg, var(--info-color) 0%, #2563eb 100%);
        --gradient-dark: linear-gradient(135deg, var(--dark-color) 0%, #111827 100%);
    }

    .modern-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 2rem 0;
    }

    .gradient-header {
        background: var(--gradient-primary);
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        border-radius: 20px;
        box-shadow: var(--shadow-large);
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

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 2rem 2.5rem;
        position: relative;
        z-index: 2;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-info h1 {
        color: white;
        font-size: 1.875rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-info p {
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
        font-size: 1rem;
        font-weight: 500;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .modern-card {
        background: var(--bg-white);
        border-radius: 16px;
        box-shadow: var(--shadow-light);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: visible;
        margin-bottom: 1.5rem;
    }

    .modern-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        border-radius: 16px 16px 0 0;
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

    .card-header {
        padding: 1.5rem 2rem 1rem 2rem;
        border-bottom: 1px solid var(--border-color);
        background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
        border-radius: 16px 16px 0 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header i {
        color: var(--primary-color);
        font-size: 1.25rem;
    }

    .card-header span {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .card-body {
        padding: 2rem;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 2rem;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
        }

        .header-actions {
            justify-content: center;
        }
    }

    .modern-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        letter-spacing: 0.025em;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }

    .modern-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .modern-btn:hover::before {
        left: 100%;
    }

    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    .btn-primary {
        background: var(--gradient-primary);
        color: white;
    }

    .btn-secondary {
        background: linear-gradient(145deg, #f1f5f9, #e2e8f0);
        color: var(--text-primary);
        border: 2px solid var(--border-color);
    }

    .btn-success {
        background: var(--gradient-success);
        color: white;
    }

    .btn-danger {
        background: var(--gradient-danger);
        color: white;
    }

    .btn-warning {
        background: var(--gradient-warning);
        color: white;
    }

    .btn-info {
        background: var(--gradient-info);
        color: white;
    }

    .btn-outline-primary {
        background: transparent;
        color: var(--primary-color);
        border: 2px solid var(--primary-color);
    }

    .btn-outline-primary:hover {
        background: var(--primary-color);
        color: white;
    }

    .modern-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        position: relative;
        overflow: hidden;
    }

    .modern-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 2s ease;
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; }
        50% { left: 100%; }
        100% { left: 100%; }
    }

    .badge-warning {
        background: var(--gradient-warning);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .badge-info {
        background: var(--gradient-info);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .badge-success {
        background: var(--gradient-success);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .badge-danger {
        background: var(--gradient-danger);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .badge-primary {
        background: var(--gradient-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
    }

    .badge-dark {
        background: var(--gradient-dark);
        color: white;
        box-shadow: 0 4px 12px rgba(31, 41, 55, 0.3);
    }

    .badge-secondary {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
        box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
    }

    .status-display {
        text-align: center;
        margin-bottom: 2rem;
    }

    .status-display .modern-badge {
        font-size: 0.875rem;
        padding: 0.75rem 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        letter-spacing: 0.025em;
    }

    .modern-input,
    .modern-select,
    .modern-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background-color: var(--bg-white);
        color: var(--text-primary);
    }

    .modern-input:focus,
    .modern-select:focus,
    .modern-textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        transform: translateY(-1px);
    }

    .modern-input:hover,
    .modern-select:hover,
    .modern-textarea:hover {
        border-color: var(--primary-light);
    }

    .modern-textarea {
        resize: vertical;
        font-family: inherit;
    }

    .full-width {
        width: 100%;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.25rem;
        box-shadow: var(--shadow-medium);
        position: relative;
        overflow: hidden;
    }

    .user-avatar::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 2s ease;
        animation: shimmer-avatar 3s infinite;
    }

    @keyframes shimmer-avatar {
        0% { left: -100%; }
        50% { left: 100%; }
        100% { left: 100%; }
    }

    .user-info h6 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 0.25rem 0;
    }

    .user-info p {
        color: var(--text-secondary);
        margin: 0;
        font-size: 0.875rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item:hover {
        background: rgba(249, 115, 22, 0.02);
        margin: 0 -1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        border-radius: 8px;
    }

    .info-item strong {
        color: var(--text-primary);
        font-weight: 600;
        min-width: 120px;
    }

    .modern-code {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        padding: 0.25rem 0.75rem;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        color: var(--text-primary);
        border: 1px solid var(--border-color);
        font-weight: 600;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }

    .info-section {
        space-y: 1rem;
    }

    .info-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
    }

    .info-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--gradient-primary);
        transition: width 0.3s ease;
    }

    .info-link:hover::after {
        width: 100%;
    }

    .info-link:hover {
        color: var(--primary-dark);
    }

    .amount {
        font-weight: 700;
        color: var(--text-primary);
    }

    .amount.highlight {
        color: var(--primary-color);
        font-size: 1.125rem;
    }

    .section-divider {
        display: flex;
        align-items: center;
        margin: 2rem 0;
        position: relative;
    }

    .section-divider::before {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--border-color), transparent);
    }

    .section-divider span {
        padding: 0 1rem;
        font-weight: 600;
        color: var(--text-secondary);
        background: var(--bg-white);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .modern-table-container {
        overflow: hidden;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background: var(--bg-white);
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        background: var(--bg-white);
    }

    .modern-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .modern-table th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--border-color);
    }

    .modern-table td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        transition: all 0.3s ease;
    }

    .modern-table tbody tr {
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background: rgba(249, 115, 22, 0.02);
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .product-thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid var(--border-color);
        transition: all 0.3s ease;
    }

    .product-thumbnail:hover {
        border-color: var(--primary-color);
        transform: scale(1.05);
        box-shadow: var(--shadow-medium);
    }

    .product-details strong {
        color: var(--text-primary);
        font-weight: 600;
    }

    .description-section {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-color);
    }

    .description-content {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        margin-top: 0.75rem;
        line-height: 1.6;
        color: var(--text-primary);
        font-size: 0.95rem;
    }

    .photos-section {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border-color);
    }

    .photo-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .photo-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid var(--border-color);
    }

    .photo-item:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-large);
        border-color: var(--primary-color);
    }

    .evidence-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.3s ease;
    }

    .photo-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .photo-item:hover .photo-overlay {
        opacity: 1;
    }

    .photo-overlay i {
        color: white;
        font-size: 1.5rem;
    }

    .admin-note {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid #f59e0b;
        border-left: 4px solid #f59e0b;
        position: relative;
        overflow: hidden;
    }

    .admin-note::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23f59e0b' fill-opacity='0.05'%3E%3Ccircle cx='20' cy='20' r='2'/%3E%3C/g%3E%3C/svg%3E") repeat;
        pointer-events: none;
    }

    .admin-note p {
        margin: 0;
        color: #92400e;
        font-weight: 500;
        line-height: 1.6;
        position: relative;
        z-index: 1;
    }
</style>
@endpush

@section('content')
<div class="modern-container">
    <!-- Gradient Header -->
    <div class="container mx-auto px-4">
        <div class="gradient-header">
            <div class="header-content">
                <div class="header-info">
                    <h1>
                        <i class="fas fa-undo-alt"></i>
                        Detail Pengajuan Pengembalian #{{ $pengembalian->id }}
                    </h1>
                    <p>
                        Diajukan oleh {{ $pengembalian->user->name }} - {{ $pengembalian->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.pengembalian.index') }}" class="modern-btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if($pengembalian->status_pengembalian === 'Menunggu Review')
                        <button class="modern-btn btn-success" onclick="showApproveModal()">
                            <i class="fas fa-check"></i> Setujui
                        </button>
                        <button class="modern-btn btn-danger" onclick="showRejectModal()">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    @elseif($pengembalian->status_pengembalian === 'Disetujui')
                        <button class="modern-btn btn-primary" onclick="showRefundModal()">
                            <i class="fas fa-money-bill-wave"></i> Konfirmasi Transfer
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="content-grid">
        <!-- Status & Actions -->
        <div class="sidebar-column">
            <!-- Current Status -->
            <div class="modern-card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i>
                    <span>Status Pengajuan</span>
                </div>
                <div class="card-body">
                    @php
                        $statusColors = [
                            'Menunggu Review' => 'warning',
                            'Dalam Review' => 'info',
                            'Disetujui' => 'success',
                            'Ditolak' => 'danger',
                            'Dana Dikembalikan' => 'primary',
                            'Selesai' => 'dark'
                        ];
                        $currentStatus = $pengembalian->status_pengembalian;
                    @endphp

                    <div class="status-display">
                        <span class="modern-badge badge-{{ $statusColors[$currentStatus] ?? 'secondary' }}">
                            {{ $currentStatus }}
                        </span>
                    </div>

                    <!-- Status Update Form -->
                    <form id="statusUpdateForm" action="{{ route('admin.pengembalian.updateStatus', $pengembalian->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="status_pengembalian" class="form-label">Ubah Status</label>
                            <select name="status_pengembalian" id="status_pengembalian" class="modern-select">
                                <option value="Menunggu Review" {{ $currentStatus === 'Menunggu Review' ? 'selected' : '' }}>Menunggu Review</option>
                                <option value="Dalam Review" {{ $currentStatus === 'Dalam Review' ? 'selected' : '' }}>Dalam Review</option>
                                <option value="Disetujui" {{ $currentStatus === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="Ditolak" {{ $currentStatus === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="Dana Dikembalikan" {{ $currentStatus === 'Dana Dikembalikan' ? 'selected' : '' }}>Dana Dikembalikan</option>
                                <option value="Selesai" {{ $currentStatus === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <div class="form-group" id="transactionNumberField" style="display: none;">
                            <label for="nomor_transaksi_pengembalian" class="form-label">Nomor Transaksi</label>
                            <input type="text" name="nomor_transaksi_pengembalian" id="nomor_transaksi_pengembalian"
                                   class="modern-input" value="{{ $pengembalian->nomor_transaksi_pengembalian }}"
                                   placeholder="Masukkan nomor transaksi">
                        </div>

                        <div class="form-group">
                            <label for="catatan_admin" class="form-label">Catatan Admin</label>
                            <textarea name="catatan_admin" id="catatan_admin" class="modern-textarea" rows="3"
                                      placeholder="Berikan catatan untuk pelanggan...">{{ $pengembalian->catatan_admin }}</textarea>
                        </div>

                        <button type="button" id="updateStatusBtn" class="modern-btn btn-primary full-width">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="modern-card">
                <div class="card-header">
                    <i class="fas fa-user"></i>
                    <span>Informasi Pelanggan</span>
                </div>
                <div class="card-body">
                    <div class="user-profile">
                        <div class="user-avatar">
                            {{ substr($pengembalian->user->name, 0, 2) }}
                        </div>
                        <div class="user-info">
                            <h6>{{ $pengembalian->user->name }}</h6>
                            <p>{{ $pengembalian->user->email }}</p>
                        </div>
                    </div>

                    @if($pengembalian->user->no_telepon)
                    <div class="info-item">
                        <strong>Telepon:</strong>
                        <span>{{ $pengembalian->user->no_telepon }}</span>
                    </div>
                    @endif

                    <div class="info-item">
                        <strong>Bergabung:</strong>
                        <span>{{ $pengembalian->user->created_at->format('d/m/Y') }}</span>
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <a href="{{ route('admin.users.show', $pengembalian->user->id) }}" class="modern-btn btn-outline-primary">
                            <i class="fas fa-eye"></i> Lihat Profil
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bank Information -->
            @if($pengembalian->bank_name || $pengembalian->account_number)
            <div class="modern-card">
                <div class="card-header">
                    <i class="fas fa-university"></i>
                    <span>Informasi Rekening</span>
                </div>
                <div class="card-body">
                    @if($pengembalian->bank_name)
                        <div class="info-item">
                            <strong>Bank:</strong>
                            <span>{{ $pengembalian->bank_name }}</span>
                        </div>
                    @endif
                    @if($pengembalian->account_number)
                        <div class="info-item">
                            <strong>No. Rekening:</strong>
                            <span class="modern-code">{{ $pengembalian->account_number }}</span>
                        </div>
                    @endif
                    @if($pengembalian->account_holder_name)
                        <div class="info-item">
                            <strong>Nama Pemegang:</strong>
                            <span>{{ $pengembalian->account_holder_name }}</span>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Review History -->
            @if($pengembalian->reviewedBy || $pengembalian->tanggal_review)
            <div class="modern-card">
                <div class="card-header">
                    <i class="fas fa-history"></i>
                    <span>Riwayat Review</span>
                </div>
                <div class="card-body">
                    @if($pengembalian->reviewedBy)
                        <div class="info-item">
                            <strong>Direview oleh:</strong>
                            <span>{{ $pengembalian->reviewedBy->name }}</span>
                        </div>
                    @endif
                    @if($pengembalian->tanggal_review)
                        <div class="info-item">
                            <strong>Tanggal Review:</strong>
                            <span>{{ \Carbon\Carbon::parse($pengembalian->tanggal_review)->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                    @if($pengembalian->tanggal_pengembalian_dana)
                        <div class="info-item">
                            <strong>Dana Dikembalikan:</strong>
                            <span>{{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian_dana)->format('d/m/Y H:i') }}</span>
                        </div>
                    @endif
                    @if($pengembalian->nomor_transaksi_pengembalian)
                        <div class="info-item">
                            <strong>No. Transaksi:</strong>
                            <span class="modern-code">{{ $pengembalian->nomor_transaksi_pengembalian }}</span>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content -->
        <div class="main-column">
            <!-- Order Information -->
            <div class="modern-card">
                <div class="card-header">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Informasi Pesanan</span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-section">
                            <div class="info-item">
                                <strong>ID Pesanan:</strong>
                                <a href="{{ route('admin.pesanan.show', $pengembalian->pesanan->id_pesanan) }}"
                                   class="info-link">
                                    {{ $pengembalian->pesanan->id_pesanan }}
                                </a>
                            </div>
                            <div class="info-item">
                                <strong>Total Pesanan:</strong>
                                <span class="amount">Rp {{ number_format($pengembalian->pesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="info-item">
                                <strong>Status Pesanan:</strong>
                                <span class="modern-badge badge-info">{{ $pengembalian->pesanan->status_pesanan }}</span>
                            </div>
                        </div>
                        <div class="info-section">
                            <div class="info-item">
                                <strong>Tanggal Pesanan:</strong>
                                <span>{{ $pengembalian->pesanan->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($pengembalian->pesanan->tanggal_pengiriman)
                            <div class="info-item">
                                <strong>Tanggal Pengiriman:</strong>
                                <span>{{ \Carbon\Carbon::parse($pengembalian->pesanan->tanggal_pengiriman)->format('d/m/Y') }}</span>
                            </div>
                            @endif
                            <div class="info-item">
                                <strong>Jumlah Refund:</strong>
                                <span class="amount highlight">Rp {{ number_format($pengembalian->jumlah_refund, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="section-divider">
                        <span>Barang yang Dipesan</span>
                    </div>
                    <div class="modern-table-container">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengembalian->pesanan->detailPesanan as $detail)
                                <tr>
                                    <td>
                                        <div class="product-info">
                                            @if($detail->produk && $detail->produk->foto_produk)
                                                <img src="{{ Storage::url($detail->produk->foto_produk) }}"
                                                     alt="{{ $detail->produk->nama_produk }}"
                                                     class="product-thumbnail">
                                            @endif
                                            <div class="product-details">
                                                <strong>{{ $detail->produk->nama_produk ?? 'Produk tidak ditemukan' }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="amount">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td class="amount">Rp {{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Return Details -->
            <div class="modern-card">
                <div class="card-header">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Detail Keluhan</span>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-section">
                            <div class="info-item">
                                <strong>Jenis Keluhan:</strong>
                                <span class="modern-badge badge-info">{{ $pengembalian->jenis_keluhan }}</span>
                            </div>
                        </div>
                        <div class="info-section">
                            <div class="info-item">
                                <strong>Jenis Pengembalian:</strong>
                                <span class="modern-badge badge-secondary">{{ $pengembalian->jenis_pengembalian }}</span>
                            </div>
                        </div>
                    </div>

                    @if($pengembalian->deskripsi_keluhan)
                    <div class="description-section">
                        <strong>Deskripsi Keluhan:</strong>
                        <div class="description-content">
                            {{ $pengembalian->deskripsi_keluhan }}
                        </div>
                    </div>
                    @endif

                    <!-- Photos -->
                    @if($pengembalian->foto_bukti)
                    <div class="photos-section">
                        <strong>Foto Bukti:</strong>
                        @php
                            $photos = is_array($pengembalian->foto_bukti) ? $pengembalian->foto_bukti : json_decode($pengembalian->foto_bukti, true);
                        @endphp
                        @if($photos)
                            <div class="photo-gallery">
                                @foreach($photos as $photo)
                                <div class="photo-item">
                                    <img src="{{ Storage::url($photo) }}"
                                         alt="Bukti"
                                         class="evidence-photo"
                                         data-bs-toggle="modal"
                                         data-bs-target="#photoModal-{{ $loop->index }}">
                                    <div class="photo-overlay">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                </div>

                                <!-- Photo Modal -->
                                <div class="modal fade" id="photoModal-{{ $loop->index }}" tabindex="-1">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Foto Bukti {{ $loop->iteration }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="{{ Storage::url($photo) }}" alt="Bukti" class="w-100">
                                            </div>
                                            <div class="modal-footer">
                                                <a href="{{ Storage::url($photo) }}" download class="modern-btn btn-primary">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Admin Notes -->
            @if($pengembalian->catatan_admin)
            <div class="modern-card">
                <div class="card-header">
                    <i class="fas fa-comment-alt"></i>
                    <span>Catatan Admin</span>
                </div>
                <div class="card-body">
                    <div class="admin-note">
                        <p>{{ $pengembalian->catatan_admin }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions Modals -->
<!-- Approve Modal -->
<div class="modern-modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Setujui Pengajuan Pengembalian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pengembalian.approve', $pengembalian->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert-success">
                        <i class="fas fa-info-circle"></i>
                        Dengan menyetujui pengajuan ini, pelanggan akan menerima notifikasi dan proses pengembalian akan dilanjutkan.
                    </div>
                    <div class="form-group">
                        <label for="approveNote" class="form-label">Catatan (Opsional)</label>
                        <textarea name="catatan_admin" id="approveNote" class="modern-textarea" rows="3"
                                  placeholder="Berikan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modern-btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="modern-btn btn-success">
                        <i class="fas fa-check"></i> Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modern-modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pengajuan Pengembalian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pengembalian.reject', $pengembalian->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Pastikan Anda memberikan alasan yang jelas untuk penolakan pengajuan ini.
                    </div>
                    <div class="form-group">
                        <label for="rejectNote" class="form-label">Alasan Penolakan <span class="required">*</span></label>
                        <textarea name="catatan_admin" id="rejectNote" class="modern-textarea" rows="4"
                                  placeholder="Jelaskan alasan penolakan dengan detail..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modern-btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="modern-btn btn-danger">
                        <i class="fas fa-times"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div class="modern-modal fade" id="refundModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pengembalian Dana</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pengembalian.markRefunded', $pengembalian->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert-primary">
                        <i class="fas fa-money-bill-wave"></i>
                        Konfirmasi bahwa dana sebesar <strong>Rp {{ number_format($pengembalian->jumlah_refund, 0, ',', '.') }}</strong>
                        telah ditransfer ke rekening pelanggan.
                    </div>

                    @if($pengembalian->bank_name)
                    <div class="transfer-info">
                        <strong>Informasi Transfer:</strong>
                        <ul>
                            <li>Bank: {{ $pengembalian->bank_name }}</li>
                            <li>No. Rekening: {{ $pengembalian->account_number }}</li>
                            <li>Nama: {{ $pengembalian->account_holder_name }}</li>
                        </ul>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="refundTransactionNumber" class="form-label">Nomor Transaksi <span class="required">*</span></label>
                        <input type="text" name="nomor_transaksi_pengembalian" id="refundTransactionNumber"
                               class="modern-input" placeholder="Masukkan nomor transaksi bank" required>
                    </div>
                    <div class="form-group">
                        <label for="refundNote" class="form-label">Catatan (Opsional)</label>
                        <textarea name="catatan_admin" id="refundNote" class="modern-textarea" rows="2"
                                  placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modern-btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="modern-btn btn-primary">
                        <i class="fas fa-money-bill-wave"></i> Konfirmasi Transfer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status_pengembalian');
    const transactionField = document.getElementById('transactionNumberField');
    const updateStatusBtn = document.getElementById('updateStatusBtn');

    function toggleTransactionField() {
        if (statusSelect.value === 'Dana Dikembalikan') {
            transactionField.style.display = 'block';
            document.getElementById('nomor_transaksi_pengembalian').required = true;
        } else {
            transactionField.style.display = 'none';
            document.getElementById('nomor_transaksi_pengembalian').required = false;
        }
    }

    statusSelect.addEventListener('change', toggleTransactionField);
    toggleTransactionField(); // Initial check

    // SweetAlert2 Status Update Confirmation
    updateStatusBtn.addEventListener('click', function(e) {
        e.preventDefault();

        const selectedStatus = statusSelect.value;
        const currentStatus = '{{ $currentStatus }}';
        const notes = document.getElementById('catatan_admin').value;
        const transactionNumber = document.getElementById('nomor_transaksi_pengembalian').value;

        // Don't allow update if status is the same
        if (selectedStatus === currentStatus) {
            Swal.fire({
                title: 'Tidak Ada Perubahan',
                text: 'Status yang dipilih sama dengan status saat ini',
                icon: 'info',
                confirmButtonColor: '#6b7280',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Validate transaction number for "Dana Dikembalikan" status
        if (selectedStatus === 'Dana Dikembalikan' && !transactionNumber.trim()) {
            Swal.fire({
                title: 'Nomor Transaksi Diperlukan',
                text: 'Silakan masukkan nomor transaksi untuk status "Dana Dikembalikan"',
                icon: 'warning',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Configure confirmation based on status
        let icon, title, text, confirmButtonText, confirmButtonColor;

        switch(selectedStatus) {
            case 'Dalam Review':
                icon = 'info';
                title = 'Ubah Status ke Dalam Review';
                text = 'Apakah Anda yakin ingin mengubah status pengembalian menjadi "Dalam Review"?';
                confirmButtonText = 'Ya, Ubah Status';
                confirmButtonColor = '#3b82f6';
                break;
            case 'Disetujui':
                icon = 'success';
                title = 'Setujui Pengembalian';
                text = 'Apakah Anda yakin ingin menyetujui pengajuan pengembalian ini? Pelanggan akan menerima notifikasi persetujuan.';
                confirmButtonText = 'Ya, Setujui';
                confirmButtonColor = '#10b981';
                break;
            case 'Ditolak':
                icon = 'warning';
                title = 'Tolak Pengembalian';
                text = 'Apakah Anda yakin ingin menolak pengajuan pengembalian ini? Pastikan catatan admin sudah diisi dengan alasan yang jelas.';
                confirmButtonText = 'Ya, Tolak';
                confirmButtonColor = '#dc2626';
                break;
            case 'Dana Dikembalikan':
                icon = 'question';
                title = 'Konfirmasi Dana Dikembalikan';
                text = `Konfirmasi bahwa dana sebesar Rp {{ number_format($pengembalian->jumlah_refund, 0, ',', '.') }} telah ditransfer ke rekening pelanggan dengan nomor transaksi: ${transactionNumber}`;
                confirmButtonText = 'Ya, Konfirmasi Transfer';
                confirmButtonColor = '#f97316';
                break;
            case 'Selesai':
                icon = 'success';
                title = 'Selesaikan Pengembalian';
                text = 'Apakah Anda yakin ingin menandai pengembalian ini sebagai selesai? Status ini menandakan bahwa seluruh proses pengembalian telah completed.';
                confirmButtonText = 'Ya, Selesaikan';
                confirmButtonColor = '#059669';
                break;
            default:
                icon = 'question';
                title = 'Ubah Status Pengembalian';
                text = `Apakah Anda yakin ingin mengubah status pengembalian menjadi "${selectedStatus}"?`;
                confirmButtonText = 'Ya, Ubah Status';
                confirmButtonColor = '#6b7280';
        }

        // Show confirmation dialog
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'animate__animated animate__fadeInDown',
                confirmButton: 'btn btn-confirm',
                cancelButton: 'btn btn-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang mengupdate status pengembalian',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit the form
                document.getElementById('statusUpdateForm').submit();
            }
        });
    });
});

// SweetAlert2 Modal Functions
function showApproveModal() {
    Swal.fire({
        title: 'Setujui Pengembalian',
        html: `
            <div class="text-left">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin (Opsional)</label>
                    <textarea id="swal-approve-notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Berikan catatan jika diperlukan..."></textarea>
                </div>
            </div>
        `,
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Setujui Pengembalian',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        preConfirm: () => {
            const notes = document.getElementById('swal-approve-notes').value;
            return { notes };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { notes } = result.value;
            submitApproveForm(notes);
        }
    });
}

function showRejectModal() {
    Swal.fire({
        title: 'Tolak Pengembalian',
        html: `
            <div class="text-left">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea id="swal-reject-notes" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Jelaskan alasan penolakan dengan detail..." required></textarea>
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Tolak Pengembalian',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        preConfirm: () => {
            const notes = document.getElementById('swal-reject-notes').value;
            if (!notes.trim()) {
                Swal.showValidationMessage('Alasan penolakan harus diisi');
                return false;
            }
            return { notes };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { notes } = result.value;
            submitRejectForm(notes);
        }
    });
}

function showRefundModal() {
    Swal.fire({
        title: 'Konfirmasi Pengembalian Dana',
        html: `
            <div class="text-left">
                <div class="alert alert-info mb-4">
                    <i class="fas fa-money-bill-wave mr-2"></i>
                    Konfirmasi bahwa dana sebesar <strong>Rp {{ number_format($pengembalian->jumlah_refund, 0, ',', '.') }}</strong>
                    telah ditransfer ke rekening pelanggan.
                </div>

                @if($pengembalian->bank_name)
                <div class="mb-4">
                    <strong>Informasi Transfer:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Bank: {{ $pengembalian->bank_name }}</li>
                        <li>No. Rekening: {{ $pengembalian->account_number }}</li>
                        <li>Nama: {{ $pengembalian->account_holder_name }}</li>
                    </ul>
                </div>
                @endif

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Transaksi <span class="text-red-500">*</span></label>
                    <input type="text" id="swal-transaction-number" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Masukkan nomor transaksi bank" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea id="swal-refund-notes" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Catatan tambahan..."></textarea>
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f97316',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Konfirmasi Transfer',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        preConfirm: () => {
            const transactionNumber = document.getElementById('swal-transaction-number').value;
            const notes = document.getElementById('swal-refund-notes').value;

            if (!transactionNumber.trim()) {
                Swal.showValidationMessage('Nomor transaksi harus diisi');
                return false;
            }

            return { transactionNumber, notes };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { transactionNumber, notes } = result.value;
            submitRefundForm(transactionNumber, notes);
        }
    });
}

// Form submission functions
function submitApproveForm(notes) {
    Swal.fire({
        title: 'Memproses...',
        text: 'Sedang menyetujui pengembalian',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.pengembalian.approve", $pengembalian->id) }}';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    if (notes) {
        const notesInput = document.createElement('input');
        notesInput.type = 'hidden';
        notesInput.name = 'catatan_admin';
        notesInput.value = notes;
        form.appendChild(notesInput);
    }

    document.body.appendChild(form);
    form.submit();
}

function submitRejectForm(notes) {
    Swal.fire({
        title: 'Memproses...',
        text: 'Sedang menolak pengembalian',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.pengembalian.reject", $pengembalian->id) }}';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    const notesInput = document.createElement('input');
    notesInput.type = 'hidden';
    notesInput.name = 'catatan_admin';
    notesInput.value = notes;
    form.appendChild(notesInput);

    document.body.appendChild(form);
    form.submit();
}

function submitRefundForm(transactionNumber, notes) {
    Swal.fire({
        title: 'Memproses...',
        text: 'Sedang mengkonfirmasi transfer dana',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.pengembalian.markRefunded", $pengembalian->id) }}';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    const transactionInput = document.createElement('input');
    transactionInput.type = 'hidden';
    transactionInput.name = 'nomor_transaksi_pengembalian';
    transactionInput.value = transactionNumber;
    form.appendChild(transactionInput);

    if (notes) {
        const notesInput = document.createElement('input');
        notesInput.type = 'hidden';
        notesInput.name = 'catatan_admin';
        notesInput.value = notes;
        form.appendChild(notesInput);
    }

    document.body.appendChild(form);
    form.submit();
}
</script>

function showRefundModal() {
    new bootstrap.Modal(document.getElementById('refundModal')).show();
}
</script>
@endpush

@push('styles')
<style>
    .avatar-lg {
        width: 60px;
        height: 60px;
    }

    .avatar-circle {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .badge {
        font-size: 0.8em;
    }

    .img-thumbnail {
        border: 1px solid #dee2e6;
        transition: transform 0.2s;
    }

    .img-thumbnail:hover {
        transform: scale(1.05);
    }

    code {
        background-color: #f8f9fa;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.9em;
    }
</style>
@endpush
