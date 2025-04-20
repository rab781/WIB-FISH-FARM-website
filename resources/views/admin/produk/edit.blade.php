@extends('admin.layouts.app')

@section('content')
    <h1>Edit Produk</h1>

    <form action="{{ route('admin.produk.update', $produk->id_Produk) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nama_ikan">Nama Ikan</label>
            <input type="text" class="form-control" id="nama_ikan" name="nama_ikan" value="{{ $produk->nama_ikan }}" required>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" required>{{ $produk->deskripsi }}</textarea>
        </div>

        <div class="form-group">
            <label for="stok">Stok</label>
            <input type="number" class="form-control" id="stok" name="stok" value="{{ $produk->stok }}" required>
        </div>

        <div class="form-group">
            <label for="harga">Harga</label>
            <input type="number" class="form-control" id="harga" name="harga" value="{{ $produk->harga }}" required>
        </div>

        <div class="form-group">
            <label for="jenis_ikan">Jenis Ikan</label>
            <input type="text" class="form-control" id="jenis_ikan" name="jenis_ikan" value="{{ $produk->jenis_ikan }}" required>
        </div>

        <div class="form-group">
            <label for="gambar">Gambar</label>
            <input type="file" class="form-control-file" id="gambar" name="gambar">
            @if($produk->gambar)
                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_ikan }}" width="200">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection