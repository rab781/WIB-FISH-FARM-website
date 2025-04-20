@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Keranjang Belanja</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($keranjang as $item)
                <tr>
                    <td>{{ $item->produk->nama_ikan }}</td>
                    <td>Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('keranjang.update', $item->id_keranjang) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="input-group">
                                <input type="number" name="jumlah" value="{{ $item->jumlah }}" class="form-control">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </td>
                    <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('keranjang.destroy', $item->id_keranjang) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total Harga</td>
                <td>Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
@endsection