@extends('admin.layouts.app')

@section('title', 'Detail Pengajuan Pengembalian #' . $pengembalian->id_pengembalian)

@push('styles')
<style>
    /* === CSS CUSTOM PROPERTIES === */
    :root {
        --primary: #4f46e5;
        --primary-light: #6366f1;
        --primary-dark: #3730a3;
        --secondary: #6b7280;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #06b6d4;
        --light: #f8fafc;
        --dark: #1f2937;
        --border: #e5e7eb;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        --radius: 12px;
        --radius-lg: 16px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --animation-duration: 0.6s;
        --border-width: 1px;
        --glass-bg: rgba(255, 255, 255, 0.95);
        --glass-border: rgba(255, 255, 255, 0.2);
    }

    /* === BASE STYLES === */
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
    }

    .page-wrapper {
        animation: pageLoad var(--animation-duration) ease-out;
        padding: 2rem 0;
        min-height: 100vh;
    }

    /* === ANIMATIONS === */
    @keyframes pageLoad {
        from {
            opacity: 0;
            transform: translateY(20px);
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

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }

    /* === LAYOUT === */
    .main-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .page-header {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: var(--border-width) solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-xl);
        animation: slideInDown var(--animation-duration) ease-out;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(79, 70, 229, 0.1),
            transparent
        );
        animation: shimmer 3s infinite;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 2rem;
        position: relative;
        z-index: 1;
    }

    .header-info {
        flex: 1;
    }

    .header-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .header-subtitle {
        color: var(--secondary);
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
        flex-shrink: 0;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 2rem;
        align-items: start;
    }

    /* === CARDS === */
    .card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: var(--border-width) solid var(--glass-border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        transition: var(--transition);
        margin-bottom: 1.5rem;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-xl);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        overflow: hidden;
    }

    .card-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.2),
            transparent
        );
        transition: var(--transition);
    }

    .card:hover .card-header::before {
        left: 100%;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* === STATUS INDICATORS === */
    .status-badge {
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
    }

    .status-badge.pending {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        animation: pulse 2s infinite;
    }

    .status-badge.approved {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .status-badge.rejected {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    .status-badge::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0.75rem;
        transform: translateY(-50%);
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
        animation: pulse 2s infinite;
    }

    /* === BUTTONS === */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: var(--radius);
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.2),
            transparent
        );
        transition: var(--transition);
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-lg);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success) 0%, #34d399 100%);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger) 0%, #f87171 100%);
        color: white;
    }

    .btn-outline {
        background: transparent;
        border: 2px solid var(--border);
        color: var(--secondary);
    }

    .btn-outline:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: rgba(79, 70, 229, 0.05);
    }

    /* === INFO GRID === */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .info-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark);
        word-break: break-all;
    }

    .info-value.large {
        font-size: 1.25rem;
        color: var(--primary);
    }

    /* === CODE DISPLAY === */
    .code-display {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        border: 2px dashed var(--border);
        border-radius: var(--radius);
        padding: 1rem;
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--primary);
        cursor: pointer;
        transition: var(--transition);
        position: relative;
    }

    .code-display:hover {
        border-color: var(--primary);
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        transform: scale(1.02);
    }

    .code-display::after {
        content: 'Click to copy';
        position: absolute;
        top: -2rem;
        left: 50%;
        transform: translateX(-50%);
        background: var(--dark);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        opacity: 0;
        pointer-events: none;
        transition: var(--transition);
    }

    .code-display:hover::after {
        opacity: 1;
        top: -2.5rem;
    }

    /* === PHOTO GALLERY === */
    .photo-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .photo-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: var(--radius);
        overflow: hidden;
        cursor: pointer;
        transition: var(--transition);
        background: var(--light);
    }

    .photo-item:hover {
        transform: scale(1.05) rotate(2deg);
        box-shadow: var(--shadow-lg);
    }

    .photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .photo-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        opacity: 0;
        transition: var(--transition);
    }

    .photo-item:hover .photo-overlay {
        opacity: 1;
    }

    /* === TIMELINE === */
    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
        background: white;
        border-radius: var(--radius);
        padding: 1rem;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    .timeline-item:hover {
        box-shadow: var(--shadow-md);
        transform: translateX(0.25rem);
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.75rem;
        top: 1rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary);
        border: 3px solid white;
        box-shadow: var(--shadow-md);
    }

    .timeline-item.completed::before {
        background: var(--success);
    }

    .timeline-item.active::before {
        background: var(--warning);
        animation: pulse 2s infinite;
    }

    .timeline-title {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .timeline-date {
        font-size: 0.875rem;
        color: var(--secondary);
    }

    /* === SIDEBAR === */
    .sidebar {
        animation: slideInRight var(--animation-duration) ease-out 0.2s both;
    }

    .sidebar .card {
        position: sticky;
        top: 2rem;
    }

    /* === MAIN CONTENT === */
    .main-content {
        animation: slideInLeft var(--animation-duration) ease-out 0.1s both;
    }

    /* === UTILITY CLASSES === */
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .fw-bold { font-weight: 700; }
    .fw-semibold { font-weight: 600; }
    .fw-medium { font-weight: 500; }
    .text-primary { color: var(--primary); }
    .text-success { color: var(--success); }
    .text-danger { color: var(--danger); }
    .text-warning { color: var(--warning); }
    .text-muted { color: var(--secondary); }
    .mb-0 { margin-bottom: 0; }
    .mb-1 { margin-bottom: 0.5rem; }
    .mb-2 { margin-bottom: 1rem; }
    .mb-3 { margin-bottom: 1.5rem; }
    .mt-3 { margin-top: 1.5rem; }
    .d-flex { display: flex; }
    .align-items-center { align-items: center; }
    .justify-content-between { justify-content: space-between; }
    .gap-2 { gap: 1rem; }

    /* === RESPONSIVE DESIGN === */
    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .sidebar {
            order: -1;
        }
    }

    @media (max-width: 768px) {
        .main-container {
            padding: 0 0.75rem;
        }

        .page-header {
            padding: 1.5rem;
        }

        .header-content {
            flex-direction: column;
            gap: 1rem;
        }

        .header-actions {
            width: 100%;
            justify-content: stretch;
        }

        .header-actions .btn {
            flex: 1;
            justify-content: center;
        }

        .header-title {
            font-size: 1.5rem;
        }

        .card-body {
            padding: 1rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .photo-gallery {
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        }
    }

    @media (max-width: 480px) {
        .page-wrapper {
            padding: 1rem 0;
        }

        .page-header {
            margin-bottom: 1rem;
        }

        .card {
            margin-bottom: 1rem;
        }

        .btn {
            padding: 0.625rem 1rem;
            font-size: 0.8125rem;
        }
    }

    /* === DARK MODE SUPPORT === */
    @media (prefers-color-scheme: dark) {
        :root {
            --glass-bg: rgba(31, 41, 55, 0.95);
            --glass-border: rgba(255, 255, 255, 0.1);
            --light: #374151;
            --dark: #f9fafb;
            --border: #4b5563;
        }

        body {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        }

        .timeline-item {
            background: #374151;
        }
    }

    /* === PRINT STYLES === */
    @media print {
        .page-wrapper {
            background: white !important;
            padding: 0 !important;
        }

        .header-actions,
        .btn {
            display: none !important;
        }

        .card {
            box-shadow: none !important;
            border: 1px solid #e5e7eb !important;
            page-break-inside: avoid;
        }

        .content-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

@section('content')
<div class="page-wrapper">
    <div class="main-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-info">
                    <h1 class="header-title">
                        <i class="fas fa-receipt"></i>
                        Detail Pengajuan Pengembalian
                    </h1>
                    <p class="header-subtitle">
                        ID: <span class="fw-bold text-primary">#{{ $pengembalian->id_pengembalian }}</span> •
                        Diajukan pada {{ \Carbon\Carbon::parse($pengembalian->created_at)->format('d M Y, H:i') }}
                    </p>

                    @php
                        $statusMap = [
                            'pending' => ['color' => 'pending', 'icon' => 'clock', 'text' => 'Menunggu Review'],
                            'approved' => ['color' => 'approved', 'icon' => 'check-circle', 'text' => 'Disetujui'],
                            'rejected' => ['color' => 'rejected', 'icon' => 'times-circle', 'text' => 'Ditolak']
                        ];
                        $currentStatus = $statusMap[$pengembalian->status_pengembalian] ?? $statusMap['pending'];
                    @endphp

                    <div class="status-badge {{ $currentStatus['color'] }}">
                        <i class="fas fa-{{ $currentStatus['icon'] }}"></i>
                        {{ $currentStatus['text'] }}
                    </div>
                </div>

                <div class="header-actions">
                    @if($pengembalian->status_pengembalian == 'pending')
                    <button class="btn btn-success" onclick="approveRefund({{ $pengembalian->id_pengembalian }})">
                        <i class="fas fa-check"></i>
                        Setujui
                    </button>
                    <button class="btn btn-danger" onclick="rejectRefund({{ $pengembalian->id_pengembalian }})">
                        <i class="fas fa-times"></i>
                        Tolak
                    </button>
                    @endif
                    <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Main Content -->
            <div class="main-content">
                <!-- Transaction Details -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-shopping-cart"></i>
                        Informasi Transaksi
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">ID Transaksi</span>
                                <div class="code-display" onclick="copyToClipboard('{{ $pengembalian->id_transaksi }}')">
                                    {{ $pengembalian->id_transaksi }}
                                </div>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Jumlah Klaim</span>
                                <span class="info-value large">
                                    Rp {{ number_format($pengembalian->jumlah_klaim, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Tanggal Transaksi</span>
                                <span class="info-value">
                                    {{ \Carbon\Carbon::parse($pengembalian->tanggal_transaksi)->format('d M Y') }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Status</span>
                                <span class="status-badge {{ $currentStatus['color'] }}">
                                    {{ $currentStatus['text'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Information -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-user"></i>
                        Informasi Pengguna
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Nama</span>
                                <span class="info-value">{{ $pengembalian->user->name ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Email</span>
                                <span class="info-value">{{ $pengembalian->user->email ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">No. Telepon</span>
                                <span class="info-value">{{ $pengembalian->user->phone ?? 'N/A' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Tanggal Daftar</span>
                                <span class="info-value">
                                    {{ $pengembalian->user ? \Carbon\Carbon::parse($pengembalian->user->created_at)->format('d M Y') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Refund Reason -->
                @if($pengembalian->deskripsi_masalah)
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-comment-alt"></i>
                        Alasan Pengembalian
                    </div>
                    <div class="card-body">
                        <div style="background: #f8fafc; border-radius: 8px; padding: 1rem; border-left: 4px solid var(--primary);">
                            <p class="mb-0">{{ $pengembalian->deskripsi_masalah }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Evidence Photos -->
                @if($pengembalian->foto_bukti)
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-images"></i>
                        Foto Bukti
                    </div>
                    <div class="card-body">
                        @php
                            $photos = is_array($pengembalian->foto_bukti) ? $pengembalian->foto_bukti : json_decode($pengembalian->foto_bukti, true);
                        @endphp
                        @if($photos && count($photos) > 0)
                            <div class="photo-gallery">
                                @foreach($photos as $index => $photo)
                                <div class="photo-item" onclick="showPhotoModal('{{ Storage::url($photo) }}', 'Foto Bukti {{ $index + 1 }}')">
                                    <img src="{{ Storage::url($photo) }}" alt="Bukti {{ $index + 1 }}">
                                    <div class="photo-overlay">
                                        <i class="fas fa-search-plus"></i>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center">Tidak ada foto bukti</p>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Bank Information -->
                @if($pengembalian->nama_bank || $pengembalian->nomor_rekening)
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-university"></i>
                        Informasi Bank
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            @if($pengembalian->nama_bank)
                            <div class="info-item">
                                <span class="info-label">Nama Bank</span>
                                <span class="info-value">{{ $pengembalian->nama_bank }}</span>
                            </div>
                            @endif
                            @if($pengembalian->nomor_rekening)
                            <div class="info-item">
                                <span class="info-label">Nomor Rekening</span>
                                <div class="code-display" onclick="copyToClipboard('{{ $pengembalian->nomor_rekening }}')">
                                    {{ $pengembalian->nomor_rekening }}
                                </div>
                            </div>
                            @endif
                            @if($pengembalian->nama_pemilik_rekening)
                            <div class="info-item">
                                <span class="info-label">Nama Pemilik</span>
                                <span class="info-value">{{ $pengembalian->nama_pemilik_rekening }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- E-Wallet Information -->
                @if($pengembalian->nama_ewallet || $pengembalian->nomor_ewallet)
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-mobile-alt"></i>
                        Informasi E-Wallet
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            @if($pengembalian->nama_ewallet)
                            <div class="info-item">
                                <span class="info-label">Jenis E-Wallet</span>
                                <span class="info-value">{{ $pengembalian->nama_ewallet }}</span>
                            </div>
                            @endif
                            @if($pengembalian->nomor_ewallet)
                            <div class="info-item">
                                <span class="info-label">Nomor E-Wallet</span>
                                <div class="code-display" onclick="copyToClipboard('{{ $pengembalian->nomor_ewallet }}')">
                                    {{ $pengembalian->nomor_ewallet }}
                                </div>
                            </div>
                            @endif
                            @if($pengembalian->nama_pemilik_ewallet)
                            <div class="info-item">
                                <span class="info-label">Nama Pemilik</span>
                                <span class="info-value">{{ $pengembalian->nama_pemilik_ewallet }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Admin Notes -->
                @if($pengembalian->catatan_admin)
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-sticky-note"></i>
                        Catatan Admin
                    </div>
                    <div class="card-body">
                        <div style="background: #fef3c7; border-radius: 8px; padding: 1rem; border-left: 4px solid var(--warning);">
                            <p class="mb-0">{{ $pengembalian->catatan_admin }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Quick Summary -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-chart-pie"></i>
                        Ringkasan
                    </div>
                    <div class="card-body">
                        <div class="info-item mb-3">
                            <span class="info-label">Total Klaim</span>
                            <span class="info-value large text-success">
                                Rp {{ number_format($pengembalian->jumlah_klaim, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="info-item mb-3">
                            <span class="info-label">Status Saat Ini</span>
                            <span class="status-badge {{ $currentStatus['color'] }}">
                                {{ $currentStatus['text'] }}
                            </span>
                        </div>
                        <div class="info-item mb-3">
                            <span class="info-label">Waktu Pengajuan</span>
                            <span class="info-value">
                                {{ \Carbon\Carbon::parse($pengembalian->created_at)->diffForHumans() }}
                            </span>
                        </div>
                        @if($pengembalian->updated_at != $pengembalian->created_at)
                        <div class="info-item">
                            <span class="info-label">Terakhir Diperbarui</span>
                            <span class="info-value">
                                {{ \Carbon\Carbon::parse($pengembalian->updated_at)->diffForHumans() }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Process Timeline -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-history"></i>
                        Timeline Proses
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item completed">
                                <div class="timeline-title">Pengajuan Dibuat</div>
                                <div class="timeline-date">
                                    {{ \Carbon\Carbon::parse($pengembalian->created_at)->format('d M Y, H:i') }}
                                </div>
                            </div>

                            @if($pengembalian->status_pengembalian == 'pending')
                            <div class="timeline-item active">
                                <div class="timeline-title">Menunggu Review Admin</div>
                                <div class="timeline-date">Sedang dalam proses</div>
                            </div>
                            @else
                            <div class="timeline-item completed">
                                <div class="timeline-title">Review Admin Selesai</div>
                                <div class="timeline-date">
                                    {{ \Carbon\Carbon::parse($pengembalian->updated_at)->format('d M Y, H:i') }}
                                </div>
                            </div>

                            @if($pengembalian->status_pengembalian == 'approved')
                            <div class="timeline-item completed">
                                <div class="timeline-title">Pengajuan Disetujui</div>
                                <div class="timeline-date">Proses refund dimulai</div>
                            </div>
                            @elseif($pengembalian->status_pengembalian == 'rejected')
                            <div class="timeline-item completed">
                                <div class="timeline-title">Pengajuan Ditolak</div>
                                <div class="timeline-date">Proses selesai</div>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions (if pending) -->
                @if($pengembalian->status_pengembalian == 'pending')
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-bolt"></i>
                        Aksi Cepat
                    </div>
                    <div class="card-body">
                        <button class="btn btn-success w-100 mb-2" onclick="approveRefund({{ $pengembalian->id_pengembalian }})">
                            <i class="fas fa-check"></i>
                            Setujui Pengajuan
                        </button>
                        <button class="btn btn-danger w-100" onclick="rejectRefund({{ $pengembalian->id_pengembalian }})">
                            <i class="fas fa-times"></i>
                            Tolak Pengajuan
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="background: var(--glass-bg); backdrop-filter: blur(20px); border: none; border-radius: var(--radius-lg);">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="photoModalTitle">Foto Bukti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="photoModalImage" src="" alt="Foto Bukti" class="img-fluid" style="max-height: 70vh; border-radius: var(--radius);">
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <a id="photoDownloadLink" href="" download class="btn btn-primary">
                    <i class="fas fa-download"></i>
                    Download
                </a>
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// === UTILITY FUNCTIONS ===
function showToast(message, type = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Berhasil disalin ke clipboard!');
    }).catch(() => {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            document.execCommand('copy');
            showToast('Berhasil disalin ke clipboard!');
        } catch (err) {
            showToast('Gagal menyalin ke clipboard', 'error');
        }

        document.body.removeChild(textArea);
    });
}

function showPhotoModal(imageSrc, title) {
    document.getElementById('photoModalImage').src = imageSrc;
    document.getElementById('photoModalTitle').textContent = title;
    document.getElementById('photoDownloadLink').href = imageSrc;

    const modal = new bootstrap.Modal(document.getElementById('photoModal'));
    modal.show();
}

function showLoadingButton(button, originalText) {
    button.disabled = true;
    button.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        Memproses...
    `;

    return () => {
        button.disabled = false;
        button.innerHTML = originalText;
    };
}

// === MAIN FUNCTIONS ===
function approveRefund(id) {
    Swal.fire({
        title: 'Konfirmasi Persetujuan',
        text: 'Apakah Anda yakin ingin menyetujui pengajuan pengembalian ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-check"></i> Ya, Setujui',
        cancelButtonText: '<i class="fas fa-times"></i> Batal',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-success me-2',
            cancelButton: 'btn btn-outline-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            const loadingAlert = Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memproses persetujuan',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Make AJAX request
            fetch(`{{ route('admin.pengembalian.updateStatus', '') }}/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: 'approved'
                })
            })
            .then(response => response.json())
            .then(data => {
                loadingAlert.close();

                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Pengajuan pengembalian telah disetujui',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                loadingAlert.close();
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan saat memproses permintaan',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

function rejectRefund(id) {
    Swal.fire({
        title: 'Tolak Pengajuan',
        text: 'Berikan alasan penolakan:',
        input: 'textarea',
        inputLabel: 'Catatan Admin',
        inputPlaceholder: 'Masukkan alasan penolakan...',
        inputAttributes: {
            'aria-label': 'Alasan penolakan',
            'style': 'height: 120px; resize: vertical;'
        },
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-times"></i> Tolak',
        cancelButtonText: '<i class="fas fa-arrow-left"></i> Batal',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton: 'btn btn-outline-secondary'
        },
        buttonsStyling: false,
        inputValidator: (value) => {
            if (!value || value.trim().length < 10) {
                return 'Alasan penolakan harus diisi minimal 10 karakter!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            const loadingAlert = Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memproses penolakan',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Make AJAX request
            fetch(`{{ route('admin.pengembalian.updateStatus', '') }}/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    status: 'rejected',
                    catatan_admin: result.value
                })
            })
            .then(response => response.json())
            .then(data => {
                loadingAlert.close();

                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Pengajuan pengembalian telah ditolak',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                loadingAlert.close();
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan saat memproses permintaan',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

// === EVENT LISTENERS ===
document.addEventListener('DOMContentLoaded', function() {
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl + Backspace = Back to list
        if (e.ctrlKey && e.key === 'Backspace') {
            e.preventDefault();
            window.location.href = '{{ route("admin.pengembalian.index") }}';
        }

        // Escape = Close modals
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                const modal = bootstrap.Modal.getInstance(openModal);
                if (modal) modal.hide();
            }
        }
    });

    // Animate elements on scroll
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

    // Observe all cards
    document.querySelectorAll('.card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(card);
    });

    // Enhanced copy functionality for code displays
    document.querySelectorAll('.code-display').forEach(code => {
        code.addEventListener('click', function() {
            const text = this.textContent.trim();
            copyToClipboard(text);

            // Visual feedback
            const original = this.style.background;
            this.style.background = 'linear-gradient(135deg, #d1fae5, #a7f3d0)';
            this.style.transform = 'scale(1.05)';

            setTimeout(() => {
                this.style.background = original;
                this.style.transform = 'scale(1)';
            }, 300);
        });
    });

    // Photo gallery enhancements
    document.querySelectorAll('.photo-item').forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05) rotate(2deg)';
        });

        item.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
        });
    });

    // Show page load success message
    setTimeout(() => {
        showToast('Detail pengajuan berhasil dimuat', 'info');
    }, 500);
});

// Handle window resize for responsive adjustments
window.addEventListener('resize', function() {
    // Adjust layout if needed
    const contentGrid = document.querySelector('.content-grid');
    if (window.innerWidth <= 1024) {
        contentGrid.style.gridTemplateColumns = '1fr';
    } else {
        contentGrid.style.gridTemplateColumns = '1fr 350px';
    }
});

// Print functionality
function printPage() {
    window.print();
}

// Share functionality (if needed)
function shareDetails() {
    if (navigator.share) {
        navigator.share({
            title: 'Detail Pengajuan Pengembalian',
            text: `Detail pengajuan pengembalian #{{ $pengembalian->id_pengembalian }}`,
            url: window.location.href
        });
    } else {
        copyToClipboard(window.location.href);
        showToast('Link detail telah disalin!');
    }
}
</script>
@endpush
