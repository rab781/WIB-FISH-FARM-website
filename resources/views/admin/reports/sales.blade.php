@extends('admin.layouts.app')
@section('title', 'Laporan Penjualan')

@push('styles')
<style>
    /* Custom styles for sales report to match overall theme */
    .summary-card {
        @apply bg-white rounded-lg shadow border-t-4 p-5 flex items-center transition-all duration-300 ease-in-out relative overflow-hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .summary-card:hover {
        @apply shadow-lg;
        transform: translateY(-5px);
    }

    .summary-card.revenue {
        @apply border-blue-500;
    }

    .summary-card.orders {
        @apply border-green-500;
    }

    .summary-card.average {
        @apply border-purple-500;
    }

    .summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(249,115,22,0.03) 0%, rgba(249,115,22,0) 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
        z-index: 0;
    }

    .summary-icon {
        @apply w-14 h-14 rounded-lg flex items-center justify-center text-white text-xl flex-shrink-0 mr-4 shadow-md z-10;
    }

    .card-title {
        @apply text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100;
    }

    .dashboard-title {
        @apply text-xl font-bold text-gray-800 mb-6 pb-2 border-b border-gray-200;
    }

    .table-header-custom {
        @apply bg-gray-50 text-gray-700 uppercase text-xs font-semibold tracking-wider;
    }

    .table-row-hover:hover {
        @apply bg-orange-50 transition-colors duration-150;
    }

    .table-row-hover:hover td:first-child {
        @apply border-l-4 border-orange-500;
    }

    .chart-box {
        @apply bg-white rounded-lg shadow border-t-4 border-orange-500 p-5 transition-all duration-300;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
    }

    .chart-box:hover {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.01);
    }

    .filter-card {
        @apply bg-white rounded-lg shadow border-t-4 border-gray-300 p-5;
    }

    /* Animated elements */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
        100% { transform: translateY(0px); }
    }

    .float-animation {
        animation: float 4s ease-in-out infinite;
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

    .stat-label {
        @apply text-sm font-semibold uppercase mb-1 flex items-center gap-1;
    }

    .stat-value {
        @apply text-2xl font-bold text-gray-900;
    }

    .stat-desc {
        @apply text-xs text-gray-500 mt-1 flex items-center gap-1;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section with Filter -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 mb-6">
            <div class="flex items-center bg-gradient-to-r from-orange-500 to-orange-600 text-white p-6 rounded-xl shadow-xl flex-1">
                <div class="bg-white/20 p-4 rounded-lg mr-5 backdrop-blur-sm">
                    <i class="fas fa-chart-line text-white text-3xl"></i>
                </div>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold">Laporan Penjualan</h1>
                    <p class="text-white/80 text-sm mt-1">Analisis data penjualan periode {{ $selectedYear }}</p>
                    <div class="flex mt-2 space-x-2">
                        <span class="bg-white/20 text-white text-xs px-3 py-1 rounded-full backdrop-blur-sm">
                            <i class="fas fa-calendar mr-1"></i> {{ $selectedYear }}
                        </span>
                        <span class="bg-white/20 text-white text-xs px-3 py-1 rounded-full backdrop-blur-sm">
                            <i class="fas fa-chart-pie mr-1"></i> Laporan Kinerja
                        </span>
                    </div>
                </div>
            </div>
            <form method="GET" class="flex items-center space-x-3 bg-white p-4 rounded-xl shadow-lg border border-gray-200 lg:ml-6">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-filter text-orange-500"></i>
                    <label for="year" class="text-sm font-medium text-gray-700">Filter Tahun:</label>
                </div>
                <select name="year" id="year" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 p-2.5 min-w-[120px] appearance-none pr-8" style="background-image: url('data:image/svg+xml;utf8,<svg fill=%23374151 xmlns=http://www.w3.org/2000/svg viewBox=0 0 24 24><path d=M7 10l5 5 5-5z/></svg>'); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.2em;">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl shadow-lg p-8 mb-8">
        <h2 class="text-3xl font-bold mb-6">Ringkasan Kinerja {{ $selectedYear }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="summary-card revenue group hover:transform hover:-translate-y-2 transition-all duration-300">
                <div>
                    <p class="stat-label text-blue-200">
                        <i class="fas fa-chart-line mr-1 group-hover:translate-y-[-2px] transition-all duration-300"></i>
                        Total Pendapatan
                    </p>
                    <h2 class="stat-value text-white">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</h2>
                    <p class="stat-desc text-blue-100">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Periode {{ $selectedYear }}
                    </p>
                </div>
            </div>
            <div class="summary-card orders group hover:transform hover:-translate-y-2 transition-all duration-300">
                <div>
                    <p class="stat-label text-green-200">
                        <i class="fas fa-shopping-cart mr-1 group-hover:translate-y-[-2px] transition-all duration-300"></i>
                        Jumlah Pesanan
                    </p>
                    <h2 class="stat-value text-white">{{ number_format($summary['total_orders']) }}</h2>
                    <p class="stat-desc text-green-100">
                        <i class="fas fa-check-circle mr-1"></i>
                        Pesanan selesai {{ $selectedYear }}
                    </p>
                </div>
            </div>
            <div class="summary-card average group hover:transform hover:-translate-y-2 transition-all duration-300">
                <div>
                    <p class="stat-label text-purple-200">
                        <i class="fas fa-tag mr-1 group-hover:translate-y-[-2px] transition-all duration-300"></i>
                        Rata-rata Nilai Pesanan
                    </p>
                    <h2 class="stat-value text-white">Rp {{ number_format($summary['average_order_value'], 0, ',', '.') }}</h2>
                    <p class="stat-desc text-purple-100">
                        <i class="fas fa-receipt mr-1"></i>
                        Per transaksi {{ $selectedYear }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="chart-box group hover:transform hover:-translate-y-2 transition-all duration-300">
            <div class="flex justify-between items-center mb-2">
                <h3 class="card-title flex items-center w-full">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3 shadow-sm group-hover:bg-orange-200 transition-colors">
                        <i class="fas fa-chart-line text-orange-500"></i>
                    </div>
                    Pendapatan Bulanan ({{ $selectedYear }})
                </h3>
            </div>
            <div class="bg-gradient-to-r from-gray-50 to-transparent p-3 mb-4 rounded text-xs text-gray-600 flex items-center border-l-4 border-orange-200">
                <i class="fas fa-info-circle text-orange-500 mx-2"></i>
                Visualisasi pendapatan bulanan selama periode {{ $selectedYear }}
            </div>
            <div class="relative h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        <div class="chart-box group hover:transform hover:-translate-y-2 transition-all duration-300">
            <div class="flex justify-between items-center mb-2">
                <h3 class="card-title flex items-center w-full">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3 shadow-sm group-hover:bg-orange-200 transition-colors">
                        <i class="fas fa-trophy text-orange-500"></i>
                    </div>
                    Top 5 Produk Terlaris
                </h3>
            </div>
            <div class="bg-gradient-to-r from-gray-50 to-transparent p-3 mb-4 rounded text-xs text-gray-600 flex items-center border-l-4 border-orange-200">
                <i class="fas fa-info-circle text-orange-500 mx-2"></i>
                Berdasarkan unit terjual pada periode {{ $selectedYear }}
            </div>
            <div class="relative h-72">
                <canvas id="topProductsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border-t-4 border-orange-500 overflow-hidden mb-8 transform transition-all duration-300 hover:shadow-xl">
        <div class="p-6 border-b border-gray-200 flex items-center">
            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3 shadow-sm">
                <i class="fas fa-list-ul text-orange-500"></i>
            </div>
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

    <div class="bg-white rounded-xl shadow-lg border-t-4 border-gray-500 overflow-hidden transform transition-all duration-300 hover:shadow-xl">
        <div class="p-6 border-b border-gray-200 flex items-center">
            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3 shadow-sm">
                <i class="fas fa-history text-gray-500"></i>
            </div>
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
                            <div class="flex items-center">
                                <span class="bg-orange-100 text-orange-600 w-6 h-6 flex items-center justify-center rounded-full mr-2">
                                    <i class="fas fa-receipt text-xs"></i>
                                </span>
                                #{{ $transaction->id_pesanan }}
                            </div>
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

    // Create gradient
    const revenueGradient = revenueCtx.createLinearGradient(0, 0, 0, 400);
    revenueGradient.addColorStop(0, 'rgba(249, 115, 22, 0.4)');
    revenueGradient.addColorStop(0.7, 'rgba(249, 115, 22, 0.05)');
    revenueGradient.addColorStop(1, 'rgba(249, 115, 22, 0)');

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(data => data.month),
            datasets: [{
                label: 'Pendapatan',
                data: monthlyData.map(data => data.total_revenue),
                backgroundColor: revenueGradient,
                borderColor: '#f97316',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#f97316',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointShadowOffsetX: 0,
                pointShadowOffsetY: 2,
                pointShadowBlur: 5,
                pointShadowColor: 'rgba(0, 0, 0, 0.2)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        lineWidth: 0.5
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000).toFixed(1) + 'M'; // Format to millions
                        },
                        font: {
                            size: 11
                        },
                        color: '#64748b'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        color: '#64748b'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                    titleColor: '#334155',
                    bodyColor: '#334155',
                    bodyFont: { size: 13, weight: 'bold' },
                    titleFont: { size: 14 },
                    padding: 12,
                    borderColor: 'rgba(249, 115, 22, 0.3)',
                    borderWidth: 1,
                    displayColors: false,
                    cornerRadius: 8,
                    boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            },
            elements: {
                line: {
                    borderJoinStyle: 'round'
                }
            }
        }
    });

    // Top Products Chart
    const productsCtx = document.getElementById('topProductsChart').getContext('2d');
    const topProductsData = @json($topProducts->take(5)); // Ambil 5 produk teratas saja untuk chart

    // Create custom colors with gradient
    const gradients = [];
    const colors = [
        { start: 'rgba(79, 209, 197, 0.9)', end: 'rgba(79, 209, 197, 0.6)' },   // Teal
        { start: 'rgba(164, 107, 255, 0.9)', end: 'rgba(164, 107, 255, 0.6)' }, // Purple
        { start: 'rgba(255, 193, 7, 0.9)', end: 'rgba(255, 193, 7, 0.6)' },     // Yellow
        { start: 'rgba(23, 162, 184, 0.9)', end: 'rgba(23, 162, 184, 0.6)' },   // Info Blue
        { start: 'rgba(249, 115, 22, 0.9)', end: 'rgba(249, 115, 22, 0.6)' }    // Orange
    ];

    // Create horizontal gradient for each bar
    colors.forEach((color, i) => {
        const gradient = productsCtx.createLinearGradient(0, 0, 300, 0);
        gradient.addColorStop(0, color.start);
        gradient.addColorStop(1, color.end);
        gradients.push(gradient);
    });

    new Chart(productsCtx, {
        type: 'bar',
        data: {
            labels: topProductsData.map(product => product.name),
            datasets: [{
                label: 'Unit Terjual',
                data: topProductsData.map(product => product.total_sold),
                backgroundColor: gradients,
                borderColor: [
                    'rgba(79, 209, 197, 1)',
                    'rgba(164, 107, 255, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(23, 162, 184, 1)',
                    'rgba(249, 115, 22, 1)'
                ],
                borderWidth: 1,
                borderRadius: 8,
                borderSkipped: false,
                barPercentage: 0.6,
                hoverBackgroundColor: [
                    'rgba(79, 209, 197, 1)',
                    'rgba(164, 107, 255, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(23, 162, 184, 1)',
                    'rgba(249, 115, 22, 1)'
                ],
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
                        color: 'rgba(0, 0, 0, 0.04)',
                        lineWidth: 0.5
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        color: '#64748b'
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11,
                            weight: 'bold'
                        },
                        color: '#334155'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                    titleColor: '#334155',
                    bodyColor: '#334155',
                    titleFont: { size: 14 },
                    bodyFont: { size: 13, weight: 'bold' },
                    padding: 12,
                    borderColor: 'rgba(0, 0, 0, 0.1)',
                    borderWidth: 1,
                    displayColors: true,
                    cornerRadius: 8,
                    boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                    callbacks: {
                        label: function(context) {
                            return 'Terjual: ' + new Intl.NumberFormat('id-ID').format(context.parsed.x) + ' unit';
                        }
                    }
                }
            },
            animation: {
                delay: function(context) {
                    return context.dataIndex * 100;
                },
                duration: 1000,
                easing: 'easeOutQuart'
            }
        }
    });

    // Function to export sales data
    window.exportSalesData = function() {
        const selectedYear = document.getElementById('year').value;
        const url = `{{ route('admin.reports.sales') }}?year=${selectedYear}&export=true`;

        // Show SweetAlert2 for export
        Swal.fire({
            title: 'Ekspor Laporan Penjualan',
            text: 'Memulai ekspor laporan penjualan...',
            icon: 'info',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-lg'
            }
        }).then(() => {
            // window.location.href = url; // Uncomment this to trigger actual download if backend is ready
            Swal.fire({
                title: 'Fungsi Belum Tersedia',
                text: 'Fitur ekspor aktual perlu diimplementasikan di backend.',
                icon: 'warning',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Mengerti',
                customClass: {
                    popup: 'rounded-lg',
                    confirmButton: 'rounded-md'
                }
            });
        });
    };
});
</script>
@endpush
@endsection
