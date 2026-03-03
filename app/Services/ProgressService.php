<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\UserProgress;
use App\Models\Leaderboard;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;

class ProgressService
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollmentRepo,
    ) {}

    public function getUserStats(int $userId): array
    {
        $enrollments = $this->enrollmentRepo->getUserEnrollments($userId);

        return [
            'total_enrolled' => $enrollments->count(),
            'in_progress' => $enrollments->where('status', 'in_progress')->count(),
            'completed' => $enrollments->where('status', 'completed')->count(),
            'avg_progress' => round($enrollments->avg('progress_percentage'), 1),
            'total_time' => UserProgress::where('user_id', $userId)->sum('time_spent_seconds'),
        ];
    }

    public function updateLeaderboard(int $userId): void
    {
        $enrollments = $this->enrollmentRepo->getUserEnrollments($userId);

        Leaderboard::updateOrCreate(
            ['user_id' => $userId, 'period' => 'all_time'],
            [
                'modules_completed' => $enrollments->where('status', 'completed')->count(),
                'total_points' => $this->calculatePoints($userId),
            ]
        );
    }

    private function calculatePoints(int $userId): int
    {
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
