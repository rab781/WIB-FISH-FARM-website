@extends('admin.layouts.app')

@section('title', 'Detail Keluhan')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                        Detail Keluhan #{{ $keluhan->id }}
                    </h2>
                    <span class="ml-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $keluhan->status === 'Belum Diproses' ? 'bg-yellow-100 text-yellow-800' :
                           ($keluhan->status === 'Sedang Diproses' ? 'bg-blue-100 text-blue-800' :
                           ($keluhan->status === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                        {{ $keluhan->status }}
                    </span>
                </div>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol role="list" class="flex items-center space-x-4">
                        <li>
                            <a href="{{ route('admin.keluhan.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Semua Keluhan</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-500">Detail Keluhan</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.keluhan.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <!-- Customer Info -->
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Pelanggan</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Detail pengirim keluhan.</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Dibuat pada</p>
                        <p class="text-sm font-medium text-gray-900">{{ $keluhan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $keluhan->user->name ?? 'N/A' }}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $keluhan->user->email ?? 'N/A' }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Jenis Keluhan</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $keluhan->jenis_keluhan }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Complaint Details -->
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Detail Keluhan</h3>
                <div class="mt-4 prose prose-sm max-w-none text-gray-900">
                    {{ $keluhan->keluhan }}
                </div>
            </div>

            <!-- Attachments -->
            @if($keluhan->gambar)
            <div class="px-4 py-5 sm:px-6 border-t border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Lampiran</h3>
                <div class="mt-4">
                    <a href="{{ Storage::url('keluhan/' . $keluhan->gambar) }}"
                       target="_blank"
                       class="inline-block group">
                        <img src="{{ Storage::url('keluhan/' . $keluhan->gambar) }}"
                             alt="Bukti Keluhan"
                             class="h-48 w-auto object-cover rounded-lg shadow-sm group-hover:shadow-md transition-shadow">
                        <span class="mt-2 inline-flex items-center text-sm text-orange-600 group-hover:text-orange-700">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z" />
                                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" />
                            </svg>
                            Buka di tab baru
                        </span>
                    </a>
                </div>
            </div>
            @endif

            <!-- Admin Response Form -->
            <div class="px-4 py-5 sm:px-6 border-t border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Tanggapan Admin</h3>
                <form action="{{ route('admin.keluhan.respond', $keluhan->id) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label for="respon_admin" class="block text-sm font-medium text-gray-700">Isi Tanggapan</label>
                            <div class="mt-1">
                                <textarea id="respon_admin"
                                          name="respon_admin"
                                          rows="4"
                                          class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                          placeholder="Tulis tanggapan untuk keluhan ini...">{{ old('respon_admin', $keluhan->respon_admin) }}</textarea>
                            </div>
                            @error('respon_admin')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Keluhan</label>
                            <select id="status"
                                    name="status"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md">
                                <option value="Belum Diproses" {{ $keluhan->status == 'Belum Diproses' ? 'selected' : '' }}>Belum Diproses</option>
                                <option value="Sedang Diproses" {{ $keluhan->status == 'Sedang Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                                <option value="Selesai" {{ $keluhan->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Simpan Tanggapan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
