@extends('admin.layouts.app')

@section('title', 'Statistik Ulasan')

@push('styles')
<style>
    .stat-card {
        @apply bg-white rounded-lg shadow border border-gray-200 p-6 hover:shadow-md transition-shadow;
    }

    .stat-icon {
        @apply h-12 w-12 rounded-lg flex items-center justify-center text-white text-xl;
    }

    .chart-container {
        @apply bg-white rounded-lg shadow border border-gray-200 p-6;
    }

    .progress-bar {
        @apply bg-gray-200 rounded-full h-2;
    }
    .progress-fill {
        @apply h-2 rounded-full transition-all duration-500;
    }
</style>
@endpush

@section('header')
<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900">Statistik Ulasan</h1>
    <div class="flex space-x-3">
        <a href="{{ route('admin.reviews.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-list mr-2"></i>Semua Ulasan
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="p-6 space-y-6">
    <!-- Overview Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Reviews -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-blue-500">
                    <i class="fas fa-star"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_reviews']) }}</p>
                    <p class="text-gray-600">Total Ulasan</p>
                </div>
            </div>
        </div>

        <!-- Reviews With Reply -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-green-500">
                    <i class="fas fa-reply"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['reviews_with_admin_reply']) }}</p>
                    <p class="text-gray-600">Sudah Dibalas</p>
                    @if($stats['total_reviews'] > 0)
                        <p class="text-xs text-gray-500">
                            {{ number_format(($stats['reviews_with_admin_reply'] / $stats['total_reviews']) * 100, 1) }}%
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Reviews Without Reply -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-yellow-500">
                    <i class="fas fa-comment-slash"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['reviews_without_reply']) }}</p>
                    <p class="text-gray-600">Belum Dibalas</p>
                    @if($stats['total_reviews'] > 0)
                        <p class="text-xs text-gray-500">
                            {{ number_format(($stats['reviews_without_reply'] / $stats['total_reviews']) * 100, 1) }}%
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-blue-500">
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['average_rating'], 1) }}</p>
                    <p class="text-gray-600">Rating Rata-rata</p>
                    <div class="flex text-yellow-400 text-xs mt-1">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $stats['average_rating'])
                                <i class="fas fa-star"></i>
                            @elseif ($i - 0.5 <= $stats['average_rating'])
                                <i class="fas fa-star-half-alt"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Average Rating -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-orange-500">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $stats['average_rating'] ? number_format($stats['average_rating'], 1) : '0.0' }}
                    </p>
                    <p class="text-gray-600">Rating Rata-rata</p>
                    <div class="flex text-orange-400 mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($stats['average_rating'] ?? 0))
                                <i class="fas fa-star text-sm"></i>
                            @else
                                <i class="far fa-star text-sm"></i>
                            @endif
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Replies -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-purple-500">
                    <i class="fas fa-reply"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['reviews_with_admin_reply']) }}</p>
                    <p class="text-gray-600">Dengan Balasan Admin</p>
                    @if($stats['approved_reviews'] > 0)
                        <p class="text-xs text-gray-500">
                            {{ number_format(($stats['reviews_with_admin_reply'] / $stats['approved_reviews']) * 100, 1) }}%
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Today's Reviews -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-teal-500">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['reviews_today']) }}</p>
                    <p class="text-gray-600">Hari Ini</p>
                </div>
            </div>
        </div>

        <!-- This Month's Reviews -->
        <div class="stat-card">
            <div class="flex items-center">
                <div class="stat-icon bg-indigo-500">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['reviews_this_month']) }}</p>
                    <p class="text-gray-600">Bulan Ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Status Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Status Distribution Chart -->
        <div class="chart-container">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Status Ulasan</h3>
            <div class="space-y-4">
                @php
                    $totalForPercentage = $stats['total_reviews'] ?: 1; // Avoid division by zero
                @endphp

                <!-- Approved -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-700">Disetujui</span>
                        <span class="text-sm text-gray-600">
                            {{ $stats['approved_reviews'] }} ({{ number_format(($stats['approved_reviews'] / $totalForPercentage) * 100, 1) }}%)
                        </span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill bg-green-500"
                             style="width: {{ ($stats['approved_reviews'] / $totalForPercentage) * 100 }}%"></div>
                    </div>
                </div>

                <!-- Pending -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-700">Menunggu Moderasi</span>
                        <span class="text-sm text-gray-600">
                            {{ $stats['pending_reviews'] }} ({{ number_format(($stats['pending_reviews'] / $totalForPercentage) * 100, 1) }}%)
                        </span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill bg-yellow-500"
                             style="width: {{ ($stats['pending_reviews'] / $totalForPercentage) * 100 }}%"></div>
                    </div>
                </div>

                <!-- Rejected -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm font-medium text-gray-700">Ditolak</span>
                        <span class="text-sm text-gray-600">
                            {{ $stats['rejected_reviews'] }} ({{ number_format(($stats['rejected_reviews'] / $totalForPercentage) * 100, 1) }}%)
                        </span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill bg-red-500"
                             style="width: {{ ($stats['rejected_reviews'] / $totalForPercentage) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="chart-container">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                @if($stats['pending_reviews'] > 0)
                    <a href="{{ route('admin.reviews.moderate') }}"
                       class="block w-full bg-yellow-50 hover:bg-yellow-100 border border-yellow-200 rounded-lg p-4 transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-yellow-800">Moderasi Ulasan Pending</h4>
                                <p class="text-sm text-yellow-600">{{ $stats['pending_reviews'] }} ulasan menunggu persetujuan</p>
                            </div>
                            <i class="fas fa-chevron-right text-yellow-600"></i>
                        </div>
                    </a>
                @endif

                <a href="{{ route('admin.reviews.index') }}"
                   class="block w-full bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg p-4 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-blue-800">Lihat Semua Ulasan</h4>
                            <p class="text-sm text-blue-600">Kelola dan pantau semua ulasan pelanggan</p>
                        </div>
                        <i class="fas fa-chevron-right text-blue-600"></i>
                    </div>
                </a>

                @if($stats['approved_reviews'] > 0)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-green-800">Response Rate</h4>
                                <p class="text-sm text-green-600">
                                    {{ number_format(($stats['reviews_with_admin_reply'] / $stats['approved_reviews']) * 100, 1) }}%
                                    ulasan memiliki balasan admin
                                </p>
                            </div>
                            <i class="fas fa-reply text-green-600"></i>
                        </div>
                    </div>
                @endif

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-800">Aktivitas Hari Ini</h4>
                            <p class="text-sm text-gray-600">{{ $stats['reviews_today'] }} ulasan baru diterima</p>
                        </div>
                        <i class="fas fa-calendar-day text-gray-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="chart-container">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Metrik Performa</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Moderation Rate -->
            <div class="text-center">
                <div class="text-3xl font-bold text-gray-900 mb-1">
                    @if($stats['total_reviews'] > 0)
                        {{ number_format((($stats['approved_reviews'] + $stats['rejected_reviews']) / $stats['total_reviews']) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </div>
                <div class="text-sm text-gray-600">Tingkat Moderasi</div>
                <div class="text-xs text-gray-500 mt-1">
                    {{ $stats['approved_reviews'] + $stats['rejected_reviews'] }} dari {{ $stats['total_reviews'] }} dimoderasi
                </div>
            </div>

            <!-- Approval Rate -->
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600 mb-1">
                    @if(($stats['approved_reviews'] + $stats['rejected_reviews']) > 0)
                        {{ number_format(($stats['approved_reviews'] / ($stats['approved_reviews'] + $stats['rejected_reviews'])) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </div>
                <div class="text-sm text-gray-600">Tingkat Persetujuan</div>
                <div class="text-xs text-gray-500 mt-1">
                    Dari ulasan yang dimoderasi
                </div>
            </div>

            <!-- Reply Rate -->
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600 mb-1">
                    @if($stats['approved_reviews'] > 0)
                        {{ number_format(($stats['reviews_with_admin_reply'] / $stats['approved_reviews']) * 100, 1) }}%
                    @else
                        0%
                    @endif
                </div>
                <div class="text-sm text-gray-600">Tingkat Balasan</div>
                <div class="text-xs text-gray-500 mt-1">
                    Dari ulasan yang disetujui
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trend (Basic Display) -->
    <div class="chart-container">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Trend Bulanan</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div class="p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-gray-900">{{ $stats['reviews_this_month'] }}</div>
                <div class="text-sm text-gray-600">Bulan Ini</div>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-gray-900">{{ $stats['reviews_today'] }}</div>
                <div class="text-sm text-gray-600">Hari Ini</div>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-gray-900">
                    {{ $stats['average_rating'] ? number_format($stats['average_rating'], 1) : '0.0' }}
                </div>
                <div class="text-sm text-gray-600">Rating Rata-rata</div>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-gray-900">
                    @if($stats['reviews_this_month'] > 0)
                        {{ number_format($stats['reviews_today'] / $stats['reviews_this_month'] * 30, 1) }}
                    @else
                        0
                    @endif
                </div>
                <div class="text-sm text-gray-600">Rata-rata per Hari</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Add any interactive features here in the future
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bars
    const progressBars = document.querySelectorAll('.progress-fill');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 100);
    });
});
</script>
@endpush
