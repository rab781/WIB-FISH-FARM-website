@extends('layouts.app')

@section('title', 'Tulis Review')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Tulis Review</h1>
                <p class="text-gray-600">Bagikan pengalaman Anda dengan produk ini</p>
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
        <!-- Review Form -->
        <div class="lg:col-span-2">
            <form id="reviewForm" action="{{ route('customer.reviews.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="pesanan_id" value="{{ $pesanan->id ?? '' }}">
                <input type="hidden" name="produk_id" value="{{ $produk->id ?? '' }}">

                <!-- Product Information -->
                @if(isset($produk))
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Produk yang Direview</h2>
                        <div class="flex items-start space-x-4">
                            <img src="{{ asset('storage/' . $produk->gambar) }}"
                                 alt="{{ $produk->nama }}"
                                 class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-800 mb-1">{{ $produk->nama }}</h3>
                                <p class="text-sm text-gray-600 mb-2">{{ $produk->kategori ?? 'Kategori tidak tersedia' }}</p>
                                <p class="text-lg font-semibold text-orange-600">
                                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                </p>
                                @if(isset($pesanan))
                                    <p class="text-xs text-gray-500 mt-2">
                                        Pesanan #{{ $pesanan->id }} • {{ $pesanan->created_at->format('d M Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Rating -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Rating Produk <span class="text-red-500">*</span></h2>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 mr-4">Berikan rating:</span>
                            <div class="flex items-center space-x-1" id="ratingStars">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button"
                                            onclick="setRating({{ $i }})"
                                            class="rating-star text-gray-300 hover:text-orange-400 focus:outline-none transition-colors duration-200"
                                            data-rating="{{ $i }}">
                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </button>
                                @endfor
                            </div>
                            <span id="ratingText" class="ml-4 text-sm font-medium text-gray-600"></span>
                        </div>
                        <input type="hidden" name="rating" id="ratingInput" required>
                        <div id="ratingError" class="text-red-500 text-sm hidden">Silakan berikan rating untuk produk</div>
                    </div>
                </div>

                <!-- Review Text -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Review Anda <span class="text-red-500">*</span></h2>
                    <div class="space-y-4">
                        <div>
                            <label for="komentar" class="block text-sm font-medium text-gray-700 mb-2">
                                Ceritakan pengalaman Anda dengan produk ini
                            </label>
                            <textarea name="komentar"
                                      id="komentar"
                                      rows="6"
                                      required
                                      maxlength="1000"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors duration-200 resize-none"
                                      placeholder="Bagikan pengalaman Anda tentang kualitas produk, pelayanan, pengiriman, dan hal-hal lain yang menurut Anda penting untuk diketahui pembeli lain..."></textarea>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-sm text-gray-500">
                                    💡 Tips: Review yang detail dan jujur sangat membantu pembeli lain
                                </p>
                                <span id="charCount" class="text-sm text-gray-400">0/1000</span>
                            </div>
                            <div id="komentarError" class="text-red-500 text-sm hidden">Review minimal 10 karakter</div>
                        </div>
                    </div>
                </div>

                <!-- Photo Upload -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Foto Review (Opsional)</h2>
                    <div class="space-y-4">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-orange-400 transition-colors duration-200"
                             id="photoDropZone">
                            <div id="photoUploadArea">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium text-orange-600 cursor-pointer hover:text-orange-500" onclick="document.getElementById('photoInput').click()">
                                        Klik untuk upload
                                    </span>
                                    atau drag & drop foto di sini
                                </p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG maksimal 5MB per foto (maksimal 5 foto)</p>
                            </div>
                            <input type="file"
                                   id="photoInput"
                                   name="photos[]"
                                   multiple
                                   accept="image/*"
                                   class="hidden"
                                   onchange="handlePhotoUpload(this.files)">
                        </div>

                        <!-- Photo Preview -->
                        <div id="photoPreview" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4" style="display: none;"></div>

                        <div id="photoError" class="text-red-500 text-sm hidden"></div>

                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <h4 class="font-medium text-orange-800 mb-2">📸 Tips Foto Review yang Baik</h4>
                            <ul class="text-sm text-orange-700 space-y-1">
                                <li>• Foto produk dari berbagai sudut</li>
                                <li>• Tunjukkan detail kualitas produk</li>
                                <li>• Foto dalam kondisi pencahayaan yang baik</li>
                                <li>• Hindari foto yang blur atau gelap</li>
                                <li>• Sertakan foto kemasan jika relevan</li>
                            </ul>
                        </div>
                    </div>
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
                                <li>• Review akan dimoderasi sebelum dipublikasikan</li>
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
                        <button type="submit"
                                id="submitBtn"
                                class="flex-1 bg-orange-500 hover:bg-orange-600 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <span id="submitText">Kirim Review</span>
                        </button>
                        <button type="button"
                                onclick="saveDraft()"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            Simpan Draft
                        </button>
                    </div>
                    <p class="text-sm text-gray-500 mt-3 text-center">
                        Review Anda akan dimoderasi terlebih dahulu sebelum dipublikasikan
                    </p>
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
                    <div class="flex items-start">
                        <span class="font-medium text-orange-600 mr-2">5.</span>
                        <span>Review akan dimoderasi dalam 1-2 hari kerja</span>
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
                    <li>• Membangun reputasi sebagai reviewer</li>
                </ul>
            </div>

            <!-- Contact Support -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                <h3 class="font-medium text-gray-800 mb-3">💬 Butuh Bantuan?</h3>
                <p class="text-sm text-gray-600 mb-3">
                    Jika Anda mengalami masalah dalam menulis review, hubungi tim support kami.
                </p>
                <a href="#" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                    Hubungi Support →
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let selectedRating = 0;
let uploadedPhotos = [];
const maxPhotos = 5;
const maxFileSize = 5 * 1024 * 1024; // 5MB

// Rating functionality
function setRating(rating) {
    selectedRating = rating;
    document.getElementById('ratingInput').value = rating;

    const stars = document.querySelectorAll('.rating-star');
    const ratingText = document.getElementById('ratingText');
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

    ratingText.textContent = ratingTexts[rating];
    document.getElementById('ratingError').classList.add('hidden');
}

// Character counter
document.getElementById('komentar').addEventListener('input', function() {
    const charCount = document.getElementById('charCount');
    const length = this.value.length;
    charCount.textContent = `${length}/1000`;

    if (length >= 950) {
        charCount.classList.add('text-orange-600');
    } else {
        charCount.classList.remove('text-orange-600');
    }

    if (length >= 10) {
        document.getElementById('komentarError').classList.add('hidden');
    }
});

// Photo upload functionality
function handlePhotoUpload(files) {
    const photoError = document.getElementById('photoError');
    const fileArray = Array.from(files);

    // Clear previous errors
    photoError.classList.add('hidden');

    // Check total photos limit
    if (uploadedPhotos.length + fileArray.length > maxPhotos) {
        photoError.textContent = `Maksimal ${maxPhotos} foto yang dapat diupload`;
        photoError.classList.remove('hidden');
        return;
    }

    // Validate each file
    for (let file of fileArray) {
        if (!file.type.startsWith('image/')) {
            photoError.textContent = 'Hanya file gambar yang diperbolehkan';
            photoError.classList.remove('hidden');
            return;
        }

        if (file.size > maxFileSize) {
            photoError.textContent = 'Ukuran file maksimal 5MB';
            photoError.classList.remove('hidden');
            return;
        }
    }

    // Add files to uploaded photos
    fileArray.forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            uploadedPhotos.push({
                file: file,
                preview: e.target.result,
                name: file.name
            });
            updatePhotoPreview();
        };
        reader.readAsDataURL(file);
    });
}

function updatePhotoPreview() {
    const preview = document.getElementById('photoPreview');

    if (uploadedPhotos.length === 0) {
        preview.style.display = 'none';
        return;
    }

    preview.style.display = 'grid';
    preview.innerHTML = '';

    uploadedPhotos.forEach((photo, index) => {
        const div = document.createElement('div');
        div.className = 'relative group';
        div.innerHTML = `
            <img src="${photo.preview}"
                 alt="Preview ${index + 1}"
                 class="w-full h-24 object-cover rounded-lg border border-gray-200">
            <button type="button"
                    onclick="removePhoto(${index})"
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 rounded-b-lg truncate">
                ${photo.name}
            </div>
        `;
        preview.appendChild(div);
    });
}

function removePhoto(index) {
    uploadedPhotos.splice(index, 1);
    updatePhotoPreview();

    // Update file input
    const dataTransfer = new DataTransfer();
    uploadedPhotos.forEach(photo => {
        dataTransfer.items.add(photo.file);
    });
    document.getElementById('photoInput').files = dataTransfer.files;
}

// Drag and drop functionality
const dropZone = document.getElementById('photoDropZone');

dropZone.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('border-orange-400', 'bg-orange-50');
});

dropZone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('border-orange-400', 'bg-orange-50');
});

dropZone.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('border-orange-400', 'bg-orange-50');

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        handlePhotoUpload(files);
    }
});

// Form validation
function validateForm() {
    let isValid = true;

    // Validate rating
    if (selectedRating === 0) {
        document.getElementById('ratingError').classList.remove('hidden');
        isValid = false;
    }

    // Validate comment
    const komentar = document.getElementById('komentar').value.trim();
    if (komentar.length < 10) {
        document.getElementById('komentarError').classList.remove('hidden');
        isValid = false;
    }

    // Validate terms
    const agreeTerms = document.getElementById('agreeTerms').checked;
    if (!agreeTerms) {
        document.getElementById('termsError').classList.remove('hidden');
        isValid = false;
    }

    return isValid;
}

// Form submission
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!validateForm()) {
        return;
    }

    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');

    // Disable submit button
    submitBtn.disabled = true;
    submitText.textContent = 'Mengirim Review...';

    // Create FormData
    const formData = new FormData(this);

    // Add photos to FormData
    uploadedPhotos.forEach((photo, index) => {
        formData.append(`photos[${index}]`, photo.file);
    });

    // Submit form
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Review berhasil dikirim! Review akan dimoderasi terlebih dahulu sebelum dipublikasikan.');
            window.location.href = '/customer/reviews';
        } else {
            alert('Gagal mengirim review: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengirim review');
    })
    .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitText.textContent = 'Kirim Review';
    });
});

// Save draft functionality
function saveDraft() {
    const formData = {
        pesanan_id: document.querySelector('input[name="pesanan_id"]').value,
        produk_id: document.querySelector('input[name="produk_id"]').value,
        rating: selectedRating,
        komentar: document.getElementById('komentar').value,
        photos: uploadedPhotos.map(photo => photo.name)
    };

    localStorage.setItem('reviewDraft', JSON.stringify(formData));
    alert('Draft review berhasil disimpan');
}

// Load draft on page load
window.addEventListener('load', function() {
    const draft = localStorage.getItem('reviewDraft');
    if (draft) {
        try {
            const data = JSON.parse(draft);

            // Ask user if they want to load the draft
            if (confirm('Ditemukan draft review yang tersimpan. Apakah Anda ingin melanjutkan?')) {
                if (data.rating) {
                    setRating(data.rating);
                }
                if (data.komentar) {
                    document.getElementById('komentar').value = data.komentar;
                    document.getElementById('komentar').dispatchEvent(new Event('input'));
                }
            }
        } catch (error) {
            console.error('Error loading draft:', error);
        }
    }
});

// Clear draft when form is successfully submitted
function clearDraft() {
    localStorage.removeItem('reviewDraft');
}

// Auto-save draft every 30 seconds
setInterval(() => {
    if (selectedRating > 0 || document.getElementById('komentar').value.trim().length > 0) {
        saveDraft();
    }
}, 30000);
</script>
@endpush
