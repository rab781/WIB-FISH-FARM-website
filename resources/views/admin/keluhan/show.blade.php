@extends('admin.layouts.app')

@section('title', 'Detail Keluhan')

@section('content')
<div class="min-h-screen bg-dark-bg text-light-text p-6">
    <div class="container mx-auto max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary-orange">Detail Keluhan #{{ $keluhan->id }}</h1>
            <a href="{{ route('admin.keluhan.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                Kembali ke Daftar
            </a>
        </div>

        <div class="bg-dark-gray-secondary rounded-lg shadow-xl p-8 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-soft-white text-sm">Pengirim:</p>
                    <p class="text-lg font-semibold text-primary-orange">{{ $keluhan->user->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-soft-white text-sm">Email Pengirim:</p>
                    <p class="text-lg font-semibold text-primary-orange">{{ $keluhan->user->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-soft-white text-sm">Jenis Keluhan:</p>
                    <p class="text-xl font-bold text-light-text">{{ $keluhan->jenis_keluhan }}</p>
                </div>
                <div>
                    <p class="text-soft-white text-sm">Status:</p>
                    <span class="relative inline-block px-3 py-1 font-semibold leading-tight text-white">
                        @php
                            $statusClass = '';
                            switch($keluhan->status) {
                                case 'Belum Diproses': $statusClass = 'bg-yellow-500'; break;
                                case 'Sedang Diproses': $statusClass = 'bg-blue-500'; break;
                                case 'Selesai': $statusClass = 'bg-green-500'; break;
                                default: $statusClass = 'bg-gray-400'; // Fallback
                            }
                        @endphp
                        <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full {{ $statusClass }}"></span>
                        <span class="relative">{{ ucfirst($keluhan->status) }}</span>
                    </span>
                </div>
                <div>
                    <p class="text-soft-white text-sm">Tanggal Dibuat:</p>
                    <p class="text-light-text">{{ $keluhan->created_at->format('d M Y H:i') }}</p>
                </div>
                @if ($keluhan->respon_at)
                <div>
                    <p class="text-soft-white text-sm">Tanggal Ditanggapi:</p>
                    <p class="text-light-text">{{ $keluhan->respon_at->format('d M Y H:i') }}</p>
                </div>
                @endif
            </div>

            <hr class="border-t border-primary-orange my-6">

            <div class="mb-6">
                <h3 class="text-xl font-semibold text-primary-orange mb-3">Detail Keluhan:</h3>
                <p class="bg-soft-white text-dark-bg p-4 rounded-lg shadow-inner leading-relaxed">
                    {{ $keluhan->keluhan }}
                </p>
            </div>

            @if ($keluhan->gambar)
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-primary-orange mb-3">Lampiran:</h3>
                    <a href="{{ Storage::url('keluhan/' . $keluhan->gambar) }}" target="_blank" class="inline-flex items-center text-blue-400 hover:text-blue-300 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-.758l-.208.208A5.002 5.002 0 0113.828 10.172zm-7.536 7.536l-.208.208A5.002 5.002 0 0110.172 13.828l.208-.208m5.656-5.656a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-.758l-.208.208A5.002 5.002 0 0113.828 10.172z"></path></svg>
                        Lihat Lampiran
                    </a>
                </div>
            @endif

            <hr class="border-t border-primary-orange my-6">

            <h3 class="text-xl font-semibold text-primary-orange mb-3">Tanggapan Admin:</h3>
            @if ($keluhan->respon_admin)
                <div class="bg-light-text text-dark-bg p-4 rounded-lg shadow-md mb-4">
                    <p class="font-bold mb-2">Admin:</p>
                    <p class="leading-relaxed">{{ $keluhan->respon_admin }}</p>
                </div>
            @else
                <p class="text-soft-white mb-4">Belum ada tanggapan dari admin.</p>
            @endif

            <div class="mt-6">
                <h3 class="text-xl font-semibold text-primary-orange mb-3">Berikan Tanggapan / Ubah Status:</h3>
                <form action="{{ route('admin.keluhan.respond', $keluhan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="respon_admin" class="block text-light-text text-sm font-bold mb-2">Tanggapan Admin:</label>
                        <textarea name="respon_admin" id="respon_admin" rows="5" class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-dark-bg leading-tight focus:outline-none focus:ring-2 focus:ring-primary-orange focus:border-transparent bg-soft-white">{{ old('respon_admin', $keluhan->respon_admin) }}</textarea>
                        @error('respon_admin')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="status" class="block text-light-text text-sm font-bold mb-2">Ubah Status:</label>
                        <select name="status" id="status" class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-dark-bg leading-tight focus:outline-none focus:ring-2 focus:ring-primary-orange focus:border-transparent bg-soft-white">
                            <option value="Belum Diproses" {{ $keluhan->status == 'Belum Diproses' ? 'selected' : '' }}>Belum Diproses</option>
                            <option value="Sedang Diproses" {{ $keluhan->status == 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                            <option value="Selesai" {{ $keluhan->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="bg-primary-orange hover:bg-accent-orange text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300 ease-in-out">
                            Perbarui Keluhan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
