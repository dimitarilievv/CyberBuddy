<?php

namespace App\Repositories;

use App\Models\UserBadge;
use App\Repositories\Interfaces\UserBadgeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserBadgeRepository extends BaseRepository implements UserBadgeRepositoryInterface
{
    public function __construct(UserBadge $model)
    {
        parent::__construct($model);
    }

    public function getUserBadges(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->with('badge')->get();
    }

    public function getBadgeUsers(int $badgeId): Collection
    {
        return $this->model->where('badge_id', $badgeId)->with('user')->get();
    }

    public function awardBadge(int $userId, int $badgeId, ?string $reason = null)
    {
        return $this->model->firstOrCreate(
            ['user_id' => $userId, 'badge_id' => $badgeId],
            ['earned_at' => now(), 'reason' => $reason]
        );
    }
}
