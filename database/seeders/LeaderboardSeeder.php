<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Leaderboard;
use App\Models\User;
use App\Models\QuizAttempt;
use App\Models\ScenarioAttempt;
use App\Models\UserBadge;
use App\Models\Enrollment;
use App\Models\Lesson;
use Carbon\Carbon;

class LeaderboardSeeder extends Seeder
{
    public function run(): void
    {
        $periods = ['all_time', 'monthly', 'weekly'];
        $users = User::whereIn('role', ['child', 'parent', 'teacher'])->get();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Skipping LeaderboardSeeder.');
            return;
        }

        foreach ($periods as $period) {
            $leaderboardData = [];

            foreach ($users as $user) {
                $data = $this->calculateUserStats($user, $period);
                if ($data['total_points'] > 0 || $data['modules_completed'] > 0) {
                    $leaderboardData[] = $data;
                }
            }

            // Sort by total points
            usort($leaderboardData, function ($a, $b) {
                return $b['total_points'] <=> $a['total_points'];
            });

            // Assign ranks and save
            $rank = 1;
            foreach ($leaderboardData as $data) {
                Leaderboard::updateOrCreate(
                    [
                        'user_id' => $data['user_id'],
                        'period' => $period,
                    ],
                    [
                        'total_points' => $data['total_points'],
                        'modules_completed' => $data['modules_completed'],
                        'quizzes_passed' => $data['quizzes_passed'],
                        'scenarios_completed' => $data['scenarios_completed'],
                        'badges_earned' => $data['badges_earned'],
                        'current_streak' => $data['current_streak'],
                        'longest_streak' => $data['longest_streak'],
                        'rank' => $rank++,
                    ]
                );
            }
        }

        $this->command->info('Leaderboard entries seeded successfully!');
    }

    private function calculateUserStats($user, string $period): array
    {
        $startDate = $this->getStartDateForPeriod($period);

        // Get enrollments (modules the user is enrolled in)
        $enrollments = Enrollment::where('user_id', $user->id);
        if ($startDate) {
            $enrollments->where('enrolled_at', '>=', $startDate);
        }
        $enrollments = $enrollments->get();

        // Get completed modules (where status is 'completed')
        $completedModules = $enrollments->filter(function ($enrollment) {
            return $enrollment->status === 'completed';
        });

        $completedModulesCount = $completedModules->count();

        // Get all lessons from completed modules
        $completedLessonIds = collect();
        foreach ($completedModules as $module) {
            $lessons = Lesson::where('module_id', $module->module_id)->pluck('id');
            $completedLessonIds = $completedLessonIds->merge($lessons);
        }

        // Get quiz attempts ONLY from lessons that are in completed modules
        $quizAttempts = QuizAttempt::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereHas('quiz.lesson', function ($query) use ($completedLessonIds) {
                $query->whereIn('id', $completedLessonIds);
            });

        if ($startDate) {
            $quizAttempts->where('completed_at', '>=', $startDate);
        }

        $quizAttempts = $quizAttempts->get();

        // Count quizzes passed (only from completed modules)
        $quizzesPassed = $quizAttempts->filter(function ($attempt) {
            return $attempt->score >= 70;
        })->count();

        // Get scenario attempts (these can be independent of modules)
        $scenarioAttempts = ScenarioAttempt::where('user_id', $user->id);
        if ($startDate) {
            $scenarioAttempts->where('created_at', '>=', $startDate);
        }
        $scenarioAttempts = $scenarioAttempts->get();
        $scenariosCompleted = $scenarioAttempts->unique('scenario_id')->count();

        // Get badges earned
        $badges = UserBadge::where('user_id', $user->id);
        if ($startDate) {
            $badges->where('earned_at', '>=', $startDate);
        }
        $badgesCount = $badges->count();

        // Calculate points
        $quizPoints = $quizAttempts->sum(function ($attempt) {
            return $attempt->score * 10;
        });

        $scenarioPoints = $scenarioAttempts->count() * 50;
        $badgePoints = $badgesCount * 100;
        $modulePoints = $completedModulesCount * 200;

        $totalPoints = $quizPoints + $scenarioPoints + $badgePoints + $modulePoints;

        // Calculate streaks
        $streaks = $this->calculateSimpleStreak($user);

        return [
            'user_id' => $user->id,
            'total_points' => $totalPoints,
            'modules_completed' => $completedModulesCount,
            'quizzes_passed' => $quizzesPassed,
            'scenarios_completed' => $scenariosCompleted,
            'badges_earned' => $badgesCount,
            'current_streak' => $streaks['current'],
            'longest_streak' => $streaks['longest'],
        ];
    }

    private function getStartDateForPeriod(string $period): ?Carbon
    {
        return match ($period) {
            'monthly' => Carbon::now()->subDays(30),
            'weekly' => Carbon::now()->subDays(7),
            default => null,
        };
    }

    private function calculateSimpleStreak($user): array
    {
        // Get last 30 days of activity from completed modules and scenarios
        $activityDates = collect();

        // Get quiz attempts from completed modules only
        $enrollments = Enrollment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->get();

        $completedLessonIds = collect();
        foreach ($enrollments as $module) {
            $lessons = Lesson::where('module_id', $module->module_id)->pluck('id');
            $completedLessonIds = $completedLessonIds->merge($lessons);
        }

        QuizAttempt::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereHas('quiz.lesson', function ($query) use ($completedLessonIds) {
                $query->whereIn('id', $completedLessonIds);
            })
            ->where('completed_at', '>=', Carbon::now()->subDays(30))
            ->pluck('completed_at')
            ->each(function ($date) use ($activityDates) {
                $activityDates->push(Carbon::parse($date)->toDateString());
            });

        ScenarioAttempt::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->pluck('created_at')
            ->each(function ($date) use ($activityDates) {
                $activityDates->push(Carbon::parse($date)->toDateString());
            });

        $uniqueDates = $activityDates->unique()->sort();

        $longestStreak = 0;
        $currentStreak = 0;
        $streak = 1;

        $previousDate = null;
        foreach ($uniqueDates as $date) {
            if ($previousDate) {
                $diff = Carbon::parse($date)->diffInDays(Carbon::parse($previousDate));
                if ($diff == 1) {
                    $streak++;
                } else {
                    $streak = 1;
                }
            }
            $longestStreak = max($longestStreak, $streak);
            $previousDate = $date;
        }

        // Check current streak
        if ($uniqueDates->isNotEmpty()) {
            $lastActivity = Carbon::parse($uniqueDates->last());
            if (Carbon::now()->diffInDays($lastActivity) <= 1) {
                $currentStreak = $streak;
            }
        }

        return ['current' => $currentStreak, 'longest' => $longestStreak];
    }
}
