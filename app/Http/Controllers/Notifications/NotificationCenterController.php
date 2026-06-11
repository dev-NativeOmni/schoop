<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationCenterController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(15);

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $request->user()->unreadNotifications()->count(),
        ]);
    }

    public function show(Request $request, string $notification): View
    {
        $notification = $this->findUserNotification($request, $notification);

        if (! $notification->read_at) {
            $notification->markAsRead();
        }

        return view('notifications.show', [
            'notification' => $notification,
            'data' => $notification->data,
        ]);
    }

    public function markAsRead(Request $request, string $notification): RedirectResponse
    {
        $notification = $this->findUserNotification($request, $notification);

        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function destroy(Request $request, string $notification): RedirectResponse
    {
        $notification = $this->findUserNotification($request, $notification);

        $notification->delete();

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }

    private function findUserNotification(Request $request, string $notificationId): DatabaseNotification
    {
        return $request->user()
            ->notifications()
            ->where('id', $notificationId)
            ->firstOrFail();
    }
}
