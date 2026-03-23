<?php

namespace App\Services;

use App\Repositories\Interfaces\ResourceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ResourceService
{
    public function __construct(
        private ResourceRepositoryInterface $resourceRepo,
    ) {}

    public function getResourcesForLesson(int $lessonId): Collection
    {
        return $this->resourceRepo->getLessonResources($lessonId);
    }

    public function getResourcesByType(string $type): Collection
    {
        return $this->resourceRepo->getByType($type);
    }

    // Optionally, you can add create/update/delete methods here if you wish
}
