/**
 * Alamat Search Utility - Enhanced Version
 *
 * Provides address search functionality for RajaOngkir integration
 * With fallback mechanisms and enhanced error handling
 */
function initializeAlamatSearch(searchInputId, selectElementId) {
    const searchInput = document.getElementById(searchInputId);
    const selectElement = document.getElementById(selectElementId);

    if (!searchInput || !selectElement) {
        console.error('Cannot initialize alamat search: invalid input or select elements');
        return;
    }

    let searchTimeout;
    console.log('Enhanced address search initialized for', searchInputId, selectElementId);

    searchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(function() {
            const searchTerm = searchInput.value;

            if (searchTerm.length < 3) return;

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
            selectElement.add(loadingOption);

            // First try using the standard API endpoint
            tryFetchAddress(`/api/alamat/search?term=${encodeURIComponent(searchTerm)}`)
                .then(data => {
                    // Process the response data
                    processAddressData(data, selectElement, currentValue);
                })
                .catch(error => {
                    console.error('Primary API endpoint failed:', error);

                    // If the first API call fails, try a fallback
                    console.log('Trying fallback API endpoint...');
                    return tryFetchAddress(`/test/api/response?term=${encodeURIComponent(searchTerm)}`)
                        .then(debugData => {
                            console.log('Debug API response:', debugData);

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
                            console.error('Fallback API also failed:', fallbackError);

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
                        });
                });
        }, 500);
    });

    // Helper function to fetch address data with timeout and error handling
    function tryFetchAddress(url) {
        return new Promise((resolve, reject) => {
            const timeoutId = setTimeout(() => {
                reject(new Error('Request timed out after 10 seconds'));
            }, 10000);

            fetch(url)
                .then(response => {
                    clearTimeout(timeoutId);
                    console.log(`API response from ${url}:`, response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(resolve)
                .catch(reject);
        });
    }

    // Helper function to process address data
    function processAddressData(data, selectElement, currentValue) {
        console.log('Processing API response:', data);

        // Remove loading option
        selectElement.remove(selectElement.options.length - 1);

        // Add options from API response
        if (data.success && data.data && data.data.length > 0) {
            console.log('Processing options from API data:', data.data.length, 'items');
            data.data.forEach(item => {
                console.log('Processing item:', item);
                let formattedAddress;

                // Format address differently based on available data
                if (item.subdistrict && item.subdistrict.trim() !== '') {
                    formattedAddress = `${item.subdistrict}, ${item.city}, ${item.province} ${item.postal_code || ''}`;
                } else {
                    formattedAddress = `${item.city || ''}, ${item.province || ''} ${item.postal_code || ''}`;
                }

                console.log('Formatted address:', formattedAddress);
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
}
