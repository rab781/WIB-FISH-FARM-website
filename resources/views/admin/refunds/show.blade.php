@extends('admin.layouts.app')

@section('title', 'Detail Refund')

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

    .evidence-photo {
        @apply w-32 h-32 object-cover rounded-lg border-2 border-gray-200 cursor-pointer transition-transform hover:scale-105;
    }
</style>
@endpush

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold">Detail Refund</h2>
        <p class="text-gray-600">ID: #{{ $refund->id }} - {{ $refund->pesanan->nomor_pesanan }}</p>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('admin.refunds.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        @if($refund->status === 'pending')
        <button onclick="processRefund('approved')" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            <i class="fas fa-check mr-2"></i>Setujui
        </button>
        <button onclick="processRefund('rejected')" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
            <i class="fas fa-times mr-2"></i>Tolak
        </button>
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="p-6 space-y-6">
    <!-- Status & Quick Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Refund Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Status Refund</h3>
            <div class="space-y-3">
                <div>
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
                </div>
                <div>
                    <span class="text-sm text-gray-500">Tanggal Pengajuan:</span>
                    <div class="text-sm font-medium text-gray-900">{{ $refund->created_at->format('d/m/Y H:i') }}</div>
                </div>
                @if($refund->processed_at)
                <div>
                    <span class="text-sm text-gray-500">Tanggal Diproses:</span>
                    <div class="text-sm font-medium text-gray-900">{{ $refund->processed_at->format('d/m/Y H:i') }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Refund Amount -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Jumlah Refund</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-sm text-gray-500">Jumlah Diminta:</span>
                    <div class="text-2xl font-bold text-orange-600">Rp {{ number_format($refund->amount, 0, ',', '.') }}</div>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Total Pesanan:</span>
                    <div class="text-sm font-medium text-gray-900">Rp {{ number_format($refund->pesanan->total_harga, 0, ',', '.') }}</div>
                </div>
                @if($refund->refund_method)
                <div>
                    <span class="text-sm text-gray-500">Metode Refund:</span>
                    <div class="text-sm font-medium text-gray-900">
                        @switch($refund->refund_method)
                            @case('bank_transfer') Transfer Bank @break
                            @case('wallet') E-Wallet @break
                            @case('store_credit') Kredit Toko @break
                            @default {{ ucfirst($refund->refund_method) }}
                        @endswitch
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Customer Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pelanggan</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-sm text-gray-500">Nama:</span>
                    <div class="text-sm font-medium text-gray-900">{{ $refund->pesanan->user->name }}</div>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Email:</span>
                    <div class="text-sm font-medium text-gray-900">{{ $refund->pesanan->user->email }}</div>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Telepon:</span>
                    <div class="text-sm font-medium text-gray-900">{{ $refund->pesanan->user->phone ?? 'Tidak tersedia' }}</div>
                </div>
                <div class="pt-2">
                    <a href="{{ route('admin.pesanan.show', $refund->pesanan->id) }}"
                       class="text-orange-600 hover:text-orange-800 text-sm">
                        <i class="fas fa-external-link-alt mr-1"></i>Lihat Detail Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Refund Details -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Reason & Description -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Detail Refund</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Refund</label>
                    <div class="text-sm text-gray-900">
                        @switch($refund->reason)
                            @case('defective') Produk Rusak/Cacat @break
                            @case('wrong_item') Barang yang Diterima Salah @break
                            @case('not_as_described') Tidak Sesuai Deskripsi @break
                            @case('dead_fish') Ikan Mati saat Diterima @break
                            @case('other') Lainnya @break
                            @default {{ ucfirst($refund->reason) }}
                        @endswitch
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Masalah</label>
                    <div class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">
                        {{ $refund->description ?: 'Tidak ada deskripsi tambahan.' }}
                    </div>
                </div>

                @if($refund->admin_notes)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                    <div class="text-sm text-gray-900 bg-blue-50 p-3 rounded-lg border border-blue-200">
                        {{ $refund->admin_notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Evidence Photos -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Bukti Foto</h3>
            </div>
            <div class="p-6">
                @if($refund->evidence_photos && count($refund->evidence_photos) > 0)
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($refund->evidence_photos as $photo)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $photo) }}"
                                 alt="Bukti foto refund"
                                 class="evidence-photo"
                                 onclick="openPhotoModal('{{ asset('storage/' . $photo) }}')">
                            <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-search-plus text-white opacity-0 hover:opacity-100"></i>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-camera text-4xl mb-4"></i>
                        <p>Tidak ada bukti foto yang diunggah.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Details -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Detail Pesanan</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <span class="text-sm text-gray-500">Nomor Pesanan:</span>
                    <div class="text-sm font-medium text-gray-900">{{ $refund->pesanan->nomor_pesanan }}</div>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Tanggal Pesanan:</span>
                    <div class="text-sm font-medium text-gray-900">{{ $refund->pesanan->created_at->format('d/m/Y') }}</div>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Status Pesanan:</span>
                    <div class="text-sm font-medium text-gray-900">{{ ucfirst($refund->pesanan->status) }}</div>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Total Harga:</span>
                    <div class="text-sm font-medium text-gray-900">Rp {{ number_format($refund->pesanan->total_harga, 0, ',', '.') }}</div>
                </div>
            </div>

            @if($refund->pesanan->produk)
            <div class="mt-6 border-t border-gray-200 pt-6">
                <h4 class="text-md font-medium text-gray-900 mb-4">Produk</h4>
                <div class="flex items-center space-x-4">
                    @if($refund->pesanan->produk->gambar)
                    <img src="{{ asset('storage/' . $refund->pesanan->produk->gambar) }}"
                         alt="{{ $refund->pesanan->produk->nama }}"
                         class="w-16 h-16 object-cover rounded-lg">
                    @endif
                    <div>
                        <div class="text-sm font-medium text-gray-900">{{ $refund->pesanan->produk->nama }}</div>
                        <div class="text-sm text-gray-500">{{ $refund->pesanan->produk->deskripsi }}</div>
                        <div class="text-sm text-gray-500">Kuantitas: {{ $refund->pesanan->kuantitas }}</div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Action Panel -->
    @if($refund->status === 'pending' || $refund->status === 'approved')
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Tindakan</h3>
        </div>
        <div class="p-6">
            <form id="actionForm" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="pending" {{ $refund->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $refund->status === 'approved' ? 'selected' : '' }}>Disetujui</option>
                            <option value="rejected">Ditolak</option>
                            <option value="processing">Diproses</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Refund</label>
                        <select name="refund_method" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="">Pilih Metode</option>
                            <option value="bank_transfer" {{ $refund->refund_method === 'bank_transfer' ? 'selected' : '' }}>Transfer Bank</option>
                            <option value="wallet" {{ $refund->refund_method === 'wallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="store_credit" {{ $refund->refund_method === 'store_credit' ? 'selected' : '' }}>Kredit Toko</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                    <textarea name="admin_notes" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                              placeholder="Tambahkan catatan untuk pelanggan...">{{ $refund->admin_notes }}</textarea>
                </div>

                <div class="flex space-x-4">
                    <button type="submit" class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<!-- Photo Modal -->
<div id="photoModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-black opacity-75" onclick="closePhotoModal()"></div>
        </div>
        <div class="inline-block align-middle bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-4xl sm:w-full">
            <div class="bg-white">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Bukti Foto</h3>
                    <button onclick="closePhotoModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="p-4">
                    <img id="modalPhoto" src="" alt="Bukti foto" class="w-full h-auto max-h-96 object-contain">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function processRefund(status) {
    const form = document.getElementById('actionForm');
    const statusSelect = form.querySelector('select[name="status"]');
    statusSelect.value = status;

    // Submit the form
    submitActionForm();
}

function openPhotoModal(photoUrl) {
    document.getElementById('modalPhoto').src = photoUrl;
    document.getElementById('photoModal').classList.remove('hidden');
}

function closePhotoModal() {
    document.getElementById('photoModal').classList.add('hidden');
}

// Action form submission
document.getElementById('actionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    submitActionForm();
});

function submitActionForm() {
    const form = document.getElementById('actionForm');
    const formData = new FormData(form);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    fetch(`/admin/refunds/{{ $refund->id }}/process`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            Swal.fire({
                title: 'Gagal Memproses Refund',
                text: data.message || 'Terjadi kesalahan saat memproses refund',
                icon: 'error',
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Tutup',
                customClass: {
                    popup: 'rounded-lg',
                    confirmButton: 'rounded-md'
                }
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            title: 'Kesalahan Sistem',
            text: 'Terjadi kesalahan saat memproses refund. Silakan coba lagi.',
            icon: 'error',
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'Tutup',
            customClass: {
                popup: 'rounded-lg',
                confirmButton: 'rounded-md'
            }
        });
    });
}

// Close photo modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhotoModal();
    }
});
</script>
@endpush
