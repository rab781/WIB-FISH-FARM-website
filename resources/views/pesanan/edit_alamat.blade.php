@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-4 bg-gray-50 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900">Edit Alamat Pengiriman</h2>
    </div>
    <div class="p-6">
        <form action="{{ route('alamat.update', ['id' => $pesanan->id_pesanan]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <!-- Cari & Pilih Alamat dengan Autocomplete -->
                <div class="sm:col-span-6">
                    <label for="alamat_search" class="block text-sm font-medium text-gray-700 mb-1">Cari & Pilih Alamat</label>
                    <input type="text" id="alamat_search" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500 @error('alamat_id') border-red-300 @enderror" placeholder="Ketik untuk mencari (cth: Jakarta, Bandung, Surabaya)" value="{{ $pesanan->alamat_id ? $pesanan->alamat->full_address : '' }}">
                    <input type="hidden" id="alamat_id" name="alamat_id" value="{{ $pesanan->alamat_id }}">

                    <!-- Dropdown hasil pencarian -->
                    <div id="address-dropdown" class="address-dropdown"></div>

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
                <div class="sm:col-span-6">
                    <label for="alamat_jalan" class="block text-sm font-medium text-gray-700 mb-1">Alamat Jalan</label>
                    <textarea name="alamat_jalan" id="alamat_jalan" rows="3" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500 @error('alamat_jalan') border-red-300 @enderror">{{ old('alamat_jalan', $pesanan->alamat_jalan) }}</textarea>
                    @error('alamat_jalan')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No. HP -->
                <div class="sm:col-span-3">
                    <label for="no_hp" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $pesanan->no_hp) }}" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                    @error('no_hp')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ url()->previous() }}" class="mr-4 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Batal
                </a>
                <button type="submit" class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/address-autocomplete-fixed.js') }}"></script>
@endpush
