<?php

namespace App\Repositories;

use App\Models\MediaFile;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\MediaFileRepositoryInterface;

class MediaFileRepository extends BaseRepository implements MediaFileRepositoryInterface
{
    public function __construct(MediaFile $model)
    {
        parent::__construct($model);
    }

    public function getByModel(string $modelType, int $modelId): Collection
    {
        return $this->model
            ->where('mediable_type', $modelType)
            ->where('mediable_id', $modelId)
            ->orderBy('sort_order')
            ->get();
    }

    public function getByLesson(int $lessonId): Collection
    {
        return $this->getByModel(Lesson::class, $lessonId);
    }

    public function getByType(string $type): Collection
    {
        return $this->model
            ->where('type', $type)
            ->get();
    }
}
