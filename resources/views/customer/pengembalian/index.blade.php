@extends('layouts.app')

@section('title', 'Kelola Refund')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css">
<style>
    .status-badge {
        @apply px-3 py-1 rounded-full text-sm font-medium;
    }
    .status-pending { @apply bg-yellow-100 text-yellow-800; }
    .status-approved { @apply bg-green-100 text-green-800; }
    .status-rejected { @apply bg-red-100 text-red-800; }
    .status-processing { @apply bg-blue-100 text-blue-800; }
    .status-completed { @apply bg-gray-100 text-gray-800; }

    .refund-card {
        @apply bg-white rounded-lg shadow hover:shadow-md transition-shadow border border-gray-200 p-6;
    }

    .refund-card:hover {
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }

    .evidence-photo {
        @apply w-20 h-20 object-cover rounded-lg border-2 border-gray-200 cursor-pointer transition-transform hover:scale-105;
    }

    .page-header {
        @apply bg-gradient-to-r from-orange-500 to-orange-600 text-white py-8 rounded-lg shadow-md mb-8;
    }

    .dashboard-card {
        @apply bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow duration-300;
    }

    .form-card {
        @apply bg-white rounded-lg shadow-lg p-6;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="page-header text-center mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Kelola Refund</h1>
        <p class="text-orange-100">Lihat dan kelola permintaan refund Anda</p>

        <div class="mt-6 mx-auto max-w-md">
            <a href="{{ route('pesanan.index') }}" class="inline-block bg-white text-orange-600 px-4 py-2 rounded-lg hover:bg-orange-50 transition-colors mr-2">
                <i class="fas fa-shopping-bag mr-2"></i>Pesanan Saya
            </a>
            <a href="{{ route('refunds.create') }}" class="inline-block bg-orange-700 text-white px-4 py-2 rounded-lg hover:bg-orange-800 transition-colors">
                <i class="fas fa-plus mr-2"></i>Ajukan Refund
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="dashboard-card bg-gradient-to-r from-orange-500 to-orange-600 text-white">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 rounded-full bg-white bg-opacity-20">
                    <i class="fas fa-undo text-2xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-orange-100">Total Refund</div>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="text-gray-600">Menunggu Review</div>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['approved'] ?? 0 }}</div>
                    <div class="text-gray-600">Disetujui</div>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-money-bill-wave text-xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_amount'] ?? 0, 0, ',', '.') }}</div>
                    <div class="text-gray-600">Total Dana</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="form-card mb-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Filter & Pencarian</h3>
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Refund</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nomor pesanan atau ID refund..."
                           class="w-full border border-gray-300 rounded-lg pl-10 px-3 py-2 focus:ring-orange-500 focus:border-orange-500 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('refunds.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Refund List -->
    @if($refunds->count() > 0)
    <div class="space-y-6">
        @foreach($refunds as $refund)
        <div class="refund-card">
            <div class="flex flex-wrap justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                        Refund #{{ $refund->id }}
                    </h3>
                    <p class="text-gray-600">Pesanan: {{ $refund->pesanan->nomor_pesanan }}</p>
                </div>
                <div class="text-right">
                    <span class="status-badge status-{{ $refund->status }}">
                        @switch($refund->status)
                            @case('pending') Menunggu Review @break
                            @case('approved') Disetujui @break
                            @case('rejected') Ditolak @break
                            @case('processing') Sedang Diproses @break
                            @case('completed') Selesai @break
                            @default {{ ucfirst($refund->status) }}
                        @endswitch
                    </span>
                    <div class="text-sm text-gray-500 mt-1">{{ $refund->created_at->format('d/m/Y') }}</div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="flex items-center space-x-4 mb-4 p-4 bg-gray-50 rounded-lg">
                @if($refund->pesanan->produk && $refund->pesanan->produk->gambar)
                <img src="{{ asset('storage/' . $refund->pesanan->produk->gambar) }}"
                     alt="{{ $refund->pesanan->produk->nama }}"
                     class="w-16 h-16 object-cover rounded-lg">
                @endif
                <div class="flex-1">
                    <div class="font-medium text-gray-900">{{ $refund->pesanan->produk->nama ?? 'Produk' }}</div>
                    <div class="text-sm text-gray-600">Kuantitas: {{ $refund->pesanan->kuantitas }} ekor</div>
                    <div class="text-sm font-medium text-orange-600">Rp {{ number_format($refund->amount, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Refund Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <span class="text-sm font-medium text-gray-700">Alasan:</span>
                    <div class="text-sm text-gray-900">
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
                @if($refund->refund_method)
                <div>
                    <span class="text-sm font-medium text-gray-700">Metode Refund:</span>
                    <div class="text-sm text-gray-900">
                        @switch($refund->refund_method)
                            @case('bank_transfer') Transfer Bank @break
                            @case('wallet') E-Wallet @break
                            @case('store_credit') Kredit Toko @break
                            @default {{ ucfirst($refund->refund_method) }}
                        @endswitch
                    </div>
                </div>
                @endif
            </div>

            @if($refund->description)
            <div class="mb-4">
                <span class="text-sm font-medium text-gray-700">Deskripsi:</span>
                <div class="text-sm text-gray-900 mt-1">{{ $refund->description }}</div>
            </div>
            @endif

            @if($refund->admin_notes)
            <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                <span class="text-sm font-medium text-blue-800">Catatan Admin:</span>
                <div class="text-sm text-blue-900 mt-1">{{ $refund->admin_notes }}</div>
            </div>
            @endif

            <!-- Evidence Photos -->
            @if($refund->evidence_photos && count($refund->evidence_photos) > 0)
            <div class="mb-4">
                <span class="text-sm font-medium text-gray-700 mb-2 block">Bukti Foto:</span>
                <div class="flex space-x-2">
                    @foreach(array_slice($refund->evidence_photos, 0, 3) as $photo)
                    <img src="{{ asset('storage/' . $photo) }}"
                         alt="Bukti foto"
                         class="evidence-photo"
                         onclick="openPhotoModal('{{ asset('storage/' . $photo) }}')">
                    @endforeach
                    @if(count($refund->evidence_photos) > 3)
                    <div class="w-20 h-20 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 text-sm hover:bg-gray-200 cursor-pointer transition-colors" onclick="openAllPhotosModal()">
                        <div>
                            <i class="fas fa-images block mb-1"></i>
                            +{{ count($refund->evidence_photos) - 3 }} lagi
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500">
                    Diajukan {{ $refund->created_at->diffForHumans() }}
                </div>
                <div class="space-x-2">
                    <a href="{{ route('refunds.show', $refund) }}"
                       class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 text-sm">
                        <i class="fas fa-eye mr-2"></i>Detail
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($refunds->hasPages())
    <div class="mt-8">
        {{ $refunds->appends(request()->all())->links() }}
    </div>
    @endif

    @else
    <!-- Empty State -->
    <div class="text-center py-12">
        <div class="text-gray-400 text-6xl mb-4">
            <i class="fas fa-undo"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Refund</h3>
        <p class="text-gray-600 mb-6">Anda belum pernah mengajukan permintaan refund.</p>
        <a href="{{ route('pesanan.index') }}"
           class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 inline-flex items-center">
            <i class="fas fa-shopping-bag mr-2"></i>
            Lihat Pesanan
        </a>
    </div>
    @endif
</div>

<!-- Photo Modal -->
<div id="photoModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-black opacity-75" onclick="closePhotoModal()"></div>
        </div>
        <div class="inline-block align-middle bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full">
            <div class="bg-white">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Bukti Foto</h3>
                    <button onclick="closePhotoModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-4">
                    <img id="modalPhoto" src="" alt="Bukti foto" class="w-full h-auto max-h-96 object-contain rounded-lg">
                </div>
                <div class="bg-gray-50 px-4 py-3 flex justify-end">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors focus:outline-none" onclick="closePhotoModal()">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- All Photos Modal -->
<div id="allPhotosModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-black opacity-75" onclick="closeAllPhotosModal()"></div>
        </div>
        <div class="inline-block align-middle bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-3xl w-full">
            <div class="bg-white">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Semua Bukti Foto</h3>
                    <button onclick="closeAllPhotosModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4" id="allPhotosContainer">
                        <!-- Photos will be added here by JS -->
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 flex justify-end">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors focus:outline-none" onclick="closeAllPhotosModal()">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
// Current refund being viewed for all photos modal
let currentRefundPhotos = [];

function openPhotoModal(photoUrl) {
    document.getElementById('modalPhoto').src = photoUrl;
    document.getElementById('photoModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function openAllPhotosModal(refundId, photos) {
    // Store the photos in the global variable
    if (photos) {
        currentRefundPhotos = photos;
    }

    // Clear the container
    const container = document.getElementById('allPhotosContainer');
    container.innerHTML = '';

    // Populate with all photos
    currentRefundPhotos.forEach(photo => {
        const photoElement = document.createElement('div');
        photoElement.className = 'evidence-photo-container';
        photoElement.innerHTML = `
            <img src="${photo}" alt="Bukti foto" class="evidence-photo w-full h-48 cursor-pointer" onclick="openPhotoModal('${photo}')">
        `;
        container.appendChild(photoElement);
    });

    document.getElementById('allPhotosModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeAllPhotosModal() {
    document.getElementById('allPhotosModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

// Close modals when clicking outside
document.getElementById('photoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePhotoModal();
    }
});

document.getElementById('allPhotosModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAllPhotosModal();
    }
});

// Escape key closes modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhotoModal();
        closeAllPhotosModal();
    }
});
</script>
@endpush
