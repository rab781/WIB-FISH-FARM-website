@extends('layouts.app')

@section('title', 'Review Produk')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-4 md:mb-0">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Review Produk</h1>
                <p class="text-gray-600">Lihat review dan rating dari pelanggan kami</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center bg-orange-50 border border-orange-200 rounded-lg px-4 py-2">
                    <span class="text-orange-600 font-medium mr-2">{{ $averageRating ?? 0 }}/5</span>
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= ($averageRating ?? 0) ? 'text-orange-400' : 'text-gray-300' }}"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-sm text-gray-500 ml-2">({{ $totalReviews ?? 0 }} review)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Review</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalReviews ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rating Rata-rata</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($averageRating ?? 0, 1) }}/5</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Review dengan Foto</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $reviewsWithPhotos ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Review Terbaru</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $recentReviews ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Review</label>
                <div class="relative">
                    <input type="text"
                           id="search"
                           placeholder="Cari berdasarkan produk atau review..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Rating Filter -->
            <div>
                <label for="ratingFilter" class="block text-sm font-medium text-gray-700 mb-2">Filter Rating</label>
                <select id="ratingFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">Semua Rating</option>
                    <option value="5">5 Bintang</option>
                    <option value="4">4 Bintang</option>
                    <option value="3">3 Bintang</option>
                    <option value="2">2 Bintang</option>
                    <option value="1">1 Bintang</option>
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label for="sortBy" class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                <select id="sortBy" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="highest_rating">Rating Tertinggi</option>
                    <option value="lowest_rating">Rating Terendah</option>
                    <option value="most_helpful">Paling Membantu</option>
                </select>
            </div>
        </div>

        <!-- Quick Filters -->
        <div class="mt-4 pt-4 border-t border-gray-200">
            <p class="text-sm font-medium text-gray-700 mb-3">Filter Cepat:</p>
            <div class="flex flex-wrap gap-2">
                <button onclick="applyQuickFilter('with_photos')"
                        class="px-3 py-1 text-sm border border-gray-300 rounded-full hover:bg-orange-50 hover:border-orange-300 hover:text-orange-600 transition-colors duration-200">
                    📸 Dengan Foto
                </button>
                <button onclick="applyQuickFilter('recent')"
                        class="px-3 py-1 text-sm border border-gray-300 rounded-full hover:bg-orange-50 hover:border-orange-300 hover:text-orange-600 transition-colors duration-200">
                    🕒 7 Hari Terakhir
                </button>
                <button onclick="applyQuickFilter('high_rating')"
                        class="px-3 py-1 text-sm border border-gray-300 rounded-full hover:bg-orange-50 hover:border-orange-300 hover:text-orange-600 transition-colors duration-200">
                    ⭐ Rating Tinggi (4-5)
                </button>
                <button onclick="applyQuickFilter('verified')"
                        class="px-3 py-1 text-sm border border-gray-300 rounded-full hover:bg-orange-50 hover:border-orange-300 hover:text-orange-600 transition-colors duration-200">
                    ✅ Pembeli Terverifikasi
                </button>
                <button onclick="clearFilters()"
                        class="px-3 py-1 text-sm bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 transition-colors duration-200">
                    🗑️ Reset Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Rating Distribution -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribusi Rating</h3>
        <div class="space-y-3">
            @for($i = 5; $i >= 1; $i--)
                @php
                    $percentage = $totalReviews > 0 ? (($ratingDistribution[$i] ?? 0) / $totalReviews) * 100 : 0;
                @endphp
                <div class="flex items-center">
                    <span class="text-sm font-medium text-gray-600 w-12">{{ $i }} ⭐</span>
                    <div class="flex-1 mx-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-orange-400 h-2 rounded-full transition-all duration-300"
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500 w-12">{{ $ratingDistribution[$i] ?? 0 }}</span>
                    <span class="text-sm text-gray-400 w-12">{{ number_format($percentage, 1) }}%</span>
                </div>
            @endfor
        </div>
    </div>

    <!-- Reviews List -->
    <div id="reviewsList" class="space-y-6">
        @forelse($reviews as $review)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 review-item"
                 data-rating="{{ $review->rating }}"
                 data-date="{{ $review->created_at->format('Y-m-d') }}"
                 data-helpful="{{ $review->helpful_count ?? 0 }}">

                <!-- Review Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-start space-x-4">
                        <!-- User Avatar -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                <span class="text-orange-600 font-medium text-lg">
                                    {{ substr($review->user->name ?? 'A', 0, 1) }}
                                </span>
                            </div>
                        </div>

                        <!-- User Info and Rating -->
                        <div>
                            <div class="flex items-center mb-1">
                                <h4 class="font-medium text-gray-800 mr-3">{{ $review->user->name ?? 'Anonymous' }}</h4>
                                @if($review->is_verified_buyer)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                        ✅ Pembeli Terverifikasi
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center mb-2">
                                <div class="flex items-center mr-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-orange-400' : 'text-gray-300' }}"
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">({{ $review->rating }}/5)</span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $review->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Review Actions -->
                    <div class="flex items-center space-x-2">
                        <button onclick="toggleHelpful({{ $review->id }})"
                                class="flex items-center px-3 py-1 text-sm border rounded-lg hover:bg-gray-50 transition-colors duration-200
                                       {{ $review->user_marked_helpful ? 'border-orange-300 text-orange-600' : 'border-gray-300 text-gray-600' }}">
                            <svg class="w-4 h-4 mr-1" fill="{{ $review->user_marked_helpful ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L9 7v13m-3-7H4a1 1 0 01-1-1V9a1 1 0 011-1h2m5-5v2.5"/>
                            </svg>
                            <span id="helpful-count-{{ $review->id }}">{{ $review->helpful_count ?? 0 }}</span>
                        </button>

                        <button onclick="shareReview({{ $review->id }})"
                                class="flex items-center px-3 py-1 text-sm border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                            </svg>
                            Bagikan
                        </button>
                    </div>
                </div>

                <!-- Product Information -->
                @if($review->produk)
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="flex items-center space-x-3">
                            <img src="{{ asset('storage/' . $review->produk->gambar) }}"
                                 alt="{{ $review->produk->nama }}"
                                 class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                            <div>
                                <h5 class="font-medium text-gray-800">{{ $review->produk->nama }}</h5>
                                <p class="text-sm text-gray-600">{{ $review->produk->kategori ?? 'Kategori tidak tersedia' }}</p>
                            </div>
                            <div class="ml-auto">
                                <a href="{{ route('public.products.show', $review->produk->id) }}"
                                   class="text-orange-600 hover:text-orange-700 text-sm font-medium transition-colors duration-200">
                                    Lihat Produk →
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Review Content -->
                <div class="mb-4">
                    <p class="text-gray-700 leading-relaxed">{{ $review->komentar }}</p>
                </div>

                <!-- Review Photos -->
                @if($review->photos && count($review->photos) > 0)
                    <div class="mb-4">
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                            @foreach($review->photos as $index => $photo)
                                <div class="relative group cursor-pointer" onclick="openPhotoModal({{ $review->id }}, {{ $index }})">
                                    <img src="{{ asset('storage/' . $photo) }}"
                                         alt="Review Photo {{ $index + 1 }}"
                                         class="w-full h-20 object-cover rounded-lg border border-gray-200 group-hover:opacity-75 transition-opacity duration-200">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all duration-200 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Admin Reply -->
                @if($review->admin_reply)
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <h4 class="text-sm font-medium text-orange-800">Balasan dari Admin</h4>
                                <p class="text-sm text-orange-700 mt-1">{{ $review->admin_reply }}</p>
                                @if($review->admin_reply_at)
                                    <p class="text-xs text-orange-600 mt-2">
                                        {{ \Carbon\Carbon::parse($review->admin_reply_at)->format('d M Y, H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-1l-4 4z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-800 mb-2">Belum Ada Review</h3>
                <p class="text-gray-600 mb-4">Jadilah yang pertama memberikan review untuk produk kami.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($reviews->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $reviews->links() }}
        </div>
    @endif

    <!-- Load More Button -->
    @if($reviews->hasMorePages())
        <div class="mt-6 text-center">
            <button onclick="loadMoreReviews()"
                    id="loadMoreBtn"
                    class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-3 px-8 rounded-lg transition-colors duration-200">
                Muat Lebih Banyak Review
            </button>
        </div>
    @endif
</div>

<!-- Photo Modal -->
<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="relative max-w-4xl max-h-full mx-4">
        <button onclick="closePhotoModal()"
                class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div class="bg-white rounded-lg overflow-hidden">
            <img id="modalImage" src="" alt="Review Photo" class="w-full h-auto max-h-[80vh] object-contain">
            <div class="p-4 bg-gray-50">
                <div class="flex justify-between items-center">
                    <span id="photoCounter" class="text-sm text-gray-600"></span>
                    <div class="flex space-x-2">
                        <button onclick="previousPhoto()"
                                class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded transition-colors duration-200">
                            ← Sebelumnya
                        </button>
                        <button onclick="nextPhoto()"
                                class="px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded transition-colors duration-200">
                            Selanjutnya →
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentReviewPhotos = [];
let currentPhotoIndex = 0;
let currentPage = 1;

// Photo modal functionality
function openPhotoModal(reviewId, index) {
    // Get photos for this review
    const reviewElement = document.querySelector(`[data-review-id="${reviewId}"]`);
    if (reviewElement) {
        currentReviewPhotos = JSON.parse(reviewElement.dataset.photos || '[]');
    }

    currentPhotoIndex = index;
    updateModalPhoto();
    document.getElementById('photoModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function updateModalPhoto() {
    if (currentReviewPhotos.length > 0) {
        const modalImage = document.getElementById('modalImage');
        const photoCounter = document.getElementById('photoCounter');

        modalImage.src = `/storage/${currentReviewPhotos[currentPhotoIndex]}`;
        photoCounter.textContent = `${currentPhotoIndex + 1} dari ${currentReviewPhotos.length}`;
    }
}

function previousPhoto() {
    if (currentPhotoIndex > 0) {
        currentPhotoIndex--;
        updateModalPhoto();
    }
}

function nextPhoto() {
    if (currentPhotoIndex < currentReviewPhotos.length - 1) {
        currentPhotoIndex++;
        updateModalPhoto();
    }
}

// Keyboard navigation for photo modal
document.addEventListener('keydown', function(e) {
    if (!document.getElementById('photoModal').classList.contains('hidden')) {
        if (e.key === 'Escape') {
            closePhotoModal();
        } else if (e.key === 'ArrowLeft') {
            previousPhoto();
        } else if (e.key === 'ArrowRight') {
            nextPhoto();
        }
    }
});

// Filter and search functionality
function applyFilters() {
    const search = document.getElementById('search').value.toLowerCase();
    const rating = document.getElementById('ratingFilter').value;
    const sortBy = document.getElementById('sortBy').value;

    const reviews = document.querySelectorAll('.review-item');
    let visibleReviews = [];

    reviews.forEach(review => {
        let visible = true;

        // Search filter
        if (search) {
            const text = review.textContent.toLowerCase();
            visible = visible && text.includes(search);
        }

        // Rating filter
        if (rating) {
            const reviewRating = review.dataset.rating;
            visible = visible && reviewRating === rating;
        }

        if (visible) {
            review.style.display = 'block';
            visibleReviews.push(review);
        } else {
            review.style.display = 'none';
        }
    });

    // Sort visible reviews
    if (sortBy && visibleReviews.length > 0) {
        const container = document.getElementById('reviewsList');
        const sortedReviews = sortReviews(visibleReviews, sortBy);

        // Re-append sorted reviews
        sortedReviews.forEach(review => {
            container.appendChild(review);
        });
    }
}

function sortReviews(reviews, sortBy) {
    return reviews.sort((a, b) => {
        switch (sortBy) {
            case 'newest':
                return new Date(b.dataset.date) - new Date(a.dataset.date);
            case 'oldest':
                return new Date(a.dataset.date) - new Date(b.dataset.date);
            case 'highest_rating':
                return parseInt(b.dataset.rating) - parseInt(a.dataset.rating);
            case 'lowest_rating':
                return parseInt(a.dataset.rating) - parseInt(b.dataset.rating);
            case 'most_helpful':
                return parseInt(b.dataset.helpful) - parseInt(a.dataset.helpful);
            default:
                return 0;
        }
    });
}

function applyQuickFilter(filter) {
    const searchInput = document.getElementById('search');
    const ratingFilter = document.getElementById('ratingFilter');
    const sortBy = document.getElementById('sortBy');

    // Clear existing filters
    searchInput.value = '';
    ratingFilter.value = '';

    switch (filter) {
        case 'with_photos':
            // This would need to be implemented with a data attribute
            break;
        case 'recent':
            // Filter reviews from last 7 days
            const weekAgo = new Date();
            weekAgo.setDate(weekAgo.getDate() - 7);
            // Implementation would filter by date
            break;
        case 'high_rating':
            ratingFilter.value = '4'; // 4-5 stars
            break;
        case 'verified':
            // Filter verified buyers only
            break;
    }

    applyFilters();
}

function clearFilters() {
    document.getElementById('search').value = '';
    document.getElementById('ratingFilter').value = '';
    document.getElementById('sortBy').value = 'newest';

    const reviews = document.querySelectorAll('.review-item');
    reviews.forEach(review => {
        review.style.display = 'block';
    });
}

// Event listeners for filters
document.getElementById('search').addEventListener('input', debounce(applyFilters, 300));
document.getElementById('ratingFilter').addEventListener('change', applyFilters);
document.getElementById('sortBy').addEventListener('change', applyFilters);

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Helpful/Like functionality
function toggleHelpful(reviewId) {
    fetch(`/public/reviews/${reviewId}/helpful`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const button = document.querySelector(`button[onclick="toggleHelpful(${reviewId})"]`);
            const count = document.getElementById(`helpful-count-${reviewId}`);

            if (data.helpful) {
                button.classList.add('border-orange-300', 'text-orange-600');
                button.classList.remove('border-gray-300', 'text-gray-600');
                button.querySelector('svg').setAttribute('fill', 'currentColor');
            } else {
                button.classList.remove('border-orange-300', 'text-orange-600');
                button.classList.add('border-gray-300', 'text-gray-600');
                button.querySelector('svg').setAttribute('fill', 'none');
            }

            count.textContent = data.helpful_count;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses feedback');
    });
}

// Share functionality
function shareReview(reviewId) {
    const url = `${window.location.origin}/public/reviews/${reviewId}`;

    if (navigator.share) {
        navigator.share({
            title: 'Review Produk',
            text: 'Lihat review produk ini',
            url: url
        });
    } else {
        // Fallback to clipboard
        navigator.clipboard.writeText(url).then(() => {
            alert('Link review berhasil disalin ke clipboard!');
        }).catch(() => {
            alert('Gagal menyalin link review');
        });
    }
}

// Load more reviews
function loadMoreReviews() {
    const button = document.getElementById('loadMoreBtn');
    const originalText = button.textContent;

    button.textContent = 'Memuat...';
    button.disabled = true;

    fetch(`/public/reviews?page=${currentPage + 1}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.html) {
            document.getElementById('reviewsList').insertAdjacentHTML('beforeend', data.html);
            currentPage++;

            if (!data.hasMore) {
                button.style.display = 'none';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Gagal memuat review tambahan');
    })
    .finally(() => {
        button.textContent = originalText;
        button.disabled = false;
    });
}
</script>
@endpush
