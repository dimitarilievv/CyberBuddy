<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Resource;
use App\Services\ResourceService;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function __construct(
        private ResourceService $resourceService
    ) {}

    // Display a listing of resources for a lesson
    public function index(Lesson $lesson)
    {
        $resources = $this->resourceService->getResourcesForLesson($lesson->id);
        return view('resources.index', compact('lesson', 'resources'));
    }

    // Display a single resource (within a lesson context)
    public function show(Lesson $lesson, Resource $resource)
    {
        // Optionally, you may wish to verify the resource belongs to the lesson
        return view('resources.show', compact('lesson', 'resource'));
    }

    // If needed, add store/update/destroy below (let me know if you need these!)
}
