<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentRepository extends BaseRepository implements EnrollmentRepositoryInterface
{
    public function __construct(Enrollment $model)
    {
        parent::__construct($model);
    }

    public function getUserEnrollments(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)
            ->with(['module.category', 'module.lessons'])
            ->latest()
            ->get();
    }

    public function getModuleEnrollments(int $moduleId): Collection
    {
        return $this->model->where('module_id', $moduleId)
            ->with('user')
            ->get();
    }

    public function isEnrolled(int $userId, int $moduleId): bool
    {
        return $this->model->where('user_id', $userId)
            ->where('module_id', $moduleId)
            ->exists();
    }

    public function enroll(int $userId, int $moduleId)
    {
        return $this->model->firstOrCreate(
            ['user_id' => $userId, 'module_id' => $moduleId],
            ['status' => 'enrolled', 'enrolled_at' => now()]
        );
    }

    public function getCompletedByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)
            ->where('status', 'completed')
            ->with('module')
            ->get();
    }
}
