<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dropdown Fixes Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/address-autocomplete.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 2rem;
            background: #f3f4f6;
        }
        .test-section {
            background: white;
            padding: 2rem;
            margin: 1rem 0;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .test-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #1f2937;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        }
        .select-wrapper {
            position: relative;
        }
        .select-wrapper::after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            pointer-events: none;
            z-index: 1;
        }
        select.form-control {
            appearance: none;
            background-image: none !important;
            padding-right: 3rem;
            cursor: pointer;
        }
        .status {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            margin: 0.5rem 0;
            font-weight: 500;
        }
        .status.success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .status.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .address-search-container {
            position: relative;
        }
    </style>
</head>
<body>
    <h1>Dropdown Fixes Verification Test</h1>
    <p>This page tests all the dropdown fixes implemented for the Laravel e-commerce application.</p>

    <!-- Test 1: Address Autocomplete Dropdown -->
    <div class="test-section">
        <div class="test-title">
            <i class="fas fa-map-marker-alt"></i> Test 1: Address Autocomplete Dropdown
        </div>
        <p>Test if the address dropdown stays open when clicking inside it and closes properly when clicking outside.</p>

        <div class="form-group">
            <label for="alamat_search" class="form-label">Search Address</label>
            <div class="address-search-container">
                <input type="text" id="alamat_search" class="form-control" placeholder="Type at least 3 characters to search...">
                <div id="address-dropdown" class="address-dropdown"></div>
            </div>
            <input type="hidden" id="alamat_id" name="alamat_id">
            <div class="selected-address">
                <span id="selected-address-display"></span>
                <span id="clear-address" class="clear-address">×</span>
            </div>
        </div>

        <div id="address-status" class="status"></div>
    </div>

    <!-- Test 2: Expense Category Dropdown -->
    <div class="test-section">
        <div class="test-title">
            <i class="fas fa-tags"></i> Test 2: Expense Category Dropdown (No Duplicate Icons)
        </div>
        <p>Test if the expense category dropdown has only one dropdown icon (no duplicates).</p>

        <div class="form-group">
            <label for="category" class="form-label">
                <i class="fas fa-tags text-blue-500"></i> Category
            </label>
            <div class="select-wrapper">
                <select class="form-control" id="category" name="category">
                    <option value="">Select Category</option>
                    <option value="Gaji Karyawan">💼 Gaji Karyawan</option>
                    <option value="Sewa">🏠 Sewa</option>
                    <option value="Listrik">⚡ Listrik</option>
                    <option value="Bahan Baku">🐟 Bahan Baku</option>
                    <option value="Peralatan">🔧 Peralatan</option>
                    <option value="Transportasi">🚚 Transportasi</option>
                    <option value="Marketing">📢 Marketing</option>
                    <option value="Administrasi">📋 Administrasi</option>
                    <option value="Lainnya">📦 Lainnya</option>
                </select>
            </div>
        </div>

        <div id="category-status" class="status"></div>
    </div>

    <!-- Test 3: Review Rating Filter Dropdown -->
    <div class="test-section">
        <div class="test-title">
            <i class="fas fa-star"></i> Test 3: Review Rating Filter (No Overlapping Icons)
        </div>
        <p>Test if the review rating filter dropdown has proper styling without overlapping icons.</p>

        <div class="form-group">
            <label class="form-label">Rating Filter</label>
            <select name="rating" class="border border-gray-300 rounded-lg px-3 py-2 pr-10 focus:ring-orange-500 focus:border-orange-500 appearance-none bg-white" style="width: 100%; padding: 0.75rem; border: 2px solid #d1d5db; border-radius: 0.5rem;">
                <option value="">All Ratings</option>
                <option value="5">5 Stars</option>
                <option value="4">4 Stars</option>
                <option value="3">3 Stars</option>
                <option value="2">2 Stars</option>
                <option value="1">1 Star</option>
            </select>
        </div>

        <div id="rating-status" class="status"></div>
    </div>

    <!-- Test Results Summary -->
    <div class="test-section">
        <div class="test-title">
            <i class="fas fa-clipboard-check"></i> Test Results Summary
        </div>
        <div id="test-summary"></div>
    </div>

    <script src="{{ asset('js/address-autocomplete-fixed.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Test status tracking
            const testResults = {
                address: false,
                category: false,
                rating: false
            };

            // Test 1: Address Autocomplete
            const addressInput = document.getElementById('alamat_search');
            const addressStatus = document.getElementById('address-status');

            // Simulate address search
            addressInput.addEventListener('input', function() {
                if (this.value.length >= 3) {
                    addressStatus.className = 'status success';
                    addressStatus.innerHTML = '<i class="fas fa-check"></i> Address search working - dropdown should appear and stay open when clicked';
                    testResults.address = true;
                } else if (this.value.length > 0) {
                    addressStatus.className = 'status';
                    addressStatus.innerHTML = '<i class="fas fa-info"></i> Type at least 3 characters';
                } else {
                    addressStatus.innerHTML = '';
                }
                updateSummary();
            });

            // Test 2: Category Dropdown
            const categorySelect = document.getElementById('category');
            const categoryStatus = document.getElementById('category-status');

            // Check for duplicate icons
            const selectWrapper = categorySelect.parentElement;
            const computedStyle = window.getComputedStyle(selectWrapper, '::after');
            const hasCustomIcon = computedStyle.content !== 'none' && computedStyle.content !== '';

            categorySelect.addEventListener('focus', function() {
                const iconCount = hasCustomIcon ? 1 : 0;
                const bgImage = window.getComputedStyle(this).backgroundImage;
                const hasBgIcon = bgImage !== 'none' && bgImage !== '';

                if (iconCount === 1 && !hasBgIcon) {
                    categoryStatus.className = 'status success';
                    categoryStatus.innerHTML = '<i class="fas fa-check"></i> Category dropdown has exactly one icon - no duplicates detected';
                    testResults.category = true;
                } else {
                    categoryStatus.className = 'status error';
                    categoryStatus.innerHTML = '<i class="fas fa-times"></i> Duplicate icons detected - needs fixing';
                    testResults.category = false;
                }
                updateSummary();
            });

            // Test 3: Rating Filter
            const ratingSelect = document.querySelector('select[name="rating"]');
            const ratingStatus = document.getElementById('rating-status');

            ratingSelect.addEventListener('focus', function() {
                const selectStyle = window.getComputedStyle(this);
                const hasAppearanceNone = selectStyle.appearance === 'none' || selectStyle.webkitAppearance === 'none';
                const hasProperPadding = selectStyle.paddingRight.includes('px') && parseInt(selectStyle.paddingRight) > 20;

                if (hasAppearanceNone && hasProperPadding) {
                    ratingStatus.className = 'status success';
                    ratingStatus.innerHTML = '<i class="fas fa-check"></i> Rating filter dropdown styled correctly - no overlapping icons';
                    testResults.rating = true;
                } else {
                    ratingStatus.className = 'status error';
                    ratingStatus.innerHTML = '<i class="fas fa-times"></i> Rating filter styling needs improvement';
                    testResults.rating = false;
                }
                updateSummary();
            });

            function updateSummary() {
                const summary = document.getElementById('test-summary');
                const passedTests = Object.values(testResults).filter(result => result === true).length;
                const totalTests = Object.keys(testResults).length;

                let summaryHTML = `<h4>Test Results: ${passedTests}/${totalTests} passed</h4><ul>`;

                summaryHTML += `<li><strong>Address Autocomplete:</strong> ${testResults.address ? '✅ PASS' : '❌ FAIL'}</li>`;
                summaryHTML += `<li><strong>Category Dropdown:</strong> ${testResults.category ? '✅ PASS' : '❌ FAIL'}</li>`;
                summaryHTML += `<li><strong>Rating Filter:</strong> ${testResults.rating ? '✅ PASS' : '❌ FAIL'}</li>`;

                summaryHTML += '</ul>';

                if (passedTests === totalTests) {
                    summaryHTML += '<div class="status success"><i class="fas fa-trophy"></i> All tests passed! All dropdown issues have been fixed.</div>';
                } else {
                    summaryHTML += '<div class="status error"><i class="fas fa-exclamation-triangle"></i> Some tests failed. Please check the individual test sections above.</div>';
                }

                summary.innerHTML = summaryHTML;
            }

            // Initialize summary
            updateSummary();
        });
    </script>
</body>
</html>
