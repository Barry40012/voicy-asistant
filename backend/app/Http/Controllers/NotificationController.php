<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get user notifications
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->notifications()->orderBy('created_at', 'desc');
        
        // Filter by read status
        if ($request->has('unread_only') && $request->unread_only) {
            $query->where('is_read', false);
        }
        
        // Limit results
        $limit = $request->get('limit', 20);
        $notifications = $query->limit($limit)->get();
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotificationsCount(),
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(UserNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'unread_count' => Auth::user()->unreadNotificationsCount(),
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        
        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }

    /**
     * Get unread count
     */
    public function unreadCount()
    {
        return response()->json([
            'unread_count' => Auth::user()->unreadNotificationsCount(),
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy(UserNotification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'unread_count' => Auth::user()->unreadNotificationsCount(),
        ]);
    }
}
