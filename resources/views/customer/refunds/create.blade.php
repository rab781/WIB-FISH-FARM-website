@extends('layouts.app')

@section('title', 'Ajukan Refund')

@push('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-material-ui/material-ui.css">
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

    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
    }

    .loading-overlay.active {
        display: flex !important;
        justify-content: center;
        align-items: center;
    }

    .loading-spinner {
        width: 48px;
        height: 48px;
        border: 4px solid #fff;
        border-radius: 50%;
        border-top-color: #f97316;
        border-right-color: #f97316;
        border-bottom-color: transparent;
        border-left-color: transparent;
        animation: spin 1s linear infinite;
        filter: drop-shadow(0 0 10px rgba(249, 115, 22, 0.5));
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .form-field-invalid {
        animation: shake 0.5s cubic-bezier(0.36, 0.07, 0.19, 0.97) both;
        border-color: #ef4444 !important;
        box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
    }

    @keyframes shake {
        10%, 90% { transform: translate3d(-1px, 0, 0); }
        20%, 80% { transform: translate3d(2px, 0, 0); }
        30%, 50%, 70% { transform: translate3d(-3px, 0, 0); }
        40%, 60% { transform: translate3d(3px, 0, 0); }
    }

    input[type="radio"]:checked + div {
        transform: scale(1.03);
        transition: all 0.2s ease;
    }

    .fade-in {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endpush

@section('content')
<!-- CSRF Meta Tag -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay" style="display: none;">
    <div class="flex flex-col items-center bg-white bg-opacity-90 p-8 rounded-xl shadow-2xl">
        <div class="loading-spinner mb-6"></div>
        <p class="text-gray-800 text-xl font-medium">Memproses pengajuan refund...</p>
        <p class="text-gray-600 mt-2">Mohon tunggu sebentar</p>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-block p-4 rounded-full bg-orange-100 mb-4">
            <i class="fas fa-undo text-4xl text-orange-600"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Ajukan Refund</h1>
        <p class="text-gray-600">Pesanan: <span class="font-medium text-orange-600">{{ $pesanan->nomor_pesanan }}</span></p>
        <div class="w-24 h-1 bg-orange-500 mx-auto my-4 rounded-full"></div>
    </div>

    <!-- Order Info -->
    <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 mb-8 hover:shadow-lg transition-all">
        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
            <i class="fas fa-shopping-bag text-orange-500 mr-2"></i>
            Informasi Pesanan
        </h3>

        <div class="flex items-center space-x-4 mb-4 p-3 bg-gray-50 rounded-lg">
            @if($pesanan->produk && $pesanan->produk->gambar)
            <img src="{{ asset('storage/' . $pesanan->produk->gambar) }}"
                 alt="{{ $pesanan->produk->nama }}"
                 class="w-24 h-24 object-cover rounded-lg shadow-sm">
            @else
            <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                <i class="fas fa-fish text-3xl text-gray-400"></i>
            </div>
            @endif
            <div>
                <div class="font-medium text-gray-900 text-lg">{{ $pesanan->produk->nama ?? 'Produk' }}</div>
                <div class="text-sm text-gray-600">Kuantitas: <span class="font-medium">{{ $pesanan->kuantitas }} ekor</span></div>
                <div class="text-sm text-gray-600">Total: <span class="font-medium text-orange-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span></div>
                <div class="text-sm text-gray-600">Status: <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">{{ $pesanan->status_pesanan }}</span></div>
            </div>
        </div>
    </div>

    <!-- Refund Form -->
    <div class="bg-white rounded-lg shadow-md border border-gray-100 p-6 hover:shadow-lg transition-all">
        <h3 class="text-lg font-medium text-gray-900 mb-6 flex items-center border-b border-gray-200 pb-4">
            <i class="fas fa-file-alt text-orange-500 mr-2"></i>
            Form Permintaan Refund
        </h3>

        <form id="refundForm" action="{{ route('refunds.store', $pesanan) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Reason Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Alasan Refund <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-all hover:border-orange-500 hover:shadow-sm">
                        <input type="radio" name="jenis_refund" value="kerusakan" required class="mr-3 accent-orange-500">
                        <div>
                            <div class="font-medium text-gray-900">Produk Rusak/Cacat</div>
                            <div class="text-sm text-gray-600">Ikan yang diterima dalam kondisi rusak atau cacat</div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-all hover:border-orange-500 hover:shadow-sm">
                        <input type="radio" name="jenis_refund" value="tidak_sesuai" required class="mr-3 accent-orange-500">
                        <div>
                            <div class="font-medium text-gray-900">Barang yang Diterima Salah</div>
                            <div class="text-sm text-gray-600">Ikan yang diterima tidak sesuai pesanan</div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-all hover:border-orange-500 hover:shadow-sm">
                        <input type="radio" name="jenis_refund" value="tidak_sesuai" required class="mr-3 accent-orange-500">
                        <div>
                            <div class="font-medium text-gray-900">Tidak Sesuai Deskripsi</div>
                            <div class="text-sm text-gray-600">Ikan tidak sesuai dengan deskripsi produk</div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-all hover:border-orange-500 hover:shadow-sm">
                        <input type="radio" name="jenis_refund" value="kematian_ikan" required class="mr-3 accent-orange-500">
                        <div>
                            <div class="font-medium text-gray-900">Ikan Mati saat Diterima</div>
                            <div class="text-sm text-gray-600">Ikan sudah mati saat paket diterima</div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-all hover:border-orange-500 hover:shadow-sm md:col-span-2">
                        <input type="radio" name="jenis_refund" value="lainnya" required class="mr-3 accent-orange-500">
                        <div>
                            <div class="font-medium text-gray-900">Lainnya</div>
                            <div class="text-sm text-gray-600">Alasan lain (akan dijelaskan di deskripsi)</div>
                        </div>
                    </label>
                </div>
                @error('jenis_refund')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Amount -->
            <div class="mb-6">
                <label for="jumlah_diminta" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Refund <span class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input type="number" id="amount" name="jumlah_diminta" value="{{ $pesanan->total_harga }}"
                           min="1" max="{{ $pesanan->total_harga }}" required
                           class="pl-10 w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm hover:border-orange-300">
                </div>
                <div class="mt-1 text-sm text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1 text-orange-500"></i>
                    Maksimal: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                </div>
                @error('jumlah_diminta')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="deskripsi_masalah" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Masalah <span class="text-red-500">*</span></label>
                <textarea id="description" name="deskripsi_masalah" rows="4" required
                          placeholder="Jelaskan secara detail masalah yang Anda alami dengan produk ini..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm hover:border-orange-300">{{ old('deskripsi_masalah') }}</textarea>
                <div class="mt-1 text-sm text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1 text-orange-500"></i>
                    Minimum 20 karakter. Semakin detail, semakin baik untuk proses review.
                </div>
                @error('deskripsi_masalah')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Evidence Photos -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Foto</label>
                <div class="upload-area" id="uploadArea">
                    <input type="file" id="photoInput" name="bukti_pendukung[]" multiple accept="image/*" class="hidden">
                    <div class="upload-content">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-700 mb-2">Klik atau seret foto ke sini</p>
                        <p class="text-sm text-gray-500">Maksimal 5 foto, ukuran masing-masing maksimal 5MB</p>
                        <p class="text-sm text-gray-500">Format: JPG, PNG, JPEG</p>
                    </div>
                </div>

                <!-- Photo Previews -->
                <div id="photoPreview" class="mt-4 flex flex-wrap gap-4"></div>

                <div class="mt-2 text-sm text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1 text-orange-500"></i>
                    Foto bukti akan membantu mempercepat proses review refund Anda
                </div>
                @error('bukti_pendukung')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('bukti_pendukung.*')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Refund Method -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Metode Refund Pilihan <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-all hover:border-orange-500 hover:shadow-sm">
                        <input type="radio" name="metode_refund" value="bank_transfer" required class="mr-3 accent-orange-500">
                        <div class="text-center w-full">
                            <i class="fas fa-university text-3xl text-blue-500 mb-2"></i>
                            <div class="font-medium text-gray-900">Transfer Bank</div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-all hover:border-orange-500 hover:shadow-sm">
                        <input type="radio" name="metode_refund" value="e_wallet" required class="mr-3 accent-orange-500">
                        <div class="text-center w-full">
                            <i class="fas fa-mobile-alt text-3xl text-green-500 mb-2"></i>
                            <div class="font-medium text-gray-900">E-Wallet</div>
                            <div class="text-sm text-gray-600">1-2 hari kerja</div>
                        </div>
                    </label>


                </div>
                <div class="mt-2 text-sm text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1 text-orange-500"></i>
                    Metode ini adalah preferensi Anda. Tim kami akan mengonfirmasi metode final saat memproses refund.
                </div>
                @error('metode_refund')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Refund Details -->
            <div class="mb-6">
                <label for="detail_refund" class="block text-sm font-medium text-gray-700 mb-2">Detail Refund <span class="text-red-500">*</span></label>
                <textarea id="detail_refund" name="detail_refund" rows="2" required
                          placeholder="Masukkan detail metode refund (contoh: nomor rekening bank, nomor e-wallet, dll)..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm hover:border-orange-300">{{ old('detail_refund') }}</textarea>
                <div class="mt-1 text-sm text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-1 text-orange-500"></i>
                    Untuk transfer bank, masukkan nama bank, nomor rekening, dan nama pemilik rekening.
                </div>
                @error('detail_refund')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Terms Agreement -->
            <div class="mb-6">
                <label class="flex items-start">
                    <input type="checkbox" name="agree_terms" required class="mt-1 mr-3 accent-orange-500">
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
            <div class="flex flex-wrap gap-4 justify-end mt-8 pt-4 border-t border-gray-200">
                <a href="{{ route('pesanan.show', $pesanan) }}"
                   class="inline-flex items-center px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all shadow-sm hover:shadow">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit"
                        id="submitRefund"
                        class="inline-flex items-center px-6 py-2.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-all focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 shadow-sm hover:shadow font-medium">
                    <i class="fas fa-paper-plane mr-2"></i>
                    <span>Ajukan Refund</span>
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('uploadArea');
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    const loadingOverlay = document.getElementById('loadingOverlay');
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
                Swal.fire({
                    title: 'Perhatian',
                    text: 'Maksimal 5 foto yang dapat diunggah',
                    icon: 'warning',
                    confirmButtonColor: '#f97316'
                });
                break;
            }

            if (!file.type.startsWith('image/')) {
                Swal.fire({
                    title: 'Format File Tidak Valid',
                    text: 'Hanya file gambar yang diperbolehkan (JPG, PNG, JPEG)',
                    icon: 'error',
                    confirmButtonColor: '#f97316'
                });
                continue;
            }

            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({
                    title: 'Ukuran File Terlalu Besar',
                    text: 'Ukuran file maksimal 5MB',
                    icon: 'error',
                    confirmButtonColor: '#f97316'
                });
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

    // Initialize modal
    function showModal(title, message, type = 'info') {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 z-50 overflow-y-auto';
        modal.innerHTML = `
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="relative bg-white rounded-lg max-w-md w-full">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">${title}</h3>
                            <button type="button" class="modal-close text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Close</span>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm text-gray-600">${message}</p>
                        </div>
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" class="modal-close px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);

        // Close modal handlers
        modal.querySelectorAll('.modal-close').forEach(button => {
            button.addEventListener('click', () => {
                modal.remove();
            });
        });

        // Close on background click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }

    const refundForm = document.getElementById('refundForm');
    const submitBtn = document.getElementById('submitRefund');
    const loadingOverlay = document.getElementById('loadingOverlay');

    refundForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validate form
        const reason = document.querySelector('input[name="jenis_refund"]:checked');
        if (!reason) {
            Swal.fire({
                title: 'Error',
                text: 'Silakan pilih alasan refund',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        const description = document.getElementById('description').value;
        if (description.length < 20) {
            Swal.fire({
                title: 'Error',
                text: 'Deskripsi masalah harus minimal 20 karakter',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        const amount = document.getElementById('amount').value;
        if (!amount || amount <= 0) {
            Swal.fire({
                title: 'Error',
                text: 'Jumlah refund harus lebih dari 0',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        const detailRefund = document.getElementById('detail_refund').value;
        if (!detailRefund || detailRefund.length < 5) {
            Swal.fire({
                title: 'Error',
                text: 'Detail refund harus diisi dengan lengkap',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        const refundMethod = document.querySelector('input[name="metode_refund"]:checked');
        if (!refundMethod) {
            Swal.fire({
                title: 'Error',
                text: 'Silakan pilih metode refund',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        const agreeTerms = document.querySelector('input[name="agree_terms"]:checked');
        if (!agreeTerms) {
            Swal.fire({
                title: 'Error',
                text: 'Anda harus menyetujui syarat dan ketentuan',
                icon: 'error',
                confirmButtonColor: '#f97316'
            });
            return;
        }

        // Show confirmation
        const result = await Swal.fire({
            title: 'Konfirmasi Pengajuan Refund',
            text: 'Apakah Anda yakin ingin mengajukan refund untuk pesanan ini? Pastikan semua informasi yang Anda berikan sudah benar.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Ajukan Refund',
            cancelButtonText: 'Batal',
            reverseButtons: true
        });

        if (!result.isConfirmed) {
            return;
        }

        // Show loading state
        loadingOverlay.style.display = 'flex';
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

        try {
            const formData = new FormData(this);

            // Add CSRF token to form data
            const token = document.querySelector('meta[name="csrf-token"]').content;

            // Make the fetch request without Content-Type header for multipart/form-data
            // Important: Don't include Content-Type when sending FormData with files
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                    // Omit Content-Type to let the browser set it with the boundary for multipart/form-data
                },
                credentials: 'same-origin'
            });

            // Parse the response
            let result;
            try {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    // Handle non-JSON responses
                    const text = await response.text();

                    if (text.includes("SQLSTATE[42S02]") || text.includes("Table") || text.includes("doesn't exist")) {
                        console.error("Database table error:", text);
                        throw new Error('Terjadi masalah dengan struktur database. Silakan hubungi administrator.');
                    }

                    try {
                        result = JSON.parse(text);
                    } catch (e) {
                        console.error("Response is not JSON:", text);

                        if (text.includes("<!DOCTYPE html>") || text.includes("<html")) {
                            // It's an HTML error page, likely a 500 error
                            throw new Error('Terjadi error pada server. Silakan coba lagi nanti.');
                        }

                        result = { message: 'Server returned an invalid response format' };
                    }
                }
            } catch (parseError) {
                console.error("Error parsing response:", parseError);
                throw new Error('Terjadi kesalahan saat memproses respons dari server');
            }

            if (response.ok) {
                await Swal.fire({
                    title: 'Sukses',
                    html: `
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-6">
                                <i class="fas fa-check-circle text-green-600 text-4xl"></i>
                            </div>
                            <p class="mb-4">Pengajuan refund berhasil dikirim. Tim kami akan segera memprosesnya.</p>
                            <p class="text-sm text-gray-600">Nomor Refund: <span class="font-medium">${result && result.refund_id ? result.refund_id : 'N/A'}</span></p>
                        </div>
                    `,
                    icon: 'success',
                    confirmButtonColor: '#f97316'
                });
                window.location.href = '{{ route("pesanan.show", $pesanan) }}';
            } else {
                console.error("Response error:", result);

                // Check for validation errors
                if (result && result.errors) {
                    const errorMessages = Object.values(result.errors).flat().join('<br>');
                    throw new Error(errorMessages);
                }
                throw new Error((result && result.message) ? result.message : 'Terjadi kesalahan saat memproses pengajuan refund');
            }
        } catch (error) {
            console.error("Form submission error:", error);

            // Check if it's a database table error
            let errorMsg = error.message || 'Terjadi kesalahan saat mengirim pengajuan refund';
            let errorTitle = 'Error';
            let errorHtml = '';

            if (errorMsg.includes("Table") && errorMsg.includes("doesn't exist")) {
                errorTitle = 'Database Error';
                errorHtml = `
                    <div class="text-left">
                        <p class="mb-2">Terjadi kesalahan pada database. Silakan hubungi administrator dengan detail berikut:</p>
                        <div class="bg-gray-100 p-2 rounded text-xs font-mono overflow-auto">
                            ${errorMsg.substring(0, 150)}...
                        </div>
                    </div>
                `;
            } else if (errorMsg.includes("<br>")) {
                // This is a validation error with multiple messages
                errorTitle = 'Validasi Gagal';
                errorHtml = `
                    <div class="text-left">
                        <p class="mb-2">Silakan perbaiki kesalahan berikut:</p>
                        <ul class="list-disc pl-4 text-sm">
                            ${errorMsg.split("<br>").map(msg => `<li>${msg}</li>`).join("")}
                        </ul>
                    </div>
                `;
            } else {
                errorHtml = errorMsg;
            }

            Swal.fire({
                title: errorTitle,
                html: errorHtml,
                icon: 'error',
                confirmButtonColor: '#f97316'
            });

            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Ajukan Refund';
        } finally {
            loadingOverlay.style.display = 'none';
        }
    });

    // Update amount display
    document.getElementById('amount').addEventListener('input', function() {
        const amount = parseInt(this.value) || 0;
        const maxAmount = {{ $pesanan->total_harga }};

        if (amount > maxAmount) {
            this.value = maxAmount;
            // Add a subtle highlight animation when correcting the value
            this.classList.add('form-field-invalid');
            setTimeout(() => {
                this.classList.remove('form-field-invalid');
            }, 800);
        }
    });
});
</script>
@endpush
