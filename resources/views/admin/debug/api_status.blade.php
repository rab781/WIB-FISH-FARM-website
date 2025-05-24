@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">API System Status</h1>
        <div>
            <a href="{{ url('/test/debug') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Debug Page
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- API Configuration -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">API Configuration</h2>
            </div>
            
            <div class="p-6">
                <dl class="grid grid-cols-3 gap-4">
                    <dt class="col-span-1 font-medium text-gray-600">API Key:</dt>
                    <dd class="col-span-2">{{ $apiKey }}</dd>
                    
                    <dt class="col-span-1 font-medium text-gray-600">API Key Komerce:</dt>
                    <dd class="col-span-2">{{ $apiKeyKomerce }}</dd>
                    
                    <dt class="col-span-1 font-medium text-gray-600">Debug Mode:</dt>
                    <dd class="col-span-2">{{ $appDebug }}</dd>
                </dl>
            </div>
        </div>
        
        <!-- Server Info -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Server Information</h2>
            </div>
            
            <div class="p-6">
                <dl class="grid grid-cols-3 gap-4">
                    <dt class="col-span-1 font-medium text-gray-600">PHP Version:</dt>
                    <dd class="col-span-2">{{ $serverInfo['php'] }}</dd>
                    
                    <dt class="col-span-1 font-medium text-gray-600">Server:</dt>
                    <dd class="col-span-2">{{ $serverInfo['server'] }}</dd>
                    
                    <dt class="col-span-1 font-medium text-gray-600">OS:</dt>
                    <dd class="col-span-2">{{ $serverInfo['os'] }}</dd>
                </dl>
            </div>
        </div>
    </div>
    
    <!-- API Test Results -->
    <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">API Test Results</h2>
        </div>
        
        <div class="p-6">
            @if($testResult['success'])
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                    <p class="font-bold">Connection Successful</p>
                    <p>HTTP Status: {{ $testResult['http_code'] }}</p>
                    <p>Response Time: {{ number_format($testResult['response_time'], 2) }}s</p>
                    <p>Size: {{ number_format($testResult['size'] / 1024, 2) }} KB</p>
                </div>
                <div class="mt-4">
                    <h3 class="font-semibold text-gray-700 mb-2">Sample Response:</h3>
                    <pre class="bg-gray-100 p-3 rounded text-sm overflow-auto max-h-48">{{ $testResult['sample'] }}</pre>
                </div>
            @else
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                    <p class="font-bold">Connection Error</p>
                    <p>{{ $testResult['message'] }}</p>
                    @if(isset($testResult['http_code']))
                        <p>HTTP Status: {{ $testResult['http_code'] }}</p>
                    @endif
                </div>
                <div class="mt-4">
                    <h3 class="font-semibold text-gray-700 mb-2">Troubleshooting Steps:</h3>
                    <ol class="list-decimal pl-5 space-y-2">
                        <li>Check if your API key is valid and not expired</li>
                        <li>Verify internet connection and server outbound access</li>
                        <li>Check if the API service is currently available</li>
                        <li>Try accessing the API from a different connection</li>
                        <li>Contact RajaOngkir support for assistance</li>
                    </ol>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Diagnostic Tools -->
    <div class="mt-6 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Diagnostic Tools</h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('test.raja-ongkir-search', ['term' => 'jakarta']) }}" 
                   class="px-4 py-3 bg-blue-50 text-blue-700 rounded border border-blue-200 hover:bg-blue-100 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 9a2 2 0 114 0 2 2 0 01-4 0z"></path>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a4 4 0 00-3.446 6.032l-2.261 2.26a1 1 0 101.414 1.415l2.261-2.261A4 4 0 1011 5z" clip-rule="evenodd"></path>
                    </svg>
                    Test Direct RajaOngkir API
                </a>
                
                <a href="{{ route('test.api-response', ['term' => 'jakarta']) }}" 
                   class="px-4 py-3 bg-green-50 text-green-700 rounded border border-green-200 hover:bg-green-100 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    Test Internal API
                </a>
                
                <a href="{{ url('/api/alamat/search?term=jakarta') }}" target="_blank" 
                   class="px-4 py-3 bg-purple-50 text-purple-700 rounded border border-purple-200 hover:bg-purple-100 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"></path>
                    </svg>
                    View Raw API Response
                </a>
                
                <a href="{{ route('test.debug') }}" 
                   class="px-4 py-3 bg-yellow-50 text-yellow-700 rounded border border-yellow-200 hover:bg-yellow-100 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Open Debug Console
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
