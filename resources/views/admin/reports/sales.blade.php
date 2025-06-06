@extends('admin.layouts.app')
@section('title', 'Laporan Penjualan')

@push('styles')
<style>
    /* Custom styles for sales report to match overall theme */
    .summary-card {
        @apply bg-white rounded-xl shadow-lg border border-gray-100 p-6 flex items-center transition-all duration-300 ease-in-out transform hover:scale-102 hover:shadow-xl;
    }
    .summary-icon {
        @apply w-14 h-14 rounded-full flex items-center justify-center text-white text-2xl flex-shrink-0 mr-4;
    }
    .card-title {
        @apply text-lg font-semibold text-gray-800 mb-4;
    }
    .table-header-custom {
        @apply bg-gray-50 text-gray-700 uppercase text-xs font-semibold tracking-wider;
    }
    .table-row-hover:hover {
        @apply bg-orange-50 transition-colors duration-150;
    }
    .chart-box {
        @apply bg-white rounded-xl shadow-lg border border-gray-100 p-6;
    }
    .filter-card {
        @apply bg-white rounded-xl shadow-lg border border-gray-100 p-6;
    }
    .form-input-custom {
        @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-orange-500 focus:border-orange-500;
    }
    .btn-primary-custom {
        @apply bg-orange-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-orange-700 transition-colors duration-200 flex items-center justify-center;
    }
    .btn-secondary-custom {
        @apply bg-gray-200 text-gray-800 px-5 py-2.5 rounded-lg font-semibold hover:bg-gray-300 transition-colors duration-200 flex items-center justify-center;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Laporan Penjualan</h1>
        <div class="flex items-center space-x-4">
            <form method="GET" class="flex items-center space-x-2">
                <label for="year" class="text-sm font-medium text-gray-700">Tahun:</label>
                <select name="year" id="year" onchange="this.form.submit()" class="form-input-custom text-sm">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </form>
            <button onclick="window.print()" class="btn-secondary-custom">
                <i class="fas fa-print mr-2"></i> Cetak Laporan
            </button>
            <button onclick="exportSalesData()" class="btn-primary-custom">
                <i class="fas fa-file-excel mr-2"></i> Ekspor Excel
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="summary-card">
            <div class="summary-icon bg-gradient-to-br from-blue-500 to-blue-700">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-blue-600 uppercase mb-1">Total Pendapatan</p>
                <h2 class="text-3xl font-bold text-gray-900">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</h2>
                <p class="text-xs text-gray-500 mt-1">Periode {{ $selectedYear }}</p>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-icon bg-gradient-to-br from-green-500 to-green-700">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-green-600 uppercase mb-1">Jumlah Pesanan</p>
                <h2 class="text-3xl font-bold text-gray-900">{{ number_format($summary['total_orders']) }}</h2>
                <p class="text-xs text-gray-500 mt-1">Pesanan selesai {{ $selectedYear }}</p>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-icon bg-gradient-to-br from-purple-500 to-purple-700">
                <i class="fas fa-calculator"></i>
            </div>
            <div>
                <p class="text-sm font-semibold text-purple-600 uppercase mb-1">Rata-rata Nilai Pesanan</p>
                <h2 class="text-3xl font-bold text-gray-900">Rp {{ number_format($summary['average_order_value'], 0, ',', '.') }}</h2>
                <p class="text-xs text-gray-500 mt-1">Per transaksi {{ $selectedYear }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="chart-box">
            <h3 class="card-title">Pendapatan Bulanan ({{ $selectedYear }})</h3>
            <div class="relative h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        <div class="chart-box">
            <h3 class="card-title">Top 5 Produk Terlaris (Berdasarkan Unit Terjual)</h3>
            <div class="relative h-72">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-200">
            <h3 class="card-title mb-0">Produk Terlaris Detail ({{ $selectedYear }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="table-header-custom">
                    <tr>
                        <th class="px-6 py-3 text-left">Produk</th>
                        <th class="px-6 py-3 text-right">Harga Satuan</th>
                        <th class="px-6 py-3 text-right">Unit Terjual</th>
                        <th class="px-6 py-3 text-right">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($topProducts as $product)
                    <tr class="table-row-hover">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                            Rp {{ number_format($product->harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">
                            {{ number_format($product->total_sold) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-orange-600 text-right">
                            Rp {{ number_format($product->total_revenue, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data produk terlaris.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="card-title mb-0">Riwayat Transaksi ({{ $selectedYear }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="table-header-custom">
                    <tr>
                        <th class="px-6 py-3 text-left">ID Pesanan</th>
                        <th class="px-6 py-3 text-left">Pelanggan</th>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-left">Item</th>
                        <th class="px-6 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($transactions as $transaction)
                    <tr class="table-row-hover">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $transaction->id_pesanan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $transaction->user->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $transaction->user->email ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $transaction->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            @foreach($transaction->detailPesanan as $detail)
                                <div>{{ $detail->produk->nama_ikan ?? 'Produk dihapus' }} (x{{ $detail->kuantitas }})</div>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-orange-600 text-right">
                            Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada riwayat transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            {{ $transactions->links('pagination::tailwind') }}
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const monthlyData = @json($yearlyData); // Pastikan ini menyediakan data { month: 'Jan', total_revenue: 1000 }

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(data => data.month),
            datasets: [{
                label: 'Pendapatan',
                data: monthlyData.map(data => data.total_revenue),
                backgroundColor: 'rgba(249, 115, 22, 0.2)',
                borderColor: '#f97316',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#f97316',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000).toFixed(1) + 'M'; // Format to millions
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    bodyFont: { size: 14 },
                    callbacks: {
                        label: function(context) {
                            return 'Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                        }
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            }
        }
    });

    // Top Products Chart
    const productsCtx = document.getElementById('topProductsChart').getContext('2d');
    const topProductsData = @json($topProducts->take(5)); // Ambil 5 produk teratas saja untuk chart

    new Chart(productsCtx, {
        type: 'bar',
        data: {
            labels: topProductsData.map(product => product.name),
            datasets: [{
                label: 'Unit Terjual',
                data: topProductsData.map(product => product.total_sold),
                backgroundColor: [
                    'rgba(79, 209, 197, 0.7)', // Teal
                    'rgba(164, 107, 255, 0.7)', // Purple
                    'rgba(255, 193, 7, 0.7)',  // Yellow
                    'rgba(23, 162, 184, 0.7)', // Info Blue
                    'rgba(108, 117, 125, 0.7)' // Gray
                ],
                borderColor: [
                    'rgba(79, 209, 197, 1)',
                    'rgba(164, 107, 255, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(23, 162, 184, 1)',
                    'rgba(108, 117, 125, 1)'
                ],
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y', // Makes it a horizontal bar chart
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    bodyFont: { size: 14 },
                    callbacks: {
                        label: function(context) {
                            return 'Terjual: ' + new Intl.NumberFormat('id-ID').format(context.parsed.x) + ' unit';
                        }
                    }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            }
        }
    });

    // Function to export sales data
    window.exportSalesData = function() {
        const selectedYear = document.getElementById('year').value;
        const url = `{{ route('admin.reports.sales') }}?year=${selectedYear}&export=true`;

        // Simulating the export process. In a real scenario, this would likely trigger a download.
        alert('Laporan Penjualan akan segera diekspor. (Fitur ekspor aktual perlu diimplementasikan di backend)');
        // window.location.href = url; // Uncomment this to trigger actual download if backend is ready
    };
});
</script>
@endpush
@endsection
