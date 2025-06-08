@extends('admin.layouts.app')

@section('title', 'Pengeluaran')

@section('styles')
<style>
    /* Modal styling */
    .modal-backdrop {
        @apply fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm;
        z-index: 40;
    }

    .modal-container {
        @apply fixed inset-0 flex items-center justify-center z-50;
    }

    .modal-content {
        @apply bg-white rounded-lg shadow-xl overflow-hidden;
        z-index: 50;
        transition: all 0.3s ease;
    }

    .summary-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }

    .metric-card {
        text-align: center;
        padding: 30px 20px;
        border-radius: 15px;
        border: none;
        transition: all 0.3s ease;
    }

    .metric-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin: 0 auto 20px;
    }

    .chart-container {
        height: 400px;
        position: relative;
        margin-bottom: 30px;
    }

    .avatar-sm {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .filter-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e3e6f0;
        padding: 12px;
        transition: border-color 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 12px 20px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .badge {
        padding: 8px 12px;
        font-weight: 500;
        border-radius: 10px;
    }

    .category-badge {
        background-color: rgba(102, 126, 234, 0.1);
        color: #667eea;
        font-weight: 500;
    }

    .recurring-badge {
        background-color: rgba(118, 75, 162, 0.1);
        color: #764ba2;
    }

    .date-badge {
        background-color: rgba(0, 0, 0, 0.05);
        color: #444;
    }

    .table-responsive {
        border-radius: 15px;
        overflow: hidden;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="expense-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">📊 Pencatatan Pengeluaran</h1>
            <p class="mb-0">Kelola semua pengeluaran bisnis Anda</p>
        </div>
        <div class="d-flex">
            <button id="exportExcel" class="btn btn-light me-2">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </button>
            <a href="{{ route('admin.expenses.create') }}" class="btn btn-light">
                <i class="fas fa-plus me-1"></i> Tambah Pengeluaran
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card filter-card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.expenses.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Dari Tanggal</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label">Kategori</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Row -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-icon" style="background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);">
                        <i class="fas fa-money-bill-wave text-white"></i>
                    </div>
                    <h5 class="mb-3">Total Pengeluaran</h5>
                    <h2 class="mb-0">Rp {{ number_format($summary['total_expenses'], 0, ',', '.') }}</h2>
                    <p class="text-muted mb-0">Periode {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-icon" style="background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);">
                        <i class="fas fa-receipt text-white"></i>
                    </div>
                    <h5 class="mb-3">Jumlah Transaksi</h5>
                    <h2 class="mb-0">{{ $summary['expense_count'] }}</h2>
                    <p class="text-muted mb-0">Total transaksi pengeluaran</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card metric-card">
                <div class="card-body">
                    <div class="metric-icon" style="background: linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%);">
                        <i class="fas fa-calculator text-white"></i>
                    </div>
                    <h5 class="mb-3">Rata-rata Pengeluaran</h5>
                    <h2 class="mb-0">Rp {{ number_format($summary['avg_expense'], 0, ',', '.') }}</h2>
                    <p class="text-muted mb-0">Per transaksi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card expense-card">
                <div class="card-body">
                    <h5 class="card-title">Pengeluaran Bulanan</h5>
                    <div class="chart-container">
                        <canvas id="monthlyExpensesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card expense-card">
                <div class="card-body">
                    <h5 class="card-title">Distribusi Kategori</h5>
                    <div class="chart-container">
                        <canvas id="categoryPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="card expense-card">
        <div class="card-body">
            <h5 class="card-title mb-4">Daftar Pengeluaran</h5>

            <!-- Alert for success message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jumlah</th>
                            <th>Metode Pembayaran</th>
                            <th>Berkala</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td>
                                    <span class="date-badge">{{ $expense->expense_date instanceof \Carbon\Carbon ? $expense->expense_date->format('d M Y') : \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</span>
                                </td>
                                <td>
                                    <span class="category-badge">{{ $expense->category }}</span>
                                </td>
                                <td>{{ $expense->description }}</td>
                                <td><strong>Rp {{ number_format($expense->amount, 0, ',', '.') }}</strong></td>
                                <td>{{ $expense->payment_method ?? '-' }}</td>
                                <td>
                                    @if($expense->is_recurring)
                                        <span class="recurring-badge">{{ ucfirst($expense->recurring_type) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admin.expenses.show', $expense->id_expense) }}" class="btn btn-sm btn-info me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.expenses.edit', $expense->id_expense) }}" class="btn btn-sm btn-warning me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.expenses.destroy', $expense->id_expense) }}" method="POST" class="delete-expense-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger delete-expense-btn" data-expense-id="{{ $expense->id_expense }}" data-expense-description="{{ $expense->description }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Tidak ada data pengeluaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $expenses->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly expenses line chart
        const monthlyCtx = document.getElementById('monthlyExpensesChart').getContext('2d');

        const monthlyData = {
            labels: @json($monthlyExpenses->pluck('month')->map(function($month) {
                return \Carbon\Carbon::parse($month)->format('M Y');
            })),
            datasets: [{
                label: 'Total Pengeluaran',
                data: @json($monthlyExpenses->pluck('total')),
                fill: true,
                backgroundColor: 'rgba(102, 126, 234, 0.2)',
                borderColor: '#667eea',
                borderWidth: 2,
                tension: 0.3,
                pointBackgroundColor: '#764ba2',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        };

        const monthlyConfig = {
            type: 'line',
            data: monthlyData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        bodyFont: {
                            size: 14
                        },
                        callbacks: {
                            label: function(context) {
                                return 'Total: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        };

        new Chart(monthlyCtx, monthlyConfig);

        // Category pie chart
        const categoryCtx = document.getElementById('categoryPieChart').getContext('2d');

        const categoryData = {
            labels: @json($categoryDistribution->pluck('category')),
            datasets: [{
                data: @json($categoryDistribution->pluck('total')),
                backgroundColor: [
                    '#667eea', '#764ba2', '#a1c4fd', '#c2e9fb', '#fbc2eb',
                    '#a18cd1', '#ff9a9e', '#fad0c4', '#ffecd2', '#fcb69f'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        };

        const categoryConfig = {
            type: 'doughnut',
            data: categoryData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        bodyFont: {
                            size: 14
                        },
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                }
            }
        };

        new Chart(categoryCtx, categoryConfig);

        // SweetAlert2 for Delete Expense
        document.querySelectorAll('.delete-expense-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const expenseId = this.getAttribute('data-expense-id');
                const expenseDescription = this.getAttribute('data-expense-description');
                const form = this.closest('.delete-expense-form');

                Swal.fire({
                    title: 'Hapus Pengeluaran',
                    text: `Apakah Anda yakin ingin menghapus pengeluaran "${expenseDescription}"? Tindakan ini tidak dapat dibatalkan.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus',
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
                            title: 'Menghapus...',
                            text: 'Sedang menghapus data pengeluaran',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit the form
                        form.submit();
                    }
                });
            });
        });

        // Excel Export with SweetAlert2 confirmation
        document.getElementById('exportExcel').addEventListener('click', function() {
            Swal.fire({
                title: 'Export Data Excel',
                text: 'Apakah Anda ingin mengexport data pengeluaran ke file Excel?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Export',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'animate__animated animate__fadeInDown'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses Export...',
                        text: 'Sedang mempersiapkan file Excel',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Fetch data from server
                    fetch(`{{ route('admin.expenses.export') }}?start_date={{ $startDate }}&end_date={{ $endDate }}&category={{ $category }}`)
                        .then(response => response.json())
                        .then(response => {
                            const data = response.data;
                            const startDate = response.startDate;
                            const endDate = response.endDate;

                            // Create workbook and worksheet
                            const wb = XLSX.utils.book_new();

                            // Add title rows with filter info
                            const title = [
                                ["LAPORAN PENGELUARAN"],
                                ["Periode:", `${startDate} sampai ${endDate}`],
                                [""],
                                ["Tanggal", "Kategori", "Deskripsi", "Jumlah", "Metode Pembayaran", "Berkala", "Tipe Berkala"]
                            ];

                            // Add data rows
                            data.forEach(expense => {
                                title.push([
                                    new Date(expense.expense_date).toLocaleDateString('id-ID'),
                                    expense.category,
                                    expense.description,
                                    expense.amount,
                                    expense.payment_method || '-',
                                    expense.is_recurring ? 'Ya' : 'Tidak',
                                    expense.recurring_type || '-'
                                ]);
                            });

                            // Create sheet and export
                            const ws = XLSX.utils.aoa_to_sheet(title);
                            XLSX.utils.book_append_sheet(wb, ws, "Pengeluaran");
                            XLSX.writeFile(wb, `laporan-pengeluaran-${startDate}-${endDate}.xlsx`);

                            // Show success message
                            Swal.fire({
                                title: 'Export Berhasil!',
                                text: 'File Excel berhasil didownload',
                                icon: 'success',
                                confirmButtonColor: '#10b981',
                                customClass: {
                                    popup: 'animate__animated animate__fadeInUp'
                                }
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Export Gagal!',
                                text: 'Terjadi kesalahan saat mengexport data',
                                icon: 'error',
                                confirmButtonColor: '#dc2626',
                                customClass: {
                                    popup: 'animate__animated animate__shakeX'
                                }
                            });
                        });
                }
            });
        });
    });
</script>
@endsection
