@extends('admin.layouts.app')

@section('title', 'Detail Karantina')

@push('styles')
<style>
    .status-badge {
        @apply px-3 py-1 rounded-full text-sm font-medium;
    }
    .status-active { @apply bg-orange-100 text-orange-800; }
    .status-completed { @apply bg-green-100 text-green-800; }
    .status-failed { @apply bg-red-100 text-red-800; }
    .status-pending { @apply bg-yellow-100 text-yellow-800; }

    .log-item {
        @apply border-l-4 pl-4 py-3;
    }
    .log-normal { @apply border-blue-400 bg-blue-50; }
    .log-warning { @apply border-yellow-400 bg-yellow-50; }
    .log-success { @apply border-green-400 bg-green-50; }
    .log-danger { @apply border-red-400 bg-red-50; }
</style>
@endpush

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h2 class="text-xl font-semibold">Detail Karantina</h2>
        <p class="text-gray-600">Pesanan: {{ $quarantine->pesanan->nomor_pesanan }}</p>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('admin.quarantine.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        @if($quarantine->status === 'active')
        <button onclick="openUpdateModal()" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700">
            <i class="fas fa-edit mr-2"></i>Update Status
        </button>
        @endif
    </div>
</div>
@endsection

@section('content')
<div class="p-6 space-y-6">
    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Quarantine Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Karantina</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-sm text-gray-500">Status:</span>
                    <span class="status-badge status-{{ $quarantine->status }} ml-2">
                        {{ ucfirst($quarantine->status) }}
                    </span>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Mulai:</span>
                    <span class="text-sm font-medium text-gray-900 ml-2">{{ $quarantine->started_at->format('d/m/Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Selesai:</span>
                    <span class="text-sm font-medium text-gray-900 ml-2">{{ $quarantine->expected_end_date->format('d/m/Y') }}</span>
                </div>
                @if($quarantine->completed_at)
                <div>
                    <span class="text-sm text-gray-500">Diselesaikan:</span>
                    <span class="text-sm font-medium text-gray-900 ml-2">{{ $quarantine->completed_at->format('d/m/Y H:i') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Progress -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Progress</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-600">Kemajuan</span>
                        <span class="text-sm font-medium">{{ $quarantine->progress_percentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-orange-600 h-3 rounded-full transition-all duration-300" style="width: {{ $quarantine->progress_percentage }}%"></div>
                    </div>
                </div>
                <div class="text-sm text-gray-600">
                    Hari ke-{{ $quarantine->days_elapsed }} dari {{ $quarantine->total_days }} hari
                </div>
            </div>
        </div>

        <!-- Order Info -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pesanan</h3>
            <div class="space-y-3">
                <div>
                    <span class="text-sm text-gray-500">Pelanggan:</span>
                    <span class="text-sm font-medium text-gray-900 ml-2">{{ $quarantine->pesanan->user->name }}</span>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Produk:</span>
                    <span class="text-sm font-medium text-gray-900 ml-2">{{ $quarantine->pesanan->produk->nama ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Total:</span>
                    <span class="text-sm font-medium text-gray-900 ml-2">Rp {{ number_format($quarantine->pesanan->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="pt-2">
                    <a href="{{ route('admin.pesanan.show', $quarantine->pesanan->id) }}"
                       class="text-orange-600 hover:text-orange-800 text-sm">
                        <i class="fas fa-external-link-alt mr-1"></i>Lihat Detail Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Logs -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Log Harian Karantina</h3>
        </div>
        <div class="p-6">
            @if($quarantine->logs->count() > 0)
                <div class="space-y-4">
                    @foreach($quarantine->logs->sortByDesc('check_date') as $log)
                    <div class="log-item log-{{ $log->status === 'healthy' ? 'success' : ($log->status === 'sick' ? 'danger' : ($log->status === 'warning' ? 'warning' : 'normal')) }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-gray-900">Hari ke-{{ $log->day_number }}</span>
                                    <span class="text-sm text-gray-500">{{ $log->check_date->format('d/m/Y') }}</span>
                                    @if($log->status === 'healthy')
                                        <span class="text-green-600"><i class="fas fa-check-circle"></i></span>
                                    @elseif($log->status === 'sick')
                                        <span class="text-red-600"><i class="fas fa-exclamation-triangle"></i></span>
                                    @elseif($log->status === 'warning')
                                        <span class="text-yellow-600"><i class="fas fa-exclamation-circle"></i></span>
                                    @endif
                                </div>
                                @if($log->notes)
                                <p class="text-sm text-gray-600 mt-1">{{ $log->notes }}</p>
                                @endif
                                @if($log->temperature)
                                <p class="text-sm text-gray-600">Suhu: {{ $log->temperature }}°C</p>
                                @endif
                                @if($log->ph_level)
                                <p class="text-sm text-gray-600">pH: {{ $log->ph_level }}</p>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $log->checked_by_admin ? 'Diperiksa oleh Admin' : 'Otomatis' }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-clipboard-list text-4xl mb-4"></i>
                    <p>Belum ada log karantina untuk pesanan ini.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Add New Log -->
    @if($quarantine->status === 'active')
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Tambah Log Baru</h3>
        </div>
        <div class="p-6">
            <form id="addLogForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="healthy">Sehat</option>
                            <option value="warning">Peringatan</option>
                            <option value="sick">Sakit</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Suhu (°C)</label>
                        <input type="number" name="temperature" step="0.1"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="26.5">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">pH</label>
                        <input type="number" name="ph_level" step="0.1"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="7.0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pemeriksaan</label>
                        <input type="date" name="check_date" value="{{ date('Y-m-d') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="notes" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-orange-500 focus:border-orange-500"
                              placeholder="Catatan pemeriksaan..."></textarea>
                </div>
                <div class="mt-4">
                    <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700">
                        <i class="fas fa-plus mr-2"></i>Tambah Log
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<!-- Update Status Modal -->
<div id="updateModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Update Status Karantina</h3>

                <form id="updateForm">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="statusSelect" name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="active" {{ $quarantine->status === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="completed" {{ $quarantine->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="failed" {{ $quarantine->status === 'failed' ? 'selected' : '' }}>Gagal</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea id="notesTextarea" name="notes" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2"
                                  placeholder="Tambahkan catatan...">{{ $quarantine->admin_notes }}</textarea>
                    </div>
                </form>
            </div>

            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="submitUpdate()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    Update
                </button>
                <button type="button" onclick="closeUpdateModal()"
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
function openUpdateModal() {
    document.getElementById('updateModal').classList.remove('hidden');
}

function closeUpdateModal() {
    document.getElementById('updateModal').classList.add('hidden');
}

function submitUpdate() {
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('status', document.getElementById('statusSelect').value);
    formData.append('notes', document.getElementById('notesTextarea').value);

    fetch(`/admin/quarantine/{{ $quarantine->id }}/update`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal mengupdate status karantina');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

// Add log form submission
document.getElementById('addLogForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    fetch(`/admin/quarantine/{{ $quarantine->id }}/add-log`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal menambah log');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
});

// Close modal when clicking outside
document.getElementById('updateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUpdateModal();
    }
});
</script>
@endpush
