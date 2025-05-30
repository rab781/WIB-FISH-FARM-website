@extends('admin.layouts.app')

@section('title', 'Diagnostik Bukti Pembayaran')

@section('styles')
<style>
    .status-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 6px;
    }
    .status-success {
        background-color: #10b981;
    }
    .status-warning {
        background-color: #f59e0b;
    }
    .status-error {
        background-color: #ef4444;
    }

    code {
        background: #f1f5f9;
        padding: 2px 4px;
        border-radius: 4px;
        font-family: monospace;
        font-size: 0.9em;
    }

    .image-thumbnail {
        max-width: 100px;
        max-height: 100px;
        object-fit: contain;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto">
    <!-- Page Heading -->
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold text-gray-800">Diagnostik Bukti Pembayaran</h1>

        <div class="flex space-x-2">
            <a href="{{ route('admin.diagnostic.payment-proofs', ['format' => 'json']) }}" class="px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700" target="_blank">
                View as JSON
            </a>
            <a href="{{ route('admin.pesanan.index') }}" class="px-3 py-2 bg-gray-500 text-white text-sm font-medium rounded hover:bg-gray-600">
                Back to Orders
            </a>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white rounded-lg shadow-md mb-6 p-5">
        <h2 class="text-lg font-medium mb-4">Check Specific Order</h2>
        <form action="{{ route('admin.diagnostic.payment-proofs') }}" method="GET" class="flex items-end space-x-4">
            <div class="flex-1">
                <label for="order_id" class="block text-sm font-medium text-gray-700 mb-1">Order ID</label>
                <input type="text" id="order_id" name="id" value="{{ $id ?? '' }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Enter Order ID">
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                    Check Order
                </button>
            </div>
        </form>
    </div>

    <!-- Directory Status -->
    <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium">Directory Status</h2>
        </div>
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Path</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exists</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Writable</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Files</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($data['directories'] as $name => $info)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <code>{{ $name }}</code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="status-indicator {{ $info['exists'] ? 'status-success' : 'status-error' }}"></span>
                                {{ $info['exists'] ? 'Yes' : 'No' }}
                                @if(!$info['exists'] && isset($info['created']) && $info['created'])
                                    <span class="text-green-600 text-xs ml-2">(Created Now)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($info['exists'])
                                    <span class="status-indicator {{ $info['writable'] ? 'status-success' : 'status-error' }}"></span>
                                    {{ $info['writable'] ? 'Yes' : 'No' }}
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($info['exists'])
                                    {{ $info['files_count'] }} files
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($info['exists'] && $info['files_count'] > 0)
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900">View Files</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Order Details -->
    @if(isset($data['order']))
    <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium">Order #{{ $data['order']['id'] ?? 'Unknown' }} Details</h2>
        </div>
        <div class="p-5">
            @if(isset($data['order']['error']))
            <div class="bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ $data['order']['error'] }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="mb-4">
                <h3 class="text-md font-medium mb-2">Payment Proof Path</h3>
                <div class="bg-gray-50 p-3 rounded-md">
                    <code>{{ $data['order']['bukti_pembayaran'] ?? 'Not set' }}</code>
                </div>
            </div>

            @if(isset($data['path_tests']))
            <div class="mb-4">
                <h3 class="text-md font-medium mb-2">Path Tests</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Path Type</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Path</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exists</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Readable</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($data['path_tests'] as $type => $test)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $type }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-800 overflow-auto max-w-xs">
                                        <code>{{ $test['path'] }}</code>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-indicator {{ $test['exists'] ? 'status-success' : 'status-error' }}"></span>
                                    {{ $test['exists'] ? 'Yes' : 'No' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($test['exists'])
                                        <span class="status-indicator {{ $test['readable'] ? 'status-success' : 'status-error' }}"></span>
                                        {{ $test['readable'] ? 'Yes' : 'No' }}
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($test['exists'])
                                        {{ $test['size'] ? round($test['size']/1024, 2) . ' KB' : 'Unknown' }}
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if(isset($data['urls']))
            <div class="mb-4">
                <h3 class="text-md font-medium mb-2">URL Tests</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($data['urls'] as $type => $url)
                        @if($url)
                        <div class="bg-gray-50 p-3 rounded-md">
                            <div class="text-sm font-medium mb-1">{{ $type }}</div>
                            <div class="flex justify-between items-center">
                                <div class="text-xs text-gray-600 overflow-hidden overflow-ellipsis">{{ $url }}</div>
                                <a href="{{ $url }}" target="_blank" class="px-2 py-1 bg-blue-500 text-white rounded-md text-xs">Test</a>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-3 border border-gray-200 rounded-md">
                        <h4 class="text-sm font-medium mb-2">Admin Route Preview</h4>
                        <div class="bg-gray-100 p-2 rounded flex items-center justify-center">
                            <img src="{{ $data['urls']['admin_route'] }}" alt="Admin Preview"
                                class="image-thumbnail"
                                onerror="this.onerror=null;this.src='/Images/image-not-found.png';this.nextElementSibling.classList.remove('hidden');">
                            <div class="hidden text-xs text-red-600 mt-2">Failed to load image</div>
                        </div>
                    </div>

                    <div class="p-3 border border-gray-200 rounded-md">
                        <h4 class="text-sm font-medium mb-2">Direct Asset Preview</h4>
                        <div class="bg-gray-100 p-2 rounded flex items-center justify-center">
                            <img src="{{ $data['urls']['direct_asset'] }}" alt="Direct Preview"
                                class="image-thumbnail"
                                onerror="this.onerror=null;this.src='/Images/image-not-found.png';this.nextElementSibling.classList.remove('hidden');">
                            <div class="hidden text-xs text-red-600 mt-2">Failed to load image</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
    @endif

    <!-- Recent Orders with Payment Proofs -->
    <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium">Recent Orders with Payment Proofs</h2>
        </div>
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Path</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File Found</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($data['recent_orders'] as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $order['id'] }}</td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-gray-600 max-w-xs overflow-hidden overflow-ellipsis">
                                    {{ $order['bukti_pembayaran'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="status-indicator {{ $order['file_found'] ? 'status-success' : 'status-error' }}"></span>
                                {{ $order['file_found'] ? 'Yes' : 'No' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($order['file_found'])
                                    <span class="text-xs text-gray-600">{{ $order['found_at'] }}</span>
                                @else
                                    <span class="text-gray-400">Not found</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                {{ $order['updated_at'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.diagnostic.payment-proofs', ['id' => $order['id']]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Check</a>
                                <a href="{{ route('admin.pesanan.show', $order['id']) }}" class="text-green-600 hover:text-green-900">View Order</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
