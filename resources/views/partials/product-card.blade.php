<div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 hover-lift">
    <div class="h-48 bg-gray-200 relative overflow-hidden">
        <img src="{{ $product->image ?? asset('images/placeholder.jpg') }}" alt="{{ $product->name ?? 'Product' }}" class="w-full h-full object-cover">
        <div class="absolute top-2 right-2">
            @if(($product->popularity ?? 0) >= 4)
                <span class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full">Populer</span>
            @endif
        </div>
    </div>
    <div class="p-4">
        <h3 class="font-medium text-gray-900 mb-1">{{ $product->name ?? 'Product Name' }}</h3>
        <div class="flex justify-between items-center mb-2">
            <p class="text-gray-900 font-bold">Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}</p>
            <p class="text-sm text-gray-600">Stok: {{ $product->stock ?? 0 }}</p>
        </div>

        @auth
            <!-- For authenticated users - Add to cart form -->
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id ?? 1 }}">
                <input type="hidden" name="quantity" value="1">
                <input type="hidden" name="name" value="{{ $product->name ?? 'Product' }}">
                <input type="hidden" name="price" value="{{ $product->price ?? 0 }}">
                <input type="hidden" name="image" value="{{ $product->image ?? asset('images/placeholder.jpg') }}">
                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                    Tambah ke Keranjang
                </button>
            </form>
        @else
            <!-- For guests - Show auth modal -->
            <button @click="showAuthWithMessage('Untuk membeli produk, silakan masuk terlebih dahulu jika sudah memiliki akun, atau daftar jika belum memiliki akun.')"
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-3 rounded-md text-sm font-medium transition">
                Tambah ke Keranjang
            </button>
        @endauth
    </div>
</div>
