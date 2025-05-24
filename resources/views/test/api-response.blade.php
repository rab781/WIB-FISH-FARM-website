@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">API Response Test</h1>
    
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold mb-4">Direct API Response</h2>
        
        <form action="{{ route('test.api-response') }}" method="GET" class="mb-6">
            <div class="flex items-center">
                <input 
                    type="text" 
                    name="term" 
                    value="{{ $term ?? '' }}" 
                    placeholder="Enter search term (e.g. bon, jakarta)"
                    class="w-full px-4 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <button 
                    type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Test API
                </button>
            </div>
        </form>
        
        @if(isset($apiUrl))
        <div class="mb-4">
            <strong class="block text-gray-700 mb-1">API URL:</strong>
            <div class="bg-gray-100 p-2 rounded">{{ $apiUrl }}</div>
        </div>
        @endif
        
        @if(isset($status))
        <div class="mb-4">
            <strong class="block text-gray-700 mb-1">Status:</strong>
            <div class="bg-gray-100 p-2 rounded">{{ $status }}</div>
        </div>
        @endif
        
        @if(isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong class="font-bold">Error:</strong>
            <div>{{ $error }}</div>
        </div>
        @endif
        
        @if(isset($response))
        <div class="mb-4">
            <strong class="block text-gray-700 mb-1">Raw Response:</strong>
            <div class="bg-gray-100 p-2 rounded overflow-x-auto">
                <pre>{{ $response }}</pre>
            </div>
        </div>
        @endif
        
        @if(isset($parsedData))
        <div class="mb-4">
            <strong class="block text-gray-700 mb-1">Parsed Data:</strong>
            <div class="bg-gray-100 p-2 rounded overflow-x-auto">
                <pre>{{ json_encode($parsedData, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        @endif
        
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Test in Alamat Search</h3>
            <div class="mb-4">
                <label for="alamat_search" class="block text-sm font-medium text-gray-700 mb-1">Search Address</label>
                <input type="text" id="alamat_search" class="w-full px-4 py-2 border rounded-md" placeholder="Type to search (e.g. jakarta)">
            </div>
            
            <div class="mb-4">
                <label for="alamat_id" class="block text-sm font-medium text-gray-700 mb-1">Select Address</label>
                <select id="alamat_id" class="w-full px-4 py-2 border rounded-md">
                    <option value="">Select address from search results</option>
                </select>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/alamat-search.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeAlamatSearch('alamat_search', 'alamat_id');
    });
</script>
@endpush

@endsection
