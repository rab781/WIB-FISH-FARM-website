@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex items-center mb-6">
        <a href="{{ url()->previous() }}" class="flex items-center text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Kembali</span>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 ml-4">Tambah Alamat Pengiriman</h1>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Alamat Pengiriman</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('alamat.simpan') }}" method="POST">
                @csrf

                <!-- Hidden fields for selected cart items -->
                @foreach(request('selected_items') as $item)
                    <input type="hidden" name="selected_items[]" value="{{ $item }}">
                @endforeach

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <!-- Cari Alamat (RajaOngkir) -->
                    <div class="sm:col-span-6">
                        <label for="alamat_search" class="block text-sm font-medium text-gray-700">Cari Alamat</label>
                        <div class="mt-1">
                            <input type="text" id="alamat_search" name="alamat_search" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Ketik nama kota/kabupaten untuk mencari">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Cari alamat berdasarkan nama kota/kabupaten</p>
                    </div>

                    <!-- Pilih Alamat dari Hasil Pencarian -->
                    <div class="sm:col-span-6">
                        <label for="alamat_id" class="block text-sm font-medium text-gray-700">Pilih Alamat</label>
                        <div class="mt-1">
                            <select id="alamat_id" name="alamat_id" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                <option value="">Pilih alamat dari hasil pencarian</option>
                                @if($user->alamat)
                                    <option value="{{ $user->alamat->id }}" selected>
                                        {{ $user->alamat->kecamatan }}, {{ $user->alamat->tipe }} {{ $user->alamat->kabupaten }}, {{ $user->alamat->provinsi }} {{ $user->alamat->kode_pos }}
                                    </option>
                                @endif
                            </select>
                        </div>
                        @error('alamat_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat Jalan -->
                    <div class="sm:col-span-6">
                        <label for="alamat_jalan" class="block text-sm font-medium text-gray-700">Alamat Lengkap (Jalan, Nomor Rumah, RT/RW)</label>
                        <div class="mt-1">
                            <textarea id="alamat_jalan" name="alamat_jalan" rows="3" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md" required>{{ old('alamat_jalan', $user->alamat_jalan) }}</textarea>
                        </div>
                        @error('alamat_jalan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No. HP -->
                    <div class="sm:col-span-3">
                        <label for="no_hp" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <div class="mt-1">
                            <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                        </div>
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
                        Simpan Alamat & Lanjutkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/address-search-inline.js') }}"></script>
@endpush
@endsection
