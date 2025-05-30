@extends('layouts.app')

@section('title', 'Detail Refund')

@push('styles')
<style>
    .status-badge {
        @apply px-3 py-1 rounded-full text-sm font-medium;
    }
    .status-pending { @apply bg-yellow-100 text-yellow-800; }
    .status-approved { @apply bg-green-100 text-green-800; }
    .status-rejected { @apply bg-red-100 text-red-800; }
    .status-processing { @apply bg-blue-100 text-blue-800; }
    .status-completed { @apply bg-gray-100 text-gray-800; }

    .timeline-item {
        @apply border-l-4 border-orange-400 pl-4 py-3 bg-orange-50;
    }

    .evidence-photo {
        @apply w-32 h-32 object-cover rounded-lg border-2 border-gray-200 cursor-pointer transition-transform hover:scale-105;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Detail Refund</h1>
            <p class="text-gray-600">ID: #{{ $refund->id }} - {{ $refund->pesanan->nomor_pesanan }}</p>
        </div>
        <div class="space-x-2">
            <a href="{{ route('refunds.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Status Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Current Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Status Saat Ini</h3>
            <div class="text-center">
                <span class="status-badge status-{{ $refund->status }} text-lg px-6 py-3">
                    @switch($refund->status)
                        @case('pending') Menunggu Review @break
                        @case('approved') Disetujui @break
                        @case('rejected') Ditolak @break
                        @case('processing') Sedang Diproses @break
                        @case('completed') Selesai @break
                        @default {{ ucfirst($refund->status) }}
                    @endswitch
                </span>
                <div class="mt-3 text-sm text-gray-600">
                    @if($refund->status === 'pending')
                        Permintaan refund Anda sedang direview oleh tim kami
                    @elseif($refund->status === 'approved')
                        Refund disetujui dan akan diproses segera
                    @elseif($refund->status === 'rejected')
                        Maaf, permintaan refund ditolak
                    @elseif($refund->status === 'processing')
                        Dana refund sedang diproses
                    @elseif($refund->status === 'completed')
                        Refund telah selesai diproses
                    @endif
                </div>
            </div>
        </div>

        <!-- Amount Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Jumlah Refund</h3>
            <div class="text-center">
                <div class="text-3xl font-bold text-orange-600 mb-2">
                    Rp {{ number_format($refund->amount, 0, ',', '.') }}
                </div>
                <div class="text-sm text-gray-600">
                    dari total pesanan<br>
                    Rp {{ number_format($refund->pesanan->total_harga, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Diajukan:</span>
                    <span class="font-medium">{{ $refund->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if($refund->processed_at)
                <div class="flex justify-between">
                    <span class="text-gray-600">Diproses:</span>
                    <span class="font-medium">{{ $refund->processed_at->format('d/m/Y H:i') }}</span>
                </div>
                @endif
                @if($refund->completed_at)
                <div class="flex justify-between">
                    <span class="text-gray-600">Selesai:</span>
                    <span class="font-medium">{{ $refund->completed_at->format('d/m/Y H:i') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Refund Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Reason & Description -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Detail Permintaan</h3>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Refund</label>
                    <div class="text-sm text-gray-900 p-3 bg-gray-50 rounded-lg">
                        @switch($refund->reason)
                            @case('defective') Produk Rusak/Cacat @break
                            @case('wrong_item') Barang yang Diterima Salah @break
                            @case('not_as_described') Tidak Sesuai Deskripsi @break
                            @case('dead_fish') Ikan Mati saat Diterima @break
                            @case('other') Lainnya @break
                            @default {{ ucfirst($refund->reason) }}
                        @endswitch
                    </div>
                </div>

                @if($refund->description)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Masalah</label>
                    <div class="text-sm text-gray-900 p-3 bg-gray-50 rounded-lg">
                        {{ $refund->description }}
                    </div>
                </div>
                @endif

                @if($refund->refund_method)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode Refund</label>
                    <div class="text-sm text-gray-900 p-3 bg-gray-50 rounded-lg">
                        @switch($refund->refund_method)
                            @case('bank_transfer') Transfer Bank @break
                            @case('wallet') E-Wallet @break
                            @case('store_credit') Kredit Toko @break
                            @default {{ ucfirst($refund->refund_method) }}
                        @endswitch
                    </div>
                </div>
                @endif

                @if($refund->admin_notes)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan dari Admin</label>
                    <div class="text-sm text-gray-900 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        {{ $refund->admin_notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Evidence Photos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Bukti Foto</h3>

            @if($refund->evidence_photos && count($refund->evidence_photos) > 0)
                <div class="grid grid-cols-2 gap-4">
                    @foreach($refund->evidence_photos as $photo)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $photo) }}"
                             alt="Bukti foto refund"
                             class="evidence-photo"
                             onclick="openPhotoModal('{{ asset('storage/' . $photo) }}')">
                        <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-search-plus text-white opacity-0 hover:opacity-100"></i>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-camera text-4xl mb-4"></i>
                    <p>Tidak ada bukti foto yang diunggah.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Order Information -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-medium text-gray-900 mb-6">Informasi Pesanan</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div>
                <span class="text-sm text-gray-500">Nomor Pesanan:</span>
                <div class="text-sm font-medium text-gray-900">{{ $refund->pesanan->nomor_pesanan }}</div>
            </div>
            <div>
                <span class="text-sm text-gray-500">Tanggal Pesanan:</span>
                <div class="text-sm font-medium text-gray-900">{{ $refund->pesanan->created_at->format('d/m/Y') }}</div>
            </div>
            <div>
                <span class="text-sm text-gray-500">Status Pesanan:</span>
                <div class="text-sm font-medium text-gray-900">{{ $refund->pesanan->status_pesanan }}</div>
            </div>
            <div>
                <span class="text-sm text-gray-500">Total Harga:</span>
                <div class="text-sm font-medium text-gray-900">Rp {{ number_format($refund->pesanan->total_harga, 0, ',', '.') }}</div>
            </div>
        </div>

        @if($refund->pesanan->produk)
        <div class="border-t border-gray-200 pt-6">
            <h4 class="text-md font-medium text-gray-900 mb-4">Produk</h4>
            <div class="flex items-center space-x-4">
                @if($refund->pesanan->produk->gambar)
                <img src="{{ asset('storage/' . $refund->pesanan->produk->gambar) }}"
                     alt="{{ $refund->pesanan->produk->nama }}"
                     class="w-16 h-16 object-cover rounded-lg">
                @endif
                <div>
                    <div class="text-sm font-medium text-gray-900">{{ $refund->pesanan->produk->nama }}</div>
                    <div class="text-sm text-gray-500">{{ $refund->pesanan->produk->deskripsi }}</div>
                    <div class="text-sm text-gray-500">Kuantitas: {{ $refund->pesanan->kuantitas }} ekor</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Status Information -->
    @if($refund->status === 'pending')
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-medium text-yellow-800 mb-2">Menunggu Review</h3>
                <p class="text-yellow-700">
                    Permintaan refund Anda sedang direview oleh tim kami.
                    Proses review biasanya memakan waktu 1-2 hari kerja.
                    Kami akan menghubungi Anda segera setelah review selesai.
                </p>
            </div>
        </div>
    </div>
    @elseif($refund->status === 'approved')
    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-medium text-green-800 mb-2">Refund Disetujui</h3>
                <p class="text-green-700">
                    Selamat! Permintaan refund Anda telah disetujui.
                    Dana akan segera diproses dan dikembalikan sesuai dengan metode yang dipilih.
                </p>
            </div>
        </div>
    </div>
    @elseif($refund->status === 'rejected')
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <i class="fas fa-times-circle text-red-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-medium text-red-800 mb-2">Refund Ditolak</h3>
                <p class="text-red-700">
                    Maaf, permintaan refund Anda ditolak.
                    Silakan lihat catatan admin di atas untuk informasi lebih lanjut.
                    Jika Anda memiliki pertanyaan, silakan hubungi customer service kami.
                </p>
            </div>
        </div>
    </div>
    @elseif($refund->status === 'processing')
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <i class="fas fa-cogs text-blue-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-medium text-blue-800 mb-2">Sedang Diproses</h3>
                <p class="text-blue-700">
                    Dana refund Anda sedang diproses.
                    Proses ini biasanya memakan waktu 3-5 hari kerja tergantung metode refund yang dipilih.
                </p>
            </div>
        </div>
    </div>
    @elseif($refund->status === 'completed')
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <i class="fas fa-check-double text-gray-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-800 mb-2">Refund Selesai</h3>
                <p class="text-gray-700">
                    Refund telah selesai diproses. Dana sudah dikembalikan sesuai dengan metode yang dipilih.
                    Terima kasih telah berbelanja di WIB Fish Farm.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="text-center space-x-4">
        <a href="{{ route('pesanan.show', $refund->pesanan) }}"
           class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition-colors inline-flex items-center">
            <i class="fas fa-box mr-2"></i>
            Lihat Detail Pesanan
        </a>

        <a href="{{ route('refunds.index') }}"
           class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors inline-flex items-center">
            <i class="fas fa-list mr-2"></i>
            Lihat Semua Refund
        </a>
    </div>
</div>

<!-- Photo Modal -->
<div id="photoModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-black opacity-75" onclick="closePhotoModal()"></div>
        </div>
        <div class="inline-block align-middle bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-4xl w-full">
            <div class="bg-white">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Bukti Foto</h3>
                    <button onclick="closePhotoModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-4">
                    <img id="modalPhoto" src="" alt="Bukti foto" class="w-full h-auto max-h-96 object-contain">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openPhotoModal(photoUrl) {
    document.getElementById('modalPhoto').src = photoUrl;
    document.getElementById('photoModal').classList.remove('hidden');
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('photoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePhotoModal();
    }
});
</script>
@endpush
