@extends('layouts.app')

@section('title', 'Tulis Review')

@push('styles')
<style>
    .rating-star, .product-rating-star {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .rating-star:hover, .product-rating-star:hover {
        transform: scale(1.1);
    }
    .rating-star.active, .product-rating-star.active {
        color: #f59e0b !important;
        transform: scale(1.05);
    }
    .form-container {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
        padding: 2rem 0;
    }
    .card {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: box-shadow 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .submit-btn {
        transition: all 0.3s ease;
    }
    .submit-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .error-message {
        font-size: 0.875rem;
        color: #dc2626;
        margin-top: 0.25rem;
    }
    .success-animation {
        animation: bounce 0.3s ease;
    }
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-5px); }
        60% { transform: translateY(-3px); }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Tulis Review</h1>
                <p class="text-gray-600">Bagikan pengalaman Anda dengan produk yang Anda beli</p>
            </div>
            <a href="{{ route('reviews.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Review Form -->
        <div class="lg:col-span-2">
            <form id="reviewForm" action="{{ route('reviews.store', $pesanan) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="pesanan_id" value="{{ $pesanan->id_pesanan }}">

                <!-- Products Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Produk yang Direview</h2>

                    @if($pesanan->reviewable_products->count() > 0)
                        <div class="space-y-6">
                            @foreach($pesanan->reviewable_products as $index => $detailPesanan)
                                <div class="review-product-item border-b border-gray-200 pb-6 mb-6 last:border-b-0 last:mb-0 last:pb-0">
                                    <div class="flex items-start space-x-4">
                                        <img src="{{ asset('storage/' . $detailPesanan->produk->gambar) }}"
                                            alt="{{ $detailPesanan->produk->nama }}"
                                            class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                        <div class="flex-1">
                                            <h3 class="font-medium text-gray-800 mb-1">{{ $detailPesanan->produk->nama }}</h3>
                                            <p class="text-sm text-gray-600 mb-2">{{ $detailPesanan->produk->kategori ?? 'Kategori tidak tersedia' }}</p>
                                            <p class="text-lg font-semibold text-orange-600">
                                                Rp {{ number_format($detailPesanan->produk->harga, 0, ',', '.') }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-2">
                                                Pesanan #{{ $pesanan->id_pesanan }} • {{ $pesanan->created_at->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>

                                    <input type="hidden" name="reviews[{{ $index }}][id_produk]" value="{{ $detailPesanan->id_Produk }}">

                                    <!-- Rating for this product -->
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating untuk produk ini</label>
                                        <div class="flex items-center space-x-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button"
                                                        onclick="setProductRating({{ $index }}, {{ $i }})"
                                                        class="product-rating-star product-{{ $index }} text-gray-300 hover:text-orange-400 focus:outline-none transition-colors duration-200"
                                                        data-product="{{ $index }}" data-rating="{{ $i }}">
                                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                </button>
                                            @endfor
                                            <span class="product-rating-text-{{ $index }} ml-4 text-sm font-medium text-gray-600"></span>
                                        </div>
                                        <input type="hidden" name="reviews[{{ $index }}][rating]" class="product-rating-input" id="productRating{{ $index }}" required>
                                        <div class="product-rating-error-{{ $index }} text-red-500 text-sm hidden">Silakan berikan rating untuk produk</div>
                                    </div>

                                    <!-- Review Text for this product -->
                                    <div class="mt-4">
                                        <label for="komentar{{ $index }}" class="block text-sm font-medium text-gray-700 mb-2">
                                            Review untuk produk ini
                                        </label>
                                        <textarea name="reviews[{{ $index }}][komentar]"
                                                id="komentar{{ $index }}"
                                                rows="4"
                                                required
                                                maxlength="1000"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 resize-none"
                                                placeholder="Bagikan pengalaman Anda tentang produk ini..."></textarea>
                                        <div class="flex justify-between items-center mt-2">
                                            <span class="text-sm text-gray-500">Minimal 10 karakter</span>
                                            <span class="product-char-count-{{ $index }} text-sm text-gray-400">0/1000</span>
                                        </div>
                                        <div class="product-comment-error-{{ $index }} text-red-500 text-sm hidden">Review minimal 10 karakter</div>
                                    </div>

                                    <!-- Photo Upload for this product -->
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-500 mb-2">
                                            Foto Produk
                                            <span class="text-gray-400">(Opsional - dapat dilanjutkan tanpa foto)</span>
                                        </label>
                                        <input type="file"
                                               name="reviews[{{ $index }}][foto_review][]"
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-600 hover:file:bg-gray-100"
                                               multiple
                                               accept="image/*">
                                        <p class="mt-1 text-sm text-gray-400">PNG, JPG, JPEG maksimal 2MB (maksimal 5 foto)</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-600">Semua produk dalam pesanan ini sudah direview.</p>
                        </div>
                    @endif
                </div>

                <!-- Terms and Conditions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Syarat dan Ketentuan</h2>
                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-medium text-gray-800 mb-2">Pedoman Review</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Review harus berdasarkan pengalaman pribadi dengan produk</li>
                                <li>• Gunakan bahasa yang sopan dan tidak menyinggung</li>
                                <li>• Hindari konten yang mengandung SARA, spam, atau tidak relevan</li>
                                <li>• Foto yang diupload harus asli dan terkait dengan produk</li>
                                <li>• Kami berhak menghapus review yang melanggar ketentuan</li>
                            </ul>
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox"
                                   id="agreeTerms"
                                   name="agree_terms"
                                   required
                                   class="mt-1 h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                            <label for="agreeTerms" class="ml-3 text-sm text-gray-700">
                                Saya setuju dengan <a href="#" class="text-orange-600 hover:text-orange-500 font-medium">syarat dan ketentuan</a>
                                yang berlaku dan menyatakan bahwa review ini berdasarkan pengalaman pribadi saya.
                            </label>
                        </div>
                        <div id="termsError" class="text-red-500 text-sm hidden">Anda harus menyetujui syarat dan ketentuan</div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        @if($pesanan->reviewable_products->count() > 0)
                            <button type="submit"
                                    id="submitBtn"
                                    class="flex-1 bg-orange-500 hover:bg-orange-600 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                <span id="submitText">Kirim Review</span>
                            </button>
                        @else
                            <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}"
                               class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Kembali ke Detail Pesanan
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Review Guidelines -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-medium text-gray-800 mb-4">📝 Panduan Review</h3>
                <div class="space-y-3 text-sm text-gray-600">
                    <div class="flex items-start">
                        <span class="font-medium text-orange-600 mr-2">1.</span>
                        <span>Berikan rating yang sesuai dengan pengalaman Anda</span>
                    </div>
                    <div class="flex items-start">
                        <span class="font-medium text-orange-600 mr-2">2.</span>
                        <span>Tulis review minimal 10 karakter dengan bahasa yang jelas</span>
                    </div>
                    <div class="flex items-start">
                        <span class="font-medium text-orange-600 mr-2">3.</span>
                        <span>Sertakan foto produk untuk review yang lebih kredibel</span>
                    </div>
                    <div class="flex items-start">
                        <span class="font-medium text-orange-600 mr-2">4.</span>
                        <span>Gunakan bahasa yang sopan dan tidak menyinggung</span>
                    </div>
                </div>
            </div>

            <!-- Rating Guide -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="font-medium text-gray-800 mb-4">⭐ Panduan Rating</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center">
                        <span class="text-orange-400 mr-2">⭐⭐⭐⭐⭐</span>
                        <span class="text-gray-600">Sangat Puas</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-orange-400 mr-2">⭐⭐⭐⭐</span>
                        <span class="text-gray-600">Puas</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-orange-400 mr-2">⭐⭐⭐</span>
                        <span class="text-gray-600">Cukup</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-orange-400 mr-2">⭐⭐</span>
                        <span class="text-gray-600">Kurang Puas</span>
                    </div>
                    <div class="flex items-center">
                        <span class="text-orange-400 mr-2">⭐</span>
                        <span class="text-gray-600">Tidak Puas</span>
                    </div>
                </div>
            </div>

            <!-- Review Benefits -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                <h3 class="font-medium text-orange-800 mb-3">🎁 Manfaat Review</h3>
                <ul class="text-sm text-orange-700 space-y-2">
                    <li>• Membantu pembeli lain membuat keputusan</li>
                    <li>• Memberikan feedback untuk penjual</li>
                    <li>• Berkontribusi pada komunitas</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Variables
const productRatings = {};
const submitBtn = document.getElementById('submitBtn');
const submitText = document.getElementById('submitText');

// Utility function to check if all required fields are filled
function checkFormValidity() {
    const reviewItems = document.querySelectorAll('.review-product-item');
    let isValid = true;

    // Check only the required fields (rating and description)
    reviewItems.forEach((item, index) => {
        const ratingInput = document.getElementById(`productRating${index}`);
        const commentInput = document.getElementById(`komentar${index}`);

        if (ratingInput && commentInput) {  // Only check if elements exist
            const comment = commentInput.value.trim();
            console.log(`Product ${index}:`, {
                rating: ratingInput.value,
                commentLength: comment.length,
                isValidRating: !!ratingInput.value,
                isValidComment: comment.length >= 10
            });

            if (!ratingInput.value || comment.length < 10) {
                isValid = false;
            }
        }
    });

    // Check terms agreement
    const agreeTerms = document.getElementById('agreeTerms');
    if (agreeTerms) {
        console.log('Terms agreed:', agreeTerms.checked);
        if (!agreeTerms.checked) {
            isValid = false;
        }
    } else {
        isValid = false;
    }

    console.log('Form is valid:', isValid);

    // Update submit button state
    if (submitBtn) {
        submitBtn.disabled = !isValid;
        submitBtn.classList.toggle('opacity-50', !isValid);
        submitBtn.classList.toggle('cursor-not-allowed', !isValid);
    }
}

// Set product rating
function setProductRating(productIndex, rating) {
    productRatings[productIndex] = rating;
    const ratingInput = document.getElementById(`productRating${productIndex}`);
    if (ratingInput) {
        ratingInput.value = rating;
        console.log(`Setting rating for product ${productIndex}:`, rating);

        // Update stars visual
        const stars = document.querySelectorAll(`.product-rating-star.product-${productIndex}`);
        const ratingText = document.querySelector(`.product-rating-text-${productIndex}`);
        const ratingTexts = ['', 'Tidak Puas', 'Kurang Puas', 'Cukup', 'Puas', 'Sangat Puas'];

        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-orange-400');
            } else {
                star.classList.remove('text-orange-400');
                star.classList.add('text-gray-300');
            }
        });

        if (ratingText) {
            ratingText.textContent = ratingTexts[rating];
        }

        const errorElement = document.querySelector(`.product-rating-error-${productIndex}`);
        if (errorElement) {
            errorElement.classList.add('hidden');
        }

        checkFormValidity();
    }
}

// Setup form handling
document.addEventListener('DOMContentLoaded', function() {
    // Initially disable submit button
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }

    // Setup character counters and validation for each product review
    const textareas = document.querySelectorAll('textarea[id^="komentar"]');
    textareas.forEach(textarea => {
        const index = textarea.id.replace('komentar', '');

        // Add attributes back to textarea
        textarea.setAttribute('rows', '4');
        textarea.setAttribute('required', '');
        textarea.setAttribute('maxlength', '1000');
        textarea.classList.add('w-full', 'px-4', 'py-3', 'border', 'border-gray-300', 'rounded-lg', 'focus:ring-2', 'focus:ring-orange-500', 'focus:border-orange-500', 'transition-colors', 'duration-200', 'resize-none');

        textarea.addEventListener('input', function() {
            const charCount = document.querySelector(`.product-char-count-${index}`);
            const length = this.value.length;

            if (charCount) {
                charCount.textContent = `${length}/1000`;
                console.log(`Comment ${index} length:`, length);

                if (length >= 950) {
                    charCount.classList.add('text-orange-600');
                } else {
                    charCount.classList.remove('text-orange-600');
                }
            }

            const errorElement = document.querySelector(`.product-comment-error-${index}`);
            if (errorElement && length >= 10) {
                errorElement.classList.add('hidden');
            }

            checkFormValidity();
        });
    });

    // Setup terms checkbox
    const agreeTerms = document.getElementById('agreeTerms');
    if (agreeTerms) {
        agreeTerms.addEventListener('change', function() {
            const errorElement = document.getElementById('termsError');
            if (errorElement) {
                errorElement.classList.toggle('hidden', this.checked);
            }
            console.log('Terms checkbox changed:', this.checked);
            checkFormValidity();
        });
    }

    // Setup form validation
    const form = document.getElementById('reviewForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (validateForm()) {
                submitBtn.disabled = true;
                submitText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
                this.submit();
            }
        });
    }
});

// Form validation
function validateForm() {
    let isValid = true;
    const reviewItems = document.querySelectorAll('.review-product-item');

    reviewItems.forEach((item, index) => {
        // Check required fields only: rating and description
        const ratingInput = document.getElementById(`productRating${index}`);
        const commentInput = document.getElementById(`komentar${index}`);

        if (ratingInput && commentInput) {  // Only check if elements exist
            const comment = commentInput.value.trim();
            console.log(`Validating product ${index}:`, {
                rating: ratingInput.value,
                commentLength: comment.length
            });

            if (!ratingInput.value) {
                const errorElement = document.querySelector(`.product-rating-error-${index}`);
                if (errorElement) {
                    errorElement.classList.remove('hidden');
                }
                isValid = false;
            }

            if (comment.length < 10) {
                const errorElement = document.querySelector(`.product-comment-error-${index}`);
                if (errorElement) {
                    errorElement.classList.remove('hidden');
                }
                isValid = false;
            }
        }
    });

    // Check terms
    const agreeTerms = document.getElementById('agreeTerms');
    if (agreeTerms && !agreeTerms.checked) {
        const errorElement = document.getElementById('termsError');
        if (errorElement) {
            errorElement.classList.remove('hidden');
        }
        isValid = false;
    }

    console.log('Final validation result:', isValid);
    return isValid;
}
</script>
@endpush
