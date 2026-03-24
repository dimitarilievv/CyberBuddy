<?php

namespace App\Repositories\Interfaces;

use App\Models\ReportedContent;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReportedContentRepositoryInterface
{
    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function paginateForReporter(int $reporterId, int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?ReportedContent;

    public function create(array $data): ReportedContent;

    public function update(int $id, array $data): bool;
}
