@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Address Error Fallback Test</h1>
            <p class="text-gray-600">Test the new fallback error messages for invalid city inputs</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Test Cases</h2>

            <div class="space-y-4">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-medium text-blue-900 mb-2">Test 1: Random Characters</h3>
                    <p class="text-sm text-blue-700 mb-3">Type: <code>aksjdhkas</code> - Should show "Kota tidak tersedia"</p>

                    <div class="address-search-container">
                        <label for="test1_search" class="block text-sm font-medium text-gray-700 mb-1">Search Address</label>
                        <input type="text" id="test1_search"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Type 'aksjdhkas' to test error message">
                        <div id="test1_dropdown" class="address-dropdown"></div>
                    </div>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="font-medium text-green-900 mb-2">Test 2: Misspelled City</h3>
                    <p class="text-sm text-green-700 mb-3">Type: <code>jakrta</code> - Should show "Alamat tidak ditemukan"</p>

                    <div class="address-search-container">
                        <label for="test2_search" class="block text-sm font-medium text-gray-700 mb-1">Search Address</label>
                        <input type="text" id="test2_search"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Type 'jakrta' to test error message">
                        <div id="test2_dropdown" class="address-dropdown"></div>
                    </div>
                </div>

                <div class="bg-purple-50 p-4 rounded-lg">
                    <h3 class="font-medium text-purple-900 mb-2">Test 3: Valid City</h3>
                    <p class="text-sm text-purple-700 mb-3">Type: <code>jakarta</code> - Should show normal results</p>

                    <div class="address-search-container">
                        <label for="test3_search" class="block text-sm font-medium text-gray-700 mb-1">Search Address</label>
                        <input type="text" id="test3_search"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Type 'jakarta' to test normal functionality">
                        <div id="test3_dropdown" class="address-dropdown"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Expected Results</h2>
            <ul class="space-y-2 text-sm text-gray-700">
                <li>• <strong>Random characters (aksjdhkas):</strong> Red error message "Kota tidak tersedia, harap periksa kembali input Anda"</li>
                <li>• <strong>Misspelled cities (jakrta):</strong> Red error message "Alamat tidak ditemukan, periksa ejaan atau coba kata kunci lain"</li>
                <li>• <strong>Valid cities (jakarta):</strong> Normal dropdown with address results</li>
                <li>• <strong>All cases:</strong> Blue suggestion message with example cities</li>
            </ul>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/address-autocomplete.css') }}">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all three test cases
    initializeTestCase('test1');
    initializeTestCase('test2');
    initializeTestCase('test3');

    function initializeTestCase(testId) {
        const searchInput = document.getElementById(testId + '_search');
        const dropdown = document.getElementById(testId + '_dropdown');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(() => {
                const searchTerm = this.value.trim();

                if (searchTerm.length < 3) {
                    dropdown.style.display = 'none';
                    return;
                }

                // Clear dropdown
                dropdown.innerHTML = '';

                // Show loading
                const loadingItem = document.createElement('div');
                loadingItem.className = 'address-item address-item-message';
                loadingItem.textContent = 'Mencari...';
                dropdown.appendChild(loadingItem);
                dropdown.style.display = 'block';

                // Simulate API call
                setTimeout(() => {
                    dropdown.innerHTML = '';

                    // Simulate different responses based on input
                    if (searchTerm.toLowerCase() === 'jakarta') {
                        // Simulate success
                        const resultItem = document.createElement('div');
                        resultItem.className = 'address-item';
                        resultItem.textContent = 'Jakarta Pusat, DKI Jakarta 10110';
                        dropdown.appendChild(resultItem);

                        const resultItem2 = document.createElement('div');
                        resultItem2.className = 'address-item';
                        resultItem2.textContent = 'Jakarta Selatan, DKI Jakarta 12110';
                        dropdown.appendChild(resultItem2);
                    } else {
                        // Simulate no results - trigger our new error handling
                        const searchTermLower = searchTerm.toLowerCase();
                        const isRandomChars = /^[a-z]{6,}$/.test(searchTermLower) && !/^(jakarta|bandung|surabaya|yogya|medan|makassar|palembang|semarang|tangerang|depok|bekasi|bogor)/i.test(searchTermLower);

                        const errorItem = document.createElement('div');
                        errorItem.className = 'address-item address-item-error';

                        if (isRandomChars) {
                            errorItem.textContent = 'Kota tidak tersedia, harap periksa kembali input Anda';
                        } else {
                            errorItem.textContent = 'Alamat tidak ditemukan, periksa ejaan atau coba kata kunci lain';
                        }

                        dropdown.appendChild(errorItem);

                        const suggestionItem = document.createElement('div');
                        suggestionItem.className = 'address-item address-item-message';
                        suggestionItem.textContent = 'Coba kata kunci seperti: Jakarta, Bandung, Surabaya, Yogyakarta';
                        dropdown.appendChild(suggestionItem);
                    }

                    dropdown.style.display = 'block';
                }, 1000);

            }, 300);
        });
    }
});
</script>
@endpush
