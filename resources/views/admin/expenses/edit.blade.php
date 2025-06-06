@extends('admin.layouts.app')

    @section('title', 'Edit Pengeluaran')

    @section('styles')
    <style>
        .expense-header {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .expense-card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 30px;
        }

        .expense-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e3e6f0;
            padding: 12px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 0.2rem rgba(249, 115, 22, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(249, 115, 22, 0.4);
        }

        .btn-secondary {
            border-radius: 10px;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
        }

        label {
            font-weight: 500;
            margin-bottom: 8px;
        }
    </style>
    @endsection

    @section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="expense-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">✏️ Edit Pengeluaran</h1>
                <p class="mb-0">Perbarui data pengeluaran bisnis Anda</p>
            </div>
            <div>
                <a href="{{ route('admin.expenses.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card expense-card">
            <form action="{{ route('admin.expenses.update', $expense->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="" disabled>Pilih Kategori</option>
                            <option value="Gaji" {{ $expense->category == 'Gaji' ? 'selected' : '' }}>Gaji Karyawan</option>
                            <option value="Sewa" {{ $expense->category == 'Sewa' ? 'selected' : '' }}>Sewa Toko/Gudang</option>
                            <option value="Listrik" {{ $expense->category == 'Listrik' ? 'selected' : '' }}>Listrik & Utilitas</option>
                            <option value="Bahan" {{ $expense->category == 'Bahan' ? 'selected' : '' }}>Bahan Baku</option>
                            <option value="Peralatan" {{ $expense->category == 'Peralatan' ? 'selected' : '' }}>Peralatan</option>
                            <option value="Transportasi" {{ $expense->category == 'Transportasi' ? 'selected' : '' }}>Transportasi</option>
                            <option value="Marketing" {{ $expense->category == 'Marketing' ? 'selected' : '' }}>Marketing & Iklan</option>
                            <option value="Administrasi" {{ $expense->category == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                            <option value="Lainnya" {{ $expense->category == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            {{-- Pastikan kategori yang sudah ada terpilih meskipun tidak di daftar statis --}}
                            @if (!in_array($expense->category, ['Gaji', 'Sewa', 'Listrik', 'Bahan', 'Peralatan', 'Transportasi', 'Marketing', 'Administrasi', 'Lainnya']))
                                <option value="{{ $expense->category }}" selected>{{ $expense->category }}</option>
                            @endif
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="amount" class="form-label">Jumlah (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" min="0" step="1000" value="{{ old('amount', $expense->amount) }}" placeholder="Contoh: 250000" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description', $expense->description) }}" placeholder="Deskripsi pengeluaran" required>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="expense_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('expense_date') is-invalid @enderror" id="expense_date" name="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                        @error('expense_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-4">
                        <label for="notes" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Tambahkan catatan tambahan jika diperlukan">{{ old('notes', $expense->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Perbarui Pengeluaran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endsection
