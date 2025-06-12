@extends('admin.layouts.app')

@section('title', 'Edit Pengeluaran')

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
        animation: slideInDown 0.6s ease-out;
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

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
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
        animation: float 8s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
        }
        50% {
            transform: translateY(-20px) rotate(180deg);
        }
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
        transition: transform 0.3s ease;
    }

    .header-icon:hover {
        transform: scale(1.1) rotate(10deg);
    }

    .header-text h1 {
        font-size: 2.25rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .header-text p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }

    .expense-id {
        background: rgba(255, 255, 255, 0.15);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .back-button {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .back-button:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }

    /* Form Container */
    .form-container {
        background: white;
        border-radius: 1.5rem;
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        animation: slideInUp 0.6s ease-out 0.2s both;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-header {
        background: linear-gradient(135deg, var(--gray-50) 0%, white 100%);
        padding: 2rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .form-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--gray-800);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-body {
        padding: 2rem;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 2rem;
        animation: fadeInUp 0.6s ease-out both;
    }

    .form-group:nth-child(1) { animation-delay: 0.1s; }
    .form-group:nth-child(2) { animation-delay: 0.2s; }
    .form-group:nth-child(3) { animation-delay: 0.3s; }
    .form-group:nth-child(4) { animation-delay: 0.4s; }
    .form-group:nth-child(5) { animation-delay: 0.5s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-label {
        display: block;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label .required {
        color: var(--red-500);
    }

    .form-control {
        width: 100%;
        padding: 1rem;
        border: 2px solid var(--gray-200);
        border-radius: 0.75rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        transform: translateY(-1px);
    }

    .form-control:hover {
        border-color: var(--gray-300);
    }

    .form-control.is-invalid {
        border-color: var(--red-500);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .form-control.is-valid {
        border-color: var(--success-500);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    /* Select Dropdown */
    .select-wrapper {
        position: relative;
    }

    .select-wrapper::after {
        content: '\f107';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-500);
        pointer-events: none;
        transition: transform 0.3s ease;
        z-index: 1;
    }

    .select-wrapper.open::after {
        transform: translateY(-50%) rotate(180deg);
    }

    select.form-control {
        appearance: none;
        background-image: none !important;
        background-repeat: no-repeat;
        cursor: pointer;
        padding-right: 3rem;
    }

    /* Remove browser default dropdown arrow */
    select.form-control::-ms-expand {
        display: none;
    }

    /* Textarea Auto-resize */
    .form-control.auto-resize {
        resize: none;
        overflow: hidden;
        min-height: 120px;
    }

    /* Invalid Feedback */
    .invalid-feedback {
        display: block;
        color: var(--red-500);
        font-size: 0.875rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    .valid-feedback {
        display: block;
        color: var(--success-500);
        font-size: 0.875rem;
        margin-top: 0.5rem;
        font-weight: 500;
    }

    /* Form Actions */
    .form-actions {
        padding: 2rem;
        background: var(--gray-50);
        border-top: 1px solid var(--gray-200);
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.875rem 2rem;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
        min-width: 140px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-orange) 0%, var(--primary-orange-dark) 100%);
        color: white;
        box-shadow: var(--shadow-md);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        background: linear-gradient(135deg, var(--primary-orange-dark) 0%, #c2410c 100%);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-secondary {
        background: var(--gray-200);
        color: var(--gray-700);
        border: 1px solid var(--gray-300);
    }

    .btn-secondary:hover {
        background: var(--gray-300);
        transform: translateY(-1px);
        color: var(--gray-700);
        text-decoration: none;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
    }

    /* Loading Spinner */
    .loading-spinner {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Error States */
    .error-message {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #dc2626;
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Success States */
    .success-message {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #16a34a;
        padding: 1rem;
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-container {
            padding: 1rem;
        }

        .page-header {
            padding: 2rem 1.5rem;
        }

        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .header-text h1 {
            font-size: 1.875rem;
        }

        .form-body {
            padding: 1.5rem;
        }

        .form-actions {
            flex-direction: column-reverse;
            padding: 1.5rem;
        }

        .btn {
            width: 100%;
        }
    }

    /* Animation for form validation */
    .shake {
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    /* Focus within enhancement */
    .form-group:focus-within .form-label {
        color: var(--primary-orange);
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    /* Custom scrollbar */
    .form-control::-webkit-scrollbar {
        width: 8px;
    }

    .form-control::-webkit-scrollbar-track {
        background: var(--gray-100);
        border-radius: 4px;
    }

    .form-control::-webkit-scrollbar-thumb {
        background: var(--gray-400);
        border-radius: 4px;
    }

    .form-control::-webkit-scrollbar-thumb:hover {
        background: var(--gray-500);
    }
</style>
@endpush

@section('content')
<div class="main-container">
    <!-- Modern Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-info">
                <div class="header-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="header-text">
                    <h1>Edit Pengeluaran</h1>
                    <p>Perbarui data pengeluaran bisnis Anda dengan mudah</p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <div class="expense-id">
                    <i class="fas fa-hashtag"></i>
                    ID: {{ $expense->id }}
                </div>
                <div class="expense-id">
                    <i class="fas fa-calendar"></i>
                    {{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}
                </div>
                <a href="{{ route('admin.reports.financial', ['year' => request('year'), 'month' => request('month')]) }}" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Laporan
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="success-message">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="error-message">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
    @endif

    <!-- Main Form -->
    <div class="form-container">
        <div class="form-header">
            <h2>
                <i class="fas fa-file-invoice-dollar text-orange-500"></i>
                Informasi Pengeluaran
            </h2>
        </div>

        <form id="editExpenseForm" action="{{ route('admin.expenses.update', $expense->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Hidden fields for year and month -->
            <input type="hidden" name="year" value="{{ request('year') }}">
            <input type="hidden" name="month" value="{{ request('month') }}">

            <div class="form-body">
                <div class="row">
                    <!-- Category Field -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="category" class="form-label">
                                <i class="fas fa-tags text-blue-500"></i>
                                Kategori
                                <span class="required">*</span>
                            </label>
                            <div class="select-wrapper">
                                <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Gaji Karyawan" {{ old('category', $expense->category) == 'Gaji Karyawan' ? 'selected' : '' }}>💼 Gaji Karyawan</option>
                                    <option value="Sewa" {{ old('category', $expense->category) == 'Sewa' ? 'selected' : '' }}>🏠 Sewa</option>
                                    <option value="Listrik" {{ old('category', $expense->category) == 'Listrik' ? 'selected' : '' }}>⚡ Listrik</option>
                                    <option value="Bahan Baku" {{ old('category', $expense->category) == 'Bahan Baku' ? 'selected' : '' }}>🐟 Bahan Baku</option>
                                    <option value="Peralatan" {{ old('category', $expense->category) == 'Peralatan' ? 'selected' : '' }}>🔧 Peralatan</option>
                                    <option value="Transportasi" {{ old('category', $expense->category) == 'Transportasi' ? 'selected' : '' }}>🚚 Transportasi</option>
                                    <option value="Marketing" {{ old('category', $expense->category) == 'Marketing' ? 'selected' : '' }}>📢 Marketing</option>
                                    <option value="Administrasi" {{ old('category', $expense->category) == 'Administrasi' ? 'selected' : '' }}>📋 Administrasi</option>
                                    <option value="Operasional" {{ old('category', $expense->category) == 'Operasional' ? 'selected' : '' }}>⚙️ Operasional</option>
                                    <option value="Lainnya" {{ old('category', $expense->category) == 'Lainnya' ? 'selected' : '' }}>📦 Lainnya</option>
                                </select>
                            </div>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Amount Field -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="amount" class="form-label">
                                <i class="fas fa-money-bill-wave text-green-500"></i>
                                Jumlah
                                <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount"
                                   value="{{ old('amount', number_format($expense->amount, 0, ',', '.')) }}" required
                                   placeholder="Masukkan jumlah pengeluaran">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Description Field -->
                <div class="form-group">
                    <label for="description" class="form-label">
                        <i class="fas fa-file-alt text-purple-500"></i>
                        Deskripsi
                        <span class="required">*</span>
                    </label>
                    <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                           value="{{ old('description', $expense->description) }}" required
                           placeholder="Deskripsi singkat pengeluaran">
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Expense Date Field -->
                <div class="form-group">
                    <label for="expense_date" class="form-label">
                        <i class="fas fa-calendar-alt text-red-500"></i>
                        Tanggal Pengeluaran
                        <span class="required">*</span>
                    </label>
                    <input type="datetime-local" class="form-control @error('expense_date') is-invalid @enderror" id="expense_date" name="expense_date"
                           value="{{ old('expense_date', \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d\TH:i')) }}" required>
                    @error('expense_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Notes Field -->
                <div class="form-group">
                    <label for="notes" class="form-label">
                        <i class="fas fa-sticky-note text-yellow-500"></i>
                        Catatan (Opsional)
                    </label>
                    <textarea class="form-control auto-resize @error('notes') is-invalid @enderror" id="notes" name="notes"
                              rows="4" placeholder="Tambahkan catatan tambahan jika diperlukan">{{ old('notes', $expense->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.reports.financial', ['year' => request('year'), 'month' => request('month')]) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    Batal
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <span class="btn-text">
                        <i class="fas fa-save"></i>
                        Update Pengeluaran
                    </span>
                    <div class="loading-spinner" style="display: none;"></div>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const form = document.getElementById('editExpenseForm');
    const submitBtn = document.getElementById('submitBtn');
    const amountInput = document.getElementById('amount');
    const categorySelect = document.getElementById('category');
    const notesTextarea = document.getElementById('notes');

    // Currency formatting for amount input
    amountInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^\d]/g, '');
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
        }
        e.target.value = value;
        validateField(e.target);
    });

    // Auto-resize textarea
    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }

    if (notesTextarea) {
        notesTextarea.addEventListener('input', function() {
            autoResize(this);
        });
        // Initial resize
        autoResize(notesTextarea);
    }

    // Select dropdown handling
    const selectWrapper = categorySelect?.parentElement;

    if (categorySelect && selectWrapper) {
        // Add open/close classes for animation
        categorySelect.addEventListener('focus', function() {
            selectWrapper.classList.add('open');
        });

        categorySelect.addEventListener('blur', function() {
            selectWrapper.classList.remove('open');
        });

        // Remove any browser default styling
        categorySelect.style.backgroundImage = 'none';
        categorySelect.style.appearance = 'none';
        categorySelect.style.webkitAppearance = 'none';
        categorySelect.style.mozAppearance = 'none';
    }

    // Form validation
    function validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;

        // Remove existing validation classes
        field.classList.remove('is-valid', 'is-invalid');

        // Remove existing feedback
        const existingFeedback = field.parentElement.querySelector('.invalid-feedback:not([data-server-error]), .valid-feedback');
        if (existingFeedback && !existingFeedback.hasAttribute('data-server-error')) {
            existingFeedback.remove();
        }

        let isValid = true;
        let errorMessage = '';

        // Validation rules
        switch(fieldName) {
            case 'category':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Kategori harus dipilih';
                }
                break;

            case 'amount':
                const numericValue = value.replace(/[^\d]/g, '');
                if (!numericValue || parseInt(numericValue) <= 0) {
                    isValid = false;
                    errorMessage = 'Jumlah harus lebih dari 0';
                } else if (parseInt(numericValue) > 999999999) {
                    isValid = false;
                    errorMessage = 'Jumlah terlalu besar';
                }
                break;

            case 'description':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Deskripsi harus diisi';
                } else if (value.length < 5) {
                    isValid = false;
                    errorMessage = 'Deskripsi minimal 5 karakter';
                } else if (value.length > 255) {
                    isValid = false;
                    errorMessage = 'Deskripsi maksimal 255 karakter';
                }
                break;

            case 'expense_date':
                if (!value) {
                    isValid = false;
                    errorMessage = 'Tanggal pengeluaran harus diisi';
                } else {
                    const selectedDate = new Date(value);
                    const now = new Date();
                    if (selectedDate > now) {
                        isValid = false;
                        errorMessage = 'Tanggal tidak boleh di masa depan';
                    }
                }
                break;
        }

        // Apply validation state
        if (isValid) {
            field.classList.add('is-valid');
            if (fieldName !== 'notes') { // Notes is optional
                const feedback = document.createElement('div');
                feedback.className = 'valid-feedback';
                feedback.innerHTML = '<i class="fas fa-check"></i> Valid';
                field.parentElement.appendChild(feedback);
            }
        } else {
            field.classList.add('is-invalid');
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + errorMessage;
            field.parentElement.appendChild(feedback);

            // Add shake animation
            field.parentElement.classList.add('shake');
            setTimeout(() => {
                field.parentElement.classList.remove('shake');
            }, 500);
        }

        return isValid;
    }

    // Add event listeners for real-time validation
    const requiredFields = ['category', 'amount', 'description', 'expense_date'];
    requiredFields.forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (field) {
            field.addEventListener('blur', () => validateField(field));
            field.addEventListener('input', () => {
                // Debounce validation for input events
                clearTimeout(field.validationTimeout);
                field.validationTimeout = setTimeout(() => validateField(field), 300);
            });
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate all required fields
        let isFormValid = true;
        requiredFields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field && !validateField(field)) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            // Scroll to first error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
            return;
        }

        // Show loading state
        const btnText = submitBtn.querySelector('.btn-text');
        const spinner = submitBtn.querySelector('.loading-spinner');

        btnText.style.display = 'none';
        spinner.style.display = 'inline-block';
        submitBtn.disabled = true;

        // Convert amount back to numeric format
        const rawAmount = amountInput.value.replace(/[^\d]/g, '');
        amountInput.value = rawAmount;

        // Submit form
        setTimeout(() => {
            form.submit();
        }, 300);
    });

    // Initialize dropdown state
    if (categorySelect.value) {
        categorySelect.parentElement.classList.remove('open');
    }

    // Focus enhancement
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
            this.parentElement.style.transition = 'transform 0.3s ease';
        });

        control.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });

    // Initialize amount formatting
    if (amountInput.value) {
        let value = amountInput.value.replace(/[^\d]/g, '');
        if (value) {
            amountInput.value = parseInt(value).toLocaleString('id-ID');
        }
    }
});
</script>
@endpush
