<?php

namespace App\Services;

use App\Repositories\Interfaces\UserBadgeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserBadgeService
{
    public function __construct(private UserBadgeRepositoryInterface $repo) {}

    public function listForUser(int $userId): Collection
    {
        return $this->repo->getUserBadges($userId);
    }

    public function listUsersForBadge(int $badgeId): Collection
    {
        return $this->repo->getBadgeUsers($badgeId);
    }

    public function award(int $userId, int $badgeId, ?string $reason = null)
    {
        return $this->repo->awardBadge($userId, $badgeId, $reason);
    }
}
