<?php

namespace App\Services;

use App\Models\UserProgress;
use App\Models\Leaderboard;
use App\Repositories\Interfaces\LessonRepositoryInterface;

class LessonService
{
    public function __construct(
        private LessonRepositoryInterface $lessonRepo,
    ) {}


    public function getUserLessonStats(int $userId): array
    {
        // All lessons where the user has any progress
        $lessonsWithProgress = $this->lessonRepo->getLessonsWithUserProgress($userId);

        $completedLessonsCount = UserProgress::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        $inProgressLessonsCount = UserProgress::where('user_id', $userId)
            ->where('status', 'in_progress')
            ->count();

        $totalTime = UserProgress::where('user_id', $userId)
            ->sum('time_spent_seconds');

        return [
            'total_lessons_with_progress' => $lessonsWithProgress->count(),
            'lessons_in_progress' => $inProgressLessonsCount,
            'lessons_completed' => $completedLessonsCount,
            'total_time' => $totalTime,
        ];
    }


    public function updateLessonLeaderboard(int $userId): void
    {
        $completedLessonsCount = UserProgress::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        $totalPoints = $this->calculateLessonPoints($userId);

        Leaderboard::updateOrCreate(
            ['user_id' => $userId, 'period' => 'all_time'],
            [
                // You may want to add / rename columns according to your schema:
                'modules_completed' => $completedLessonsCount, // or a dedicated lessons_completed column
                'total_points' => $totalPoints,
            ]
        );
    }


    private function calculateLessonPoints(int $userId): int
    {
        $completedLessons = UserProgress::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        $quizPoints = \App\Models\QuizAttempt::where('user_id', $userId)
            ->where('passed', true)
            ->sum('score');

        $scenarioPoints = \App\Models\ScenarioAttempt::where('user_id', $userId)
            ->sum('safety_score');

        // Same scoring logic as ProgressService, but conceptually “lesson-focused”
        return ($completedLessons * 10) + $quizPoints + $scenarioPoints;
    }
}
