<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ModuleRepositoryInterface extends BaseRepositoryInterface
{
    public function getPublished(): Collection;

    public function getByCategory(int $categoryId): Collection;

    public function getByAudience(string $audience): Collection;

    public function getByAuthor(int $authorId): Collection;

    public function findBySlug(string $slug);

    public function getPopular(int $limit = 5): Collection;
}
