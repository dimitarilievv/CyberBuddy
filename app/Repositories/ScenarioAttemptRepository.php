<?php

namespace App\Repositories;

use App\Models\ScenarioAttempt;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\ScenarioAttemptRepositoryInterface;

class ScenarioAttemptRepository extends BaseRepository implements ScenarioAttemptRepositoryInterface
{
    public function __construct(ScenarioAttempt $model)
    {
        parent::__construct($model);
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function getByScenario(int $scenarioId): Collection
    {
        return $this->model
            ->where('scenario_id', $scenarioId)
            ->get();
    }

    public function getUserScenarioAttempts(int $scenarioId, int $userId): Collection
    {
        return $this->model
            ->where('scenario_id', $scenarioId)
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function getAttemptsCount(int $scenarioId, int $userId): int
    {
        return $this->model
            ->where('scenario_id', $scenarioId)
            ->where('user_id', $userId)
            ->count();
    }
}
