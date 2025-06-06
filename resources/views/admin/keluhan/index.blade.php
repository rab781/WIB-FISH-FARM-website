@extends('admin.layouts.app')

@section('title', 'Manajemen Keluhan')

@section('content')
<div class="min-h-screen bg-dark-bg text-light-text p-6">
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-primary-orange">Daftar Keluhan</h1>
        </div>

        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow-md">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-dark-gray-secondary rounded-lg shadow-xl overflow-hidden">
            <div class="p-4">
                {{-- Fitur pencarian opsional --}}
                <input type="text" placeholder="Cari keluhan..." class="w-full p-3 rounded-lg bg-soft-white text-dark-bg border-2 border-primary-orange focus:outline-none focus:ring-2 focus:ring-accent-orange">
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-primary-orange text-dark-bg uppercase text-sm font-semibold">
                            <th class="px-5 py-3 border-b-2 border-accent-orange text-left text-light-text">
                                ID Keluhan
                            </th>
                            <th class="px-5 py-3 border-b-2 border-accent-orange text-left text-light-text">
                                Pengirim
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
                        @forelse ($keluhans as $keluhan)
                            <tr class="hover:bg-dark-bg transition duration-200">
                                <td class="px-5 py-3 border-b border-soft-white text-sm text-light-text">
                                    {{ $keluhan->id }}
                                </td>
                                <td class="px-5 py-3 border-b border-soft-white text-sm text-light-text">
                                    {{ $keluhan->user->name ?? 'N/A' }}
                                </td>
                                <td class="px-5 py-3 border-b border-soft-white text-sm text-light-text">
                                    {{ Str::limit($keluhan->jenis_keluhan, 40) }}
                                </td>
                                <td class="px-5 py-3 border-b border-soft-white text-sm text-light-text">
                                    <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
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
                                        <span class="relative text-white">{{ ucfirst($keluhan->status) }}</span>
                                    </span>
                                </td>
                                <td class="px-5 py-3 border-b border-soft-white text-sm text-light-text">
                                    {{ $keluhan->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-5 py-3 border-b border-soft-white text-sm text-center">
                                    <a href="{{ route('admin.keluhan.show', $keluhan->id) }}" class="text-primary-orange hover:text-accent-orange mr-3">
                                        Lihat & Tanggapi
                                    </a>
                                    {{-- Jika ada fitur edit/hapus keluhan oleh admin, tambahkan di sini --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-3 border-b border-soft-white text-center text-sm text-light-text">
                                    Tidak ada keluhan ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-5">
                {{ $keluhans->links('pagination::tailwind') }} {{-- Pastikan Anda punya view paginasi Tailwind --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#keluhanTable').DataTable({
            "paging": false,
            "info": false,
            "searching": true,
            "order": [[ 1, "desc" ]]
        });
    });
</script>
@endpush
