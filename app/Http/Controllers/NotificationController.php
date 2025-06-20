<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must log in first.');
        }

        // Ambil notifikasi terbaru user (misal 10 notif)
        $notifications = $user->notifications()->latest()->take(10)->get();

        // Format data notifikasi untuk frontend
        $formatted = $notifications->map(function ($notif) {
            return [
                'id' => $notif->id,
                'message' => $notif->data['message'],
                'read_at' => $notif->read_at,
                'created_at' => $notif->created_at->diffForHumans(),
            ];
        });

        // Hitung notifikasi belum dibaca
        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'notifications' => $formatted,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        return response()->json(['status' => 'read']);
    }

    public function delete($id)
    {
        $user = Auth::user();
        $notif = $user->notifications()->where('id', $id)->first();

        if ($notif) {
            $notif->delete();
            return response()->json(['status' => 'deleted']);
        }

        return response()->json(['status' => 'not_found'], 404);
    }
}
