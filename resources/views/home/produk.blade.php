@extends('layouts.app')

@section('content')
<div class="container ">
    <h1>Daftar Produk</h1>
    
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
    
    <div class="d-flex justify-content-center">
        {{ $produk->links() }}
    </div>
</div>
@endsection