@extends('layouts.customer')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Notifikasi</h1>

            @if($notifications->isNotEmpty() && $notifications->where('is_read', false)->count() > 0)
            <form action="{{ route('notifications.mark-all-as-read') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-orange-600 hover:text-orange-800">
                    Tandai semua sudah dibaca
                </button>
            </form>
            @endif
        </div>

        @if($notifications->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <svg class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <p class="text-gray-600">Tidak ada notifikasi yang ditemukan</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    <div class="p-5 {{ !$notification->is_read ? 'bg-orange-50 font-medium' : 'opacity-70' }} cursor-pointer hover:bg-orange-100 transition-colors duration-150"
                         onclick="handleNotificationClick({{ $notification->id }}, {{ json_encode(isset($notification->data['url']) ? $notification->data['url'] : '') }}, {{ $notification->is_read ? 'true' : 'false' }})">
                        <div class="flex">
                            <div class="flex-shrink-0 mt-1">
                                @switch($notification->type)
                                    @case('product')
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @case('order')
                                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @case('review')
                                        <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                        </div>
                                        @break
                                    @default
                                        <div class="h-10 w-10 rounded-full bg-pink-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                @endswitch
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-medium text-gray-800">{{ $notification->title }}</h3>
                                    <p class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="text-gray-600 mt-1">{{ $notification->message }}</p>
                                @if(!$notification->is_read)
                                <div class="mt-2">
                                    <form action="{{ route('notifications.mark-as-read', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs text-orange-600 hover:text-orange-800">
                                            Tandai dibaca
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function handleNotificationClick(notificationId, url, isRead) {
    // If notification is not read, mark it as read first
    if (!isRead) {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch(`/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.redirect_url) {
                    // Use the validated URL from server response
                    window.location.href = data.redirect_url;
                } else if (url) {
                    // Fallback to original URL if no validated URL provided
                    window.location.href = url;
                }
            } else if (url) {
                // Redirect even if marking as read fails
                window.location.href = url;
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
            // Still redirect even if marking as read fails, but use fallback
            if (url) {
                window.location.href = url;
            } else {
                // If no URL available, reload the notifications page
                window.location.reload();
            }
        });
    } else {
        // If already read, validate URL before redirect
        if (url) {
            // For read notifications, still validate the URL via server
            fetch(`/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: new FormData(),
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    window.location.href = url;
                }
            })
            .catch(error => {
                console.error('Error validating notification URL:', error);
                window.location.href = url;
            });
        }
    }
}
</script>
@endsection
