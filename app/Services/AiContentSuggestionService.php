<?php

namespace App\Services;

use App\Models\AiContentSuggestion;
use App\Repositories\Interfaces\AiContentSuggestionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AiContentSuggestionService
{
    public function __construct(
        private AiContentSuggestionRepositoryInterface $repo
    ) {}

    public function listPendingForTeacher(int $perPage = 15): LengthAwarePaginator
    {
        return $this->repo->paginateForTeacher('pending', $perPage);
    }

    public function listForTeacher(?string $status, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repo->paginateForTeacher($status ?? 'pending', $perPage);
    }

    public function listForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repo->paginateForUser($userId, $perPage);
    }

    /**
     * This is used by the "AI" part of the system.
     * For now it’s just a normal create with pending status.
     */
    public function createSuggestion(int $userId, array $data): AiContentSuggestion
    {
        return $this->repo->create([
            'user_id' => $userId,
            'content_type' => $data['content_type'],
            'title' => $data['title'],
            'suggested_content' => $data['suggested_content'],
            'status' => 'pending',
            'admin_notes' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);
    }

    public function approve(int $suggestionId, int $teacherId, ?string $adminNotes = null): bool
    {
        return $this->repo->update($suggestionId, [
            'status' => 'approved',
            'reviewed_by' => $teacherId,
            'reviewed_at' => now(),
            'admin_notes' => $adminNotes,
        ]);
    }

    public function reject(int $suggestionId, int $teacherId, ?string $adminNotes = null): bool
    {
        return $this->repo->update($suggestionId, [
            'status' => 'rejected',
            'reviewed_by' => $teacherId,
            'reviewed_at' => now(),
            'admin_notes' => $adminNotes,
        ]);
    }
}
