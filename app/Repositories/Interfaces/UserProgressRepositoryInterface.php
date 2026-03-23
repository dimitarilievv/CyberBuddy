<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\UserProgress;

interface UserProgressRepositoryInterface extends BaseRepositoryInterface
{
    public function getUserLessonProgress(int $userId, int $lessonId): ?UserProgress;
    public function getUserAllProgress(int $userId): Collection;
    public function getLessonUserProgress(int $lessonId): Collection;
}
