<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ModuleRepositoryInterface extends BaseRepositoryInterface
{
    public function getPublished(): Collection;
    public function getPublishedPaginated(int $perPage = 9);

    public function getByCategory(int $categoryId): Collection;
    public function getByCategoryPaginated(int $categoryId, int $perPage = 9);

    public function getByAudience(string $audience): Collection;
    public function getByAudiencePaginated(string $audience, int $perPage = 9);

    public function getByAuthor(int $authorId): Collection;
    public function getByAuthorPaginated(int $authorId, int $perPage = 9);

    public function findBySlug(string $slug);

    public function getPopular(int $limit = 5): Collection;

    public function getAllModules(): Collection;
    public function getAllModulesPaginated(int $perPage = 9);

}
