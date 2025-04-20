@extends('layouts.customer')

@section('content')
<div class="container">
    <h1>Detail Produk</h1>
    
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $produk->gambar) }}" class="img-fluid" alt="{{ $produk->nama_ikan }}">
        </div>
        <div class="col-md-6">
            <h2>{{ $produk->nama_ikan }}</h2>
            <p>{{ $produk->deskripsi }}</p>
            <p>Harga: Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
            <p>Stok: {{ $produk->stok }}</p>
            <p>Jenis Ikan: {{ $produk->jenis_ikan }}</p>
            
            <h3>Ulasan</h3>
            @if($ulasan->count() > 0)
                @foreach($ulasan as $u)
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $u->user->name }}</h5>
                            <p class="card-text">Rating: {{ $u->rating }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <p>Belum ada ulasan.</p>
            @endif
        </div>
    </div>
</div>
@endsection