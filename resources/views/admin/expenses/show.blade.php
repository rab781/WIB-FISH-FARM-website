@extends('admin.layouts.app')

    @section('title', 'Detail Pengeluaran')

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
        }

        .expense-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .info-group {
            margin-bottom: 1.5rem;
        }

        .info-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 500;
        }

        .badge {
            padding: 8px 12px;
            font-weight: 500;
            border-radius: 10px;
        }

        .category-badge {
            background-color: rgba(249, 115, 22, 0.1); /* Tailwind orange-500 light */
            color: #f97316; /* Tailwind orange-500 */
        }

        .btn-action {
            border-radius: 10px;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .created-info {
            font-size: 0.9rem;
            color: #6c757d;
            padding: 10px;
            background-color: rgba(0,0,0,0.02);
            border-radius: 10px;
        }
    </style>
    @endsection

    @section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="expense-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">🧾 Detail Pengeluaran</h1>
                <p class="mb-0">Informasi lengkap tentang pengeluaran</p>
            </div>
            <div>
                <a href="{{ route('admin.expenses.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12"> {{-- Ubah dari col-md-8 menjadi col-md-12 karena tidak ada receipt image --}}
                <div class="card expense-card mb-4">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-4">Informasi Pengeluaran</h4>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <div class="info-label">Kategori</div>
                                    <div class="info-value">
                                        <span class="badge category-badge">{{ $expense->category }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-group">
                                    <div class="info-label">Tanggal</div>
                                    <div class="info-value">{{ $expense->expense_date instanceof \Carbon\Carbon ? $expense->expense_date->format('d F Y') : \Carbon\Carbon::parse($expense->expense_date)->format('d F Y') }}</div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="info-group">
                                    <div class="info-label">Deskripsi</div>
                                    <div class="info-value">{{ $expense->description }}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-group">
                                    <div class="info-label">Jumlah</div>
                                    <div class="info-value text-danger">Rp {{ number_format($expense->amount, 0, ',', '.') }}</div>
                                </div>
                            </div>

                            <div class="col-md-12"> {{-- Notes akan mengambil seluruh lebar --}}
                                <div class="info-group">
                                    <div class="info-label">Catatan</div>
                                    <div class="info-value">{{ $expense->notes ?? '-' }}</div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="created-info">
                                    <small>
                                        <i class="fas fa-clock me-1"></i> Dibuat: {{ $expense->created_at->format('d M Y H:i') }}
                                        @if($expense->created_at != $expense->updated_at)
                                            <span class="ms-3"><i class="fas fa-edit me-1"></i> Diperbarui: {{ $expense->updated_at->format('d M Y H:i') }}</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <a href="{{ route('admin.expenses.edit', $expense->id) }}" class="btn btn-warning btn-action">
                        <i class="fas fa-edit me-1"></i> Edit Pengeluaran
                    </a>

                    <form id="deleteExpenseShowForm" action="{{ route('admin.expenses.destroy', $expense->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" id="deleteExpenseShowBtn" class="btn btn-danger btn-action">
                            <i class="fas fa-trash me-1"></i> Hapus Pengeluaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
document.getElementById('deleteExpenseShowBtn').addEventListener('click', function(e) {
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

            document.getElementById('deleteExpenseShowForm').submit();
        }
    });
});
</script>
@endpush

@endsection
