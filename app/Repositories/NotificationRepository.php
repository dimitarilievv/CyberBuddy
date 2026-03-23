<?php

namespace App\Repositories;

use App\Models\Notification;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }

    public function getUserNotifications(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function getUnreadUserNotifications(int $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->orderByDesc('created_at')
            ->get();
    }

    public function unreadCount(int $userId): int
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    public function createForUser(int $userId, array $data): Notification
    {
        // Only allow fillable fields (plus user_id) to be set.
        $payload = array_merge($data, ['user_id' => $userId]);

        return $this->model->create($payload);
    }

    public function findUserNotificationById(int $userId, int $notificationId): ?Notification
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('id', $notificationId)
            ->first();
    }

    public function markAsRead(int $userId, int $notificationId): bool
    {
        $notification = $this->findUserNotificationById($userId, $notificationId);

        if (! $notification) {
            return false;
        }

        if ($notification->is_read) {
            return true;
        }

        $notification->markAsRead();
        return true;
    }

    public function markAllAsRead(int $userId): int
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
                'updated_at' => now(),
            ]);
    }

    public function deleteUserNotification(int $userId, int $notificationId): bool
    {
        return $this->model
                ->where('user_id', $userId)
                ->where('id', $notificationId)
                ->delete() > 0;
    }

    public function deleteAllUserNotifications(int $userId): int
    {
        return $this->model
            ->where('user_id', $userId)
            ->delete();
    }
}
