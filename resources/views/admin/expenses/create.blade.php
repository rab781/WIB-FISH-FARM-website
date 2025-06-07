@extends('admin.layouts.app')

@section('title', 'Tambah Pengeluaran')

@section('styles')
<style>
    /* Header Section */
    .expense-header {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
        padding: 2rem;
        border-radius: 1.25rem;
        margin-bottom: 2rem;
        box-shadow: 0 6px 20px rgba(249, 115, 22, 0.25);
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .expense-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .expense-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        transform: translate(-30%, 30%);
    }

    /* Card Design */
    .expense-card {
        border-radius: 1.25rem;
        border: none;
        box-shadow: 0 8px 30px rgba(0,0,0,0.06);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        padding: 2.5rem;
        background: #ffffff;
        position: relative;
        overflow: hidden;
        animation: slideUp 0.5s ease-out 0.2s both;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .expense-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(90deg, #f97316, #fb923c, #fdba74);
        z-index: 1;
    }

    .expense-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 180px;
        height: 180px;
        background: radial-gradient(circle, rgba(249, 115, 22, 0.03) 0%, rgba(249, 115, 22, 0) 70%);
        border-radius: 50%;
    }

    .expense-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    /* Form Elements */
    .form-control, .form-select {
        border-radius: 0.85rem;
        border: 2px solid #e5e7eb;
        padding: 0.85rem 1.2rem;
        font-size: 0.95rem;
        background-color: #f9fafb;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }

    .form-control:focus, .form-select:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.15);
        background-color: #ffffff;
        transform: translateY(-1px);
    }

    .form-label {
        font-weight: 600;
        color: #4b5563;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        transition: color 0.3s;
    }

    .form-group:hover .form-label {
        color: #f97316;
    }

    .form-group:hover .form-label i {
        transform: translateY(-2px);
        text-shadow: 0 3px 5px rgba(249, 115, 22, 0.2);
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        border: none;
        border-radius: 0.85rem;
        padding: 0.85rem 1.75rem;
        font-weight: 600;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
        z-index: 1;
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.25);
    }

    .btn-primary::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
        z-index: -1;
        transition: opacity 0.3s ease;
        opacity: 0;
    }

    .btn-primary:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 10px 20px rgba(249, 115, 22, 0.35);
        letter-spacing: 0.3px;
    }

    .btn-primary:hover::after {
        opacity: 1;
    }

    .btn-primary:active {
        transform: translateY(1px);
        box-shadow: 0 5px 15px rgba(249, 115, 22, 0.35);
    }

    .btn-secondary {
        background-color: #f3f4f6;
        color: #4b5563;
        border: 1px solid #e5e7eb;
        border-radius: 0.85rem;
        padding: 0.85rem 1.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .btn-secondary:hover {
        transform: translateY(-3px);
        background-color: #e5e7eb;
        box-shadow: 0 7px 15px rgba(0, 0, 0, 0.08);
    }

    /* Form Icons and Input Groups */
    .input-icon-group {
        position: relative;
    }

    .input-icon {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: 1rem;
        color: #9ca3af;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .input-icon-group:focus-within .input-icon {
        color: #f97316;
    }

    .has-icon {
        padding-left: 3rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease;
    }

    .form-group:focus-within {
        transform: translateY(-2px);
    }

    .category-icon {
        margin-right: 0.5rem;
        font-size: 0.85rem;
        color: #f97316;
    }

    /* Info Box */
    .info-box {
        background-color: rgba(249, 115, 22, 0.05);
        border-left: 4px solid #f97316;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .info-box:hover {
        background-color: rgba(249, 115, 22, 0.08);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(249, 115, 22, 0.1);
    }

    /* Form Section Dividers */
    .form-divider {
        height: 1px;
        background: linear-gradient(90deg, rgba(249, 115, 22, 0.2), rgba(249, 115, 22, 0.05), rgba(249, 115, 22, 0));
        margin: 1.5rem 0;
        position: relative;
    }

    .expense-data-badge {
        background-color: #fef3c7;
        color: #d97706;
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 0.5rem;
        box-shadow: 0 2px 4px rgba(217, 119, 6, 0.1);
    }

    /* Form Row Spacing */
    .row.g-4 > .col,
    .row.g-4 > [class*="col-"] {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    /* Animations */
    .form-float-in {
        animation: floatIn 0.5s ease-out both;
    }

    @keyframes floatIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Responsive Improvements */
    @media (max-width: 768px) {
        .expense-header {
            padding: 1.5rem;
        }
        .expense-card {
            padding: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="expense-header d-flex justify-content-between align-items-center mb-4">
        <div class="form-float-in" style="animation-delay: 0.1s">
            <h1 class="h3 mb-2 font-weight-bold">
                <span class="fa-stack fa-1x me-2">
                    <i class="fas fa-circle fa-stack-2x" style="color: rgba(255,255,255,0.2)"></i>
                    <i class="fas fa-plus-circle fa-stack-1x" style="color: white"></i>
                </span>
                Tambah Pengeluaran Baru
            </h1>
            <p class="mb-0 d-flex align-items-center">
                <i class="fas fa-info-circle me-2"></i>
                Tambahkan data pengeluaran bisnis Anda dengan mudah dan terstruktur
            </p>
            <div class="mt-2">
                <span class="badge bg-white text-orange-600 shadow-sm me-2">
                    <i class="fas fa-tag me-1"></i> Keuangan
                </span>
                <span class="badge bg-white text-orange-600 shadow-sm">
                    <i class="fas fa-check-circle me-1"></i> Data Akan Otomatis Tersimpan
                </span>
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

    <!-- Form Card -->
    <div class="card expense-card">
        <form action="{{ route('admin.expenses.store') }}" method="POST">
            @csrf
            @if(isset($queryParams))
                @if(isset($queryParams['year']))
                    <input type="hidden" name="year" value="{{ $queryParams['year'] }}">
                @endif
                @if(isset($queryParams['month']))
                    <input type="hidden" name="month" value="{{ $queryParams['month'] }}">
                @endif
            @endif

            <div class="row g-4">
                <div class="col-12">
                    <div class="info-box mb-4">
                        <div class="d-flex align-items-center">
                            <span class="fa-stack fa-1x me-3">
                                <i class="fas fa-circle fa-stack-2x text-orange-100"></i>
                                <i class="fas fa-info fa-stack-1x text-orange-500"></i>
                            </span>
                            <div>
                                <h5 class="mb-1 text-orange-600 fw-bold">Panduan Pengisian Form</h5>
                                <p class="mb-0 text-sm">Lengkapi informasi pengeluaran dengan detail yang jelas untuk pelaporan keuangan yang akurat dan mudah dianalisis.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="category" class="form-label d-flex align-items-center">
                            <i class="fas fa-tags me-2 text-orange-500"></i>Kategori <span class="text-danger ms-1">*</span>
                        </label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="" selected disabled>Pilih Kategori</option>
                            <option value="Gaji"><i class="fas fa-users category-icon"></i>Gaji Karyawan</option>
                            <option value="Sewa"><i class="fas fa-building category-icon"></i>Sewa Toko/Gudang</option>
                            <option value="Listrik"><i class="fas fa-bolt category-icon"></i>Listrik & Utilitas</option>
                            <option value="Bahan"><i class="fas fa-boxes category-icon"></i>Bahan Baku</option>
                            <option value="Peralatan"><i class="fas fa-tools category-icon"></i>Peralatan</option>
                            <option value="Transportasi"><i class="fas fa-truck category-icon"></i>Transportasi</option>
                            <option value="Marketing"><i class="fas fa-ad category-icon"></i>Marketing & Iklan</option>
                            <option value="Administrasi"><i class="fas fa-file-alt category-icon"></i>Administrasi</option>
                            <option value="Lainnya"><i class="fas fa-ellipsis-h category-icon"></i>Lainnya</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="amount" class="form-label d-flex align-items-center">
                            <i class="fas fa-money-bill-wave me-2 text-orange-500"></i>Jumlah (Rp) <span class="text-danger ms-1">*</span>
                        </label>
                        <div class="input-icon-group">
                            <span class="input-icon">Rp</span>
                            <input type="number" class="form-control has-icon @error('amount') is-invalid @enderror" id="amount" name="amount" min="0" step="1000" value="{{ old('amount') }}" placeholder="Contoh: 250000" required>
                        </div>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="expense_date" class="form-label d-flex align-items-center">
                            <i class="fas fa-calendar-alt me-2 text-orange-500"></i>Tanggal <span class="text-danger ms-1">*</span>
                        </label>
                        <input type="date" class="form-control @error('expense_date') is-invalid @enderror" id="expense_date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required>
                        @error('expense_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="description" class="form-label d-flex align-items-center">
                            <i class="fas fa-align-left me-2 text-orange-500"></i>Deskripsi <span class="text-danger ms-1">*</span>
                        </label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description') }}" placeholder="Deskripsi pengeluaran" required>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="notes" class="form-label d-flex align-items-center">
                            <i class="fas fa-sticky-note me-2 text-orange-500"></i>Catatan (Opsional)
                        </label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Tambahkan catatan tambahan jika diperlukan">{{ old('notes') }}</textarea>
                        <small class="text-muted">Catatan ini akan membantu memberikan konteks tambahan pada pengeluaran</small>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-divider"></div>
                </div>

                <div class="col-md-12 mb-3">
                    <div class="card bg-light border-0 p-3 rounded-lg mb-0">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                <i class="fas fa-lightbulb text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Tips Pengisian</h6>
                                <p class="text-muted mb-0 small">Pastikan kategori yang dipilih sesuai untuk mempermudah pengelompokan dalam laporan keuangan bisnis Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary me-3">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Simpan Pengeluaran
                    </button>
                </div>
                </div>
            </form>
        </div>
    </div>
    @endsection
