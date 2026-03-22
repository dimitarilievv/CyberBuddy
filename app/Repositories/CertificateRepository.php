<?php

namespace App\Repositories;

use App\Models\Certificate;
use App\Repositories\Interfaces\CertificateRepositoryInterface;

class CertificateRepository implements CertificateRepositoryInterface
{
    public function allForUser(int $userId): iterable
    {
        return Certificate::where('user_id', $userId)
            ->with('module')
            ->latest('issued_at')
            ->get();
    }

    public function findByEnrollment(int $userId, int $moduleId): ?Certificate
    {
        return Certificate::where('user_id', $userId)
            ->where('module_id', $moduleId)
            ->first();
    }

    public function create(array $data): Certificate
    {
        return Certificate::create($data);
    }

    public function find(int $id): ?Certificate
    {
        return Certificate::with(['user', 'module'])->find($id);
    }
}
