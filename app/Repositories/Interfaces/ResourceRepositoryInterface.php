<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Resource;

interface ResourceRepositoryInterface extends BaseRepositoryInterface
{
    public function getLessonResources(int $lessonId): Collection;

    public function getByType(string $type): Collection;
}
