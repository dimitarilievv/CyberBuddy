<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Module;
use App\Models\UserProgress;
use App\Models\Enrollment;
use OpenApi\Attributes as OA;

class LessonController extends Controller
{
    public function show(Module $module, Lesson $lesson)
    {
        $lesson->load(['resources', 'scenarios.choices', 'quizzes']);

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
        $enrollment = Enrollment::where('user_id', auth()->id())
            ->where('module_id', $module->id)
            ->first();

        if ($enrollment) {
            UserProgress::updateOrCreate(
                ['user_id' => auth()->id(), 'lesson_id' => $lesson->id],
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
        }

        return redirect()->route('lessons.show', [$module, $lesson])
            ->with('success', 'Lesson is ended!');
    }
}
