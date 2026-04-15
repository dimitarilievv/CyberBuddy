<?php

namespace App\Services;

use App\Models\UserProgress;
use App\Models\Leaderboard;

class LessonService
{
    public function __construct()
    {
        // No dependencies needed right now
    }

    public function getUserLessonStats(int $userId): array
    {
        // All lessons where the user has any progress
        // (if you really need "lessonsWithProgress", you can fetch Lesson via relation)
        $lessonsWithProgress = UserProgress::where('user_id', $userId)
            ->pluck('lesson_id')
            ->unique();

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
                // rename if your column is different
                'modules_completed' => $completedLessonsCount, // or lessons_completed
                'total_points'      => $totalPoints,
            ]
        );
    }

    private function calculateLessonPoints(int $userId): int
    {
        // 10 points per completed lesson
        $completedLessons = UserProgress::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        $quizPoints = \App\Models\QuizAttempt::where('user_id', $userId)
            ->where('passed', true)
            ->sum('score');

        $scenarioPoints = \App\Models\ScenarioAttempt::where('user_id', $userId)
            ->sum('safety_score');

        return ($completedLessons * 10) + $quizPoints + $scenarioPoints;
    }
}
