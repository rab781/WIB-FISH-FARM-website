@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Selamat Datang di Toko Ikan Segar</h1>
    
    <h2>Produk Terbaru</h2>
    <div class="row">
        @foreach($produk as $p)
            <div class="col-md-3">
                <div class="card">
                    <img src="{{ asset('storage/' . $p->gambar) }}" class="card-img-top" alt="{{ $p->nama_ikan }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $p->nama_ikan }}</h5>
                        <p class="card-text">{{ $p->deskripsi }}</p>
                        <p class="card-text">Harga: Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                        <a href="{{ route('detailProduk', $p->id_Produk) }}" class="btn btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <h2>Ulasan Terbaru</h2>
    <div class="row">
        @foreach($ulasan as $u)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $u->user->name }}</h5>
                        <p class="card-text">Produk: {{ $u->produk->nama_ikan }}</p>
                        <p class="card-text">Rating: {{ $u->rating }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection