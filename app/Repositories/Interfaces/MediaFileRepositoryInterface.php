<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface MediaFileRepositoryInterface extends BaseRepositoryInterface
{
    public function getByModel(string $modelType, int $modelId): Collection;

    public function getByLesson(int $lessonId): Collection;

    public function getByType(string $type): Collection;
}
