@extends('layouts.app')

@php
use Carbon\Carbon;
@endphp

@section('title', 'Detail Pesanan #' . $pesanan->id_pesanan)

@section('content')
<div class="container my-5">
    <h1 class="h3 mb-4">Detail Pesanan #{{ $pesanan->id_pesanan }}</h1>

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
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Informasi Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th width="200">ID Pesanan</th>
                                <td>{{ $pesanan->id_pesanan }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pemesanan</th>
                                <td>{{ $pesanan->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            @if($pesanan->status_pesanan == 'Menunggu Pembayaran' && $pesanan->batas_waktu)
                            <tr>
                                <th>Batas Waktu Pembayaran</th>
                                <td>
                                    @php
                                        $batasWaktu = $pesanan->batas_waktu instanceof \Carbon\Carbon
                                            ? $pesanan->batas_waktu
                                            : \Carbon\Carbon::parse($pesanan->batas_waktu);
                                    @endphp
                                    {{ $batasWaktu->format('d M Y, H:i') }}
                                    @if($batasWaktu->isPast())
                                        <span class="badge badge-danger">Telah Berakhir</span>
                                    @else
                                        <span class="badge badge-warning">{{ $batasWaktu->diffForHumans() }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
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
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Detail Produk</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
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
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Ringkasan Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
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

            @if($pesanan->status_pesanan == 'Menunggu Pembayaran')
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Unggah Bukti Pembayaran</h5>
                </div>
                <div class="card-body">
                    @php
                        $batasWaktu = $pesanan->batas_waktu instanceof \Carbon\Carbon
                            ? $pesanan->batas_waktu
                            : \Carbon\Carbon::parse($pesanan->batas_waktu);
                    @endphp
                    @if($pesanan->batas_waktu && $batasWaktu->isPast())
                        <div class="alert alert-danger">
                            Batas waktu pembayaran telah berakhir. Pesanan ini akan otomatis dibatalkan.
                        </div>
                    @else
                        @if($pesanan->metode_pembayaran == 'transfer_bank')
                            <p class="mb-3">Silakan transfer ke rekening berikut:</p>
                            <div class="alert alert-info mb-3">
                                <p class="mb-1"><strong>Bank BCA</strong></p>
                                <p class="mb-1">No. Rekening: 0240934507</p>
                                <p class="mb-0">Atas Nama: Gamma Setiawan</p>
                            </div>
                        @else
                            <p class="mb-3">Silakan scan QRIS berikut:</p>
                            <div class="text-center mb-3">
                                <img src="{{ asset('images/qris-sample.png') }}" alt="QRIS" class="img-fluid" style="max-width: 200px;">
                            </div>
                        @endif

                        <form action="{{ route('pesanan.payment', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="bukti_pembayaran">Unggah Bukti Pembayaran</label>
                                <input type="file" class="form-control-file" id="bukti_pembayaran" name="bukti_pembayaran" required>
                                <small class="form-text text-muted">Format: JPG, PNG, JPEG. Max: 2MB</small>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Kirim Bukti Pembayaran</button>
                        </form>
                    @endif
                </div>
            </div>
            @endif

            @if($pesanan->status_pesanan == 'Dikirim')
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Konfirmasi Pesanan</h5>
                </div>
                <div class="card-body">
                    <p>Apakah Anda sudah menerima pesanan ini?</p>
                    <form action="{{ route('pesanan.konfirmasi', $pesanan->id_pesanan) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block">Pesanan Diterima</button>
                    </form>
                </div>
            </div>
            @endif

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Tindakan</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('pesanan.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
