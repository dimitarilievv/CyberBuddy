<?php

namespace App\Livewire\Notification;

use App\Models\Notification as CustomNotification;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 15;

    public function markAsRead($notificationId)
    {
        $notification = CustomNotification::findOrFail($notificationId);

        if ($notification->user_id === auth()->id()) {
            $notification->markAsRead();
            $this->dispatch('notificationRead', $notificationId);
        }
    }

    public function markAllAsRead()
    {
        $userId = auth()->id();
        CustomNotification::where('user_id', $userId)->whereNull('read_at')->update(['is_read' => true, 'read_at' => now()]);
        $this->dispatch('allNotificationsRead');
    }

    public function delete($notificationId)
    {
        $notification = CustomNotification::findOrFail($notificationId);

        if ($notification->user_id === auth()->id()) {
            $notification->delete();
            $this->dispatch('notificationDeleted', $notificationId);
        }
    }

    public function deleteAll()
    {
        CustomNotification::where('user_id', auth()->id())->delete();
        $this->dispatch('allNotificationsDeleted');
    }

    public function render()
    {
        $userId = auth()->id();

        $unreadCount = CustomNotification::where('user_id', $userId)->where('is_read', false)->count();

        $notifications = CustomNotification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.notification.index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ])->layout('layouts.app');
    }
}
