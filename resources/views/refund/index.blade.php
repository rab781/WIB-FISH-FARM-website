@extends('layouts.app')

@section('title', 'Pengajuan Refund')

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
                                <i class="fas fa-money-bill-wave me-2 text-success"></i>
                                Pengajuan Refund
                            </h4>
                            <p class="text-muted mb-0">Kelola pengajuan refund pesanan Anda</p>
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
                            <h6 class="mb-0">Total Refund</h6>
                            <h2 class="mb-0">{{ $stats['total'] ?? 0 }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-file-invoice-dollar fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Menunggu Review</h6>
                            <h2 class="mb-0">{{ $stats['pending'] ?? 0 }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-clock fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Disetujui</h6>
                            <h2 class="mb-0">{{ $stats['approved'] ?? 0 }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-check-circle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Ditolak</h6>
                            <h2 class="mb-0">{{ $stats['rejected'] ?? 0 }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-times-circle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Refund List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Daftar Pengajuan Refund</h5>
                    
                    @if($refunds->isEmpty())
                        <div class="text-center py-5">
                            <img src="{{ asset('images/empty-data.svg') }}" alt="Data Kosong" class="img-fluid mb-3" style="max-height: 150px">
                            <h5>Belum Ada Pengajuan Refund</h5>
                            <p class="text-muted">Anda belum pernah mengajukan refund untuk pesanan Anda.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Pesanan</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Jumlah</th>
                                        <th>Jenis</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($refunds as $refund)
                                        <tr>
                                            <td>#{{ $refund->id }}</td>
                                            <td>
                                                <a href="{{ route('pesanan.show', $refund->pesanan->id_pesanan) }}" class="text-decoration-none">
                                                    #{{ $refund->pesanan->id_pesanan }}
                                                </a>
                                            </td>
                                            <td>{{ $refund->created_at->format('d M Y H:i') }}</td>
                                            <td>Rp {{ number_format($refund->jumlah_diminta, 0, ',', '.') }}</td>
                                            <td>{{ $refund->jenis_refund_text }}</td>
                                            <td>
                                                <span class="badge {{ match($refund->status) {
                                                    'pending' => 'bg-warning text-dark',
                                                    'approved' => 'bg-success',
                                                    'rejected' => 'bg-danger',
                                                    'processing' => 'bg-info',
                                                    'completed' => 'bg-primary',
                                                    default => 'bg-secondary'
                                                } }}">
                                                    {{ match($refund->status) {
                                                        'pending' => 'Menunggu Review',
                                                        'approved' => 'Disetujui',
                                                        'rejected' => 'Ditolak',
                                                        'processing' => 'Diproses',
                                                        'completed' => 'Selesai',
                                                        default => 'Unknown'
                                                    } }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('refund.show', $refund->id) }}" class="btn btn-sm btn-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $refunds->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
