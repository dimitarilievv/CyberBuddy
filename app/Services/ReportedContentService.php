<?php

namespace App\Services;

use App\Models\ReportedContent;
use App\Repositories\Interfaces\ReportedContentRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportedContentService
{
    public function __construct(
        private ReportedContentRepositoryInterface $repo
    ) {}

    public function listForAdmin(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repo->paginate($perPage);
    }

    public function listForReporter(int $reporterId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repo->paginateForReporter($reporterId, $perPage);
    }

    public function find(int $id): ?ReportedContent
    {
        return $this->repo->findById($id);
    }

    public function createReport(int $reporterId, array $data): ReportedContent
    {
        return $this->repo->create([
            'reporter_id' => $reporterId,
            'reportable_type' => $data['reportable_type'],
            'reportable_id' => $data['reportable_id'],
            'reason' => $data['reason'],
            'description' => $data['description'] ?? null,
            'status' => 'pending',
            'reviewed_by' => null,
            'admin_notes' => null,
            'reviewed_at' => null,
        ]);
    }

    public function review(int $id, int $reviewerId, string $status, ?string $adminNotes = null): bool
    {
        // Example statuses: pending / reviewed / dismissed
        return $this->repo->update($id, [
            'status' => $status,
            'reviewed_by' => $reviewerId,
            'admin_notes' => $adminNotes,
            'reviewed_at' => now(),
        ]);
    }
}
