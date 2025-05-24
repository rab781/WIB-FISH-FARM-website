@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('home') }}" class="flex items-center text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Kembali</span>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 ml-4">Pesanan Saya</h1>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-4 py-5 sm:p-6">
            <div class="overflow-x-auto">
                <table id="pesananTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengiriman</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($pesanan as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $p->id_pesanan }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="font-medium text-gray-900">{{ $p->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $p->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($p->kurir)
                                    <div class="font-medium uppercase">{{ $p->kurir }}</div>
                                    @if($p->kurir_service)
                                        <div class="text-xs text-gray-500 uppercase">{{ $p->kurir_service }}</div>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @php
                                    $statusColor = 'gray';
                                    $statusBg = 'bg-gray-100';
                                    $statusText = 'text-gray-800';

                                    if ($p->status_pesanan == 'Menunggu Pembayaran') {
                                        $statusColor = 'yellow';
                                        $statusBg = 'bg-yellow-100';
                                        $statusText = 'text-yellow-800';
                                    } elseif ($p->status_pesanan == 'Pembayaran Dikonfirmasi') {
                                        $statusColor = 'blue';
                                        $statusBg = 'bg-blue-100';
                                        $statusText = 'text-blue-800';
                                    } elseif ($p->status_pesanan == 'Diproses') {
                                        $statusColor = 'indigo';
                                        $statusBg = 'bg-indigo-100';
                                        $statusText = 'text-indigo-800';
                                    } elseif ($p->status_pesanan == 'Dikirim') {
                                        $statusColor = 'cyan';
                                        $statusBg = 'bg-cyan-100';
                                        $statusText = 'text-cyan-800';
                                    } elseif ($p->status_pesanan == 'Selesai') {
                                        $statusColor = 'green';
                                        $statusBg = 'bg-green-100';
                                        $statusText = 'text-green-800';
                                    } elseif ($p->status_pesanan == 'Dibatalkan') {
                                        $statusColor = 'red';
                                        $statusBg = 'bg-red-100';
                                        $statusText = 'text-red-800';
                                    }
                                @endphp

                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusBg }} {{ $statusText }}">
                                    {{ $p->status_pesanan }}
                                </span>

                                @if($p->status_pesanan == 'Menunggu Pembayaran' && $p->batas_waktu)
                                    @php
                                        $batasWaktu = $p->batas_waktu instanceof \Carbon\Carbon
                                            ? $p->batas_waktu
                                            : \Carbon\Carbon::parse($p->batas_waktu);
                                    @endphp

                                    @if(!$batasWaktu->isPast())
                                        <div class="text-xs text-red-600 mt-1">
                                            Batas: {{ $batasWaktu->format('d/m/Y H:i') }}
                                        </div>
                                    @else
                                        <div class="text-xs text-red-600 mt-1">
                                            Batas waktu habis
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex space-x-2">
                                    <a href="{{ route('pesanan.show', $p->id_pesanan) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Detail
                                    </a>

                                    @if($p->status_pesanan == 'Menunggu Pembayaran')
                                    <a href="{{ route('pesanan.show', $p->id_pesanan) }}#pembayaran" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Bayar
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.tailwindcss.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.tailwindcss.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#pesananTable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/id.json',
        },
        order: [[1, 'desc']], // Sort by date desc
        columnDefs: [
            { orderable: false, targets: 5 } // Disable sorting on action column
        ],
        dom: '<"flex flex-col md:flex-row items-center justify-between mb-4"<"w-full md:w-auto mb-2 md:mb-0"l><"w-full md:w-auto"f>>rtip',
        pageLength: 10,
        responsive: true,
        className: 'border-separate',
        drawCallback: function() {
            // Add styling to pagination elements
            $('.dataTables_paginate .paginate_button').addClass('px-3 py-1 mx-1 border rounded text-sm hover:bg-gray-100');
            $('.dataTables_paginate .paginate_button.current').addClass('bg-orange-500 text-white hover:bg-orange-600 border-orange-500');
        }
    });
});
</script>
@endsection
