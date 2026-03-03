<?php

namespace App\Services;

use App\Repositories\Interfaces\ModuleRepositoryInterface;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;

class ModuleService
{
    public function __construct(
        private ModuleRepositoryInterface $moduleRepo,
        private EnrollmentRepositoryInterface $enrollmentRepo,
    ) {}

    public function getPublishedModules()
    {
        return $this->moduleRepo->getPublished();
    }

    public function getModuleBySlug(string $slug)
    {
        return $this->moduleRepo->findBySlug($slug);
    }

    public function getModulesForUser(int $userId, string $audience)
    {
        return $this->moduleRepo->getByAudience($audience);
    }

    public function enrollUser(int $userId, int $moduleId)
    {
        return $this->enrollmentRepo->enroll($userId, $moduleId);
    }

    public function isUserEnrolled(int $userId, int $moduleId): bool
    {
        return $this->enrollmentRepo->isEnrolled($userId, $moduleId);
    }

    public function getPopularModules(int $limit = 5)
    {
        return $this->moduleRepo->getPopular($limit);
    }
}
