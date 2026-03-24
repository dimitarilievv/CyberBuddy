<?php

namespace App\Repositories;

use App\Models\AiInteraction;
use App\Repositories\Interfaces\AiInteractionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AiInteractionRepository extends BaseRepository implements AiInteractionRepositoryInterface
{
    public function __construct(AiInteraction $model)
    {
        parent::__construct($model);
    }

    public function recentForUser(int $userId, int $limit = 10): Collection
    {
        return $this->model->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function createInteraction(array $data)
    {
        return $this->model->create($data);
    }
}
