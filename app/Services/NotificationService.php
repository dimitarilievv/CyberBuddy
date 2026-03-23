<?php

namespace App\Services;

use App\Models\Notification;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class NotificationService
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepo
    ) {}

    public function listForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->notificationRepo->getUserNotifications($userId, $perPage);
    }

    public function unreadForUser(int $userId): Collection
    {
        return $this->notificationRepo->getUnreadUserNotifications($userId);
    }

    public function unreadCount(int $userId): int
    {
        return $this->notificationRepo->unreadCount($userId);
    }

    public function notify(int $userId, array $data): Notification
    {
        $payload = [
            'title' => $data['title'] ?? 'Notification',
            'message' => $data['message'] ?? '',
            'type' => $data['type'] ?? 'system',
            'icon' => $data['icon'] ?? null,
            'action_url' => $data['action_url'] ?? null,
            'is_read' => (bool)($data['is_read'] ?? false),
            'read_at' => $data['read_at'] ?? null,
        ];

        $payload = Arr::except($payload, array_keys(array_filter($payload, fn ($v) => $v === null)));

        return $this->notificationRepo->createForUser($userId, $payload);
    }

    public function markAsRead(int $userId, int $notificationId): bool
    {
        return $this->notificationRepo->markAsRead($userId, $notificationId);
    }

    public function markAllAsRead(int $userId): int
    {
        return $this->notificationRepo->markAllAsRead($userId);
    }

    public function deleteOne(int $userId, int $notificationId): bool
    {
        return $this->notificationRepo->deleteUserNotification($userId, $notificationId);
    }

    public function deleteAll(int $userId): int
    {
        return $this->notificationRepo->deleteAllUserNotifications($userId);
    }
}
