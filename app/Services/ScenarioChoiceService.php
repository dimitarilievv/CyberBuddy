<?php

namespace App\Services;

use App\Models\ScenarioChoice;
use App\Repositories\Interfaces\ScenarioChoiceRepositoryInterface;

class ScenarioChoiceService
{
    private ScenarioChoiceRepositoryInterface $choiceRepo;

    public function __construct(ScenarioChoiceRepositoryInterface $choiceRepo)
    {
        $this->choiceRepo = $choiceRepo;
    }

    public function getScenarioChoices(int $scenarioId)
    {
        return $this->choiceRepo->getOrdered($scenarioId);
    }

    public function evaluateChoice(int $choiceId)
    {
        $choice = $this->choiceRepo->find($choiceId);

        return [
            'consequence' => $choice->consequence,
            'safety_score' => $choice->safety_score,
            'ai_explanation' => $choice->ai_explanation,
            'is_recommended' => $choice->is_recommended,
        ];
    }

    public function createChoice(array $data): ScenarioChoice
    {
        return $this->choiceRepo->create($data);
    }
}
