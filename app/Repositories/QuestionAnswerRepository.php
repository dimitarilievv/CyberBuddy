<?php

namespace App\Repositories;

use App\Models\QuestionAnswer;
use App\Repositories\Interfaces\QuestionAnswerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class QuestionAnswerRepository extends BaseRepository implements QuestionAnswerRepositoryInterface
{
    public function __construct(\App\Models\QuestionAnswer $model)
    {
        parent::__construct($model);
    }

    public function getByAttemptId(int $attemptId): array
    {
        return $this->model::with('question')
            ->where('quiz_attempt_id', $attemptId)
            ->orderBy('question_id')
            ->get()
            ->toArray();
    }

    public function getCorrectAnswersByAttempt(int $attemptId): array
    {
        return $this->model::with('question')
            ->where('quiz_attempt_id', $attemptId)
            ->where('is_correct', true)
            ->get()
            ->toArray();
    }

    public function getScoreSummaryByAttempt(int $attemptId): array
    {
        $totalPoints = $this->model::where('quiz_attempt_id', $attemptId)->sum('points_earned');
        $totalQuestions = $this->model::where('quiz_attempt_id', $attemptId)->count();
        $correctAnswers = $this->model::where('quiz_attempt_id', $attemptId)->where('is_correct', true)->count();

        return [
            'total_points' => $totalPoints,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'percentage' => $totalQuestions ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0,
        ];
    }
}
