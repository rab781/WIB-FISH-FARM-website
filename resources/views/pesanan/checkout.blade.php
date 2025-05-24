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

                            <!-- Opsi Pengiriman -->
                            <div class="py-2">
                                <p class="text-sm text-gray-600 mb-2">Opsi Pengiriman</p>
                                <div class="space-y-2" id="courierOptions">
                                    @if($alamatLengkap)
                                        <div id="loadingIndicator" class="text-sm text-gray-500 py-2 text-center">
                                            <div class="animate-pulse flex space-x-4 justify-center">
                                                <div class="h-4 bg-slate-200 rounded w-28"></div>
                                                <div class="h-4 bg-slate-200 rounded w-20"></div>
                                            </div>
                                            <div class="mt-2">Memuat opsi pengiriman...</div>
                                        </div>
                                        <!-- Tampilkan opsi TIKI langsung untuk pengalaman pengguna yang lebih baik -->
                                        <div id="defaultOptions">
                                            <div class="p-3 mb-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                                <p class="text-sm text-yellow-800">
                                                    <span class="font-medium">Perhatian:</span> Pengiriman ikan hias menggunakan kurir TIKI untuk menjaga keamanan dan kualitas ikan.
                                                </p>
                                            </div>
                                            <label class="flex items-center justify-between p-2 border border-orange-500 bg-orange-50 rounded-lg cursor-pointer mb-1">
                                                <div class="flex items-center">
                                                    <input type="radio" name="shipping_option" value="tiki_REG" class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500" checked>
                                                    <div class="ml-2">
                                                        <div class="font-medium">TIKI REG</div>
                                                        <div class="text-xs text-gray-500">Estimasi: 1-3 hari</div>
                                                    </div>
                                                </div>
                                                <div>                                    <div class="font-semibold" id="defaultShippingCost">Menghitung...</div>
                                </div>
                            </label>
                            <!-- Hidden inputs for courier options -->
                            <input type="hidden" name="courier" value="tiki" id="courierField">
                            <input type="hidden" name="courier_service" id="serviceField">
                            <input type="hidden" name="shipping_cost" id="shippingCostField">

                                            <!-- Informasi pengiriman ikan -->
                                            <div class="p-3 mt-3 bg-orange-50 border border-orange-200 rounded-lg">
                                                <p class="text-sm text-orange-800 font-medium mb-1">Informasi Pengiriman Ikan Hias:</p>
                                                <ul class="list-disc pl-5 text-xs text-orange-700 space-y-1">
                                                    <li>Ikan dikemas dalam box khusus ukuran 40x40x40 cm</li>
                                                    <li>Setiap box dapat memuat maksimal 3 ekor ikan</li>
                                                    <li>Dilengkapi sistem aerasi untuk menjaga kualitas air</li>
                                                    <li>Khusus menggunakan kurir TIKI untuk pengiriman yang aman</li>
                                                    <li>Estimasi waktu pengiriman 1-3 hari kerja</li>
                                                </ul>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-500 py-2 text-center">
                                            Tambahkan alamat terlebih dahulu
                                        </div>
                                    @endif
                                </div>
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

                            <!-- Box Information Display -->
                            <div id="boxInfoContainer" class="hidden mt-3 mb-3 px-3 py-3 bg-orange-50 border border-orange-200 rounded-lg">
                                <div class="flex items-start">
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <div class="ml-2">
                                        <p class="text-xs font-medium text-orange-800">Informasi Box Pengiriman Ikan</p>
                                        <div class="text-xs text-orange-700" id="boxInfo"></div>
                                        <p class="text-xs italic text-orange-700 mt-1">Setiap box dilengkapi dengan sistem aerasi khusus untuk menjaga kesegaran ikan selama pengiriman.</p>
                                    </div>
                                </div>
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
    let currentOngkir = 0;
    let defaultCourier = 'tiki';
    let defaultService = 'REG';

    // Initialize with loading state
    document.getElementById('ongkirAmount').textContent = 'Menghitung...';

    // Add submit handler to form to validate courier selection
    const checkoutForm = document.getElementById('checkoutForm');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            const courierField = document.getElementById('courierField');
            const shippingCostField = document.getElementById('shippingCostField');

            // Validate courier selection
            if (!courierField || courierField.value !== 'tiki') {
                e.preventDefault();
                alert('Pengiriman ikan hias hanya dapat menggunakan kurir TIKI untuk menjaga keamanan dan kualitas ikan.');
                return false;
            }

            // Validate shipping cost
            if (!currentOngkir || currentOngkir === 0) {
                e.preventDefault();
                alert('Mohon tunggu kalkulasi biaya pengiriman selesai.');
                return false;
            }

            // Set shipping cost field before submitting
            if (!shippingCostField) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'shipping_cost';
                input.id = 'shippingCostField';
                input.value = currentOngkir;
                checkoutForm.appendChild(input);
            } else {
                shippingCostField.value = currentOngkir;
            }
        });
    }

    // Function to display box information
    function displayBoxInformation(data) {
        const boxInfoContainer = document.getElementById('boxInfoContainer');
        const boxInfoElement = document.getElementById('boxInfo');

        if (boxInfoContainer && boxInfoElement) {
            boxInfoContainer.classList.remove('hidden');

            // Format the box information text with icons and better formatting
            let boxHTML = `<strong>${data.jumlah_box} box pengiriman</strong> diperlukan untuk pesanan Anda`;

            // Build a more detailed description
            if (data.box_info) {
                boxHTML += `<div class="mt-1 space-y-1">
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-orange-600 mt-0.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Ukuran box: ${data.box_info.ukuran_box}</span>
                    </div>
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-orange-600 mt-0.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Maksimal ${data.ikan_per_box || 3} ikan per box</span>
                    </div>
                    <div class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-orange-600 mt-0.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Berat: ${(data.berat_total / 1000) || data.berat_per_box || 10} kg total</span>
                    </div>
                </div>`;
            }

            boxInfoElement.innerHTML = boxHTML;
        }
    }

    // Function to display quantity info
    function displayQuantityInfo(data) {
        const ongkirElement = document.getElementById('ongkirAmount');
        const ongkirText = ongkirElement.textContent;

        // Create a small info element below the ongkir amount
        let infoElement = document.getElementById('quantityInfo');
        if (!infoElement) {
            infoElement = document.createElement('div');
            infoElement.id = 'quantityInfo';
            infoElement.className = 'text-xs text-gray-500 mt-1';
            ongkirElement.parentNode.appendChild(infoElement);
        }

        // Format the info text
        let infoText = `${data.jumlah_total} ikan dalam ${data.jumlah_box} box`;

        // Add biaya tambahan info if available
        if (data.biaya_tambahan > 0) {
            infoText += ` (+Rp ${numberFormat(data.biaya_tambahan)} biaya tambahan)`;
        }

        infoElement.textContent = infoText;
    }

    // Calculate shipping cost if address is available
    if (alamatLengkap) {
        const alamatId = alamatLengkap.alamat_id;

        // Fetch shipping cost with selected items
        const selectedItems = @json(request('selected_items'));
        const queryString = new URLSearchParams();
        selectedItems.forEach(item => queryString.append('selected_items[]', item));

        // Make API request in the background
        fetch('{{ url("checkout/get-ongkir") }}/' + alamatId + '?' + queryString.toString())
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update ongkir display with actual data
                    currentOngkir = data.ongkir;
                    document.getElementById('ongkirAmount').textContent = 'Rp ' + numberFormat(currentOngkir);

                    // Set the shipping cost field
                    let shippingCostField = document.getElementById('shippingCostField');
                    if (!shippingCostField) {
                        shippingCostField = document.createElement('input');
                        shippingCostField.type = 'hidden';
                        shippingCostField.name = 'shipping_cost';
                        shippingCostField.id = 'shippingCostField';
                        document.getElementById('checkoutForm').appendChild(shippingCostField);
                    }
                    shippingCostField.value = currentOngkir;

                    // Hide loading indicator
                    document.getElementById('loadingIndicator').classList.add('hidden');

                    // Display box information if available
                    if (data.jumlah_box) {
                        displayBoxInformation(data);
                    }

                    // Add quantity info below ongkir if available
                    if (data.jumlah_total) {
                        displayQuantityInfo(data);
                    }

                    // Calculate total with proper number handling
                    updateTotal();

                    // Update the default shipping cost display
                    document.getElementById('defaultShippingCost').textContent = 'Rp ' + numberFormat(currentOngkir);

                    // Update courier service field
                    const serviceField = document.getElementById('serviceField');
                    if (serviceField) {
                        serviceField.value = 'REG';
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching shipping cost:', error);
                // API failed, but we already have default options showing, so no additional UI update needed
            });
    }

    // Render courier options from API (optimized to only show TIKI)
    function renderCourierOptions(options) {
        // Filter to only show TIKI options
        const tikiOptions = options.filter(option => option.courier === 'tiki');

        if (tikiOptions.length === 0) {
            // If no TIKI options found, keep using the default options already shown
            return;
        }

        const container = document.getElementById('courierOptions');
        const optionsContainer = document.createElement('div');

        // Add notice about TIKI requirement
        const noticeDiv = document.createElement('div');
        noticeDiv.className = 'p-3 mb-3 bg-yellow-50 border border-yellow-200 rounded-lg';
        noticeDiv.innerHTML = `
            <p class="text-sm text-yellow-800">
                <span class="font-medium">Perhatian:</span> Pengiriman ikan hias hanya dapat menggunakan kurir TIKI untuk menjaga keamanan dan kualitas ikan selama pengiriman.
            </p>
        `;
        optionsContainer.appendChild(noticeDiv);

        // Create a group for TIKI options
        const courierDiv = document.createElement('div');
        courierDiv.className = 'mb-3';

        const courierTitle = document.createElement('p');
        courierTitle.className = 'font-medium text-gray-700 mb-1';
        courierTitle.textContent = 'TIKI';
        courierDiv.appendChild(courierTitle);

        // Create radio buttons for each TIKI service
        tikiOptions.forEach((service, index) => {
            const label = document.createElement('label');
            label.className = 'flex items-center justify-between p-2 border rounded-lg cursor-pointer mb-1 hover:bg-orange-50';

            // First option is selected by default
            const selected = index === 0;
            if (selected) {
                label.classList.add('border-orange-500', 'bg-orange-50');

                // Update the hidden form fields for the selected courier
                updateCourierHiddenFields('tiki', service.service);

                // Update the shipping cost display
                currentOngkir = service.cost;
                document.getElementById('ongkirAmount').textContent = 'Rp ' + numberFormat(service.cost);
                updateTotal();
            } else {
                label.classList.add('border-gray-200');
            }

            const leftDiv = document.createElement('div');
            const rightDiv = document.createElement('div');
            rightDiv.className = 'text-right';

            // Radio input
            const input = document.createElement('input');
            input.type = 'radio';
            input.name = 'shipping_option';
            input.value = 'tiki_' + service.service;
            input.className = 'w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500';
            input.checked = selected;

            // Add event listener for selection changes
            input.addEventListener('change', function() {
                // Update UI
                document.querySelectorAll('#courierOptions label').forEach(l => {
                    l.classList.remove('border-orange-500', 'bg-orange-50');
                    l.classList.add('border-gray-200');
                });

                label.classList.remove('border-gray-200');
                label.classList.add('border-orange-500', 'bg-orange-50');

                // Update hidden fields
                updateCourierHiddenFields('tiki', service.service);

                // Update shipping cost
                currentOngkir = service.cost;
                document.getElementById('ongkirAmount').textContent = 'Rp ' + numberFormat(service.cost);
                updateTotal();
            });

            // Service details
            const serviceInfo = document.createElement('div');
            serviceInfo.className = 'ml-2';
            serviceInfo.innerHTML = `
                <div class="font-medium">${service.service}</div>
                <div class="text-xs text-gray-500">Estimasi: ${service.etd} hari</div>
            `;

            // Cost
            const costInfo = document.createElement('div');
            costInfo.innerHTML = `<div class="font-semibold">Rp ${numberFormat(service.cost)}</div>`;

            leftDiv.appendChild(input);
            leftDiv.appendChild(serviceInfo);
            rightDiv.appendChild(costInfo);

            const flexContainer = document.createElement('div');
            flexContainer.className = 'flex items-center justify-between w-full';
            flexContainer.appendChild(leftDiv);
            flexContainer.appendChild(rightDiv);

            label.appendChild(flexContainer);
            courierDiv.appendChild(label);
        });

        optionsContainer.appendChild(courierDiv);

        // Replace the loading indicator with our options
        document.getElementById('loadingIndicator').classList.add('hidden');
        document.getElementById('defaultOptions').classList.add('hidden');
        container.appendChild(optionsContainer);
    }

    // Update hidden fields for courier selection
    function updateCourierHiddenFields(courier, service) {
        // Get existing fields
        let courierField = document.getElementById('courierField');
        let serviceField = document.getElementById('serviceField');

        // Create or update fields
        if (!courierField) {
            courierField = document.createElement('input');
            courierField.type = 'hidden';
            courierField.name = 'courier';
            courierField.id = 'courierField';
            document.getElementById('checkoutForm').appendChild(courierField);
        }

        if (!serviceField) {
            serviceField = document.createElement('input');
            serviceField.type = 'hidden';
            serviceField.name = 'courier_service';
            serviceField.id = 'serviceField';
            document.getElementById('checkoutForm').appendChild(serviceField);
        }

        // Set values
        courierField.value = courier;
        serviceField.value = service;
    }

    // Calculate and update the total - optimized version
    function updateTotal() {
        const subtotal = {{ $subtotal }};
        const total = subtotal + currentOngkir;
        document.getElementById('totalAmount').textContent = 'Rp ' + numberFormat(total);
    }

    // Format number to Indonesian format - optimized version
    function numberFormat(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
});
</script>
@endsection
