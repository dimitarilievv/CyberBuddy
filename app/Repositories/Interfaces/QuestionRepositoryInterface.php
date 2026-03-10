<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface QuestionRepositoryInterface extends BaseRepositoryInterface
{
    public function getByQuiz(int $quizId): Collection;

    public function getOrderedByQuiz(int $quizId): Collection;

    public function getWithAnswers(int $questionId);
}
