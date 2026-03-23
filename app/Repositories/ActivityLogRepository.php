<?php

namespace App\Repositories;

use App\Models\ActivityLog;
use App\Repositories\Interfaces\ActivityLogRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityLogRepository extends BaseRepository implements ActivityLogRepositoryInterface
{
    public function __construct(ActivityLog $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with('user')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function findById(int $id): ?ActivityLog
    {
        return $this->model
            ->with(['user', 'loggable'])
            ->where('id', $id)
            ->first();
    }

    public function create(array $data): ActivityLog
    {
        return $this->model->create($data);
    }

    public function delete(int $id): bool
    {
        return $this->model->where('id', $id)->delete() > 0;
    }
}
