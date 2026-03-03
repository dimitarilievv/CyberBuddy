<?php

namespace App\Repositories;

use App\Models\Scenario;
use App\Models\ScenarioAttempt;
use App\Repositories\Interfaces\ScenarioRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ScenarioRepository extends BaseRepository implements ScenarioRepositoryInterface
{
    public function __construct(Scenario $model)
    {
        parent::__construct($model);
    }

    public function getPublished(): Collection
    {
        return $this->model->where('is_published', true)
            ->with('choices')
            ->get();
    }

    public function getByLesson(int $lessonId): Collection
    {
        return $this->model->where('lesson_id', $lessonId)
            ->where('is_published', true)
            ->with('choices')
            ->get();
    }

    public function getWithChoices(int $scenarioId)
    {
        return $this->model->with(['choices' => function ($q) {
            $q->orderBy('sort_order');
        }])->findOrFail($scenarioId);
    }

    public function getByDifficulty(string $difficulty): Collection
    {
        return $this->model->where('difficulty', $difficulty)
            ->where('is_published', true)
            ->get();
    }

    public function getUserAttempts(int $scenarioId, int $userId): Collection
    {
        return ScenarioAttempt::where('scenario_id', $scenarioId)
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }
}
