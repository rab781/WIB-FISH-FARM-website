@extends('layouts.customer')

@section('title', 'Ajukan Keluhan Baru')


@section('content')
<div class="min-h-screen bg-dark-bg text-light-text p-6">
    <div class="container mx-auto max-w-2xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary-orange">Ajukan Keluhan Baru</h1>
            <a href="{{ route('keluhan.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                Kembali ke Keluhan Saya
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow-md">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-dark-gray-secondary rounded-lg shadow-xl p-8">
            <form action="{{ route('keluhan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-5">
                    <label for="jenis_keluhan" class="block text-light-text text-sm font-bold mb-2">Jenis Keluhan:</label>
                    <select name="jenis_keluhan" id="jenis_keluhan" class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-dark-bg leading-tight focus:outline-none focus:ring-2 focus:ring-primary-orange focus:border-transparent bg-soft-white" required>
                        <option value="">Pilih Jenis Keluhan</option>
                        @foreach ($jenisKeluhan as $key => $value)
                            <option value="{{ $key }}" {{ old('jenis_keluhan') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_keluhan')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="keluhan" class="block text-light-text text-sm font-bold mb-2">Deskripsi Keluhan:</label>
                    <textarea name="keluhan" id="keluhan" rows="7" class="shadow-sm appearance-none border rounded w-full py-3 px-4 text-dark-bg leading-tight focus:outline-none focus:ring-2 focus:ring-primary-orange focus:border-transparent bg-soft-white" required>{{ old('keluhan') }}</textarea>
                    @error('keluhan')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="gambar" class="block text-light-text text-sm font-bold mb-2">Lampiran (opsional, gambar atau dokumen):</label>
                    <input type="file" name="gambar" id="gambar" class="block w-full text-sm text-light-text
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-primary-orange file:text-white
                        hover:file:bg-accent-orange file:transition file:duration-300 file:ease-in-out">
                    <p class="text-xs text-soft-white mt-1">Maksimum ukuran file: 2MB. Format: JPG, PNG, PDF.</p>
                    @error('gambar')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-primary-orange hover:bg-accent-orange text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300 ease-in-out">
                        Ajukan Keluhan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
