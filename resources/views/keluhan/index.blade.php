@extends('layouts.app')

@section('title', 'Keluhan Saya')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    @if (session('success'))
        <div class="rounded-md bg-green-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-4 py-5 sm:p-6">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Keluhan Saya</h2>
                    <p class="mt-1 text-sm text-gray-600">Lihat status dan riwayat keluhan yang telah Anda ajukan</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('keluhan.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajukan Keluhan
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($keluhans->isEmpty())
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada keluhan</h3>
                <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">
                    Anda belum pernah mengajukan keluhan. Jika Anda memiliki masalah atau pertanyaan, silakan ajukan keluhan baru.
                </p>
                <div class="mt-6">
                    <a href="{{ route('keluhan.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Ajukan Keluhan Sekarang
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="space-y-4">
            @foreach($keluhans as $keluhan)
                <div class="bg-white shadow rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $keluhan->status === 'Belum Diproses' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($keluhan->status === 'Sedang Diproses' ? 'bg-blue-100 text-blue-800' : 
                                           ($keluhan->status === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2
                                            {{ $keluhan->status === 'Belum Diproses' ? 'text-yellow-400' : 
                                               ($keluhan->status === 'Sedang Diproses' ? 'text-blue-400' : 
                                               ($keluhan->status === 'Selesai' ? 'text-green-400' : 'text-gray-400')) }}" 
                                             fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        {{ $keluhan->status }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        {{ $keluhan->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>

                                <h3 class="mt-3 text-lg font-medium text-gray-900">{{ $keluhan->jenis_keluhan }}</h3>
                                <div class="mt-2 text-sm text-gray-600 line-clamp-2">
                                    {{ $keluhan->keluhan }}
                                </div>

                                @if($keluhan->respon_admin)
                                    <div class="mt-3 text-sm">
                                        <span class="text-orange-600 font-medium">Ditanggapi admin</span>
                                    </div>
                                @endif
                            </div>

                            <div class="ml-4">
                                <a href="{{ route('keluhan.show', $keluhan->id) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    Detail
                                </a>
                            </div>
                        </div>

                        @if($keluhan->gambar)
                            <div class="mt-4 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                </svg>
                                <span>Bukti gambar terlampir</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $keluhans->links() }}
        </div>
    @endif
</div>
@endsection
