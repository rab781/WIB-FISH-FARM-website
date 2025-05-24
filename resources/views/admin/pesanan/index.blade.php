@extends('admin.layouts.app')

@section('title', 'Daftar Pesanan')

@section('styles')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css" rel="stylesheet">
<style>
    .badge-outline-warning {
        color: #f6c23e;
        border: 1px solid #f6c23e;
        background-color: transparent;
    }
    .badge-outline-info {
        color: #36b9cc;
        border: 1px solid #36b9cc;
        background-color: transparent;
    }
    .badge-outline-primary {
        color: #4e73df;
        border: 1px solid #4e73df;
        background-color: transparent;
    }
    .badge-outline-secondary {
        color: #858796;
        border: 1px solid #858796;
        background-color: transparent;
    }
    .badge-outline-success {
        color: #1cc88a;
        border: 1px solid #1cc88a;
        background-color: transparent;
    }
    .badge-outline-danger {
        color: #e74a3b;
        border: 1px solid #e74a3b;
        background-color: transparent;
    }
    .order-status {
        min-width: 130px;
        display: inline-block;
        text-align: center;
        padding: 6px 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-action {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
        margin: 0 0.125rem;
    }
    .order-date {
        color: #5a5c69;
        font-size: 0.875rem;
    }
    .order-total {
        font-weight: 600;
        color: #2e59d9;
    }
    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05) !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .status-filter {
        transition: all 0.2s ease;
        border-bottom: 3px solid transparent;
    }

    .status-filter:hover {
        background-color: rgba(78, 115, 223, 0.05);
        text-decoration: none;
    }

    .status-filter.active {
        border-bottom-color: #4e73df;
        background-color: rgba(78, 115, 223, 0.1);
    }
@endsection

@section('content')
<div class="container-fluid">
    <!-- Order Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle p-3 bg-primary bg-opacity-10 me-3">
                        <i class="fas fa-shopping-bag fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase fs-xs mb-1">Total Pesanan Hari Ini</h6>
                        <h2 class="mb-0 fw-bold">{{ $pesanan->where('created_at', '>=', \Carbon\Carbon::today())->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle p-3 bg-success bg-opacity-10 me-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase fs-xs mb-1">Pesanan Selesai</h6>
                        <h2 class="mb-0 fw-bold">{{ $pesanan->where('status_pesanan', 'Selesai')->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle p-3 bg-warning bg-opacity-10 me-3">
                        <i class="fas fa-clock fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase fs-xs mb-1">Menunggu Proses</h6>
                        <h2 class="mb-0 fw-bold">{{ $pesanan->whereIn('status_pesanan', ['Menunggu Pembayaran', 'Pembayaran Dikonfirmasi'])->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm rounded-lg h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle p-3 bg-info bg-opacity-10 me-3">
                        <i class="fas fa-truck fa-2x text-info"></i>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase fs-xs mb-1">Sedang Dikirim</h6>
                        <h2 class="mb-0 fw-bold">{{ $pesanan->where('status_pesanan', 'Dikirim')->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Pesanan</h1>
        <div class="d-none d-sm-inline-block">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-primary btn-sm" id="refreshTable">
                    <i class="fas fa-sync-alt fa-sm"></i> Refresh
                </button>
                <button type="button" class="btn btn-info btn-sm" id="exportTable">
                    <i class="fas fa-download fa-sm"></i> Export
                </button>
            </div>
        </div>
    </div>

    <!-- Status Filter -->
    <div class="card mb-4">
        <div class="card-body p-0">
            <div class="row no-gutters">
                <div class="col border-right">
                    <a href="javascript:void(0)" class="status-filter active nav-link text-center py-3" data-status="all">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">Semua</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pesanan->count() }}</div>
                    </a>
                </div>
                <div class="col border-right">
                    <a href="javascript:void(0)" class="status-filter nav-link text-center py-3" data-status="Sedang Diproses">
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-primary">Sedang Diproses</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pesanan->where('status_pesanan', 'Diproses')->count() }}</div>
                    </a>
                </div>
                <div class="col border-right">
                    <a href="javascript:void(0)" class="status-filter nav-link text-center py-3" data-status="Sedang Dikirim">
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-info">Sedang Dikirim</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pesanan->where('status_pesanan', 'Dikirim')->count() }}</div>
                    </a>
                </div>
                <div class="col border-right">
                    <a href="javascript:void(0)" class="status-filter nav-link text-center py-3" data-status="Selesai">
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-success">Selesai</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pesanan->where('status_pesanan', 'Selesai')->count() }}</div>
                    </a>
                </div>
                <div class="col">
                    <a href="javascript:void(0)" class="status-filter nav-link text-center py-3" data-status="Dibatalkan">
                        <div class="text-xs font-weight-bold text-uppercase mb-1 text-danger">Dibatalkan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pesanan->where('status_pesanan', 'Dibatalkan')->count() }}</div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Manajemen Pesanan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="orderTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pesanan as $p)
                        <tr onclick="window.location='{{ route('admin.pesanan.show', $p->id_pesanan) }}'" style="cursor: pointer" class="hover:bg-gray-50">
                            <td>
                                <strong class="text-primary">#{{ $p->id_pesanan }}</strong>
                            </td>
                            <td>
                                <div>{{ $p->user->name }}</div>
                                <small class="text-muted">{{ $p->user->email }}</small>
                            </td>
                            <td>
                                <div class="order-date">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ $p->created_at->format('d M Y') }}
                                    <br>
                                    <small>
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $p->created_at->format('H:i') }} WIB
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="order-total">
                                    Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge order-status badge-outline-
                                    @if($p->status_pesanan == 'Menunggu Pembayaran') warning
                                    @elseif($p->status_pesanan == 'Pembayaran Dikonfirmasi') info
                                    @elseif($p->status_pesanan == 'Diproses') primary
                                    @elseif($p->status_pesanan == 'Dikirim') secondary
                                    @elseif($p->status_pesanan == 'Selesai') success
                                    @elseif($p->status_pesanan == 'Dibatalkan') danger
                                    @endif">
                                    <i class="fas fa-circle mr-1" style="font-size: 8px;"></i>
                                    {{ $p->status_pesanan }}
                                </span>
                            </td>
                            <td class="text-center">
                                #{{ $p->id_pesanan }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#orderTable').DataTable({
        responsive: true,
        order: [[2, 'desc']], // Sort by date column descending
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Tidak ada data yang ditemukan",
            info: "Menampilkan halaman _PAGE_ dari _PAGES_",
            infoEmpty: "Tidak ada data yang tersedia",
            infoFiltered: "(difilter dari total _MAX_ data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
    });

    // Status Filter
    $('.status-filter').click(function(e) {
        e.preventDefault();
        $('.status-filter').removeClass('active');
        $(this).addClass('active');

        let status = $(this).data('status');
        if (status === 'all') {
            table.column(4).search('').draw();
        } else {
            table.column(4).search(status).draw();
        }
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Refresh button handler
    $('#refreshTable').click(function() {
        location.reload();
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>
@endsection
