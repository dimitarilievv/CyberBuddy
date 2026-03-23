<?php

namespace App\Repositories;

use App\Models\Resource;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ResourceRepository extends BaseRepository implements ResourceRepositoryInterface
{
    public function __construct(Resource $model)
    {
        parent::__construct($model);
    }

    public function getLessonResources(int $lessonId): Collection
    {
        return $this->model->where('lesson_id', $lessonId)->orderBy('sort_order')->get();
    }

    public function getByType(string $type): Collection
    {
        return $this->model->where('type', $type)->get();
    }
}
