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
            <div class="flex flex-col md:flex-row border-b border-gray-200 hover:bg-gray-50">
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
                <div class="flex justify-between items-center">
                    <p class="text-base font-medium">Total ({{ $keranjang->count() }} produk):</p>
                    <p class="text-xl font-bold text-orange-600">Rp {{ number_format($totalHarga, 0, ',', '.') }}</p>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" id="deleteSelectedBtn" class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Hapus Produk Terpilih
                    </button>

                    <a href="#" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Lanjut ke Pembayaran
                    </a>
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
        <a href="{{ route('produk') }}" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
            Mulai Belanja
        </a>
    </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
    // Fungsi untuk menangani perubahan kuantitas
    function handleQuantityChange(cartItemId, action) {
        const quantityInput = document.getElementById(`quantity-${cartItemId}`);
        let currentQuantity = parseInt(quantityInput.value);

        if (action === 'increase') {
            currentQuantity += 1;
        } else if (action === 'decrease') {
            if (currentQuantity > 1) {
                currentQuantity -= 1;
            } else {
                if (confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                    console.log('Menghapus item dengan ID:', cartItemId);

                    // Gunakan XMLHttpRequest untuk menghindari masalah dengan fetch API
                    const xhr = new XMLHttpRequest();
                    const url = `/keranjang/${cartItemId}/delete`;

                    // Buat form data untuk mengirim CSRF token
                    const formData = new FormData();
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    formData.append('_token', csrfToken);

                    xhr.open('POST', url, true);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4) {
                            console.log('Response status:', xhr.status);
                            if (xhr.status >= 200 && xhr.status < 300) {
                                // Berhasil, reload halaman
                                window.location.reload();
                            } else {
                                // Gagal, tampilkan pesan error
                                console.error('Error response:', xhr.responseText);
                                alert('Terjadi kesalahan saat menghapus item');
                            }
                        }
                    };
                    xhr.send(formData);
                    return;
                }
                return;
            }
        }

        // Update nilai di input
        quantityInput.value = currentQuantity;

        // Kirim request update ke server menggunakan XMLHttpRequest
        const xhr = new XMLHttpRequest();
        const url = `/keranjang/${cartItemId}/update`;

        // Buat form data
        const formData = new FormData();
        formData.append('jumlah', currentQuantity);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        xhr.open('POST', url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            // Sukses, reload halaman untuk memperbarui data
                            window.location.reload();
                        } else {
                            // Gagal, tampilkan pesan error
                            alert(data.message || 'Terjadi kesalahan saat memperbarui kuantitas');
                            window.location.reload();
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        window.location.reload();
                    }
                } else {
                    // Gagal, tampilkan pesan error
                    console.error('Error updating quantity:', xhr.responseText);
                    alert('Terjadi kesalahan saat memperbarui kuantitas');
                    window.location.reload();
                }
            }
        };
        xhr.send(formData);
    }

    // Fungsi lama yang mungkin masih digunakan di tempat lain
    function updateQuantity(cartItemId, increase) {
        handleQuantityChange(cartItemId, increase ? 'increase' : 'decrease');
    }

    document.addEventListener('DOMContentLoaded', function() {
        // PERBAIKAN: Tangani tombol delete untuk semua item
        document.querySelectorAll('.delete-form').forEach(form => {
            // Hapus event listener yang ada (jika ada)
            const oldForm = form;
            const newForm = oldForm.cloneNode(true);
            if (oldForm.parentNode) {
                oldForm.parentNode.replaceChild(newForm, oldForm);
            }

            // Tambahkan event listener baru dengan pendekatan yang berbeda
            newForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Hentikan submit form default

                // Ambil ID item dari URL form
                const itemId = this.action.split('/').pop();
                console.log('Delete form submitted for item ID:', itemId);

                if (confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
                    // Gunakan pendekatan XMLHttpRequest untuk menghindari masalah potensial dengan fetch
                    const xhr = new XMLHttpRequest();
                    const url = this.action;
                    const formData = new FormData(this);

                    xhr.open('POST', url, true);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4) {
                            console.log('Response status:', xhr.status);
                            if (xhr.status >= 200 && xhr.status < 300) {
                                // Berhasil, reload halaman
                                window.location.reload();
                            } else {
                                // Gagal, tampilkan pesan error
                                console.error('Error response:', xhr.responseText);
                                alert('Terjadi kesalahan saat menghapus item');
                            }
                        }
                    };
                    xhr.send(formData);
                }
            });

            // Tambahkan juga event listener untuk tombol delete di dalam form
            const deleteBtn = newForm.querySelector('.delete-btn');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation(); // Hentikan event propagation
                    console.log('Delete button clicked, triggering form submit');
                    newForm.dispatchEvent(new Event('submit'));
                });
            }
        });

        // PERBAIKAN: Rest of the event listeners...
        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                document.querySelectorAll('.cart-item-checkbox').forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
        }

        // PERBAIKAN: Bulk delete handler
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        if (deleteSelectedBtn) {
            deleteSelectedBtn.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah form submit secara otomatis
                const selectedItems = document.querySelectorAll('.cart-item-checkbox:checked');
                console.log('Selected items count:', selectedItems.length);

                if (selectedItems.length > 0) {
                    if (confirm('Apakah Anda yakin ingin menghapus ' + selectedItems.length + ' produk yang terpilih dari keranjang?')) {
                        // Pastikan semua checkbox yang dipilih masuk ke form
                        const form = document.getElementById('bulkDeleteForm');

                        // Bersihkan input hidden yang ada untuk menghindari duplikasi
                        form.querySelectorAll('input[name="selected_items[]"]').forEach(input => {
                            if (input.type === 'hidden') {
                                input.remove();
                            }
                        });

                        // Tambahkan input hidden untuk setiap item yang dipilih
                        selectedItems.forEach((item, index) => {
                            console.log(`Item ${index+1} akan dihapus: id=${item.value}`);

                            // Buat elemen input baru untuk memastikan data terkirim
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'selected_items[]';
                            hiddenInput.value = item.value;
                            form.appendChild(hiddenInput);
                        });

                        // Log form yang akan dikirim
                        const formData = new FormData(form);
                        console.log('Form akan dikirim dengan data:');
                        for (let pair of formData.entries()) {
                            console.log(pair[0] + ': ' + pair[1]);
                        }

                        // Submit form
                        form.submit();
                    }
                } else {
                    alert('Tidak ada produk yang dipilih untuk dihapus.');
                }
            });
        }
    });
</script>
@endsection
