<?php

namespace App\Repositories;

use App\Models\ReportedContent;
use App\Repositories\Interfaces\ReportedContentRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportedContentRepository extends BaseRepository implements ReportedContentRepositoryInterface
{
    public function __construct(ReportedContent $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['reporter', 'reviewer', 'reportable'])
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function paginateForReporter(int $reporterId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['reporter', 'reviewer', 'reportable'])
            ->where('reporter_id', $reporterId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function findById(int $id): ?ReportedContent
    {
        return $this->model
            ->with(['reporter', 'reviewer', 'reportable'])
            ->where('id', $id)
            ->first();
    }

    public function create(array $data): ReportedContent
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data) > 0;
    }
}
