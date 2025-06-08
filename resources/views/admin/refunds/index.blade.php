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
@endsection

@push('scripts')
<script>
let currentRefundId = null;

function processRefund(refundId, status = null) {
    currentRefundId = refundId;

    let icon, title, text, confirmButtonText, confirmButtonColor;

    switch(status) {
        case 'approved':
            icon = 'question';
            title = 'Setujui Refund';
            text = `Apakah Anda yakin ingin menyetujui refund #${refundId}?`;
            confirmButtonText = 'Ya, Setujui';
            confirmButtonColor = '#10b981';
            break;
        case 'rejected':
            icon = 'warning';
            title = 'Tolak Refund';
            text = `Apakah Anda yakin ingin menolak refund #${refundId}?`;
            confirmButtonText = 'Ya, Tolak';
            confirmButtonColor = '#dc2626';
            break;
        default:
            icon = 'info';
            title = 'Proses Refund';
            text = `Pilih status untuk refund #${refundId}`;
            confirmButtonText = 'Proses';
            confirmButtonColor = '#f97316';
    }

    if (status === 'approved' || status === 'rejected') {
        // Show direct confirmation for approve/reject
        Swal.fire({
            title: title,
            text: text,
            icon: icon,
            input: 'textarea',
            inputLabel: 'Catatan Admin (Opsional)',
            inputPlaceholder: 'Tambahkan catatan untuk pelanggan...',
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'animate__animated animate__fadeInDown',
                confirmButton: 'btn btn-confirm',
                cancelButton: 'btn btn-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const adminNotes = result.value || '';
                submitProcessDirect(refundId, status, adminNotes);
            }
        });
    } else {
        // Show detailed form for other statuses
        Swal.fire({
            title: 'Proses Refund',
            html: `
                <div class="text-left">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="swal-status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="approved">Setujui</option>
                            <option value="rejected">Tolak</option>
                            <option value="processing">Proses</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                        <textarea id="swal-notes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Tambahkan catatan untuk pelanggan..."></textarea>
                    </div>
                    <div id="swal-refund-method" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Refund</label>
                        <select id="swal-method" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="bank_transfer">Transfer Bank</option>
                            <option value="wallet">E-Wallet</option>
                            <option value="store_credit">Kredit Toko</option>
                        </select>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Proses Refund',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            didOpen: () => {
                // Handle status change
                const statusSelect = document.getElementById('swal-status');
                const refundMethodDiv = document.getElementById('swal-refund-method');

                statusSelect.addEventListener('change', function() {
                    if (this.value === 'approved' || this.value === 'processing') {
                        refundMethodDiv.classList.remove('hidden');
                    } else {
                        refundMethodDiv.classList.add('hidden');
                    }
                });
            },
            preConfirm: () => {
                const status = document.getElementById('swal-status').value;
                const notes = document.getElementById('swal-notes').value;
                const method = document.getElementById('swal-method').value;

                return { status, notes, method };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { status, notes, method } = result.value;
                submitProcessDetailed(refundId, status, notes, method);
            }
        });
    }
}

function submitProcessDirect(refundId, status, adminNotes) {
    // Show loading
    Swal.fire({
        title: 'Memproses...',
        text: 'Sedang memproses refund',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('status', status);
    formData.append('admin_notes', adminNotes);

    fetch(`/admin/refunds/${refundId}/process`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: `Refund berhasil ${status === 'approved' ? 'disetujui' : 'ditolak'}`,
                icon: 'success',
                confirmButtonColor: '#10b981',
                customClass: {
                    popup: 'animate__animated animate__fadeInUp'
                }
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: 'Gagal memproses refund',
                icon: 'error',
                confirmButtonColor: '#dc2626',
                customClass: {
                    popup: 'animate__animated animate__shakeX'
                }
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan saat memproses refund',
            icon: 'error',
            confirmButtonColor: '#dc2626',
            customClass: {
                popup: 'animate__animated animate__shakeX'
            }
        });
    });
}

function submitProcessDetailed(refundId, status, adminNotes, refundMethod) {
    // Show loading
    Swal.fire({
        title: 'Memproses...',
        text: 'Sedang memproses refund',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('status', status);
    formData.append('admin_notes', adminNotes);

    if (status === 'approved' || status === 'processing') {
        formData.append('refund_method', refundMethod);
    }

    fetch(`/admin/refunds/${refundId}/process`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Refund berhasil diproses',
                icon: 'success',
                confirmButtonColor: '#10b981',
                customClass: {
                    popup: 'animate__animated animate__fadeInUp'
                }
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                title: 'Gagal!',
                text: 'Gagal memproses refund',
                icon: 'error',
                confirmButtonColor: '#dc2626',
                customClass: {
                    popup: 'animate__animated animate__shakeX'
                }
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Error!',
            text: 'Terjadi kesalahan saat memproses refund',
            icon: 'error',
            confirmButtonColor: '#dc2626',
            customClass: {
                popup: 'animate__animated animate__shakeX'
            }
        });
    });
}

// Legacy functions for backward compatibility
function closeProcessModal() {
    // No longer needed with SweetAlert2
}

function submitProcess() {
    // No longer needed with SweetAlert2
}
</script>
@endpush
