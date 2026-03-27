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

    // 🔴 ДОДАЈ ОВА - Од interface
    public function findOrFail(int $id): QuizAttempt
    {
        return $this->model->findOrFail($id);
    }

    public function find(int $id): ?QuizAttempt
    {
        return $this->model->find($id);
    }

    public function getByUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->where('user_id', $userId);

        if (isset($filters['quiz_id'])) {
            $query->where('quiz_id', $filters['quiz_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getUserAttemptsForQuiz(int $userId, int $quizId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getTopAttempts(int $quizId, int $limit = 10): Collection
    {
        return $this->model
            ->where('quiz_id', $quizId)
            ->orderBy('score', 'desc')
            ->limit($limit)
            ->get();
    }

    public function create(array $data): QuizAttempt
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $attempt = $this->find($id);
        if (! $attempt) {
            return false;
        }

        return (bool) $attempt->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->findOrFail($id)->delete();
    }

    // 🔴 ПОСТОЕЧКИ МЕТОДИ
    public function getByQuiz(int $quizId, array $filters = []): Collection
    {
        return $this->model
            ->where('quiz_id', $quizId)
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByQuizWithPagination(int $quizId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->where('quiz_id', $quizId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getLeaderboard(int $quizId, int $limit = 10): Collection
    {
        return $this->model
            ->where('quiz_id', $quizId)
            ->orderBy('score', 'desc')
            ->limit($limit)
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
            ->orderByDesc('created_at')
            ->first();
    }

    public function getPassedAttempts(int $quizId): Collection
    {
        return $this->model
            ->where('quiz_id', $quizId)
            ->where('status', 'passed')
            ->orderByDesc('score')
            ->get();
    }

    public function getAttemptsByDateRange(string $from, string $to, ?int $userId = null): Collection
    {
        $q = $this->model->whereBetween('created_at', [$from, $to]);
        if ($userId) {
            $q->where('user_id', $userId);
        }
        return $q->get();
    }

    public function getAverageScoreForQuiz(int $quizId): float
    {
        return (float) $this->model
            ->where('quiz_id', $quizId)
            ->whereNotNull('score')
            ->avg('score');
    }

    public function submitAttempt(int $attemptId, array $data): QuizAttempt
    {
        $attempt = $this->findOrFail($attemptId);
        $attempt->update($data);
        return $attempt;
    }

    public function saveAiFeedback(int $attemptId, string $feedback): QuizAttempt
    {
        $attempt = $this->findOrFail($attemptId);
        $attempt->update(['ai_feedback' => $feedback]);
        return $attempt;
    }
}
