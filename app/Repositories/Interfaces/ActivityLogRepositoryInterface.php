<?php

namespace App\Repositories\Interfaces;

use App\Models\ActivityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ActivityLogRepositoryInterface
{
    public function paginate(int $perPage = 20): LengthAwarePaginator;

    public function paginateForUser(int $userId, int $perPage = 20): LengthAwarePaginator;

    public function findById(int $id): ?ActivityLog;

    public function create(array $data): ActivityLog;

    public function delete(int $id): bool;
}
