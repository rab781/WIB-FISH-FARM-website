@extends('admin.layouts.app')

@section('title', 'Daftar Pesanan')

@section('styles')
<!-- DateRangePicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css">

<style>
    /* Status badges */
    .status-badge {
        @apply px-3 py-1 rounded-full text-xs font-medium;
    }
    .status-badge.status-menunggu-pembayaran {
        @apply bg-yellow-100 text-yellow-700 border border-yellow-200;
    }
    .status-badge.status-menunggu-konfirmasi {
        @apply bg-blue-100 text-blue-700 border border-blue-200;
    }
    .status-badge.status-pembayaran-dikonfirmasi {
        @apply bg-orange-100 text-orange-700 border border-orange-200;
    }
    .status-badge.status-diproses,
    .status-badge.status-sedang-diproses {
        @apply bg-indigo-100 text-indigo-700 border border-indigo-200;
    }
    .status-badge.status-dikirim,
    .status-badge.status-sedang-dikirim {
        @apply bg-purple-100 text-purple-700 border border-purple-200;
    }
    .status-badge.status-selesai {
        @apply bg-green-100 text-green-700 border border-green-200;
    }
    .status-badge.status-dibatalkan {
        @apply bg-red-100 text-red-700 border border-red-200;
    }
    .status-badge.status-karantina {
        @apply bg-yellow-100 text-yellow-700 border border-yellow-200;
    }
    .status-badge.status-pengembalian {
        @apply bg-orange-100 text-orange-700 border border-orange-200;
    }

    /* Small status indicator dots for dropdown menu */
    .dropdown-menu button .status-badge {
        @apply w-3 h-3 rounded-full p-0 mr-2 inline-block;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
    }

    .order-summary {
        margin-bottom: 20px;
        padding: 0.75rem;
        border-bottom: 1px solid #e3e6f0;
        background-color: #f8f9fc;
    }

    .nav-tabs .nav-link {
        color: #5a5c69;
        font-weight: 500;
    }
    .nav-tabs .nav-link.active {
        color: #4e73df;
        font-weight: 600;
    }

    .product-mini img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 4px;
    }

    .date-small {
        font-size: 0.8rem;
        color: #858796;
    }

    /* Bootstrap specific overrides */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        width: 100vw;
        height: 100vh;
        background-color: #000;
    }

    .modal-backdrop.fade {
        opacity: 0;
    }

    .modal-backdrop.show {
        opacity: 0.5;
    }

    .modal-open {
        overflow: hidden;
    }

    .modal-open .modal {
        overflow-x: hidden;
        overflow-y: auto;
    }

    /* Table adjustments */
    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
        border-collapse: collapse;
    }

    /* Clickable row styles */
    tr.cursor-pointer {
        transition: all 0.2s ease-in-out;
    }

    tr.cursor-pointer:hover {
        background-color: rgba(249, 115, 22, 0.05) !important;
        box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.25);
    }
</style>
@endsection

@section('content')
<div class="container mx-auto">
    <!-- Page Heading -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Daftar Pesanan</h1>
            <p class="text-sm text-gray-500 mt-1"><i class="fas fa-info-circle mr-1"></i> Klik pada baris untuk melihat detail pesanan</p>
        </div>
        <div class="flex space-x-2">
            <button class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded-md flex items-center text-sm" id="refreshBtn">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
            </button>
            <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-2 rounded-md flex items-center text-sm" id="exportBtn">
                <i class="fas fa-download mr-2"></i> Export Data
            </button>
        </div>
    </div>

    <!-- Order Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Card -->
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-orange-500">
            <div class="flex flex-col">
                <span class="text-xs font-medium text-orange-500 uppercase tracking-wider mb-1">Total</span>
                <span class="text-2xl font-bold text-gray-800">{{ $totalPesanan ?? 0 }}</span>
            </div>
        </div>

        <!-- Menunggu Konfirmasi Card -->
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
            <div class="flex flex-col">
                <span class="text-xs font-medium text-yellow-500 uppercase tracking-wider mb-1">Menunggu Konfirmasi</span>
                <span class="text-2xl font-bold text-gray-800">{{ $menungguPembayaran ?? 0 }}</span>
            </div>
        </div>

        <!-- Sedang Diproses Card -->
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-indigo-500">
            <div class="flex flex-col">
                <span class="text-xs font-medium text-indigo-500 uppercase tracking-wider mb-1">Sedang Diproses</span>
                <span class="text-2xl font-bold text-gray-800">{{ $pembayaranDikonfirmasi + ($sedangDiproses ?? 0) }}</span>
            </div>
        </div>

        <!-- Sedang Dikirim Card -->
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
            <div class="flex flex-col">
                <span class="text-xs font-medium text-purple-500 uppercase tracking-wider mb-1">Sedang Dikirim</span>
                <span class="text-2xl font-bold text-gray-800">{{ $dikirim + ($selesai ?? 0) }}</span>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Card Header - Tabs -->
        <div class="border-b border-gray-200">
            <nav class="flex overflow-x-auto">
                <a href="{{ route('admin.pesanan.index') }}"
                   class="px-4 py-3 text-sm font-medium {{ !request('status') ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Semua
                </a>
                <a href="{{ route('admin.pesanan.index', ['status' => 'Menunggu Konfirmasi']) }}"
                   class="px-4 py-3 text-sm font-medium {{ request('status') == 'Menunggu Konfirmasi' || request('status') == 'Menunggu Pembayaran' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Menunggu Konfirmasi
                </a>
                <a href="{{ route('admin.pesanan.index', ['status' => 'Sedang Diproses']) }}"
                   class="px-4 py-3 text-sm font-medium {{ request('status') == 'Sedang Diproses' || request('status') == 'Diproses' || request('status') == 'Pembayaran Dikonfirmasi' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Sedang Diproses
                </a>
                <a href="{{ route('admin.pesanan.index', ['status' => 'Sedang Dikirim']) }}"
                   class="px-4 py-3 text-sm font-medium {{ request('status') == 'Sedang Dikirim' || request('status') == 'Dikirim' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Sedang Dikirim
                </a>
                <a href="{{ route('admin.pesanan.index', ['status' => 'Dibatalkan']) }}"
                   class="px-4 py-3 text-sm font-medium {{ request('status') == 'Dibatalkan' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Dibatalkan
                </a>
            </nav>
        </div>

        <!-- Filter Section -->
        <div class="bg-gray-50 p-4 border-b border-gray-200">
            <form id="filterForm" method="GET" action="{{ route('admin.pesanan.index') }}">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                    <div class="md:col-span-4">
                        <div class="relative">
                            <input type="text" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500"
                                   id="dateRange" name="date_range" placeholder="Filter tanggal" value="{{ request('date_range') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-calendar text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-6">
                        <div class="relative">
                            <input type="text" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500"
                                   id="search" name="search" placeholder="Cari ID pesanan atau nama pelanggan" value="{{ request('search') }}">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <button type="submit" class="absolute inset-y-0 right-0 px-3 bg-orange-500 text-white rounded-r-md hover:bg-orange-600 flex items-center">
                                Cari
                            </button>
                        </div>
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <button type="button" id="resetFilter" class="py-2 px-4 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 flex items-center">
                            <i class="fas fa-redo-alt mr-2"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="orderTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="checkAll" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                            </th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pesanan <i class="fas fa-caret-down text-gray-400 text-xs ml-1 tooltip" title="Klik status untuk mengubah"></i></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($pesanan) && count($pesanan) > 0)
                            @foreach($pesanan as $order)
                                <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('admin.pesanan.show', $order->id_pesanan) }}'">
                                    <td class="px-2 py-3 whitespace-nowrap" onclick="event.stopPropagation()">
                                        <input type="checkbox" class="order-checkbox rounded border-gray-300 text-orange-600 focus:ring-orange-500" value="{{ $order->id_pesanan }}">
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap font-medium text-gray-900">
                                        #{{ $order->id_pesanan }}
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Pelanggan tidak tersedia' }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->user->email ?? '' }}</div>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $order->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-3 py-3">
                                        @if($order->detailPesanan->isNotEmpty())
                                            @php
                                                $firstItem = $order->detailPesanan->first();
                                                $additionalItems = $order->detailPesanan->count() - 1;
                                            @endphp

                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 mr-3">
                                                    @if($firstItem->produk && $firstItem->produk->gambar)
                                                        @if(Str::startsWith($firstItem->produk->gambar, 'uploads/'))
                                                            <img class="h-10 w-10 rounded object-cover" src="{{ asset($firstItem->produk->gambar) }}" alt="{{ $firstItem->produk->nama_ikan }}">
                                                        @elseif(Str::startsWith($firstItem->produk->gambar, 'storage/'))
                                                            <img class="h-10 w-10 rounded object-cover" src="{{ asset($firstItem->produk->gambar) }}" alt="{{ $firstItem->produk->nama_ikan }}">
                                                        @else
                                                            <img class="h-10 w-10 rounded object-cover" src="{{ asset('storage/' . $firstItem->produk->gambar) }}" alt="{{ $firstItem->produk->nama_ikan }}">
                                                        @endif
                                                    @else
                                                        <div class="h-10 w-10 rounded bg-gray-100 flex items-center justify-center">
                                                            <i class="fas fa-box text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ Str::limit($firstItem->produk->nama_ikan ?? 'Produk tidak tersedia', 25) }}
                                                    </div>
                                                    @if($additionalItems > 0)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            +{{ $additionalItems }} produk
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500">Tidak ada produk</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-right">
                                        <div class="text-sm font-medium text-gray-900">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                                        @if($order->ongkir_biaya)
                                            <div class="text-xs text-gray-500">Ongkir: Rp {{ number_format($order->ongkir_biaya, 0, ',', '.') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-center" onclick="event.stopPropagation()">
                                        <div class="relative" x-data="{ open: false }">
                                            @php
                                                $statusClass = 'status-' . strtolower(str_replace(' ', '-', $order->status_pesanan));
                                                $statusText = $order->status_pesanan;

                                                // Show special status text for when payment proof uploaded but not yet confirmed
                                                if ($order->status_pesanan == 'Menunggu Pembayaran' && $order->bukti_pembayaran) {
                                                    $statusText = 'Menunggu Konfirmasi';
                                                    $statusClass = 'status-menunggu-konfirmasi';
                                                }
                                            @endphp

                                            <button @click="open = !open" type="button" class="status-badge {{ $statusClass }} inline-flex items-center justify-between w-full">
                                                <span>{{ $statusText }}</span>
                                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                                            </button>

                                            <div x-show="open"
                                                 @click.away="open = false"
                                                 class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10"
                                                 x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="transform opacity-0 scale-95"
                                                 x-transition:enter-end="transform opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-75"
                                                 x-transition:leave-start="transform opacity-100 scale-100"
                                                 x-transition:leave-end="transform opacity-0 scale-95">
                                                <div class="py-1" role="menu" aria-orientation="vertical">
                                                    <form method="POST" id="statusForm-{{ $order->id_pesanan }}">
                                                        @csrf
                                                        <input type="hidden" name="order_id" value="{{ $order->id_pesanan }}">

                                                        <button type="button" onclick="updateStatus('{{ $order->id_pesanan }}', 'Menunggu Konfirmasi')" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 {{ ($order->status_pesanan == 'Menunggu Pembayaran' && $order->bukti_pembayaran) ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
                                                            <span class="status-badge status-menunggu-konfirmasi mr-2"></span>
                                                            Menunggu Konfirmasi
                                                        </button>

                                                        <button type="button" onclick="updateStatus('{{ $order->id_pesanan }}', 'Sedang Diproses')" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 {{ $order->status_pesanan == 'Sedang Diproses' || $order->status_pesanan == 'Diproses' || $order->status_pesanan == 'Pembayaran Dikonfirmasi' ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
                                                            <span class="status-badge status-sedang-diproses mr-2"></span>
                                                            Sedang Diproses
                                                        </button>

                                                        <button type="button" onclick="updateStatus('{{ $order->id_pesanan }}', 'Sedang Dikirim')" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 {{ $order->status_pesanan == 'Sedang Dikirim' || $order->status_pesanan == 'Dikirim' || $order->status_pesanan == 'Selesai' ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
                                                            <span class="status-badge status-sedang-dikirim mr-2"></span>
                                                            Sedang Dikirim
                                                        </button>

                                                        <button type="button" onclick="updateStatus('{{ $order->id_pesanan }}', 'Dibatalkan')" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 {{ $order->status_pesanan == 'Dibatalkan' ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
                                                            <span class="status-badge status-dibatalkan mr-2"></span>
                                                            Dibatalkan
                                                        </button>

                                                        <button type="button" onclick="updateStatus('{{ $order->id_pesanan }}', 'Karantina')" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 {{ $order->status_pesanan == 'Karantina' ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
                                                            <span class="status-badge status-karantina mr-2"></span>
                                                            Karantina
                                                        </button>

                                                        <button type="button" onclick="updateStatus('{{ $order->id_pesanan }}', 'Pengembalian')" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100 {{ $order->status_pesanan == 'Pengembalian' ? 'bg-gray-100 text-gray-900' : 'text-gray-700' }}">
                                                            <span class="status-badge status-pengembalian mr-2"></span>
                                                            Pengembalian
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center py-8">
                                    <div class="py-6">
                                        <div class="flex justify-center mb-3">
                                            <i class="fas fa-box-open text-5xl text-gray-300"></i>
                                        </div>
                                        <h5 class="text-lg font-medium text-gray-900 mb-1">Belum ada pesanan yang tersedia</h5>
                                        <p class="text-gray-500 mb-4">Belum ada data pesanan yang tercatat dalam sistem.</p>
                                        @if(request('status') || request('search') || request('date_range'))
                                            <a href="{{ route('admin.pesanan.index') }}" class="inline-flex items-center px-4 py-2 border border-orange-500 text-sm font-medium rounded-md text-orange-500 hover:bg-orange-50">
                                                Hapus Filter
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if(isset($pesanan) && $pesanan instanceof \Illuminate\Pagination\LengthAwarePaginator && $pesanan->total() > 0)
                <div class="flex flex-wrap justify-between items-center mt-4 px-6 py-3">
                    <div class="text-sm text-gray-700 mb-2">
                        Menampilkan {{ $pesanan->firstItem() }} - {{ $pesanan->lastItem() }} dari {{ $pesanan->total() }} pesanan
                    </div>
                    <div>
                        {{ $pesanan->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal for Order Actions -->
<div id="orderActionModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="orderActionModalLabel" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="orderActionModalLabel">Update Status Pesanan</h3>
                        <div class="mt-4">
                            <form id="orderActionForm" method="POST">
                                @csrf
                                <p id="orderActionText" class="text-gray-700 mb-4">Apakah Anda yakin ingin mengubah status pesanan ini?</p>

                                <div id="orderCancelReasonContainer" class="mb-4 hidden">
                                    <label for="alasan_pembatalan" class="block text-sm font-medium text-gray-700 mb-1">Alasan Pembatalan</label>
                                    <textarea id="alasan_pembatalan" name="alasan_pembatalan" rows="3" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Masukkan alasan pembatalan pesanan"></textarea>
                                </div>

                                <div id="orderShippingContainer" class="mb-4 hidden">
                                    <label for="resi" class="block text-sm font-medium text-gray-700 mb-1">Nomor Resi Pengiriman</label>
                                    <input type="text" id="resi" name="resi" class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Masukkan nomor resi pengiriman">
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm" id="orderActionButton">
                    Konfirmasi
                </button>
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm modal-close">
                    Batal
                </button>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Date Range Picker -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<!-- Custom scripts for the admin orders page -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('orderActions', () => ({
            showModal: false,
            modalTitle: 'Update Status Pesanan',
            modalText: 'Apakah Anda yakin ingin mengubah status pesanan ini?',
            actionButtonText: 'Konfirmasi',
            formAction: '',
            showCancelReason: false,
            showShipping: false,

            setupModal(orderId, title, text, buttonText, formAction, type = null) {
                this.modalTitle = title;
                this.modalText = text;
                this.actionButtonText = buttonText;
                this.formAction = formAction;

                // Reset containers
                this.showCancelReason = false;
                this.showShipping = false;

                // Show specific container based on type
                if (type === 'cancel') {
                    this.showCancelReason = true;
                } else if (type === 'shipping') {
                    this.showShipping = true;
                }

                this.showModal = true;
            },

            closeModal() {
                this.showModal = false;
            }
        }));
    });

    $(document).ready(function() {
        // Initialize DataTable
        $('#orderTable').DataTable({
            "paging": false,  // We use Laravel's pagination
            "ordering": true,
            "searching": false, // We're using our own search
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": [0, 6] }  // Fixed: checkbox (0) and status dropdown (6) are not orderable
            ],
            "language": {
                "emptyTable": "Tidak ada data pesanan",
                "zeroRecords": "Tidak ada data pesanan yang cocok dengan pencarian"
            }
        });

        // Initialize date range picker
        $('#dateRange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD/MM/YYYY'
            }
        });

        $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

        $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        // Check all checkboxes
        $('#checkAll').change(function() {
            $('.order-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Order action modal
        const orderModal = document.getElementById('orderActionModal');

        // Handle order action buttons
        $('.process-order').click(function(e) {
            e.preventDefault();
            const orderId = $(this).data('id');
            setupOrderActionModal(
                orderId,
                'Proses Pesanan',
                'Apakah Anda yakin ingin memproses pesanan #' + orderId + '?',
                'Proses Pesanan',
                '{{ route("admin.pesanan.process", ["id" => "__id__"]) }}'.replace('__id__', orderId)
            );
        });

        $('.ship-order').click(function(e) {
            e.preventDefault();
            const orderId = $(this).data('id');
            setupOrderActionModal(
                orderId,
                'Kirim Pesanan',
                'Masukkan nomor resi pengiriman untuk pesanan #' + orderId,
                'Kirim Pesanan',
                '{{ route("admin.pesanan.ship", ["id" => "__id__"]) }}'.replace('__id__', orderId),
                'shipping'
            );
        });

        $('.confirm-payment').click(function(e) {
            e.preventDefault();
            const orderId = $(this).data('id');
            setupOrderActionModal(
                orderId,
                'Konfirmasi Pembayaran',
                'Apakah Anda yakin ingin mengkonfirmasi pembayaran untuk pesanan #' + orderId + '?',
                'Konfirmasi Pembayaran',
                '{{ route("admin.pesanan.confirm-payment", ["id" => "__id__"]) }}'.replace('__id__', orderId)
            );
        });

        $('.cancel-order').click(function(e) {
            e.preventDefault();
            const orderId = $(this).data('id');
            setupOrderActionModal(
                orderId,
                'Batalkan Pesanan',
                'Apakah Anda yakin ingin membatalkan pesanan #' + orderId + '?',
                'Batalkan Pesanan',
                '{{ route("admin.pesanan.cancel", ["id" => "__id__"]) }}'.replace('__id__', orderId),
                'cancel'
            );
        });

        function setupOrderActionModal(orderId, title, text, buttonText, formAction, type = null) {
            $('#orderActionModalLabel').text(title);
            $('#orderActionText').text(text);
            $('#orderActionButton').text(buttonText);
            $('#orderActionForm').attr('action', formAction);

            // Reset containers
            $('#orderCancelReasonContainer').addClass('hidden');
            $('#orderShippingContainer').addClass('hidden');

            // Show specific container based on type
            if(type === 'cancel') {
                $('#orderCancelReasonContainer').removeClass('hidden');
            } else if(type === 'shipping') {
                $('#orderShippingContainer').removeClass('hidden');
            }

            // Show modal
            $('#orderActionModal').removeClass('hidden');
        }

        // Close modal on click
        document.querySelectorAll('.modal-close').forEach(button => {
            button.addEventListener('click', function() {
                $('#orderActionModal').addClass('hidden');
            });
        });

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === $('#orderActionModal')[0]) {
                $('#orderActionModal').addClass('hidden');
            }
        });

        // Reset filter button
        $('#resetFilter').click(function(e) {
            e.preventDefault();
            window.location.href = '{{ route("admin.pesanan.index") }}';
        });

        // Refresh button
        $('#refreshBtn').click(function(e) {
            e.preventDefault();
            location.reload();
        });

        // Export data button
        $('#exportBtn').click(function(e) {
            e.preventDefault();
            alert('Fitur export data sedang dalam pengembangan');
            // Actual implementation would be:
            // window.location.href = '{{ route("admin.pesanan.index") }}' + '/export' + window.location.search;
        });
    });
</script>
@stack('scripts')
