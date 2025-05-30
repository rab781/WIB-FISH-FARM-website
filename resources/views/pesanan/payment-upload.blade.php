@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}" class="text-orange-600 hover:text-orange-700 mr-3">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <h1 class="text-2xl font-semibold text-gray-800">Upload Bukti Pembayaran</h1>
            </div>
            <p class="text-gray-600">Pesanan #{{ $pesanan->id_pesanan }}</p>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 mb-4">Ringkasan Pesanan</h2>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Produk:</span>
                        <span class="font-medium">Rp {{ number_format($pesanan->total_harga - ($pesanan->ongkir_biaya ?? 0), 0, ',', '.') }}</span>
                    </div>
                    @if($pesanan->ongkir_biaya)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ongkos Kirim:</span>
                        <span class="font-medium">Rp {{ number_format($pesanan->ongkir_biaya, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="border-t pt-3">
                        <div class="flex justify-between text-lg font-semibold">
                            <span>Total Pembayaran:</span>
                            <span class="text-orange-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-blue-800 mb-3">
                <i class="fas fa-info-circle mr-2"></i>
                Instruksi Pembayaran
            </h3>
            <div class="text-blue-700 space-y-2">
                <p><strong>1. Transfer ke rekening berikut:</strong></p>
                <div class="bg-white rounded p-4 border border-blue-200">
                    <div class="space-y-1">
                        <p><strong>Bank BCA</strong></p>
                        <p>No. Rekening: <strong>1234567890</strong></p>
                        <p>Atas Nama: <strong>Toko Ikan Digital</strong></p>
                    </div>
                </div>
                <p><strong>2. Nominal transfer:</strong> Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                <p><strong>3. Upload bukti transfer di bawah ini</strong></p>
                <p class="text-sm"><strong>Catatan:</strong> Pastikan nominal transfer sesuai dengan total pembayaran</p>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Upload Bukti Pembayaran</h3>

                @if($pesanan->bukti_pembayaran)
                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-yellow-800 mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Anda sudah mengunggah bukti pembayaran sebelumnya. Upload file baru akan mengganti yang lama.
                    </p>
                    <div class="mt-2">
                        <img src="{{ asset($pesanan->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="max-w-xs rounded border">
                    </div>
                </div>
                @endif

                <form action="{{ route('pesanan.payment', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-6">
                        <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih File Bukti Transfer <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-orange-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="bukti_pembayaran" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                        <span>Upload file</span>
                                        <input id="bukti_pembayaran" name="bukti_pembayaran" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg" required>
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG maksimal 2MB</p>
                            </div>
                        </div>
                        @error('bukti_pembayaran')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preview Area -->
                    <div id="preview-area" class="hidden mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preview:</label>
                        <img id="preview-image" class="max-w-xs rounded border shadow-sm" alt="Preview">
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('pesanan.show', $pesanan->id_pesanan) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium py-3 px-6 rounded-lg transition duration-200">
                            Batal
                        </a>
                        <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                            <i class="fas fa-upload mr-2"></i>
                            Upload Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('bukti_pembayaran');
    const previewArea = document.getElementById('preview-area');
    const previewImage = document.getElementById('preview-image');

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewArea.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            previewArea.classList.add('hidden');
        }
    });

    // Drag and drop functionality
    const dropZone = document.querySelector('.border-dashed');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-orange-400', 'bg-orange-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-orange-400', 'bg-orange-50');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            fileInput.files = files;
            // Trigger change event
            const event = new Event('change', { bubbles: true });
            fileInput.dispatchEvent(event);
        }
    }
});
</script>
@endsection
