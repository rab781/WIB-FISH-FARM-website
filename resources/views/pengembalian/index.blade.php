@extends('layouts.app')

@section('title', 'Pengajuan Pengembalian')

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
                                Pengajuan Pengembalian
                            </h4>
                            <p class="text-muted mb-0">Kelola pengajuan pengembalian barang Anda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Pengajuan</h6>
                            <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-list-alt fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Menunggu Review</h6>
                            <h3 class="mb-0">{{ $stats['pending'] ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Disetujui</h6>
                            <h3 class="mb-0">{{ $stats['approved'] ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Ditolak</h6>
                            <h3 class="mb-0">{{ $stats['rejected'] ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-times-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('pengembalian.index') }}">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="status" class="form-label">Filter Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="Menunggu Review" {{ request('status') === 'Menunggu Review' ? 'selected' : '' }}>Menunggu Review</option>
                                    <option value="Dalam Review" {{ request('status') === 'Dalam Review' ? 'selected' : '' }}>Dalam Review</option>
                                    <option value="Disetujui" {{ request('status') === 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                                    <option value="Ditolak" {{ request('status') === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="Dana Dikembalikan" {{ request('status') === 'Dana Dikembalikan' ? 'selected' : '' }}>Dana Dikembalikan</option>
                                    <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="search" class="form-label">Cari</label>
                                <input type="text" name="search" id="search" class="form-control"
                                       value="{{ request('search') }}"
                                       placeholder="Cari berdasarkan ID pesanan atau keluhan...">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Return Requests List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Pengajuan Pengembalian</h5>
                </div>
                <div class="card-body">
                    @if($pengembalian->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum Ada Pengajuan Pengembalian</h5>
                            <p class="text-muted">Anda belum memiliki pengajuan pengembalian.</p>
                            <a href="{{ route('pesanan.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-1"></i> Lihat Pesanan
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID Pengajuan</th>
                                        <th>Pesanan</th>
                                        <th>Jenis Keluhan</th>
                                        <th>Status</th>
                                        <th>Tanggal Ajukan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengembalian as $item)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">PB-{{ $item->id }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('pesanan.show', $item->pesanan->id_pesanan) }}"
                                                   class="text-decoration-none">
                                                    <strong>{{ $item->pesanan->id_pesanan }}</strong>
                                                </a>
                                                <br>
                                                <small class="text-muted">
                                                    {{ number_format($item->pesanan->total_harga, 0, ',', '.') }} IDR
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $item->jenis_keluhan }}</span>
                                                @if($item->deskripsi_keluhan)
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ Str::limit($item->deskripsi_keluhan, 50) }}
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'Menunggu Review' => 'warning',
                                                        'Dalam Review' => 'info',
                                                        'Disetujui' => 'success',
                                                        'Ditolak' => 'danger',
                                                        'Dana Dikembalikan' => 'primary',
                                                        'Selesai' => 'dark'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$item->status_pengembalian] ?? 'secondary' }}">
                                                    {{ $item->status_pengembalian }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $item->created_at->format('d/m/Y H:i') }}
                                                <br>
                                                <small class="text-muted">
                                                    {{ $item->created_at->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td>
                                                <a href="{{ route('pengembalian.show', $item->id) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $pengembalian->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-question-circle me-2"></i>
                    Bantuan Pengajuan Pengembalian
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Apa itu Pengajuan Pengembalian?</h6>
                <p>Pengajuan pengembalian adalah proses untuk meminta pengembalian dana atau penggantian barang jika terdapat masalah dengan pesanan Anda.</p>

                <h6>Kapan Bisa Mengajukan Pengembalian?</h6>
                <ul>
                    <li>Barang yang diterima rusak atau cacat</li>
                    <li>Barang tidak sesuai dengan deskripsi</li>
                    <li>Barang yang diterima salah atau tidak lengkap</li>
                    <li>Masalah pengiriman (barang tidak sampai dalam waktu yang wajar)</li>
                </ul>

                <h6>Dokumen yang Diperlukan:</h6>
                <ul>
                    <li>Foto barang yang bermasalah</li>
                    <li>Bukti transfer jika perlu pengembalian dana</li>
                    <li>Deskripsi masalah yang jelas</li>
                </ul>

                <h6>Proses Pengembalian:</h6>
                <ol>
                    <li>Ajukan pengembalian melalui halaman detail pesanan</li>
                    <li>Tim kami akan meninjau pengajuan dalam 1-3 hari kerja</li>
                    <li>Jika disetujui, dana akan dikembalikan dalam 7-14 hari kerja</li>
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .table th {
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .badge {
        font-size: 0.75em;
    }

    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.875rem;
    }
</style>
@endpush
