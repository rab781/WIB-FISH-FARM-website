@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('keranjang.index') }}" class="flex items-center text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Kembali</span>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 ml-4">Checkout Pesanan</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Alamat Pengiriman -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="p-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Alamat Pengiriman</h2>
                </div>
                <div class="p-4">
                    @if($alamatLengkap)
                        <div class="mb-4">
                            <p class="font-medium">{{ Auth::user()->name }}</p>
                            <p class="mt-1 text-gray-600">{{ $alamatLengkap['jalan'] }}</p>
                            <p class="text-gray-600">{{ $alamatLengkap['kecamatan'] }}, {{ $alamatLengkap['kabupaten'] }}</p>
                            <p class="text-gray-600">{{ $alamatLengkap['provinsi'] }}</p>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500 mb-4">Anda belum memiliki alamat pengiriman.</p>
                            <a href="{{ route('alamat.tambah', ['selected_items' => request('selected_items')]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Tambahkan Alamat
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Produk Dipesan -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="p-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Produk Dipesan</h2>
                </div>
                <div class="p-4">
                    <!-- Daftar produk -->
                    @foreach($keranjang as $item)
                    <div class="flex py-4 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                        <div class="flex-shrink-0 w-20 h-20 border border-gray-200 rounded-md overflow-hidden">
                            @if($item->produk->gambar)
                                @if(Str::startsWith($item->produk->gambar, 'uploads/'))
                                    <img src="{{ asset($item->produk->gambar) }}" alt="{{ $item->produk->nama_ikan }}" class="h-full w-full object-contain">
                                @elseif(Str::startsWith($item->produk->gambar, 'storage/'))
                                    <img src="{{ asset($item->produk->gambar) }}" alt="{{ $item->produk->nama_ikan }}" class="h-full w-full object-contain">
                                @else
                                    <img src="{{ asset('storage/' . $item->produk->gambar) }}" alt="{{ $item->produk->nama_ikan }}" class="h-full w-full object-contain">
                                @endif
                            @else
                                <img src="{{ asset('Images/Default-fish.png') }}" class="h-full w-full object-contain opacity-80" alt="Default Fish Image">
                            @endif
                        </div>

                        <div class="ml-4 flex-1 flex flex-col">
                            <div>
                                <div class="flex justify-between">
                                    <h3 class="text-base font-medium text-gray-900">{{ $item->produk->nama_ikan }}</h3>
                                    <p class="text-base font-medium text-gray-900">
                                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                                    </p>
                                </div>
                                @if($item->ukuran)
                                <p class="mt-1 text-sm text-gray-500">Ukuran: {{ $item->ukuran->ukuran }}</p>
                                @endif
                            </div>

                            <div class="flex-1 flex items-end justify-between text-sm">
                                <p class="text-gray-500">Qty {{ $item->jumlah }}</p>
                                <p class="text-gray-500">
                                    @if($item->ukuran && $item->ukuran->harga)
                                        Rp {{ number_format($item->ukuran->harga, 0, ',', '.') }} x {{ $item->jumlah }}
                                    @else
                                        Rp {{ number_format($item->produk->harga, 0, ',', '.') }} x {{ $item->jumlah }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Ringkasan Pesanan -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="p-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Ringkasan Pesanan</h2>
                </div>
                <div class="p-4">
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
                        @csrf
                        @foreach(request('selected_items') as $item)
                            <input type="hidden" name="selected_items[]" value="{{ $item }}">
                        @endforeach

                        <div class="mb-6">
                            <div class="flex justify-between py-2">
                                <p class="text-sm text-gray-600">Subtotal Produk</p>
                                <p class="text-sm font-medium text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex justify-between py-2">
                                <p class="text-sm text-gray-600">Ongkos Kirim</p>
                                <p class="text-sm font-medium text-gray-900" id="ongkirAmount">
                                    @if($alamatLengkap)
                                    Menghitung...
                                    @else
                                    Rp 0
                                    @endif
                                </p>
                            </div>
                            <div class="border-t border-gray-200 mt-2 pt-2">
                                <div class="flex justify-between">
                                    <p class="text-base font-medium text-gray-900">Total Pembayaran</p>
                                    <p class="text-base font-bold text-orange-600" id="totalAmount">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-base font-medium text-gray-900 mb-3">Metode Pembayaran</h3>
                            <div class="space-y-2">
                                @foreach($metodePembayaran as $key => $value)
                                <label class="flex items-center p-3 border rounded-lg {{ $loop->first ? 'border-orange-500 bg-orange-50' : 'border-gray-300' }}">
                                    <input type="radio" name="metode_pembayaran" value="{{ $key }}" class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500" {{ $loop->first ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm font-medium text-gray-900">{{ $value }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 text-base font-medium" {{ $alamatLengkap ? '' : 'disabled' }}>
                            Buat Pesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alamatLengkap = @json($alamatLengkap);

    // Calculate shipping cost if address is available
    if (alamatLengkap) {
        const kabupatenId = alamatLengkap.kabupaten_id;

        // Fetch shipping cost with selected items
        const selectedItems = @json(request('selected_items'));
        const queryString = new URLSearchParams();
        selectedItems.forEach(item => queryString.append('selected_items[]', item));

        fetch('{{ url("checkout/get-ongkir") }}/' + kabupatenId + '?' + queryString.toString())
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update ongkir display
                    document.getElementById('ongkirAmount').textContent = 'Rp ' + numberFormat(data.ongkir);

                    // Add total quantity info if available
                    if (data.jumlah_total) {
                        // Add quantity info below ongkir
                        const ongkirDiv = document.getElementById('ongkirAmount').parentNode;
                        const quantityInfo = document.createElement('div');
                        quantityInfo.className = 'text-xs text-gray-500 mt-1';

                        let infoText = 'Jumlah ikan: ' + data.jumlah_total;
                        if (data.jumlah_total > 3) {
                            const tambahan = Math.ceil((data.jumlah_total - 3) / 3) * 2000;
                            infoText += ' (+ Rp ' + numberFormat(tambahan) + ')';
                        }

                        quantityInfo.textContent = infoText;
                        // Remove previous info if exists
                        const oldInfo = ongkirDiv.querySelector('.text-xs.text-gray-500');
                        if (oldInfo) {
                            ongkirDiv.removeChild(oldInfo);
                        }
                        ongkirDiv.appendChild(quantityInfo);
                    }

                    // Calculate total with proper number handling
                    const subtotal = {{ $subtotal }};
                    const ongkir = parseInt(data.ongkir);
                    const total = subtotal + ongkir;

                    // Log for debugging
                    console.log('Subtotal:', subtotal);
                    console.log('Ongkir:', ongkir);
                    console.log('Total:', total);

                    document.getElementById('totalAmount').textContent = 'Rp ' + numberFormat(total);
                }
            })
            .catch(error => {
                console.error('Error fetching shipping cost:', error);
            });
    }

    // Format number to Indonesian format
    function numberFormat(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
});
</script>
@endsection
