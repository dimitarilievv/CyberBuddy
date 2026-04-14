<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\NotificationService;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $notifications = [];

    #[On('notificationsUpdated')]
    public function mount()
    {
        $userId = auth()->id();
        if ($userId) {
            $service = app(NotificationService::class);
            $this->unreadCount = (int) $service->unreadCount($userId);
            $this->notifications = $service->unreadForUser($userId)->take(5)->map(function($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'message' => $n->message,
                    'action_url' => $n->action_url ?? route('notifications.index'),
                    'created_at' => $n->created_at,
                    'created_at_human' => optional($n->created_at)->diffForHumans(),
                    'is_read' => $n->is_read,
                ];
            })->all();
        }
    }

    public function markAsRead($id)
    {
        $userId = auth()->id();
        $service = app(NotificationService::class);
        $service->markAsRead($userId, (int)$id);
        $this->mount();
    }

    public function openNotification($id, $url = null)
    {
        $this->markAsRead($id);
        // ✅ Redirect to the action URL
        return redirect($url ?? route('notifications.index'));
    }

    public function render()
    {
        return view('livewire.notification.bell');
    }
}
