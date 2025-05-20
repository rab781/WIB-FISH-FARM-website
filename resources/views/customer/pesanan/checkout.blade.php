@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6">Checkout</h2>

    <!-- Alamat -->
    <div class="mb-8 p-4 bg-white rounded shadow">
        <h3 class="text-lg font-semibold mb-2">Alamat Pengiriman</h3>
        @if($alamatLengkap)
            <div class="mb-2">
                <div><span class="font-semibold">Jalan:</span> {{ $alamatLengkap['jalan'] }}</div>
                <div><span class="font-semibold">Kecamatan:</span> {{ $alamatLengkap['kecamatan'] }}</div>
                <div><span class="font-semibold">Kabupaten:</span> {{ $alamatLengkap['kabupaten'] }}</div>
                <div><span class="font-semibold">Provinsi:</span> {{ $alamatLengkap['provinsi'] }}</div>
            </div>
        @else
            <div class="mb-2 text-gray-500">Belum ada alamat pengiriman.</div>
            <a href="{{ route('alamat.edit') }}" class="inline-block px-4 py-2 bg-orange-600 text-white rounded hover:bg-orange-700">Tambah Alamat</a>
        @endif
    </div>

    <!-- Daftar Produk di Keranjang -->
    <div class="mb-8 p-4 bg-white rounded shadow">
        <h3 class="text-lg font-semibold mb-2">Produk Dipesan</h3>
        <table class="w-full text-left">
            <thead>
                <tr>
                    <th class="py-2">Produk</th>
                    <th class="py-2">Harga Satuan</th>
                    <th class="py-2">Jumlah</th>
                    <th class="py-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($keranjang as $item)
                    @php $subtotal = $item->produk->harga * $item->kuantitas; $total += $subtotal; @endphp
                    <tr>
                        <td class="py-2 flex items-center">
                            <img src="{{ asset('storage/'.$item->produk->gambar) }}" alt="{{ $item->produk->nama_produk }}" class="w-12 h-12 object-cover rounded mr-2">
                            {{ $item->produk->nama_produk }}
                        </td>
                        <td class="py-2">Rp {{ number_format($item->produk->harga,0,',','.') }}</td>
                        <td class="py-2">{{ $item->kuantitas }}</td>
                        <td class="py-2">Rp {{ number_format($subtotal,0,',','.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4 text-right font-bold">Subtotal Produk: Rp {{ number_format($total,0,',','.') }}</div>
    </div>

    <!-- Tombol Lanjutkan Pembayaran -->
    <div class="text-right">
        @if($alamatLengkap)
            <form action="{{ route('pesanan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="alamat_pengiriman" value="{{ $alamatLengkap['jalan'] }}, {{ $alamatLengkap['kecamatan'] }}, {{ $alamatLengkap['kabupaten'] }}, {{ $alamatLengkap['provinsi'] }}">
                <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded hover:bg-orange-700">Buat Pesanan</button>
            </form>
        @else
            <p class="text-red-500">Silakan tambahkan alamat terlebih dahulu untuk melanjutkan.</p>
        @endif
    </div>
</div>
@endsection
