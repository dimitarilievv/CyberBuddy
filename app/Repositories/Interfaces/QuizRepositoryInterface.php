<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface QuizRepositoryInterface extends BaseRepositoryInterface
{
    public function getByLesson(int $lessonId): Collection;

    public function getWithQuestions(int $quizId);

    public function getUserAttempts(int $quizId, int $userId): Collection;

    public function getAttemptsCount(int $quizId, int $userId): int;
}
