<?php

namespace App\Services;

use App\Models\Question;
use App\Repositories\Interfaces\QuestionRepositoryInterface;

class QuestionService
{
    private QuestionRepositoryInterface $questionRepo;

    public function __construct(QuestionRepositoryInterface $questionRepo)
    {
        $this->questionRepo = $questionRepo;
    }

    public function getQuizQuestions(int $quizId)
    {
        return $this->questionRepo->getOrderedByQuiz($quizId);
    }

    public function createQuestion(array $data): Question
    {
        return $this->questionRepo->create($data);
    }

    public function checkAnswer(int $questionId, $givenAnswer): bool
    {
        $question = $this->questionRepo->find($questionId);

        if ($givenAnswer === null) {
            return false;
        }

        $correct = $question->correct_answer;
        $given = is_array($givenAnswer) ? $givenAnswer : [$givenAnswer];

        return empty(array_diff($correct, $given)) && empty(array_diff($given, $correct));
    }
}
