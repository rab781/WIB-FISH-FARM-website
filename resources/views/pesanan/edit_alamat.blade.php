@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-center">
        <a href="{{ url()->previous() }}" class="flex items-center text-gray-600 hover:text-gray-800 mr-4">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Edit Alamat Pengiriman</h1>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <div class="p-5 bg-gradient-to-r from-orange-50 to-amber-50 border-b border-orange-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-orange-100 rounded-full p-2">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Alamat</h2>
                    <p class="text-sm text-gray-600">Pastikan alamat yang Anda masukkan benar dan lengkap</p>
                </div>
            </div>
        </div>
        <div class="p-6">
        <form action="{{ route('pesanan.update-alamat', ['id' => $pesanan->id_pesanan]) }}" method="POST" id="editAlamatForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $pesanan->id_pesanan }}" />

            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Cari & Pilih Alamat dengan Autocomplete -->
                <div class="sm:col-span-6 bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                    <label for="alamat_search" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                        <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari Lokasi Anda
                    </label>
                    <div class="relative">
                        <input type="text"
                            id="alamat_search"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('alamat_id') border-red-300 @enderror"
                            placeholder="Ketik nama kota atau kecamatan (cth: Jakarta Selatan, Bandung)"
                            value="{{ $pesanan->alamat_id ? $pesanan->alamat->full_address : '' }}"
                        >
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                        </div>
                    </div>
                    <input type="hidden" id="alamat_id" name="alamat_id" value="{{ $pesanan->alamat_id }}">

                    <!-- Dropdown hasil pencarian dengan style yang ditingkatkan -->
                    <div id="address-dropdown" class="address-dropdown mt-1 bg-white rounded-md shadow-lg border border-gray-200"></div>

                    <!-- Tampilan alamat yang terpilih -->
                    <div class="selected-address mt-2" style="{{ $pesanan->alamat_id ? '' : 'display:none' }}">
                        <span id="selected-address-display" class="text-sm text-gray-700">{{ $pesanan->alamat_id ? $pesanan->alamat->full_address : '' }}</span>
                        <span id="clear-address" class="clear-address text-red-500 cursor-pointer ml-2" title="Hapus alamat">×</span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Ketik minimal 3 karakter untuk mencari alamat</p>
                    @error('alamat_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat Jalan -->
                <div class="sm:col-span-6 mt-6">
                    <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                        <label for="alamat_jalan" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Detail Alamat Lengkap
                        </label>
                        <div class="relative">
                            <textarea
                                name="alamat_jalan"
                                id="alamat_jalan"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('alamat_jalan') border-red-300 @enderror"
                                placeholder="Masukkan detail alamat lengkap (nama jalan, nomor rumah, RT/RW, dll)"
                            >{{ old('alamat_jalan', $pesanan->alamat_jalan) }}</textarea>
                        </div>
                        @error('alamat_jalan')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <!-- No. HP -->
                <div class="sm:col-span-6 mt-6">
                    <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                        <label for="no_hp" class="flex items-center text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            Nomor Telepon
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                id="no_hp"
                                name="no_hp"
                                value="{{ old('no_hp', $pesanan->no_hp) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                placeholder="Masukkan nomor telepon aktif"
                                required
                            >
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        @error('no_hp')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/address-autocomplete-fixed.js') }}"></script>
@endpush
