@extends('admin.layouts.app')

@section('title', 'Detail Ulasan')

@push('styles')
<style>
    .rating-stars {
        color: #fbbf24;
    }
    .status-badge {
        @apply px-3 py-1 rounded-full text-sm font-medium;
    }
    .status-approved { @apply bg-green-100 text-green-800; }
    .status-rejected { @apply bg-red-100 text-red-800; }
    .status-pending { @apply bg-yellow-100 text-yellow-800; }

    .review-photos img {
        @apply w-24 h-24 object-cover rounded-lg border cursor-pointer hover:opacity-80 transition;
    }

    .interaction-btn {
        @apply px-3 py-1 rounded-md border transition-colors text-sm font-medium;
    }
    .interaction-btn.active {
        @apply bg-orange-500 text-white border-orange-500;
    }
    .interaction-btn:not(.active) {
        @apply bg-white text-gray-700 border-gray-300 hover:bg-gray-50;
    }

    .admin-reply-section {
        @apply bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r-lg;
    }
</style>
@endpush

@section('header')
<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900">Detail Ulasan</h1>
    <a href="{{ route('admin.reviews.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali
    </a>
</div>
@endsection

@section('content')
<div class="p-6 space-y-6">
    <!-- Review Details Card -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <div class="p-6">
            <!-- Review Header -->
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center space-x-4">
                    <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                        @if($review->user->foto)
                            <img src="{{ asset('storage/uploads/users/' . $review->user->foto) }}"
                                 alt="{{ $review->user->name }}"
                                 class="h-12 w-12 rounded-full object-cover">
                        @else
                            <span class="text-lg font-medium text-gray-600">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $review->user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $review->created_at->format('d M Y, H:i') }}</p>
                        @if($review->is_verified_purchase)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                <i class="fas fa-check-circle mr-1"></i>
                                Pembelian Terverifikasi
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Status Badge -->
                    <span class="status-badge status-{{ $review->status_review }}">
                        {{ ucfirst($review->status_review) }}
                    </span>

                    <!-- Actions Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded-lg">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10 border">
                            <div class="py-1">
                                @if($review->status_review === 'pending')
                                    <form action="{{ route('admin.reviews.updateStatus', $review) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status_review" value="approved">
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-50">
                                            <i class="fas fa-check mr-2"></i>Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reviews.updateStatus', $review) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status_review" value="rejected">
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                            <i class="fas fa-times mr-2"></i>Tolak
                                        </button>
                                    </form>
                                @endif
                                @if($review->status_review === 'approved')
                                    <form action="{{ route('admin.reviews.updateStatus', $review) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status_review" value="pending">
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-yellow-700 hover:bg-yellow-50">
                                            <i class="fas fa-clock mr-2"></i>Ubah ke Pending
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4">
                    @if($review->produk->gambar)
                        <img src="{{ asset('storage/' . $review->produk->gambar) }}"
                             alt="{{ $review->produk->nama_ikan }}"
                             class="h-16 w-16 rounded-lg object-cover">
                    @endif
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900">{{ $review->produk->nama_ikan }}</h4>
                        <p class="text-sm text-gray-600">{{ $review->produk->jenis_ikan }}</p>
                        <p class="text-sm font-medium text-orange-600">Rp {{ number_format($review->produk->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Rating -->
            <div class="mb-4">
                <div class="flex items-center space-x-2">
                    <div class="flex rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-lg font-semibold text-gray-900">{{ $review->rating }}/5</span>
                </div>
            </div>

            <!-- Review Content -->
            <div class="mb-6">
                <h4 class="text-md font-semibold text-gray-900 mb-2">Ulasan:</h4>
                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed">{{ $review->komentar }}</p>
                </div>
            </div>

            <!-- Review Photos -->
            @if($review->hasPhotos())
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-900 mb-3">Foto Ulasan:</h4>
                    <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3 review-photos">
                        @foreach($review->photo_urls as $photo)
                            <img src="{{ $photo }}" alt="Review photo" onclick="openImageModal('{{ $photo }}')">
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Interactions Stats -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-md font-semibold text-gray-900 mb-3">Statistik Interaksi:</h4>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-green-600">{{ $review->helpful_count }}</div>
                        <div class="text-sm text-gray-600">Membantu</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-red-600">{{ $review->not_helpful_count }}</div>
                        <div class="text-sm text-gray-600">Tidak Membantu</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-blue-600">{{ $review->interactions->count() }}</div>
                        <div class="text-sm text-gray-600">Total Interaksi</div>
                    </div>
                </div>
            </div>

            <!-- Admin Reply Section -->
            @if($review->hasAdminReply())
                <div class="admin-reply-section mb-6">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-orange-500 flex items-center justify-center">
                                <i class="fas fa-user-shield text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <h5 class="font-semibold text-gray-900">Balasan Admin</h5>
                                @if($review->adminReplier)
                                    <span class="text-sm text-gray-600">oleh {{ $review->adminReplier->name }}</span>
                                @endif
                                <span class="text-sm text-gray-500">{{ $review->tanggal_balasan->format('d M Y, H:i') }}</span>
                            </div>
                            <p class="text-gray-700">{{ $review->balasan_admin }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Add Admin Reply Form -->
            @if(!$review->hasAdminReply())
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-md font-semibold text-gray-900 mb-3">Tambah Balasan Admin:</h4>
                    <form action="{{ route('admin.reviews.addAdminReply', $review) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <textarea name="balasan_admin" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                      placeholder="Tulis balasan admin untuk ulasan ini..."
                                      required>{{ old('balasan_admin') }}</textarea>
                            @error('balasan_admin')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-reply mr-2"></i>Kirim Balasan
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Interactions -->
    @if($review->interactions->isNotEmpty())
        <div class="bg-white rounded-lg shadow-md border border-gray-200">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Interaksi Terbaru</h3>
                <div class="space-y-4">
                    @foreach($review->interactions->take(10) as $interaction)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                            <div class="flex items-center space-x-3">
                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-600">
                                        {{ strtoupper(substr($interaction->user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $interaction->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $interaction->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($interaction->interaction_type === 'helpful')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                        <i class="fas fa-thumbs-up mr-1"></i>Membantu
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">
                                        <i class="fas fa-thumbs-down mr-1"></i>Tidak Membantu
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="max-w-4xl max-h-screen">
            <img id="modalImage" src="" alt="Full size image" class="max-w-full max-h-full object-contain rounded-lg">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function openImageModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Close modal when clicking outside the image
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
</script>
@endpush
