<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    public function index(Request $request): View
    {
        $userId = (int) auth()->id();
        $perPage = (int) $request->query('per_page', 15);

        $notifications = $this->notificationService->listForUser($userId, $perPage);
        $unreadCount = $this->notificationService->unreadCount($userId);

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Ако ти треба ова како посебна страница:
     * можеш да направиш notifications/unread.blade.php
     * или едноставно да редиректира со филтер (не е задолжително).
     */
    public function unread(): RedirectResponse
    {
        return redirect()
            ->route('notifications.index')
            ->with('success', 'Showing all notifications. (Unread list endpoint is not a separate page yet.)');
    }

    public function markAsRead(int $id): RedirectResponse
    {
        $userId = (int) auth()->id();

        $ok = $this->notificationService->markAsRead($userId, $id);

        if (! $ok) {
            return back()->with('error', 'Notification not found.');
        }

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead(): RedirectResponse
    {
        $userId = (int) auth()->id();

        $updated = $this->notificationService->markAllAsRead($userId);

        return back()->with('success', "All notifications marked as read. Updated: {$updated}");
    }

    public function destroy(int $id): RedirectResponse
    {
        $userId = (int) auth()->id();

        $ok = $this->notificationService->deleteOne($userId, $id);

        if (! $ok) {
            return back()->with('error', 'Notification not found.');
        }

        return back()->with('success', 'Notification deleted.');
    }

    public function destroyAll(): RedirectResponse
    {
        $userId = (int) auth()->id();

        $deleted = $this->notificationService->deleteAll($userId);

        return redirect()
            ->route('notifications.index')
            ->with('success', "All notifications deleted. Deleted: {$deleted}");
    }
}
