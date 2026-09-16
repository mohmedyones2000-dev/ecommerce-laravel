<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::forUser(Auth::id())
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function unread()
    {
        $userId = Auth::id();

        $count = Cache::remember(
            "notif_count_user_{$userId}",
            now()->addSeconds(5),
            fn () => Notification::forUser($userId)->unread()->count()
        );

        $notifications = Notification::forUser($userId)
            ->unread()
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($n) => [
                'id'         => $n->id,
                'title'      => $n->title,
                'message'    => $n->message,
                'type'       => $n->type,
                'color'      => $n->color,
                'icon'       => $n->icon_svg,
                'link'       => $n->link,
                'created_at' => $n->created_at->diffForHumans(),
            ]);

        return response()->json([
            'count'         => $count,
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();
        $this->clearCache();

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back();
    }

    public function markAllAsRead()
    {
        Notification::forUser(Auth::id())
            ->unread()
            ->update(['read_at' => now()]);

        $this->clearCache();

        return back()->with('success', 'تم تعليم جميع الإشعارات كمقروءة');
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();
        $this->clearCache();

        return back()->with('success', 'تم حذف الإشعار');
    }

    public function clearAll()
    {
        Notification::forUser(Auth::id())->delete();
        $this->clearCache();

        return back()->with('success', 'تم حذف جميع الإشعارات');
    }

    protected function clearCache(): void
    {
        Cache::forget('notif_count_user_' . Auth::id());
    }
}