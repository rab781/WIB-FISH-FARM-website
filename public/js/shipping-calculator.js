/**
 * Shipping Cost Calculator for TIKI Courier
 * Uses RajaOngkir API to calculate shipping costs from Sumbersari, Jember to customer's address
 */
document.addEventListener('DOMContentLoaded', function() {
    // Fixed origin address for Sumbersari, Jember, Jawa Timur
    const ORIGIN_ID = 30957; // ID untuk Sumbersari, Jember (sesuaikan dengan ID yang benar)
    const COURIER = 'tiki'; // Hanya menggunakan TIKI

    // Calculate weight based on number of fish
    const calculateWeight = (fishCount) => {
        // Each fish weighs 3kg
        return fishCount * 3000; // Berat dalam gram
    };

    // Get elements
    const calculateShippingBtn = document.getElementById('calculate-shipping');
    const shippingResultsContainer = document.getElementById('shipping-results');
    const fishCountSelect = document.getElementById('fish-count');
    const loadingIndicator = document.getElementById('shipping-loading');
    const addressIdInput = document.getElementById('alamat_id');

    // Initialize
    if (calculateShippingBtn && shippingResultsContainer) {
        initShippingCalculator();
    }

    function initShippingCalculator() {
        if (calculateShippingBtn) {
            calculateShippingBtn.addEventListener('click', function(e) {
                e.preventDefault();
                calculateShipping();
            });
        }

        // Auto-calculate when address is selected
        if (addressIdInput) {
            // Use MutationObserver to detect when value changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                        if (addressIdInput.value) {
                            calculateShipping();
                        } else {
                            clearShippingResults();
                        }
                    }
                });
            });

            observer.observe(addressIdInput, { attributes: true });

            // Also check manually if there's already a value
            if (addressIdInput.value) {
                calculateShipping();
            }
        }

        // Also recalculate when fish count changes
        if (fishCountSelect) {
            fishCountSelect.addEventListener('change', function() {
                if (addressIdInput.value) {
                    calculateShipping();
                }
            });
        }
    }

    function calculateShipping() {
        // Check if destination is selected
        const destinationId = addressIdInput.value;
        if (!destinationId) {
            shippingResultsContainer.innerHTML = '<div class="alert alert-warning">Silakan pilih alamat pengiriman terlebih dahulu</div>';
            return;
        }

        // Get fish count
        const fishCount = fishCountSelect ? parseInt(fishCountSelect.value) || 1 : 1;
        const weight = calculateWeight(fishCount);

        // Show loading indicator
        if (loadingIndicator) loadingIndicator.style.display = 'block';
        if (shippingResultsContainer) shippingResultsContainer.innerHTML = '<div class="text-center">Menghitung biaya pengiriman...</div>';

        // Call API to calculate shipping cost
        fetch('/api/ongkir/calculate', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                origin: ORIGIN_ID,
                destination: destinationId,
                weight: weight,
                courier: COURIER
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            displayShippingResults(data, fishCount);
        })
        .catch(error => {
            console.error('Error calculating shipping:', error);
            shippingResultsContainer.innerHTML = `
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                    <p>Gagal menghitung ongkir: ${error.message}</p>
                    <p class="text-sm">Silakan coba lagi nanti atau hubungi customer service.</p>
                </div>
            `;
        })
        .finally(() => {
            if (loadingIndicator) loadingIndicator.style.display = 'none';
        });
    }

    function displayShippingResults(response, fishCount) {
        if (!shippingResultsContainer) return;

        if (response.success && response.data) {
            const services = response.data;

            if (services.length === 0) {
                shippingResultsContainer.innerHTML = `
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded">
                        <p>Tidak ada layanan pengiriman yang tersedia untuk alamat ini.</p>
                    </div>
                `;
                return;
            }

            // Format for volume weight
            const dimensions = '50x50x50'; // 50cm x 50cm x 50cm
            const volume = fishCount * 125000; // 50x50x50 = 125000 cm³ per fish

            let html = `
                <div class="bg-green-50 p-4 rounded-lg border border-green-100 mb-4">
                    <h3 class="font-medium text-lg text-green-800 mb-2">Informasi Pengiriman</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Asal:</p>
                            <p class="font-medium">SUMBERSARI, JEMBER, JAWA TIMUR 68121</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Berat:</p>
                            <p class="font-medium">${(fishCount * 3).toLocaleString()} kg (${fishCount} ikan)</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Dimensi per ikan:</p>
                            <p class="font-medium">${dimensions} cm</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Volume total:</p>
                            <p class="font-medium">${(volume/1000000).toLocaleString()} m³</p>
                        </div>
                    </div>
                </div>
                <div class="overflow-hidden rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layanan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estimasi</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tarif</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
            `;

            services.forEach(service => {
                const costs = service.costs || [];
                costs.forEach(cost => {
                    const etd = cost.etd || 'n/a';
                    html += `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900">${service.code.toUpperCase()}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">${cost.service}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${etd} hari</div>
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">Rp ${parseInt(cost.value).toLocaleString()}</div>
                            </td>
                        </tr>
                    `;
                });
            });

            html += `
                        </tbody>
                    </table>
                </div>
                <div class="text-sm text-gray-500 mt-2">
                    * Biaya pengiriman aktual dapat berubah dan akan dikonfirmasi saat checkout.
                </div>
            `;

            shippingResultsContainer.innerHTML = html;
        } else {
            shippingResultsContainer.innerHTML = `
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded">
                    <p>Tidak dapat menghitung biaya pengiriman.</p>
                    <p class="text-sm">${response.message || 'Silakan coba lagi nanti.'}</p>
                </div>
            `;
        }
    }

    function clearShippingResults() {
        if (shippingResultsContainer) {
            shippingResultsContainer.innerHTML = '';
        }
    }
});
