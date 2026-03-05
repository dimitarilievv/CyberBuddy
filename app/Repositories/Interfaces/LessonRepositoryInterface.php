<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Lesson;

interface LessonRepositoryInterface extends BaseRepositoryInterface
{

    public function getModuleLessons(int $moduleId): Collection;


    public function getPublishedModuleLessons(int $moduleId): Collection;


    public function getPublishedLessons(): Collection;


    public function getBySlug(string $slug, ?int $moduleId = null): ?Lesson;


    public function getOrderedLessonsByModule(int $moduleId): Collection;


    public function isPublished(int $lessonId): bool;


    public function getLessonsWithUserProgress(int $userId): Collection;
}
