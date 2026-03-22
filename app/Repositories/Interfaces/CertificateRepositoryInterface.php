<?php

namespace App\Repositories\Interfaces;

use App\Models\Certificate;

interface CertificateRepositoryInterface
{
    public function allForUser(int $userId): iterable;

    public function findByEnrollment(int $userId, int $moduleId): ?Certificate;

    public function create(array $data): Certificate;

    public function find(int $id): ?Certificate;
}
