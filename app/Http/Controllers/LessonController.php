<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Module;
use App\Models\UserProgress;
use App\Models\Enrollment;
use App\Services\BadgeService;
use App\Services\UserStatsService;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function __construct(
        private BadgeService $badgeService,
        private UserStatsService $userStatsService
    ) {}

    public function show(Module $module, Lesson $lesson)
    {
        // Проверка дали лекцијата припаѓа на модулот
        if ($lesson->module_id !== $module->id) {
            abort(404);
        }

        $lesson->load(['resources', 'scenarios.choices', 'quizzes', 'mediaFiles']);

        $progress = null;
        if (auth()->check()) {
            $enrollment = Enrollment::where('user_id', auth()->id())
                ->where('module_id', $module->id)
                ->first();

            if ($enrollment) {
                $progress = UserProgress::firstOrCreate(
                    ['user_id' => auth()->id(), 'lesson_id' => $lesson->id],
                    [
                        'enrollment_id' => $enrollment->id,
                        'status' => 'in_progress',
                        'started_at' => now(),
                    ]
                );
            }
        }

        $nextLesson = Lesson::where('module_id', $module->id)
            ->where('sort_order', '>', $lesson->sort_order)
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->first();

        $prevLesson = Lesson::where('module_id', $module->id)
            ->where('sort_order', '<', $lesson->sort_order)
            ->where('is_published', true)
            ->orderByDesc('sort_order')
            ->first();

        return view('lessons.show', compact('module', 'lesson', 'progress', 'nextLesson', 'prevLesson'));
    }

    public function complete(Module $module, Lesson $lesson)
    {
        // Проверка дали лекцијата припаѓа на модулот
        if ($lesson->module_id !== $module->id) {
            abort(404);
        }

        if (!auth()->check()) {
            abort(401);
        }

        $user = Auth::user();
        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('module_id', $module->id)
            ->first();

        if ($enrollment) {
            UserProgress::updateOrCreate(
                ['user_id' => $user->id, 'lesson_id' => $lesson->id],
                [
                    'enrollment_id' => $enrollment->id,
                    'status' => 'completed',
                    'completed_at' => now(),
                ]
            );

            $totalLessons = $module->lessons()->where('is_published', true)->count();
            $completedLessons = UserProgress::where('enrollment_id', $enrollment->id)
                ->where('status', 'completed')
                ->count();

            $percentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

            $enrollment->update([
                'progress_percentage' => $percentage,
                'status' => $percentage >= 100 ? 'completed' : 'in_progress',
                'completed_at' => $percentage >= 100 ? now() : null,
            ]);

            // ✅ ADD USER STATS - Points and Streak
            $this->userStatsService->addPoints($user, 10); // 10 points per lesson
            $this->userStatsService->updateStreak($user);

            // ✅ CHECK AND AWARD BADGES
            $newBadges = $this->badgeService->checkAndAward($user);

            if ($newBadges->isNotEmpty()) {
                session()->flash('awarded', $newBadges);
            }
        }

        // Find the next lesson
        $nextLesson = Lesson::where('module_id', $module->id)
            ->where('sort_order', '>', $lesson->sort_order)
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->first();

        if ($nextLesson) {
            return redirect()->route('lessons.show', [$module->id, $nextLesson->id])
                ->with('success', 'Lesson marked as completed! Moving to next lesson.');
        }

        // If no next lesson, go to module overview or congrat message
        return redirect()->route('modules.index')
            ->with('success', 'Congratulations! You have completed this module.');
    }
}
