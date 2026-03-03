<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface EnrollmentRepositoryInterface extends BaseRepositoryInterface
{
    public function getUserEnrollments(int $userId): Collection;

    public function getModuleEnrollments(int $moduleId): Collection;

    public function isEnrolled(int $userId, int $moduleId): bool;

    public function enroll(int $userId, int $moduleId);

    public function getCompletedByUser(int $userId): Collection;
}
