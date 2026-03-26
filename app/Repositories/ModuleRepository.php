<?php

namespace App\Repositories;

use App\Models\Module;
use App\Repositories\Interfaces\ModuleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ModuleRepository extends BaseRepository implements ModuleRepositoryInterface
{
    public function __construct(Module $model)
    {
        parent::__construct($model);
    }

    public function getPublished(): Collection
    {
        return $this->model->where('is_published', true)
            ->with(['category', 'tags', 'author'])
            ->withCount('lessons')
            ->orderBy('sort_order')
            ->get();
    }

    public function getPublishedPaginated(int $perPage = 9)
    {
        return $this->model->where('is_published', true)
            ->with(['category', 'tags', 'author'])
            ->withCount('lessons')
            ->orderBy('sort_order')
            ->paginate($perPage);
    }

    public function getByCategory(int $categoryId): Collection
    {
        return $this->model->where('category_id', $categoryId)
            ->where('is_published', true)
            ->with(['tags', 'author'])
            ->get();
    }

    public function getByCategoryPaginated(int $categoryId, int $perPage = 9)
    {
        return $this->model->where('category_id', $categoryId)
            ->where('is_published', true)
            ->with(['tags', 'author'])
            ->paginate($perPage);
    }

    public function getByAudience(string $audience): Collection
    {
        return $this->model->where('audience', $audience)
            ->where('is_published', true)
            ->with(['category', 'tags'])
            ->get();
    }

    public function getByAudiencePaginated(string $audience, int $perPage = 9)
    {
        return $this->model->where('audience', $audience)
            ->where('is_published', true)
            ->with(['category', 'tags'])
            ->paginate($perPage);
    }

    public function getByAuthor(int $authorId): Collection
    {
        return $this->model->where('author_id', $authorId)
            ->withCount('enrollments')
            ->latest()
            ->get();
    }
    public function getByAuthorPaginated(int $authorId, int $perPage = 9)
    {
        return $this->model->where('author_id', $authorId)
            ->withCount('enrollments')
            ->latest()
            ->paginate($perPage);
    }
    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)
            ->with(['category', 'tags', 'lessons' => function ($q) {
                $q->where('is_published', true)->orderBy('sort_order');
            }, 'author'])
            ->firstOrFail();
    }

    public function getPopular(int $limit = 5): Collection
    {
        return $this->model->where('is_published', true)
            ->withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->limit($limit)
            ->get();
    }

    public function getAllModules(): Collection
    {
        return $this->model->all();
    }
    public function getAllModulesPaginated(int $perPage = 9)
    {
        return $this->model->paginate($perPage);
    }
}
