<?php

namespace App\Repositories;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Repositories\Interfaces\QuizRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class QuizRepository extends BaseRepository implements QuizRepositoryInterface
{
    public function __construct(Quiz $model)
    {
        parent::__construct($model);
    }

    public function getByLesson(int $lessonId): Collection
    {
        return $this->model->where('lesson_id', $lessonId)
            ->where('is_published', true)
            ->get();
    }

    public function getWithQuestions(int $quizId)
    {
        return $this->model->with(['questions' => function ($q) {
            $q->orderBy('sort_order');
        }])->findOrFail($quizId);
    }

    public function getUserAttempts(int $quizId, int $userId): Collection
    {
        return QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function getAttemptsCount(int $quizId, int $userId): int
    {
        return QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->count();
    }
}
