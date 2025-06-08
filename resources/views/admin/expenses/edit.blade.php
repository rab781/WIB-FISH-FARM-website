@extends('admin.layouts.app')

@section('title', 'Edit Pengeluaran')

@section('styles')
<style>
    /* Header Section */
    .expense-header {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 6px 18px rgba(249, 115, 22, 0.2);
        position: relative;
        overflow: hidden;
        animation: fadeIn 0.6s ease-out;
        display: flex;
        align-items: center;
        justify-content: space-between;
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

    .header-icon {
        font-size: 2rem;
        margin-right: 1.5rem;
        background: rgba(255,255,255,0.2);
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .header-icon:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    /* Card Design */
    .card {
        border-radius: 0.75rem;
        border: none;
        box-shadow: 0 4px 16px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }

    .card-header {
        background-color: #f9fafb;
        border-bottom: 1px solid #f3f4f6;
        padding: 1rem 1.5rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Form Elements */
    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        background-color: #f9fafb;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .form-control:focus, .form-select:focus {
        border-color: #f97316;
        box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.15);
        background-color: #ffffff;
    }

    .form-label {
        font-weight: 600;
        color: #4b5563;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        transition: color 0.3s;
    }

    .form-group:hover .form-label {
        color: #f97316;
    }

    /* Input Icon Groups */
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
        margin-bottom: 1.25rem;
        transition: transform 0.3s ease;
    }

    .form-group:focus-within {
        transform: translateY(-1px);
    }

    /* Info Box */
    .info-box {
        background-color: rgba(249, 115, 22, 0.05);
        border-left: 4px solid #f97316;
        border-radius: 0.5rem;
        padding: 1.25rem;
        transition: all 0.3s ease;
    }

    .info-box:hover {
        background-color: rgba(249, 115, 22, 0.08);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(249, 115, 22, 0.1);
    }

    /* Metadata Panel */
    .metadata-panel {
        background-color: #f9fafb;
        border-radius: 0.75rem;
        padding: 1.25rem;
        transition: all 0.3s ease;
    }

    .metadata-panel:hover {
        background-color: #f3f4f6;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
    }

    .metadata-title {
        color: #6b7280;
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.05rem;
        margin-bottom: 0.5rem;
    }

    .metadata-value {
        color: #111827;
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(249, 115, 22, 0.15);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(249, 115, 22, 0.25);
    }

    .btn-secondary {
        background-color: #f3f4f6;
        color: #4b5563;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #e5e7eb;
        transform: translateY(-2px);
    }

    /* Button Styles */
    .btn-outline-danger {
        border: 1px solid #ef4444;
        color: #ef4444;
        background: transparent;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-danger:hover {
        background-color: #fee2e2;
        border-color: #ef4444;
        color: #b91c1c;
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(239, 68, 68, 0.15);
    }

    /* Badge status */
    .badge.bg-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        box-shadow: 0 2px 5px rgba(16, 185, 129, 0.2);
        transition: all 0.3s ease;
    }

    .badge.bg-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
    }

    /* Animations */
    .form-float-in {
        animation: floatIn 0.5s ease-out both;
    }

    @keyframes floatIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Form Row Spacing */
    .row.g-3 > .col,
    .row.g-3 > [class*="col-"] {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    /* Responsive Improvements */
    @media (max-width: 768px) {
        .expense-header {
            padding: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
        }

        .expense-header > div:last-child {
            margin-top: 1rem;
            align-self: flex-start;
        }

        .header-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .metadata-panel .row {
            margin: 0 -0.5rem;
        }

        .metadata-panel .col-6 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
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
                <p class="mb-2 d-flex align-items-center">
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

    <!-- Form Card -->
    <div class="card expense-card">
        <h4 class="expense-card-title">
            <i class="fas fa-file-invoice mr-2 text-orange-500"></i>
            Update Pengeluaran #{{ $expense->id }}
        </h4>
        <form action="{{ route('admin.expenses.update', $expense->id) }}" method="POST">
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

            <!-- Improved Form Structure -->
            <form action="{{ route('admin.expenses.update', $expense->id) }}" method="POST">
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
                <div class="card border-0 shadow-sm rounded-lg mb-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="info-box mb-0">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-orange-100 p-3 me-3 shadow-sm">
                                    <i class="fas fa-edit text-orange-500"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1 text-orange-600 fw-bold">Mode Edit Pengeluaran</h5>
                                    <p class="mb-0 text-sm">Anda sedang mengedit pengeluaran yang dibuat pada <strong>{{ \Carbon\Carbon::parse($expense->created_at)->format('d M Y') }}</strong>. Pastikan data yang diperbarui sudah benar dan lengkap.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Dasar Card -->
                <div class="card border-0 shadow-sm rounded-lg mb-4 overflow-hidden">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="mb-0 fw-bold text-gray-700 d-flex align-items-center">
                            <i class="fas fa-info-circle me-2 text-orange-500"></i>
                            Informasi Dasar
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Kategori Field -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="category" class="form-label d-flex align-items-center">
                                        <i class="fas fa-tags me-2 text-orange-500"></i>Kategori <span class="text-danger ms-1">*</span>
                                    </label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="" disabled>Pilih Kategori</option>
                                        <option value="Gaji" {{ $expense->category == 'Gaji' ? 'selected' : '' }}><i class="fas fa-users category-icon"></i>Gaji Karyawan</option>
                                        <option value="Sewa" {{ $expense->category == 'Sewa' ? 'selected' : '' }}><i class="fas fa-building category-icon"></i>Sewa Toko/Gudang</option>
                                        <option value="Listrik" {{ $expense->category == 'Listrik' ? 'selected' : '' }}><i class="fas fa-bolt category-icon"></i>Listrik & Utilitas</option>
                                        <option value="Bahan" {{ $expense->category == 'Bahan' ? 'selected' : '' }}><i class="fas fa-boxes category-icon"></i>Bahan Baku</option>
                                        <option value="Peralatan" {{ $expense->category == 'Peralatan' ? 'selected' : '' }}><i class="fas fa-tools category-icon"></i>Peralatan</option>
                                        <option value="Transportasi" {{ $expense->category == 'Transportasi' ? 'selected' : '' }}><i class="fas fa-truck category-icon"></i>Transportasi</option>
                                        <option value="Marketing" {{ $expense->category == 'Marketing' ? 'selected' : '' }}><i class="fas fa-ad category-icon"></i>Marketing & Iklan</option>
                                        <option value="Administrasi" {{ $expense->category == 'Administrasi' ? 'selected' : '' }}><i class="fas fa-file-alt category-icon"></i>Administrasi</option>
                                        <option value="Lainnya" {{ $expense->category == 'Lainnya' ? 'selected' : '' }}><i class="fas fa-ellipsis-h category-icon"></i>Lainnya</option>
                                        {{-- Pastikan kategori yang sudah ada terpilih meskipun tidak di daftar statis --}}
                                        @if (!in_array($expense->category, ['Gaji', 'Sewa', 'Listrik', 'Bahan', 'Peralatan', 'Transportasi', 'Marketing', 'Administrasi', 'Lainnya']))
                                            <option value="{{ $expense->category }}" selected>{{ $expense->category }}</option>
                                        @endif
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Jumlah Field -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="amount" class="form-label d-flex align-items-center">
                                        <i class="fas fa-money-bill-wave me-2 text-orange-500"></i>Jumlah (Rp) <span class="text-danger ms-1">*</span>
                                    </label>
                                    <div class="input-icon-group">
                                        <span class="input-icon">Rp</span>
                                        <input type="number" class="form-control has-icon @error('amount') is-invalid @enderror" id="amount" name="amount" min="0" step="1000" value="{{ old('amount', $expense->amount) }}" placeholder="Contoh: 250000" required>
                                    </div>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tanggal Field -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="expense_date" class="form-label d-flex align-items-center">
                                        <i class="fas fa-calendar-alt me-2 text-orange-500"></i>Tanggal <span class="text-danger ms-1">*</span>
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
                <div class="card border-0 shadow-sm rounded-lg mb-4 overflow-hidden">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="mb-0 fw-bold text-gray-700 d-flex align-items-center">
                            <i class="fas fa-file-alt me-2 text-orange-500"></i>
                            Detail Pengeluaran
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Deskripsi Field -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="form-label d-flex align-items-center">
                                        <i class="fas fa-align-left me-2 text-orange-500"></i>Deskripsi <span class="text-danger ms-1">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description', $expense->description) }}" placeholder="Deskripsi pengeluaran" required>
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
                <div class="card border-0 shadow-sm rounded-lg mb-4 overflow-hidden">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="mb-0 fw-bold text-gray-700 d-flex align-items-center">
                            <i class="fas fa-history me-2 text-orange-500"></i>
                            Informasi Metadata
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <div class="metadata-panel form-float-in" style="animation-delay: 0.4s">
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
                <div class="card border-0 shadow-sm bg-light bg-opacity-50 p-4 rounded-lg mb-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3 shadow-sm">
                            <i class="fas fa-lightbulb text-warning"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold">Catatan Penting</h6>
                            <p class="text-muted mb-0 small">Perubahan akan langsung tersimpan dan diterapkan pada semua laporan keuangan yang terkait.</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between mb-3">
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
                        <button type="submit" class="btn btn-primary px-4">
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
document.getElementById('deleteExpenseBtn').addEventListener('click', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Hapus Pengeluaran?',
        html: '<div class="text-center"><p class="text-red-600 font-semibold mb-2">⚠️ PERINGATAN ⚠️</p><p>Pengeluaran "{{ $expense->deskripsi }}" akan dihapus secara permanen dan <strong>tidak dapat dibatalkan</strong>!</p></div>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-lg',
            confirmButton: 'rounded-md',
            cancelButton: 'rounded-md'
        },
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve();
                }, 500);
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
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            document.getElementById('delete-form').submit();
        }
    });
});
</script>
@endpush

@endsection
