<?php

namespace App\Services;

use App\Repositories\Interfaces\AiInteractionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AiInteractionService
{
    public function __construct(private AiInteractionRepositoryInterface $repo) {}

    public function recentForUser(int $userId, int $limit = 10): Collection
    {
        return $this->repo->recentForUser($userId, $limit);
    }

    public function create(array $data)
    {
        return $this->repo->createInteraction($data);
    }
}
