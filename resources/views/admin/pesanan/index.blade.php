@extends('admin.layouts.app')

@section('title', 'Daftar Pesanan')

@section('styles')
<!-- DateRangePicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css">

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">

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

    /* Quick sort buttons styling */
    .btn-group .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.75rem;
        border-radius: 0.375rem;
        border: 1px solid #d1d5db;
        background-color: #ffffff;
        color: #374151;
        transition: all 0.15s ease-in-out;
        margin-right: 0.25rem;
    }

    .btn-group .btn:hover {
        background-color: #f97316;
        border-color: #f97316;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn-group .btn.active {
        background-color: #ea580c;
        border-color: #ea580c;
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .btn-group .btn i {
        font-size: 0.7rem;
    }

    /* Keyboard key styling */
    kbd {
        background-color: #f1f5f9;
        border: 1px solid #cbd5e1;
        border-radius: 0.25rem;
        color: #334155;
        font-family: monospace;
        font-size: 0.75rem;
        padding: 0.125rem 0.25rem;
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

    /* DataTables sorting styles */
    .dataTable thead th {
        cursor: pointer;
        position: relative;
        user-select: none;
        transition: all 0.2s ease-in-out;
    }

    .dataTable thead th.sorting,
    .dataTable thead th.sorting_asc,
    .dataTable thead th.sorting_desc {
        padding-right: 30px;
    }

    .dataTable thead th.sorting:hover {
        background: #f3f4f6;
        box-shadow: 0 2px 0 #f97316;
        color: #f97316;
    }

    .dataTable thead th.sorting_asc:hover,
    .dataTable thead th.sorting_desc:hover {
        background: #fef3c7;
    }

    .dataTable thead th.sorting:after,
    .dataTable thead th.sorting_asc:after,
    .dataTable thead th.sorting_desc:after {
        position: absolute;
        top: 50%;
        right: 8px;
        transform: translateY(-50%);
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        opacity: 0.5;
        font-size: 0.8em;
    }

    .dataTable thead th.sorting:after {
        content: "\f0dc"; /* fa-sort */
        opacity: 0.3;
    }

    .dataTable thead th.sorting_asc:after {
        content: "\f0de"; /* fa-sort-up */
        opacity: 0.8;
        color: #f97316;
    }

    .dataTable thead th.sorting_desc:after {
        content: "\f0dd"; /* fa-sort-down */
        opacity: 0.8;
        color: #f97316;
    }

    .dataTable thead th.sorting:hover:after {
        opacity: 0.6;
    }

    .dataTable thead th.no-sort.sorting:after,
    .dataTable thead th.no-sort.sorting_asc:after,
    .dataTable thead th.no-sort.sorting_desc:after {
        display: none;
    }

    /* Enhance table header styling */
    .dataTable thead th {
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border-bottom: 2px solid #e5e7eb;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #374151;
        transition: all 0.2s ease;
        position: relative;
    }

    .dataTable thead th:hover {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #f97316;
    }

    /* Active sort indicator */
    .dataTable thead th.sorting_asc,
    .dataTable thead th.sorting_desc {
        background-color: rgba(249, 115, 22, 0.05);
        border-bottom: 2px solid #f97316;
        color: #f97316;
        font-weight: 700;
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
                   class="px-4 py-3 text-sm font-medium {{ request('status') == 'Menunggu Konfirmasi' ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Menunggu Konfirmasi
                </a>
                <a href="{{ route('admin.pesanan.index', ['status' => 'Sedang Diproses']) }}"
                   class="px-4 py-3 text-sm font-medium {{ in_array(request('status'), ['Sedang Diproses','Diproses','Pembayaran Dikonfirmasi']) ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Sedang Diproses
                </a>
                <a href="{{ route('admin.pesanan.index', ['status' => 'Sedang Dikirim']) }}"
                   class="px-4 py-3 text-sm font-medium {{ in_array(request('status'), ['Sedang Dikirim','Dikirim']) ? 'border-b-2 border-orange-500 text-orange-600' : 'text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
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
            <form id="filterForm" class="filter-form" method="GET" action="{{ route('admin.pesanan.index') }}">
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
                <table class="min-w-full divide-y divide-gray-200 table table-striped" id="orderTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($pesanan) && count($pesanan) > 0)
                            @foreach($pesanan as $order)
                                <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('admin.pesanan.show', $order->id_pesanan) }}'">
                                    <td class="px-3 py-3 whitespace-nowrap font-medium text-gray-900" data-order="{{ $order->id_pesanan }}">
                                        #{{ $order->id_pesanan }}
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap" data-order="{{ $order->user->name ?? 'Pelanggan tidak tersedia' }}">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Pelanggan tidak tersedia' }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->user->email ?? '' }}</div>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap" data-order="{{ $order->created_at->timestamp }}">
                                        <div class="text-sm text-gray-900">{{ $order->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-3 py-3" data-order="{{ $order->detailPesanan->first()->produk->nama_ikan ?? 'Tidak ada produk' }}">
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
                                    <td class="px-3 py-3 whitespace-nowrap text-right" data-order="{{ $order->total_harga }}">
                                        <div class="text-sm font-medium text-gray-900">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                                        @if($order->ongkir_biaya)
                                            <div class="text-xs text-gray-500">Ongkir: Rp {{ number_format($order->ongkir_biaya, 0, ',', '.') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-center" data-order="{{ $order->status_pesanan }}">
                                        @php
                                            $statusClass = 'status-' . strtolower(str_replace(' ', '-', $order->status_pesanan));
                                            $statusText = $order->status_pesanan;

                                            // Show special status text for when payment proof uploaded but not yet confirmed
                                            if ($order->status_pesanan == 'Menunggu Pembayaran' && $order->bukti_pembayaran) {
                                                $statusText = 'Menunggu Konfirmasi';
                                                $statusClass = 'status-menunggu-konfirmasi';
                                            }
                                        @endphp

                                        {{-- Status badge tanpa dropdown untuk keamanan --}}
                                        <div class="flex flex-col items-center space-y-2">
                                            <span class="status-badge {{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>

                                            {{-- Tombol untuk ke detail pesanan --}}
                                            <a href="{{ route('admin.pesanan.show', $order->id_pesanan) }}"
                                               class="text-xs text-orange-600 hover:text-orange-800 font-medium px-2 py-1 rounded border border-orange-300 hover:border-orange-500 transition-colors duration-200"
                                               onclick="event.stopPropagation()"
                                               title="Kelola pesanan di halaman detail">
                                                <i class="fas fa-edit mr-1"></i>Kelola
                                            </a>
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
                <div class="flex flex-wrap justify-between items-center mt-4 px-6 py-3 d-none" id="laravelPagination">
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

<!-- Old modal system removed - now using SweetAlert2 for all order actions -->
@endsection

@section('scripts')
<!-- Ensure jQuery is loaded -->
<script>
if (typeof jQuery === 'undefined') {
    document.write('<script src="https://code.jquery.com/jquery-3.6.0.min.js"><\/script>');
}
</script>

<!-- Date Range Picker -->
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>

<!-- Custom scripts for the admin orders page -->
<script>
    // Wait for jQuery to be fully loaded
    function initializeOrderPage() {
        if (typeof jQuery === 'undefined') {
            setTimeout(initializeOrderPage, 50);
            return;
        }

        console.log('jQuery loaded successfully:', typeof jQuery);
        console.log('updateStatus function is globally accessible:', typeof window.updateStatus === 'function');

        $(document).ready(function() {
        // Initialize DataTable with comprehensive configuration
        var table = $('#orderTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "processing": true,
            "deferRender": true,
            "pageLength": 25,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            "order": [[0, "desc"]], // Sort by Order ID column (newest/highest ID first) for consistent ordering
            "orderCellsTop": true, // Enable sorting header in fixed header
            "fixedHeader": true, // Fix header to top of table during scroll
            "columnDefs": [
                {
                    "targets": [0], // Order ID column
                    "type": "num",
                    "className": "text-center",
                    "render": function(data, type, row) {
                        if (type === 'sort') {
                            // Extract numeric ID from data-order attribute for proper numeric sorting
                            var match = data.match(/data-order="(\d+)"/);
                            return match ? parseInt(match[1]) : 0;
                        }
                        return data;
                    }
                },
                {
                    "targets": [1], // Customer column - alphabetical sorting
                    "type": "string",
                    "render": function(data, type, row) {
                        if (type === 'sort') {
                            // Extract customer name from HTML for sorting
                            var match = data.match(/data-order="([^"]+)"/);
                            return match ? match[1] : '';
                        }
                        return data;
                    }
                },
                {
                    "targets": [2], // Date column
                    "type": "num", // Use numeric type for timestamp sorting
                    "render": function(data, type, row) {
                        if (type === 'sort') {
                            // Extract timestamp from data-order attribute
                            var match = data.match(/data-order="(\d+)"/);
                            return match ? parseInt(match[1]) : 0;
                        }
                        return data;
                    }
                },
                {
                    "targets": [3], // Product column - alphabetical sorting
                    "type": "string",
                    "render": function(data, type, row) {
                        if (type === 'sort') {
                            // Extract product name from HTML for sorting
                            var match = data.match(/data-order="([^"]+)"/);
                            return match ? match[1] : '';
                        }
                        return data;
                    }
                },
                {
                    "targets": [4], // Total Price column
                    "type": "num",
                    "className": "text-right",
                    "render": function(data, type, row) {
                        if (type === 'sort') {
                            // Extract numeric value from data-order attribute
                            var match = data.match(/data-order="(\d+)"/);
                            return match ? parseInt(match[1]) : 0;
                        }
                        return data;
                    }
                },
                {
                    "targets": [5], // Status column - alphabetical sorting
                    "type": "string",
                    "render": function(data, type, row) {
                        if (type === 'sort') {
                            // Extract status text from data-order attribute
                            var match = data.match(/data-order="([^"]+)"/);
                            return match ? match[1] : '';
                        }
                        return data;
                    }
                }
            ],
            "language": {
                "processing": "Memproses...",
                "lengthMenu": "Tampilkan _MENU_ pesanan per halaman",
                "zeroRecords": "Tidak ada pesanan yang ditemukan",
                "emptyTable": "Tidak ada data pesanan",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ pesanan",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 pesanan",
                "infoFiltered": "(disaring dari _MAX_ total pesanan)",
                "search": "Cari:",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
                "aria": {
                    "sortAscending": ": aktifkan untuk mengurutkan kolom secara ascending",
                    "sortDescending": ": aktifkan untuk mengurutkan kolom secara descending"
                }
            },
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                   "<'row'<'col-sm-12'tr>>" +
                   "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "drawCallback": function(settings) {
                // Re-initialize any dropdowns or interactive elements after table redraw
                $('[x-data]').each(function() {
                    if (!this._x_dataStack) {
                        Alpine.initTree(this);
                    }
                });

                // Add tooltips to sortable headers
                $('.dataTable thead th.sorting').attr('title', 'Klik untuk mengurutkan A-Z / Z-A');
                $('.dataTable thead th.sorting_asc').attr('title', 'Diurutkan A-Z, klik untuk Z-A');
                $('.dataTable thead th.sorting_desc').attr('title', 'Diurutkan Z-A, klik untuk A-Z');
            },
            "initComplete": function() {
                // Add custom sorting info
                var sortingInfo = $('<div class="alert alert-info mt-2 mb-3" style="font-size: 0.85rem; padding: 0.5rem 0.75rem; background-color: #e0f2fe; border: 1px solid #b3e5fc; border-radius: 0.375rem; color: #0277bd;"><i class="fas fa-info-circle mr-2"></i><strong>Tip:</strong> Klik pada header kolom untuk mengurutkan data. ID dan Total dapat diurutkan berdasarkan nilai, Pelanggan/Produk/Status dapat diurutkan A-Z, dan Tanggal dapat diurutkan dari terbaru/terlama.</div>');
                $('#orderTable_wrapper').prepend(sortingInfo);

                // Add tooltips to column headers
                $('#orderTable thead th:nth-child(1)').attr('title', 'Klik untuk mengurutkan berdasarkan ID Pesanan (Default: ID Tertinggi ke Terendah)');
                $('#orderTable thead th:nth-child(2)').attr('title', 'Klik untuk mengurutkan berdasarkan Nama Pelanggan');
                $('#orderTable thead th:nth-child(3)').attr('title', 'Klik untuk mengurutkan berdasarkan Tanggal');
                $('#orderTable thead th:nth-child(4)').attr('title', 'Klik untuk mengurutkan berdasarkan Nama Produk');
                $('#orderTable thead th:nth-child(5)').attr('title', 'Klik untuk mengurutkan berdasarkan Total Harga');
                $('#orderTable thead th:nth-child(6)').attr('title', 'Klik untuk mengurutkan berdasarkan Status');

                // Restore user's preferred sorting if available, or set default to ID descending
                var savedPreference = localStorage.getItem('orderPreference');
                if (savedPreference) {
                    switch(savedPreference) {
                        case 'newest':
                            $('.btn-outline-secondary').removeClass('active');
                            $('#sortNewest').addClass('active');
                            table.order([2, 'desc']).draw();
                            break;
                        case 'oldest':
                            $('.btn-outline-secondary').removeClass('active');
                            $('#sortOldest').addClass('active');
                            table.order([2, 'asc']).draw();
                            break;
                        case 'idDesc':
                            $('.btn-outline-secondary').removeClass('active');
                            $('#sortIdDesc').addClass('active');
                            table.order([0, 'desc']).draw();
                            break;
                        case 'idAsc':
                            $('.btn-outline-secondary').removeClass('active');
                            $('#sortIdAsc').addClass('active');
                            table.order([0, 'asc']).draw();
                            break;
                        case 'customerAZ':
                            $('.btn-outline-secondary').removeClass('active');
                            $('#sortCustomerAZ').addClass('active');
                            table.order([1, 'asc']).draw();
                            break;
                        case 'customerZA':
                            $('.btn-outline-secondary').removeClass('active');
                            $('#sortCustomerZA').addClass('active');
                            table.order([1, 'desc']).draw();
                            break;
                        case 'priceHigh':
                            $('.btn-outline-secondary').removeClass('active');
                            $('#sortPriceHigh').addClass('active');
                            table.order([4, 'desc']).draw();
                            break;
                        case 'priceLow':
                            $('.btn-outline-secondary').removeClass('active');
                            $('#sortPriceLow').addClass('active');
                            table.order([4, 'asc']).draw();
                            break;
                        default:
                            $('.btn-outline-secondary').removeClass('active');
                            $('#sortIdDesc').addClass('active');
                            table.order([0, 'desc']).draw();
                    }
                } else {
                    // Default sorting - ID descending (newest order ID first)
                    $('.btn-outline-secondary').removeClass('active');
                    $('#sortIdDesc').addClass('active');
                    localStorage.setItem('orderPreference', 'idDesc');
                }

                // Auto-hide the info after 10 seconds
                setTimeout(function() {
                    sortingInfo.fadeOut(500);
                }, 10000);
            }
        });

        // Custom search functionality that works with existing filters
        $('.filter-form input[name="search"], .filter-form select[name="status"], .filter-form input[name="date_range"]').on('change keyup', function() {
            // Use built-in search for immediate feedback
            if ($(this).attr('name') === 'search') {
                table.search($(this).val()).draw();
            }
        });

        // Checkbox functionality removed as it had no purpose

        // Add quick sort buttons
        var quickSortButtons = $(`
            <div class="mb-3">
                <div class="btn-group" role="group" aria-label="Quick Sort Options">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="sortNewest">
                        <i class="fas fa-sort-numeric-down mr-1"></i>Terbaru
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="sortOldest">
                        <i class="fas fa-sort-numeric-up mr-1"></i>Terlama
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm active" id="sortIdDesc">
                        <i class="fas fa-sort-numeric-down mr-1"></i>ID Tertinggi
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="sortIdAsc">
                        <i class="fas fa-sort-numeric-up mr-1"></i>ID Terendah
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="sortCustomerAZ">
                        <i class="fas fa-sort-alpha-down mr-1"></i>Pelanggan A-Z
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="sortCustomerZA">
                        <i class="fas fa-sort-alpha-up mr-1"></i>Pelanggan Z-A
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="sortPriceHigh">
                        <i class="fas fa-sort-amount-down mr-1"></i>Harga Tertinggi
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="sortPriceLow">
                        <i class="fas fa-sort-amount-up mr-1"></i>Harga Terendah
                    </button>
                </div>
            </div>
        `);
        $('#orderTable_wrapper').prepend(quickSortButtons);

        // Add sorting indicator tip
        var sortTip = $('<div class="text-xs text-gray-500 text-right mb-2"><i class="fas fa-info-circle text-blue-400"></i> Tip: Klik header kolom untuk mengurutkan</div>');
        $('.dataTables_filter').append(sortTip);

        // Quick sort button functionality
        $('#sortNewest').click(function() {
            table.order([2, 'desc']).draw();  // Order by date column (newest first)
            $('.btn-outline-secondary').removeClass('active');
            $(this).addClass('active');

            // Save the sort preference in localStorage
            localStorage.setItem('orderPreference', 'newest');
        });

        $('#sortOldest').click(function() {
            table.order([2, 'asc']).draw();  // Order by date column (oldest first)
            $('.btn-outline-secondary').removeClass('active');
            $(this).addClass('active');

            // Save the sort preference in localStorage
            localStorage.setItem('orderPreference', 'oldest');
        });

        $('#sortIdDesc').click(function() {
            table.order([0, 'desc']).draw();  // Order by ID column (highest/newest ID first) - DEFAULT
            $('.btn-outline-secondary').removeClass('active');
            $(this).addClass('active');

            // Save the sort preference in localStorage
            localStorage.setItem('orderPreference', 'idDesc');
        });

        $('#sortIdAsc').click(function() {
            table.order([0, 'asc']).draw();  // Order by ID column (lowest/oldest ID first)
            $('.btn-outline-secondary').removeClass('active');
            $(this).addClass('active');

            // Save the sort preference in localStorage
            localStorage.setItem('orderPreference', 'idAsc');
        });

        $('#sortCustomerAZ').click(function() {
            table.order([1, 'asc']).draw();
            $('.btn-outline-secondary').removeClass('active');
            $(this).addClass('active');
            localStorage.setItem('orderPreference', 'customerAZ');
        });

        $('#sortCustomerZA').click(function() {
            table.order([1, 'desc']).draw();
            $('.btn-outline-secondary').removeClass('active');
            $(this).addClass('active');
            localStorage.setItem('orderPreference', 'customerZA');
        });

        $('#sortPriceHigh').click(function() {
            table.order([4, 'desc']).draw();
            $('.btn-outline-secondary').removeClass('active');
            $(this).addClass('active');
            localStorage.setItem('orderPreference', 'priceHigh');
        });

        $('#sortPriceLow').click(function() {
            table.order([4, 'asc']).draw();
            $('.btn-outline-secondary').removeClass('active');
            $(this).addClass('active');
            localStorage.setItem('orderPreference', 'priceLow');
        });

        // Multi-column sorting info
        var multiSortInfo = $('<div class="alert alert-secondary mt-2" style="font-size: 0.8rem; padding: 0.4rem 0.6rem;"><i class="fas fa-keyboard mr-1"></i><strong>Tip Advanced:</strong> Tahan tombol <kbd>Shift</kbd> sambil klik header kolom untuk sorting multi-kolom.</div>');
        $('#orderTable_wrapper').append(multiSortInfo);

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

        // Reset filter button with DataTables integration
        $('#resetFilter').click(function(e) {
            e.preventDefault();
            // Clear form inputs
            $('#dateRange').val('');
            $('input[name="search"]').val('');
            $('input[name="status"]').val('');

            // Reset DataTables search and sorting (ID descending - newest order ID first)
            table.search('').order([0, 'desc']).draw();

            // Reset active sort button
            $('.btn-outline-secondary').removeClass('active');
            $('#sortIdDesc').addClass('active');

            // Reset sort preference
            localStorage.setItem('orderPreference', 'idDesc');

            // Navigate to clean URL without parameters
            window.location.href = '{{ route("admin.pesanan.index") }}';
        });

        // Refresh button
        $('#refreshBtn').click(function(e) {
            e.preventDefault();
            location.reload();
        });

        // Export data button with SweetAlert2
        $('#exportBtn').click(function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Export Data Pesanan',
                text: 'Fitur export sedang dalam pengembangan. Apakah Anda ingin melanjutkan?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Export',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'animate__animated animate__fadeInDown'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Informasi',
                        text: 'Fitur export data sedang dalam pengembangan',
                        icon: 'info',
                        confirmButtonColor: '#3b82f6'
                    });
                    // Actual implementation would be:
                    // window.location.href = '{{ route("admin.pesanan.index") }}' + '/export' + window.location.search;
                }
            });
        });

        // Status update functionality has been removed for security reasons
        // All status changes must now be performed through the detail page
    });

    } // End of initializeOrderPage function

    // Start initialization
    initializeOrderPage();
</script>
@stack('scripts')
