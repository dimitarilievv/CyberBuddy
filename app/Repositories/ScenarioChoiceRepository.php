<?php

namespace App\Repositories;

use App\Models\ScenarioChoice;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\ScenarioChoiceRepositoryInterface;

class ScenarioChoiceRepository extends BaseRepository implements ScenarioChoiceRepositoryInterface
{
    public function __construct(ScenarioChoice $model)
    {
        parent::__construct($model);
    }

    public function getByScenario(int $scenarioId): Collection
    {
        return $this->model
            ->where('scenario_id', $scenarioId)
            ->get();
    }

    public function getRecommended(int $scenarioId)
    {
        return $this->model
            ->where('scenario_id', $scenarioId)
            ->where('is_recommended', true)
            ->first();
    }

    public function getOrdered(int $scenarioId): Collection
    {
        return $this->model
            ->where('scenario_id', $scenarioId)
            ->orderBy('sort_order')
            ->get();
    }
}
