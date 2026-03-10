<?php

namespace App\Repositories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\QuestionRepositoryInterface;

class QuestionRepository extends BaseRepository implements QuestionRepositoryInterface
{
    public function __construct(Question $model)
    {
        parent::__construct($model);
    }

    public function getByQuiz(int $quizId): Collection
    {
        return $this->model
            ->where('quiz_id', $quizId)
            ->get();
    }

    public function getOrderedByQuiz(int $quizId): Collection
    {
        return $this->model
            ->where('quiz_id', $quizId)
            ->orderBy('sort_order')
            ->get();
    }

    public function getWithAnswers(int $questionId)
    {
        return $this->model
            ->with('answers')
            ->findOrFail($questionId);
    }
}
