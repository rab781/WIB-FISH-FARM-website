@extends('admin.layouts.app')

@section('title', 'Detail Pengajuan Pengembalian #' . $pengembalian->id_pengembalian)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    :root {
        --primary:rgb(244, 117, 39);
        --primary-dark:rgb(177, 110, 17);
        --primary-light: #e0e7ff;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #06b6d4;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;
        --white: #ffffff;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        --border-radius: 12px;
    }

    * {
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        min-height: 100vh;
        line-height: 1.6;
    }

    .main-container {
        padding: 2rem 0;
        min-height: 100vh;
    }

    .page-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        margin-bottom: 2rem;
        overflow: hidden;
        position: relative;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--info));
    }

    .header-content {
        padding: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-info h1 {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--gray-800);
        margin: 0 0 0.5rem 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-info h1 i {
        color: var(--primary);
        background: var(--primary-light);
        padding: 0.5rem;
        border-radius: 50%;
        font-size: 1rem;
    }

    .header-info p {
        color: var(--gray-600);
        margin: 0;
        font-size: 1rem;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: var(--border-radius);
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: var(--white);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success), #059669);
        color: var(--white);
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger), #dc2626);
        color: var(--white);
    }

    .btn-secondary {
        background: var(--gray-100);
        color: var(--gray-700);
        border: 1px solid var(--gray-300);
    }

    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-md);
        margin-bottom: 2rem;
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.7s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
    }

    .card:hover::before {
        left: 100%;
    }

    .card-header {
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, var(--gray-50), var(--white));
        border-bottom: 1px solid var(--gray-200);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header i {
        color: var(--primary);
        font-size: 1.25rem;
        background: var(--primary-light);
        padding: 0.5rem;
        border-radius: 50%;
    }

    .card-header h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--gray-800);
        margin: 0;
    }

    .card-body {
        padding: 2rem;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1rem 0;
        border-bottom: 1px solid var(--gray-200);
        transition: all 0.3s ease;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item:hover {
        background: var(--primary-light);
        margin: 0 -2rem;
        padding-left: 2rem;
        padding-right: 2rem;
        border-radius: 8px;
    }

    .info-label {
        font-weight: 600;
        color: var(--gray-600);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 180px;
    }

    .info-label i {
        color: var(--primary);
        width: 16px;
        text-align: center;
    }

    .info-value {
        color: var(--gray-800);
        font-weight: 500;
        text-align: right;
        flex: 1;
        word-break: break-all;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }

    .status-pending {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        border: 1px solid #f59e0b;
    }

    .status-approved {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        border: 1px solid #10b981;
    }

    .status-rejected {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border: 1px solid #ef4444;
    }

    .amount-highlight {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--danger);
    }

    .copy-btn {
        background: none;
        border: none;
        color: var(--gray-400);
        padding: 0.25rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 4px;
    }

    .copy-btn:hover {
        color: var(--primary);
        background: var(--primary-light);
        transform: scale(1.1);
    }

    .description-box {
        background: var(--gray-50);
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        padding: 1rem;
        margin-top: 0.5rem;
        line-height: 1.6;
        color: var(--gray-700);
    }

    .photo-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .photo-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
        border: 2px solid var(--gray-200);
    }

    .photo-item:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary);
    }

    .photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.3s ease;
    }

    .photo-item .media-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: all 0.3s ease;
    }

    .video-item video {
        pointer-events: none; /* Prevent video controls from interfering */
    }

    .video-badge {
        background: linear-gradient(135deg, #dc2626, #ef4444) !important;
        font-size: 0.65rem !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
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
        color: var(--white);
        font-size: 1.5rem;
    }

    .photo-number {
        position: absolute;
        top: 4px;
        right: 4px;
        background: var(--primary);
        color: var(--white);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 600;
    }

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
        background: var(--gray-300);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        background: var(--white);
        border-radius: 8px;
        padding: 1rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.5rem;
        top: 1rem;
        width: 12px;
        height: 12px;
        background: var(--primary);
        border-radius: 50%;
        border: 3px solid var(--white);
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .timeline-date {
        font-size: 0.75rem;
        color: var(--gray-500);
        font-weight: 500;
    }

    .timeline-title {
        font-weight: 600;
        color: var(--gray-800);
        margin: 0.25rem 0;
    }

    .timeline-desc {
        font-size: 0.875rem;
        color: var(--gray-600);
        margin: 0;
    }

    .admin-note {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border: 1px solid #f59e0b;
        border-left: 4px solid #f59e0b;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .admin-note .note-title {
        font-weight: 600;
        color: #92400e;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .admin-note .note-content {
        color: #92400e;
        margin: 0;
        font-size: 0.875rem;
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--gray-50);
        border-radius: 8px;
        border: 1px solid var(--gray-200);
        margin-bottom: 1rem;
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: var(--white);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.25rem;
        box-shadow: var(--shadow-md);
    }

    .user-info h4 {
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
        font-weight: 600;
        color: var(--gray-800);
    }

    .user-info p {
        margin: 0;
        font-size: 0.875rem;
        color: var(--gray-600);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--gray-500);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--gray-400);
    }

    /* SweetAlert2 Custom Styles for Photo/Video Modal */
    /* Photo gallery hover effects */
    .photo-item {
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: var(--shadow-md);
    }

    .photo-item:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: var(--shadow-xl);
    }

    .photo-item:hover .photo-overlay {
        opacity: 1;
        visibility: visible;
    }

    .photo-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        border-radius: 12px;
    }

    .photo-overlay i {
        color: white;
        font-size: 2rem;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }

    .photo-number {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .video-badge {
        background: rgba(244, 117, 39, 0.9) !important;
    }

    .media-preview {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .video-item .media-preview {
        filter: brightness(0.9);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .main-container {
            padding: 1rem 0;
        }

        .content-grid {
            padding: 0 1rem;
        }

        .header-content {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem;
        }

        .header-actions {
            justify-content: center;
        }

        .info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .info-value {
            text-align: left;
        }

        .photo-gallery {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        }
    }

    @media (max-width: 480px) {
        .card-body {
            padding: 1rem;
        }

        .header-info h1 {
            font-size: 1.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
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

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .page-header {
        animation: fadeInUp 0.6s ease-out;
    }

    .card:nth-child(odd) {
        animation: fadeInLeft 0.6s ease-out;
        animation-delay: 0.1s;
        animation-fill-mode: both;
    }

    .card:nth-child(even) {
        animation: fadeInRight 0.6s ease-out;
        animation-delay: 0.2s;
        animation-fill-mode: both;
    }

    /* Loading States */
    .loading {
        opacity: 0.7;
        pointer-events: none;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid transparent;
        border-top: 2px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* SweetAlert2 Custom Styles */
    .swal2-large-modal {
        max-width: 90vw !important;
        max-height: 90vh !important;
        padding: 1.5rem !important;
    }

    .swal2-image-container,
    .swal2-video-container {
        padding: 0 !important;
        margin: 0 !important;
    }

    .swal2-image-container img {
        max-width: 100% !important;
        max-height: 75vh !important;
        object-fit: contain !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    }

    .swal2-video-container video {
        max-width: 100% !important;
        max-height: 75vh !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
    }

    .swal2-popup.swal2-large-modal .swal2-title {
        font-size: 1.5rem !important;
        color: var(--gray-800) !important;
        margin-bottom: 1rem !important;
        font-weight: 600 !important;
    }

    .swal2-popup.swal2-large-modal .swal2-close {
        font-size: 2rem !important;
        color: var(--gray-500) !important;
        background: rgba(0,0,0,0.05) !important;
        border-radius: 50% !important;
        width: 40px !important;
        height: 40px !important;
        line-height: 40px !important;
        right: 15px !important;
        top: 15px !important;
        transition: all 0.3s ease !important;
    }

    .swal2-popup.swal2-large-modal .swal2-close:hover {
        background: rgba(0,0,0,0.1) !important;
        color: var(--danger) !important;
        transform: scale(1.1) !important;
    }

    /* Media action buttons */
    .media-action-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 15px;
        flex-wrap: wrap;
    }

    .media-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.875rem;
    }

    .media-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .media-action-btn.download {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
    }

    .media-action-btn.open-tab {
        background: linear-gradient(135deg, var(--info), #0284c7);
        color: white;
    }

    .media-action-btn.fullscreen {
        background: linear-gradient(135deg, var(--success), #059669);
        color: white;
    }

    /* Fullscreen modal styles */
    .swal2-fullscreen-modal {
        max-width: 100vw !important;
        max-height: 100vh !important;
        width: 100vw !important;
        height: 100vh !important;
        margin: 0 !important;
        padding: 0 !important;
        border-radius: 0 !important;
        background: #000 !important;
    }

    .swal2-fullscreen-modal .swal2-close {
        position: fixed !important;
        top: 20px !important;
        right: 20px !important;
        z-index: 99999 !important;
        background: rgba(0,0,0,0.8) !important;
        color: white !important;
        border-radius: 50% !important;
        width: 50px !important;
        height: 50px !important;
        line-height: 50px !important;
        font-size: 2rem !important;
    }

    .swal2-fullscreen-modal .swal2-close:hover {
        background: rgba(255,0,0,0.8) !important;
        transform: scale(1.1) !important;
    }
</style>
@endpush

@section('content')
<div class="main-container">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="header-info">
                    <h1>
                        <i class="fas fa-undo-alt"></i>
                        Detail Pengembalian #{{ $pengembalian->id_pengembalian }}
                    </h1>
                    <p>Informasi lengkap pengajuan pengembalian dana pelanggan</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                    @if($pengembalian->status_pengembalian == 'Menunggu Review')
                        <button type="button" class="btn btn-success" onclick="approveRefund({{ $pengembalian->id_pengembalian }})">
                            <i class="fas fa-check"></i>
                            Setujui
                        </button>
                        <button type="button" class="btn btn-danger" onclick="rejectRefund({{ $pengembalian->id_pengembalian }})">
                            <i class="fas fa-times"></i>
                            Tolak
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Main Content -->
            <div class="main-content">
                <!-- Refund Information -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Informasi Pengembalian</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-hashtag"></i>
                                ID Pengembalian
                            </div>
                            <div class="info-value">
                                #{{ $pengembalian->id_pengembalian }}
                                <button class="copy-btn" onclick="copyToClipboard('#{{ $pengembalian->id_pengembalian }}')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-shopping-cart"></i>
                                ID Pesanan
                            </div>
                            <div class="info-value">
                                #{{ $pengembalian->pesanan->id_pesanan ?? 'N/A' }}
                                @if($pengembalian->pesanan)
                                    <button class="copy-btn" onclick="copyToClipboard('#{{ $pengembalian->pesanan->id_pesanan }}')">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-money-bill-wave"></i>
                                Jumlah Pengembalian
                            </div>
                            <div class="info-value amount-highlight">
                                Rp {{ number_format($pengembalian->jumlah_klaim, 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-flag"></i>
                                Status
                            </div>
                            <div class="info-value">
                                @if($pengembalian->status_pengembalian == 'Menunggu Review')
                                    <span class="status-badge status-pending">
                                        <i class="fas fa-clock"></i>
                                        {{ $pengembalian->status_pengembalian }}
                                    </span>
                                @elseif($pengembalian->status_pengembalian == 'Disetujui')
                                    <span class="status-badge status-approved">
                                        <i class="fas fa-check"></i>
                                        {{ $pengembalian->status_pengembalian }}
                                    </span>
                                @elseif($pengembalian->status_pengembalian == 'Ditolak')
                                    <span class="status-badge status-rejected">
                                        <i class="fas fa-times"></i>
                                        {{ $pengembalian->status_pengembalian }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar"></i>
                                Tanggal Pengajuan
                            </div>
                            <div class="info-value">
                                {{ $pengembalian->created_at->format('d F Y, H:i') }} WIB
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-comment-alt"></i>
                                Jenis Keluhan
                            </div>
                            <div class="info-value">
                                {{ $pengembalian->jenis_keluhan }}
                            </div>
                        </div>

                        @if($pengembalian->catatan_admin && $pengembalian->status_pengembalian == 'Ditolak')
                            <div class="admin-note">
                                <div class="note-title">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Alasan Penolakan
                                </div>
                                <p class="note-content">{{ $pengembalian->catatan_admin }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Complaint Details -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h3>Detail Keluhan</h3>
                    </div>
                    <div class="card-body">
                        @if($pengembalian->deskripsi_masalah)
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-comment"></i>
                                    Deskripsi Masalah
                                </div>
                                <div class="info-value">
                                    <div class="description-box">
                                        {{ $pengembalian->deskripsi_masalah }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($pengembalian->foto_bukti)
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-camera"></i>
                                    Media Bukti
                                </div>
                                <div class="info-value">
                                    @php
                                        $media = is_array($pengembalian->foto_bukti) ? $pengembalian->foto_bukti : json_decode($pengembalian->foto_bukti, true);
                                    @endphp
                                    @if($media && count($media) > 0)
                                        <div class="photo-gallery">
                                            @foreach($media as $index => $file)
                                                @php
                                                    // Jangan gunakan storage/ di path karena file disimpan di public/uploads/pengembalian
                                                    $filePath = $file; // Path relatif dari public sudah disimpan di database
                                                    $isVideo = in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['mp4', 'mov', 'avi']);
                                                @endphp

                                                @if($isVideo)
                                                    <div class="photo-item video-item" onclick="showVideoModal('{{ asset($filePath) }}', 'Video Bukti {{ $index + 1 }}')">
                                                        <video class="media-preview" preload="metadata">
                                                            <source src="{{ asset($filePath) }}" type="video/{{ strtolower(pathinfo($file, PATHINFO_EXTENSION)) }}">
                                                            Browser tidak mendukung video.
                                                        </video>
                                                        <div class="photo-overlay">
                                                            <i class="fas fa-play"></i>
                                                        </div>
                                                        <div class="photo-number video-badge">
                                                            <i class="fas fa-video mr-1"></i>{{ $index + 1 }}
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="photo-item" onclick="showPhotoModal('{{ asset($filePath) }}', 'Foto Bukti {{ $index + 1 }}')">
                                                        <img src="{{ asset($filePath) }}" alt="Bukti {{ $index + 1 }}" class="media-preview">
                                                        <div class="photo-overlay">
                                                            <i class="fas fa-expand"></i>
                                                        </div>
                                                        <div class="photo-number">{{ $index + 1 }}</div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <small style="color: var(--gray-500); font-size: 0.75rem; display: block; margin-top: 0.5rem;">
                                            Klik media untuk melihat ukuran penuh
                                        </small>
                                    @else
                                        <div class="empty-state">
                                            <i class="fas fa-image"></i>
                                            <p>Tidak ada media bukti yang dilampirkan</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-user"></i>
                        <h3>Informasi Pelanggan</h3>
                    </div>
                    <div class="card-body">
                        @if($pengembalian->pesanan && $pengembalian->pesanan->user)
                            <div class="user-profile">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($pengembalian->pesanan->user->name, 0, 1)) }}
                                </div>
                                <div class="user-info">
                                    <h4>{{ $pengembalian->pesanan->user->name }}</h4>
                                    <p>{{ $pengembalian->pesanan->user->email }}</p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-envelope"></i>
                                    Email
                                </div>
                                <div class="info-value">{{ $pengembalian->pesanan->user->email }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-phone"></i>
                                    No. Telepon
                                </div>
                                <div class="info-value">{{ $pengembalian->pesanan->user->no_hp ?? 'Tidak tersedia' }}</div>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-user-times"></i>
                                <p>Data pelanggan tidak tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Order Summary -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-receipt"></i>
                        <h3>Ringkasan Pesanan</h3>
                    </div>
                    <div class="card-body">
                        @if($pengembalian->pesanan)
                            <div class="info-item">
                                <div class="info-label">Total Pesanan</div>
                                <div class="info-value amount-highlight">
                                    Rp {{ number_format($pengembalian->pesanan->total_harga, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Status Pesanan</div>
                                <div class="info-value">
                                    <span class="status-badge status-approved">
                                        {{ $pengembalian->pesanan->status_pesanan }}
                                    </span>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Metode Pembayaran</div>
                                <div class="info-value">{{ $pengembalian->pesanan->metode_pembayaran ?? 'N/A' }}</div>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-receipt"></i>
                                <p>Data pesanan tidak tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-history"></i>
                        <h3>Timeline</h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-date">{{ $pengembalian->created_at->format('d M Y, H:i') }}</div>
                                <div class="timeline-title">Pengajuan Dibuat</div>
                                <div class="timeline-desc">Pelanggan mengajukan pengembalian dana</div>
                            </div>

                            @if($pengembalian->status_pengembalian == 'Disetujui')
                                <div class="timeline-item">
                                    <div class="timeline-date">{{ $pengembalian->updated_at->format('d M Y, H:i') }}</div>
                                    <div class="timeline-title" style="color: var(--success);">Disetujui</div>
                                    <div class="timeline-desc">Pengajuan pengembalian telah disetujui</div>
                                </div>
                            @elseif($pengembalian->status_pengembalian == 'Ditolak')
                                <div class="timeline-item">
                                    <div class="timeline-date">{{ $pengembalian->updated_at->format('d M Y, H:i') }}</div>
                                    <div class="timeline-title" style="color: var(--danger);">Ditolak</div>
                                    <div class="timeline-desc">Pengajuan pengembalian ditolak</div>
                                </div>
                            @else
                                <div class="timeline-item">
                                    <div class="timeline-date">Sedang berlangsung</div>
                                    <div class="timeline-title" style="color: var(--warning);">Menunggu Review</div>
                                    <div class="timeline-desc">Menunggu review dari admin</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Bank Information -->
                @if($pengembalian->nama_bank || $pengembalian->nomor_rekening || $pengembalian->nama_ewallet || $pengembalian->nomor_ewallet)
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-university"></i>
                            <h3>Informasi Pembayaran</h3>
                        </div>
                        <div class="card-body">
                            @if($pengembalian->nama_bank || $pengembalian->nomor_rekening)
                                <h4 style="color: var(--gray-700); font-size: 0.875rem; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                    <i class="fas fa-university" style="margin-right: 0.5rem;"></i>
                                    Bank
                                </h4>

                                @if($pengembalian->nama_bank)
                                    <div class="info-item">
                                        <div class="info-label">Nama Bank</div>
                                        <div class="info-value">{{ $pengembalian->nama_bank }}</div>
                                    </div>
                                @endif

                                @if($pengembalian->nomor_rekening)
                                    <div class="info-item">
                                        <div class="info-label">No. Rekening</div>
                                        <div class="info-value">
                                            {{ $pengembalian->nomor_rekening }}
                                            <button class="copy-btn" onclick="copyToClipboard('{{ $pengembalian->nomor_rekening }}')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                @if($pengembalian->nama_pemilik_rekening)
                                    <div class="info-item">
                                        <div class="info-label">Nama Rekening</div>
                                        <div class="info-value">{{ $pengembalian->nama_pemilik_rekening }}</div>
                                    </div>
                                @endif
                            @endif

                            @if($pengembalian->nama_ewallet || $pengembalian->nomor_ewallet)
                                @if($pengembalian->nama_bank || $pengembalian->nomor_rekening)
                                    <hr style="margin: 1.5rem 0; border: none; height: 1px; background: var(--gray-200);">
                                @endif

                                <h4 style="color: var(--gray-700); font-size: 0.875rem; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                    <i class="fas fa-mobile-alt" style="margin-right: 0.5rem;"></i>
                                    E-Wallet
                                </h4>

                                @if($pengembalian->nama_ewallet)
                                    <div class="info-item">
                                        <div class="info-label">Nama E-Wallet</div>
                                        <div class="info-value">{{ $pengembalian->nama_ewallet }}</div>
                                    </div>
                                @endif

                                @if($pengembalian->nomor_ewallet)
                                    <div class="info-item">
                                        <div class="info-label">No. E-Wallet</div>
                                        <div class="info-value">
                                            {{ $pengembalian->nomor_ewallet }}
                                            <button class="copy-btn" onclick="copyToClipboard('{{ $pengembalian->nomor_ewallet }}')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
            </div>
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
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }).catch(function() {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);

        Swal.fire({
            title: 'Berhasil!',
            text: 'Data berhasil disalin ke clipboard',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    });
}

// Show photo modal using SweetAlert2 with enhanced features
function showPhotoModal(imageSrc, title) {
    Swal.fire({
        title: title,
        html: `
            <div style="text-align: center;">
                <img src="${imageSrc}" alt="${title}" style="
                    max-width: 100%;
                    max-height: 75vh;
                    border-radius: 12px;
                    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
                    margin-bottom: 15px;
                    cursor: zoom-in;
                " onclick="openImageFullscreen('${imageSrc}')">
                <div class="media-action-buttons">
                    <a href="${imageSrc}" download class="media-action-btn download">
                        <i class="fas fa-download"></i>Download
                    </a>
                    <button onclick="openImageInNewTab('${imageSrc}')" class="media-action-btn open-tab">
                        <i class="fas fa-external-link-alt"></i>Buka di Tab Baru
                    </button>
                    <button onclick="openImageFullscreen('${imageSrc}')" class="media-action-btn fullscreen">
                        <i class="fas fa-expand"></i>Fullscreen
                    </button>
                </div>
            </div>
        `,
        showCloseButton: true,
        showConfirmButton: false,
        customClass: {
            popup: 'swal2-large-modal',
            htmlContainer: 'swal2-image-container'
        },
        width: 'auto',
        padding: '1.5rem',
        background: '#fff',
        backdrop: `
            rgba(0,0,0,0.85)
            left top
            no-repeat
        `,
        allowOutsideClick: true,
        allowEscapeKey: true,
        focusConfirm: false,
        showClass: {
            popup: 'animate__animated animate__zoomIn animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__zoomOut animate__faster'
        }
    });
}

// Open image in new tab
function openImageInNewTab(imageSrc) {
    window.open(imageSrc, '_blank');
}

// Open image in fullscreen mode
function openImageFullscreen(imageSrc) {
    Swal.fire({
        html: `
            <img src="${imageSrc}" style="
                width: 100%;
                height: 100%;
                object-fit: contain;
                border-radius: 0;
            ">
        `,
        showCloseButton: true,
        showConfirmButton: false,
        customClass: {
            popup: 'swal2-fullscreen-modal'
        },
        width: '100vw',
        height: '100vh',
        padding: 0,
        background: '#000',
        backdrop: false,
        allowOutsideClick: true,
        allowEscapeKey: true,
        showClass: {
            popup: 'animate__animated animate__fadeIn animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOut animate__faster'
        }
    });
}

// Show video modal using SweetAlert2 with enhanced features
function showVideoModal(videoSrc, title) {
    Swal.fire({
        title: title,
        html: `
            <div style="text-align: center;">
                <video controls autoplay style="
                    max-width: 100%;
                    max-height: 75vh;
                    border-radius: 12px;
                    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
                    margin-bottom: 15px;
                    background: #000;
                ">
                    <source src="${videoSrc}" type="video/mp4">
                    <source src="${videoSrc}" type="video/mov">
                    <source src="${videoSrc}" type="video/avi">
                    Browser Anda tidak mendukung video.
                </video>
                <div class="media-action-buttons">
                    <a href="${videoSrc}" download class="media-action-btn download">
                        <i class="fas fa-download"></i>Download Video
                    </a>
                    <button onclick="openVideoInNewTab('${videoSrc}')" class="media-action-btn open-tab">
                        <i class="fas fa-external-link-alt"></i>Buka di Tab Baru
                    </button>
                    <button onclick="openVideoFullscreen('${videoSrc}')" class="media-action-btn fullscreen">
                        <i class="fas fa-expand"></i>Fullscreen
                    </button>
                </div>
            </div>
        `,
        showCloseButton: true,
        showConfirmButton: false,
        customClass: {
            popup: 'swal2-large-modal',
            htmlContainer: 'swal2-video-container'
        },
        width: 'auto',
        padding: '1.5rem',
        background: '#fff',
        backdrop: `
            rgba(0,0,0,0.85)
            left top
            no-repeat
        `,
        allowOutsideClick: true,
        allowEscapeKey: true,
        focusConfirm: false,
        showClass: {
            popup: 'animate__animated animate__zoomIn animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__zoomOut animate__faster'
        },
        willClose: () => {
            // Pause video when modal is closed
            const videos = document.querySelectorAll('.swal2-popup video');
            videos.forEach(video => {
                video.pause();
                video.currentTime = 0;
            });
        }
    });
}

// Open video in new tab
function openVideoInNewTab(videoSrc) {
    window.open(videoSrc, '_blank');
}

// Open video in fullscreen mode
function openVideoFullscreen(videoSrc) {
    Swal.fire({
        html: `
            <video controls autoplay style="
                width: 100%;
                height: 100%;
                object-fit: contain;
                border-radius: 0;
                background: #000;
            ">
                <source src="${videoSrc}" type="video/mp4">
                <source src="${videoSrc}" type="video/mov">
                <source src="${videoSrc}" type="video/avi">
                Browser Anda tidak mendukung video.
            </video>
        `,
        showCloseButton: true,
        showConfirmButton: false,
        customClass: {
            popup: 'swal2-fullscreen-modal'
        },
        width: '100vw',
        height: '100vh',
        padding: 0,
        background: '#000',
        backdrop: false,
        allowOutsideClick: true,
        allowEscapeKey: true,
        showClass: {
            popup: 'animate__animated animate__fadeIn animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOut animate__faster'
        },
        willClose: () => {
            // Pause video when modal is closed
            const videos = document.querySelectorAll('.swal2-popup video');
            videos.forEach(video => {
                video.pause();
                video.currentTime = 0;
            });
        }
    });
}

// Approve refund function
function approveRefund(id) {
    Swal.fire({
        title: 'Konfirmasi Persetujuan',
        text: 'Apakah Anda yakin ingin menyetujui pengembalian ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-check me-2"></i>Ya, Setujui',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memproses persetujuan pengembalian',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create and submit form
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

// Reject refund function
function rejectRefund(id) {
    Swal.fire({
        title: 'Tolak Pengembalian',
        html: `
            <div class="text-start">
                <label class="form-label fw-bold mb-2">Alasan Penolakan:</label>
                <textarea id="swal-rejection-reason" class="form-control" rows="4"
                          placeholder="Berikan alasan yang jelas mengapa pengembalian ini ditolak..."
                          style="border: 2px solid #e5e7eb; border-radius: 8px; padding: 12px;"></textarea>
                <small class="text-muted mt-1 d-block">Alasan ini akan dikirimkan kepada pelanggan</small>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '<i class="fas fa-times me-2"></i>Tolak',
        cancelButtonText: '<i class="fas fa-arrow-left me-2"></i>Batal',
        customClass: {
            confirmButton: 'btn btn-danger',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false,
        preConfirm: () => {
            const reason = document.getElementById('swal-rejection-reason').value.trim();
            if (!reason) {
                Swal.showValidationMessage('Alasan penolakan harus diisi!');
                return false;
            }
            if (reason.length < 10) {
                Swal.showValidationMessage('Alasan penolakan harus minimal 10 karakter!');
                return false;
            }
            return reason;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memproses penolakan pengembalian',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Create and submit form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/pengembalian/${id}/reject`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'catatan_admin';
            reasonInput.value = result.value;

            form.appendChild(csrfToken);
            form.appendChild(reasonInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Initialize tooltips and other interactive elements
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add loading states to buttons
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function() {
            if (!this.classList.contains('copy-btn') && this.type !== 'button') {
                this.classList.add('loading');
                setTimeout(() => {
                    this.classList.remove('loading');
                }, 3000);
            }
        });
    });

    // Lazy load images
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => {
        imageObserver.observe(img);
    });
});

// Add keyboard shortcuts for better UX
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + Backspace to go back
    if ((e.ctrlKey || e.metaKey) && e.key === 'Backspace') {
        e.preventDefault();
        window.history.back();
    }

    // Press '1' to approve, '2' to reject (when not in input fields)
    if (!e.target.matches('input, textarea, select')) {
        if (e.key === '1') {
            e.preventDefault();
            const approveBtn = document.querySelector('button[onclick*="approveRefund"]');
            if (approveBtn) approveBtn.click();
        }
        if (e.key === '2') {
            e.preventDefault();
            const rejectBtn = document.querySelector('button[onclick*="rejectRefund"]');
            if (rejectBtn) rejectBtn.click();
        }
    }

    // Escape key for SweetAlert2 modals is handled automatically
});

// Add smooth scroll to sections
function scrollToSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Add loading indicator for actions
function showLoadingIndicator(message = 'Memproses...') {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
}

// Success notification with auto-redirect
function showSuccessAndRedirect(message, redirectUrl, delay = 2000) {
    Swal.fire({
        title: 'Berhasil!',
        text: message,
        icon: 'success',
        timer: delay,
        showConfirmButton: false,
        willClose: () => {
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
        }
    });
}
</script>
@endpush
