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
        @apply bg-white rounded-lg shadow border border-gray-200 p-4 hover:shadow-md transition-shadow;
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
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
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
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                    <p class="text-gray-600">Pending</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['verified'] ?? 0 }}</p>
                    <p class="text-gray-600">Terverifikasi</p>
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

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-eye-slash"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['hidden'] ?? 0 }}</p>
                    <p class="text-gray-600">Disembunyikan</p>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Semua Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Verifikasi</label>
                    <select name="verified" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Semua</option>
                        <option value="1" {{ request('verified') == '1' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="0" {{ request('verified') == '0' ? 'selected' : '' }}>Belum Verifikasi</option>
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
        <div class="review-card">
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
                            @if($review->produk->gambar)
                            <img src="{{ asset('storage/' . $review->produk->gambar) }}"
                                 alt="{{ $review->produk->nama }}"
                                 class="w-12 h-12 object-cover rounded-lg">
                            @endif
                            <div>
                                <div class="font-medium text-gray-900">{{ $review->produk->nama }}</div>
                                <div class="text-sm text-gray-500">Pesanan: {{ $review->pesanan ? $review->pesanan->nomor_pesanan : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Content -->
                    <div class="mb-3">
                        <p class="text-gray-800">{{ $review->ulasan }}</p>
                    </div>

                    <!-- Review Photos -->
                    @if($review->photos && count($review->photos) > 0)
                    <div class="mb-3">
                        <div class="flex space-x-2">
                            @foreach(array_slice($review->photos, 0, 4) as $photo)
                            <img src="{{ asset('storage/' . $photo) }}"
                                 alt="Review photo"
                                 class="w-16 h-16 object-cover rounded-lg cursor-pointer hover:opacity-80"
                                 onclick="openPhotoModal('{{ asset('storage/' . $photo) }}')">
                            @endforeach
                            @if(count($review->photos) > 4)
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-600 text-sm">
                                +{{ count($review->photos) - 4 }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Admin Reply -->
                    @if($review->admin_reply)
                    <div class="mt-4 p-3 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
                        <div class="flex items-center space-x-2 mb-2">
                            <i class="fas fa-reply text-blue-600"></i>
                            <span class="font-medium text-blue-900">Balasan Admin</span>
                            <span class="text-sm text-blue-600">{{ $review->admin_reply_at ? $review->admin_reply_at->format('d/m/Y H:i') : '' }}</span>
                        </div>
                        <p class="text-blue-800">{{ $review->admin_reply }}</p>
                    </div>
                    @endif

                    <!-- Interactions -->
                    @if($review->interactions->count() > 0)
                    <div class="mt-3 flex items-center space-x-4 text-sm text-gray-600">
                        <span><i class="fas fa-thumbs-up mr-1"></i>{{ $review->interactions->where('type', 'helpful')->count() }} Helpful</span>
                        <span><i class="fas fa-thumbs-down mr-1"></i>{{ $review->interactions->where('type', 'not_helpful')->count() }} Not Helpful</span>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="ml-4 flex flex-col space-y-2">
                    <a href="{{ route('admin.reviews.show', $review) }}"
                       class="text-orange-600 hover:text-orange-900 text-sm">
                        <i class="fas fa-eye mr-1"></i>Detail
                    </a>

                    @if(!$review->admin_reply)
                    <button onclick="openReplyModal({{ $review->id }})"
                            class="text-blue-600 hover:text-blue-900 text-sm">
                        <i class="fas fa-reply mr-1"></i>Balas
                    </button>
                    @endif

                    @if(!$review->is_verified)
                    <button onclick="verifyReview({{ $review->id }})"
                            class="text-green-600 hover:text-green-900 text-sm">
                        <i class="fas fa-check mr-1"></i>Verifikasi
                    </button>
                    @endif

                    <button onclick="moderateReview({{ $review->id }}, '{{ $review->status === 'published' ? 'hidden' : 'published' }}')"
                            class="text-red-600 hover:text-red-900 text-sm">
                        <i class="fas fa-{{ $review->status === 'published' ? 'eye-slash' : 'eye' }} mr-1"></i>
                        {{ $review->status === 'published' ? 'Sembunyikan' : 'Tampilkan' }}
                    </button>
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

<!-- Reply Modal -->
<div id="replyModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <!-- Modal Backdrop with Blur -->
    <div class="modal-backdrop"></div>

    <!-- Modal Container -->
    <div class="modal-container">
        <!-- Modal Content -->
        <div class="modal-content max-w-lg w-full mx-auto">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Balas Ulasan</h3>

                <form id="replyForm">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Balasan Admin</label>
                        <textarea id="replyTextarea" name="admin_reply" rows="4" required
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                                  placeholder="Tulis balasan untuk pelanggan..."></textarea>
                    </div>
                </form>
            </div>

            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="submitReply()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    Kirim Balasan
                </button>
                <button type="button" onclick="closeReplyModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div id="photoModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <!-- Modal Backdrop with Blur -->
    <div class="modal-backdrop" onclick="closePhotoModal()"></div>

    <!-- Modal Container -->
    <div class="modal-container">
        <!-- Modal Content -->
        <div class="modal-content max-w-4xl w-full mx-auto">
            <div class="bg-white">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Foto Ulasan</h3>
                    <button onclick="closePhotoModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-4">
                    <img id="modalPhoto" src="" alt="Review photo" class="w-full h-auto max-h-96 object-contain">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentReviewId = null;

function openReplyModal(reviewId) {
    currentReviewId = reviewId;
    document.getElementById('replyModal').classList.remove('hidden');
    document.getElementById('replyTextarea').focus();
}

function closeReplyModal() {
    document.getElementById('replyModal').classList.add('hidden');
    currentReviewId = null;
    document.getElementById('replyTextarea').value = '';
}

function submitReply() {
    if (!currentReviewId) return;

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('admin_reply', document.getElementById('replyTextarea').value);

    fetch(`/admin/reviews/${currentReviewId}/reply`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal mengirim balasan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

function verifyReview(reviewId) {
    if (!confirm('Yakin ingin memverifikasi ulasan ini?')) return;

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    fetch(`/admin/reviews/${reviewId}/verify`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal memverifikasi ulasan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

function moderateReview(reviewId, action) {
    const actionText = action === 'hidden' ? 'menyembunyikan' : 'menampilkan';
    if (!confirm(`Yakin ingin ${actionText} ulasan ini?`)) return;

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('action', action);

    fetch(`/admin/reviews/${reviewId}/moderate`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal memperbarui status ulasan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

function openPhotoModal(photoUrl) {
    document.getElementById('modalPhoto').src = photoUrl;
    document.getElementById('photoModal').classList.remove('hidden');
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
}

// Close modals when clicking on the backdrop
document.querySelector('#replyModal .modal-backdrop').addEventListener('click', function() {
    closeReplyModal();
});

// Close photo modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhotoModal();
        closeReplyModal();
    }
});
</script>
@endpush
