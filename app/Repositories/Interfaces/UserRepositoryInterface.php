<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getByRole(string $role): Collection;

    public function getChildren(int $parentId): Collection;

    public function findByEmail(string $email);

    public function getActiveUsers(): Collection;
}
