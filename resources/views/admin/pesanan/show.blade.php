@extends('admin.layouts.app')

@section('title', 'Detail Pesanan #' . $pesanan->id_pesanan)

@section('styles')
<style>
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-card {
        background: #f8f9fc;
        border-radius: 0.35rem;
        padding: 1rem;
        margin-bottom: 1rem;
        border-left: 4px solid #4e73df;
    }
    .info-card .title {
        color: #4e73df;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    .info-card .content {
        color: #5a5c69;
    }
    .table-product img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 0.25rem;
    }
    .product-name {
        font-weight: 600;
        color: #2e59d9;
    }
    .product-code {
        font-size: 0.875rem;
        color: #858796;
    }
    .text-price {
        font-family: monospace;
        font-weight: 600;
        color: #2e59d9;
    }
    .box-alert {
        border: 1px solid #f6c23e;
        background-color: #fff3cd;
        border-radius: 0.35rem;
        padding: 1rem;
        margin-top: 1rem;
    }
    .box-alert i {
        color: #f6c23e;
    }
    .payment-info {
        background: #fff;
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        padding: 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Detail Pesanan #{{ $pesanan->id_pesanan }}</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.pesanan.index') }}" class="text-primary">
                            <i class="fas fa-chevron-left fa-sm mr-1"></i>
                            Kembali ke Daftar Pesanan
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Pesanan</li>
                </ol>
            </nav>
        </div>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="statusDropdown" data-toggle="dropdown">
                <i class="fas fa-sync-alt mr-1"></i> Update Status
            </button>
            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id_pesanan) }}" method="POST" id="updateStatusForm">
                    @csrf
                    <select class="form-control" name="status_pesanan" id="status_pesanan" onchange="this.form.submit()">
                        <option value="">Pilih Status</option>
                        <option value="Menunggu Pembayaran" {{ $pesanan->status_pesanan == 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="Pembayaran Dikonfirmasi" {{ $pesanan->status_pesanan == 'Pembayaran Dikonfirmasi' ? 'selected' : '' }}>Pembayaran Dikonfirmasi</option>
                        <option value="Diproses" {{ $pesanan->status_pesanan == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Dikirim" {{ $pesanan->status_pesanan == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="Selesai" {{ $pesanan->status_pesanan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Dibatalkan" {{ $pesanan->status_pesanan == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Informasi Pesanan -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-1"></i>
                        Informasi Pesanan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="title">Status Pesanan</div>
                                <div class="content">
                                    <span class="status-badge
                                        @if($pesanan->status_pesanan == 'Menunggu Pembayaran') bg-warning text-dark
                                        @elseif($pesanan->status_pesanan == 'Pembayaran Dikonfirmasi') bg-info
                                        @elseif($pesanan->status_pesanan == 'Diproses') bg-primary
                                        @elseif($pesanan->status_pesanan == 'Dikirim') bg-secondary
                                        @elseif($pesanan->status_pesanan == 'Selesai') bg-success
                                        @elseif($pesanan->status_pesanan == 'Dibatalkan') bg-danger
                                        @endif">
                                        <i class="fas fa-circle mr-1" style="font-size: 8px;"></i>
                                        {{ $pesanan->status_pesanan }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="title">Tanggal Pemesanan</div>
                                <div class="content">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ $pesanan->created_at->format('d M Y, H:i') }} WIB
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="title">Pelanggan</div>
                                <div class="content">
                                    <div>{{ $pesanan->user->name }}</div>
                                    <small class="text-muted">{{ $pesanan->user->email }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="title">Metode Pembayaran</div>
                                <div class="content">
                                    @if($pesanan->metode_pembayaran == 'transfer_bank')
                                        <i class="fas fa-university mr-1"></i> Transfer Bank
                                    @elseif($pesanan->metode_pembayaran == 'qris')
                                        <i class="fas fa-qrcode mr-1"></i> QRIS
                                    @else
                                        {{ $pesanan->metode_pembayaran }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-card mt-3">
                        <div class="title">Alamat Pengiriman</div>
                        <div class="content">{{ $pesanan->alamat_pengiriman }}</div>
                    </div>

                    @if($pesanan->jumlah_box)
                    <div class="box-alert">
                        <div class="d-flex">
                            <div class="mr-3">
                                <i class="fas fa-box fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-warning mb-1">Informasi Box Pengiriman</h6>
                                <p class="mb-1">{{ $pesanan->jumlah_box }} box (maks. 3 ikan/box)</p>
                                <p class="mb-1">Ukuran box: 40x40x40 cm</p>
                                <p class="mb-0">Berat total: {{ number_format($pesanan->berat_total/1000, 1) }} kg</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Detail Produk -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-fish mr-1"></i>
                        Detail Produk
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-product">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Ukuran</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pesanan->detailPesanan as $detail)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($detail->produk)
                                                @if($detail->produk->gambar)
                                                    <img src="{{ asset($detail->produk->gambar) }}"
                                                         alt="{{ $detail->produk->nama_ikan }}"
                                                         class="mr-3">
                                                @else
                                                    <div class="bg-light rounded mr-3" style="width: 60px; height: 60px;">
                                                        <i class="fas fa-fish fa-2x text-gray-300 p-3"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="product-name">{{ $detail->produk->nama_ikan }}</div>
                                                    <div class="product-code">#{{ $detail->produk->id }}</div>
                                                </div>
                                            @else
                                                <span class="text-muted">Produk tidak tersedia</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($detail->ukuran_id && isset($detail->ukuran))
                                            <span class="badge badge-info">
                                                {{ $detail->ukuran->ukuran }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-price">
                                            Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $detail->kuantitas }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-price">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Ringkasan Pembayaran -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-money-bill-wave mr-1"></i>
                        Ringkasan Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    <div class="payment-info">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-gray-600">Subtotal Produk</span>
                            <span class="text-price">
                                Rp {{ number_format($pesanan->detailPesanan->sum('subtotal'), 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-gray-600">Ongkos Kirim</span>
                            <span class="text-price">
                                Rp {{ number_format($pesanan->ongkir->biaya ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="font-weight-bold">Total Pembayaran</span>
                            <span class="text-price h5 mb-0">
                                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    @if($pesanan->bukti_pembayaran)
                    <div class="text-center mt-4">
                        <a href="{{ asset($pesanan->bukti_pembayaran) }}"
                           target="_blank"
                           class="btn btn-info btn-block">
                            <i class="fas fa-receipt mr-1"></i>
                            Lihat Bukti Pembayaran
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tindakan -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog mr-1"></i>
                        Tindakan
                    </h6>
                </div>
                <div class="card-body">
                    @if($pesanan->status_pesanan == 'Menunggu Pembayaran')
                    <form action="{{ route('admin.pesanan.forceExpire', $pesanan->id_pesanan) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin ingin menguji kedaluwarsa pesanan ini?');"
                          class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-block">
                            <i class="fas fa-clock mr-1"></i>
                            Uji Kedaluwarsa Pesanan
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali ke Daftar Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Status update confirmation
    $('#updateStatusForm').on('submit', function(e) {
        const newStatus = $('#status_pesanan').val();
        if (!newStatus) {
            e.preventDefault();
            return false;
        }

        @if($pesanan->jumlah_box > 0)
        if (newStatus === 'Diproses' || newStatus === 'Dikirim') {
            if (!confirm('Pesanan ini berisi ikan hias yang memerlukan penanganan khusus.\n\n' +
                        'Apakah Anda sudah mempersiapkan:\n' +
                        '- Box pengiriman khusus ikan (40x40x40 cm)\n' +
                        '- Media air dan aerasi sudah dipersiapkan\n' +
                        '- Kurir TIKI sudah dikonfirmasi untuk penanganan khusus\n\n' +
                        'Lanjutkan mengubah status menjadi ' + newStatus + '?')) {
                e.preventDefault();
                return false;
            }
        }
        @endif

        return confirm('Apakah Anda yakin ingin mengubah status pesanan menjadi ' + newStatus + '?');
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>
@endsection
