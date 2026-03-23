<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Repositories\Interfaces\ActivityLogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    public function __construct(
        private ActivityLogRepositoryInterface $repo
    ) {}

    public function listAll(int $perPage = 20): LengthAwarePaginator
    {
        return $this->repo->paginate($perPage);
    }

    public function listForUser(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->repo->paginateForUser($userId, $perPage);
    }

    public function find(int $id): ?ActivityLog
    {
        return $this->repo->findById($id);
    }

    /**
     * Basic helper to write logs from anywhere in the app.
     */
    public function log(
        int $userId,
        string $action,
        ?string $description = null,
        ?Model $loggable = null,
        array $metadata = [],
        ?string $ipAddress = null
    ): ActivityLog {
        return $this->repo->create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'loggable_type' => $loggable ? $loggable::class : null,
            'loggable_id' => $loggable?->getKey(),
            'metadata' => $metadata,
            'ip_address' => $ipAddress,
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->repo->delete($id);
    }
}
