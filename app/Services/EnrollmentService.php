<?php

namespace App\Services;

use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentService
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollmentRepo
    ) {}

    public function getUserEnrollments(int $userId): Collection
    {
        return $this->enrollmentRepo->getUserEnrollments($userId);
    }

    public function getModuleEnrollments(int $moduleId): Collection
    {
        return $this->enrollmentRepo->getModuleEnrollments($moduleId);
    }

    public function isEnrolled(int $userId, int $moduleId): bool
    {
        return $this->enrollmentRepo->isEnrolled($userId, $moduleId);
    }

    public function enroll(int $userId, int $moduleId)
    {
        return $this->enrollmentRepo->enroll($userId, $moduleId);
    }

    public function getCompletedByUser(int $userId): Collection
    {
        return $this->enrollmentRepo->getCompletedByUser($userId);
    }
}
