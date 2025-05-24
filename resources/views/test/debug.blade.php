@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Address Search System Debug</h1>
    
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold mb-4">API Configuration</h2>
        <div class="mb-4">
            <strong class="block text-gray-700 mb-1">API Key:</strong>
            <div class="bg-gray-100 p-2 rounded">{{ substr(env('RAJA_ONGKIR_API_KEY'), 0, 5) }}...{{ substr(env('RAJA_ONGKIR_API_KEY'), -3) }}</div>
        </div>
        
        <h3 class="text-lg font-medium mb-2">API Endpoints</h3>
        <ul class="list-disc pl-5 mb-4">
            <li class="mb-2">
                <a href="{{ route('test.raja-ongkir-search', ['term' => 'jakarta']) }}" class="text-blue-600 hover:underline">
                    Test RajaOngkir Direct API
                </a>
            </li>
            <li class="mb-2">
                <a href="{{ route('test.api-response', ['term' => 'jakarta']) }}" class="text-blue-600 hover:underline">
                    Test Internal API
                </a>
            </li>
            <li class="mb-2">
                <a href="/api/alamat/search?term=jakarta" class="text-blue-600 hover:underline" target="_blank">
                    Raw API Response
                </a>
            </li>
        </ul>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold mb-4">Address Search Component Test</h2>
        
        <div class="mb-8">
            <div class="mb-4">
                <label for="alamat_search" class="block text-sm font-medium text-gray-700 mb-1">Search Address</label>
                <input type="text" id="alamat_search" class="w-full px-4 py-2 border rounded-md" placeholder="Type to search (e.g. jakarta)">
                <p class="mt-1 text-xs text-gray-500">Type at least 3 characters to start searching</p>
            </div>
            
            <div class="mb-4">
                <label for="alamat_id" class="block text-sm font-medium text-gray-700 mb-1">Select Address</label>
                <select id="alamat_id" class="w-full px-4 py-2 border rounded-md">
                    <option value="">Select address from search results</option>
                </select>
            </div>
        </div>
        
        <div class="border-t pt-4">
            <h3 class="text-lg font-medium mb-2">Console Output</h3>
            <p class="text-sm text-gray-600 mb-2">Open browser console (F12) to see detailed logs</p>
            <div id="console_output" class="bg-gray-900 text-green-400 p-3 rounded font-mono text-sm h-64 overflow-auto">
                <!-- Console output will be displayed here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/alamat-search.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Capture console.log output
        const originalConsoleLog = console.log;
        const originalConsoleError = console.error;
        const consoleOutput = document.getElementById('console_output');
        
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
        
        console.log('Debug page loaded at ' + new Date().toLocaleTimeString());
        
        // Initialize address search
        initializeAlamatSearch('alamat_search', 'alamat_id');
        console.log('Address search initialized');
    });
</script>
@endpush

@endsection
