@extends('layouts.customer')
@section('title', 'Keluhan Saya')

@section('content')
<div class="min-h-screen bg-dark-bg text-light-text p-6">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary-orange">Keluhan Saya</h1>
            <a href="{{ route('keluhan.create') }}" class="bg-primary-orange hover:bg-accent-orange text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                Ajukan Keluhan Baru
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow-md">
                {{ session('success') }}
            </div>
        @endif

        @if ($keluhans->isEmpty())
            <div class="bg-dark-gray-secondary p-8 rounded-lg shadow-xl text-center">
                <p class="text-soft-white text-lg">Anda belum memiliki keluhan yang diajukan.</p>
                <a href="{{ route('keluhan.create') }}" class="mt-4 inline-block bg-primary-orange hover:bg-accent-orange text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300 ease-in-out">
                    Ajukan Keluhan Pertama Anda
                </a>
            </div>
        @else
            <div class="hidden md:block bg-dark-gray-secondary rounded-lg shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-primary-orange text-dark-bg uppercase text-sm font-semibold">
                                <th class="px-5 py-3 border-b-2 border-accent-orange text-left text-light-text">
                                    ID Keluhan
                                </th>
                                <th class="px-5 py-3 border-b-2 border-accent-orange text-left text-light-text">
                                    Jenis Keluhan
                                </th>
                                <th class="px-5 py-3 border-b-2 border-accent-orange text-left text-light-text">
                                    Status
                                </th>
                                <th class="px-5 py-3 border-b-2 border-accent-orange text-left text-light-text">
                                    Tanggal Dibuat
                                </th>
                                <th class="px-5 py-3 border-b-2 border-accent-orange text-center text-light-text">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($keluhans as $keluhan)
                                <tr class="hover:bg-dark-bg transition duration-200">
                                    <td class="px-5 py-3 border-b border-soft-white text-sm text-light-text">
                                        {{ $keluhan->id }}
                                    </td>
                                    <td class="px-5 py-3 border-b border-soft-white text-sm text-light-text">
                                        {{ Str::limit($keluhan->jenis_keluhan, 50) }}
                                    </td>
                                    <td class="px-5 py-3 border-b border-soft-white text-sm text-light-text">
                                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                                            @php
                                                $statusClass = '';
                                                switch($keluhan->status) {
                                                    case 'Belum Diproses': $statusClass = 'bg-yellow-500'; break;
                                                    case 'Sedang Diproses': $statusClass = 'bg-blue-500'; break;
                                                    case 'Selesai': $statusClass = 'bg-green-500'; break;
                                                    default: $statusClass = 'bg-gray-400';
                                                }
                                            @endphp
                                            <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full {{ $statusClass }}"></span>
                                            <span class="relative text-white">{{ ucfirst($keluhan->status) }}</span>
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 border-b border-soft-white text-sm text-light-text">
                                        {{ $keluhan->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-5 py-3 border-b border-soft-white text-sm text-center">
                                        <a href="{{ route('keluhan.show', $keluhan->id) }}" class="text-primary-orange hover:text-accent-orange font-medium">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-5">
                    {{ $keluhans->links('pagination::tailwind') }}
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:hidden">
                @foreach ($keluhans as $keluhan)
                    <div class="bg-dark-gray-secondary rounded-lg shadow-md p-5 border-l-4 border-primary-orange">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-semibold text-light-text">{{ Str::limit($keluhan->jenis_keluhan, 40) }}</h3>
                            <span class="relative inline-block px-3 py-1 font-semibold leading-tight text-white text-sm">
                                @php
                                    $statusClass = '';
                                    switch($keluhan->status) {
                                        case 'Belum Diproses': $statusClass = 'bg-yellow-500'; break;
                                        case 'Sedang Diproses': $statusClass = 'bg-blue-500'; break;
                                        case 'Selesai': $statusClass = 'bg-green-500'; break;
                                        default: $statusClass = 'bg-gray-400';
                                    }
                                @endphp
                                <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full {{ $statusClass }}"></span>
                                <span class="relative">{{ ucfirst($keluhan->status) }}</span>
                            </span>
                        </div>
                        <p class="text-soft-white text-sm mb-2">ID: {{ $keluhan->id }} | Dibuat: {{ $keluhan->created_at->format('d M Y H:i') }}</p>
                        <p class="text-light-text mb-4">{{ Str::limit($keluhan->keluhan, 100) }}</p>
                        <a href="{{ route('keluhan.show', $keluhan->id) }}" class="bg-primary-orange hover:bg-accent-orange text-white text-sm font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out">
                            Lihat Detail
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
