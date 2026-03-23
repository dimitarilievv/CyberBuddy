<?php

namespace App\Services;

use App\Repositories\Interfaces\UserProgressRepositoryInterface;
use App\Models\UserProgress;
use Illuminate\Database\Eloquent\Collection;

class UserProgressService
{
    public function __construct(
        private UserProgressRepositoryInterface $progressRepo
    ) {}

    public function getUserLessonProgress(int $userId, int $lessonId): ?UserProgress
    {
        return $this->progressRepo->getUserLessonProgress($userId, $lessonId);
    }

    public function getUserAllProgress(int $userId): Collection
    {
        return $this->progressRepo->getUserAllProgress($userId);
    }

    public function getLessonUserProgress(int $lessonId): Collection
    {
        return $this->progressRepo->getLessonUserProgress($lessonId);
    }
}
