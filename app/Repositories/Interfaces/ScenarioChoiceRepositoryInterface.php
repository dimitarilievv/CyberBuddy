<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ScenarioChoiceRepositoryInterface extends BaseRepositoryInterface
{
    public function getByScenario(int $scenarioId): Collection;

    public function getRecommended(int $scenarioId);

    public function getOrdered(int $scenarioId): Collection;
}
