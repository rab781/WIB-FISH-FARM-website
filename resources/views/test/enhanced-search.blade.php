@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900">Enhanced Address Search Test</h1>
        <p class="mt-4 text-xl text-gray-600">This page tests the improved address search component</p>
    </div>

    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Test Form</h2>
        </div>
        <div class="p-6 space-y-6">
            <div class="space-y-4">
                <!-- Cari Alamat (RajaOngkir) -->
                <div>
                    <label for="alamat_search" class="block text-sm font-medium text-gray-700 mb-1">Cari Alamat</label>
                    <input type="text" id="alamat_search" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500" placeholder="Masukkan nama kota/kabupaten" value="jakarta">
                    <p class="mt-1 text-xs text-gray-500">Ketik untuk mencari alamat dari RajaOngkir (coba "jakarta", "bandung", dll)</p>
                </div>

                <!-- Hasil Pencarian -->
                <div>
                    <label for="alamat_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Alamat</label>
                    <select id="alamat_id" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 focus:border-blue-500">
                        <option value="">Pilih alamat dari hasil pencarian</option>
                    </select>
                </div>
            </div>

            <!-- API Status -->
            <div class="mt-8 bg-gray-50 rounded-lg p-4">
                <h3 class="font-medium text-gray-700 mb-2">Status:</h3>
                <div id="status" class="text-sm">Siap mengirim permintaan API...</div>
            </div>
            
            <!-- Console Output -->
            <div class="mt-6">
                <h3 class="font-medium text-gray-700 mb-2">Console Output:</h3>
                <div id="console_output" class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm h-48 overflow-auto">
                    <!-- Console output will be displayed here -->
                </div>
            </div>
            
            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-4">
                <button id="test_standard" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Test Standard API
                </button>
                <button id="test_fallback" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    Test Fallback API
                </button>
                <button id="test_combined" class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600">
                    Test Combined Method
                </button>
            </div>
        </div>
    </div>
    
    <div class="mt-8 text-center">
        <a href="{{ route('test.debug') }}" class="text-blue-600 hover:underline">
            Go to Debug Console
        </a>
        <span class="mx-2">|</span>
        <a href="{{ url('/admin/debug/api-status') }}" class="text-blue-600 hover:underline">
            View API Status
        </a>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/alamat-search-enhanced.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Capture console.log output
        const originalConsoleLog = console.log;
        const originalConsoleError = console.error;
        const consoleOutput = document.getElementById('console_output');
        const statusDiv = document.getElementById('status');
        
        console.log = function() {
            const args = Array.from(arguments);
            originalConsoleLog.apply(console, args);
            
            const message = args.map(arg => {
                return typeof arg === 'object' ? JSON.stringify(arg) : arg;
            }).join(' ');
            
            const logLine = document.createElement('div');
            logLine.innerText = '[LOG] ' + message;
            consoleOutput.appendChild(logLine);
            consoleOutput.scrollTop = consoleOutput.scrollHeight;
        };
        
        console.error = function() {
            const args = Array.from(arguments);
            originalConsoleError.apply(console, args);
            
            const message = args.map(arg => {
                return typeof arg === 'object' ? JSON.stringify(arg) : arg;
            }).join(' ');
            
            const logLine = document.createElement('div');
            logLine.innerText = '[ERROR] ' + message;
            logLine.style.color = '#f87171'; // Red color for errors
            consoleOutput.appendChild(logLine);
            consoleOutput.scrollTop = consoleOutput.scrollHeight;
        };
        
        console.log('Enhanced test page loaded at ' + new Date().toLocaleTimeString());
        
        // Initialize address search
        initializeAlamatSearch('alamat_search', 'alamat_id');
        
        // Test buttons
        document.getElementById('test_standard').addEventListener('click', function() {
            statusDiv.textContent = 'Testing standard API...';
            statusDiv.className = 'text-sm text-blue-600';
            
            fetch(`/api/alamat/search?term=jakarta`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Standard API test result:', data);
                    statusDiv.textContent = 'Standard API test successful!';
                    statusDiv.className = 'text-sm text-green-600';
                })
                .catch(error => {
                    console.error('Standard API test failed:', error);
                    statusDiv.textContent = 'Standard API test failed: ' + error.message;
                    statusDiv.className = 'text-sm text-red-600';
                });
        });
        
        document.getElementById('test_fallback').addEventListener('click', function() {
            statusDiv.textContent = 'Testing fallback API...';
            statusDiv.className = 'text-sm text-blue-600';
            
            fetch(`/test/api/response?term=jakarta`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Fallback API test result:', data);
                    statusDiv.textContent = 'Fallback API test successful!';
                    statusDiv.className = 'text-sm text-green-600';
                })
                .catch(error => {
                    console.error('Fallback API test failed:', error);
                    statusDiv.textContent = 'Fallback API test failed: ' + error.message;
                    statusDiv.className = 'text-sm text-red-600';
                });
        });
        
        document.getElementById('test_combined').addEventListener('click', function() {
            const searchInput = document.getElementById('alamat_search');
            searchInput.dispatchEvent(new Event('keyup'));
        });
    });
</script>
@endpush

@endsection
