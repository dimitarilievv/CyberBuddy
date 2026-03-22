<?php

namespace App\Repositories;

use App\Models\QuizAttempt;
use App\Repositories\Interfaces\QuizAttemptRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class QuizAttemptRepository extends BaseRepository implements QuizAttemptRepositoryInterface
{
    public function __construct(QuizAttempt $model)
    {
        parent::__construct($model);
    }

    public function getByQuiz(int $quizId, array $filters = []): Collection
    {
        return $this->model
            ->where('quiz_id', $quizId)
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->when(isset($filters['user_id']), fn($q) => $q->where('user_id', $filters['user_id']))
            ->with(['user:id,name,avatar'])
            ->orderByDesc('score')
            ->get();
    }

    public function getByUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->model
            ->where('user_id', $userId)
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->when(isset($filters['quiz_id']), fn($q) => $q->where('quiz_id', $filters['quiz_id']))
            ->with(['quiz:id,title,passing_score'])
            ->orderByDesc('created_at')
            ->paginate($filters['per_page'] ?? 15);
    }

    public function getUserAttemptsForQuiz(int $userId, int $quizId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->with(['answers.question'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function countUserAttemptsForQuiz(int $userId, int $quizId): int
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->whereIn('status', ['completed', 'passed', 'failed'])
            ->count();
    }

    public function getBestAttemptForUser(int $userId, int $quizId): ?QuizAttempt
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->whereNotNull('score')
            ->orderByDesc('score')
            ->first();
    }

    public function getLatestAttemptForUser(int $userId, int $quizId): ?QuizAttempt
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->orderByDesc('created_at')
            ->first();
    }

    public function getInProgressAttempts(int $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('status', 'in_progress')
            ->with(['quiz:id,title,time_limit_minutes'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function findActiveAttempt(int $userId, int $quizId): ?QuizAttempt
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->where('status', 'in_progress')
            ->first();
    }

    public function getPassedAttempts(int $quizId): Collection
    {
        return $this->model
            ->where('quiz_id', $quizId)
            ->where('status', 'passed')
            ->with(['user:id,name,avatar'])
            ->orderByDesc('score')
            ->get();
    }

    public function getTopAttempts(int $quizId, int $limit = 10): Collection
    {
        return $this->model
            ->where('quiz_id', $quizId)
            ->where('status', 'passed')
            ->with(['user:id,name,avatar'])
            ->orderByDesc('score')
            ->orderBy('time_spent_seconds')
            ->limit($limit)
            ->get();
    }

    public function getAttemptsByDateRange(string $from, string $to, ?int $userId = null): Collection
    {
        return $this->model
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->whereBetween('created_at', [$from, $to])
            ->with(['quiz:id,title', 'user:id,name'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function getAverageScoreForQuiz(int $quizId): float
    {
        return (float) $this->model
            ->where('quiz_id', $quizId)
            ->whereNotNull('score')
            ->avg('score') ?? 0.0;
    }

    public function submitAttempt(int $attemptId, array $data): QuizAttempt
    {
        $attempt = $this->model->findOrFail($attemptId);
        $attempt->update($data);

        return $attempt->fresh(['quiz', 'answers']);
    }

    public function saveAiFeedback(int $attemptId, string $feedback): QuizAttempt
    {
        $attempt = $this->model->findOrFail($attemptId);
        $attempt->update(['ai_feedback' => $feedback]);

        return $attempt;
    }
}
