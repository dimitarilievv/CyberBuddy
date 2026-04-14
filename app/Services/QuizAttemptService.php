<?php

namespace App\Services;

use App\Models\QuizAttempt;
use App\Models\QuestionAnswer;
use App\Repositories\Interfaces\QuizAttemptRepositoryInterface;
use App\Repositories\Interfaces\QuizRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class QuizAttemptService
{
    public function __construct(
        private readonly QuizAttemptRepositoryInterface $attemptRepository,
        private readonly QuizRepositoryInterface        $quizRepository,
    )
    {
    }

    public function getAttemptsForUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->attemptRepository->getByUser($userId, $filters);
    }

    public function getAttemptById(int $attemptId): QuizAttempt
    {
        return $this->attemptRepository->findOrFail($attemptId);
    }

    public function getUserAttemptsForQuiz(int $userId, int $quizId): Collection
    {
        return $this->attemptRepository->getUserAttemptsForQuiz($userId, $quizId);
    }

    public function getLeaderboard(int $quizId, int $limit = 10): Collection
    {
        return $this->attemptRepository->getTopAttempts($quizId, $limit);
    }

    public function startAttempt(int $quizId): QuizAttempt
    {
        $userId = Auth::id();
        $quiz = $this->quizRepository->findOrFail($quizId);

        if (!$quiz->is_published) {
            throw ValidationException::withMessages([
                'quiz' => ['This quiz is not available yet.'],
            ]);
        }

        $existing = $this->attemptRepository->findActiveAttempt($userId, $quizId);
        if ($existing) {
            return $existing;
        }

        if ($quiz->max_attempts !== null) {
            $count = $this->attemptRepository->countUserAttemptsForQuiz($userId, $quizId);
            if ($count >= $quiz->max_attempts) {
                throw ValidationException::withMessages([
                    'attempts' => ["You have reached the maximum number of attempts ({$quiz->max_attempts}) for this quiz."],
                ]);
            }
        }

        return $this->attemptRepository->create([
            'quiz_id' => $quizId,
            'user_id' => $userId,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function submitAttempt(int $attemptId, array $answers): QuizAttempt
    {
        $attempt = $this->attemptRepository->findOrFail($attemptId);

        if ($attempt->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to submit this attempt.');
        }

        if ($attempt->status !== 'in_progress') {
            throw ValidationException::withMessages([
                'status' => ['This attempt has already been submitted.'],
            ]);
        }

        $this->enforceTimeLimit($attempt);

        $score = $this->calculateScore($attempt->quiz_id, $answers);
        $quiz = $this->quizRepository->findOrFail($attempt->quiz_id);
        $passed = $score >= $quiz->passing_score;
        $timeSpent = now()->diffInSeconds($attempt->started_at);

        $this->storeAnswers($attemptId, $answers);

        $totalPoints = QuestionAnswer::where('quiz_attempt_id', $attemptId)->sum('points_earned');
        $percentage = $score;

        $updated = $this->attemptRepository->submitAttempt($attemptId, [
            'score' => $score,
            'total_points' => $totalPoints,
            'percentage' => $percentage,
            'passed' => $passed,
            'time_spent_seconds' => $timeSpent,
            'status' => $passed ? 'passed' : 'failed',
            'completed_at' => now(),
        ]);



        $this->generateAndSaveAiFeedback($updated);

        return $updated->fresh(['quiz', 'questionAnswers.question']);
    }

    private function calculateScore(int $quizId, array $answers): float
    {
        $quiz = $this->quizRepository->findOrFail($quizId);
        $questions = $quiz->questions()->get();

        if ($questions->isEmpty()) {
            return 0.0;
        }

        $correct = 0;

        foreach ($questions as $question) {
            $givenAnswer = $answers[$question->id] ?? null;
            if ($givenAnswer === null || $givenAnswer === '') {
                continue;
            }

            $correctLetters = json_decode($question->correct_answer, true) ?? [];
            $givenLetters = is_array($givenAnswer) ? $givenAnswer : [$givenAnswer];

            $isCorrect = !empty($givenLetters)
                && !array_diff($givenLetters, $correctLetters)
                && !array_diff($correctLetters, $givenLetters);

            if ($isCorrect) {
                $correct++;
            }
        }

        return round(($correct / $questions->count()) * 100, 2);
    }

    private function storeAnswers(int $attemptId, array $answers): void
    {
        $records = [];
        foreach ($answers as $questionId => $givenAnswer) {
            $records[] = [
                'quiz_attempt_id' => $attemptId,
                'question_id' => $questionId,
                'given_answer' => $givenAnswer,
                'is_correct' => false,
                'points_earned' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        QuestionAnswer::insert($records);

        // update correctness and points
        foreach ($answers as $questionId => $givenAnswer) {
            $question = $this->quizRepository->findQuestionById($questionId);

            $correctLetters = [];
            if ($question && $question->correct_answer) {
                // correct_answer is JSON, e.g. '["B"]'
                $correctLetters = json_decode($question->correct_answer, true) ?? [];
            }

            // If in the future you allow multiple answers, $givenAnswer may be array
            $givenLetters = is_array($givenAnswer) ? $givenAnswer : [$givenAnswer];

            // fully correct if chosen letters == correctLetters set
            $isCorrect = $question
                && !empty($givenLetters)
                && !array_diff($givenLetters, $correctLetters)
                && !array_diff($correctLetters, $givenLetters);

            QuestionAnswer::where('quiz_attempt_id', $attemptId)
                ->where('question_id', $questionId)
                ->update([
                    'is_correct' => $isCorrect,
                    'points_earned' => $isCorrect ? ($question->points ?? 0) : 0,
                ]);
        }
    }

    private function enforceTimeLimit(QuizAttempt $attempt): void
    {
        $quiz = $this->quizRepository->findOrFail($attempt->quiz_id);

        if ($quiz->time_limit_minutes === null) {
            return;
        }

        $elapsed = now()->diffInMinutes($attempt->started_at);

        if ($elapsed > $quiz->time_limit_minutes) {
            $this->attemptRepository->update($attempt->id, ['status' => 'abandoned']);

            throw ValidationException::withMessages([
                'time' => ['Time is up! Your attempt has expired.'],
            ]);
        }
    }

    public function generateAndSaveAiFeedback(QuizAttempt $attempt): void
    {
        try {
            $feedback = $this->requestAiFeedback($attempt);
            $this->attemptRepository->saveAiFeedback($attempt->id, $feedback);
        } catch (\Throwable) {
            // ignore failures
        }
    }

    private function requestAiFeedback(QuizAttempt $attempt): string
    {
        $score = $attempt->score;
        $passed = $attempt->status === 'passed';
        $quiz = $attempt->quiz;
        $passText = $passed ? 'passed' : 'did not pass';

        $prompt = <<<PROMPT
            You are CyberBuddy, a friendly cybersecurity coach for children aged 8–14.
            A student just finished the quiz "{$quiz->title}" and scored {$score}%.
            They {$passText} the quiz.

            Write 2–3 short, encouraging sentences:
            1. Celebrate their effort.
            2. Mention one cybersecurity tip relevant to this quiz topic.
            3. Motivate them to keep learning.

            Keep the language simple, fun, and safe for children.
            PROMPT;

        $response = Http::withHeaders([
            'x-api-key' => config('services.anthropic.key'),
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->post('https://api.anthropic.com/v1/messages', [
            'model' => 'claude-3-haiku-20240307',
            'max_tokens' => 200,
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return $response->json('content.0.text', 'Great job completing the quiz! Keep exploring cybersecurity!');
    }

    public function getQuizStats(int $quizId): array
    {
        $attempts = $this->attemptRepository->getByQuiz($quizId);

        $completed = $attempts->whereIn('status', ['passed', 'failed']);
        $passed = $attempts->where('status', 'passed');

        return [
            'total_attempts' => $attempts->count(),
            'completed_attempts' => $completed->count(),
            'pass_count' => $passed->count(),
            'pass_rate' => $completed->count() > 0
                ? round(($passed->count() / $completed->count()) * 100, 1)
                : 0,
            'average_score' => $this->attemptRepository->getAverageScoreForQuiz($quizId),
            'average_time_sec' => $completed->avg('time_spent_seconds'),
        ];
    }

    public function getUserStats(int $userId): array
    {
        $attempts = $this->attemptRepository->getByUser($userId, ['per_page' => 999]);
        $items = collect($attempts->items());
        $completed = $items->whereIn('status', ['passed', 'failed']);

        return [
            'total_attempts' => $items->count(),
            'quizzes_passed' => $items->where('status', 'passed')->count(),
            'average_score' => round($completed->avg('score') ?? 0, 1),
            'total_time_spent' => $completed->sum('time_spent_seconds'),
        ];
    }
}
