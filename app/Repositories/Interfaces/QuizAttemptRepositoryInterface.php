<?php

namespace App\Repositories\Interfaces;

use App\Models\QuizAttempt;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface QuizAttemptRepositoryInterface extends BaseRepositoryInterface
{
    public function getByQuiz(int $quizId, array $filters = []): Collection;
    public function getByUser(int $userId, array $filters = []): LengthAwarePaginator;
    public function getUserAttemptsForQuiz(int $userId, int $quizId): Collection;
    public function countUserAttemptsForQuiz(int $userId, int $quizId): int;
    public function getBestAttemptForUser(int $userId, int $quizId): ?QuizAttempt;
    public function getLatestAttemptForUser(int $userId, int $quizId): ?QuizAttempt;
    public function getInProgressAttempts(int $userId): Collection;
    public function findActiveAttempt(int $userId, int $quizId): ?QuizAttempt;
    public function getPassedAttempts(int $quizId): Collection;
    public function getTopAttempts(int $quizId, int $limit = 10): Collection;
    public function getAttemptsByDateRange(string $from, string $to, ?int $userId = null): Collection;
    public function getAverageScoreForQuiz(int $quizId): float;
    public function submitAttempt(int $attemptId, array $data): QuizAttempt;
    public function saveAiFeedback(int $attemptId, string $feedback): QuizAttempt;
}
