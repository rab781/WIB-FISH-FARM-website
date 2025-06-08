@extends('admin.layouts.app')

@section('title', 'Edit Pengeluaran')

@section('styles')
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
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.07);
        --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
        --shadow-xl: 0 20px 40px rgba(0,0,0,0.1);
        --border-radius: 0.75rem;
        --border-radius-sm: 0.5rem;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    /* Enhanced Header Section */
    .expense-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 50%, #c2410c 100%);
        color: white;
        padding: 2.5rem;
        border-radius: var(--border-radius);
        margin-bottom: 2rem;
        box-shadow: var(--shadow-xl);
        position: relative;
        overflow: hidden;
        animation: slideInDown 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes slideInDown {
        from { 
            opacity: 0; 
            transform: translateY(-30px) scale(0.95); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0) scale(1); 
        }
    }

    .expense-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    .expense-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }

    .header-icon {
        font-size: 2.5rem;
        margin-right: 1.5rem;
        background: rgba(255,255,255,0.2);
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        transition: var(--transition);
        backdrop-filter: blur(10px);
    }

    .header-icon:hover {
        transform: scale(1.1) rotate(10deg);
        box-shadow: 0 12px 30px rgba(0,0,0,0.2);
    }

    /* Enhanced Card Design */
    .card {
        border-radius: var(--border-radius);
        border: 1px solid rgba(255,255,255,0.8);
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(20px);
        box-shadow: var(--shadow-lg);
        transition: var(--transition);
        overflow: hidden;
        margin-bottom: 2rem;
        position: relative;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        border-color: rgba(249, 115, 22, 0.2);
    }

    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .card:hover::before {
        opacity: 1;
    }

    .card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid var(--border-color);
        padding: 1.5rem;
    }

    .card-body {
        padding: 2rem;
    }

    /* Advanced Form Elements */
    .form-control, .form-select {
        border-radius: var(--border-radius-sm);
        border: 2px solid var(--border-color);
        padding: 1rem 1.25rem;
        font-size: 0.95rem;
                background: #ffffff;
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
        font-weight: 500;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.1);
        background: #ffffff;
        outline: none;
        transform: translateY(-2px);
    }

    .form-control:hover, .form-select:hover {
        border-color: rgba(249, 115, 22, 0.3);
        transform: translateY(-1px);
    }

    .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        transition: color 0.3s ease;
    }

    .form-group:hover .form-label {
        color: var(--primary-color);
    }

    /* Enhanced Input Icon Groups */
    .input-icon-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: 1.25rem;
        color: var(--text-secondary);
        font-weight: 600;
        transition: var(--transition);
        z-index: 10;
    }

    .input-icon-group:focus-within .input-icon {
        color: var(--primary-color);
        transform: translateY(-50%) scale(1.1);
    }

    .has-icon {
        padding-left: 3.5rem;
    }

    .form-group {
        margin-bottom: 1.75rem;
        transition: var(--transition);
    }

    .form-group:focus-within {
        transform: translateY(-2px);
    }

    /* Enhanced Info Boxes */
    .info-box {
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.05) 0%, rgba(249, 115, 22, 0.02) 100%);
        border: 1px solid rgba(249, 115, 22, 0.1);
        border-left: 4px solid var(--primary-color);
        border-radius: var(--border-radius-sm);
        padding: 1.5rem;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .info-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, var(--primary-color), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .info-box:hover {
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.08) 0%, rgba(249, 115, 22, 0.04) 100%);
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: rgba(249, 115, 22, 0.2);
    }

    .info-box:hover::before {
        opacity: 1;
    }

    /* Enhanced Metadata Panel */
    .metadata-panel {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: var(--border-radius);
        padding: 2rem;
        transition: var(--transition);
        border: 1px solid var(--border-color);
        position: relative;
    }

    .metadata-panel:hover {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    .metadata-title {
        color: var(--text-secondary);
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.05rem;
        margin-bottom: 0.75rem;
    }

    .metadata-value {
        color: var(--text-primary);
        font-size: 0.95rem;
        font-weight: 600;
    }

    /* Enhanced Buttons */
    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border: none;
        border-radius: var(--border-radius-sm);
        padding: 0.875rem 2rem;
        font-weight: 600;
        transition: var(--transition);
        box-shadow: var(--shadow-md);
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
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(249, 115, 22, 0.3);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        color: var(--text-primary);
        border: 2px solid var(--border-color);
        border-radius: var(--border-radius-sm);
        padding: 0.875rem 2rem;
        font-weight: 600;
        transition: var(--transition);
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        border-color: var(--text-secondary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .btn-outline-danger {
        border: 2px solid var(--danger-color);
        color: var(--danger-color);
        background: transparent;
        border-radius: var(--border-radius-sm);
        padding: 0.875rem 2rem;
        font-weight: 600;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .btn-outline-danger::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 100%;
        background: var(--danger-color);
        transition: width 0.3s ease;
        z-index: -1;
    }

    .btn-outline-danger:hover::before {
        width: 100%;
    }

    .btn-outline-danger:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
    }

    /* Enhanced Badge Styles */
    .badge.bg-success {
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%) !important;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        padding: 0.5rem 1rem;
        border-radius: 50px;
    }

    .badge.bg-success:hover {
        transform: translateY(-1px) scale(1.05);
        box-shadow: 0 8px 15px rgba(16, 185, 129, 0.3);
    }

    /* Custom Select Dropdown with Animation */
    .select-wrapper {
        position: relative;
        display: block;
    }

    .select-wrapper select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: transparent;
        padding-right: 3rem;
    }

    .select-chevron {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        pointer-events: none;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        color: var(--text-secondary);
    }

    .select-wrapper:focus-within .select-chevron {
        transform: translateY(-50%) rotate(180deg);
        color: var(--primary-color);
    }

    /* Enhanced Animations */
    .form-float-in {
        animation: floatIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) both;
    }

    @keyframes floatIn {
        from { 
            opacity: 0; 
            transform: translateY(20px) scale(0.95); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0) scale(1); 
        }
    }

    /* Loading Animation */
    .loading-spinner {
        display: inline-block;
        width: 1.2rem;
        height: 1.2rem;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #ffffff;
        animation: spin 0.8s linear infinite;
        margin-right: 0.5rem;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Currency Formatting */
    .currency-display {
        font-family: 'Monaco', 'Menlo', monospace;
        font-weight: 600;
        color: var(--success-color);
        background: rgba(16, 185, 129, 0.1);
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.9rem;
    }

    /* Auto-resize textarea */
    .auto-resize {
        resize: none;
        overflow: hidden;
        min-height: 80px;
        transition: height 0.3s ease;
    }

    /* Form validation */
    .is-valid {
        border-color: var(--success-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.1) !important;
    }

    .is-invalid {
        border-color: var(--danger-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(239, 68, 68, 0.1) !important;
        animation: shake 0.3s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    .valid-feedback, .invalid-feedback {
        font-size: 0.85rem;
        font-weight: 500;
        margin-top: 0.5rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
    }

    .valid-feedback {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success-color);
    }

    .invalid-feedback {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger-color);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .expense-header {
            padding: 2rem;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
        }

        .expense-header > div:last-child {
            margin-top: 1.5rem;
            align-self: flex-start;
        }

        .header-icon {
            width: 60px;
            height: 60px;
            font-size: 2rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .metadata-panel {
            padding: 1.5rem;
        }

        .metadata-panel .row {
            margin: 0 -0.75rem;
        }

        .metadata-panel .col-6, .metadata-panel .col-md-3 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            margin-bottom: 1rem;
        }

        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .d-flex.justify-content-between {
            flex-direction: column-reverse;
        }

        .d-flex.justify-content-between > div:first-child {
            margin-top: 1rem;
        }
    }

    @media (max-width: 576px) {
        .expense-header {
            padding: 1.5rem;
        }

        .card-body {
            padding: 1.25rem;
        }

        .form-control, .form-select {
            padding: 0.875rem 1rem;
        }

        .has-icon {
            padding-left: 3rem;
        }
    }

    /* Print styles */
    @media print {
        .expense-header, .btn, .card-header {
            display: none !important;
        }

        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Enhanced Header with Animation -->
    <div class="expense-header d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <div class="header-icon">
                <i class="fas fa-edit"></i>
            </div>
            <div class="form-float-in" style="animation-delay: 0.1s">
                <h1 class="h3 mb-2 font-weight-bold">
                    Edit Pengeluaran
                </h1>
                <p class="mb-2 d-flex align-items-center opacity-90">
                    <i class="fas fa-info-circle me-2"></i>
                    Perbarui data pengeluaran bisnis Anda dengan mudah
                </p>
                <div class="mt-2 d-flex align-items-center flex-wrap">
                    <span class="badge bg-white text-orange-600 shadow-sm me-2">
                        <i class="fas fa-hashtag me-1"></i> ID: {{ $expense->id }}
                    </span>
                    <span class="badge bg-white text-orange-600 shadow-sm me-2">
                        <i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($expense->created_at)->format('d M Y H:i') }}
                    </span>
                    <span class="badge bg-success text-white">
                        <i class="fas fa-check-circle me-1"></i> Aktif
                    </span>
                </div>
            </div>
        </div>
        <div class="form-float-in" style="animation-delay: 0.2s">
            @if(isset($queryParams) && !empty($queryParams))
                <a href="{{ route('admin.reports.financial', $queryParams) }}" class="btn btn-light shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Laporan
                </a>
            @else
                <a href="{{ route('admin.expenses.index') }}" class="btn btn-light shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            @endif
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="card">
        <form id="editExpenseForm" action="{{ route('admin.expenses.update', $expense->id) }}" method="POST">
            @csrf
            @method('PUT')
            @if(isset($queryParams))
                @if(isset($queryParams['year']))
                    <input type="hidden" name="year" value="{{ $queryParams['year'] }}">
                @endif
                @if(isset($queryParams['month']))
                    <input type="hidden" name="month" value="{{ $queryParams['month'] }}">
                @endif
            @endif

            <!-- Info Box for Form Instructions -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden form-float-in" style="animation-delay: 0.3s">
                <div class="card-body p-0">
                    <div class="info-box mb-0">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-3 me-3 shadow-sm" style="background: linear-gradient(135deg, rgba(249, 115, 22, 0.1) 0%, rgba(249, 115, 22, 0.05) 100%);">
                                <i class="fas fa-edit" style="color: var(--primary-color); font-size: 1.25rem;"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold" style="color: var(--primary-color);">Mode Edit Pengeluaran</h5>
                                <p class="mb-0 text-sm text-muted">Anda sedang mengedit pengeluaran yang dibuat pada <strong>{{ \Carbon\Carbon::parse($expense->created_at)->format('d M Y') }}</strong>. Pastikan data yang diperbarui sudah benar dan lengkap.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Dasar Card -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden form-float-in" style="animation-delay: 0.4s">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0 fw-bold d-flex align-items-center" style="color: var(--text-primary);">
                        <i class="fas fa-info-circle me-2" style="color: var(--primary-color);"></i>
                        Informasi Dasar
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Kategori Field -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="category" class="form-label d-flex align-items-center">
                                    <i class="fas fa-tags me-2" style="color: var(--primary-color);"></i>Kategori <span class="text-danger ms-1">*</span>
                                </label>
                                <div class="select-wrapper">
                                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="" disabled>Pilih Kategori</option>
                                        <option value="Gaji" {{ $expense->category == 'Gaji' ? 'selected' : '' }}>👥 Gaji Karyawan</option>
                                        <option value="Sewa" {{ $expense->category == 'Sewa' ? 'selected' : '' }}>🏢 Sewa Toko/Gudang</option>
                                        <option value="Listrik" {{ $expense->category == 'Listrik' ? 'selected' : '' }}>⚡ Listrik & Utilitas</option>
                                        <option value="Bahan" {{ $expense->category == 'Bahan' ? 'selected' : '' }}>📦 Bahan Baku</option>
                                        <option value="Peralatan" {{ $expense->category == 'Peralatan' ? 'selected' : '' }}>🔧 Peralatan</option>
                                        <option value="Transportasi" {{ $expense->category == 'Transportasi' ? 'selected' : '' }}>🚚 Transportasi</option>
                                        <option value="Marketing" {{ $expense->category == 'Marketing' ? 'selected' : '' }}>📢 Marketing & Iklan</option>
                                        <option value="Administrasi" {{ $expense->category == 'Administrasi' ? 'selected' : '' }}>📋 Administrasi</option>
                                        <option value="Lainnya" {{ $expense->category == 'Lainnya' ? 'selected' : '' }}>⚙️ Lainnya</option>
                                        @if (!in_array($expense->category, ['Gaji', 'Sewa', 'Listrik', 'Bahan', 'Peralatan', 'Transportasi', 'Marketing', 'Administrasi', 'Lainnya']))
                                            <option value="{{ $expense->category }}" selected>{{ $expense->category }}</option>
                                        @endif
                                    </select>
                                    <svg class="select-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Jumlah Field -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="amount" class="form-label d-flex align-items-center">
                                    <i class="fas fa-money-bill-wave me-2" style="color: var(--primary-color);"></i>Jumlah (Rp) <span class="text-danger ms-1">*</span>
                                </label>
                                <div class="input-icon-group">
                                    <span class="input-icon">Rp</span>
                                    <input type="text" class="form-control has-icon @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', number_format($expense->amount, 0, ',', '.')) }}" placeholder="Contoh: 250.000" required>
                                </div>
                                <small class="text-muted mt-1 d-block">Masukkan jumlah dalam Rupiah (contoh: 250.000)</small>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Tanggal Field -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="expense_date" class="form-label d-flex align-items-center">
                                    <i class="fas fa-calendar-alt me-2" style="color: var(--primary-color);"></i>Tanggal <span class="text-danger ms-1">*</span>
                                </label>
                                <input type="date" class="form-control @error('expense_date') is-invalid @enderror" id="expense_date" name="expense_date" value="{{ old('expense_date', $expense->expense_date instanceof \Carbon\Carbon ? $expense->expense_date->format('Y-m-d') : $expense->expense_date) }}" required>
                                @error('expense_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Pengeluaran Card -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden form-float-in" style="animation-delay: 0.5s">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0 fw-bold d-flex align-items-center" style="color: var(--text-primary);">
                        <i class="fas fa-file-alt me-2" style="color: var(--primary-color);"></i>
                        Detail Pengeluaran
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Deskripsi Field -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description" class="form-label d-flex align-items-center">
                                    <i class="fas fa-align-left me-2" style="color: var(--primary-color);"></i>Deskripsi <span class="text-danger ms-1">*</span>
                                </label>
                                <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description', $expense->description) }}" placeholder="Deskripsi pengeluaran" required>
                                <small class="text-muted mt-1 d-block">Berikan deskripsi yang jelas dan spesifik</small>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Catatan Field -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes" class="form-label d-flex align-items-center">
                                    <i class="fas fa-sticky-note me-2 text-orange-500"></i>Catatan (Opsional)
                                </label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Tambahkan catatan tambahan jika diperlukan">{{ old('notes', $expense->notes) }}</textarea>
                                <small class="text-muted mt-1 d-block">Catatan ini akan membantu memberikan konteks tambahan pada pengeluaran</small>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meta Data Card -->
            <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden form-float-in" style="animation-delay: 0.6s">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0 fw-bold d-flex align-items-center" style="color: var(--text-primary);">
                        <i class="fas fa-history me-2" style="color: var(--primary-color);"></i>
                        Informasi Metadata
                    </h5>
                </div>

                <div class="card-body p-4">
                    <div class="metadata-panel">
                        <div class="row text-center">
                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                <div class="metadata-title">Dibuat pada</div>
                                <div class="metadata-value">
                                    <i class="fas fa-calendar-plus text-success me-1"></i>
                                    {{ $expense->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>

                            <div class="col-md-3 col-6 mb-3 mb-md-0">
                                <div class="metadata-title">Terakhir Diperbarui</div>
                                <div class="metadata-value">
                                    <i class="fas fa-clock text-primary me-1"></i>
                                    {{ $expense->updated_at->format('d M Y, H:i') }}
                                </div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="metadata-title">Status Data</div>
                                <div class="metadata-value">
                                    <span class="badge bg-success text-white">
                                        <i class="fas fa-check-circle me-1"></i>Aktif
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="metadata-title">Kategori</div>
                                <div class="metadata-value">
                                    <span class="badge" style="background-color: rgba(249, 115, 22, 0.15); color: #ea580c">
                                        <i class="fas fa-tag me-1"></i>{{ $expense->category }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips Box -->
            <div class="card border-0 shadow-sm bg-light bg-opacity-50 p-4 rounded-3 mb-4 form-float-in" style="animation-delay: 0.7s">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-3 me-3 shadow-sm" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%);">
                        <i class="fas fa-lightbulb text-warning"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-bold">Catatan Penting</h6>
                        <p class="text-muted mb-0 small">Perubahan akan langsung tersimpan dan diterapkan pada semua laporan keuangan yang terkait.</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex justify-content-between mb-3 form-float-in" style="animation-delay: 0.8s">
                <div>
                    <button type="button" id="deleteExpenseBtn" class="btn btn-outline-danger">
                        <i class="fas fa-trash-alt me-2"></i>Hapus Pengeluaran
                    </button>
                </div>

                <div class="d-flex">
                    @if(isset($queryParams) && !empty($queryParams))
                        <a href="{{ route('admin.reports.financial', $queryParams) }}" class="btn btn-secondary me-3">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    @else
                        <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary me-3">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

        <!-- Delete Form -->
        <form id="delete-form" action="{{ route('admin.expenses.destroy', $expense->id) }}" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
            @if(isset($queryParams))
                @if(isset($queryParams['year']))
                    <input type="hidden" name="year" value="{{ $queryParams['year'] }}">
                @endif
                @if(isset($queryParams['month']))
                    <input type="hidden" name="month" value="{{ $queryParams['month'] }}">
                @endif
            @endif
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced form validation and UX
    const form = document.getElementById('editExpenseForm');
    const submitBtn = document.getElementById('submitBtn');
    const amountInput = document.getElementById('amount');
    const notesTextarea = document.getElementById('notes');
    const categorySelect = document.getElementById('category');
    
    // Format currency input with Indonesian locale
    function formatCurrency(value) {
        // Remove all non-digit characters
        const digits = value.replace(/\D/g, '');
        // Format with thousand separators
        return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
    
    // Handle amount input formatting
    amountInput.addEventListener('input', function(e) {
        const cursorPosition = e.target.selectionStart;
        const oldLength = e.target.value.length;
        const formatted = formatCurrency(e.target.value);
        e.target.value = formatted;
        
        // Maintain cursor position
        const newLength = formatted.length;
        const newPosition = cursorPosition + (newLength - oldLength);
        e.target.setSelectionRange(newPosition, newPosition);
    });
    
    // Auto-resize textarea
    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }
    
    notesTextarea.addEventListener('input', function() {
        autoResize(this);
    });
    
    // Initialize textarea height
    autoResize(notesTextarea);
    
    // Enhanced form validation
    function validateForm() {
        let isValid = true;
        const requiredFields = ['category', 'amount', 'expense_date', 'description'];
        
        requiredFields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            const value = field.value.trim();
            
            // Remove existing validation classes
            field.classList.remove('is-valid', 'is-invalid');
            
            if (!value) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.add('is-valid');
            }
        });
        
        // Validate amount is numeric
        const amountValue = amountInput.value.replace(/\D/g, '');
        if (amountValue && parseInt(amountValue) <= 0) {
            amountInput.classList.remove('is-valid');
            amountInput.classList.add('is-invalid');
            isValid = false;
        }
        
        return isValid;
    }
    
    // Real-time validation
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', validateForm);
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateForm();
            }
        });
    });
    
    // Enhanced form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            // Scroll to first invalid field
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                firstInvalid.focus();
            }
            return;
        }
        
        // Show loading state
        submitBtn.innerHTML = '<span class="loading-spinner"></span>Menyimpan...';
        submitBtn.disabled = true;
        
        // Convert formatted amount to raw number
        const rawAmount = amountInput.value.replace(/\D/g, '');
        amountInput.value = rawAmount;
        
        // Submit the form
        setTimeout(() => {
            form.submit();
        }, 500);
    });
    
    // Delete functionality with enhanced confirmation
    document.getElementById('deleteExpenseBtn').addEventListener('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Hapus Pengeluaran?',
            html: `
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-warning mb-2" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-muted mb-2">Pengeluaran "<strong>{{ $expense->description }}</strong>" akan dihapus secara permanen.</p>
                    <p class="text-danger small"><i class="fas fa-warning me-1"></i> Tindakan ini tidak dapat dibatalkan!</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-trash me-2"></i>Ya, Hapus!',
            cancelButtonText: '<i class="fas fa-times me-2"></i>Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3 shadow-xl',
                confirmButton: 'rounded-2',
                cancelButton: 'rounded-2'
            },
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve) => {
                    setTimeout(() => {
                        resolve();
                    }, 800);
                });
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang memproses penghapusan pengeluaran.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-3'
                    },
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                setTimeout(() => {
                    document.getElementById('delete-form').submit();
                }, 1000);
            }
        });
    });
    
    // Enhanced visual feedback
    categorySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            this.style.color = 'var(--text-primary)';
            this.style.fontWeight = '600';
        }
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S to save
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
        
        // Escape to cancel
        if (e.key === 'Escape') {
            const cancelBtn = document.querySelector('.btn-secondary');
            if (cancelBtn) {
                cancelBtn.click();
            }
        }
    });
    
    // Initial validation on load
    setTimeout(validateForm, 100);
});
</script>
@endpush

@endsection
