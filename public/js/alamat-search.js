/**
 * Alamat Search Utility
 *
 * Provides address search functionality for RajaOngkir integration
 * With fallback mechanisms and improved error handling
 */
function initializeAlamatSearch(searchInputId, selectElementId) {
    const searchInput = document.getElementById(searchInputId);
    const selectElement = document.getElementById(selectElementId);

    if (!searchInput || !selectElement) {
        console.error('Cannot initialize alamat search: invalid input or select elements');
        return;
    }

    let searchTimeout;
    console.log('Address search initialized for', searchInputId, selectElementId);

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
              // Make API request
            console.log('Fetching from API:', `/api/alamat/search?term=${encodeURIComponent(searchTerm)}`);
            fetch(`/api/alamat/search?term=${encodeURIComponent(searchTerm)}`)
                .then(response => {
                    console.log('API response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('API response data:', data);

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
                        // No results found
                        const noResultOption = new Option('Tidak ada hasil ditemukan', '');
                        noResultOption.disabled = true;
                        selectElement.add(noResultOption);
                    }
                })                .catch(error => {
                    console.error('Error fetching alamat:', error);
                    console.error('Error details:', error.message);

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

                    // Add option to check console
                    const consoleOption = new Option('Lihat console browser (F12) untuk detail error', '');
                    consoleOption.disabled = true;
                    selectElement.add(consoleOption);
                });
        }, 500);
    });
}
