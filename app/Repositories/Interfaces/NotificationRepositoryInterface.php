<?php

namespace App\Repositories\Interfaces;

use App\Models\Notification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface NotificationRepositoryInterface
{
    public function getUserNotifications(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function getUnreadUserNotifications(int $userId): Collection;

    public function unreadCount(int $userId): int;

    public function createForUser(int $userId, array $data): Notification;

    public function findUserNotificationById(int $userId, int $notificationId): ?Notification;

    public function markAsRead(int $userId, int $notificationId): bool;

    public function markAllAsRead(int $userId): int;

    public function deleteUserNotification(int $userId, int $notificationId): bool;

    public function deleteAllUserNotifications(int $userId): int;
}
