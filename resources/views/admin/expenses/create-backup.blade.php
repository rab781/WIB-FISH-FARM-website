@extends('admin.layouts.app')

@section('title', 'Tambah Pengeluaran')

@push('styles')
<style>
    :root {
        --primary-orange: #f97316;
        --primary-orange-dark: #ea580c;
        --primary-orange-light: #fb923c;
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
        --success-500: #10b981;
        --red-500: #ef4444;
        --blue-500: #3b82f6;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    * {
        box-sizing: border-box;
    }

    body {
        background-color: var(--gray-50);
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .main-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    /* Modern Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-dark) 100%);
        border-radius: 1.5rem;
        padding: 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-xl);
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .page-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }

    .header-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .header-info {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        color: white;
    }

    .header-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        transition: all 0.3s ease;
    }

    .header-icon:hover {
        transform: scale(1.05) rotate(5deg);
        background: rgba(255, 255, 255, 0.25);
    }

    .header-text h1 {
        font-size: 2.25rem;
        font-weight: 800;
        margin: 0 0 0.5rem 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .header-text p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0 0 1rem 0;
    }

    .header-badges {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .badge {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .badge:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
    }

    .back-btn {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.4);
        color: white;
        transform: translateY(-2px);
    }

    /* Form Container */
    .form-container {
        background: white;
        border-radius: 1.5rem;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    /* Info Alert */
    .info-alert {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-left: 5px solid #f59e0b;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-radius: 0 12px 12px 0;
        position: relative;
        overflow: hidden;
    }

    .info-alert::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 20 20'%3E%3Cg fill-opacity='0.03'%3E%3Cpolygon fill='%23000' points='20 0 20 20 0 20'/%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.1;
    }

    .info-content {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .info-icon {
        width: 50px;
        height: 50px;
        background: #f59e0b;
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .info-text h3 {
        color: #92400e;
        font-size: 1.125rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
    }

    .info-text p {
        color: #a16207;
        margin: 0;
        font-size: 0.95rem;
    }

    /* Form Sections */
    .form-section {
        padding: 2rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--gray-100);
    }

    .section-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-dark) 100%);
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .section-title {
        color: var(--gray-800);
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0;
    }

    /* Form Fields */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .form-group {
        position: relative;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--gray-700);
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
        transition: color 0.3s ease;
    }

    .form-label i {
        color: var(--primary-orange);
        font-size: 1rem;
    }

    .required {
        color: var(--red-500);
    }

    .form-control, .form-select {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 2px solid var(--gray-200);
        border-radius: 12px;
        background: var(--gray-50);
        font-size: 1rem;
        font-weight: 500;
        color: var(--gray-800);
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: var(--primary-orange);
        background: white;
        box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1), var(--shadow-md);
        transform: translateY(-1px);
    }

    .form-control::placeholder {
        color: var(--gray-400);
        font-weight: 400;
    }

    /* Input with Icon */
    .input-with-icon {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-400);
        font-weight: 600;
        pointer-events: none;
        transition: color 0.3s ease;
        z-index: 2;
    }

    .input-with-icon .form-control {
        padding-left: 3.5rem;
    }

    .input-with-icon:focus-within .input-icon {
        color: var(--primary-orange);
    }

    /* Textarea */
    textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }

    /* Form Actions */
    .form-actions {
        padding: 2rem;
        background: var(--gray-50);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        cursor: pointer;
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
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-dark) 100%);
        color: white;
        box-shadow: var(--shadow-md);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-orange-dark) 0%, #dc2626 100%);
        box-shadow: var(--shadow-lg);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: white;
        color: var(--gray-600);
        border-color: var(--gray-300);
        box-shadow: var(--shadow-sm);
    }

    .btn-secondary:hover {
        background: var(--gray-50);
        color: var(--gray-700);
        border-color: var(--gray-400);
        transform: translateY(-1px);
    }

    /* Loading State */
    .btn-loading {
        pointer-events: none;
        opacity: 0.7;
    }

    .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        right: 1rem;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Animations */
    .slide-in {
        animation: slideIn 0.6s ease-out forwards;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeIn 0.8s ease-out forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-container {
            padding: 1rem 0.5rem;
        }

        .page-header {
            padding: 2rem 1.5rem;
        }

        .header-content {
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
        }

        .header-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .header-text h1 {
            font-size: 1.875rem;
        }

        .form-section {
            padding: 1.5rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .header-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        .header-text h1 {
            font-size: 1.5rem;
        }

        .form-section {
            padding: 1rem;
        }
    }

    /* Custom Select Dropdown */
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 16px 16px;
        padding-right: 3rem;
    }

    .form-select:focus {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23f97316' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    }

    /* Error States */
    .form-control.error, .form-select.error {
        border-color: var(--red-500);
        background-color: #fef2f2;
    }

    .error-message {
        color: var(--red-500);
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Success States */
    .form-control.success, .form-select.success {
        border-color: var(--success-500);
        background-color: #f0fdf4;
    }
</style>
@endpush

@section('content')
<div class="main-container">
    <!-- Enhanced Page Header -->
    <div class="page-header slide-in">
        <div class="header-content">
            <div class="header-info">
                <div class="header-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="header-text">
                    <h1>Tambah Pengeluaran Baru</h1>
                    <p>Tambahkan data pengeluaran bisnis Anda dengan mudah dan terstruktur</p>
                    <div class="header-badges">
                        <div class="badge">
                            <i class="fas fa-tag"></i>
                            <span>Manajemen Keuangan</span>
                        </div>
                        <div class="badge">
                            <i class="fas fa-shield-check"></i>
                            <span>Data Aman & Akurat</span>
                        </div>
                        <div class="badge">
                            <i class="fas fa-chart-line"></i>
                            <span>Laporan Real-time</span>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                @if(isset($queryParams) && !empty($queryParams))
                    <a href="{{ route('admin.reports.financial', $queryParams) }}" class="back-btn">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali ke Laporan</span>
                    </a>
                @else
                    <a href="{{ route('admin.expenses.index') }}" class="back-btn">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Info Alert -->
    <div class="info-alert fade-in">
        <div class="info-content">
            <div class="info-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="info-text">
                <h3>Panduan Pengisian Form</h3>
                <p>Lengkapi informasi pengeluaran dengan detail yang jelas untuk pelaporan keuangan yang akurat dan mudah dianalisis. Semua field yang bertanda (*) wajib diisi.</p>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <form action="{{ route('admin.expenses.store') }}" method="POST" id="expenseForm" class="form-container slide-in">
        @csrf
        @if(isset($queryParams))
            @if(isset($queryParams['year']))
                <input type="hidden" name="year" value="{{ $queryParams['year'] }}">
            @endif
            @if(isset($queryParams['month']))
                <input type="hidden" name="month" value="{{ $queryParams['month'] }}">
            @endif
        @endif

        <!-- Informasi Dasar Section -->
        <div class="form-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <h2 class="section-title">Informasi Dasar</h2>
            </div>

            <div class="form-grid">
                <!-- Kategori -->
                <div class="form-group">
                    <label for="category" class="form-label">
                        <i class="fas fa-tags"></i>
                        <span>Kategori Pengeluaran <span class="required">*</span></span>
                    </label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="" disabled selected>Pilih kategori pengeluaran</option>
                        <option value="Gaji">💼 Gaji Karyawan</option>
                        <option value="Sewa">🏢 Sewa Toko/Gudang</option>
                        <option value="Listrik">⚡ Listrik & Utilitas</option>
                        <option value="Bahan">📦 Bahan Baku</option>
                        <option value="Peralatan">🔧 Peralatan</option>
                        <option value="Transportasi">🚚 Transportasi</option>
                        <option value="Marketing">📢 Marketing & Iklan</option>
                        <option value="Administrasi">📋 Administrasi</option>
                        <option value="Lainnya">📝 Lainnya</option>
                    </select>
                </div>

                <!-- Jumlah -->
                <div class="form-group">
                    <label for="amount" class="form-label">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Jumlah Pengeluaran <span class="required">*</span></span>
                    </label>
                    <div class="input-with-icon">
                        <div class="input-icon">Rp</div>
                        <input type="number" class="form-control" id="amount" name="amount" min="0" step="1000" placeholder="Masukkan jumlah pengeluaran" required>
                    </div>
                </div>

                <!-- Tanggal -->
                <div class="form-group">
                    <label for="expense_date" class="form-label">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Tanggal Pengeluaran <span class="required">*</span></span>
                    </label>
                    <input type="date" class="form-control" id="expense_date" name="expense_date" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
        </div>

        <!-- Detail Pengeluaran Section -->
        <div class="form-section">
            <div class="section-header">
                <div class="section-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h2 class="section-title">Detail Pengeluaran</h2>
            </div>

            <div class="form-grid">
                <!-- Deskripsi -->
                <div class="form-group full-width">
                    <label for="description" class="form-label">
                        <i class="fas fa-align-left"></i>
                        <span>Deskripsi Pengeluaran <span class="required">*</span></span>
                    </label>
                    <input type="text" class="form-control" id="description" name="description" placeholder="Masukkan deskripsi singkat dan jelas tentang pengeluaran ini" required>
                </div>

                <!-- Catatan -->
                <div class="form-group full-width">
                    <label for="notes" class="form-label">
                        <i class="fas fa-sticky-note"></i>
                        <span>Catatan Tambahan (Opsional)</span>
                    </label>
                    <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Tambahkan catatan atau keterangan tambahan yang diperlukan untuk pengeluaran ini..."></textarea>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <div>
                @if(isset($queryParams) && !empty($queryParams))
                    <a href="{{ route('admin.reports.financial', $queryParams) }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        <span>Batal</span>
                    </a>
                @else
                    <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        <span>Batal</span>
                    </a>
                @endif
            </div>
            <div>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-save"></i>
                    <span>Simpan Pengeluaran</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('expenseForm');
    const submitBtn = document.getElementById('submitBtn');
    const amountInput = document.getElementById('amount');

    // Format number input dengan pemisah ribuan
    amountInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value) {
            // Format dengan pemisah ribuan untuk display
            let formattedValue = new Intl.NumberFormat('id-ID').format(value);
            // Tapi simpan value asli tanpa format untuk submit
            e.target.setAttribute('data-raw-value', value);
        }
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Show loading state
        submitBtn.classList.add('btn-loading');
        submitBtn.querySelector('span').textContent = 'Menyimpan...';

        // Reset error states
        document.querySelectorAll('.form-control, .form-select').forEach(field => {
            field.classList.remove('error');
        });
        document.querySelectorAll('.error-message').forEach(msg => {
            msg.remove();
        });

        let isValid = true;

        // Validate required fields
        const requiredFields = [
            { id: 'category', name: 'Kategori' },
            { id: 'amount', name: 'Jumlah' },
            { id: 'expense_date', name: 'Tanggal' },
            { id: 'description', name: 'Deskripsi' }
        ];

        requiredFields.forEach(field => {
            const element = document.getElementById(field.id);
            if (!element.value.trim()) {
                showFieldError(element, `${field.name} harus diisi`);
                isValid = false;
            }
        });

        // Validate amount
        const amount = document.getElementById('amount');
        if (amount.value && parseFloat(amount.value) <= 0) {
            showFieldError(amount, 'Jumlah harus lebih dari 0');
            isValid = false;
        }

        if (isValid) {
            // Submit form setelah delay singkat untuk UX yang lebih baik
            setTimeout(() => {
                // Pastikan raw value digunakan untuk amount
                if (amountInput.hasAttribute('data-raw-value')) {
                    amountInput.value = amountInput.getAttribute('data-raw-value');
                }
                form.submit();
            }, 1000);
        } else {
            // Reset button state if validation fails
            submitBtn.classList.remove('btn-loading');
            submitBtn.querySelector('span').textContent = 'Simpan Pengeluaran';
        }
    });

    function showFieldError(field, message) {
        field.classList.add('error');

        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i><span>${message}</span>`;

        field.parentNode.appendChild(errorDiv);
    }

    // Real-time validation
    document.querySelectorAll('.form-control, .form-select').forEach(field => {
        field.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('error');
            } else {
                this.classList.remove('error');
                this.classList.add('success');
                // Remove error message if exists
                const errorMsg = this.parentNode.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });

        field.addEventListener('focus', function() {
            this.classList.remove('error', 'success');
        });
    });

    // Auto-resize textarea
    const textarea = document.getElementById('notes');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.max(120, this.scrollHeight) + 'px';
    });
});
</script>
@endpush
