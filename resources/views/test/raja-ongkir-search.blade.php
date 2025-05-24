@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">RajaOngkir API Test</h1>
    
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-xl font-semibold mb-4">Search API Test</h2>
        
        <form action="{{ route('test.raja-ongkir-search') }}" method="GET" class="mb-6">
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
                    Search
                </button>
            </div>
        </form>
        
        <div class="mb-4">
            <strong class="block text-gray-700 mb-1">API Key:</strong>
            <div class="bg-gray-100 p-2 rounded">{{ $api_key_masked ?? 'Not configured' }}</div>
        </div>
        
        @if(isset($status))
            <div class="mb-4">
                <strong class="block text-gray-700 mb-1">Status:</strong>
                <div class="bg-gray-100 p-2 rounded">
                    {{ $status }} - {{ $success ? 'Success' : 'Failed' }}
                </div>
            </div>
        @endif
        
        @if(isset($error))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong class="font-bold">Error:</strong>
                <div>{{ $error }}</div>
            </div>
        @endif
        
        @if(isset($results) && count($results) > 0)
            <div class="mb-4">
                <strong class="block text-gray-700 mb-1">Search Results:</strong>
                <div class="overflow-x-auto bg-gray-100 p-4 rounded">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Province</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">City</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">District</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subdistrict</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Postal Code</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($results as $result)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $result['id'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $result['label'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $result['province'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $result['city'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $result['district'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $result['subdistrict'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $result['postal_code'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif(isset($results))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                <p>No results found for search term: "{{ $term }}"</p>
            </div>
        @endif
        
        @if(isset($raw_response))
            <div class="mt-8">
                <h3 class="text-lg font-medium mb-2">Raw API Response:</h3>
                <div class="bg-gray-800 text-white p-4 rounded overflow-auto max-h-96">
                    <pre>{{ $raw_response }}</pre>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
