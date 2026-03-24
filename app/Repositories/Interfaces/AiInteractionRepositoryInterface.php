<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface AiInteractionRepositoryInterface extends BaseRepositoryInterface
{
    public function recentForUser(int $userId, int $limit = 10): Collection;
    public function createInteraction(array $data);
}
