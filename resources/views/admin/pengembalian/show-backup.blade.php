@extends('admin.layouts.app')

@section('title', 'Detail Pengajuan Pengembalian #' . $pengembalian->id)

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --warning-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        --danger-gradient: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
        --glass-bg: rgba(255, 255, 255, 0.25);
        --glass-border: rgba(255, 255, 255, 0.18);
        --shadow-soft: 0 8px 32px rgba(31, 38, 135, 0.37);
        --shadow-hover: 0 15px 35px rgba(31, 38, 135, 0.2);
        --border-radius: 16px;
        --animation-speed: 0.4s;
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .page-container {
        animation: fadeIn 0.8s ease-out;
        padding: 2rem 0;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInDown {
        from { opacity: 0; transform: translateY(-30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-soft);
        transition: all var(--animation-speed) cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .glass-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        opacity: 0;
        transition: opacity var(--animation-speed) ease;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-hover);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .glass-card:hover::before {
        opacity: 1;
    }

    .detail-header {
        background: var(--primary-gradient);
        color: white;
        padding: 2rem;
        border-radius: var(--border-radius) var(--border-radius) 0 0;
        position: relative;
        overflow: hidden;
        animation: slideInDown 0.6s ease-out;
    }

    .detail-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: shimmer 3s ease-in-out infinite;
    }

    @keyframes shimmer {
        0%, 100% { transform: rotate(0deg); }
        50% { transform: rotate(180deg); }
    }

    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        transition: all var(--animation-speed) ease;
    }

    .status-indicator.pending {
        background: var(--warning-gradient);
        color: #8b5a00;
        animation: pulse 2s infinite;
    }

    .status-indicator.approved {
        background: var(--success-gradient);
        color: #065f46;
    }

    .status-indicator.rejected {
        background: var(--danger-gradient);
        color: #7f1d1d;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        padding: 2rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        animation: slideInLeft 0.6s ease-out;
        animation-fill-mode: both;
    }

    .info-item:nth-child(even) {
        animation-name: slideInRight;
    }

    .info-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.8);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: white;
        word-break: break-all;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        padding: 1.5rem 2rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 0 0 var(--border-radius) var(--border-radius);
        animation: slideInDown 0.8s ease-out;
    }

    .btn-action {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 50px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all var(--animation-speed) cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-action::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: all 0.6s ease;
    }

    .btn-action:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .btn-success {
        background: var(--success-gradient);
        color: white;
    }

    .btn-danger {
        background: var(--danger-gradient);
        color: white;
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .section-card {
        margin-bottom: 2rem;
        animation: fadeIn 0.8s ease-out;
        animation-fill-mode: both;
    }

    .section-card:nth-child(odd) {
        animation-delay: 0.1s;
    }

    .section-card:nth-child(even) {
        animation-delay: 0.2s;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: white;
        margin-bottom: 1rem;
        padding: 1rem 2rem;
        background: var(--secondary-gradient);
        border-radius: var(--border-radius) var(--border-radius) 0 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        overflow: hidden;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
        animation: slideInLeft 1s ease-out;
    }

    .detail-content {
        padding: 2rem;
        background: rgba(255, 255, 255, 0.95);
        color: #374151;
        border-radius: 0 0 var(--border-radius) var(--border-radius);
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        transition: all var(--animation-speed) ease;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-row:hover {
        background: rgba(99, 102, 241, 0.05);
        padding-left: 1rem;
        border-radius: 8px;
    }

    .detail-label {
        font-weight: 600;
        color: #4b5563;
        font-size: 0.9rem;
        min-width: 150px;
    }

    .detail-value {
        color: #1f2937;
        font-weight: 500;
        flex: 1;
        text-align: right;
    }

    .image-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .image-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        transition: all var(--animation-speed) ease;
        cursor: pointer;
    }

    .image-item:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-hover);
    }

    .image-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        transition: all var(--animation-speed) ease;
    }

    .image-item:hover img {
        transform: scale(1.1);
    }

    .back-button {
        position: fixed;
        top: 2rem;
        left: 2rem;
        z-index: 1000;
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all var(--animation-speed) ease;
        animation: slideInLeft 0.6s ease-out;
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateX(-5px);
        color: white;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
            padding: 1.5rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.75rem;
        }

        .detail-row {
            flex-direction: column;
            gap: 0.5rem;
        }

        .detail-value {
            text-align: left;
        }

        .back-button {
            position: relative;
            top: auto;
            left: auto;
            margin-bottom: 1rem;
        }
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .detail-content {
            background: rgba(31, 41, 55, 0.95);
            color: #f3f4f6;
        }

        .detail-label {
            color: #d1d5db;
        }

        .detail-value {
            color: #f9fafb;
        }
    }
</style>

    .btn-compact {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        border: none;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-compact:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }

    .copy-btn {
        background: none;
        border: none;
        color: #6b7280;
        padding: 0.25rem;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .copy-btn:hover {
        color: #374151;
    }

    .timeline-item {
        position: relative;
        padding-left: 2rem;
        padding-bottom: 1.5rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0.5rem;
        width: 8px;
        height: 8px;
        background: #6366f1;
        border-radius: 50%;
        transform: translateX(-50%);
    }

    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 1rem;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
        transform: translateX(-50%);
    }

    @media (max-width: 768px) {
        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header -->
    <div class="compact-card">
        <div class="compact-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="h4 mb-1 fw-bold">
                        <i class="fas fa-undo-alt me-2"></i>
                        Detail Pengembalian #{{ $pengembalian->id }}
                    </h1>
                    <p class="mb-0 opacity-90">Informasi lengkap pengajuan pengembalian dana</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-light btn-compact">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                    @if($pengembalian->status == 'Menunggu Review')
                        <button type="button" class="btn btn-success btn-compact" onclick="approveRefund({{ $pengembalian->id }})">
                            <i class="fas fa-check"></i>
                            Setujui
                        </button>
                        <button type="button" class="btn btn-danger btn-compact" onclick="rejectRefund({{ $pengembalian->id }})">
                            <i class="fas fa-times"></i>
                            Tolak
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Refund Details -->
        <div class="col-lg-8">
            <div class="compact-card">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Informasi Pengembalian
                    </h6>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-hashtag me-1"></i>
                            ID Pengembalian
                        </span>
                        <span class="info-value d-flex align-items-center">
                            #{{ $pengembalian->id }}
                            <button class="copy-btn ms-2" onclick="copyToClipboard('#{{ $pengembalian->id }}')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-shopping-cart me-1"></i>
                            ID Pesanan
                        </span>
                        <span class="info-value d-flex align-items-center">
                            #{{ $pengembalian->pesanan->id_pesanan ?? 'N/A' }}
                            <button class="copy-btn ms-2" onclick="copyToClipboard('#{{ $pengembalian->pesanan->id_pesanan ?? '' }}')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-money-bill-wave me-1"></i>
                            Jumlah Pengembalian
                        </span>
                        <span class="info-value fw-bold text-danger">
                            Rp {{ number_format($pengembalian->jumlah_pengembalian, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-flag me-1"></i>
                            Status
                        </span>
                        <span class="info-value">
                            @if($pengembalian->status == 'Menunggu Review')
                                <span class="status-badge bg-warning text-dark">
                                    <i class="fas fa-clock me-1"></i>{{ $pengembalian->status }}
                                </span>
                            @elseif($pengembalian->status == 'Disetujui')
                                <span class="status-badge bg-success text-white">
                                    <i class="fas fa-check me-1"></i>{{ $pengembalian->status }}
                                </span>
                            @elseif($pengembalian->status == 'Ditolak')
                                <span class="status-badge bg-danger text-white">
                                    <i class="fas fa-times me-1"></i>{{ $pengembalian->status }}
                                </span>
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-calendar me-1"></i>
                            Tanggal Pengajuan
                        </span>
                        <span class="info-value">
                            {{ $pengembalian->created_at->format('d F Y, H:i') }} WIB
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-comment-alt me-1"></i>
                            Alasan Pengembalian
                        </span>
                        <span class="info-value">
                            <div class="bg-light p-3 rounded mt-2" style="max-width: 100%;">
                                {{ $pengembalian->alasan }}
                            </div>
                        </span>
                    </div>
                    @if($pengembalian->alasan_penolakan)
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-ban me-1"></i>
                            Alasan Penolakan
                        </span>
                        <span class="info-value">
                            <div class="bg-danger-subtle p-3 rounded mt-2 text-danger">
                                {{ $pengembalian->alasan_penolakan }}
                            </div>
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="compact-card">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-user me-2 text-primary"></i>
                        Informasi Pelanggan
                    </h6>
                </div>
                <div class="card-body">
                    @if($pengembalian->pesanan && $pengembalian->pesanan->user)
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-user-circle me-1"></i>
                                Nama Lengkap
                            </span>
                            <span class="info-value">{{ $pengembalian->pesanan->user->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-envelope me-1"></i>
                                Email
                            </span>
                            <span class="info-value">{{ $pengembalian->pesanan->user->email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">
                                <i class="fas fa-phone me-1"></i>
                                No. Telepon
                            </span>
                            <span class="info-value">{{ $pengembalian->pesanan->user->no_hp ?? 'Tidak tersedia' }}</span>
                        </div>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Data pelanggan tidak tersedia
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Order Details & Timeline -->
        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="compact-card">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-receipt me-2 text-primary"></i>
                        Ringkasan Pesanan
                    </h6>
                </div>
                <div class="card-body">
                    @if($pengembalian->pesanan)
                        <div class="info-row">
                            <span class="info-label">Total Pesanan</span>
                            <span class="info-value fw-bold">
                                Rp {{ number_format($pengembalian->pesanan->total_harga, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status Pesanan</span>
                            <span class="info-value">
                                <span class="badge bg-info">{{ $pengembalian->pesanan->status }}</span>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Metode Pembayaran</span>
                            <span class="info-value">{{ $pengembalian->pesanan->metode_pembayaran ?? 'N/A' }}</span>
                        </div>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Data pesanan tidak tersedia
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="compact-card">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-history me-2 text-primary"></i>
                        Timeline
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline-item">
                        <div class="fw-semibold">Pengajuan Dibuat</div>
                        <small class="text-muted">{{ $pengembalian->created_at->format('d M Y, H:i') }}</small>
                        <div class="small mt-1">Pelanggan mengajukan pengembalian dana</div>
                    </div>

                    @if($pengembalian->status == 'Disetujui')
                        <div class="timeline-item">
                            <div class="fw-semibold text-success">Disetujui</div>
                            <small class="text-muted">{{ $pengembalian->updated_at->format('d M Y, H:i') }}</small>
                            <div class="small mt-1">Pengajuan pengembalian telah disetujui</div>
                        </div>
                    @elseif($pengembalian->status == 'Ditolak')
                        <div class="timeline-item">
                            <div class="fw-semibold text-danger">Ditolak</div>
                            <small class="text-muted">{{ $pengembalian->updated_at->format('d M Y, H:i') }}</small>
                            <div class="small mt-1">Pengajuan pengembalian ditolak</div>
                        </div>
                    @else
                        <div class="timeline-item">
                            <div class="fw-semibold text-warning">Menunggu Review</div>
                            <small class="text-muted">Sedang berlangsung</small>
                            <div class="small mt-1">Menunggu review dari admin</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bank Information (if available) -->
            @if($pengembalian->bank_name || $pengembalian->account_number)
            <div class="compact-card">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 fw-bold">
                        <i class="fas fa-university me-2 text-primary"></i>
                        Informasi Bank
                    </h6>
                </div>
                <div class="card-body">
                    @if($pengembalian->bank_name)
                        <div class="info-row">
                            <span class="info-label">Nama Bank</span>
                            <span class="info-value">{{ $pengembalian->bank_name }}</span>
                        </div>
                    @endif
                    @if($pengembalian->account_number)
                        <div class="info-row">
                            <span class="info-label">No. Rekening</span>
                            <span class="info-value d-flex align-items-center">
                                {{ $pengembalian->account_number }}
                                <button class="copy-btn ms-2" onclick="copyToClipboard('{{ $pengembalian->account_number }}')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </span>
                        </div>
                    @endif
                    @if($pengembalian->account_name)
                        <div class="info-row">
                            <span class="info-label">Nama Rekening</span>
                            <span class="info-value">{{ $pengembalian->account_name }}</span>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data berhasil disalin ke clipboard',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });
    }).catch(function() {
        Swal.fire({
            title: 'Gagal!',
            text: 'Gagal menyalin data ke clipboard',
            icon: 'error',
            timer: 1500,
            showConfirmButton: false
        });
    });
}

// Approve refund
function approveRefund(id) {
    Swal.fire({
        title: 'Konfirmasi Persetujuan',
        text: 'Apakah Anda yakin ingin menyetujui pengembalian ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Setujui',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memproses persetujuan',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/pengembalian/${id}/approve`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Reject refund
function rejectRefund(id) {
    Swal.fire({
        title: 'Tolak Pengembalian',
        html: `
            <div class="text-start">
                <label class="form-label fw-bold">Alasan Penolakan:</label>
                <textarea id="swal-rejection-reason" class="form-control" rows="4"
                          placeholder="Berikan alasan yang jelas..."></textarea>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Tolak',
        cancelButtonText: 'Batal',
        preConfirm: () => {
            const reason = document.getElementById('swal-rejection-reason').value.trim();
            if (!reason) {
                Swal.showValidationMessage('Alasan penolakan harus diisi!');
                return false;
            }
            return reason;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memproses penolakan',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/pengembalian/${id}/reject`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'alasan_penolakan';
            reasonInput.value = result.value;

            form.appendChild(csrfToken);
            form.appendChild(reasonInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.7s ease;
    }

    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-hover-shadow);
    }

    .modern-card:hover::before {
        left: 100%;
    }

    .card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.05));
        border-radius: var(--border-radius) var(--border-radius) 0 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header i {
        font-size: 1.25rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .card-header span {
        font-size: 1.125rem;
        font-weight: 600;
        color: #374151;
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
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-primary { background: var(--primary-gradient); color: white; }
    .btn-secondary { background: #e5e7eb; color: #374151; border: 2px solid #d1d5db; }
    .btn-success { background: var(--success-gradient); color: white; }
    .btn-danger { background: var(--danger-gradient); color: white; }
    .btn-warning { background: var(--warning-gradient); color: white; }
    .btn-info { background: var(--info-gradient); color: white; }

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
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; }
        50% { left: 100%; }
        100% { left: 100%; }
    }

    .badge-warning { background: var(--warning-gradient); color: white; }
    .badge-info { background: var(--info-gradient); color: white; }
    .badge-success { background: var(--success-gradient); color: white; }
    .badge-danger { background: var(--danger-gradient); color: white; }
    .badge-primary { background: var(--primary-gradient); color: white; }
    .badge-dark { background: var(--dark-gradient); color: white; }
    .badge-secondary { background: #6b7280; color: white; }

    .status-display {
        text-align: center;
        margin-bottom: 2rem;
    }

    .status-display .modern-badge {
        font-size: 0.875rem;
        padding: 0.75rem 1.5rem;
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .8; }
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        letter-spacing: 0.025em;
    }

    .modern-input,
    .modern-select,
    .modern-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 12px;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        color: #374151;
    }

    .modern-input:focus,
    .modern-select:focus,
    .modern-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
        background: rgba(255, 255, 255, 0.95);
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }

    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--primary-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.25rem;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
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
        color: #374151;
        margin: 0 0 0.25rem 0;
    }

    .user-info p {
        color: #6b7280;
        margin: 0;
        font-size: 0.875rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item:hover {
        background: rgba(102, 126, 234, 0.05);
        margin: 0 -1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        border-radius: 8px;
    }

    .info-item strong {
        color: #374151;
        font-weight: 600;
        min-width: 120px;
    }

    .modern-code {
        background: rgba(255, 255, 255, 0.5);
        padding: 0.25rem 0.75rem;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        color: #374151;
        border: 1px solid rgba(255, 255, 255, 0.3);
        font-weight: 600;
        backdrop-filter: blur(5px);
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

    .info-link {
        color: #667eea;
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
        background: var(--primary-gradient);
        transition: width 0.3s ease;
    }

    .info-link:hover::after {
        width: 100%;
    }

    .amount {
        font-weight: 700;
        color: #374151;
    }

    .amount.highlight {
        color: #667eea;
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
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    }

    .section-divider span {
        padding: 0 1rem;
        font-weight: 600;
        color: #6b7280;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(5px);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-radius: 20px;
    }

    .modern-table-container {
        overflow: hidden;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        background: transparent;
    }

    .modern-table thead {
        background: rgba(255, 255, 255, 0.5);
    }

    .modern-table th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid rgba(255, 255, 255, 0.3);
    }

    .modern-table td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        vertical-align: middle;
        transition: all 0.3s ease;
    }

    .modern-table tbody tr {
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background: rgba(102, 126, 234, 0.05);
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
        border: 2px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }

    .product-thumbnail:hover {
        border-color: #667eea;
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .product-details strong {
        color: #374151;
        font-weight: 600;
    }

    .description-content {
        background: rgba(255, 255, 255, 0.5);
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        margin-top: 0.75rem;
        line-height: 1.6;
        color: #374151;
        font-size: 0.95rem;
        backdrop-filter: blur(5px);
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
        border: 2px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .photo-item:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        border-color: #667eea;
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
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.05));
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid rgba(245, 158, 11, 0.3);
        border-left: 4px solid #f59e0b;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(5px);
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

    .full-width {
        width: 100%;
    }

    /* Alert styles */
    .alert-success {
        padding: 1rem;
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border: 1px solid #34d399;
        border-radius: 8px;
        color: #065f46;
        margin-bottom: 1rem;
    }

    .alert-warning {
        padding: 1rem;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 1px solid #f59e0b;
        border-radius: 8px;
        color: #92400e;
        margin-bottom: 1rem;
    }

    .alert-primary {
        padding: 1rem;
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border: 1px solid #3b82f6;
        border-radius: 8px;
        color: #1e40af;
        margin-bottom: 1rem;
    }

    .alert-info {
        padding: 1rem;
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        border: 1px solid #0ea5e9;
        border-radius: 8px;
        color: #0c4a6e;
        margin-bottom: 1rem;
    }

    .modern-modal .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        backdrop-filter: blur(20px);
        background: rgba(255, 255, 255, 0.95);
    }

    .modern-modal .modal-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        padding: 1.5rem 2rem;
    }

    .modern-modal .modal-body {
        padding: 2rem;
    }

    .modern-modal .modal-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        padding: 1.5rem 2rem;
    }

    .transfer-info {
        background: rgba(255, 255, 255, 0.5);
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        margin-bottom: 1rem;
    }

    .transfer-info ul {
        margin: 0.5rem 0 0 0;
        padding-left: 1.5rem;
    }

    .required {
        color: #dc2626;
    }

    .sidebar-column {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .main-column {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    /* Custom scrollbar for photo gallery */
    .photo-gallery::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .photo-gallery::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }

    .photo-gallery::-webkit-scrollbar-thumb {
        background: var(--primary-gradient);
        border-radius: 10px;
    }

    .photo-gallery::-webkit-scrollbar-thumb:hover {
        background: var(--dark-gradient);
    }
</style>

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
window.showApproveModal = function() {
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

window.showRejectModal = function() {
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

window.showRefundModal = function() {
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
window.submitApproveForm = function(notes) {
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

window.submitRejectForm = function(notes) {
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

window.submitRefundForm = function(transactionNumber, notes) {
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

// Animate cards on scroll
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

// Observe all modern cards
document.querySelectorAll('.modern-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(card);
});

// Enhanced photo gallery interaction
document.querySelectorAll('.photo-item').forEach(item => {
    item.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.05) rotate(2deg)';
    });

    item.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1) rotate(0deg)';
    });
});

// Copy to clipboard functionality for account numbers and transaction numbers
document.querySelectorAll('.modern-code').forEach(code => {
    code.style.cursor = 'pointer';
    code.title = 'Klik untuk menyalin';

    code.addEventListener('click', function() {
        navigator.clipboard.writeText(this.textContent).then(() => {
            // Show temporary success message
            const original = this.textContent;
            this.textContent = '✓ Disalin!';
            this.style.background = 'linear-gradient(135deg, #d1fae5, #a7f3d0)';
            this.style.color = '#065f46';

            setTimeout(() => {
                this.textContent = original;
                this.style.background = '';
                this.style.color = '';
            }, 2000);
        });
    });
});
</script>
@endpush

@push('styles')
<style>
    /* Additional component styles for better integration */
    .animate__animated {
        animation-duration: 0.5s;
        animation-fill-mode: both;
    }

    .animate__fadeInDown {
        animation-name: fadeInDown;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translate3d(0, -20px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    /* Enhanced SweetAlert2 integration */
    .swal2-popup {
        border-radius: 16px !important;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25) !important;
    }

    .swal2-title {
        font-weight: 600 !important;
        color: #374151 !important;
    }

    .swal2-content {
        color: #6b7280 !important;
    }

    .btn-confirm {
        background: var(--primary-gradient) !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 0.75rem 1.5rem !important;
        font-weight: 600 !important;
    }

    .btn-cancel {
        background: #e5e7eb !important;
        color: #374151 !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 0.75rem 1.5rem !important;
        font-weight: 600 !important;
    }

    /* Loading animation */
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive improvements */
    @media (max-width: 640px) {
        .header-content {
            padding: 1.5rem;
        }

        .header-info h1 {
            font-size: 1.5rem;
        }

        .content-grid {
            padding: 0 1rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .modern-table th,
        .modern-table td {
            padding: 0.75rem 1rem;
        }

        .photo-gallery {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }
    }

    /* Print styles */
    @media print {
        .header-actions,
        .modern-btn,
        button {
            display: none !important;
        }

        .modern-container {
            background: white !important;
        }

        .modern-card {
            box-shadow: none !important;
            border: 1px solid #e5e7eb !important;
        }

        .gradient-header {
            background: #f3f4f6 !important;
            color: #374151 !important;
        }
    }
</style>
@endpush

@endsection
