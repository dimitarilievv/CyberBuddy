<?php

namespace App\Services;

use App\Models\QuizAttempt;
use App\Models\QuestionAnswer;
use App\Repositories\Interfaces\QuizRepositoryInterface;

class QuizService
{
    private QuizRepositoryInterface $quizRepo;

    public function __construct(
        QuizRepositoryInterface $quizRepo
    ) {
        $this->quizRepo = $quizRepo;
    }

    public function getQuizWithQuestions(int $quizId)
    {
        return $this->quizRepo->getWithQuestions($quizId);
    }

    public function canAttempt(int $quizId, int $userId): bool
    {
        $quiz = $this->quizRepo->find($quizId);
        $attempts = $this->quizRepo->getAttemptsCount($quizId, $userId);

        return $attempts < $quiz->max_attempts;
    }

    public function submitQuiz(int $quizId, int $userId, array $answers): QuizAttempt
    {
        $quiz = $this->quizRepo->getWithQuestions($quizId);

        $attempt = QuizAttempt::create([
            'quiz_id' => $quizId,
            'user_id' => $userId,
            'started_at' => now(),
        ]);

        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($quiz->questions as $question) {
            $givenAnswer = $answers[$question->id] ?? null;
            $isCorrect = $this->checkAnswer($question, $givenAnswer);
            $points = $isCorrect ? $question->points : 0;

            QuestionAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'given_answer' => $givenAnswer,
                'is_correct' => $isCorrect,
                'points_earned' => $points,
            ]);

            $totalPoints += $question->points;
            $earnedPoints += $points;
        }

        $percentage = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;

        $attempt->update([
            'score' => $earnedPoints,
            'total_points' => $totalPoints,
            'percentage' => $percentage,
            'passed' => $percentage >= $quiz->passing_score,
            'completed_at' => now(),
        ]);

        return $attempt->fresh();
    }

    private function checkAnswer($question, $givenAnswer): bool
    {
        if ($givenAnswer === null) return false;

        $correct = $question->correct_answer;
        $given = is_array($givenAnswer) ? $givenAnswer : [$givenAnswer];

        return empty(array_diff($correct, $given)) && empty(array_diff($given, $correct));
    }
}
