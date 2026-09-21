<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    // Render the "View all" notifications page
    public function index()
    {
        $user = Auth::user();
        $all = $user->notifications()->orderBy('created_at', 'desc')->paginate(100);
        return view('admin.notifications.index', compact('all'));
    }

    // Return JSON payload for topbar (polling)
    public function list(Request $request)
    {
        $user = Auth::user();

        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function($n) {
                return [
                    'id' => $n->id,
                    'data' => $n->data,
                    'read_at' => $n->read_at,
                    'created_at' => $n->created_at->toDateTimeString(),
                    'time_diff' => $n->created_at->diffForHumans(),
                ];
            });

        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    // Mark a single notification as read
    public function markRead(Request $request)
    {
        $request->validate(['id' => 'required|string']);
        $user = Auth::user();

        $notification = $user->notifications()->where('id', $request->id)->first();

        if ($notification && !$notification->read_at) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    // Mark all unread as read
    public function clearAll(Request $request)
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
