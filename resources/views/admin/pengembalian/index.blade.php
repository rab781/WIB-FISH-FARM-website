@extends('admin.layouts.app')

@section('title', 'Manajemen Pengembalian')

@push('styles')
{{-- Memastikan gaya status-badge konsisten dengan pesanan --}}
<style>
    .status-badge {
        @apply px-3 py-1 rounded-full text-xs font-medium;
    }
    .status-pending { @apply bg-yellow-100 text-yellow-800; }
    .status-approved { @apply bg-green-100 text-green-800; }
    .status-rejected { @apply bg-red-100 text-red-800; }
    .status-processing { @apply bg-blue-100 text-blue-800; }
    .status-completed { @apply bg-gray-100 text-gray-800; }
    .status-menunggu-review { @apply bg-yellow-100 text-yellow-800; }
    .status-dalam-review { @apply bg-blue-100 text-blue-800; }
    .status-disetujui { @apply bg-green-100 text-green-800; }
    .status-ditolak { @apply bg-red-100 text-red-800; }
    .status-dana-dikembalikan { @apply bg-purple-100 text-purple-800; }
    .status-selesai { @apply bg-gray-100 text-gray-800; }
    .status-lainnya { @apply bg-gray-100 text-gray-800; }

    /* Gaya tambahan untuk card dan tabel */
    .card-modern {
        @apply bg-white rounded-lg shadow-md overflow-hidden;
    }
    .card-header-modern {
        @apply px-6 py-4 border-b border-gray-200;
    }
    .table-header-custom {
        @apply bg-gray-50 text-gray-700 uppercase text-xs font-semibold tracking-wider;
    }
    .table-row-hover:hover {
        @apply bg-gray-50 transition-colors duration-150;
    }
    .filter-input {
        @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500;
    }
    .btn-action-outline {
        @apply px-3 py-2 rounded-md border text-sm font-medium transition-colors duration-150;
    }
    .btn-action-outline-primary {
        @apply border-blue-500 text-blue-600 hover:bg-blue-50;
    }
    .btn-action-outline-success {
        @apply border-green-500 text-green-600 hover:bg-green-50;
    }
    .btn-action-outline-danger {
        @apply border-red-500 text-red-600 hover:bg-red-50;
    }
    .btn-primary-custom {
        @apply bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 flex items-center justify-center;
    }
    .btn-secondary-custom {
        @apply bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 flex items-center justify-center;
    }
    
    /* Enhanced Modal Styling */
    .modal-backdrop {
        @apply opacity-0 transition-opacity duration-300 ease-out;
    }
    
    .modal-backdrop.opacity-100 {
        @apply opacity-100;
    }
    
    .fixed.inset-0.z-50:not(.hidden) .relative {
        @apply opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 transition-all duration-300 ease-out;
    }
    
    .fixed.inset-0.z-50:not(.hidden) .relative.opacity-100 {
        @apply opacity-100 translate-y-0 sm:scale-100;
    }
    
    /* Form control focus states */
    .shadow-sm:focus {
        @apply ring-2 ring-offset-2 outline-none;
    }
    
    textarea, input[type="text"], input[type="date"] {
        @apply transition-all duration-200 ease-in-out;
    }
    
    /* Button hover effects */
    button[type="submit"], button[type="button"] {
        @apply relative overflow-hidden transition-all duration-300 ease-out;
    }
    
    button[type="submit"]:after, button[type="button"]:after {
        content: '';
        @apply absolute inset-0 rounded-md opacity-0 bg-white transition-opacity duration-300;
    }
    
    button[type="submit"]:hover:after, button[type="button"]:hover:after {
        @apply opacity-10;
    }
    
    button[type="submit"]:active:after, button[type="button"]:active:after {
        @apply opacity-20;
    }
    
    /* Toast notifications */
    .toast-notification {
        @apply min-w-[250px] max-w-[350px] transition-all duration-300 ease-out;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1), 0 5px 10px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Pengembalian</h1>
            <p class="text-gray-600 mt-1">Kelola semua pengajuan pengembalian dana dari pelanggan Anda.</p>
        </div>
        <div class="flex space-x-3">
            <button class="btn-secondary-custom" onclick="refreshData()">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
            </button>
            <button class="btn-primary-custom" onclick="exportData()">
                <i class="fas fa-download mr-2"></i> Export Data
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="card-modern p-4 border-l-4 border-blue-500">
            <div class="flex flex-col">
                <span class="text-xs font-medium text-blue-600 uppercase tracking-wider mb-1">Total</span>
                <span class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</span>
            </div>
        </div>
        <div class="card-modern p-4 border-l-4 border-yellow-500">
            <div class="flex flex-col">
                <span class="text-xs font-medium text-yellow-600 uppercase tracking-wider mb-1">Menunggu Review</span>
                <span class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</span>
            </div>
        </div>
        <div class="card-modern p-4 border-l-4 border-indigo-500">
            <div class="flex flex-col">
                <span class="text-xs font-medium text-indigo-600 uppercase tracking-wider mb-1">Dalam Review</span>
                <span class="text-2xl font-bold text-gray-900">{{ $stats['in_review'] }}</span>
            </div>
        </div>
        <div class="card-modern p-4 border-l-4 border-green-500">
            <div class="flex flex-col">
                <span class="text-xs font-medium text-green-600 uppercase tracking-wider mb-1">Disetujui</span>
                <span class="text-2xl font-bold text-gray-900">{{ $stats['approved'] }}</span>
            </div>
        </div>
        <div class="card-modern p-4 border-l-4 border-gray-500">
            <div class="flex flex-col">
                <span class="text-xs font-medium text-gray-600 uppercase tracking-wider mb-1">Selesai</span>
                <span class="text-2xl font-bold text-gray-900">{{ $stats['completed'] }}</span>
            </div>
        </div>
         <div class="card-modern p-4 border-l-4 border-red-500">
            <div class="flex flex-col">
                <span class="text-xs font-medium text-red-600 uppercase tracking-wider mb-1">Ditolak / Lainnya</span>
                <span class="text-2xl font-bold text-gray-900">{{ $stats['total'] - $stats['pending'] - $stats['in_review'] - $stats['approved'] - $stats['completed'] }}</span>
            </div>
        </div>
    </div>

    <div class="card-modern mb-6">
        <div class="card-header-modern">
            <h3 class="text-lg font-semibold text-gray-800">Filter Pengajuan</h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.pengembalian.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="filter-input">
                            <option value="">Semua Status</option>
                            <option value="Menunggu Review" {{ request('status') === 'Menunggu Review' ? 'selected' : '' }}>Menunggu Review</option>
                            <option value="Dalam Review" {{ request('status') === 'Dalam Review' ? 'selected' : '' }}>Dalam Review</option>
                            <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                            <option value="Dana Dikembalikan" {{ request('status') === 'Dana Dikembalikan' ? 'selected' : '' }}>Dana Dikembalikan</option>
                            <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="md:col-span-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                        <input type="text" name="search" id="search" class="filter-input"
                               value="{{ request('search') }}"
                               placeholder="ID pesanan, nama pelanggan, atau keluhan...">
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" class="btn-primary-custom flex-1">
                            <i class="fas fa-filter mr-2"></i> Terapkan
                        </button>
                        <a href="{{ route('admin.pengembalian.index') }}" class="btn-secondary-custom">
                            <i class="fas fa-redo-alt"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card-modern">
        <div class="card-header-modern">
            <h3 class="text-lg font-semibold text-gray-800">Daftar Pengajuan Pengembalian</h3>
        </div>
        <div class="p-6">
            @if($pengembalian->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                    <h5 class="text-lg font-medium text-gray-900">Tidak Ada Pengajuan Pengembalian</h5>
                    <p class="text-gray-500">Belum ada pengajuan pengembalian yang masuk sesuai filter Anda.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="table-header-custom">
                            <tr>
                                <th class="px-6 py-3 text-left">ID</th>
                                <th class="px-6 py-3 text-left">Pelanggan</th>
                                <th class="px-6 py-3 text-left">Pesanan</th>
                                <th class="px-6 py-3 text-left">Keluhan</th>
                                <th class="px-6 py-3 text-right">Jumlah Refund</th>
                                <th class="px-6 py-3 text-center">Status</th>
                                <th class="px-6 py-3 text-left">Tanggal</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pengembalian as $item)
                                <tr class="table-row-hover">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $item->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 text-sm font-semibold mr-2">
                                                {{ substr($item->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $item->user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $item->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.pesanan.show', $item->pesanan->id_pesanan) }}"
                                           class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                                            #{{ $item->pesanan->id_pesanan }}
                                        </a>
                                        <div class="text-xs text-gray-500">Rp {{ number_format($item->pesanan->total_harga, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge bg-blue-100 text-blue-800">{{ $item->jenis_keluhan }}</span>
                                        <span class="status-badge bg-gray-100 text-gray-800">{{ $item->jenis_pengembalian }}</span>
                                        @if($item->deskripsi_keluhan)
                                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit($item->deskripsi_keluhan, 40) }}</div>
                                        @endif
                                        @if($item->foto_bukti)
                                            <div class="text-xs text-orange-600 mt-1">
                                                <i class="fas fa-camera mr-1"></i>Ada bukti foto
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-green-600">
                                        Rp {{ number_format($item->jumlah_refund, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @php
                                            $statusClass = strtolower(str_replace(' ', '-', $item->status_pengembalian));
                                        @endphp
                                        <span class="status-badge status-{{ $statusClass }}">
                                            {{ $item->status_pengembalian }}
                                        </span>
                                        @if($item->status_pengembalian === 'Menunggu Review')
                                            <div class="text-xs text-gray-500 mt-1">{{ $item->created_at->diffForHumans() }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $item->created_at->format('d M Y') }}
                                        <div class="text-xs text-gray-500">{{ $item->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('admin.pengembalian.show', $item->id) }}"
                                               class="btn-action-outline btn-action-outline-primary" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($item->status_pengembalian === 'Menunggu Review')
                                                <button class="btn-action-outline btn-action-outline-success"
                                                        onclick="showQuickApproveModal({{ $item->id }})" title="Setujui Cepat">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn-action-outline btn-action-outline-danger"
                                                        onclick="showQuickRejectModal({{ $item->id }})" title="Tolak Cepat">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @elseif($item->status_pengembalian === 'Disetujui')
                                                <button class="btn-action-outline btn-action-outline-primary"
                                                        onclick="showRefundModal({{ $item->id }})" title="Konfirmasi Pengembalian Dana">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($pengembalian->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $pengembalian->appends(request()->query())->links('pagination::tailwind') }}
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Quick Approve Modal - Tailwind Styled -->
<div id="quickApproveModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="quickApproveModalLabel" aria-modal="true" role="dialog">
    <div class="modal-backdrop fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
    
    <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
        <div class="relative bg-white rounded-lg shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
            <div class="bg-green-600 px-4 py-3 rounded-t-lg flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-white" id="quickApproveModalLabel">
                    <i class="fas fa-check-circle mr-2"></i>Setujui Pengajuan Pengembalian
                </h3>
                <button type="button" class="text-white hover:text-gray-200 modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="quickApproveForm" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mb-4">
                        <label for="approveNote" class="block text-sm font-medium text-gray-700 mb-1">
                            Catatan (Opsional)
                        </label>
                        <textarea 
                            name="catatan_admin" 
                            id="approveNote" 
                            rows="3"
                            class="shadow-sm focus:ring-green-500 focus:border-green-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                            placeholder="Berikan catatan jika diperlukan..."></textarea>
                        <p class="mt-1 text-xs text-gray-500">
                            Catatan ini akan ditampilkan kepada pelanggan saat melihat detail pengembalian
                        </p>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-check mr-2"></i> Setujui
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm modal-close" data-bs-dismiss="modal">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Reject Modal - Tailwind Styled -->
<div id="quickRejectModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="quickRejectModalLabel" aria-modal="true" role="dialog">
    <div class="modal-backdrop fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
    
    <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
        <div class="relative bg-white rounded-lg shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
            <div class="bg-red-600 px-4 py-3 rounded-t-lg flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-white" id="quickRejectModalLabel">
                    <i class="fas fa-times-circle mr-2"></i>Tolak Pengajuan Pengembalian
                </h3>
                <button type="button" class="text-white hover:text-gray-200 modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="quickRejectForm" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mb-4">
                        <div class="flex items-center mb-2">
                            <label for="rejectNote" class="block text-sm font-medium text-gray-700">
                                Alasan Penolakan
                            </label>
                            <span class="ml-1 text-red-600 text-sm">*</span>
                        </div>
                        <textarea 
                            name="catatan_admin" 
                            id="rejectNote" 
                            rows="3"
                            class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                            placeholder="Jelaskan alasan penolakan secara detail..." 
                            required></textarea>
                        <p class="mt-1 text-xs text-gray-500">
                            Berikan alasan yang jelas dan proses selanjutnya yang dapat dilakukan pelanggan
                        </p>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-times mr-2"></i> Tolak
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm modal-close" data-bs-dismiss="modal">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Refund Modal - Tailwind Styled -->
<div id="refundModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="refundModalLabel" aria-modal="true" role="dialog">
    <div class="modal-backdrop fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
    
    <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
        <div class="relative bg-white rounded-lg shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
            <div class="bg-orange-600 px-4 py-3 rounded-t-lg flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-white" id="refundModalLabel">
                    <i class="fas fa-money-bill-wave mr-2"></i>Konfirmasi Pengembalian Dana
                </h3>
                <button type="button" class="text-white hover:text-gray-200 modal-close" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="refundForm" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!-- Transaction Date -->
                    <div class="mb-4">
                        <div class="flex items-center mb-1">
                            <label for="refundDate" class="block text-sm font-medium text-gray-700">
                                Tanggal Transfer
                            </label>
                            <span class="ml-1 text-red-600 text-sm">*</span>
                        </div>
                        <input 
                            type="date" 
                            name="tanggal_transfer" 
                            id="refundDate"
                            class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                            value="{{ date('Y-m-d') }}"
                            required>
                    </div>
                    
                    <!-- Transaction Number -->
                    <div class="mb-4">
                        <div class="flex items-center mb-1">
                            <label for="refundTransactionNumber" class="block text-sm font-medium text-gray-700">
                                Nomor Transaksi
                            </label>
                            <span class="ml-1 text-red-600 text-sm">*</span>
                        </div>
                        <input 
                            type="text" 
                            name="nomor_transaksi_pengembalian" 
                            id="refundTransactionNumber"
                            class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                            placeholder="Masukkan nomor transaksi bank" 
                            required>
                        <p class="mt-1 text-xs text-gray-500">
                            Nomor transaksi atau referensi untuk memudahkan pelacakan transfer
                        </p>
                    </div>
                    
                    <!-- Admin Note -->
                    <div class="mb-4">
                        <label for="refundNote" class="block text-sm font-medium text-gray-700 mb-1">
                            Catatan (Opsional)
                        </label>
                        <textarea 
                            name="catatan_admin" 
                            id="refundNote" 
                            rows="2"
                            class="shadow-sm focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                            placeholder="Catatan tambahan tentang transfer..."></textarea>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-money-bill-wave mr-2"></i> Konfirmasi Transfer
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm modal-close" data-bs-dismiss="modal">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Modal utility functions
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('hidden');
    
    // Trigger animation
    setTimeout(() => {
        const backdrop = modal.querySelector('.modal-backdrop');
        const dialog = modal.querySelector('.relative');
        
        backdrop.classList.add('opacity-100');
        dialog.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        dialog.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
    }, 10);
    
    // Add event listeners
    setupModalListeners(modalId);
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('hidden');
    
    // Remove event listeners
    const closeButtons = modal.querySelectorAll('.modal-close');
    closeButtons.forEach(button => {
        button.removeEventListener('click', () => hideModal(modalId));
    });
}

function setupModalListeners(modalId) {
    const modal = document.getElementById(modalId);
    
    // Close buttons
    const closeButtons = modal.querySelectorAll('.modal-close');
    closeButtons.forEach(button => {
        button.addEventListener('click', () => hideModal(modalId));
    });
    
    // Backdrop click
    const backdrop = modal.querySelector('.modal-backdrop');
    backdrop.addEventListener('click', () => hideModal(modalId));
    
    // Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            hideModal(modalId);
        }
    });
}

// Show modals with proper setup
function showQuickApproveModal(id) {
    const form = document.getElementById('quickApproveForm');
    form.action = `/admin/pengembalian/${id}/approve`;
    
    // Get return details for the modal title
    const returnRow = document.querySelector(`tr[data-id="${id}"]`);
    const returnIdText = returnRow ? `#${id}` : `#${id}`;
    
    // Update modal title with return ID
    const modalTitle = document.getElementById('quickApproveModalLabel');
    if (modalTitle) {
        modalTitle.innerHTML = `<i class="fas fa-check-circle mr-2"></i>Setujui Pengajuan ${returnIdText}`;
    }
    
    showModal('quickApproveModal');
}

function showQuickRejectModal(id) {
    const form = document.getElementById('quickRejectForm');
    form.action = `/admin/pengembalian/${id}/reject`;
    
    // Get return details for the modal title
    const returnRow = document.querySelector(`tr[data-id="${id}"]`);
    const returnIdText = returnRow ? `#${id}` : `#${id}`;
    
    // Update modal title with return ID
    const modalTitle = document.getElementById('quickRejectModalLabel');
    if (modalTitle) {
        modalTitle.innerHTML = `<i class="fas fa-times-circle mr-2"></i>Tolak Pengajuan ${returnIdText}`;
    }
    
    showModal('quickRejectModal');
}

function showRefundModal(id) {
    const form = document.getElementById('refundForm');
    form.action = `/admin/pengembalian/${id}/mark-refunded`;
    
    // Get return details for the modal title
    const returnRow = document.querySelector(`tr[data-id="${id}"]`);
    const returnIdText = returnRow ? `#${id}` : `#${id}`;
    
    // Update modal title with return ID
    const modalTitle = document.getElementById('refundModalLabel');
    if (modalTitle) {
        modalTitle.innerHTML = `<i class="fas fa-money-bill-wave mr-2"></i>Konfirmasi Pengembalian Dana ${returnIdText}`;
    }
    
    // Set today's date as default
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('refundDate').value = today;
    
    showModal('refundModal');
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('#quickApproveForm, #quickRejectForm, #refundForm');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    
                    // Add error message if not exists
                    const errorId = `${field.id}-error`;
                    if (!document.getElementById(errorId)) {
                        const errorMsg = document.createElement('p');
                        errorMsg.id = errorId;
                        errorMsg.className = 'mt-1 text-sm text-red-600';
                        errorMsg.textContent = 'Bidang ini wajib diisi';
                        field.parentNode.appendChild(errorMsg);
                    }
                } else {
                    field.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    const errorMsg = document.getElementById(`${field.id}-error`);
                    if (errorMsg) errorMsg.remove();
                }
            });
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
});

function refreshData() {
    // Show loading indicator
    const loadingToast = createToast('Memuat ulang data...', 'loading');
    
    // Reload page
    window.location.reload();
}

function exportData() {
    // Create toast notification
    createToast('Mempersiapkan data untuk ekspor...', 'info');
    
    // Future implementation - replace with actual export logic
    setTimeout(() => {
        createToast('Fitur export data akan segera diimplementasikan!', 'warning');
    }, 1000);
}

// Toast notification system
function createToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast-notification');
    existingToasts.forEach((toast, index) => {
        // Stagger removal to prevent visual glitches
        setTimeout(() => {
            toast.classList.add('opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, index * 100);
    });
    
    // Create toast container if not exists
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'fixed bottom-4 right-4 z-50 flex flex-col space-y-2';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast
    const toast = document.createElement('div');
    toast.className = 'toast-notification bg-white rounded-lg shadow-lg border-l-4 p-4 transform transition-all duration-300 opacity-0 translate-x-2';
    
    // Set toast style based on type
    let icon = '';
    switch (type) {
        case 'success':
            toast.classList.add('border-green-500');
            icon = '<i class="fas fa-check-circle text-green-500 mr-2"></i>';
            break;
        case 'error':
            toast.classList.add('border-red-500');
            icon = '<i class="fas fa-times-circle text-red-500 mr-2"></i>';
            break;
        case 'warning':
            toast.classList.add('border-yellow-500');
            icon = '<i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>';
            break;
        case 'loading':
            toast.classList.add('border-blue-500');
            icon = '<i class="fas fa-circle-notch fa-spin text-blue-500 mr-2"></i>';
            break;
        default:
            toast.classList.add('border-orange-500');
            icon = '<i class="fas fa-info-circle text-orange-500 mr-2"></i>';
    }
    
    toast.innerHTML = `
        <div class="flex items-center">
            ${icon}
            <span class="text-sm font-medium text-gray-800">${message}</span>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    // Show the toast
    setTimeout(() => {
        toast.classList.remove('opacity-0', 'translate-x-2');
    }, 10);
    
    // Auto hide after 4 seconds (except for loading)
    if (type !== 'loading') {
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-x-2');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 4000);
    }
    
    return toast;
}

// Auto-refresh for pending reviews every 30 seconds
setInterval(function() {
    if (window.location.search.includes('status=Menunggu Review') || window.location.search === '') {
        const pendingCount = {{ $stats['pending'] }};
        if (pendingCount > 0) {
            // Check for new returns via fetch API
            fetch('/admin/pengembalian/check-new')
                .then(response => response.json())
                .then(data => {
                    if (data.hasNew) {
                        createToast(`${data.count} pengajuan pengembalian baru menunggu review!`, 'info');
                    }
                })
                .catch(() => console.log('Failed to check for new returns'));
        }
    }
}, 30000);

// Add data-id attributes to rows for modal reference
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const idCell = row.querySelector('td:first-child');
        if (idCell) {
            const idText = idCell.textContent.trim();
            const id = idText.replace('#', '');
            row.setAttribute('data-id', id);
        }
    });
});
</script>
@endpush
