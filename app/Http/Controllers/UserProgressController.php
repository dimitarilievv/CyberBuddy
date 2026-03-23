<?php

namespace App\Http\Controllers;

use App\Services\UserProgressService;
use App\Models\Lesson;
use Illuminate\Http\Request;

class UserProgressController extends Controller
{
    public function __construct(
        private UserProgressService $progressService
    ) {}

    // Show all progress for the authenticated user
    public function index()
    {
        $userId = auth()->id();
        $progress = $this->progressService->getUserAllProgress($userId);
        $lessons = Lesson::all(); // Fetch all lessons

        return view('user_progress.index', compact('progress', 'lessons'));
    }

    // Show progress for a specific lesson for this user
    public function show(Lesson $lesson)
    {
        $userId = auth()->id();
        $progress = $this->progressService->getUserLessonProgress($userId, $lesson->id);

        return view('user_progress.show', compact('lesson', 'progress'));
    }
}
