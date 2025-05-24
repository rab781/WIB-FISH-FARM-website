document.addEventListener('DOMContentLoaded', function() {
    // The input field where user will type
    const addressInput = document.getElementById('alamat_search');

    // Hidden input to store the selected address ID for form submission
    const addressIdInput = document.getElementById('alamat_id');

    // Elements for UI
    const dropdownContainer = document.getElementById('address-dropdown');
    const selectedAddressDisplay = document.getElementById('selected-address-display');

    // Variables for controlling search behavior
    let searchTimeout;
    let isDropdownOpen = false;
    let currentItems = [];

    // Jika ada alamat yang sudah dipilih sebelumnya, tampilkan pada input
    if (selectedAddressDisplay && selectedAddressDisplay.textContent.trim() !== '' && addressIdInput.value) {
        addressInput.value = selectedAddressDisplay.textContent.trim();
    }

    // Debug logging function
    function logDebug(message, data = null) {
        const now = new Date();
        const timestamp = `${now.getHours()}:${now.getMinutes()}:${now.getSeconds()}.${now.getMilliseconds()}`;
        console.log(`[${timestamp}] 🔍 ${message}`);
        if (data !== null) {
            console.log(data);
        }
    }

    // Initialize
    logDebug('Address autocomplete initialized');

    // Function to show the dropdown
    function showDropdown() {
        dropdownContainer.style.display = 'block';
        isDropdownOpen = true;
    }

    // Function to hide the dropdown
    function hideDropdown() {
        dropdownContainer.style.display = 'none';
        isDropdownOpen = false;
    }

    // Function to select an address
    function selectAddress(id, text) {
        addressIdInput.value = id;
        addressInput.value = text;

        // Display the selected address
        if (selectedAddressDisplay) {
            selectedAddressDisplay.textContent = text;
            selectedAddressDisplay.parentElement.style.display = 'block';
        }

        hideDropdown();
    }
      // Search throttling variables
    let lastSearchTerm = '';
    let lastSearchTime = 0;
    const minSearchInterval = 800; // Minimal interval between searches in ms

    // Handle search input
    addressInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);

        // Clear selected address if input changes
        if (addressIdInput.value && addressInput.value === '') {
            addressIdInput.value = '';
            if (selectedAddressDisplay) {
                selectedAddressDisplay.parentElement.style.display = 'none';
            }
        }

        // Set a timeout to prevent too many API requests
        searchTimeout = setTimeout(function() {
            const searchTerm = addressInput.value.trim();

            // Don't search again for the same term
            if (searchTerm === lastSearchTerm && searchTerm.length >= 3) {
                return;
            }

            // Clear dropdown
            dropdownContainer.innerHTML = '';

            if (searchTerm.length < 3) {
                if (searchTerm.length > 0) {
                    // Show minimal character message
                    const helpItem = document.createElement('div');
                    helpItem.className = 'address-item address-item-message';
                    helpItem.textContent = 'Masukkan minimal 3 karakter untuk mencari';
                    dropdownContainer.appendChild(helpItem);
                    showDropdown();
                } else {
                    hideDropdown();
                }
                return;
            }

            // Check time since last search to prevent too many requests
            const now = Date.now();
            const timeSinceLastSearch = now - lastSearchTime;

            if (timeSinceLastSearch < minSearchInterval) {
                // If searching too quickly, add a delay item
                const waitItem = document.createElement('div');
                waitItem.className = 'address-item address-item-message';
                waitItem.textContent = 'Tunggu sebentar...';
                dropdownContainer.appendChild(waitItem);
                showDropdown();

                // Wait for the remaining time before actually searching
                const additionalDelay = minSearchInterval - timeSinceLastSearch;
                setTimeout(() => performSearch(searchTerm), additionalDelay);
                return;
            }

            // Perform the search immediately if enough time has passed
            performSearch(searchTerm);
        }, 300); // 300ms delay while typing
    });

    // Function to perform the actual search
    function performSearch(searchTerm) {
        // Update tracking variables
        lastSearchTerm = searchTerm;
        lastSearchTime = Date.now();

        // Clear and show loading
        dropdownContainer.innerHTML = '';
        const loadingItem = document.createElement('div');
        loadingItem.className = 'address-item address-item-message';
        loadingItem.textContent = 'Mencari...';
        dropdownContainer.appendChild(loadingItem);
        showDropdown();

        // Add a timestamp to prevent caching
        const timestamp = new Date().getTime();
        logDebug(`Searching for "${searchTerm}" via API`);

            // Fetch address data
            tryFetchAddress(`/api/alamat/search?term=${encodeURIComponent(searchTerm)}&_=${timestamp}`)
                .then(data => {
                    logDebug(`API search results for "${searchTerm}"`, data);
                    processAddressResults(data);
                })
                .catch(error => {
                    logDebug(`API search failed: ${error.message}`);
                    showErrorInDropdown(error.message);
                });
    }

      // Function to fetch address data with retry mechanism
    function tryFetchAddress(url, retryCount = 0, maxRetries = 2, delayMs = 1000) {
        return new Promise((resolve, reject) => {
            const timeoutId = setTimeout(() => {
                reject(new Error('Request timed out after 10 seconds'));
            }, 10000);

            // Add headers for request
            const headers = {
                'X-Requested-With': 'XMLHttpRequest'
            };

            // Add CSRF token if available
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                headers['X-CSRF-TOKEN'] = token.getAttribute('content');
            }

            fetch(url, { headers })
                .then(response => {
                    clearTimeout(timeoutId);

                    // Handle specific HTTP errors
                    if (response.status === 429) {
                        // Too Many Requests - implement retry with exponential backoff
                        const retryDelay = delayMs * Math.pow(2, retryCount);
                        logDebug(`Rate limit hit (429). Retrying in ${retryDelay}ms... (Attempt ${retryCount + 1}/${maxRetries + 1})`);

                        if (retryCount < maxRetries) {
                            return new Promise(resolve => setTimeout(resolve, retryDelay))
                                .then(() => tryFetchAddress(url, retryCount + 1, maxRetries, delayMs));
                        } else {
                            throw new Error('Terlalu banyak permintaan. Coba lagi dalam beberapa saat.');
                        }
                    } else if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    return response.json();
                })
                .then(resolve)
                .catch(error => {
                    clearTimeout(timeoutId);
                    reject(error);
                });
        });
    }

    // Process the address results and display in dropdown
    function processAddressResults(data) {
        // Clear dropdown
        dropdownContainer.innerHTML = '';

        // Process results
        if (data.success && data.data && data.data.length > 0) {
            logDebug(`Processing ${data.data.length} address items`);
            currentItems = data.data;

            data.data.forEach((item, index) => {
                // Format the address text
                let formattedAddress;
                if (item.subdistrict && item.subdistrict.trim() !== '') {
                    formattedAddress = `${item.subdistrict}, ${item.city}, ${item.province} ${item.postal_code || ''}`;
                } else {
                    formattedAddress = `${item.city || ''}, ${item.province || ''} ${item.postal_code || ''}`;
                }

                // Create dropdown item
                const addressItem = document.createElement('div');
                addressItem.className = 'address-item';
                addressItem.textContent = formattedAddress;
                addressItem.dataset.id = item.id;
                addressItem.dataset.index = index;

                // Add click event to select this item
                addressItem.addEventListener('click', function() {
                    selectAddress(item.id, formattedAddress);
                });

                dropdownContainer.appendChild(addressItem);
            });

            showDropdown();
        } else {
            // No results
            const noResultItem = document.createElement('div');
            noResultItem.className = 'address-item address-item-message';
            noResultItem.textContent = 'Tidak ada hasil ditemukan';
            dropdownContainer.appendChild(noResultItem);
            showDropdown();
        }
    }
      // Show error message in dropdown
    function showErrorInDropdown(message) {
        // Clear dropdown
        dropdownContainer.innerHTML = '';

        // Check if it's a rate limiting error
        const isRateLimitError = message.includes('429') ||
                                 message.includes('Too Many Requests') ||
                                 message.includes('Terlalu banyak permintaan');

        // Show error message
        const errorItem = document.createElement('div');
        errorItem.className = 'address-item address-item-error';

        if (isRateLimitError) {
            errorItem.textContent = 'Terlalu banyak pencarian dalam waktu singkat';
        } else {
            errorItem.textContent = `Error: ${message}`;
        }

        dropdownContainer.appendChild(errorItem);

        // Add retry suggestion
        const retryItem = document.createElement('div');
        retryItem.className = 'address-item address-item-message';

        if (isRateLimitError) {
            retryItem.textContent = 'Tunggu sebentar dan coba lagi...';

            // Add auto-retry after a few seconds for rate limit errors
            setTimeout(() => {
                if (addressInput.value.trim().length >= 3) {
                    // Trigger input event to restart search
                    addressInput.dispatchEvent(new Event('input'));
                }
            }, 3000);
        } else {
            retryItem.textContent = 'Coba refresh halaman atau periksa koneksi internet';
        }

        dropdownContainer.appendChild(retryItem);

        showDropdown();
    }

    // Keyboard navigation
    addressInput.addEventListener('keydown', function(e) {
        if (!isDropdownOpen) return;

        const items = dropdownContainer.querySelectorAll('.address-item:not(.address-item-message):not(.address-item-error)');
        if (items.length === 0) return;

        const activeItem = dropdownContainer.querySelector('.address-item-active');
        let activeIndex = -1;

        if (activeItem) {
            activeIndex = parseInt(activeItem.dataset.index);
        }

        // Arrow down
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (activeIndex < items.length - 1) {
                activeIndex++;
            } else {
                activeIndex = 0;
            }
            highlightItem(items, activeIndex);
        }
        // Arrow up
        else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (activeIndex > 0) {
                activeIndex--;
            } else {
                activeIndex = items.length - 1;
            }
            highlightItem(items, activeIndex);
        }
        // Enter key
        else if (e.key === 'Enter') {
            e.preventDefault();
            if (activeItem) {
                const item = currentItems[activeIndex];
                let formattedAddress;
                if (item.subdistrict && item.subdistrict.trim() !== '') {
                    formattedAddress = `${item.subdistrict}, ${item.city}, ${item.province} ${item.postal_code || ''}`;
                } else {
                    formattedAddress = `${item.city || ''}, ${item.province || ''} ${item.postal_code || ''}`;
                }
                selectAddress(item.id, formattedAddress);
            }
        }
    });

    // Highlight an item in the dropdown
    function highlightItem(items, index) {
        // Remove active class from all items
        items.forEach(item => {
            item.classList.remove('address-item-active');
        });

        // Add active class to selected item
        if (items[index]) {
            items[index].classList.add('address-item-active');
            items[index].scrollIntoView({ block: 'nearest' });
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (isDropdownOpen && !dropdownContainer.contains(e.target) && e.target !== addressInput) {
            hideDropdown();
        }
    });

    // Clear button functionality
    const clearBtn = document.getElementById('clear-address');
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            addressInput.value = '';
            addressIdInput.value = '';
            if (selectedAddressDisplay) {
                selectedAddressDisplay.parentElement.style.display = 'none';
            }
            hideDropdown();
        });
    }
});
