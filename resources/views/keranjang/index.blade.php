@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Keranjang Belanja</h1>

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

    @if($keranjang->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <form id="bulkDeleteForm" action="{{ route('keranjang.bulk-delete') }}" method="POST">
            @csrf
            <!-- Header row showing labels/categories -->
            <div class="hidden md:flex border-b border-gray-200 bg-gray-50">
                <div class="w-6 p-4">
                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                </div>
                <div class="flex-1 p-4 text-sm font-medium text-gray-700">Produk</div>
                <div class="w-32 p-4 text-sm font-medium text-gray-700 text-center">Harga Satuan</div>
                <div class="w-32 p-4 text-sm font-medium text-gray-700 text-center">Kuantitas</div>
                <div class="w-32 p-4 text-sm font-medium text-gray-700 text-center">Total Harga</div>
                <div class="w-24 p-4 text-sm font-medium text-gray-700 text-center">Aksi</div>
            </div>

            <!-- Cart items -->
            @foreach($keranjang as $item)
            <div class="flex flex-col md:flex-row border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200" data-price="{{ $item->ukuran && $item->ukuran->harga ? $item->ukuran->harga : $item->produk->harga }}" data-total="{{ $item->total_harga }}">
                <div class="md:w-6 p-4 flex items-center justify-center">
                    <input type="checkbox" name="selected_items[]" value="{{ $item->id_keranjang }}" class="cart-item-checkbox rounded border-gray-300 text-orange-600 focus:ring-orange-500" onchange="updateCartItem(this)">
                </div>

                <div class="flex-1 p-4">
                    <div class="flex flex-col md:flex-row items-start">
                        <div class="w-24 h-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 mb-4 md:mb-0">
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
                        <div class="ml-0 md:ml-4">
                            <h3 class="text-base font-medium text-gray-900">{{ $item->produk->nama_ikan }}</h3>
                            @if($item->ukuran)
                            <p class="text-sm text-gray-500">Ukuran: {{ $item->ukuran->ukuran }}</p>
                            @endif
                            <!-- Mobile view only price -->
                            <p class="mt-1 text-sm text-gray-500 md:hidden">
                                @if($item->ukuran && $item->ukuran->harga)
                                    Rp {{ number_format($item->ukuran->harga, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Price column (hidden on mobile) -->
                <div class="w-32 hidden md:flex items-center justify-center p-4">
                    <span class="text-base text-gray-900">
                        @if($item->ukuran && $item->ukuran->harga)
                            Rp {{ number_format($item->ukuran->harga, 0, ',', '.') }}
                        @else
                            Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                        @endif
                    </span>
                </div>

                <!-- Quantity control -->
                <div class="w-full md:w-32 p-4 flex items-center justify-center">
                    <div class="flex items-center">
                        <button type="button" class="w-8 h-8 flex items-center justify-center bg-gray-100 text-gray-600 rounded-l border border-gray-300" onclick="handleQuantityChange({{ $item->id_keranjang }}, 'decrease')">
                            <span class="sr-only">Decrease quantity</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <input type="number" id="quantity-{{ $item->id_keranjang }}" value="{{ $item->jumlah }}" min="1" max="999" class="w-16 h-8 border-t border-b border-gray-300 text-center focus:outline-none focus:ring-1 focus:ring-orange-500" readonly>
                        <button type="button" class="w-8 h-8 flex items-center justify-center bg-gray-100 text-gray-600 rounded-r border border-gray-300" onclick="handleQuantityChange({{ $item->id_keranjang }}, 'increase')">
                            <span class="sr-only">Increase quantity</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Total price column -->
                <div class="w-full md:w-32 p-4 flex items-center justify-center">
                    <span class="text-base font-medium text-orange-600">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</span>
                </div>

                <!-- Action buttons -->
                <div class="w-24 p-4 flex flex-row md:flex-col items-center md:justify-center space-x-2 md:space-x-0 md:space-y-2">
                    <form action="{{ route('keranjang.destroy.post', $item->id_keranjang) }}" method="POST" class="delete-form">
                        @csrf
                        <button type="submit" class="delete-btn text-red-600 hover:text-red-900">
                            <span class="sr-only">Hapus Produk</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach


            <!-- Summary section -->
            <div class="p-4">
                <div class="bg-orange-50 border border-orange-100 rounded-lg p-4 mb-4">
                    <div class="flex items-center text-orange-700 mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm">Pilih produk yang ingin Anda pesan dengan mencentang kotak di sebelah kiri produk.</p>
                    </div>
                </div>
                <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg">
                    <p class="text-base font-medium">Total Produk Terpilih: <span id="selectedItemCount">0</span></p>
                    <p class="text-xl font-bold text-orange-600">Rp <span id="selectedTotal">0</span></p>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" id="deleteSelectedBtn" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Hapus Produk Terpilih
                    </button>

                    <button type="button" id="lanjutPembayaranBtn" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        Buat Pesanan
                    </button>
                </div>
            </div>
        </form>
    </div>
    @else
    <div class="bg-white rounded-lg shadow overflow-hidden p-6 text-center">
        <div class="flex justify-center mb-4">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <h2 class="text-xl font-medium text-gray-900 mb-2">Keranjang Belanja Kosong</h2>
        <p class="text-gray-500 mb-6">Anda belum memiliki produk di keranjang belanja.</p>
        <a href="{{ route('produk.index') }}" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
            Mulai Belanja
        </a>
    </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    function incrementCartItem(button) {
        let form = button.closest('form');
        let input = form.querySelector('input[type=number]');
        if (input.value < parseInt(input.max) || !input.max) {
            input.value = parseInt(input.value) + 1;
            // Auto submit the form after a short delay
            setTimeout(() => form.submit(), 500);
        }
    }

    function decrementCartItem(button) {
        let form = button.closest('form');
        let input = form.querySelector('input[type=number]');
        if (input.value > 1) {
            input.value = parseInt(input.value) - 1;
            // Auto submit the form after a short delay
            setTimeout(() => form.submit(), 500);
        }
    }

    // Dispatch cart updated event whenever a form is submitted
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.quantity-form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                setTimeout(() => {
                    window.dispatchEvent(new Event('cartUpdated'));
                }, 500);
            });
        });

        // Select all checkbox functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                document.querySelectorAll('.cart-item-checkbox').forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                    updateCartItem(checkbox);
                });
            });
        }

        // Delete confirmation for single item
        const deleteBtns = document.querySelectorAll('.delete-btn');
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if(window.confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?', {
                    confirmButtonText: 'Oke',
                    cancelButtonText: 'Batal'
                })) {
                    this.closest('form').submit();
                }
            });
        });

        // Delete selected items functionality
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        if (deleteSelectedBtn) {
            deleteSelectedBtn.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah form submit secara otomatis
                const selectedItems = document.querySelectorAll('.cart-item-checkbox:checked');
                console.log('Selected items count:', selectedItems.length);

                if (selectedItems.length > 0) {
                    if (window.confirm('Apakah Anda yakin ingin menghapus ' + selectedItems.length + ' produk yang terpilih dari keranjang?', {
                        confirmButtonText: 'Oke',
                        cancelButtonText: 'Batal'
                    })) {
                        document.getElementById('bulkDeleteForm').submit();
                    }
                } else {
                    alert('Tidak ada produk yang dipilih untuk dihapus.');
                }
            });
        }

        // Manage cart item selection and total calculation
        const lanjutBtn = document.getElementById('lanjutPembayaranBtn');
        const checkboxes = document.querySelectorAll('.cart-item-checkbox');
        const selectedTotalDisplay = document.getElementById('selectedTotal');
        const selectedCountDisplay = document.getElementById('selectedItemCount');

        function updateCartItem(checkbox) {
            const row = checkbox.closest('.flex.flex-col.md\\:flex-row');

            // Toggle selected state visual
            if (checkbox.checked) {
                row.classList.add('bg-orange-50');
                row.classList.add('border-l-4');
                row.classList.add('border-l-orange-500');
            } else {
                row.classList.remove('bg-orange-50');
                row.classList.remove('border-l-4');
                row.classList.remove('border-l-orange-500');
            }

            updateTotalAndCount();
        }

        function updateTotalAndCount() {
            const selectedItems = document.querySelectorAll('.cart-item-checkbox:checked');
            let total = 0;

            selectedItems.forEach(item => {
                const row = item.closest('.flex.flex-col.md\\:flex-row');
                total += parseFloat(row.dataset.total);
            });

            selectedTotalDisplay.textContent = total.toLocaleString('id-ID');
            selectedCountDisplay.textContent = selectedItems.length;
            lanjutBtn.disabled = selectedItems.length === 0;
        }

        // Initialize checkboxes
        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => updateCartItem(cb));
        });

        // Handle checkout button click
        lanjutBtn.addEventListener('click', function() {
            const selectedItems = Array.from(document.querySelectorAll('.cart-item-checkbox:checked')).map(cb => cb.value);

            if (selectedItems.length === 0) {
                alert('Silakan pilih minimal satu produk untuk melanjutkan ke pembayaran.');
                return;
            }

            // Create a form dynamically to submit selected items
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('checkout') }}';

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add selected items
            selectedItems.forEach(itemId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_items[]';
                input.value = itemId;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        });

        // Inisialisasi saat load
        updateLanjutBtn();
    });
</script>
@endsection
