<?php

namespace App\Repositories\Interfaces;

use App\Models\AiContentSuggestion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AiContentSuggestionRepositoryInterface
{
    public function paginateForTeacher(string $status = 'pending', int $perPage = 15): LengthAwarePaginator;

    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?AiContentSuggestion;

    public function create(array $data): AiContentSuggestion;

    public function update(int $id, array $data): bool;
}
