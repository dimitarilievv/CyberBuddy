<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface UserBadgeRepositoryInterface extends BaseRepositoryInterface
{
    public function getUserBadges(int $userId): Collection;
    public function getBadgeUsers(int $badgeId): Collection;
    public function awardBadge(int $userId, int $badgeId, ?string $reason = null);
}
