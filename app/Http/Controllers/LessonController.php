<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Module;
use App\Models\UserProgress;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Services\BadgeService;
use App\Services\UserStatsService;
use App\Services\CertificateService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LessonController extends Controller
{
    public function __construct(
        private BadgeService $badgeService,
        private UserStatsService $userStatsService,
        private CertificateService $certificateService,
        private NotificationService $notificationService
    ) {}

    public function show(Module $module, Lesson $lesson)
    {
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

            // Count all lessons in the module (not limited to published only) so progress reflects the actual lesson set
            $totalLessons = $module->lessons()->count();
            $completedLessons = UserProgress::where('enrollment_id', $enrollment->id)
                ->where('status', 'completed')
                ->count();

            $percentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

            $wasCompleted = $enrollment->status === 'completed';

            $enrollment->update([
                'progress_percentage' => $percentage,
                'status' => $percentage >= 100 ? 'completed' : 'in_progress',
                'completed_at' => $percentage >= 100 ? now() : null,
            ]);

            // ✅ DETECT MODULE COMPLETION
            $justCompleted = !$wasCompleted && $percentage >= 100;

            Log::info('Module completion check', [
                'user_id' => $user->id,
                'module_id' => $module->id,
                'percentage' => $percentage,
                'wasCompleted' => $wasCompleted,
                'justCompleted' => $justCompleted,
            ]);

            if ($justCompleted) {
                try {
                    $enrollment->refresh();

                    Log::info('Generating certificate', [
                        'user_id' => $user->id,
                        'module_id' => $module->id,
                    ]);

                    $certificate = $this->certificateService->generate($enrollment);

                    Log::info('Certificate generated successfully', [
                        'certificate_id' => $certificate->id,
                    ]);
                } catch (\Throwable $e) {
                    Log::error('Certificate generation failed', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }

            // Add points and streak
            $this->userStatsService->addPoints($user, 10);
            $this->userStatsService->updateStreak($user);

            // Check and award badges
            $newBadges = $this->badgeService->checkAndAward($user);

            if ($newBadges->isNotEmpty()) {
                session()->flash('awarded', $newBadges);
            }
        }

        $nextLesson = Lesson::where('module_id', $module->id)
            ->where('sort_order', '>', $lesson->sort_order)
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->first();

        if ($nextLesson) {
            return redirect()->route('lessons.show', [$module->id, $nextLesson->id])
                ->with('success', 'Lesson marked as completed! Moving to next lesson.');
        }

        return redirect()->route('modules.index')
            ->with('success', 'Congratulations! You have completed this module.');
    }
}
