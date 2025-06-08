@extends('layouts.app')

@section('title', 'Ajukan Keluhan')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back button and title -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">Ajukan Keluhan</h2>
                <p class="mt-1 text-sm text-gray-500">Sampaikan keluhan Anda dan kami akan segera menanggapinya</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('keluhan.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white shadow rounded-lg">
            <form action="{{ route('keluhan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="p-6 space-y-6">
                    <!-- Jenis Keluhan -->
                    <div>
                        <label for="jenis_keluhan" class="block text-sm font-medium text-gray-700">Jenis Keluhan</label>
                        <select id="jenis_keluhan" name="jenis_keluhan" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md">
                            <option value="">Pilih Jenis Keluhan</option>
                            @foreach($jenisKeluhan as $key => $value)
                                <option value="{{ $key }}" {{ old('jenis_keluhan') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('jenis_keluhan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi Keluhan -->
                    <div>
                        <label for="keluhan" class="block text-sm font-medium text-gray-700">Deskripsi Keluhan</label>
                        <div class="mt-1">
                            <textarea id="keluhan" name="keluhan" rows="4" required
                                    class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Jelaskan keluhan Anda secara detail...">{{ old('keluhan') }}</textarea>
                        </div>
                        @error('keluhan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Bukti Gambar (Opsional)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-orange-500 transition-colors">
                            <div class="space-y-1 text-center">
                                <div class="flex flex-col items-center" id="upload-area">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="gambar" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500">
                                            <span>Upload file</span>
                                            <input id="gambar" name="gambar" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                                </div>
                                <!-- Preview Container -->
                                <div id="preview-container" class="mt-4 hidden">
                                    <img id="preview-image" class="mx-auto h-32 w-auto object-cover rounded-lg shadow-sm" src="" alt="Preview">
                                    <button type="button" id="remove-image" class="mt-2 text-sm text-red-600 hover:text-red-500">Hapus Gambar</button>
                                </div>
                            </div>
                        </div>
                        @error('gambar')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 flex items-center justify-end rounded-b-lg">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Kirim Keluhan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
const input = document.getElementById('gambar');
const previewContainer = document.getElementById('preview-container');
const previewImage = document.getElementById('preview-image');
const removeButton = document.getElementById('remove-image');
const uploadArea = document.getElementById('upload-area');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

uploadArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    input.files = files;
    handleFiles(files);
}

input.addEventListener('change', function() {
    handleFiles(this.files);
});

function handleFiles(files) {
    if (files.length > 0) {
        const file = files[0];
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }

            reader.readAsDataURL(file);
        }
    }
}

removeButton.addEventListener('click', function() {
    input.value = '';
    previewImage.src = '';
    previewContainer.classList.add('hidden');
});
</script>
@endpush
@endsection
