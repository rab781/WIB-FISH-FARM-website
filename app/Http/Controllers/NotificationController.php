<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Produk;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     */
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            if (request()->wantsJson() || request()->has('format') && request('format') === 'json') {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        $user = Auth::user();
        $isAdmin = $user->is_admin ?? false;

        try {
            // For API requests, check if specific admin/customer filter is provided
            $forAdmin = $isAdmin;
            if (request()->has('for_admin')) {
                $forAdmin = request('for_admin') == 1;
            }

            // Get notifications appropriate for the user type (admin or customer)
            $notifications = Notification::where('user_id', $user->id)
                ->where('for_admin', $forAdmin)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Return JSON response for AJAX requests
            if (request()->wantsJson() || request()->has('format') && request('format') === 'json') {
                // Pastikan $notifications adalah instance Paginator
                if (method_exists($notifications, 'items')) {
                    $notificationItems = $notifications->items();
                } else {
                    // Jika bukan Paginator, gunakan sebagai koleksi atau array
                    $notificationItems = is_array($notifications) ? $notifications : $notifications->all();
                }

                // Konversi notifikasi ke format yang diinginkan
                $formattedNotifications = collect($notificationItems)->map(function($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'data' => $notification->data,
                        'is_read' => (bool) $notification->is_read,
                        'created_at' => $notification->created_at,
                        'created_at_human' => $notification->created_at->diffForHumans(),
                    ];
                });

                return response()->json([
                    'notifications' => $formattedNotifications,
                    'unread_count' => Notification::where('user_id', $user->id)
                        ->where('for_admin', $forAdmin)
                        ->where('is_read', false)
                        ->count(),
                ]);
            }

            // Use different views for admin and customer
            if ($isAdmin) {
                $title = 'Notifikasi Admin';
                return view('admin.notifications.index', compact('notifications', 'title'));
            } else {
                return view('customer.notifications.index', compact('notifications'));
            }
        } catch (\Exception $e) {
            Log::error('Error loading notifications: ' . $e->getMessage());

            if (request()->wantsJson() || request()->has('format') && request('format') === 'json') {
                return response()->json(['error' => 'Failed to load notifications'], 500);
            }

            return redirect()->back()->with('error', 'Gagal memuat notifikasi');
        }
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead($id)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        try {
            $notification = Notification::findOrFail($id);

            // Check if this notification belongs to the current user
            if ($notification->user_id != Auth::id()) {
                if (request()->ajax()) {
                    return response()->json(['error' => 'Unauthorized access'], 403);
                }
                return redirect()->back()->with('error', 'Tidak diizinkan mengakses notifikasi ini');
            }

            $notification->is_read = true;
            $notification->save();

            if (request()->ajax()) {
                $redirectUrl = null;
                if (is_array($notification->data) && isset($notification->data['url'])) {
                    $redirectUrl = $this->validateAndGetRedirectUrl($notification);
                }

                return response()->json([
                    'success' => true,
                    'redirect_url' => $redirectUrl
                ]);
            }

            // If notification has a URL, validate and redirect there, otherwise redirect back
            if (is_array($notification->data) && isset($notification->data['url'])) {
                $validUrl = $this->validateAndGetRedirectUrl($notification);
                if ($validUrl) {
                    return redirect($validUrl);
                }
            }

            // Redirect based on user type
            $isAdmin = Auth::user()->is_admin ?? false;
            if ($isAdmin) {
                return redirect()->route('admin.notifications.index')->with('success', 'Notifikasi telah ditandai sebagai dibaca');
            } else {
                return redirect()->route('notifications.index')->with('success', 'Notifikasi telah ditandai sebagai dibaca');
            }
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json(['error' => 'Failed to mark notification as read'], 500);
            }

            return redirect()->back()->with('error', 'Gagal menandai notifikasi sebagai dibaca');
        }
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        try {
            $user = Auth::user();

            Notification::where('user_id', $user->id)
                ->where('for_admin', $user->is_admin ?? false)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            if (request()->ajax()) {
                return response()->json(['success' => true]);
            }

            // Redirect based on user type
            $isAdmin = Auth::user()->is_admin ?? false;
            if ($isAdmin) {
                return redirect()->route('admin.notifications.index')->with('success', 'Semua notifikasi telah ditandai sebagai dibaca');
            } else {
                return redirect()->route('notifications.index')->with('success', 'Semua notifikasi telah ditandai sebagai dibaca');
            }
        } catch (\Exception $e) {
            Log::error('Error marking all notifications as read: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json(['error' => 'Failed to mark all notifications as read'], 500);
            }

            return redirect()->back()->with('error', 'Gagal menandai semua notifikasi sebagai dibaca');
        }
    }

    /**
     * Get the count of unread notifications.
     */
    public function getUnreadCount()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        try {
            $user = Auth::user();
            $isAdmin = $user->is_admin ?? false;

            // For API requests, check if specific admin/customer filter is provided
            $forAdmin = $isAdmin;
            if (request()->has('for_admin')) {
                $forAdmin = request('for_admin') == 1;
            }

            $count = Notification::where('user_id', $user->id)
                ->where('for_admin', $forAdmin)
                ->where('is_read', false)
                ->count();

            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            Log::error('Error getting unread notification count: ' . $e->getMessage());
            return response()->json(['count' => 0, 'error' => 'Failed to get count']);
        }
    }

    /**
     * Create a new notification.
     *
     * @param array $data Notification data
     * @return Notification
     */
    public static function createNotification($data)
    {
        return Notification::create([
            'user_id' => $data['user_id'] ?? null,
            'type' => $data['type'] ?? 'general',
            'title' => $data['title'] ?? 'Notifikasi Baru',
            'message' => $data['message'] ?? '',
            'data' => $data['data'] ?? null,
            'is_read' => false,
            'for_admin' => $data['for_admin'] ?? false,
        ]);
    }

    /**
     * Send notifications to all admins.
     *
     * @param array $data Notification data
     */
    public static function notifyAdmins($data)
    {
        $admins = User::where('is_admin', true)->get();

        foreach ($admins as $admin) {
            self::createNotification([
                'user_id' => $admin->id,
                'type' => $data['type'] ?? 'admin',
                'title' => $data['title'] ?? 'Notifikasi Admin Baru',
                'message' => $data['message'] ?? '',
                'data' => $data['data'] ?? null,
                'for_admin' => true,
            ]);
        }
    }

    /**
     * Send notifications to a specific customer.
     *
     * @param int $userId User ID
     * @param array $data Notification data
     */
    public static function notifyCustomer($userId, $data)
    {
        return self::createNotification([
            'user_id' => $userId,
            'type' => $data['type'] ?? 'customer',
            'title' => $data['title'] ?? 'Notifikasi Baru',
            'message' => $data['message'] ?? '',
            'data' => $data['data'] ?? null,
            'for_admin' => false,
        ]);
    }

    /**
     * Validate notification URL and return appropriate redirect URL
     */
    private function validateAndGetRedirectUrl($notification)
    {
        if (!is_array($notification->data) || !isset($notification->data['url'])) {
            return null;
        }

        $url = $notification->data['url'];
        $type = $notification->type ?? 'general';

        try {
            // Check if URL is for product detail
            if ($type === 'product' && isset($notification->data['product_id'])) {
                $productId = $notification->data['product_id'];
                $product = \App\Models\Produk::withTrashed()->find($productId);

                if (!$product) {
                    // Product completely deleted - redirect to product list
                    return route('produk.index');
                } elseif ($product->deleted_at) {
                    // Product soft deleted - notify user and redirect to product list
                    session()->flash('warning', 'Produk yang dimaksud telah dihapus. Anda dialihkan ke halaman produk.');
                    return route('produk.index');
                }
                // Product exists and available - return original URL
                return $url;
            }

            // Check if URL is for order detail
            if ($type === 'order' && isset($notification->data['order_id'])) {
                $orderId = $notification->data['order_id'];
                $order = \App\Models\Pesanan::find($orderId);

                if (!$order) {
                    // Order not found - redirect to orders list
                    $isAdmin = Auth::user()->is_admin ?? false;
                    if ($isAdmin) {
                        return route('admin.pesanan.index');
                    } else {
                        return route('pesanan.index');
                    }
                }
                // Order exists - return original URL
                return $url;
            }

            // For other notification types or if no specific validation needed
            return $url;

        } catch (\Exception $e) {
            Log::error('Error validating notification URL: ' . $e->getMessage(), [
                'notification_id' => $notification->id,
                'url' => $url,
                'type' => $type
            ]);

            // If validation fails, redirect based on user type
            $isAdmin = Auth::user()->is_admin ?? false;
            if ($isAdmin) {
                return route('admin.notifications.index');
            } else {
                return route('notifications.index');
            }
        }
    }
}
