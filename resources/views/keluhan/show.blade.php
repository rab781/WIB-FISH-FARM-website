@extends('layouts.customer')
@section('title', 'Detail Keluhan')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Navigation and Header -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl">Detail Keluhan #{{ $keluhan->id }}</h2>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol role="list" class="flex items-center space-x-4">
                        <li>
                            <a href="{{ route('keluhan.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Keluhan Saya</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-700">Detail Keluhan</span>
                            </div>
                        </li>
                    </ol>
                </nav>
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

        <!-- Main Content -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Status and Info Section -->
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $keluhan->status === 'Belum Diproses' ? 'bg-yellow-100 text-yellow-800' :
                               ($keluhan->status === 'Sedang Diproses' ? 'bg-blue-100 text-blue-800' :
                               ($keluhan->status === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                            <svg class="-ml-1 mr-1.5 h-2 w-2
                                {{ $keluhan->status === 'Belum Diproses' ? 'text-yellow-400' :
                                   ($keluhan->status === 'Sedang Diproses' ? 'text-blue-400' :
                                   ($keluhan->status === 'Selesai' ? 'text-green-400' : 'text-gray-400')) }}"
                                 fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            {{ $keluhan->status }}
                        </span>
                        <span class="text-sm text-gray-500">
                            Dibuat: {{ $keluhan->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                    @if($keluhan->respon_at)
                    <span class="text-sm text-gray-500">
                        Ditanggapi: {{ $keluhan->respon_at->format('d M Y, H:i') }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Complaint Details -->
            <div class="px-6 py-6 space-y-6">
                <!-- Type -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Jenis Keluhan</h3>
                    <p class="mt-2 text-lg text-gray-900">{{ $keluhan->jenis_keluhan }}</p>
                </div>

                <!-- Description -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Deskripsi Keluhan</h3>
                    <div class="mt-2 prose prose-sm max-w-none text-gray-900">
                        {{ $keluhan->keluhan }}
                    </div>
                </div>

                <!-- Attachment -->
                @if($keluhan->gambar)
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Bukti Gambar</h3>
                    <div class="mt-2">
                        <a href="{{ Storage::url('keluhan/' . $keluhan->gambar) }}"
                           target="_blank"
                           class="inline-block">
                            <img src="{{ Storage::url('keluhan/' . $keluhan->gambar) }}"
                                 alt="Bukti Keluhan"
                                 class="h-48 w-auto object-cover rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        </a>
                    </div>
                </div>
                @endif

                <!-- Admin Response -->
                @if($keluhan->respon_admin)
                <div class="relative">
                    <div class="absolute inset-0 flex items-center" aria-hidden="true">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-start">
                        <span class="pr-3 bg-white text-sm text-gray-500">
                            Tanggapan Admin
                        </span>
                    </div>
                </div>
                <div class="bg-orange-50 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-orange-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-4 4a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-900">{{ $keluhan->respon_admin }}</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-4 4a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-700">Admin belum memberikan tanggapan untuk keluhan ini.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
