@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $pesanan->id_pesanan)

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Detail Pesanan #{{ $pesanan->id_pesanan }}</h1>

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

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pesanan</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="statusDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Update Status
                        </button>
                        <div class="dropdown-menu" aria-labelledby="statusDropdown">
                            <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id_pesanan) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status_pesanan" value="Menunggu Pembayaran">
                                <button type="submit" class="dropdown-item">Menunggu Pembayaran</button>
                            </form>
                            <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id_pesanan) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status_pesanan" value="Pembayaran Dikonfirmasi">
                                <button type="submit" class="dropdown-item">Pembayaran Dikonfirmasi</button>
                            </form>
                            <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id_pesanan) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status_pesanan" value="Diproses">
                                <button type="submit" class="dropdown-item">Diproses</button>
                            </form>
                            <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id_pesanan) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status_pesanan" value="Dikirim">
                                <button type="submit" class="dropdown-item">Dikirim</button>
                            </form>
                            <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id_pesanan) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status_pesanan" value="Selesai">
                                <button type="submit" class="dropdown-item">Selesai</button>
                            </form>
                            <form action="{{ route('admin.pesanan.updateStatus', $pesanan->id_pesanan) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status_pesanan" value="Dibatalkan">
                                <button type="submit" class="dropdown-item">Dibatalkan</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">ID Pesanan</th>
                                <td>{{ $pesanan->id_pesanan }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pemesanan</th>
                                <td>{{ $pesanan->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Batas Waktu Pembayaran</th>
                                <td>
                                    @if($pesanan->batas_waktu)
                                        {{ $pesanan->batas_waktu->format('d M Y, H:i') }}
                                        @if($pesanan->batas_waktu->isPast())
                                            <span class="badge badge-danger">Telah Berakhir</span>
                                        @else
                                            <span class="badge badge-warning">{{ $pesanan->batas_waktu->diffForHumans() }}</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge
                                        @if($pesanan->status_pesanan == 'Menunggu Pembayaran') badge-warning
                                        @elseif($pesanan->status_pesanan == 'Pembayaran Dikonfirmasi') badge-info
                                        @elseif($pesanan->status_pesanan == 'Diproses') badge-primary
                                        @elseif($pesanan->status_pesanan == 'Dikirim') badge-secondary
                                        @elseif($pesanan->status_pesanan == 'Selesai') badge-success
                                        @elseif($pesanan->status_pesanan == 'Dibatalkan') badge-danger
                                        @endif">
                                        {{ $pesanan->status_pesanan }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Pelanggan</th>
                                <td>{{ $pesanan->user->name }} ({{ $pesanan->user->email }})</td>
                            </tr>
                            <tr>
                                <th>Alamat Pengiriman</th>
                                <td>{{ $pesanan->alamat_pengiriman }}</td>
                            </tr>
                            <tr>
                                <th>Metode Pembayaran</th>
                                <td>
                                    @if($pesanan->metode_pembayaran == 'transfer_bank')
                                        Transfer Bank
                                    @elseif($pesanan->metode_pembayaran == 'qris')
                                        QRIS
                                    @else
                                        {{ $pesanan->metode_pembayaran }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Bukti Pembayaran</th>
                                <td>
                                    @if($pesanan->bukti_pembayaran)
                                        <a href="{{ asset($pesanan->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Lihat Bukti Pembayaran
                                        </a>
                                    @else
                                        <span class="text-warning">Belum ada bukti pembayaran</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Produk</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
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
                                        @if($detail->produk)
                                            <div class="d-flex align-items-center">
                                                @if($detail->produk->gambar)
                                                <img src="{{ asset($detail->produk->gambar) }}" alt="{{ $detail->produk->nama_produk }}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                                @endif
                                                <span>{{ $detail->produk->nama_produk }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">Produk tidak ditemukan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($detail->ukuran_id && isset($detail->ukuran))
                                            {{ $detail->ukuran->ukuran }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>Rp. {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td>{{ $detail->kuantitas }}</td>
                                    <td>Rp. {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ringkasan Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Subtotal Produk</th>
                                <td class="text-right">Rp. {{ number_format($pesanan->detailPesanan->sum('subtotal'), 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Ongkos Kirim</th>
                                <td class="text-right">Rp. {{ number_format($pesanan->ongkir->biaya ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="bg-light">
                                <th>Total Pembayaran</th>
                                <td class="text-right font-weight-bold">Rp. {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tindakan</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.pesanan.index') }}" class="btn btn-secondary btn-block mb-2">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
                    </a>

                    @if($pesanan->status_pesanan == 'Menunggu Pembayaran')
                    <form action="{{ route('admin.pesanan.forceExpire', $pesanan->id_pesanan) }}" method="POST" onsubmit="return confirm('Yakin ingin menguji kedaluwarsa pesanan ini?');">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-block">
                            <i class="fas fa-clock"></i> Uji Kedaluwarsa Pesanan
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
