@extends('admin.layouts.app')

@section('content')
    <h1>Detail Produk</h1>

    <h2>{{ $produk->nama_ikan }}</h2>
    <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_ikan }}" width="300">
    <p>Deskripsi: {{ $produk->deskripsi }}</p>
    <p>Harga: Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
    <p>Stok: {{ $produk->stok }}</p>
    <p>Jenis Ikan: {{ $produk->jenis_ikan }}</p>

    <a href="{{ route('admin.produk.index') }}" class="btn btn-primary">Kembali</a>
@endsection