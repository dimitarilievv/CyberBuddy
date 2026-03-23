<?php

namespace App\Repositories;

use App\Models\UserProgress;
use App\Repositories\Interfaces\UserProgressRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class UserProgressRepository extends BaseRepository implements UserProgressRepositoryInterface
{
    public function __construct(UserProgress $model)
    {
        parent::__construct($model);
    }

    public function getUserLessonProgress(int $userId, int $lessonId): ?UserProgress
    {
        return $this->model->where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->first();
    }

    public function getUserAllProgress(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function getLessonUserProgress(int $lessonId): Collection
    {
        return $this->model->where('lesson_id', $lessonId)->get();
    }
}
