@extends('layouts.app')

@section('title', 'Detail Review')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Detail Review</h1>
                <p class="text-gray-600">Review untuk pesanan #{{ $review->pesanan->id ?? 'N/A' }}</p>
            </div>
            <a href="{{ route('customer.reviews.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Review Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Review Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">{{ $review->produk->nama ?? 'Produk tidak ditemukan' }}</h2>
                        <div class="flex items-center mb-2">
                            <div class="flex items-center mr-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-orange-400' : 'text-gray-300' }}"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">({{ $review->rating }}/5)</span>
                            </div>
                            @php
                                $statusBg = 'bg-gray-100';
                                $statusText = 'text-gray-800';
                                if ($review->status_review === 'approved') {
                                    $statusBg = 'bg-green-100';
                                    $statusText = 'text-green-800';
                                } elseif ($review->status_review === 'pending') {
                                    $statusBg = 'bg-yellow-100';
                                    $statusText = 'text-yellow-800';
                                } elseif ($review->status_review === 'rejected') {
                                    $statusBg = 'bg-red-100';
                                    $statusText = 'text-red-800';
                                }
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusBg }} {{ $statusText }}">
                                {{ ucfirst($review->status_review) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">
                            Ditulis pada {{ $review->created_at->format('d M Y') }} •
                            Diperbarui {{ $review->updated_at->format('d M Y') }}
                        </p>
                    </div>
                    @if($review->status === 'approved')
                        <div class="flex space-x-2">
                            <button onclick="likeReview({{ $review->id }})"
                                    class="flex items-center px-3 py-1 text-sm border rounded-lg hover:bg-gray-50 transition-colors duration-200
                                           {{ $review->user_liked ? 'border-orange-300 text-orange-600' : 'border-gray-300 text-gray-600' }}">
                                <svg class="w-4 h-4 mr-1" fill="{{ $review->user_liked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L9 7v13m-3-7H4a1 1 0 01-1-1V9a1 1 0 011-1h2m5-5v2.5"/>
                                </svg>
                                <span id="like-count-{{ $review->id }}">{{ $review->likes_count ?? 0 }}</span>
                            </button>
                            <button onclick="shareReview({{ $review->id }})"
                                    class="flex items-center px-3 py-1 text-sm border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                </svg>
                                Bagikan
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Review Content -->
                <div class="mb-6">
                    <h3 class="font-medium text-gray-800 mb-2">Review</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700 leading-relaxed">{{ $review->komentar }}</p>
                    </div>
                </div>

                <!-- Review Photos -->
                @if($review->photos && count($review->photos) > 0)
                    <div class="mb-6">
                        <h3 class="font-medium text-gray-800 mb-3">Foto Review</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach($review->photos as $index => $photo)
                                <div class="relative group cursor-pointer" onclick="openPhotoModal({{ $index }})">
                                    <img src="{{ asset('storage/' . $photo) }}"
                                         alt="Review Photo {{ $index + 1 }}"
                                         class="w-full h-24 object-cover rounded-lg border border-gray-200 group-hover:opacity-75 transition-opacity duration-200">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all duration-200 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="border-t pt-6">
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
                                            Dibalas pada {{ \Carbon\Carbon::parse($review->admin_reply_at)->format('d M Y, H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Review Actions -->
            @if($review->status === 'approved' && !$review->admin_reply)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-medium text-gray-800 mb-4">Aksi Review</h3>
                    <div class="flex flex-wrap gap-3">
                        <button onclick="editReview()"
                                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Review
                        </button>
                        <button onclick="deleteReview()"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Hapus Review
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Product Information -->
            @if($review->produk)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-medium text-gray-800 mb-4">Informasi Produk</h3>
                    <div class="flex items-start space-x-4">
                        <img src="{{ asset('storage/' . $review->produk->gambar) }}"
                             alt="{{ $review->produk->nama }}"
                             class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800 mb-1">{{ $review->produk->nama }}</h4>
                            <p class="text-sm text-gray-600 mb-2">{{ $review->produk->kategori ?? 'Kategori tidak tersedia' }}</p>
                            <p class="text-lg font-semibold text-orange-600">
                                Rp {{ number_format($review->produk->harga, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('customer.products.show', $review->produk->id) }}"
                           class="text-orange-600 hover:text-orange-700 text-sm font-medium transition-colors duration-200">
                            Lihat Detail Produk →
                        </a>
                    </div>
                </div>
            @endif

            <!-- Order Information -->
            @if($review->pesanan)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-medium text-gray-800 mb-4">Informasi Pesanan</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">ID Pesanan:</span>
                            <span class="text-sm font-medium text-gray-800">#{{ $review->pesanan->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Tanggal Pesanan:</span>
                            <span class="text-sm font-medium text-gray-800">{{ $review->pesanan->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Status:</span>
                            @php
                                $orderStatusBg = 'bg-gray-100';
                                $orderStatusText = 'text-gray-800';
                                if ($review->pesanan->status === 'completed') {
                                    $orderStatusBg = 'bg-green-100';
                                    $orderStatusText = 'text-green-800';
                                } elseif ($review->pesanan->status === 'processing') {
                                    $orderStatusBg = 'bg-blue-100';
                                    $orderStatusText = 'text-blue-800';
                                } elseif ($review->pesanan->status === 'shipped') {
                                    $orderStatusBg = 'bg-yellow-100';
                                    $orderStatusText = 'text-yellow-800';
                                }
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $orderStatusBg }} {{ $orderStatusText }}">
                                {{ ucfirst($review->pesanan->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('customer.orders.show', $review->pesanan->id) }}"
                           class="text-orange-600 hover:text-orange-700 text-sm font-medium transition-colors duration-200">
                            Lihat Detail Pesanan →
                        </a>
                    </div>
                </div>
            @endif

            <!-- Review Statistics -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-medium text-gray-800 mb-4">Statistik Review</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Like:</span>
                        <span class="text-sm font-medium text-gray-800">{{ $review->likes_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Dilihat:</span>
                        <span class="text-sm font-medium text-gray-800">{{ $review->views_count ?? 0 }} kali</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Rating:</span>
                        <span class="text-sm font-medium text-gray-800">{{ $review->rating }}/5 ⭐</span>
                    </div>
                    @if($review->status === 'approved')
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Helpful:</span>
                            <span class="text-sm font-medium text-gray-800">{{ $review->helpful_count ?? 0 }} orang</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Help Section -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                <h3 class="font-medium text-orange-800 mb-3">💡 Tips Review</h3>
                <ul class="text-sm text-orange-700 space-y-2">
                    <li>• Review yang detail membantu pembeli lain</li>
                    <li>• Sertakan foto untuk review yang lebih kredibel</li>
                    <li>• Berikan rating yang jujur sesuai pengalaman</li>
                    <li>• Hindari kata-kata yang tidak pantas</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Fix modal class conflict --}}
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 z-50" style="display: none;">
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
<script>
let currentPhotoIndex = 0;
const photos = @json($review->photos ?? []);

function openPhotoModal(index) {
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
    if (photos.length > 0) {
        const modalImage = document.getElementById('modalImage');
        const photoCounter = document.getElementById('photoCounter');

        modalImage.src = `/storage/${photos[currentPhotoIndex]}`;
        photoCounter.textContent = `${currentPhotoIndex + 1} dari ${photos.length}`;
    }
}

function previousPhoto() {
    if (currentPhotoIndex > 0) {
        currentPhotoIndex--;
        updateModalPhoto();
    }
}

function nextPhoto() {
    if (currentPhotoIndex < photos.length - 1) {
        currentPhotoIndex++;
        updateModalPhoto();
    }
}

// Keyboard navigation
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

// Review interactions
function likeReview(reviewId) {
    fetch(`/customer/reviews/${reviewId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeButton = document.querySelector(`button[onclick="likeReview(${reviewId})"]`);
            const likeCount = document.getElementById(`like-count-${reviewId}`);

            if (data.liked) {
                likeButton.classList.add('border-orange-300', 'text-orange-600');
                likeButton.classList.remove('border-gray-300', 'text-gray-600');
                likeButton.querySelector('svg').setAttribute('fill', 'currentColor');
            } else {
                likeButton.classList.remove('border-orange-300', 'text-orange-600');
                likeButton.classList.add('border-gray-300', 'text-gray-600');
                likeButton.querySelector('svg').setAttribute('fill', 'none');
            }

            likeCount.textContent = data.likes_count;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses like');
    });
}

function shareReview(reviewId) {
    const url = window.location.href;

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

function editReview() {
    if (confirm('Apakah Anda yakin ingin mengedit review ini?')) {
        window.location.href = `/customer/reviews/{{ $review->id }}/edit`;
    }
}

function deleteReview() {
    if (confirm('Apakah Anda yakin ingin menghapus review ini? Tindakan ini tidak dapat dibatalkan.')) {
        fetch(`/customer/reviews/{{ $review->id }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Review berhasil dihapus');
                window.location.href = '/customer/reviews';
            } else {
                alert('Gagal menghapus review: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus review');
        });
    }
}

// Auto-refresh for pending reviews
@if($review->status === 'pending')
    setInterval(() => {
        fetch(`/customer/reviews/{{ $review->id }}/status`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status !== 'pending') {
                location.reload();
            }
        })
        .catch(error => console.error('Status check error:', error));
    }, 30000); // Check every 30 seconds
@endif
</script>
@endpush
