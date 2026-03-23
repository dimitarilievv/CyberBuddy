<?php

namespace App\Repositories;

use App\Models\AiContentSuggestion;
use App\Repositories\Interfaces\AiContentSuggestionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AiContentSuggestionRepository extends BaseRepository implements AiContentSuggestionRepositoryInterface
{
    public function __construct(AiContentSuggestion $model)
    {
        parent::__construct($model);
    }

    public function paginateForTeacher(string $status = 'pending', int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['user', 'reviewer'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['user', 'reviewer'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function findById(int $id): ?AiContentSuggestion
    {
        return $this->model
            ->with(['user', 'reviewer'])
            ->where('id', $id)
            ->first();
    }

    public function create(array $data): AiContentSuggestion
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data) > 0;
    }
}
