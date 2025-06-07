@extends('layouts.app')

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
                            <p class="text-muted mb-0">Informasi lengkap pengajuan pengembalian Anda</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('pengembalian.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Status & Timeline -->
        <div class="col-md-4">
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

                    <!-- Timeline -->
                    <div class="timeline">
                        <div class="timeline-item {{ in_array($currentStatus, ['Menunggu Review', 'Dalam Review', 'Disetujui', 'Ditolak', 'Dana Dikembalikan', 'Selesai']) ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pengajuan Dibuat</h6>
                                <small class="text-muted">{{ $pengembalian->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>

                        @if($pengembalian->tanggal_review)
                        <div class="timeline-item {{ in_array($currentStatus, ['Dalam Review', 'Disetujui', 'Ditolak', 'Dana Dikembalikan', 'Selesai']) ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Review Dimulai</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($pengembalian->tanggal_review)->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endif

                        @if($currentStatus === 'Disetujui' || $currentStatus === 'Dana Dikembalikan' || $currentStatus === 'Selesai')
                        <div class="timeline-item completed">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pengajuan Disetujui</h6>
                                <small class="text-muted">{{ $pengembalian->tanggal_review ? \Carbon\Carbon::parse($pengembalian->tanggal_review)->format('d/m/Y H:i') : '-' }}</small>
                            </div>
                        </div>
                        @endif

                        @if($pengembalian->tanggal_pengembalian_dana)
                        <div class="timeline-item completed">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Dana Dikembalikan</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($pengembalian->tanggal_pengembalian_dana)->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endif

                        @if($currentStatus === 'Ditolak')
                        <div class="timeline-item rejected">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1 text-danger">Pengajuan Ditolak</h6>
                                <small class="text-muted">{{ $pengembalian->tanggal_review ? \Carbon\Carbon::parse($pengembalian->tanggal_review)->format('d/m/Y H:i') : '-' }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bank Details (if provided) -->
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
                            <strong>No. Rekening:</strong> {{ $pengembalian->account_number }}
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
                                <a href="{{ route('pesanan.show', $pengembalian->pesanan->id_pesanan) }}"
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
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Foto Bukti</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <img src="{{ Storage::url($photo) }}" alt="Bukti" class="img-fluid">
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

            <!-- Admin Response -->
            @if($pengembalian->catatan_admin)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-shield me-2"></i>
                        Tanggapan Admin
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-comment-alt me-2 mt-1"></i>
                            <div>
                                <p class="mb-2">{{ $pengembalian->catatan_admin }}</p>
                                @if($pengembalian->reviewedBy)
                                <small class="text-muted">
                                    Oleh: {{ $pengembalian->reviewedBy->name }} -
                                    {{ $pengembalian->tanggal_review ? \Carbon\Carbon::parse($pengembalian->tanggal_review)->format('d/m/Y H:i') : '' }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($pengembalian->nomor_transaksi_pengembalian)
                    <div class="alert alert-success">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-receipt me-2"></i>
                            <div>
                                <strong>Nomor Transaksi Pengembalian:</strong>
                                <span class="ms-2">{{ $pengembalian->nomor_transaksi_pengembalian }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 20px;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 20px;
        padding-left: 25px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 8px;
        bottom: -20px;
        width: 2px;
        background-color: #dee2e6;
    }

    .timeline-item:last-child::before {
        display: none;
    }

    .timeline-marker {
        position: absolute;
        left: -15px;
        top: 5px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background-color: #dee2e6;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #dee2e6;
    }

    .timeline-item.completed .timeline-marker {
        background-color: #198754;
        box-shadow: 0 0 0 2px #198754;
    }

    .timeline-item.rejected .timeline-marker {
        background-color: #dc3545;
        box-shadow: 0 0 0 2px #dc3545;
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
    }
</style>
@endpush
