@extends('admin.layouts.app')

@section('title', 'Manajemen Pengembalian')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="card mb-4" style="background: linear-gradient(135deg, #ff8c00 0%, #e67e00 100%); border: none; border-radius: 15px;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-2 fw-bold">
                                <i class="fas fa-undo-alt me-2"></i>
                                Manajemen Pengembalian
                            </h3>
                            <p class="card-text mb-0 opacity-75">
                                Kelola semua pengajuan pengembalian dana dari pelanggan
                            </p>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-white text-dark px-3 py-2 fs-6">
                                <i class="fas fa-list me-1"></i>
                                Daftar Pengembalian
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-warning mb-2">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <h5 class="card-title text-warning">{{ $stats['menunggu'] ?? 0 }}</h5>
                            <p class="card-text text-muted">Menunggu Review</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-success mb-2">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <h5 class="card-title text-success">{{ $stats['disetujui'] ?? 0 }}</h5>
                            <p class="card-text text-muted">Disetujui</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-danger mb-2">
                                <i class="fas fa-times-circle fa-2x"></i>
                            </div>
                            <h5 class="card-title text-danger">{{ $stats['ditolak'] ?? 0 }}</h5>
                            <p class="card-text text-muted">Ditolak</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="text-info mb-2">
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                            </div>
                            <h5 class="card-title text-info">{{ $stats['total'] ?? 0 }}</h5>
                            <p class="card-text text-muted">Total Pengajuan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter and Search -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.pengembalian.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="Menunggu Review" {{ request('status') == 'Menunggu Review' ? 'selected' : '' }}>Menunggu Review</option>
                                    <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Dari</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Sampai</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Cari</label>
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari ID pesanan..." value="{{ request('search') }}">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Main Content Table -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>
                        Daftar Pengembalian
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if(isset($pengembalian) && $pengembalian->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="100">ID</th>
                                        <th>Pesanan</th>
                                        <th>Pelanggan</th>
                                        <th>Jumlah</th>
                                        <th>Alasan</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th width="150">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengembalian as $item)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">#{{ $item->id }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold">#{{ $item->pesanan->id_pesanan ?? 'N/A' }}</div>
                                            <small class="text-muted">Rp {{ number_format($item->pesanan->total_harga ?? 0, 0, ',', '.') }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $item->pesanan->user->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $item->pesanan->user->email ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-danger">Rp {{ number_format($item->jumlah_pengembalian, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <div style="max-width: 200px;">
                                                {{ Str::limit($item->alasan, 50) }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($item->status == 'Menunggu Review')
                                                <span class="badge bg-warning">{{ $item->status }}</span>
                                            @elseif($item->status == 'Disetujui')
                                                <span class="badge bg-success">{{ $item->status }}</span>
                                            @elseif($item->status == 'Ditolak')
                                                <span class="badge bg-danger">{{ $item->status }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $item->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $item->created_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.pengembalian.show', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($item->status == 'Menunggu Review')
                                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="approveRefund({{ $item->id }})">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="rejectRefund({{ $item->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if(method_exists($pengembalian, 'links'))
                            <div class="card-footer">
                                {{ $pengembalian->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-inbox fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted">Tidak ada data pengembalian</h5>
                            <p class="text-muted">Belum ada pengajuan pengembalian yang masuk.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approval Modal -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Persetujuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menyetujui pengembalian ini?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Dana akan dikembalikan ke pelanggan dan status pesanan akan diubah.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="confirmApproval">Ya, Setujui</button>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pengembalian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Alasan Penolakan</label>
                    <textarea class="form-control" id="rejectionReason" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmRejection">Tolak</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentRefundId = null;

    // Approve refund function
    window.approveRefund = function(id) {
        currentRefundId = id;
        const modal = new bootstrap.Modal(document.getElementById('approvalModal'));
        modal.show();
    };

    // Reject refund function
    window.rejectRefund = function(id) {
        currentRefundId = id;
        const modal = new bootstrap.Modal(document.getElementById('rejectionModal'));
        modal.show();
    };

    // Confirm approval
    document.getElementById('confirmApproval').addEventListener('click', function() {
        if (currentRefundId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/pengembalian/${currentRefundId}/approve`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    });

    // Confirm rejection
    document.getElementById('confirmRejection').addEventListener('click', function() {
        const reason = document.getElementById('rejectionReason').value.trim();

        if (!reason) {
            alert('Alasan penolakan harus diisi!');
            return;
        }

        if (currentRefundId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/pengembalian/${currentRefundId}/reject`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const reasonInput = document.createElement('input');
            reasonInput.type = 'hidden';
            reasonInput.name = 'alasan_penolakan';
            reasonInput.value = reason;

            form.appendChild(csrfToken);
            form.appendChild(reasonInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
});
</script>
@endpush
