@extends('admin.layouts.app')

@section('title', 'Moderasi Ulasan')

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

    .review-card {
        @apply bg-white rounded-lg shadow border border-gray-200 p-4 hover:shadow-md transition-shadow;
    }

    .bulk-actions {
        @apply bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6;
    }

    .action-btn {
        @apply px-4 py-2 rounded-lg font-medium transition-colors text-sm;
    }
    .action-btn.approve {
        @apply bg-green-500 hover:bg-green-600 text-white;
    }
    .action-btn.reject {
        @apply bg-red-500 hover:bg-red-600 text-white;
    }
    .action-btn.pending {
        @apply bg-yellow-500 hover:bg-yellow-600 text-white;
    }
</style>
@endpush

@section('header')
<div class="flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900">Moderasi Ulasan</h1>
    <div class="flex space-x-3">
        <a href="{{ route('admin.reviews.statistics') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-chart-bar mr-2"></i>Statistik
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-list mr-2"></i>Semua Ulasan
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="p-6">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- Bulk Actions -->
    @if($pendingReviews->count() > 0)
        <div class="bulk-actions">
            <form id="bulkModerationForm" action="{{ route('admin.reviews.bulkModerate') }}" method="POST">
                @csrf
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="selectAll" class="form-checkbox h-4 w-4 text-orange-600">
                            <span class="ml-2 text-sm font-medium text-gray-700">Pilih Semua</span>
                        </label>
                        <span id="selectedCount" class="text-sm text-gray-600">0 dipilih</span>
                    </div>

                    <div class="flex space-x-2">
                        <button type="submit" name="action" value="approve" class="action-btn approve" disabled id="approveBtn">
                            <i class="fas fa-check mr-1"></i>Setujui
                        </button>
                        <button type="submit" name="action" value="reject" class="action-btn reject" disabled id="rejectBtn">
                            <i class="fas fa-times mr-1"></i>Tolak
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    <!-- Pending Reviews Section -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">
            Ulasan Menunggu Moderasi ({{ $pendingReviews->count() }})
        </h2>

        @if($pendingReviews->count() > 0)
            <div class="space-y-4">
                @foreach($pendingReviews as $review)
                    <div class="review-card">
                        <div class="flex items-start space-x-4">
                            <!-- Checkbox for bulk actions -->
                            <div class="flex-shrink-0 pt-1">
                                <input type="checkbox" name="review_ids[]" value="{{ $review->id_ulasan }}"
                                       class="review-checkbox form-checkbox h-4 w-4 text-orange-600">
                            </div>

                            <!-- User Avatar -->
                            <div class="flex-shrink-0">
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
                            </div>

                            <!-- Review Content -->
                            <div class="flex-1 min-w-0">
                                <!-- Header -->
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-2">
                                        <h4 class="text-sm font-semibold text-gray-900">{{ $review->user->name }}</h4>
                                        @if($review->is_verified_purchase)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-check-circle mr-1"></i>Verified
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $review->created_at->format('d M Y, H:i') }}</span>
                                </div>

                                <!-- Product Info -->
                                <div class="flex items-center space-x-3 mb-3 p-2 bg-gray-50 rounded">
                                    @if($review->produk->gambar)
                                        <img src="{{ asset('storage/' . $review->produk->gambar) }}"
                                             alt="{{ $review->produk->nama_ikan }}"
                                             class="h-10 w-10 rounded object-cover">
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $review->produk->nama_ikan }}</p>
                                        <p class="text-xs text-gray-600">{{ $review->produk->jenis_ikan }}</p>
                                    </div>
                                </div>

                                <!-- Rating -->
                                <div class="flex items-center space-x-2 mb-2">
                                    <div class="flex rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fas fa-star text-sm"></i>
                                            @else
                                                <i class="far fa-star text-sm"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $review->rating }}/5</span>
                                </div>

                                <!-- Review Text -->
                                <p class="text-gray-700 mb-3">{{ $review->komentar }}</p>

                                <!-- Review Photos -->
                                @if($review->hasPhotos())
                                    <div class="flex space-x-2 mb-3">
                                        @foreach($review->photo_urls as $index => $photo)
                                            @if($index < 3)
                                                <img src="{{ $photo }}" alt="Review photo"
                                                     class="h-16 w-16 rounded object-cover border cursor-pointer"
                                                     onclick="openImageModal('{{ $photo }}')">
                                            @elseif($index === 3)
                                                <div class="h-16 w-16 rounded bg-gray-200 flex items-center justify-center border">
                                                    <span class="text-xs text-gray-600">+{{ count($review->photo_urls) - 3 }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    <form action="{{ route('admin.reviews.updateStatus', $review) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status_review" value="approved">
                                        <button type="submit" class="action-btn approve">
                                            <i class="fas fa-check mr-1"></i>Setujui
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.reviews.updateStatus', $review) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="status_review" value="rejected">
                                        <button type="submit" class="action-btn reject">
                                            <i class="fas fa-times mr-1"></i>Tolak
                                        </button>
                                    </form>

                                    <a href="{{ route('admin.reviews.show', $review) }}" class="action-btn pending">
                                        <i class="fas fa-eye mr-1"></i>Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $pendingReviews->links() }}
            </div>
        @else
            <div class="text-center py-8 bg-white rounded-lg shadow border border-gray-200">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <i class="fas fa-clipboard-check text-4xl"></i>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada ulasan pending</h3>
                <p class="mt-1 text-sm text-gray-500">Semua ulasan telah dimoderasi.</p>
            </div>
        @endif
    </div>

    <!-- Recent Approved Reviews -->
    @if($recentReviews->count() > 0)
        <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-4">
                Ulasan Terbaru yang Disetujui
            </h2>

            <div class="space-y-4">
                @foreach($recentReviews as $review)
                    <div class="review-card">
                        <div class="flex items-start space-x-4">
                            <!-- User Avatar -->
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                    @if($review->user->foto)
                                        <img src="{{ asset('storage/uploads/users/' . $review->user->foto) }}"
                                             alt="{{ $review->user->name }}"
                                             class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <span class="text-sm font-medium text-gray-600">
                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Review Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center space-x-2">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $review->user->name }}</h4>
                                        <span class="status-badge status-approved">Disetujui</span>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                                </div>

                                <p class="text-sm text-gray-600 mb-1">{{ $review->produk->nama_ikan }}</p>

                                <div class="flex items-center space-x-2 mb-1">
                                    <div class="flex rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fas fa-star text-xs"></i>
                                            @else
                                                <i class="far fa-star text-xs"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-700">{{ $review->rating }}/5</span>
                                </div>

                                <p class="text-sm text-gray-700 line-clamp-2">{{ $review->komentar }}</p>

                                <div class="flex justify-between items-center mt-2">
                                    <div class="text-xs text-gray-500">
                                        {{ $review->helpful_count }} membantu • {{ $review->interactions->count() }} interaksi
                                    </div>
                                    <a href="{{ route('admin.reviews.show', $review) }}" class="text-orange-600 hover:text-orange-700 text-sm">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
<script>
// Image modal functions
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

// Bulk selection functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const reviewCheckboxes = document.querySelectorAll('.review-checkbox');
    const bulkForm = document.getElementById('bulkModerationForm');
    const selectedCount = document.getElementById('selectedCount');
    const approveBtn = document.getElementById('approveBtn');
    const rejectBtn = document.getElementById('rejectBtn');

    // Update buttons and count when checkboxes change
    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.review-checkbox:checked');
        const count = checkedBoxes.length;

        selectedCount.textContent = count + ' dipilih';

        if (count > 0) {
            approveBtn.disabled = false;
            rejectBtn.disabled = false;
            approveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            rejectBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            approveBtn.disabled = true;
            rejectBtn.disabled = true;
            approveBtn.classList.add('opacity-50', 'cursor-not-allowed');
            rejectBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    // Select all functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            reviewCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }

    // Individual checkbox functionality
    reviewCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActions();

            // Update select all checkbox
            if (selectAllCheckbox) {
                const checkedCount = document.querySelectorAll('.review-checkbox:checked').length;
                selectAllCheckbox.checked = checkedCount === reviewCheckboxes.length;
                selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < reviewCheckboxes.length;
            }
        });
    });

    // Confirm bulk actions
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const checkedBoxes = document.querySelectorAll('.review-checkbox:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert('Pilih minimal satu ulasan untuk dimoderasi.');
                return;
            }

            const action = e.submitter.value;
            const actionText = action === 'approve' ? 'menyetujui' : 'menolak';

            if (!confirm(`Apakah Anda yakin ingin ${actionText} ${checkedBoxes.length} ulasan?`)) {
                e.preventDefault();
            }
        });
    }

    // Initialize
    updateBulkActions();
});
</script>
@endpush
