<?php

namespace App\Services;

use App\Repositories\Interfaces\QuestionAnswerRepositoryInterface;
use App\Models\QuizAttempt;

class QuestionAnswerService
{
    private QuestionAnswerRepositoryInterface $repo;

    public function __construct(QuestionAnswerRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function evaluateAttempt(int $attemptId): array
    {
        $summary = $this->repo->getScoreSummaryByAttempt($attemptId);

        $feedback = $this->generateFeedback($summary['percentage']);

        return [
            'summary' => $summary,
            'feedback' => $feedback,
        ];
    }

    public function submitAnswer(int $attemptId, int $questionId, array|string $givenAnswer): bool
    {
        // Check if answer exists for attempt
        $existing = $this->repo->model()->where([
            'quiz_attempt_id' => $attemptId,
            'question_id' => $questionId
        ])->first();

        if ($existing) {
            return $existing->update([
                'given_answer' => $givenAnswer,
                'is_correct' => $this->checkAnswer($questionId, $givenAnswer)
            ]);
        }

        return (bool)$this->repo->model()->create([
            'quiz_attempt_id' => $attemptId,
            'question_id' => $questionId,
            'given_answer' => $givenAnswer,
            'is_correct' => $this->checkAnswer($questionId, $givenAnswer),
            'points_earned' => 0,
        ]);
    }

    private function checkAnswer(int $questionId, array|string $givenAnswer): bool
    {
        $question = \App\Models\Question::find($questionId);
        return $question && $question->correct_answer == $givenAnswer;
    }

    private function generateFeedback(float $scorePercentage): string
    {
        if ($scorePercentage >= 85) {
            return "🎉 Excellent! You're a CyberBuddy expert!";
        } elseif ($scorePercentage >= 70) {
            return "🌟 Good job! Keep practicing your cybersecurity skills!";
        } else {
            return "💪 Don't worry! Review the questions and try again!";
        }
    }
}
