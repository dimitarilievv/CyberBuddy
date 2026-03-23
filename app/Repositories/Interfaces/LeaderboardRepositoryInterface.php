<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface LeaderboardRepositoryInterface extends BaseRepositoryInterface
{
    public function top(int $limit = 10, string $period = 'alltime'): Collection;
    public function forUser(int $userId, string $period = 'alltime');
}
