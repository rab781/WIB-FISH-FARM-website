@extends('admin.layouts.app')

@section('title', 'Manajemen Keluhan')

@push('styles')
<style>
    .search-input:focus {
        box-shadow: 0 0 0 2px rgba(251, 146, 60, 0.3);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Manajemen Keluhan
                </h2>
                <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        Total Keluhan: {{ $keluhans->total() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- <!-- Stats Cards -->
        <div class="grid grid-cols-4 gap-4 mb-6">
            <!-- Total -->
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <h3 class="text-orange-500 font-medium text-sm mb-2">TOTAL</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $keluhans->total() }}</p>
            </div>

            <!-- Belum Diproses -->
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <h3 class="text-orange-500 font-medium text-sm mb-2">BELUM DIPROSES</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $keluhans->where('status', 'Belum Diproses')->count() }}</p>
            </div>

            <!-- Sedang Diproses -->
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <h3 class="text-blue-500 font-medium text-sm mb-2">SEDANG DIPROSES</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $keluhans->where('status', 'Sedang Diproses')->count() }}</p>
            </div>

            <!-- Selesai -->
            <div class="bg-white rounded-lg p-4 shadow-sm">
                <h3 class="text-violet-500 font-medium text-sm mb-2">SELESAI</h3>
                <p class="text-2xl font-bold text-gray-900">{{ $keluhans->where('status', 'Selesai')->count() }}</p>
            </div>
        </div> --}}

        <!-- Filters and Search -->
        <div class="mt-6 bg-white shadow rounded-lg">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex-1">
                        <div class="relative rounded-md">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text"
                                   id="search"
                                   class="search-input form-input block w-full pl-10 sm:text-sm border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500"
                                   placeholder="Cari keluhan...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengirim</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Keluhan</th>
                            {{-- <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th> --}}
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($keluhans as $keluhan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $keluhan->user->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $keluhan->user->email ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $keluhan->jenis_keluhan }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($keluhan->keluhan, 50) }}</div>
                                </td>
                                {{-- <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $keluhan->status === 'Belum Diproses' ? 'bg-yellow-100 text-yellow-800' :
                                           ($keluhan->status === 'Sedang Diproses' ? 'bg-blue-100 text-blue-800' :
                                           ($keluhan->status === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ $keluhan->status }}
                                    </span>
                                </td> --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $keluhan->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.keluhan.show', $keluhan->id) }}"
                                       class="text-orange-600 hover:text-orange-900 ml-3">
                                        Lihat Detail Keluhan
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Tidak ada keluhan ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $keluhans->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('search').addEventListener('keyup', function(e) {
    const searchValue = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
});
</script>
@endpush
