@extends('admin.layouts.app')

@section('title', 'Manajemen Ulasan')

@push('styles')
<style>
    .rating-stars {
        color: #fbbf24;
    }
    .status-badge {
        @apply px-3 py-1 rounded-full text-sm font-medium;
    }
    .status-published { @apply bg-green-100 text-green-800; }
    .status-hidden { @apply bg-red-100 text-red-800; }
    .status-pending { @apply bg-yellow-100 text-yellow-800; }
    .status-verified { @apply bg-blue-100 text-blue-800; }

    .review-card {
        @apply bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 cursor-pointer relative overflow-hidden;
        backdrop-filter: blur(10px);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.98) 100%);
        margin-bottom: 1.5rem;
        border-left: 4px solid transparent;
        padding: 1.5rem;
    }

    .review-card:hover {
        @apply transform scale-[1.02] border-orange-200;
        border-left-color: #f97316;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 0 0 1px rgba(249, 115, 22, 0.1);
    }

    .review-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #f97316, #ea580c, #dc2626);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .review-card:hover::before {
        opacity: 1;
    }

    .review-separator {
        @apply border-b border-gray-100 pb-6 mb-6 last:border-b-0 last:pb-0 last:mb-0;
    }

    .customer-avatar {
        @apply w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg;
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
    }

    .rating-display {
        @apply flex items-center space-x-1;
    }

    .rating-display .star {
        @apply text-lg transition-all duration-200;
    }

    .rating-display .star.filled {
        @apply text-yellow-400;
        filter: drop-shadow(0 1px 2px rgba(251, 191, 36, 0.3));
    }

    .rating-display .star.empty {
        @apply text-gray-300;
    }

    .product-info-card {
        @apply bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-150;
        backdrop-filter: blur(5px);
    }

    .product-image {
        @apply w-14 h-14 object-cover rounded-xl shadow-md;
        border: 2px solid white;
    }

    .review-content {
        @apply text-gray-800 leading-relaxed text-base;
        line-height: 1.7;
    }

    .photo-thumbnail {
        @apply w-20 h-20 object-cover rounded-xl cursor-pointer border-2 border-white shadow-lg;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .photo-thumbnail:hover {
        @apply transform scale-110 border-orange-300;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        z-index: 10;
    }

    .photo-grid {
        @apply flex flex-wrap gap-3;
    }

    .photo-count-badge {
        @apply w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl flex items-center justify-center text-gray-600 text-sm font-semibold cursor-pointer;
        transition: all 0.3s ease;
    }

    .photo-count-badge:hover {
        @apply bg-gradient-to-br from-orange-200 to-orange-300 text-orange-700 transform scale-105;
    }

    .admin-reply-card {
        @apply bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-400 rounded-xl p-4;
        backdrop-filter: blur(5px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
    }

    .interactions-display {
        @apply flex items-center space-x-6 text-sm font-medium;
    }

    .interaction-item {
        @apply flex items-center space-x-2 px-3 py-2 rounded-lg;
        transition: all 0.2s ease;
    }

    .interaction-item.helpful {
        @apply bg-green-50 text-green-700 hover:bg-green-100;
    }

    .interaction-item.not-helpful {
        @apply bg-red-50 text-red-700 hover:bg-red-100;
    }

    .action-buttons {
        @apply flex flex-col space-y-3;
    }

    .action-btn {
        @apply flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200;
    }

    .action-btn.reply {
        @apply bg-blue-50 text-blue-700 hover:bg-blue-100 hover:text-blue-800;
    }

    .action-btn.edit {
        @apply bg-green-50 text-green-700 hover:bg-green-100 hover:text-green-800;
    }

    .action-btn:hover {
        @apply transform translateY(-1px) shadow-md;
    }

    .empty-state {
        @apply text-center py-12 px-6;
    }

    .empty-state-icon {
        @apply text-6xl text-gray-300 mb-4;
    }

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
    }

    /* Enhanced animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .review-card {
        animation: fadeInUp 0.5s ease-out;
    }

    .review-card:nth-child(1) { animation-delay: 0.1s; }
    .review-card:nth-child(2) { animation-delay: 0.2s; }
    .review-card:nth-child(3) { animation-delay: 0.3s; }
    .review-card:nth-child(4) { animation-delay: 0.4s; }
    .review-card:nth-child(5) { animation-delay: 0.5s; }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .review-card {
            @apply p-4 mx-2;
        }

        .customer-avatar {
            @apply w-10 h-10 text-base;
        }

        .photo-thumbnail, .photo-count-badge {
            @apply w-16 h-16;
        }
    }
</style>
@endpush

@section('header', 'Manajemen Ulasan')

@section('content')
<div class="p-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-star"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                    <p class="text-gray-600">Total Ulasan</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['avg_rating'] ?? 0, 1) }}</p>
                    <p class="text-gray-600">Rating Rata-rata</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-64">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Ulasan</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nama pelanggan, produk, atau isi ulasan..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="relative">
                        <select name="rating" class="border border-gray-300 rounded-lg px-3 py-2 pr-10 focus:ring-orange-500 focus:border-orange-500 appearance-none bg-white w-full">
                            <option value="">Semua Rating</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Bintang</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Bintang</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Bintang</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Bintang</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Bintang</option>
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Balasan</label>
                    <select name="replied" class="border border-gray-300 rounded-lg px-3 py-2 pr-10 focus:ring-orange-500 focus:border-orange-500 appearance-none bg-white">
                        <option value="">Semua</option>
                        <option value="yes" {{ request('replied') == 'yes' ? 'selected' : '' }}>Sudah Dibalas</option>
                        <option value="no" {{ request('replied') == 'no' ? 'selected' : '' }}>Belum Dibalas</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.reviews.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="space-y-6">
        @forelse($reviews as $review)
        <div class="review-card" onclick="openReviewDetailModal({{ $review->id_ulasan }})">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-3">
                                <div class="customer-avatar">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900 text-lg">{{ $review->user->name }}</div>
                                    <div class="text-sm text-gray-500 flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $review->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                <div class="rating-display">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star star {{ $i <= $review->rating ? 'filled' : 'empty' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-sm font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded-full">({{ $review->rating }}/5)</span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            @if($review->is_verified)
                                <span class="status-badge status-verified">
                                    <i class="fas fa-check-circle mr-1"></i>Terverifikasi
                                </span>
                            @endif
                            <span class="status-badge status-{{ $review->status }}">
                                {{ ucfirst($review->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="mb-4">
                        <div class="product-info-card">
                            <div class="flex items-center space-x-4">
                                @if($review->detailPesanan && $review->detailPesanan->produk && $review->detailPesanan->produk->gambar)
                                    @if(Str::startsWith($review->detailPesanan->produk->gambar, 'uploads/'))
                                        <img src="{{ asset($review->detailPesanan->produk->gambar) }}" alt="{{ $review->detailPesanan->produk->nama_ikan }}" class="product-image">
                                    @elseif(Str::startsWith($review->detailPesanan->produk->gambar, 'storage/'))
                                        <img src="{{ asset($review->detailPesanan->produk->gambar) }}" alt="{{ $review->detailPesanan->produk->nama_ikan }}" class="product-image">
                                    @else
                                        <img src="{{ asset('storage/' . $review->detailPesanan->produk->gambar) }}" alt="{{ $review->detailPesanan->produk->nama_ikan }}" class="product-image">
                                    @endif
                                @else
                                    <div class="w-14 h-14 bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <div class="font-semibold text-gray-900 text-lg">{{ $review->detailPesanan && $review->detailPesanan->produk ? $review->detailPesanan->produk->nama_ikan : 'Produk tidak tersedia' }}</div>
                                    <div class="text-sm text-gray-500 flex items-center mt-1">
                                        <i class="fas fa-receipt mr-1"></i>
                                        Pesanan: #{{ $review->pesanan ? $review->pesanan->id_pesanan : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Content -->
                    <div class="mb-4">
                        <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-comment-alt mr-2 text-orange-500"></i>
                            Ulasan Pelanggan
                        </h4>
                        <p class="review-content">{{ $review->komentar }}</p>
                    </div>

                    <!-- Review Photos -->
                    @if($review->hasPhotos())
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-700 mb-3 flex items-center">
                                <i class="fas fa-images mr-2 text-purple-500"></i>
                                Foto Ulasan ({{ count($review->photos) }})
                            </h4>
                            <div class="photo-grid">
                                @foreach(array_slice($review->photo_urls, 0, 4) as $index => $photoUrl)
                                <img src="{{ $photoUrl }}"
                                     alt="Review photo {{ $index + 1 }}"
                                     class="photo-thumbnail"
                                     onclick="event.stopPropagation(); openPhotoGallery({{ $review->id_ulasan }}, {{ $index }})">
                                @endforeach
                                @if(count($review->photos) > 4)
                                <div class="photo-count-badge"
                                     onclick="event.stopPropagation(); openPhotoGallery({{ $review->id_ulasan }}, 0)">
                                    +{{ count($review->photos) - 4 }}
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Admin Reply -->
                    @if($review->balasan_admin)
                    <div class="mb-4">
                        <div class="admin-reply-card">
                            <div class="flex items-center space-x-2 mb-3">
                                <i class="fas fa-reply text-blue-600"></i>
                                <span class="font-semibold text-blue-900">Balasan Admin</span>
                                <span class="text-sm text-blue-600 bg-blue-100 px-2 py-1 rounded-full">{{ $review->balasan_admin_at ? $review->balasan_admin_at->format('d/m/Y H:i') : '' }}</span>
                            </div>
                            <p class="text-blue-800 leading-relaxed">{{ $review->balasan_admin }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Interactions -->
                    @if($review->interactions->count() > 0)
                    <div class="interactions-display">
                        <div class="interaction-item helpful">
                            <i class="fas fa-thumbs-up"></i>
                            <span>{{ $review->interactions->where('interaction_type', 'helpful')->count() }} Helpful</span>
                        </div>
                        <div class="interaction-item not-helpful">
                            <i class="fas fa-thumbs-down"></i>
                            <span>{{ $review->interactions->where('interaction_type', 'not_helpful')->count() }} Not Helpful</span>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="action-buttons" onclick="event.stopPropagation()">
                    @if(!$review->balasan_admin)
                    <button onclick="openReplyModalSwal({{ $review->id_ulasan }})"
                            class="action-btn reply">
                        <i class="fas fa-reply"></i>
                        <span>Balas</span>
                    </button>
                    @else
                    <button onclick="editReplyModalSwal({{ $review->id_ulasan }}, '{{ addslashes($review->balasan_admin) }}')"
                            class="action-btn edit">
                        <i class="fas fa-edit"></i>
                        <span>Edit Balasan</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-star empty-state-icon"></i>
            <h3 class="text-xl font-medium text-gray-600 mb-2">Tidak ada ulasan ditemukan</h3>
            <p class="text-gray-500">Belum ada ulasan dari pelanggan yang sesuai dengan filter yang dipilih.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($reviews->hasPages())
    <div class="mt-6">
        {{ $reviews->appends(request()->all())->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Enhanced Review Detail Modal
function openReviewDetailModal(reviewId) {
    // Show loading
    Swal.fire({
        title: 'Memuat Detail Ulasan...',
        html: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Fetch review details
    fetch(`/admin/reviews/${reviewId}/detail`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Review detail response:', data);
        if (data.success) {
            showReviewDetailModal(data.review);
        } else {
            throw new Error(data.message || 'Gagal memuat detail ulasan');
        }
    })
    .catch(error => {
        console.error('Review detail error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan saat memuat detail ulasan: ' + error.message,
            icon: 'error',
            confirmButtonColor: '#ea580c'
        });
    });
}

function showReviewDetailModal(review) {
    // Build rating stars
    const stars = Array.from({length: 5}, (_, i) => {
        return `<i class="fas fa-star ${i < review.rating ? 'text-yellow-400' : 'text-gray-300'}"></i>`;
    }).join('');

    // Build status badge
    const statusColors = {
        'published': 'bg-green-100 text-green-800',
        'hidden': 'bg-red-100 text-red-800',
        'pending': 'bg-yellow-100 text-yellow-800',
        'verified': 'bg-blue-100 text-blue-800'
    };
    const statusClass = statusColors[review.status] || 'bg-gray-100 text-gray-800';

    // Build photos section
    let photosHtml = '';
    if (review.photos && review.photos.length > 0) {
        photosHtml = `
            <div class="mt-4">
                <h4 class="font-medium text-gray-900 mb-2">Foto Ulasan</h4>
                <div class="grid grid-cols-4 gap-2">
                    ${review.photos.slice(0, 8).map((photo, index) => `
                        <img src="${photo}" alt="Review photo ${index + 1}"
                             class="w-full h-20 object-cover rounded-lg cursor-pointer hover:opacity-80"
                             onclick="openPhotoGalleryFromModal(${review.id_ulasan}, ${index})">
                    `).join('')}
                    ${review.photos.length > 8 ? `
                        <div class="w-full h-20 bg-gray-200 rounded-lg flex items-center justify-center text-gray-600 text-sm cursor-pointer hover:bg-gray-300"
                             onclick="openPhotoGalleryFromModal(${review.id_ulasan}, 0)">
                            +${review.photos.length - 8}
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
    }

    // Build admin reply section
    let adminReplyHtml = '';
    if (review.admin_reply) {
        adminReplyHtml = `
            <div class="mt-4 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-reply text-blue-600"></i>
                        <span class="font-medium text-blue-900">Balasan Admin</span>
                        ${review.admin_replier ? `<span class="text-sm text-blue-600">oleh ${review.admin_replier.name}</span>` : ''}
                    </div>
                    <span class="text-sm text-blue-600">${review.admin_reply_date || ''}</span>
                </div>
                <p class="text-blue-800">${review.admin_reply}</p>
            </div>
        `;
    }

    // Build interactions section
    let interactionsHtml = '';
    if (review.interactions && (review.interactions.helpful > 0 || review.interactions.not_helpful > 0)) {
        interactionsHtml = `
            <div class="mt-4 flex items-center space-x-4 text-sm text-gray-600">
                <span><i class="fas fa-thumbs-up mr-1 text-green-600"></i>${review.interactions.helpful} Helpful</span>
                <span><i class="fas fa-thumbs-down mr-1 text-red-600"></i>${review.interactions.not_helpful} Not Helpful</span>
            </div>
        `;
    }

    const modalHtml = `
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900">Detail Ulasan</h3>
                        <span class="px-3 py-1 rounded-full text-sm font-medium ${statusClass}">
                            ${review.status_label || review.status}
                        </span>
                    </div>
                </div>

                <div class="p-6 max-h-96 overflow-y-auto">
                    <!-- Customer Info -->
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-gray-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900">${review.customer_name}</h4>
                            <div class="flex items-center space-x-2">
                                <div class="flex space-x-1">${stars}</div>
                                <span class="text-sm text-gray-500">${review.created_at}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-4">
                            ${review.product_image ? `
                                <img src="${review.product_image}" alt="${review.product_name}"
                                     class="w-16 h-16 object-cover rounded-lg">
                            ` : `
                                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-fish text-gray-400"></i>
                                </div>
                            `}
                            <div>
                                <h5 class="font-medium text-gray-900">${review.product_name}</h5>
                                <p class="text-sm text-gray-500">Pesanan: #${review.order_id}</p>
                                <p class="text-sm text-gray-500">Tanggal: ${review.order_date}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Review Content -->
                    <div class="mb-4">
                        <h4 class="font-medium text-gray-900 mb-2">Ulasan</h4>
                        <p class="text-gray-800 leading-relaxed">${review.comment}</p>
                    </div>

                    ${photosHtml}
                    ${adminReplyHtml}
                    ${interactionsHtml}
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                    ${!review.admin_reply ? `
                        <button onclick="closeReviewDetailAndOpenReply(${review.id_ulasan})"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-reply mr-2"></i>Balas Ulasan
                        </button>
                    ` : `
                        <button onclick="closeReviewDetailAndEditReply(${review.id_ulasan}, '${review.admin_reply.replace(/'/g, "\\'")}')"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500">
                            <i class="fas fa-edit mr-2"></i>Edit Balasan
                        </button>
                    `}
                    <button onclick="Swal.close()"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    `;

    Swal.fire({
        html: modalHtml,
        width: '90%',
        showConfirmButton: false,
        customClass: {
            popup: 'p-0',
            htmlContainer: 'p-0'
        }
    });
}

// Photo Gallery Modal
let currentPhotoGallery = [];
let currentPhotoIndex = 0;


function openPhotoGallery(reviewId, startIndex = 0) {
    // Show loading
    Swal.fire({
        title: 'Memuat Galeri Foto...',
        html: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Fetch photos
    fetch(`/admin/reviews/${reviewId}/photos`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Photo gallery response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Photo gallery response:', data);
        if (data.success) {
            if (data.photos && data.photos.length > 0) {
                currentPhotoGallery = data.photos;
                currentPhotoIndex = startIndex;
                showPhotoGalleryModal();
            } else {
                Swal.fire({
                    title: 'Tidak Ada Foto',
                    text: 'Ulasan ini tidak memiliki foto yang valid',
                    icon: 'info',
                    confirmButtonColor: '#ea580c'
                });
            }
        } else { // <--- 'else' ini adalah pasangan yang benar dari 'if (data.success)'
            Swal.fire({
                title: 'Error!',
                text: data.message || 'Gagal memuat galeri foto',
                icon: 'error',
                confirmButtonColor: '#ea580c'
            });
        }
    })
    .catch(error => {
        console.error('Photo gallery error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan saat memuat galeri foto: ' + error.message,
            icon: 'error',
            confirmButtonColor: '#ea580c'
        });
    });
}

function openPhotoGalleryFromModal(reviewId, startIndex = 0) {
    // Close current modal first
    Swal.close();
    // Then open photo gallery
    setTimeout(() => openPhotoGallery(reviewId, startIndex), 100);
}

function showPhotoGalleryModal() {
    if (currentPhotoGallery.length === 0) {
        Swal.fire({
            title: 'Tidak Ada Foto',
            text: 'Ulasan ini tidak memiliki foto',
            icon: 'info',
            confirmButtonColor: '#ea580c'
        });
        return;
    }

    const buildThumbnails = () => {
        return currentPhotoGallery.map((photo, index) => `
            <img src="${photo}" alt="Thumbnail ${index + 1}"
                 class="w-16 h-16 object-cover rounded-lg cursor-pointer border-2 ${index === currentPhotoIndex ? 'border-orange-500' : 'border-transparent'} hover:border-orange-300"
                 onclick="changePhoto(${index})">
        `).join('');
    };

    const modalHtml = `
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900">Galeri Foto Ulasan</h3>
                        <span class="text-sm text-gray-500">${currentPhotoIndex + 1} dari ${currentPhotoGallery.length}</span>
                    </div>
                </div>

                <div class="relative">
                    <!-- Main Photo -->
                    <div class="p-6 text-center bg-gray-50">
                        <img id="mainPhoto" src="${currentPhotoGallery[currentPhotoIndex]}" alt="Review photo ${currentPhotoIndex + 1}"
                             class="max-w-full max-h-96 mx-auto object-contain rounded-lg shadow-lg">
                    </div>

                    <!-- Navigation Arrows -->
                    ${currentPhotoGallery.length > 1 ? `
                        <button onclick="previousPhoto()"
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg">
                            <i class="fas fa-chevron-left text-gray-700"></i>
                        </button>
                        <button onclick="nextPhoto()"
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-3 shadow-lg">
                            <i class="fas fa-chevron-right text-gray-700"></i>
                        </button>
                    ` : ''}
                </div>

                <!-- Thumbnails -->
                ${currentPhotoGallery.length > 1 ? `
                    <div class="px-6 py-4 border-t border-gray-200">
                        <div class="flex space-x-2 overflow-x-auto">
                            ${buildThumbnails()}
                        </div>
                    </div>
                ` : ''}

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button onclick="Swal.close()"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    `;

    Swal.fire({
        html: modalHtml,
        width: '90%',
        showConfirmButton: false,
        customClass: {
            popup: 'p-0',
            htmlContainer: 'p-0'
        }
    });
}

function changePhoto(index) {
    currentPhotoIndex = index;
    document.getElementById('mainPhoto').src = currentPhotoGallery[currentPhotoIndex];

    // Update thumbnails
    const thumbnails = document.querySelectorAll('.swal2-html-container img');
    thumbnails.forEach((thumb, i) => {
        if (i < currentPhotoGallery.length) {
            thumb.classList.toggle('border-orange-500', i === currentPhotoIndex);
            thumb.classList.toggle('border-transparent', i !== currentPhotoIndex);
        }
    });

    // Update counter
    const counter = document.querySelector('.swal2-html-container .text-sm.text-gray-500');
    if (counter) {
        counter.textContent = `${currentPhotoIndex + 1} dari ${currentPhotoGallery.length}`;
    }
}

function previousPhoto() {
    currentPhotoIndex = currentPhotoIndex > 0 ? currentPhotoIndex - 1 : currentPhotoGallery.length - 1;
    changePhoto(currentPhotoIndex);
}

function nextPhoto() {
    currentPhotoIndex = currentPhotoIndex < currentPhotoGallery.length - 1 ? currentPhotoIndex + 1 : 0;
    changePhoto(currentPhotoIndex);
}

// Helper functions to close detail modal and open reply modal
function closeReviewDetailAndOpenReply(reviewId) {
    Swal.close();
    setTimeout(() => openReplyModalSwal(reviewId), 100);
}

function closeReviewDetailAndEditReply(reviewId, currentReply) {
    Swal.close();
    setTimeout(() => editReplyModalSwal(reviewId, currentReply), 100);
}

// Keyboard navigation for photo gallery
document.addEventListener('keydown', function(e) {
    if (currentPhotoGallery.length > 0 && Swal.isVisible()) {
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            previousPhoto();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            nextPhoto();
        }
    }
});

// Open reply modal using SweetAlert
function openReplyModalSwal(reviewId) {
    Swal.fire({
        title: 'Balas Ulasan',
        html: `
            <div class="text-left">
                <label class="block text-sm font-medium text-gray-700 mb-2">Balasan Admin</label>
                <textarea id="swal-reply-textarea" rows="4" required
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                          placeholder="Tulis balasan untuk pelanggan..."></textarea>
            </div>
        `,
        width: '600px',
        showCancelButton: true,
        confirmButtonText: 'Kirim Balasan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ea580c',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-xl',
            content: 'text-left'
        },
        preConfirm: () => {
            const reply = document.getElementById('swal-reply-textarea').value;
            if (!reply.trim()) {
                Swal.showValidationMessage('Balasan tidak boleh kosong');
                return false;
            }
            return reply;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            submitReply(reviewId, result.value);
        }
    });
}

// Edit reply modal using SweetAlert
function editReplyModalSwal(reviewId, currentReply) {
    Swal.fire({
        title: 'Edit Balasan',
        html: `
            <div class="text-left">
                <label class="block text-sm font-medium text-gray-700 mb-2">Balasan Admin</label>
                <textarea id="swal-reply-textarea" rows="4" required
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                          placeholder="Tulis balasan untuk pelanggan...">${currentReply}</textarea>
            </div>
        `,
        width: '600px',
        showCancelButton: true,
        confirmButtonText: 'Update Balasan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ea580c',
        cancelButtonColor: '#6b7280',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-xl',
            content: 'text-left'
        },
        preConfirm: () => {
            const reply = document.getElementById('swal-reply-textarea').value;
            if (!reply.trim()) {
                Swal.showValidationMessage('Balasan tidak boleh kosong');
                return false;
            }
            return reply;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            submitReply(reviewId, result.value);
        }
    });
}

// Submit reply function
function submitReply(reviewId, replyText) {
    // Show loading
    Swal.fire({
        title: 'Mengirim Balasan...',
        html: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('balasan_admin', replyText);

    fetch(`/admin/reviews/${reviewId}/reply`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Balasan berhasil dikirim',
                icon: 'success',
                confirmButtonColor: '#ea580c'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: data.message || 'Gagal mengirim balasan',
                icon: 'error',
                confirmButtonColor: '#ea580c'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan saat mengirim balasan',
            icon: 'error',
            confirmButtonColor: '#ea580c'
        });
    });
}

// Photo modal functions (backward compatibility)
function openPhotoModal(photoUrl) {
    // Use new photo gallery system with single photo
    currentPhotoGallery = [photoUrl];
    currentPhotoIndex = 0;
    showPhotoGalleryModal();
}

function closePhotoModal() {
    Swal.close();
}

// Close photo modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && Swal.isVisible()) {
        Swal.close();
    }
});
</script>
@endpush
