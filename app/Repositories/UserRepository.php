<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getByRole(string $role): Collection
    {
        return $this->model->where('role', $role)
            ->with('profile')
            ->get();
    }

    public function getChildren(int $parentId): Collection
    {
        return $this->model->where('parent_id', $parentId)
            ->with(['profile', 'enrollments.module'])
            ->get();
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function getActiveUsers(): Collection
    {
        return $this->model->where('is_active', true)->get();
    }
}
