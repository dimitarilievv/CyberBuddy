<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MediaFileService;
use App\Models\Lesson;

class MediaFileController extends Controller
{
    private MediaFileService $mediaService;

    public function __construct(MediaFileService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index(int $lessonId)
    {
        $mediaFiles = $this->mediaService->getLessonMedia($lessonId);

        return view('media.index', compact('mediaFiles'));
    }

    public function store(Request $request, int $lessonId)
    {
        $request->validate([
            'file_name' => 'required|string',
            'file_path' => 'required|string',
            'mime_type' => 'required|string',
            'file_size' => 'required|integer',
            'type' => 'required|string',
        ]);

        $this->mediaService->attachToModel(
            Lesson::class,
            $lessonId,
            $request->only([
                'file_name',
                'file_path',
                'mime_type',
                'file_size',
                'type',
                'alt_text',
                'sort_order'
            ])
        );

        return back()->with('success', 'Media file uploaded successfully!');
    }

    public function destroy(int $mediaId)
    {
        $this->mediaService->deleteMedia($mediaId);

        return back()->with('success', 'Media file deleted!');
    }
}
