<?php

namespace App\Services;

use App\Models\ScenarioAttempt;
use App\Repositories\Interfaces\ScenarioAttemptRepositoryInterface;
use App\Repositories\Interfaces\ScenarioChoiceRepositoryInterface;

class ScenarioAttemptService
{
    private ScenarioAttemptRepositoryInterface $attemptRepo;
    private ScenarioChoiceRepositoryInterface $choiceRepo;

    public function __construct(
        ScenarioAttemptRepositoryInterface $attemptRepo,
        ScenarioChoiceRepositoryInterface  $choiceRepo
    )
    {
        $this->attemptRepo = $attemptRepo;
        $this->choiceRepo = $choiceRepo;
    }

    public function submitAttempt(int $scenarioId, int $userId, int $choiceId, int $timeSpent = 0): ScenarioAttempt
    {
        $choice = $this->choiceRepo->find($choiceId);

        $attempt = $this->attemptRepo->create([
            'scenario_id' => $scenarioId,
            'user_id' => $userId,
            'chosen_choice_id' => $choiceId,
            'safety_score' => $choice->safety_score,
            'ai_feedback' => $choice->ai_explanation,
            'time_spent_seconds' => $timeSpent,
        ]);

        return $attempt->fresh();
    }

    public function getUserAttempts(int $scenarioId, int $userId)
    {
        return $this->attemptRepo->getUserScenarioAttempts($scenarioId, $userId);
    }

    public function canAttempt(int $scenarioId, int $userId): bool
    {
        $attempts = $this->attemptRepo->getAttemptsCount($scenarioId, $userId);

        return $attempts < 5;
    }
}
