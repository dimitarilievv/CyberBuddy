<?php

namespace App\Repositories;

use App\Models\Leaderboard;
use App\Repositories\Interfaces\LeaderboardRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LeaderboardRepository extends BaseRepository implements LeaderboardRepositoryInterface
{
    public function __construct(Leaderboard $model)
    {
        parent::__construct($model);
    }

    public function top(int $limit = 10, string $period = 'alltime'): Collection
    {
        return $this->model
            ->where('period', $period)
            ->orderByDesc('total_points')
            ->limit($limit)
            ->with('user')
            ->get();
    }

    public function forUser(int $userId, string $period = 'alltime')
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('period', $period)
            ->with('user')
            ->first();
    }
}
