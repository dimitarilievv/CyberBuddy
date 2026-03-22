<?php

namespace App\Repositories\Interfaces;

use App\Models\QuestionAnswer;

interface QuestionAnswerRepositoryInterface extends BaseRepositoryInterface
{
    public function getByAttemptId(int $attemptId): array;

    public function getCorrectAnswersByAttempt(int $attemptId): array;

    public function getScoreSummaryByAttempt(int $attemptId): array;
}
