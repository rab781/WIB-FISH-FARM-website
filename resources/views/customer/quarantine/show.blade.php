@extends('layouts.app')

@section('title', 'Status Karantina Pesanan')

@push('styles')
<style>
    .status-badge {
        @apply px-3 py-1 rounded-full text-sm font-medium;
    }
    .status-active { @apply bg-orange-100 text-orange-800; }
    .status-completed { @apply bg-green-100 text-green-800; }
    .status-failed { @apply bg-red-100 text-red-800; }

    .timeline-item {
        @apply border-l-4 pl-4 py-3;
    }
    .timeline-healthy { @apply border-green-400 bg-green-50; }
    .timeline-warning { @apply border-yellow-400 bg-yellow-50; }
    .timeline-danger { @apply border-red-400 bg-red-50; }
    .timeline-normal { @apply border-blue-400 bg-blue-50; }

    .progress-bar {
        transition: width 0.3s ease-in-out;
    }

    .fish-icon {
        animation: swim 3s ease-in-out infinite;
    }

    @keyframes swim {
        0%, 100% { transform: translateX(0px); }
        50% { transform: translateX(10px); }
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Status Karantina Pesanan</h1>
        <p class="text-gray-600">Nomor Pesanan: <span class="font-semibold text-orange-600">{{ $pesanan->nomor_pesanan }}</span></p>
    </div>

    <!-- Progress Overview -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="text-center mb-6">
            <div class="fish-icon text-6xl text-orange-500 mb-4">
                <i class="fas fa-fish"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">Proses Karantina 7 Hari</h2>
            <p class="text-gray-600">Memastikan kesehatan ikan sebelum pengiriman</p>
        </div>

        @if($quarantine)
        <div class="space-y-6">
            <!-- Status Badge -->
            <div class="text-center">
                <span class="status-badge status-{{ $quarantine->status }} text-lg px-6 py-3">
                    @switch($quarantine->status)
                        @case('active') Karantina Berlangsung @break
                        @case('completed') Karantina Selesai @break
                        @case('failed') Karantina Gagal @break
                        @default {{ ucfirst($quarantine->status) }}
                    @endswitch
                </span>
            </div>

            <!-- Progress Bar -->
            <div class="max-w-2xl mx-auto">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Progress Karantina</span>
                    <span class="text-sm font-bold text-orange-600">{{ $quarantine->progress_percentage }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="progress-bar bg-gradient-to-r from-orange-400 to-orange-600 h-4 rounded-full"
                         style="width: {{ $quarantine->progress_percentage }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>Hari ke-{{ $quarantine->days_elapsed }}</span>
                    <span>dari {{ $quarantine->total_days }} hari</span>
                </div>
            </div>

            <!-- Timeline Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                <div>
                    <div class="text-lg font-semibold text-gray-900">{{ $quarantine->started_at->format('d/m/Y') }}</div>
                    <div class="text-sm text-gray-600">Tanggal Mulai</div>
                </div>
                <div>
                    <div class="text-lg font-semibold text-gray-900">{{ $quarantine->expected_end_date->format('d/m/Y') }}</div>
                    <div class="text-sm text-gray-600">Perkiraan Selesai</div>
                </div>
                <div>
                    @if($quarantine->completed_at)
                        <div class="text-lg font-semibold text-green-600">{{ $quarantine->completed_at->format('d/m/Y') }}</div>
                        <div class="text-sm text-gray-600">Tanggal Selesai</div>
                    @else
                        <div class="text-lg font-semibold text-orange-600">{{ $quarantine->expected_end_date->diffForHumans() }}</div>
                        <div class="text-sm text-gray-600">Sisa Waktu</div>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-8">
            <div class="text-gray-400 text-4xl mb-4">
                <i class="fas fa-info-circle"></i>
            </div>
            <p class="text-gray-600">Pesanan ini belum memasuki fase karantina</p>
        </div>
        @endif
    </div>

    <!-- Daily Monitoring Logs -->
    @if($quarantine && $quarantine->logs->count() > 0)
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-clipboard-list text-orange-500 mr-3"></i>
            Log Pemantauan Harian
        </h3>

        <div class="space-y-4">
            @foreach($quarantine->logs->sortByDesc('check_date') as $log)
            <div class="timeline-item timeline-{{ $log->status === 'healthy' ? 'healthy' : ($log->status === 'sick' ? 'danger' : ($log->status === 'warning' ? 'warning' : 'normal')) }}">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <span class="font-semibold text-gray-900">Hari ke-{{ $log->day_number }}</span>
                            <span class="text-sm text-gray-600">{{ $log->check_date->format('d/m/Y') }}</span>
                            @if($log->status === 'healthy')
                                <span class="text-green-600"><i class="fas fa-check-circle"></i> Sehat</span>
                            @elseif($log->status === 'sick')
                                <span class="text-red-600"><i class="fas fa-exclamation-triangle"></i> Sakit</span>
                            @elseif($log->status === 'warning')
                                <span class="text-yellow-600"><i class="fas fa-exclamation-circle"></i> Peringatan</span>
                            @endif
                        </div>

                        @if($log->notes)
                        <p class="text-gray-700 mb-2">{{ $log->notes }}</p>
                        @endif

                        <div class="flex space-x-6 text-sm">
                            @if($log->temperature)
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-thermometer-half text-blue-500"></i>
                                <span class="text-gray-600">Suhu: {{ $log->temperature }}°C</span>
                            </div>
                            @endif
                            @if($log->ph_level)
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-vial text-green-500"></i>
                                <span class="text-gray-600">pH: {{ $log->ph_level }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $log->created_at->format('H:i') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Product Information -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-fish text-orange-500 mr-3"></i>
            Informasi Produk
        </h3>

        <div class="flex items-start space-x-6">
            @if($pesanan->produk && $pesanan->produk->gambar)
            <div class="flex-shrink-0">
                <img src="{{ asset('storage/' . $pesanan->produk->gambar) }}"
                     alt="{{ $pesanan->produk->nama }}"
                     class="w-24 h-24 object-cover rounded-lg border-2 border-gray-200">
            </div>
            @endif

            <div class="flex-1">
                <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $pesanan->produk->nama ?? 'Produk' }}</h4>
                <p class="text-gray-600 mb-4">{{ $pesanan->produk->deskripsi ?? 'Deskripsi tidak tersedia' }}</p>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-700">Kuantitas:</span>
                        <span class="text-gray-600">{{ $pesanan->kuantitas }} ekor</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Total Harga:</span>
                        <span class="text-gray-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Panel -->
    <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-orange-800 mb-4 flex items-center">
            <i class="fas fa-info-circle mr-2"></i>
            Tentang Proses Karantina
        </h3>
        <div class="text-orange-700 space-y-2">
            <p>• <strong>7 Hari Pemantauan:</strong> Ikan dipantau secara ketat selama 7 hari untuk memastikan kesehatan optimal</p>
            <p>• <strong>Parameter Air:</strong> Suhu dan pH air dijaga dalam kondisi ideal untuk kesehatan ikan</p>
            <p>• <strong>Monitoring Harian:</strong> Kondisi ikan diperiksa setiap hari oleh tim ahli kami</p>
            <p>• <strong>Jaminan Kualitas:</strong> Hanya ikan yang sehat dan berkualitas tinggi yang akan dikirim</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="text-center space-x-4">
        <a href="{{ route('pesanan.tracking', $pesanan) }}"
           class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition-colors inline-flex items-center">
            <i class="fas fa-tracking mr-2"></i>
            Lihat Status Pesanan
        </a>

        @if($pesanan->status_pesanan === 'Selesai' && !$pesanan->ulasan)
        <a href="{{ route('pesanan.review', $pesanan) }}"
           class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors inline-flex items-center">
            <i class="fas fa-star mr-2"></i>
            Beri Ulasan
        </a>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto refresh if quarantine is active
    @if($quarantine && $quarantine->status === 'active')
    setTimeout(function() {
        location.reload();
    }, 300000); // Refresh every 5 minutes
    @endif
});
</script>
@endpush
