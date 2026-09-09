<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Halaman semua notifikasi.
     */
    public function index(Request $request)
    {
        $query = Notification::forUser(auth()->id())->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->get('filter') === 'unread') {
            $query->unread();
        }

        $notifications = $query->paginate(20)->withQueryString();
        $unreadCount = Notification::forUser(auth()->id())->unread()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * API: Ambil notifikasi belum dibaca (untuk AJAX polling di topbar).
     */
    public function getUnread()
    {
        $notifications = Notification::forUser(auth()->id())
            ->unread()
            ->latest()
            ->limit(5)
            ->get();

        $count = Notification::forUser(auth()->id())->unread()->count();

        return response()->json([
            'count' => $count,
            'notifications' => $notifications->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'type' => $notif->type,
                    'icon' => $notif->icon,
                    'title' => $notif->title,
                    'message' => \Str::limit($notif->message, 80),
                    'action_url' => $notif->action_url,
                    'time_ago' => $notif->time_ago,
                    'dot_color' => $notif->dot_color,
                ];
            }),
        ]);
    }

    /**
     * Tandai satu notifikasi sebagai dibaca.
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $notification->markAsRead();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        // Redirect ke action_url jika ada
        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        return back();
    }

    /**
     * Tandai semua notifikasi sebagai dibaca.
     */
    public function markAllAsRead()
    {
        Notification::forUser(auth()->id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    /**
     * Hapus notifikasi.
     */
    public function destroy($id)
    {
        Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }
}
