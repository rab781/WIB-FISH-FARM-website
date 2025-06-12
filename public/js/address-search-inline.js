document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('alamat_search');
    const selectElement = document.getElementById('alamat_id');

    if (!searchInput || !selectElement) {
        console.error('Cannot initialize alamat search: invalid input or select elements');
        return;
    }

    let searchTimeout;
    console.log('Enhanced address search initialized for tambah_alamat page');

    // Debug function to help diagnose issues
    function logDebug(message, data = null) {
        const now = new Date();
        const timestamp = `${now.getHours()}:${now.getMinutes()}:${now.getSeconds()}.${now.getMilliseconds()}`;
        console.log(`[${timestamp}] 🔍 ${message}`);
        if (data !== null) {
            console.log(data);
        }
    }

    // Log initialization
    logDebug('Address search component ready');

    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(function() {
            const searchTerm = searchInput.value.trim();

            if (searchTerm.length < 3) {
                // If term is less than 3 chars but not empty, show helpful message
                if (searchTerm.length > 0) {
                    // Clear options except for the placeholder
                    selectElement.innerHTML = '<option value="">Pilih alamat dari hasil pencarian</option>';

                    // Add helpful message
                    const helpOption = new Option('Masukkan minimal 3 karakter untuk mencari', '');
                    helpOption.disabled = true;
                    selectElement.add(helpOption);
                }
                return;
            }

            // Keep the current selected option
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const currentValue = selectedOption ? selectedOption.value : '';

            // Clear options except for the placeholder
            selectElement.innerHTML = '<option value="">Pilih alamat dari hasil pencarian</option>';

            // Add back the selected option if it exists
            if (currentValue && selectedOption) {
                selectElement.add(selectedOption);
            }

            // Add loading option
            const loadingOption = new Option('Loading...', '');
            selectElement.add(loadingOption);            // Add a timestamp to prevent caching
            const timestamp = new Date().getTime();

            // Log the search attempt
            logDebug(`Searching for "${searchTerm}" via API`);

            // First try using the standard API endpoint with cache-busting parameter
            tryFetchAddress(`/api/alamat/search?term=${encodeURIComponent(searchTerm)}&_=${timestamp}`)
                .then(data => {
                    logDebug(`API search results for "${searchTerm}"`, data);
                    // Process the response data
                    processAddressData(data, selectElement, currentValue);
                })
                .catch(error => {
                    logDebug(`Primary API endpoint failed for "${searchTerm}"`, error);                    // If the first API call fails, try a fallback
                    logDebug('Primary API failed, trying fallback endpoint...');
                    return tryFetchAddress(`/test/api/response?term=${encodeURIComponent(searchTerm)}&_=${timestamp+1}`)
                        .then(debugData => {
                            logDebug('Fallback API response:', debugData);

                                // Try to extract data from debug response
                                if (debugData.parsedData && debugData.parsedData.success &&
                                    debugData.parsedData.data && debugData.parsedData.data.length > 0) {
                                    return debugData.parsedData;
                                } else {
                                    throw new Error('No valid data in fallback response');
                                }
                            })
                            .then(data => {
                                processAddressData(data, selectElement, currentValue);
                            })
                            .catch(fallbackError => {
                                logDebug('Fallback API also failed:', fallbackError);

                                // Both endpoints failed, show error message
                                // Remove loading option
                                selectElement.remove(selectElement.options.length - 1);

                                // Show detailed error message
                                const errorOption = new Option(`Error: ${error.message || 'Gagal memuat data'}`, '');
                                errorOption.disabled = true;
                                selectElement.add(errorOption);

                                // Add helpful suggestion with more details
                                const helpOption = new Option('Coba refresh halaman atau periksa koneksi internet', '');
                                helpOption.disabled = true;
                                selectElement.add(helpOption);

                                // Add link to debug page
                                const debugOption = new Option('Lihat halaman debug untuk bantuan', '');
                                debugOption.disabled = true;
                                selectElement.add(debugOption);

                                // Add error troubleshooting alert
                                alert('Terjadi kesalahan saat mencari alamat. Silakan coba lagi atau hubungi support.');
                            });
                    });
            }, 500);
    });    // Helper function to fetch address data with timeout and error handling
    function tryFetchAddress(url) {
        return new Promise((resolve, reject) => {
            const timeoutId = setTimeout(() => {
                reject(new Error('Request timed out after 10 seconds'));
            }, 10000);

            // Add CSRF token and Ajax headers for Laravel
            const headers = {
                'X-Requested-With': 'XMLHttpRequest'
            };

            // Add CSRF token if available
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                headers['X-CSRF-TOKEN'] = token.getAttribute('content');
            }
             logDebug(`Fetching from URL: ${url}`);

            fetch(url, { headers })
                .then(response => {
                    clearTimeout(timeoutId);
                    logDebug(`API response status: ${response.status} from ${url}`);
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                    })
                    .then(resolve)
                    .catch(reject);
            });
    }    // Helper function to process address data
    function processAddressData(data, selectElement, currentValue) {
        logDebug('Processing API response', data);

        // Remove loading option
        selectElement.remove(selectElement.options.length - 1);

        // Add options from API response
        if (data.success && data.data && data.data.length > 0) {
            logDebug(`Processing ${data.data.length} address items`);
            data.data.forEach(item => {
                logDebug('Processing address item', item);                let formattedAddress;

                // Format address differently based on available data
                if (item.subdistrict && item.subdistrict.trim() !== '') {
                    formattedAddress = `${item.subdistrict}, ${item.city}, ${item.province} ${item.postal_code || ''}`;
                } else {
                    formattedAddress = `${item.city || ''}, ${item.province || ''} ${item.postal_code || ''}`;
                }

                logDebug('Formatted address:', formattedAddress);
                    const option = new Option(formattedAddress, item.id);

                    // Don't add duplicates
                    if (item.id != currentValue) {
                        selectElement.add(option);
                    }
                });
            } else {
                // No results found - show user-friendly message
                const noResultOption = new Option('Kota tidak tersedia, harap periksa kembali input Anda', '');
                noResultOption.disabled = true;
                selectElement.add(noResultOption);

                // Add suggestion option
                const suggestionOption = new Option('Coba kata kunci lain seperti: Jakarta, Bandung, Surabaya', '');
                suggestionOption.disabled = true;
                selectElement.add(suggestionOption);
            }
        }
});
