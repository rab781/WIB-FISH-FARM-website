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
        @apply bg-white rounded-lg shadow border border-gray-200 p-4 hover:shadow-md transition-shadow cursor-pointer;
    }

    .review-card:hover {
        @apply transform scale-[1.01];
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
                    <select name="rating" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Semua Rating</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Bintang</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Bintang</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Bintang</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Bintang</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Bintang</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Balasan</label>
                    <select name="replied" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
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
    <div class="space-y-4">
        @forelse($reviews as $review)
        <div class="review-card" onclick="openReviewDetailModal({{ $review->id_ulasan }})">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $review->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600">({{ $review->rating }}/5)</span>
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
                    <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            @if($review->detailPesanan && $review->detailPesanan->produk && $review->detailPesanan->produk->gambar)
                                @if(Str::startsWith($review->detailPesanan->produk->gambar, 'uploads/'))
                                    <img src="{{ asset($review->detailPesanan->produk->gambar) }}" alt="{{ $review->detailPesanan->produk->nama_ikan }}" class="w-12 h-12 object-cover rounded-lg">
                                @elseif(Str::startsWith($review->detailPesanan->produk->gambar, 'storage/'))
                                    <img src="{{ asset($review->detailPesanan->produk->gambar) }}" alt="{{ $review->detailPesanan->produk->nama_ikan }}" class="w-12 h-12 object-cover rounded-lg">
                                @else
                                    <img src="{{ asset('storage/' . $review->detailPesanan->produk->gambar) }}" alt="{{ $review->detailPesanan->produk->nama_ikan }}" class="w-12 h-12 object-cover rounded-lg">
                                @endif
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-900">{{ $review->detailPesanan && $review->detailPesanan->produk ? $review->detailPesanan->produk->nama_ikan : 'Produk tidak tersedia' }}</div>
                                <div class="text-sm text-gray-500">Pesanan: #{{ $review->pesanan ? $review->pesanan->id_pesanan : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Content -->
                    <div class="mb-3">
                        <p class="text-gray-800">{{ $review->komentar }}</p>
                    </div>

                    <!-- Review Photos -->
                    @php
                        $hasValidPhotos = false;
                        $validPhotos = [];

                        if ($review->foto_review) {
                            // Parse foto_review data
                            $photos = is_string($review->foto_review) ? json_decode($review->foto_review, true) : $review->foto_review;

                            // Handle single photo case
                            if (!is_array($photos)) {
                                $photos = [$review->foto_review];
                            }

                            // Filter out empty, null, or invalid photos with more comprehensive checks
                            $validPhotos = array_filter($photos, function($photo) {
                                if (empty($photo) || $photo === null || $photo === '' || $photo === 'null') {
                                    return false;
                                }

                                $trimmedPhoto = trim($photo);
                                if ($trimmedPhoto === '' || $trimmedPhoto === 'null') {
                                    return false;
                                }

                                // Check for common placeholder values
                                $invalidValues = ['[]', '{}', 'null', 'undefined', 'false', '0'];
                                if (in_array(strtolower($trimmedPhoto), $invalidValues)) {
                                    return false;
                                }

                                // Check if file exists (basic validation)
                                $filePath = storage_path('app/public/' . $trimmedPhoto);
                                if (!file_exists($filePath)) {
                                    return false;
                                }

                                return true;
                            });

                            $hasValidPhotos = !empty($validPhotos) && count($validPhotos) > 0;
                        }
                    @endphp

                    @if($hasValidPhotos)
                    <div class="mb-3">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Foto Ulasan ({{ count($validPhotos) }}):</h4>
                        <div class="flex space-x-2">
                            @foreach(array_slice($validPhotos, 0, 4) as $index => $photo)
                            <img src="{{ asset('storage/' . $photo) }}"
                                 alt="Review photo"
                                 class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-80"
                                 onclick="event.stopPropagation(); openPhotoGallery({{ $review->id_ulasan }}, {{ $index }})">
                            @endforeach
                            @if(count($validPhotos) > 4)
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-600 text-sm cursor-pointer hover:bg-gray-300"
                                 onclick="event.stopPropagation(); openPhotoGallery({{ $review->id_ulasan }}, 0)">
                                +{{ count($validPhotos) - 4 }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Admin Reply -->
                    @if($review->balasan_admin)
                    <div class="mt-4 p-3 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
                        <div class="flex items-center space-x-2 mb-2">
                            <i class="fas fa-reply text-blue-600"></i>
                            <span class="font-medium text-blue-900">Balasan Admin</span>
                            <span class="text-sm text-blue-600">{{ $review->balasan_admin_at ? $review->balasan_admin_at->format('d/m/Y H:i') : '' }}</span>
                        </div>
                        <p class="text-blue-800">{{ $review->balasan_admin }}</p>
                    </div>
                    @endif

                    <!-- Interactions -->
                    @if($review->interactions->count() > 0)
                    <div class="mt-3 flex items-center space-x-4 text-sm text-gray-600">
                        <span><i class="fas fa-thumbs-up mr-1"></i>{{ $review->interactions->where('interaction_type', 'helpful')->count() }} Helpful</span>
                        <span><i class="fas fa-thumbs-down mr-1"></i>{{ $review->interactions->where('interaction_type', 'not_helpful')->count() }} Not Helpful</span>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="ml-4 flex flex-col space-y-2" onclick="event.stopPropagation()">
                    @if(!$review->balasan_admin)
                    <button onclick="openReplyModalSwal({{ $review->id_ulasan }})"
                            class="text-blue-600 hover:text-blue-900 text-sm flex items-center">
                        <i class="fas fa-reply mr-1"></i>Balas
                    </button>
                    @else
                    <button onclick="editReplyModalSwal({{ $review->id_ulasan }}, '{{ addslashes($review->balasan_admin) }}')"
                            class="text-green-600 hover:text-green-900 text-sm flex items-center">
                        <i class="fas fa-edit mr-1"></i>Edit Balasan
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-star text-4xl mb-4"></i>
            <p>Tidak ada ulasan ditemukan.</p>
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
        } else {
            throw new Error(data.message || 'Gagal memuat galeri foto');
        }
    })
        } else {
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
