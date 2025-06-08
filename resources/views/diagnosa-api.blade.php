@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h1 class="text-2xl font-bold mb-4">Diagnosa API RajaOngkir</h1>

            <div class="mb-6 grid grid-cols-1 gap-4">
                <div class="p-4 bg-gray-50 rounded border border-gray-300">
                    <h2 class="text-xl font-semibold mb-2">Informasi API Key</h2>
                    <div class="mb-2">
                        <span class="font-medium">API Key yang digunakan:</span> <span class="font-mono bg-gray-100 px-2 py-1">{{ substr(env('RAJA_ONGKIR_API_KEY'), 0, 5) }}...</span>
                    </div>
                    <div class="mb-2">
                        <span class="font-medium">Status API Key:</span> <span id="apiKeyStatus" class="px-2 py-1 rounded">Menunggu...</span>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Uji Coba API</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Test API Komerce -->
                    <div>
                        <div class="p-4 bg-gray-50 rounded border border-gray-300">
                            <h3 class="font-medium mb-2">RajaOngkir Komerce API</h3>
                            <button id="testKomerce" class="py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Test API Komerce
                            </button>
                            <div id="komerceResult" class="mt-4 hidden">
                                <div class="flex items-center mb-2">
                                    <span class="font-medium mr-2">Status:</span>
                                    <span id="komerceStatus" class="px-2 py-1 rounded"></span>
                                </div>
                                <pre id="komerceResponse" class="bg-gray-100 p-2 rounded text-xs overflow-auto max-h-56 whitespace-pre-wrap break-all"></pre>
                            </div>
                        </div>
                    </div>

                    <!-- Test RajaOngkir API -->
                    <div>
                        <div class="p-4 bg-gray-50 rounded border border-gray-300">
                            <h3 class="font-medium mb-2">RajaOngkir Standard API</h3>
                            <button id="testRajaOngkir" class="py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600">
                                Test API RajaOngkir
                            </button>
                            <div id="rajaOngkirResult" class="mt-4 hidden">
                                <div class="flex items-center mb-2">
                                    <span class="font-medium mr-2">Status:</span>
                                    <span id="rajaOngKirStatus" class="px-2 py-1 rounded"></span>
                                </div>
                                <pre id="rajaOngKirResponse" class="bg-gray-100 p-2 rounded text-xs overflow-auto max-h-56 whitespace-pre-wrap break-all"></pre>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comprehensive API Test -->
                <div class="p-4 bg-gray-50 rounded border border-gray-300">
                    <h3 class="font-medium mb-2">Uji Coba Komprehensif API RajaOngkir</h3>
                    <button id="testComprehensive" class="py-2 px-4 bg-green-600 text-white rounded hover:bg-green-700">
                        Uji Semua Endpoint API
                    </button>
                    <div id="loadingComprehensive" class="mt-4 hidden">
                        <div class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Mengecek semua endpoint API...</span>
                        </div>
                    </div>
                    <div id="comprehensiveResult" class="mt-4 hidden">
                        <div class="grid grid-cols-1 gap-4">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Endpoint</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">HTTP Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hasil</th>
                                    </tr>
                                </thead>
                                <tbody id="comprehensiveTable" class="bg-white divide-y divide-gray-200">
                                    <!-- Results will be inserted here -->
                                </tbody>
                            </table>

                            <div class="p-4 mt-2" id="comprehensiveConclusion">
                                <!-- Conclusion will be added here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Diagnosa Frontend</h2>

                <div class="p-4 bg-gray-50 rounded border border-gray-300">
                    <h3 class="font-medium mb-2">Test Pencarian Alamat</h3>
                    <div class="mb-4">
                        <label for="testSearchInput" class="block text-sm font-medium text-gray-700">Kata Kunci</label>
                        <input type="text" id="testSearchInput" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Masukkan minimal 3 karakter...">
                    </div>
                    <button id="testSearch" class="py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Cari Alamat
                    </button>
                    <div id="searchResult" class="mt-4 hidden">
                        <div class="flex items-center mb-2">
                            <span class="font-medium mr-2">Status:</span>
                            <span id="searchStatus" class="px-2 py-1 rounded"></span>
                        </div>
                        <div class="mb-2">
                            <span class="font-medium">Hasil ditemukan:</span> <span id="searchCount">0</span>
                        </div>
                        <div class="mb-2">
                            <select id="searchResults" class="block w-full mt-1 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                <option value="">Pilih alamat dari hasil pencarian</option>
                            </select>
                        </div>
                        <pre id="searchResponse" class="bg-gray-100 p-2 rounded text-xs overflow-auto max-h-56 whitespace-pre-wrap break-all"></pre>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h2 class="text-xl font-semibold mb-2">Rekomendasi Perbaikan</h2>
                <div id="recommendations" class="p-4 bg-gray-50 rounded border border-gray-300">
                    <p class="text-sm text-gray-600">Silahkan jalankan diagnosa terlebih dahulu untuk mendapatkan rekomendasi perbaikan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Test Komerce API
        const testKomerceBtn = document.getElementById('testKomerce');
        const komerceResult = document.getElementById('komerceResult');
        const komerceStatus = document.getElementById('komerceStatus');
        const komerceResponse = document.getElementById('komerceResponse');

        testKomerceBtn.addEventListener('click', function() {
            testKomerceBtn.disabled = true;
            testKomerceBtn.innerText = 'Testing...';
            komerceStatus.className = 'px-2 py-1 rounded bg-yellow-200 text-yellow-800';
            komerceStatus.innerText = 'Loading...';

            komerceResult.classList.remove('hidden');

            fetch('/test-komerce')
                .then(response => {
                    return response.json().then(data => {
                        return {
                            status: response.status,
                            ok: response.ok,
                            data
                        };
                    });
                })
                .then(result => {
                    const formattedResponse = JSON.stringify(result.data, null, 2);
                    komerceResponse.textContent = formattedResponse;

                    if (result.ok && result.data.success) {
                        komerceStatus.className = 'px-2 py-1 rounded bg-green-200 text-green-800';
                        komerceStatus.innerText = 'Sukses';
                        updateApiKeyStatus(true, 'Komerce API');
                        addRecommendation('API Komerce berfungsi dengan baik.');
                    } else {
                        komerceStatus.className = 'px-2 py-1 rounded bg-red-200 text-red-800';
                        komerceStatus.innerText = 'Gagal';
                        updateApiKeyStatus(false, 'Komerce API');
                        addRecommendation('API Komerce gagal. Periksa API key dan pastikan endpoint-nya benar.');
                    }
                })
                .catch(error => {
                    komerceStatus.className = 'px-2 py-1 rounded bg-red-200 text-red-800';
                    komerceStatus.innerText = 'Error';
                    komerceResponse.textContent = 'Error: ' + error.message;
                    updateApiKeyStatus(false, 'Komerce API - Error');
                    addRecommendation('Terjadi error saat memanggil API Komerce. Periksa koneksi internet atau firewall.');
                })
                .finally(() => {
                    testKomerceBtn.disabled = false;
                    testKomerceBtn.innerText = 'Test API Komerce';
                });
        });

        // Test RajaOngkir API
        const testRajaOngkirBtn = document.getElementById('testRajaOngkir');
        const rajaOngkirResult = document.getElementById('rajaOngKirResult');
        const rajaOngKirStatus = document.getElementById('rajaOngKirStatus');
        const rajaOngKirResponse = document.getElementById('rajaOngKirResponse');

        testRajaOngkirBtn.addEventListener('click', function() {
            testRajaOngkirBtn.disabled = true;
            testRajaOngkirBtn.innerText = 'Testing...';
            rajaOngKirStatus.className = 'px-2 py-1 rounded bg-yellow-200 text-yellow-800';
            rajaOngKirStatus.innerText = 'Loading...';

            rajaOngkirResult.classList.remove('hidden');

            fetch('/test-standard')
                .then(response => {
                    return response.json().then(data => {
                        return {
                            status: response.status,
                            ok: response.ok,
                            data
                        };
                    });
                })
                .then(result => {
                    const formattedResponse = JSON.stringify(result.data, null, 2);
                    rajaOngKirResponse.textContent = formattedResponse;

                    if (result.ok && result.data.success) {
                        rajaOngKirStatus.className = 'px-2 py-1 rounded bg-green-200 text-green-800';
                        rajaOngKirStatus.innerText = 'Sukses';
                        updateApiKeyStatus(true, 'Standard API');
                        addRecommendation('API Standard RajaOngkir berfungsi dengan baik.');
                    } else {
                        rajaOngKirStatus.className = 'px-2 py-1 rounded bg-red-200 text-red-800';
                        rajaOngKirStatus.innerText = 'Gagal';
                        updateApiKeyStatus(false, 'Standard API');
                        addRecommendation('API Standard RajaOngkir gagal. API key mungkin tidak cocok dengan API Standard.');
                    }
                })
                .catch(error => {
                    rajaOngKirStatus.className = 'px-2 py-1 rounded bg-red-200 text-red-800';
                    rajaOngKirStatus.innerText = 'Error';
                    rajaOngKirResponse.textContent = 'Error: ' + error.message;
                    updateApiKeyStatus(false, 'Standard API - Error');
                    addRecommendation('Terjadi error saat memanggil API Standard RajaOngkir.');
                })
                .finally(() => {
                    testRajaOngkirBtn.disabled = false;
                    testRajaOngkirBtn.innerText = 'Test API RajaOngkir';
                });
        });

        // Test Search
        const testSearchBtn = document.getElementById('testSearch');
        const testSearchInput = document.getElementById('testSearchInput');
        const searchResult = document.getElementById('searchResult');
        const searchStatus = document.getElementById('searchStatus');
        const searchCount = document.getElementById('searchCount');
        const searchResults = document.getElementById('searchResults');
        const searchResponse = document.getElementById('searchResponse');

        testSearchBtn.addEventListener('click', function() {
            const searchTerm = testSearchInput.value;

            if (searchTerm.length < 3) {
                Swal.fire({
                    title: 'Input Tidak Valid',
                    text: 'Masukkan minimal 3 karakter untuk pencarian',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            testSearchBtn.disabled = true;
            testSearchBtn.innerText = 'Mencari...';
            searchStatus.className = 'px-2 py-1 rounded bg-yellow-200 text-yellow-800';
            searchStatus.innerText = 'Loading...';

            searchResult.classList.remove('hidden');

            // Clear current select options except the first one
            while (searchResults.options.length > 1) {
                searchResults.remove(1);
            }

            fetch(`/api/alamat/search?term=${searchTerm}`)
                .then(response => {
                    return response.json().then(data => {
                        return {
                            status: response.status,
                            ok: response.ok,
                            data
                        };
                    });
                })
                .then(result => {
                    const formattedResponse = JSON.stringify(result.data, null, 2);
                    searchResponse.textContent = formattedResponse;

                    if (result.ok && result.data.success) {
                        searchStatus.className = 'px-2 py-1 rounded bg-green-200 text-green-800';
                        searchStatus.innerText = 'Sukses';

                        if (result.data.data && result.data.data.length > 0) {
                            searchCount.textContent = result.data.data.length;

                            result.data.data.forEach(item => {
                                let formattedAddress;

                                if (item.subdistrict && item.subdistrict.trim() !== '') {
                                    formattedAddress = `${item.subdistrict}, ${item.type} ${item.city}, ${item.province} ${item.postal_code}`;
                                } else {
                                    formattedAddress = `${item.type} ${item.city}, ${item.province} ${item.postal_code}`;
                                }

                                const option = new Option(formattedAddress, item.id);
                                searchResults.add(option);
                            });

                            addRecommendation('Pencarian alamat berfungsi dengan baik.');
                        } else {
                            searchCount.textContent = "0";
                            addRecommendation('Tidak ada hasil pencarian untuk kata kunci tersebut. Coba kata kunci lain.');
                        }
                    } else {
                        searchStatus.className = 'px-2 py-1 rounded bg-red-200 text-red-800';
                        searchStatus.innerText = 'Gagal';
                        searchCount.textContent = "0";
                        addRecommendation('Pencarian alamat gagal. Periksa implementasi RajaOngkir controller.');
                    }
                })
                .catch(error => {
                    searchStatus.className = 'px-2 py-1 rounded bg-red-200 text-red-800';
                    searchStatus.innerText = 'Error';
                    searchResponse.textContent = 'Error: ' + error.message;
                    searchCount.textContent = "0";
                    addRecommendation('Terjadi error saat melakukan pencarian alamat: ' + error.message);
                })
                .finally(() => {
                    testSearchBtn.disabled = false;
                    testSearchBtn.innerText = 'Cari Alamat';
                });
        });

        // Test Comprehensive API
        const testComprehensiveBtn = document.getElementById('testComprehensive');
        const loadingComprehensive = document.getElementById('loadingComprehensive');
        const comprehensiveResult = document.getElementById('comprehensiveResult');
        const comprehensiveTable = document.getElementById('comprehensiveTable');
        const comprehensiveConclusion = document.getElementById('comprehensiveConclusion');

        testComprehensiveBtn.addEventListener('click', function() {
            testComprehensiveBtn.disabled = true;
            testComprehensiveBtn.innerText = 'Menguji...';
            loadingComprehensive.classList.remove('hidden');
            comprehensiveResult.classList.add('hidden');
            comprehensiveTable.innerHTML = '';
            comprehensiveConclusion.innerHTML = '';

            // Call our new comprehensive API test endpoint
            fetch('/test-rajaongkir-api')
                .then(response => response.json())
                .then(data => {
                    // Status indicator for API key
                    if (data.api_key_available) {
                        updateApiKeyStatus(true, 'RajaOngkir V2');
                    } else {
                        updateApiKeyStatus(false, 'RajaOngkir V2');
                        addRecommendation('API key RajaOngkir tidak tersedia. Periksa konfigurasi .env Anda.');
                    }

                    // Process each endpoint result
                    let totalSuccess = 0;
                    Object.entries(data.test_results).forEach(([endpoint, result]) => {
                        // Format endpoint name for display
                        let endpointName = endpoint.replace(/_/g, ' ');
                        endpointName = endpointName.charAt(0).toUpperCase() + endpointName.slice(1);

                        // Create a new row in the table
                        const row = document.createElement('tr');
                        row.className = 'hover:bg-gray-100';

                        // Status badge color
                        const statusClass = result.success ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800';
                        const statusText = result.success ? 'Sukses' : 'Gagal';

                        // Count successes
                        if (result.success) totalSuccess++;

                        // Format response sample for display
                        let responseSample = '';
                        try {
                            if (typeof result.response_sample === 'string') {
                                if (result.response_sample.length > 300) {
                                    responseSample = result.response_sample.substring(0, 300) + '...';
                                } else {
                                    responseSample = result.response_sample;
                                }
                            } else {
                                responseSample = JSON.stringify(result.response_sample, null, 2);
                            }
                        } catch (e) {
                            responseSample = 'Error parsing response data';
                        }

                        // Add recommendations based on result
                        if (result.success) {
                            addRecommendation(`Endpoint ${endpointName} berfungsi dengan baik.`);
                        } else {
                            addRecommendation(`Endpoint ${endpointName} gagal. Status code: ${result.status_code}`);
                        }

                        // Create the table row
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${endpointName}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 py-1 rounded ${statusClass}">${statusText}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${result.status_code || 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-pre-wrap text-sm text-gray-500">
                                <button class="text-blue-500 hover:underline mb-1" onclick="toggleResponse('${endpoint}')">Toggle Details</button>
                                <div id="response-${endpoint}" class="hidden">
                                    <pre class="bg-gray-100 p-2 rounded text-xs overflow-auto max-h-56 whitespace-pre-wrap break-all">${responseSample}</pre>
                                </div>
                            </td>
                        `;

                        comprehensiveTable.appendChild(row);
                    });

                    // Add success rate for conclusion
                    const totalEndpoints = Object.keys(data.test_results).length;
                    const successRate = Math.round((totalSuccess / totalEndpoints) * 100);

                    // Style conclusion based on success rate
                    let conclusionClass = 'bg-yellow-50 text-yellow-700';

                    if (successRate >= 70) {
                        conclusionClass = 'bg-green-50 text-green-700';
                    } else if (successRate <= 30) {
                        conclusionClass = 'bg-red-50 text-red-700';
                    }

                    comprehensiveConclusion.innerHTML = `
                        <div class="p-4 rounded-lg ${conclusionClass}">
                            <h4 class="font-medium mb-2">Hasil Diagnosa API RajaOngkir</h4>
                            <p>${data.message}</p>
                            <p class="mt-1"><strong>Success Rate:</strong> ${successRate}% (${totalSuccess}/${totalEndpoints} endpoints berhasil)</p>
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error in comprehensive test:', error);
                    comprehensiveConclusion.innerHTML = `
                        <div class="p-4 bg-red-50 rounded-lg">
                            <p class="text-sm text-red-700">Terjadi error saat melakukan pengujian: ${error.message}</p>
                        </div>
                    `;
                    addRecommendation('Terjadi error saat menjalankan pengujian komprehensif API: ' + error.message);
                })
                .finally(() => {
                    testComprehensiveBtn.disabled = false;
                    testComprehensiveBtn.innerText = 'Uji Semua Endpoint API';
                    loadingComprehensive.classList.add('hidden');
                    comprehensiveResult.classList.remove('hidden');
                });
        });

        // Helper functions
        function updateApiKeyStatus(isSuccess, api) {
            const apiKeyStatus = document.getElementById('apiKeyStatus');
            if (isSuccess) {
                apiKeyStatus.className = 'px-2 py-1 rounded bg-green-200 text-green-800';
                apiKeyStatus.innerText = 'Valid (' + api + ')';
            } else {
                apiKeyStatus.className = 'px-2 py-1 rounded bg-red-200 text-red-800';
                apiKeyStatus.innerText = 'Tidak Valid (' + api + ')';
            }
        }

        function addRecommendation(text) {
            const recommendations = document.getElementById('recommendations');
            const existingText = recommendations.innerHTML.includes('Silahkan jalankan diagnosa')
                ? ''
                : recommendations.innerHTML;

            const newRecommendation = `<div class="p-2 mb-2 border-l-4 border-blue-500 bg-blue-50">
                <p class="text-sm">${text}</p>
            </div>`;

            recommendations.innerHTML = existingText + newRecommendation;
        }
    });

    // Function to toggle response visibility
    function toggleResponse(endpoint) {
        const responseElement = document.getElementById(`response-${endpoint}`);
        if (responseElement.classList.contains('hidden')) {
            responseElement.classList.remove('hidden');
        } else {
            responseElement.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection
