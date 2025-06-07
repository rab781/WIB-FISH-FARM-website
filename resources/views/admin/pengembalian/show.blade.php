@extends('admin.layouts.app')

@section('title', 'Detail Pengajuan Pengembalian #' . $pengembalian->id)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">
                                <i class="fas fa-undo-alt me-2 text-warning"></i>
                                Detail Pengajuan Pengembalian #{{ $pengembalian->id }}
                            </h4>
                            <p class="text-muted mb-0">
                                Diajukan oleh {{ $pengembalian->user->name }} - {{ $pengembalian->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            @if($pengembalian->status_pengembalian === 'Menunggu Review')
                                <button class="btn btn-success" onclick="showApproveModal()">
                                    <i class="fas fa-check me-1"></i> Setujui
                                </button>
                                <button class="btn btn-danger" onclick="showRejectModal()">
                                    <i class="fas fa-times me-1"></i> Tolak
                                </button>
                            @elseif($pengembalian->status_pengembalian === 'Disetujui')
                                <button class="btn btn-primary" onclick="showRefundModal()">
                                    <i class="fas fa-money-bill-wave me-1"></i> Konfirmasi Transfer
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Status & Actions -->
        <div class="col-md-4">
            <!-- Current Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Status Pengajuan
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $statusColors = [
                            'Menunggu Review' => 'warning',
                            'Dalam Review' => 'info', 
                            'Disetujui' => 'success',
                            'Ditolak' => 'danger',
                            'Dana Dikembalikan' => 'primary',
                            'Selesai' => 'dark'
                        ];
                        $currentStatus = $pengembalian->status_pengembalian;
                    @endphp
                    
                    <div class="text-center mb-3">
                        <span class="badge bg-{{ $statusColors[$currentStatus] ?? 'secondary' }} fs-6 px-3 py-2">
                            {{ $currentStatus }}
                        </span>
                    </div>

                    <!-- Status Update Form -->
                    <form action="{{ route('admin.pengembalian.updateStatus', $pengembalian->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="status_pengembalian" class="form-label">Ubah Status</label>
                            <select name="status_pengembalian" id="status_pengembalian" class="form-select">
                                <option value="Menunggu Review" {{ $currentStatus === 'Menunggu Review' ? 'selected' : '' }}>Menunggu Review</option>
                                <option value="Dalam Review" {{ $currentStatus === 'Dalam Review' ? 'selected' : '' }}>Dalam Review</option>
                                <option value="Disetujui" {{ $currentStatus === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="Ditolak" {{ $currentStatus === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                <option value="Dana Dikembalikan" {{ $currentStatus === 'Dana Dikembalikan' ? 'selected' : '' }}>Dana Dikembalikan</option>
                                <option value="Selesai" {{ $currentStatus === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        
                        <div class="mb-3" id="transactionNumberField" style="display: none;">
                            <label for="nomor_transaksi_pengembalian" class="form-label">Nomor Transaksi</label>
                            <input type="text" name="nomor_transaksi_pengembalian" id="nomor_transaksi_pengembalian" 
                                   class="form-control" value="{{ $pengembalian->nomor_transaksi_pengembalian }}" 
                                   placeholder="Masukkan nomor transaksi">
                        </div>
                        
                        <div class="mb-3">
                            <label for="catatan_admin" class="form-label">Catatan Admin</label>
                            <textarea name="catatan_admin" id="catatan_admin" class="form-control" rows="3" 
                                      placeholder="Berikan catatan untuk pelanggan...">{{ $pengembalian->catatan_admin }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>
                        Informasi Pelanggan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-lg me-3">
                            <div class="avatar-circle bg-primary text-white">
                                {{ substr($pengembalian->user->name, 0, 2) }}
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $pengembalian->user->name }}</h6>
                            <p class="text-muted mb-0">{{ $pengembalian->user->email }}</p>
                        </div>
                    </div>
                    
                    @if($pengembalian->user->no_telepon)
                    <div class="mb-2">
                        <strong>Telepon:</strong> {{ $pengembalian->user->no_telepon }}
                    </div>
                    @endif
                    
                    <div class="mb-2">
                        <strong>Bergabung:</strong> {{ $pengembalian->user->created_at->format('d/m/Y') }}
                    </div>
                    
                    <a href="{{ route('admin.users.show', $pengembalian->user->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-1"></i> Lihat Profil
                    </a>
                </div>
            </div>

            <!-- Bank Information -->
            @if($pengembalian->bank_name || $pengembalian->account_number)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-university me-2"></i>
                        Informasi Rekening
                    </h5>
                </div>
                <div class="card-body">
                    @if($pengembalian->bank_name)
                        <div class="mb-2">
                            <strong>Bank:</strong> {{ $pengembalian->bank_name }}
                        </div>
                    @endif
                    @if($pengembalian->account_number)
                        <div class="mb-2">
                            <strong>No. Rekening:</strong> 
                            <code>{{ $pengembalian->account_number }}</code>
                        </div>
                    @endif
                    @if($pengembalian->account_holder_name)
                        <div class="mb-2">
                            <strong>Nama Pemegang:</strong> {{ $pengembalian->account_holder_name }}
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Review History -->
            @if($pengembalian->reviewedBy || $pengembalian->tanggal_review)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Riwayat Review
                    </h5>
                </div>
                <div class="card-body">
                    @if($pengembalian->reviewedBy)
                        <div class="mb-2">
                            <strong>Direview oleh:</strong> {{ $pengembalian->reviewedBy->name }}
                        </div>
                    @endif
                    @if($pengembalian->tanggal_review)
                        <div class="mb-2">
                            <strong>Tanggal Review:</strong> {{ \Carbon\Carbon::parse($pengembalian->tanggal_review)->format('d/m/Y H:i') }}
                        </div>
                    @endif
                    @if($pengembalian->tanggal_pengembalian_dana)
                        <div class="mb-2">
                            <strong>Dana Dikembalikan:</strong> {{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian_dana)->format('d/m/Y H:i') }}
                        </div>
                    @endif
                    @if($pengembalian->nomor_transaksi_pengembalian)
                        <div class="mb-2">
                            <strong>No. Transaksi:</strong> 
                            <code>{{ $pengembalian->nomor_transaksi_pengembalian }}</code>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Order Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Informasi Pesanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>ID Pesanan:</strong>
                                <a href="{{ route('admin.pesanan.show', $pengembalian->pesanan->id_pesanan) }}" 
                                   class="text-decoration-none ms-2">
                                    {{ $pengembalian->pesanan->id_pesanan }}
                                </a>
                            </div>
                            <div class="mb-3">
                                <strong>Total Pesanan:</strong>
                                <span class="ms-2">Rp {{ number_format($pengembalian->pesanan->total_harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Status Pesanan:</strong>
                                <span class="badge bg-info ms-2">{{ $pengembalian->pesanan->status_pesanan }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Tanggal Pesanan:</strong>
                                <span class="ms-2">{{ $pengembalian->pesanan->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($pengembalian->pesanan->tanggal_pengiriman)
                            <div class="mb-3">
                                <strong>Tanggal Pengiriman:</strong>
                                <span class="ms-2">{{ \Carbon\Carbon::parse($pengembalian->pesanan->tanggal_pengiriman)->format('d/m/Y') }}</span>
                            </div>
                            @endif
                            <div class="mb-3">
                                <strong>Jumlah Refund:</strong>
                                <span class="ms-2 text-success fw-bold">Rp {{ number_format($pengembalian->jumlah_refund, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <h6 class="mt-4 mb-3">Barang yang Dipesan:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengembalian->pesanan->detailPesanan as $detail)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($detail->produk && $detail->produk->foto_produk)
                                                <img src="{{ Storage::url($detail->produk->foto_produk) }}" 
                                                     alt="{{ $detail->produk->nama_produk }}" 
                                                     class="img-thumbnail me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $detail->produk->nama_produk ?? 'Produk tidak ditemukan' }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>Rp {{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Return Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Detail Keluhan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Jenis Keluhan:</strong>
                                <span class="badge bg-info ms-2">{{ $pengembalian->jenis_keluhan }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Jenis Pengembalian:</strong>
                                <span class="badge bg-secondary ms-2">{{ $pengembalian->jenis_pengembalian }}</span>
                            </div>
                        </div>
                    </div>

                    @if($pengembalian->deskripsi_keluhan)
                    <div class="mb-3">
                        <strong>Deskripsi Keluhan:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {{ $pengembalian->deskripsi_keluhan }}
                        </div>
                    </div>
                    @endif

                    <!-- Photos -->
                    @if($pengembalian->foto_bukti)
                    <div class="mb-3">
                        <strong>Foto Bukti:</strong>
                        <div class="mt-2">
                            @php
                                $photos = is_array($pengembalian->foto_bukti) ? $pengembalian->foto_bukti : json_decode($pengembalian->foto_bukti, true);
                            @endphp
                            @if($photos)
                                <div class="row g-2">
                                    @foreach($photos as $photo)
                                    <div class="col-md-3">
                                        <img src="{{ Storage::url($photo) }}" 
                                             alt="Bukti" 
                                             class="img-thumbnail w-100" 
                                             style="height: 150px; object-fit: cover; cursor: pointer;"
                                             data-bs-toggle="modal" 
                                             data-bs-target="#photoModal-{{ $loop->index }}">
                                    </div>

                                    <!-- Photo Modal -->
                                    <div class="modal fade" id="photoModal-{{ $loop->index }}" tabindex="-1">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Foto Bukti {{ $loop->iteration }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ Storage::url($photo) }}" alt="Bukti" class="img-fluid">
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="{{ Storage::url($photo) }}" download class="btn btn-primary">
                                                        <i class="fas fa-download me-1"></i> Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Admin Notes -->
            @if($pengembalian->catatan_admin)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-comment-alt me-2"></i>
                        Catatan Admin
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <p class="mb-0">{{ $pengembalian->catatan_admin }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions Modals -->
<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Setujui Pengajuan Pengembalian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pengembalian.approve', $pengembalian->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-info-circle me-2"></i>
                        Dengan menyetujui pengajuan ini, pelanggan akan menerima notifikasi dan proses pengembalian akan dilanjutkan.
                    </div>
                    <div class="mb-3">
                        <label for="approveNote" class="form-label">Catatan (Opsional)</label>
                        <textarea name="catatan_admin" id="approveNote" class="form-control" rows="3" 
                                  placeholder="Berikan catatan jika diperlukan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i> Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tolak Pengajuan Pengembalian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pengembalian.reject', $pengembalian->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Pastikan Anda memberikan alasan yang jelas untuk penolakan pengajuan ini.
                    </div>
                    <div class="mb-3">
                        <label for="rejectNote" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="catatan_admin" id="rejectNote" class="form-control" rows="4" 
                                  placeholder="Jelaskan alasan penolakan dengan detail..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div class="modal fade" id="refundModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pengembalian Dana</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.pengembalian.markRefunded', $pengembalian->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-primary">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Konfirmasi bahwa dana sebesar <strong>Rp {{ number_format($pengembalian->jumlah_refund, 0, ',', '.') }}</strong> 
                        telah ditransfer ke rekening pelanggan.
                    </div>
                    
                    @if($pengembalian->bank_name)
                    <div class="mb-3">
                        <strong>Informasi Transfer:</strong>
                        <ul class="mb-0">
                            <li>Bank: {{ $pengembalian->bank_name }}</li>
                            <li>No. Rekening: {{ $pengembalian->account_number }}</li>
                            <li>Nama: {{ $pengembalian->account_holder_name }}</li>
                        </ul>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="refundTransactionNumber" class="form-label">Nomor Transaksi <span class="text-danger">*</span></label>
                        <input type="text" name="nomor_transaksi_pengembalian" id="refundTransactionNumber" 
                               class="form-control" placeholder="Masukkan nomor transaksi bank" required>
                    </div>
                    <div class="mb-3">
                        <label for="refundNote" class="form-label">Catatan (Opsional)</label>
                        <textarea name="catatan_admin" id="refundNote" class="form-control" rows="2" 
                                  placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-money-bill-wave me-1"></i> Konfirmasi Transfer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status_pengembalian');
    const transactionField = document.getElementById('transactionNumberField');
    
    function toggleTransactionField() {
        if (statusSelect.value === 'Dana Dikembalikan') {
            transactionField.style.display = 'block';
            document.getElementById('nomor_transaksi_pengembalian').required = true;
        } else {
            transactionField.style.display = 'none';
            document.getElementById('nomor_transaksi_pengembalian').required = false;
        }
    }
    
    statusSelect.addEventListener('change', toggleTransactionField);
    toggleTransactionField(); // Initial check
});

function showApproveModal() {
    new bootstrap.Modal(document.getElementById('approveModal')).show();
}

function showRejectModal() {
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}

function showRefundModal() {
    new bootstrap.Modal(document.getElementById('refundModal')).show();
}
</script>
@endpush

@push('styles')
<style>
    .avatar-lg {
        width: 60px;
        height: 60px;
    }
    
    .avatar-circle {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
    }
    
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .badge {
        font-size: 0.8em;
    }
    
    .img-thumbnail {
        border: 1px solid #dee2e6;
        transition: transform 0.2s;
    }
    
    .img-thumbnail:hover {
        transform: scale(1.05);
    }
    
    code {
        background-color: #f8f9fa;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.9em;
    }
</style>
@endpush
