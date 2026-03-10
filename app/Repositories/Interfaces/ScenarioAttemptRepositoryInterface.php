<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ScenarioAttemptRepositoryInterface extends BaseRepositoryInterface
{
    public function getByUser(int $userId): Collection;

    public function getByScenario(int $scenarioId): Collection;

    public function getUserScenarioAttempts(int $scenarioId, int $userId): Collection;

    public function getAttemptsCount(int $scenarioId, int $userId): int;
}
