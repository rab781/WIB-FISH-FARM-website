@extends('admin.layouts.app')

@section('title', 'Manajemen Refund')

@push('styles')
<style>
    .status-badge {
        @apply px-3 py-1 rounded-full text-sm font-medium;
    }
    .status-pending { @apply bg-yellow-100 text-yellow-800; }
    .status-approved { @apply bg-green-100 text-green-800; }
    .status-rejected { @apply bg-red-100 text-red-800; }
    .status-processing { @apply bg-blue-100 text-blue-800; }
    .status-completed { @apply bg-gray-100 text-gray-800; }
</style>
@endpush

@section('header', 'Manajemen Refund')

@section('content')
<div class="p-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
                    <p class="text-gray-600">Pending</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved'] ?? 0 }}</p>
                    <p class="text-gray-600">Disetujui</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['rejected'] ?? 0 }}</p>
                    <p class="text-gray-600">Ditolak</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['processing'] ?? 0 }}</p>
                    <p class="text-gray-600">Diproses</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['total_amount'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-gray-600">Total Refund</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-64">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Refund</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nomor pesanan, nama pelanggan, atau ID refund..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan</label>
                    <select name="reason" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Semua Alasan</option>
                        <option value="defective" {{ request('reason') == 'defective' ? 'selected' : '' }}>Produk Rusak</option>
                        <option value="wrong_item" {{ request('reason') == 'wrong_item' ? 'selected' : '' }}>Barang Salah</option>
                        <option value="not_as_described" {{ request('reason') == 'not_as_described' ? 'selected' : '' }}>Tidak Sesuai Deskripsi</option>
                        <option value="dead_fish" {{ request('reason') == 'dead_fish' ? 'selected' : '' }}>Ikan Mati</option>
                        <option value="other" {{ request('reason') == 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.refunds.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        Reset
                    </a>
                    <a href="{{ route('admin.refunds.export') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        <i class="fas fa-download mr-2"></i>Export
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Refund List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Daftar Refund</h3>
                <div class="text-sm text-gray-500">
                    Total: {{ $refunds->total() }} refund
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID & Pesanan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pelanggan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Alasan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jumlah
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($refunds as $refund)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $refund->id }}</div>
                            <div class="text-sm text-gray-500">{{ $refund->pesanan->nomor_pesanan }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $refund->pesanan->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $refund->pesanan->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                @switch($refund->reason)
                                    @case('defective') Produk Rusak @break
                                    @case('wrong_item') Barang Salah @break
                                    @case('not_as_described') Tidak Sesuai Deskripsi @break
                                    @case('dead_fish') Ikan Mati @break
                                    @default Lainnya
                                @endswitch
                            </div>
                            @if($refund->evidence_photos)
                                <div class="text-xs text-orange-600 mt-1">
                                    <i class="fas fa-camera mr-1"></i>Ada bukti foto
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($refund->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="status-badge status-{{ $refund->status }}">
                                @switch($refund->status)
                                    @case('pending') Pending @break
                                    @case('approved') Disetujui @break
                                    @case('rejected') Ditolak @break
                                    @case('processing') Diproses @break
                                    @case('completed') Selesai @break
                                    @default {{ ucfirst($refund->status) }}
                                @endswitch
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $refund->created_at->format('d/m/Y') }}
                            <div class="text-xs text-gray-500">{{ $refund->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.refunds.show', $refund) }}"
                                   class="text-orange-600 hover:text-orange-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($refund->status === 'pending')
                                <button onclick="processRefund({{ $refund->id }}, 'approved')"
                                        class="text-green-600 hover:text-green-900" title="Setujui">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button onclick="processRefund({{ $refund->id }}, 'rejected')"
                                        class="text-red-600 hover:text-red-900" title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data refund ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($refunds->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $refunds->appends(request()->all())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Process Refund Modal -->
<div id="processModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Proses Refund</h3>

                <form id="processForm">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="statusSelect" name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="approved">Setujui</option>
                            <option value="rejected">Tolak</option>
                            <option value="processing">Proses</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                        <textarea id="notesTextarea" name="admin_notes" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2"
                                  placeholder="Tambahkan catatan untuk pelanggan..."></textarea>
                    </div>

                    <div id="refundMethodDiv" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Refund</label>
                        <select id="refundMethodSelect" name="refund_method" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="bank_transfer">Transfer Bank</option>
                            <option value="wallet">E-Wallet</option>
                            <option value="store_credit">Kredit Toko</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="submitProcess()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    Proses
                </button>
                <button type="button" onclick="closeProcessModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentRefundId = null;

function processRefund(refundId, status = null) {
    currentRefundId = refundId;
    if (status) {
        document.getElementById('statusSelect').value = status;
    }

    // Show/hide refund method based on status
    const refundMethodDiv = document.getElementById('refundMethodDiv');
    if (status === 'approved' || status === 'processing') {
        refundMethodDiv.classList.remove('hidden');
    } else {
        refundMethodDiv.classList.add('hidden');
    }

    document.getElementById('processModal').classList.remove('hidden');
}

function closeProcessModal() {
    document.getElementById('processModal').classList.add('hidden');
    currentRefundId = null;
}

function submitProcess() {
    if (!currentRefundId) return;

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('status', document.getElementById('statusSelect').value);
    formData.append('admin_notes', document.getElementById('notesTextarea').value);

    const refundMethod = document.getElementById('refundMethodSelect').value;
    if (!document.getElementById('refundMethodDiv').classList.contains('hidden')) {
        formData.append('refund_method', refundMethod);
    }

    fetch(`/admin/refunds/${currentRefundId}/process`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal memproses refund');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

// Show/hide refund method based on status selection
document.getElementById('statusSelect').addEventListener('change', function() {
    const refundMethodDiv = document.getElementById('refundMethodDiv');
    if (this.value === 'approved' || this.value === 'processing') {
        refundMethodDiv.classList.remove('hidden');
    } else {
        refundMethodDiv.classList.add('hidden');
    }
});

// Close modal when clicking outside
document.getElementById('processModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProcessModal();
    }
});
</script>
@endpush
