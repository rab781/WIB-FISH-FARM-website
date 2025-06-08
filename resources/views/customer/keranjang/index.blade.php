@extends('layouts.customer')

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
            <div class="cart-item-row flex flex-col md:flex-row border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200" data-cart-id="{{ $item->id_keranjang }}" data-price="{{ $item->ukuran && $item->ukuran->harga ? $item->ukuran->harga : $item->produk->harga }}" data-total="{{ $item->total_harga }}">
                <div class="md:w-6 p-4 flex items-center justify-center">
                    <input type="checkbox" name="selected_items[]" value="{{ $item->id_keranjang }}" class="cart-item-checkbox rounded border-gray-300 text-orange-600 focus:ring-orange-500">
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
                                <img src="{{ asset('images/Default-fish.png') }}" alt="Default Fish Image" class="h-full w-full object-contain">
                            @endif
                        </div>

                        <div class="md:ml-4">
                            <h3 class="text-base font-medium text-gray-900">{{ $item->produk->nama_ikan }}</h3>
                            <p class="text-sm text-gray-500">{{ $item->produk->jenis_ikan }}</p>
                            @if($item->ukuran)
                                <p class="text-sm text-gray-500">Ukuran: {{ $item->ukuran->ukuran }}</p>
                                <p class="text-xs text-gray-400">Stok: {{ $item->ukuran->stok }}</p>
                            @else
                                <p class="text-xs text-gray-400">Stok: {{ $item->produk->stok }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Price -->
                <div class="w-32 p-4 flex items-center justify-center">
                    <span class="text-sm font-medium text-gray-900">
                        Rp {{ number_format($item->ukuran && $item->ukuran->harga ? $item->ukuran->harga : $item->produk->harga, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Quantity -->
                <div class="w-32 p-4 flex items-center justify-center">
                    <div class="flex items-center space-x-2">
                        <button type="button" class="quantity-btn quantity-decrease w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors" data-cart-id="{{ $item->id_keranjang }}">
                            <span class="text-lg font-medium">-</span>
                        </button>
                        <input type="number" id="quantity-{{ $item->id_keranjang }}" value="{{ $item->jumlah }}" min="1" class="quantity-input w-16 text-center border border-gray-300 rounded px-2 py-1" readonly>
                        <button type="button" class="quantity-btn quantity-increase w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors" data-cart-id="{{ $item->id_keranjang }}">
                            <span class="text-lg font-medium">+</span>
                        </button>
                    </div>
                </div>

                <!-- Total Price -->
                <div class="w-32 p-4 flex items-center justify-center">
                    <span class="item-total text-sm font-medium text-orange-600">
                        Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Actions -->
                <div class="w-24 p-4 flex items-center justify-center">
                    <form class="delete-form" action="{{ route('keranjang.destroy.post', $item->id_keranjang) }}" method="POST">
                        @csrf
                        <button type="button" class="delete-btn text-red-600 hover:text-red-800 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </form>

        <!-- Summary section -->
        <div class="bg-gray-50 px-4 py-6 space-y-4">
            <div class="flex justify-end mx-4">
                <div class="text-right">
                    <p class="text-sm text-gray-600">Total Produk Terpilih (<span id="selectedItemCount">0</span>):</p>
                    <p class="text-lg font-bold text-orange-600">Rp <span id="selectedTotal">0</span></p>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" id="deleteSelectedBtn" class="px-3 py-1 text-sm text-red-600 hover:text-red-800 border border-red-300 hover:border-red-400 rounded transition-colors">
                        Hapus Terpilih
                </button>
                <button type="button" id="lanjutPembayaranBtn" class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed" disabled>
                        Buat Pesanan
                </button>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-12">
        <div class="text-gray-400 mb-4">
            <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 1.5M7 13h10.5M9 19.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm10.5-1.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Keranjang Belanja Kosong</h3>
        <p class="text-gray-500 mb-6">Anda belum menambahkan produk ke dalam keranjang belanja.</p>
        <a href="{{ route('katalog') }}" class="inline-flex items-center px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Mulai Belanja
        </a>
    </div>
    @endif
</div>

<script>
console.log('CART SCRIPT LOADING - FINAL FIXED VERSION');

// Test SweetAlert availability immediately
const hasSweetAlert = typeof Swal !== 'undefined';
console.log('SweetAlert2 available:', hasSweetAlert);

// Enhanced SweetAlert wrapper with better fallback
function showAlert(config) {
    try {
        if (hasSweetAlert && Swal.fire) {
            return Swal.fire(config);
        }
    } catch (e) {
        console.warn('SweetAlert2 error:', e);
    }

    // Native fallback
    console.log('Using native dialog fallback');
    if (config.showCancelButton) {
        const message = `${config.title || 'Konfirmasi'}\n\n${config.text || ''}`;
        const confirmed = confirm(message);
        return Promise.resolve({ isConfirmed: confirmed });
    } else {
        const message = `${config.title || 'Notifikasi'}\n\n${config.text || ''}`;
        alert(message);
        return Promise.resolve({ isConfirmed: true });
    }
}

// Update cart item visual state
function updateCartItem(checkbox) {
    const row = checkbox.closest('.cart-item-row');
    if (!row) return;

    if (checkbox.checked) {
        row.classList.add('bg-orange-50', 'border-l-4', 'border-l-orange-500');
    } else {
        row.classList.remove('bg-orange-50', 'border-l-4', 'border-l-orange-500');
    }
    updateTotalAndCount();
}

// Update total calculation
function updateTotalAndCount() {
    const selectedItems = document.querySelectorAll('.cart-item-checkbox:checked');
    const totalDisplay = document.getElementById('selectedTotal');
    const countDisplay = document.getElementById('selectedItemCount');
    const checkoutBtn = document.getElementById('lanjutPembayaranBtn');
    const selectAllBtns = document.querySelectorAll('#selectAll, #selectAll2');

    let total = 0;
    let itemCount = 0;

    selectedItems.forEach(item => {
        const row = item.closest('.cart-item-row');
        if (row && row.dataset.total) {
            const itemTotal = parseFloat(row.dataset.total) || 0;
            total += itemTotal;
            itemCount++;
        }
    });

    // Update displays
    if (totalDisplay) totalDisplay.textContent = total.toLocaleString('id-ID');
    if (countDisplay) countDisplay.textContent = itemCount;
    if (checkoutBtn) checkoutBtn.disabled = itemCount === 0;

    // Update select all state
    const allCheckboxes = document.querySelectorAll('.cart-item-checkbox');
    const allSelected = allCheckboxes.length > 0 && selectedItems.length === allCheckboxes.length;
    selectAllBtns.forEach(btn => {
        btn.checked = allSelected;
    });

    console.log(`Updated: ${itemCount} items selected, total: Rp ${total.toLocaleString('id-ID')}`);
}

// Handle quantity changes
function handleQuantityChange(cartId, action) {
    const input = document.getElementById(`quantity-${cartId}`);
    const row = document.querySelector(`[data-cart-id="${cartId}"]`);

    if (!input || !row) {
        console.error('Elements not found for cart ID:', cartId);
        return;
    }

    const currentValue = parseInt(input.value) || 1;
    let newValue = currentValue;

    if (action === 'increase') {
        newValue = currentValue + 1;
    } else if (action === 'decrease') {
        if (currentValue > 1) {
            newValue = currentValue - 1;
        } else {
            // Confirm deletion when reducing to 0
            showAlert({
                title: 'Konfirmasi Hapus',
                text: 'Mengurangi kuantitas menjadi 0 akan menghapus produk dari keranjang. Apakah Anda yakin?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const deleteForm = row.querySelector('.delete-form');
                    if (deleteForm) {
                        deleteForm.submit();
                    }
                }
            });
            return;
        }
    }

    // Update quantity via AJAX
    if (newValue !== currentValue) {
        updateQuantityAjax(cartId, newValue, input, row);
    }
}

// AJAX quantity update
function updateQuantityAjax(cartId, newValue, input, row) {
    const originalValue = input.value;

    // Show loading
    input.disabled = true;
    input.value = newValue;

    const formData = new FormData();
    formData.append('jumlah', newValue);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    fetch(`/keranjang/${cartId}/update`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update row data
            if (data.new_total) {
                row.dataset.total = data.new_total;
                const totalElement = row.querySelector('.item-total');
                if (totalElement && data.formatted_total) {
                    totalElement.textContent = `Rp ${data.formatted_total}`;
                }
            }

            updateTotalAndCount();

            showAlert({
                title: 'Berhasil',
                text: 'Kuantitas berhasil diperbarui',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            throw new Error(data.message || 'Gagal memperbarui kuantitas');
        }
    })
    .catch(error => {
        console.error('Update error:', error);
        input.value = originalValue;

        showAlert({
            title: 'Error',
            text: error.message || 'Terjadi kesalahan saat memperbarui kuantitas',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    })
    .finally(() => {
        input.disabled = false;
    });
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing cart functionality...');

    // Quantity button events
    document.addEventListener('click', function(e) {
        if (e.target.closest('.quantity-decrease')) {
            e.preventDefault();
            const btn = e.target.closest('.quantity-decrease');
            const cartId = btn.getAttribute('data-cart-id');
            console.log('Decrease clicked for cart:', cartId);
            handleQuantityChange(cartId, 'decrease');
        }

        if (e.target.closest('.quantity-increase')) {
            e.preventDefault();
            const btn = e.target.closest('.quantity-increase');
            const cartId = btn.getAttribute('data-cart-id');
            console.log('Increase clicked for cart:', cartId);
            handleQuantityChange(cartId, 'increase');
        }

        // Delete button
        if (e.target.closest('.delete-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.delete-btn');
            const form = btn.closest('.delete-form');

            console.log('Delete button clicked');

            if (!form) {
                console.error('Delete form not found');
                return;
            }

            showAlert({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus produk ini dari keranjang?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('User confirmed deletion');
                    form.submit();
                }
            });
        }

        // Delete selected button
        if (e.target.closest('#deleteSelectedBtn')) {
            e.preventDefault();
            const selectedItems = document.querySelectorAll('.cart-item-checkbox:checked');

            if (selectedItems.length === 0) {
                showAlert({
                    title: 'Tidak Ada Produk Terpilih',
                    text: 'Silakan pilih minimal satu produk untuk dihapus.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            showAlert({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus ${selectedItems.length} produk yang terpilih?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitBulkDelete(selectedItems);
                }
            });
        }

        // Checkout button
        if (e.target.closest('#lanjutPembayaranBtn')) {
            e.preventDefault();
            const selectedItems = document.querySelectorAll('.cart-item-checkbox:checked');

            if (selectedItems.length === 0) {
                showAlert({
                    title: 'Tidak Ada Produk Terpilih',
                    text: 'Silakan pilih minimal satu produk untuk melanjutkan ke pembayaran.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Submit to checkout
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('checkout') }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrfToken);

            selectedItems.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_items[]';
                input.value = checkbox.value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    });

    // Checkbox change events
    document.addEventListener('change', function(e) {
        // Select all checkboxes
        if (e.target.matches('#selectAll, #selectAll2')) {
            const checkboxes = document.querySelectorAll('.cart-item-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = e.target.checked;
                updateCartItem(cb);
            });

            // Sync both select all checkboxes
            document.querySelectorAll('#selectAll, #selectAll2').forEach(cb => {
                cb.checked = e.target.checked;
            });
        }

        // Individual checkboxes
        if (e.target.classList.contains('cart-item-checkbox')) {
            updateCartItem(e.target);
        }
    });

    // Submit bulk delete form
    function submitBulkDelete(selectedItems) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('keranjang.bulk-delete') }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrfToken);

        selectedItems.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_items[]';
            input.value = checkbox.value;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }

    // Initialize totals
    updateTotalAndCount();

    console.log('Cart initialization complete');
});
</script>
@endsection
