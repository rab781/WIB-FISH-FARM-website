<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
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
            // Get notifications appropriate for the user type (admin or customer)
            $notifications = Notification::where('user_id', $user->id)
                ->where('for_admin', $isAdmin)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Return JSON response for AJAX requests
            if (request()->wantsJson() || request()->has('format') && request('format') === 'json') {
                $formattedNotifications = $notifications->items()->map(function($notification) {
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
                        ->where('for_admin', $isAdmin)
                        ->where('is_read', false)
                        ->count(),
                ]);
            }

            return view('notifications.index', compact('notifications'));
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
                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('success', 'Notifikasi telah ditandai sebagai dibaca');
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

            return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca');
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

            $count = Notification::where('user_id', $user->id)
                ->where('for_admin', $isAdmin)
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
}
