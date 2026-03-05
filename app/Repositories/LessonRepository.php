<?php

namespace App\Repositories;

use App\Models\Lesson;
use App\Repositories\Interfaces\LessonRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LessonRepository extends BaseRepository implements LessonRepositoryInterface
{
    public function __construct(Lesson $model)
    {
        parent::__construct($model);
    }


    public function getModuleLessons(int $moduleId): Collection
    {
        return $this->model
            ->where('module_id', $moduleId)
            ->get();
    }


    public function getPublishedModuleLessons(int $moduleId): Collection
    {
        return $this->model
            ->where('module_id', $moduleId)
            ->where('is_published', true)
            ->get();
    }


    public function getPublishedLessons(): Collection
    {
        return $this->model
            ->where('is_published', true)
            ->get();
    }


    public function getBySlug(string $slug, ?int $moduleId = null): ?Lesson
    {
        $query = $this->model->where('slug', $slug);

        if (! is_null($moduleId)) {
            $query->where('module_id', $moduleId);
        }

        return $query->first();
    }


    public function getOrderedLessonsByModule(int $moduleId): Collection
    {
        return $this->model
            ->where('module_id', $moduleId)
            ->orderBy('sort_order')
            ->get();
    }


    public function isPublished(int $lessonId): bool
    {
        return $this->model
            ->where('id', $lessonId)
            ->where('is_published', true)
            ->exists();
    }


    public function getLessonsWithUserProgress(int $userId): Collection
    {
        return $this->model
            ->whereHas('userProgress', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with(['userProgress' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->get();
    }
}
