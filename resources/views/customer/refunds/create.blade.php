@extends('layouts.app')

@section('title', 'Ajukan Refund')

@push('styles')
<style>
    .upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .upload-area:hover {
        border-color: #f97316;
        background-color: #fff7ed;
    }

    .upload-area.dragover {
        border-color: #f97316;
        background-color: #fff7ed;
    }

    .preview-image {
        position: relative;
        display: inline-block;
    }

    .preview-image img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #d1d5db;
    }

    .preview-image .remove-btn {
        position: absolute;
        top: -8px;
        right: -8px;
        background-color: #ef4444;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Ajukan Refund</h1>
        <p class="text-gray-600">Pesanan: {{ $pesanan->nomor_pesanan }}</p>
    </div>

    <!-- Order Info -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pesanan</h3>

        <div class="flex items-center space-x-4 mb-4">
            @if($pesanan->produk && $pesanan->produk->gambar)
            <img src="{{ asset('storage/' . $pesanan->produk->gambar) }}"
                 alt="{{ $pesanan->produk->nama }}"
                 class="w-20 h-20 object-cover rounded-lg">
            @endif
            <div>
                <div class="font-medium text-gray-900">{{ $pesanan->produk->nama ?? 'Produk' }}</div>
                <div class="text-sm text-gray-600">Kuantitas: {{ $pesanan->kuantitas }} ekor</div>
                <div class="text-sm text-gray-600">Total: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
                <div class="text-sm text-gray-600">Status: {{ $pesanan->status_pesanan }}</div>
            </div>
        </div>
    </div>

    <!-- Refund Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-6">Form Permintaan Refund</h3>

        <form id="refundForm" action="{{ route('refunds.store', $pesanan) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Reason Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Alasan Refund <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="reason" value="defective" required class="mr-3">
                        <div>
                            <div class="font-medium text-gray-900">Produk Rusak/Cacat</div>
                            <div class="text-sm text-gray-600">Ikan yang diterima dalam kondisi rusak atau cacat</div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="reason" value="wrong_item" required class="mr-3">
                        <div>
                            <div class="font-medium text-gray-900">Barang yang Diterima Salah</div>
                            <div class="text-sm text-gray-600">Ikan yang diterima tidak sesuai pesanan</div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="reason" value="not_as_described" required class="mr-3">
                        <div>
                            <div class="font-medium text-gray-900">Tidak Sesuai Deskripsi</div>
                            <div class="text-sm text-gray-600">Ikan tidak sesuai dengan deskripsi produk</div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="reason" value="dead_fish" required class="mr-3">
                        <div>
                            <div class="font-medium text-gray-900">Ikan Mati saat Diterima</div>
                            <div class="text-sm text-gray-600">Ikan sudah mati saat paket diterima</div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 md:col-span-2">
                        <input type="radio" name="reason" value="other" required class="mr-3">
                        <div>
                            <div class="font-medium text-gray-900">Lainnya</div>
                            <div class="text-sm text-gray-600">Alasan lain (akan dijelaskan di deskripsi)</div>
                        </div>
                    </label>
                </div>
                @error('reason')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div class="mb-6">
                <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Refund <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input type="number" id="amount" name="amount" value="{{ $pesanan->total_harga }}"
                           min="1" max="{{ $pesanan->total_harga }}" required
                           class="pl-10 w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                </div>
                <div class="mt-1 text-sm text-gray-500">Maksimal: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
                @error('amount')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Masalah <span class="text-red-500">*</span></label>
                <textarea id="description" name="description" rows="4" required
                          placeholder="Jelaskan secara detail masalah yang Anda alami dengan produk ini..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">{{ old('description') }}</textarea>
                <div class="mt-1 text-sm text-gray-500">Minimum 20 karakter. Semakin detail, semakin baik untuk proses review.</div>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Evidence Photos -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Foto</label>
                <div class="upload-area" id="uploadArea">
                    <input type="file" id="photoInput" name="evidence_photos[]" multiple accept="image/*" class="hidden">
                    <div class="upload-content">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-700 mb-2">Klik atau seret foto ke sini</p>
                        <p class="text-sm text-gray-500">Maksimal 5 foto, ukuran masing-masing maksimal 5MB</p>
                        <p class="text-sm text-gray-500">Format: JPG, PNG, JPEG</p>
                    </div>
                </div>

                <!-- Photo Previews -->
                <div id="photoPreview" class="mt-4 flex flex-wrap gap-4"></div>

                <div class="mt-2 text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Foto bukti akan membantu mempercepat proses review refund Anda
                </div>
                @error('evidence_photos')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('evidence_photos.*')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Refund Method -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Metode Refund Pilihan</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="preferred_refund_method" value="bank_transfer" class="mr-3">
                        <div class="text-center w-full">
                            <i class="fas fa-university text-2xl text-blue-500 mb-2"></i>
                            <div class="font-medium text-gray-900">Transfer Bank</div>
                            <div class="text-sm text-gray-600">3-5 hari kerja</div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="preferred_refund_method" value="wallet" class="mr-3">
                        <div class="text-center w-full">
                            <i class="fas fa-mobile-alt text-2xl text-green-500 mb-2"></i>
                            <div class="font-medium text-gray-900">E-Wallet</div>
                            <div class="text-sm text-gray-600">1-2 hari kerja</div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="preferred_refund_method" value="store_credit" class="mr-3">
                        <div class="text-center w-full">
                            <i class="fas fa-gift text-2xl text-orange-500 mb-2"></i>
                            <div class="font-medium text-gray-900">Kredit Toko</div>
                            <div class="text-sm text-gray-600">Instan</div>
                        </div>
                    </label>
                </div>
                <div class="mt-2 text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Metode ini adalah preferensi Anda. Tim kami akan mengonfirmasi metode final saat memproses refund.
                </div>
            </div>

            <!-- Terms Agreement -->
            <div class="mb-6">
                <label class="flex items-start">
                    <input type="checkbox" name="agree_terms" required class="mt-1 mr-3">
                    <span class="text-sm text-gray-700">
                        Saya menyetujui <a href="#" class="text-orange-600 hover:text-orange-800">syarat dan ketentuan refund</a>
                        dan memahami bahwa informasi yang saya berikan adalah benar dan dapat dipertanggungjawabkan.
                    </span>
                </label>
                @error('agree_terms')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-wrap gap-4 justify-end">
                <a href="{{ route('pesanan.show', $pesanan) }}"
                   class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Ajukan Refund
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    let selectedFiles = [];

    // Click to upload
    uploadArea.addEventListener('click', function() {
        photoInput.click();
    });

    // Drag and drop events
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    // File input change
    photoInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        for (let file of files) {
            if (selectedFiles.length >= 5) {
                alert('Maksimal 5 foto yang dapat diunggah');
                break;
            }

            if (!file.type.startsWith('image/')) {
                alert('Hanya file gambar yang diperbolehkan');
                continue;
            }

            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file maksimal 5MB');
                continue;
            }

            selectedFiles.push(file);
            displayPreview(file, selectedFiles.length - 1);
        }
        updateFileInput();
    }

    function displayPreview(file, index) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.createElement('div');
            previewDiv.className = 'preview-image';
            previewDiv.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <div class="remove-btn" onclick="removePhoto(${index})">
                    <i class="fas fa-times"></i>
                </div>
            `;
            photoPreview.appendChild(previewDiv);
        };
        reader.readAsDataURL(file);
    }

    window.removePhoto = function(index) {
        selectedFiles.splice(index, 1);
        updateFileInput();
        refreshPreviews();
    };

    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        photoInput.files = dt.files;
    }

    function refreshPreviews() {
        photoPreview.innerHTML = '';
        selectedFiles.forEach((file, index) => {
            displayPreview(file, index);
        });
    }

    // Form validation
    document.getElementById('refundForm').addEventListener('submit', function(e) {
        const description = document.getElementById('description').value;
        if (description.length < 20) {
            e.preventDefault();
            alert('Deskripsi masalah harus minimal 20 karakter');
            return;
        }

        const reason = document.querySelector('input[name="reason"]:checked');
        if (!reason) {
            e.preventDefault();
            alert('Silakan pilih alasan refund');
            return;
        }

        const amount = document.getElementById('amount').value;
        const maxAmount = {{ $pesanan->total_harga }};
        if (amount > maxAmount) {
            e.preventDefault();
            alert('Jumlah refund tidak boleh melebihi total pesanan');
            return;
        }

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    });

    // Update amount display
    document.getElementById('amount').addEventListener('input', function() {
        const amount = parseInt(this.value) || 0;
        const maxAmount = {{ $pesanan->total_harga }};

        if (amount > maxAmount) {
            this.value = maxAmount;
        }
    });
});
</script>
@endpush
