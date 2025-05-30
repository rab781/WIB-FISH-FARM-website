@extends('layouts.app')

@section('title', 'Ulasan Saya')

@push('styles')
<style>
    .status-badge {
        @apply px-3 py-1 rounded-full text-sm font-medium;
    }
    .status-published { @apply bg-green-100 text-green-800; }
    .status-pending { @apply bg-yellow-100 text-yellow-800; }
    .status-hidden { @apply bg-gray-100 text-gray-800; }

    .review-card {
        @apply bg-white rounded-lg shadow hover:shadow-md transition-all border border-gray-200 p-6;
    }

    .review-card:hover {
        transform: translateY(-2px);
    }

    .star-rating {
        display: flex;
        gap: 2px;
    }

    .star-rating .star {
        color: #d1d5db;
        font-size: 1.25rem;
        cursor: pointer;
        transition: color 0.2s;
    }

    .star-rating .star.active {
        color: #fbbf24;
    }

    .review-photos {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 8px;
        max-width: 400px;
    }

    .review-photo {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .review-photo:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Ulasan Saya</h1>
        <p class="text-gray-600">Kelola ulasan dan rating produk yang telah Anda berikan</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-star text-2xl"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-orange-100">Total Ulasan</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['published'] ?? 0 }}</div>
                    <div class="text-gray-600">Dipublikasi</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</div>
                    <div class="text-gray-600">Menunggu Review</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['average_rating'] ?? 0, 1) }}</div>
                    <div class="text-gray-600">Rating Rata-rata</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-64">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Ulasan</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nama produk atau isi ulasan..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Dipublikasi</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Review</option>
                    <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Disembunyikan</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                <select name="rating" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Semua Rating</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Bintang</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Bintang</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Bintang</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Bintang</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Bintang</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('reviews.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Reviews List -->
    @if($reviews->count() > 0)
    <div class="space-y-6">
        @foreach($reviews as $review)
        <div class="review-card">
            <div class="flex flex-wrap justify-between items-start mb-4">
                <div class="flex items-center space-x-4">
                    <!-- Product Image -->
                    @if($review->pesanan->produk && $review->pesanan->produk->gambar)
                    <img src="{{ asset('storage/' . $review->pesanan->produk->gambar) }}"
                         alt="{{ $review->pesanan->produk->nama }}"
                         class="w-16 h-16 object-cover rounded-lg">
                    @endif

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $review->pesanan->produk->nama ?? 'Produk' }}</h3>
                        <p class="text-gray-600">Pesanan: {{ $review->pesanan->nomor_pesanan }}</p>
                        <div class="flex items-center space-x-2 mt-1">
                            <div class="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star star {{ $i <= $review->rating ? 'active' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600">({{ $review->rating }}/5)</span>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <span class="status-badge status-{{ $review->status }}">
                        @switch($review->status)
                            @case('published') Dipublikasi @break
                            @case('pending') Menunggu Review @break
                            @case('hidden') Disembunyikan @break
                            @default {{ ucfirst($review->status) }}
                        @endswitch
                    </span>
                    @if($review->is_verified)
                        <div class="text-sm text-green-600 mt-1">
                            <i class="fas fa-check-circle mr-1"></i>Terverifikasi
                        </div>
                    @endif
                    <div class="text-sm text-gray-500 mt-1">{{ $review->created_at->format('d/m/Y') }}</div>
                </div>
            </div>

            <!-- Review Content -->
            <div class="mb-4">
                <p class="text-gray-800 leading-relaxed">{{ $review->review }}</p>
            </div>

            <!-- Review Photos -->
            @if($review->photos && count($review->photos) > 0)
            <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Foto Ulasan:</h4>
                <div class="review-photos">
                    @foreach($review->photos as $photo)
                    <img src="{{ asset('storage/' . $photo) }}"
                         alt="Foto ulasan"
                         class="review-photo"
                         onclick="openPhotoModal('{{ asset('storage/' . $photo) }}')">
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Admin Reply -->
            @if($review->admin_reply)
            <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-tie text-white text-sm"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="font-medium text-blue-800">Balasan Admin</span>
                            <span class="text-sm text-blue-600">{{ $review->admin_reply_at ? $review->admin_reply_at->format('d/m/Y H:i') : '' }}</span>
                        </div>
                        <p class="text-blue-800">{{ $review->admin_reply }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Interaction Stats -->
            @if($review->interactions->count() > 0)
            <div class="mt-4 flex items-center space-x-4 text-sm text-gray-600">
                <span class="flex items-center">
                    <i class="fas fa-thumbs-up mr-1 text-green-500"></i>
                    {{ $review->interactions->where('type', 'helpful')->count() }} Helpful
                </span>
                <span class="flex items-center">
                    <i class="fas fa-thumbs-down mr-1 text-red-500"></i>
                    {{ $review->interactions->where('type', 'not_helpful')->count() }} Not Helpful
                </span>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                    Dibuat {{ $review->created_at->diffForHumans() }}
                </div>
                <div class="space-x-2">
                    <a href="{{ route('reviews.show', $review) }}"
                       class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 text-sm">
                        <i class="fas fa-eye mr-2"></i>Detail
                    </a>
                    @if($review->status === 'published')
                    <a href="{{ route('public.reviews.show', $review) }}" target="_blank"
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                        <i class="fas fa-external-link-alt mr-2"></i>Lihat Publik
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($reviews->hasPages())
    <div class="mt-8">
        {{ $reviews->appends(request()->all())->links() }}
    </div>
    @endif

    @else
    <!-- Empty State -->
    <div class="text-center py-12">
        <div class="text-gray-400 text-6xl mb-4">
            <i class="fas fa-star"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Ulasan</h3>
        <p class="text-gray-600 mb-6">Anda belum memberikan ulasan untuk produk apapun.</p>
        <a href="{{ route('pesanan.index') }}"
           class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 inline-flex items-center">
            <i class="fas fa-shopping-bag mr-2"></i>
            Lihat Pesanan untuk Direview
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
        <div class="inline-block align-middle bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-4xl w-full">
            <div class="bg-white">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Foto Ulasan</h3>
                    <button onclick="closePhotoModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-4">
                    <img id="modalPhoto" src="" alt="Foto ulasan" class="w-full h-auto max-h-96 object-contain">
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
