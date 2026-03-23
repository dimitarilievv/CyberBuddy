<?php

namespace App\Services;

use App\Repositories\Interfaces\LeaderboardRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LeaderboardService
{
    public function __construct(private LeaderboardRepositoryInterface $repo) {}

    public function getTop(int $limit = 10, string $period = 'alltime'): Collection
    {
        return $this->repo->top($limit, $period);
    }

    public function getForUser(int $userId, string $period = 'alltime')
    {
        return $this->repo->forUser($userId, $period);
    }
}
