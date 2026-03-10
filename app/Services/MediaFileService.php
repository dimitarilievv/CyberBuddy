<?php

namespace App\Services;

use App\Models\MediaFile;
use App\Repositories\Interfaces\MediaFileRepositoryInterface;

class MediaFileService
{
    private MediaFileRepositoryInterface $mediaRepo;

    public function __construct(MediaFileRepositoryInterface $mediaRepo)
    {
        $this->mediaRepo = $mediaRepo;
    }

    public function getLessonMedia(int $lessonId)
    {
        return $this->mediaRepo->getByLesson($lessonId);
    }

    public function attachToModel(string $modelType, int $modelId, array $data): MediaFile
    {
        $data['mediable_type'] = $modelType;
        $data['mediable_id'] = $modelId;

        return $this->mediaRepo->create($data);
    }

    public function deleteMedia(int $mediaId): void
    {
        $media = $this->mediaRepo->find($mediaId);

        if ($media) {
            $this->mediaRepo->delete($mediaId);
        }
    }
}
