<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface ScenarioRepositoryInterface extends BaseRepositoryInterface
{
    public function getPublished(): Collection;

    public function getByLesson(int $lessonId): Collection;

    public function getWithChoices(int $scenarioId);

    public function getByDifficulty(string $difficulty): Collection;

    public function getUserAttempts(int $scenarioId, int $userId): Collection;
}
