@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Penjualan Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-500">Total Penjualan</h3>
                <span class="{{ $revenueGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} flex items-center text-xs font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $revenueGrowth >= 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}"></path>
                    </svg>
                    {{ number_format(abs($revenueGrowth), 1) }}%
                </span>
            </div>
            <div>
                <span class="text-3xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                <p class="text-xs text-gray-500 mt-1">Dibandingkan bulan lalu</p>
            </div>
        </div>

        <!-- Total Pesanan Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-500">Total Pesanan</h3>
                <span class="{{ $ordersGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} flex items-center text-xs font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ordersGrowth >= 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}"></path>
                    </svg>
                    {{ number_format(abs($ordersGrowth), 1) }}%
                </span>
            </div>
            <div>
                <span class="text-3xl font-bold text-gray-900">{{ number_format($totalOrders) }}</span>
                <p class="text-xs text-gray-500 mt-1">Dibandingkan bulan lalu</p>
            </div>
        </div>

        <!-- Pelanggan Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-500">Total Pelanggan</h3>
                <span class="{{ $customersGrowth >= 0 ? 'text-green-500' : 'text-red-500' }} flex items-center text-xs font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $customersGrowth >= 0 ? 'M5 10l7-7m0 0l7 7m-7-7v18' : 'M19 14l-7 7m0 0l-7-7m7 7V3' }}"></path>
                    </svg>
                    {{ number_format(abs($customersGrowth), 1) }}%
                </span>
            </div>
            <div>
                <span class="text-3xl font-bold text-gray-900">{{ number_format($totalCustomers) }}</span>
                <p class="text-xs text-gray-500 mt-1">Dibandingkan bulan lalu</p>
            </div>
        </div>

        <!-- Produk Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-500">Total Produk</h3>
                <span class="text-blue-500 flex items-center text-xs font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Aktif
                </span>
            </div>
            <div>
                <span class="text-3xl font-bold text-gray-900">{{ number_format($totalProducts) }}</span>
                <p class="text-xs text-gray-500 mt-1">Produk tersedia</p>
            </div>
        </div>
    </div>

    <!-- Charts & Tables Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Sales Chart -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="py-4 px-6 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Penjualan Bulanan</h2>
            </div>
            <div class="p-4">
                <canvas id="salesChart" height="250"></canvas>
            </div>
        </div>

        <!-- Popular Products Chart -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="py-4 px-6 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Produk Terlaris</h2>
            </div>
            <div class="p-4">
                <canvas id="productsChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="py-4 px-6 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Pesanan Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID Pesanan
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pelanggan
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ $order->id_pesanan }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-sm font-medium">{{ strtoupper(substr($order->user->nama ?? 'UN', 0, 2)) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->user->nama ?? 'Unknown' }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->user->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = match($order->status_pesanan) {
                                    'Selesai' => 'bg-green-100 text-green-800',
                                    'Dikirim' => 'bg-blue-100 text-blue-800',
                                    'Diproses' => 'bg-yellow-100 text-yellow-800',
                                    'Menunggu Pembayaran' => 'bg-gray-100 text-gray-800',
                                    'Dibatalkan' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                {{ $order->status_pesanan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.pesanan.show', $order->id_pesanan) }}" class="text-blue-600 hover:text-blue-900">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            Tidak ada pesanan terbaru
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Chart with real data
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const monthlySalesData = @json($monthlySales);

        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: monthlySalesData.map(item => item.month),
                datasets: [{
                    label: 'Penjualan {{ date("Y") }}',
                    data: monthlySalesData.map(item => item.total),
                    backgroundColor: 'rgba(79, 209, 197, 0.2)',
                    borderColor: 'rgb(79, 209, 197)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Penjualan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Products Chart with real data
        const productsCtx = document.getElementById('productsChart').getContext('2d');
        const topProductsData = @json($topProducts);

        const productsChart = new Chart(productsCtx, {
            type: 'bar',
            data: {
                labels: topProductsData.map(item => item.name),
                datasets: [{
                    label: 'Jumlah Terjual',
                    data: topProductsData.map(item => item.sold),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Terjual: ' + context.parsed.y + ' unit';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
