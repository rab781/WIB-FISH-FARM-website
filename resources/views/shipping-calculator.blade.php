<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TIKI Fish Shipping Cost Calculator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-blue-600 mb-2">
                <i class="fas fa-fish text-blue-500"></i>
                TIKI Fish Shipping Calculator
            </h1>
            <p class="text-gray-600">Test RajaOngkir API integration with TIKI courier for live fish delivery</p>
            <div class="mt-4">
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                    <i class="fas fa-check-circle"></i> API Status: Active
                </span>
            </div>
        </div>

        <!-- API Test Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Quick API Tests -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-vials text-purple-500"></i>
                    API Connection Tests
                </h2>
                <div class="space-y-3">
                    <button onclick="testApiConnection()" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-plug"></i> Test API Connection
                    </button>
                    <button onclick="testAddressSearch()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-search"></i> Test Address Search
                    </button>
                    <button onclick="testDomesticCost()" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-calculator"></i> Test Domestic Cost API
                    </button>
                </div>
                <div id="apiTestResults" class="mt-4 p-3 bg-gray-50 rounded-lg min-h-[100px] font-mono text-sm overflow-auto max-h-60"></div>
            </div>

            <!-- System Info -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    System Information
                </h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="font-medium">API Provider:</span>
                        <span class="text-blue-600">RajaOngkir Komerce</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Courier:</span>
                        <span class="text-green-600">TIKI (Specialized)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Service Type:</span>
                        <span class="text-orange-600">Domestic Shipping</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Min Weight:</span>
                        <span class="text-gray-600">1000g (1kg)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Filtered Services:</span>
                        <span class="text-red-600">No Motorcycle/Trucking</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Calculator Form -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">
                <i class="fas fa-shipping-fast text-blue-500"></i>
                Calculate Shipping Cost
            </h2>

            <form id="shippingForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Origin City -->
                    <div>
                        <label for="origin" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-green-500"></i>
                            Origin City
                        </label>
                        <div class="relative">
                            <input type="text" id="originSearch"
                                   placeholder="Search origin city (e.g., Jakarta)"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <input type="hidden" id="origin" name="origin">
                            <div id="originResults" class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-y-auto hidden"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Selected: <span id="selectedOrigin" class="font-medium">None</span></p>
                    </div>

                    <!-- Destination City -->
                    <div>
                        <label for="destination" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-red-500"></i>
                            Destination City
                        </label>
                        <div class="relative">
                            <input type="text" id="destinationSearch"
                                   placeholder="Search destination city"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <input type="hidden" id="destination" name="destination">
                            <div id="destinationResults" class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-y-auto hidden"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Selected: <span id="selectedDestination" class="font-medium">None</span></p>
                    </div>
                </div>

                <!-- Weight Input -->
                <div>
                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-weight text-orange-500"></i>
                        Package Weight (grams)
                    </label>
                    <div class="relative">
                        <input type="number" id="weight" name="weight" min="1000" step="100" value="1000"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500">grams</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimum weight: 1000g (1kg) - Suitable for live fish packaging</p>
                </div>

                <!-- Calculate Button -->
                <button type="submit" id="calculateBtn"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                    <i class="fas fa-calculator mr-2"></i>
                    Calculate TIKI Shipping Cost
                </button>
            </form>
        </div>

        <!-- Results Section -->
        <div id="resultsSection" class="mt-8 hidden">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-semibold text-gray-800 mb-6">
                    <i class="fas fa-truck text-green-500"></i>
                    TIKI Shipping Options
                </h3>
                <div id="shippingResults"></div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
            <div class="flex items-center justify-center h-full">
                <div class="bg-white rounded-lg p-8 text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
                    <p class="text-gray-700">Calculating shipping costs...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // CSRF token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Address search functionality
        function setupAddressSearch(searchInputId, resultsId, hiddenInputId, selectedDisplayId) {
            let searchTimeout;

            $(`#${searchInputId}`).on('input', function() {
                const searchTerm = $(this).val();
                const resultsDiv = $(`#${resultsId}`);

                clearTimeout(searchTimeout);

                if (searchTerm.length < 2) {
                    resultsDiv.addClass('hidden');
                    return;
                }

                searchTimeout = setTimeout(() => {
                    $.get('/public/search-alamat', { search: searchTerm })
                        .done(function(response) {
                            if (response.success && response.data.length > 0) {
                                let html = '';
                                response.data.forEach(item => {
                                    html += `
                                        <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 address-item"
                                             data-id="${item.city_id}"
                                             data-full-text="${item.full_address}">
                                            <div class="font-medium text-gray-800">${item.kabupaten}, ${item.provinsi}</div>
                                            <div class="text-sm text-gray-600">${item.kecamatan} - ${item.kode_pos}</div>
                                        </div>
                                    `;
                                });
                                resultsDiv.html(html).removeClass('hidden');

                                // Handle selection
                                resultsDiv.find('.address-item').on('click', function() {
                                    const cityId = $(this).data('id');
                                    const fullText = $(this).data('full-text');

                                    $(`#${hiddenInputId}`).val(cityId);
                                    $(`#${searchInputId}`).val(fullText);
                                    $(`#${selectedDisplayId}`).text(fullText);
                                    resultsDiv.addClass('hidden');
                                });
                            } else {
                                resultsDiv.html('<div class="p-3 text-gray-500">No results found</div>').removeClass('hidden');
                            }
                        })
                        .fail(function() {
                            resultsDiv.html('<div class="p-3 text-red-500">Error searching addresses</div>').removeClass('hidden');
                        });
                }, 300);
            });
        }

        // Initialize address search for both origin and destination
        setupAddressSearch('originSearch', 'originResults', 'origin', 'selectedOrigin');
        setupAddressSearch('destinationSearch', 'destinationResults', 'destination', 'selectedDestination');

        // Hide dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.relative').length) {
                $('.address-item').parent().addClass('hidden');
            }
        });

        // Form submission
        $('#shippingForm').on('submit', function(e) {
            e.preventDefault();

            const origin = $('#origin').val();
            const destination = $('#destination').val();
            const weight = $('#weight').val();

            if (!origin || !destination || !weight) {
                alert('Please fill in all fields');
                return;
            }

            if (weight < 1000) {
                alert('Minimum weight is 1000 grams (1kg)');
                return;
            }

            calculateShipping(origin, destination, weight);
        });

        function calculateShipping(origin, destination, weight) {
            $('#loadingState').removeClass('hidden');
            $('#resultsSection').addClass('hidden');

            // Try web endpoint first (no throttle), fallback to API endpoint
            $.post('/web/cek-ongkir', {
                origin: origin,
                destination: destination,
                weight: weight
            })
            .done(function(response) {
                $('#loadingState').addClass('hidden');

                if (response.success) {
                    displayShippingResults(response.data);
                } else {
                    showError('Failed to calculate shipping cost: ' + response.message);
                }
            })
            .fail(function(xhr) {
                console.log('Web endpoint failed, trying API endpoint...');

                // Fallback to API endpoint
                $.post('/api/cek-ongkir', {
                    origin: origin,
                    destination: destination,
                    weight: weight
                })
                .done(function(response) {
                    $('#loadingState').addClass('hidden');

                    if (response.success) {
                        displayShippingResults(response.data);
                    } else {
                        showError('Failed to calculate shipping cost: ' + response.message);
                    }
                })
                .fail(function(xhr2) {
                    $('#loadingState').addClass('hidden');
                    const errorMsg = xhr2.responseJSON ? xhr2.responseJSON.message : 'Network error occurred';
                    showError('Error: ' + errorMsg);
                });
            });
        }

        function displayShippingResults(data) {
            const options = data.shipping_options;
            const recommended = data.recommended;

            let html = '';

            if (recommended) {
                html += `
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <h4 class="text-lg font-semibold text-green-800 mb-2">
                            <i class="fas fa-star text-yellow-500"></i>
                            Recommended Option
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span class="text-sm text-green-600">Service:</span>
                                <div class="font-medium">${recommended.service} - ${recommended.service_description}</div>
                            </div>
                            <div>
                                <span class="text-sm text-green-600">Cost:</span>
                                <div class="font-bold text-lg text-green-700">${recommended.formatted_cost}</div>
                            </div>
                            <div>
                                <span class="text-sm text-green-600">Delivery Time:</span>
                                <div class="font-medium">${recommended.etd_text}</div>
                            </div>
                        </div>
                    </div>
                `;
            }

            html += '<h4 class="text-lg font-semibold text-gray-800 mb-4">All Available TIKI Options:</h4>';
            html += '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">';

            options.forEach((option, index) => {
                const isRecommended = recommended && option.service === recommended.service;
                const cardClass = isRecommended ? 'border-green-500 bg-green-50' : 'border-gray-200 bg-white';

                html += `
                    <div class="border-2 ${cardClass} rounded-lg p-4 hover:shadow-md transition duration-200">
                        <div class="flex justify-between items-start mb-2">
                            <h5 class="font-semibold text-gray-800">${option.service}</h5>
                            ${isRecommended ? '<span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">BEST</span>' : ''}
                        </div>
                        <p class="text-sm text-gray-600 mb-3">${option.service_description}</p>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Cost:</span>
                                <span class="font-bold text-lg text-blue-600">${option.formatted_cost}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Delivery:</span>
                                <span class="font-medium">${option.etd_text}</span>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';

            // Add courier info
            html += `
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h5 class="font-semibold text-blue-800 mb-2">
                        <i class="fas fa-info-circle"></i>
                        Courier Information
                    </h5>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-blue-600">Name:</span>
                            <div class="font-medium">${data.courier_info.name}</div>
                        </div>
                        <div>
                            <span class="text-blue-600">Code:</span>
                            <div class="font-medium">${data.courier_info.code.toUpperCase()}</div>
                        </div>
                        <div>
                            <span class="text-blue-600">Specialization:</span>
                            <div class="font-medium">${data.courier_info.specialization}</div>
                        </div>
                    </div>
                </div>
            `;

            $('#shippingResults').html(html);
            $('#resultsSection').removeClass('hidden');
        }

        function showError(message) {
            $('#resultsSection').removeClass('hidden');
            $('#shippingResults').html(`
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-red-800 mb-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        Error
                    </h4>
                    <p class="text-red-700">${message}</p>
                </div>
            `);
        }

        // API Test Functions
        function testApiConnection() {
            const resultsDiv = $('#apiTestResults');
            resultsDiv.html('<div class="text-blue-600">Testing API connection...</div>');

            $.get('/test-komerce')
                .done(function(response) {
                    resultsDiv.html(`
                        <div class="text-green-600 font-bold">✓ API Connection Test</div>
                        <div class="text-sm mt-2">Status: ${response.status_code}</div>
                        <div class="text-sm">Success: ${response.success ? 'Yes' : 'No'}</div>
                        <pre class="text-xs mt-2 text-gray-600">${JSON.stringify(response, null, 2)}</pre>
                    `);
                })
                .fail(function() {
                    resultsDiv.html('<div class="text-red-600">✗ API Connection Failed</div>');
                });
        }        function testAddressSearch() {
            const resultsDiv = $('#apiTestResults');
            resultsDiv.html('<div class="text-blue-600">Testing address search...</div>');

            $.get('/public/search-alamat', { search: 'jakarta' })
                .done(function(response) {
                    resultsDiv.html(`
                        <div class="text-green-600 font-bold">✓ Address Search Test</div>
                        <div class="text-sm mt-2">Found: ${response.data ? response.data.length : 0} results</div>
                        <pre class="text-xs mt-2 text-gray-600">${JSON.stringify(response.data?.slice(0, 2), null, 2)}</pre>
                    `);
                })
                .fail(function() {
                    resultsDiv.html('<div class="text-red-600">✗ Address Search Failed</div>');
                });
        }

        function testDomesticCost() {
            const resultsDiv = $('#apiTestResults');
            resultsDiv.html('<div class="text-blue-600">Testing domestic cost calculation...</div>');

            $.get('/test-domestic-cost')
                .done(function(response) {
                    resultsDiv.html(`
                        <div class="text-green-600 font-bold">✓ Domestic Cost Test</div>
                        <div class="text-sm mt-2">Status: ${response.status || 'Success'}</div>
                        <pre class="text-xs mt-2 text-gray-600">${JSON.stringify(response, null, 2)}</pre>
                    `);
                })
                .fail(function() {
                    resultsDiv.html('<div class="text-red-600">✗ Domestic Cost Test Failed</div>');
                });
        }

        // Auto-fill popular cities for testing
        $(document).ready(function() {
            // Add quick test buttons
            const quickTests = `
                <div class="mt-4 p-4 bg-gray-100 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Quick Test Routes:</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <button onclick="quickFill('Jakarta', 'Surabaya')" class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Jakarta → Surabaya</button>
                        <button onclick="quickFill('Bandung', 'Medan')" class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Bandung → Medan</button>
                        <button onclick="quickFill('Yogyakarta', 'Balikpapan')" class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Yogyakarta → Balikpapan</button>
                        <button onclick="quickFill('Semarang', 'Makassar')" class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">Semarang → Makassar</button>
                    </div>
                </div>
            `;
            $('#shippingForm').after(quickTests);
        });

        function quickFill(origin, destination) {
            $('#originSearch').val(origin).trigger('input');
            $('#destinationSearch').val(destination).trigger('input');

            // Simulate API search and selection
            setTimeout(() => {
                // This would normally be handled by the search results, but for testing
                // we'll just set some sample IDs (you may need to adjust these)
                if (origin === 'Jakarta') $('#origin').val('151'); // Jakarta Pusat
                if (origin === 'Bandung') $('#origin').val('23');  // Bandung
                if (origin === 'Yogyakarta') $('#origin').val('419'); // Yogyakarta
                if (origin === 'Semarang') $('#origin').val('392'); // Semarang

                if (destination === 'Surabaya') $('#destination').val('444'); // Surabaya
                if (destination === 'Medan') $('#destination').val('9'); // Medan
                if (destination === 'Balikpapan') $('#destination').val('46'); // Balikpapan
                if (destination === 'Makassar') $('#destination').val('170'); // Makassar

                $('#selectedOrigin').text(origin);
                $('#selectedDestination').text(destination);
            }, 500);
        }
    </script>
</body>
</html>
