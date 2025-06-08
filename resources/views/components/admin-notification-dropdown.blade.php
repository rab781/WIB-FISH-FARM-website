<!-- Admin Notification dropdown component -->
<div class="relative inline-block" x-data="{ open: false, unreadCount: 0 }" x-init="
    fetch('{{ route('notifications.unread-count') }}')
        .then(response => response.json())
        .then(data => { unreadCount = data.count; })
        .catch(error => console.error('Error fetching notification count:', error));
">
    <!-- Notification button with counter -->
    <button @click="open = !open; if(open) { loadAdminNotifications(); }"
            class="relative p-1 text-gray-600 hover:text-yellow-600 focus:outline-none focus:text-yellow-500 transition-colors"
            aria-label="Notifications">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        <!-- Notification counter badge -->
        <span x-show="unreadCount > 0"
              x-text="unreadCount"
              class="absolute top-0 right-0 -mt-1 -mr-1 px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-500 text-white">
        </span>
    </button>

    <!-- Notification dropdown - Made wider for better visibility -->
    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-[28rem] bg-yellow-50 rounded-md shadow-lg py-1 z-50 max-h-[600px] overflow-y-auto"
         style="display: none;">

        <!-- Notification title header -->
        <div class="px-4 py-3 border-b border-yellow-100">
            <h3 class="text-base font-medium text-gray-700">Notifikasi Admin</h3>
        </div>

        <div id="admin-notification-container" class="bg-yellow-50">
            <!-- Loading indicator -->
            <div id="admin-notification-loading" class="p-4 text-center text-gray-500">
                <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-2">Memuat notifikasi...</p>
            </div>

            <!-- Will be filled with AJAX -->
            <div id="admin-notification-list" class="divide-y divide-yellow-100"></div>

            <!-- Empty state -->
            <div id="admin-notification-empty" class="p-6 text-center text-gray-500" style="display:none;">
                <svg class="h-12 w-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p>Tidak ada notifikasi baru</p>
            </div>
        </div>

        <div class="border-t border-yellow-100 mt-2 pt-2 pb-2 px-4 text-center">
            <a href="{{ route('notifications.index') }}" class="text-yellow-600 hover:text-yellow-800 font-medium">
                Lihat Semua Notifikasi
            </a>
        </div>
    </div>
</div>

<script>
// Define the loadAdminNotifications function in global scope to make it accessible
function loadAdminNotifications() {
    const container = document.getElementById('admin-notification-container');
    const loading = document.getElementById('admin-notification-loading');
    const list = document.getElementById('admin-notification-list');
    const empty = document.getElementById('admin-notification-empty');

    // Show loading indicator
    loading.style.display = 'block';
    list.style.display = 'none';
    empty.style.display = 'none';

    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Fetch notifications with proper headers
    fetch('{{ route('notifications.index') }}?format=json', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken || ''
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 401) {
                throw new Error('Silakan login untuk melihat notifikasi');
            }
            throw new Error('Terjadi kesalahan: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        loading.style.display = 'none';

        if (data.error) {
            throw new Error(data.error);
        }

        if (!data.notifications || data.notifications.length === 0) {
            empty.style.display = 'block';
            return;
        }

        list.style.display = 'block';
        list.innerHTML = '';

        // Render each notification
        data.notifications.forEach(notification => {
            const item = document.createElement('div');
            item.className = `p-4 hover:bg-yellow-100 cursor-pointer transition duration-150 ${notification.is_read ? 'opacity-70' : 'bg-yellow-50'}`;

            // Generate icon based on notification type
            let iconHtml = '';
            switch(notification.type) {
                case 'product':
                    iconHtml = `<div class="flex-shrink-0 h-14 w-14 rounded bg-indigo-50 flex items-center justify-center">
                                    <svg class="h-7 w-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>`;
                    break;
                case 'order':
                    iconHtml = `<div class="flex-shrink-0 h-14 w-14 rounded bg-green-50 flex items-center justify-center">
                                    <svg class="h-7 w-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>`;
                    break;
                case 'review':
                    iconHtml = `<div class="flex-shrink-0 h-14 w-14 rounded bg-orange-50 flex items-center justify-center">
                                    <svg class="h-7 w-7 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>`;
                    break;
                case 'admin':
                    iconHtml = `<div class="flex-shrink-0 h-14 w-14 rounded bg-yellow-50 flex items-center justify-center">
                                    <svg class="h-7 w-7 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>`;
                    break;
                default:
                    iconHtml = `<div class="flex-shrink-0 h-14 w-14 rounded bg-yellow-50 flex items-center justify-center">
                                    <svg class="h-7 w-7 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                </div>`;
            }

            // Build notification content
            item.innerHTML = `
                <div class="flex">
                    ${iconHtml}
                    <div class="ml-4 flex-1">
                        <div class="font-medium text-gray-800">${notification.title}</div>
                        <p class="text-sm text-gray-600 my-1">${notification.message}</p>
                        <div class="flex justify-between mt-1">
                            <p class="text-xs text-gray-500">${notification.created_at_human}</p>
                            ${!notification.is_read ?
                              `<button class="text-xs text-yellow-600 hover:text-yellow-800 mark-read" data-id="${notification.id}">Tandai dibaca</button>` : ''}
                        </div>
                    </div>
                </div>
            `;

            // Add click event to navigate to detail page and mark as read
            if (notification.data && notification.data.url) {
                item.addEventListener('click', function(e) {
                    // Don't navigate if clicked on the "mark as read" button
                    if (e.target.closest('.mark-read')) {
                        return;
                    }

                    // If notification is unread, mark it as read first
                    if (!notification.is_read) {
                        const formData = new FormData();
                        formData.append('_token', csrfToken);

                        fetch(`{{ url('notifications') }}/${notification.id}/mark-as-read`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken || ''
                            },
                            body: formData,
                            credentials: 'same-origin'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update the Alpine.js unreadCount variable
                                const alpineData = document.querySelector('[x-data]').__x.$data;
                                if (alpineData && typeof alpineData.unreadCount !== 'undefined') {
                                    alpineData.unreadCount = Math.max(0, alpineData.unreadCount - 1);
                                }

                                // Use validated URL from server response
                                if (data.redirect_url) {
                                    window.location.href = data.redirect_url;
                                } else {
                                    window.location.href = notification.data.url;
                                }
                            } else {
                                // Navigate even if marking as read fails
                                window.location.href = notification.data.url;
                            }
                        })
                        .catch(error => {
                            console.error('Error marking notification as read:', error);
                            // Still navigate even if marking as read fails
                            window.location.href = notification.data.url;
                        });
                    } else {
                        // If already read, validate URL before redirect
                        fetch(`{{ url('notifications') }}/${notification.id}/mark-as-read`, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken || ''
                            },
                            body: new FormData(),
                            credentials: 'same-origin'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.redirect_url) {
                                window.location.href = data.redirect_url;
                            } else {
                                window.location.href = notification.data.url;
                            }
                        })
                        .catch(error => {
                            console.error('Error validating notification URL:', error);
                            window.location.href = notification.data.url;
                        });
                        window.location.href = notification.data.url;
                    }
                });
            }

            // Add event listener for mark as read button
            const markReadBtn = item.querySelector('.mark-read');
            if (markReadBtn) {
                markReadBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const id = this.getAttribute('data-id');

                    // Create a form data object for the POST request
                    const formData = new FormData();
                    formData.append('_token', csrfToken);

                    fetch(`{{ url('notifications') }}/${id}/mark-as-read`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken || ''
                        },
                        body: formData,
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 401) {
                                throw new Error('Silakan login untuk menandai notifikasi');
                            }
                            throw new Error('Terjadi kesalahan: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            item.classList.add('opacity-70');
                            markReadBtn.remove();

                            // Update the Alpine.js unreadCount variable
                            const alpineData = document.querySelector('[x-data]').__x.$data;
                            if (alpineData && typeof alpineData.unreadCount !== 'undefined') {
                                alpineData.unreadCount = Math.max(0, alpineData.unreadCount - 1);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error marking notification as read:', error);
                    });
                });
            }

            list.appendChild(item);
        });
    })
    .catch(error => {
        console.error('Error loading notifications:', error);
        loading.style.display = 'none';
        list.innerHTML = '<div class="p-4 text-center text-red-500">Gagal memuat notifikasi: ' + error.message + '</div>';
        list.style.display = 'block';
    });
}
</script>
