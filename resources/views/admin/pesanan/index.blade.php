@extends('admin.layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Pesanan</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pesanan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                        <tr>
                            <td>{{ $p->id_pesanan }}</td>
                            <td>{{ $p->user->name }}</td>
                            <td>{{ $p->created_at->format('d M Y, H:i') }}</td>
                            <td>Rp. {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge
                                    @if($p->status_pesanan == 'Menunggu Pembayaran') badge-warning
                                    @elseif($p->status_pesanan == 'Pembayaran Dikonfirmasi') badge-info
                                    @elseif($p->status_pesanan == 'Diproses') badge-primary
                                    @elseif($p->status_pesanan == 'Dikirim') badge-secondary
                                    @elseif($p->status_pesanan == 'Selesai') badge-success
                                    @elseif($p->status_pesanan == 'Dibatalkan') badge-danger
                                    @endif">
                                    {{ $p->status_pesanan }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.pesanan.show', $p->id_pesanan) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pesanan->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
