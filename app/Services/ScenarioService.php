<?php

namespace App\Services;

use App\Models\ScenarioAttempt;
use App\Repositories\Interfaces\ScenarioRepositoryInterface;

class ScenarioService
{
    public function __construct(
        private ScenarioRepositoryInterface $scenarioRepo,
        private AIService $aiService,
    ) {}

    public function getScenarioWithChoices(int $scenarioId)
    {
        return $this->scenarioRepo->getWithChoices($scenarioId);
    }

    public function getPublishedScenarios()
    {
        return $this->scenarioRepo->getPublished();
    }

    public function getByLesson(int $lessonId)
    {
        return $this->scenarioRepo->getByLesson($lessonId);
    }

    public function submitChoice(int $scenarioId, int $userId, int $choiceId): ScenarioAttempt
    {
        $scenario = $this->scenarioRepo->getWithChoices($scenarioId);
        $choice = $scenario->choices->find($choiceId);

        $aiFeedback = $this->aiService->ask(
            "Ученик избра: \"{$choice->choice_text}\" во ситуација: \"{$scenario->situation}\". " .
            "Дај кратко објаснување на македонски (2-3 реченици) зошто тоа е " .
            ($choice->is_recommended ? "добар" : "лош") . " избор за безбедноста на интернет."
        );

        return ScenarioAttempt::create([
            'scenario_id' => $scenarioId,
            'user_id' => $userId,
            'chosen_choice_id' => $choiceId,
            'safety_score' => $choice->safety_score,
            'ai_feedback' => $aiFeedback,
        ]);
    }

    public function generateScenario(string $topic, string $difficulty = 'medium'): array
    {
        $prompt = "Генерирај сценарио за сајбер безбедност за деца (10-13 години) на тема: {$topic}.
        Тежина: {$difficulty}.

        Врати JSON со формат:
        {
            \"title\": \"Наслов\",
            \"situation\": \"Опис на ситуацијата\",
            \"choices\": [
                {\"text\": \"Избор 1\", \"consequence\": \"Последица\", \"safety_score\": 0, \"is_recommended\": false},
                {\"text\": \"Избор 2\", \"consequence\": \"Последица\", \"safety_score\": 50, \"is_recommended\": false},
                {\"text\": \"Избор 3\", \"consequence\": \"Последица\", \"safety_score\": 100, \"is_recommended\": true}
            ]
        }";

        return $this->aiService->askJson($prompt);
    }
}
