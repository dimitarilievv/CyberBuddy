<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use App\Models\QuizAttempt;
use App\Models\ScenarioAttempt;
use App\Models\Enrollment;
use App\Models\Lesson;
use Illuminate\Support\Collection;

class BadgeService
{
    public function __construct(private UserBadgeService $userBadgeService) {}

    /**
     * Check and award badges to user based on criteria
     */
    public function checkAndAward(User $user): Collection  // ✅ Changed return type
    {
        $awardedBadges = collect();  // This returns Illuminate\Support\Collection
        $allBadges = Badge::where('is_active', true)->get();

        foreach ($allBadges as $badge) {
            // Skip if already earned
            if ($this->userEarned($user, $badge)) {
                continue;
            }

            // Check if user meets all criteria for this badge
            if ($this->meetsAllCriteria($user, $badge)) {
                $this->userBadgeService->award(
                    $user->id,
                    $badge->id,
                    "Earned by meeting badge criteria: {$badge->name}"
                );
                $awardedBadges->push($badge);
            }
        }

        return $awardedBadges;
    }

    /**
     * Get all active badges
     */
    public function getActiveBadges(): Collection
    {
        return Badge::where('is_active', true)
            ->orderBy('type')
            ->get();
    }

    /**
     * Get next badge for user (not yet earned)
     */
    public function getNextBadge(User $user): ?Badge
    {
        $earned = $user->badges()->pluck('badge_id')->toArray();

        return Badge::where('is_active', true)
            ->whereNotIn('id', $earned)
            ->first();
    }

    /**
     * Get missions/lessons needed to unlock next badge
     */
    public function getMissionsToNextBadge(User $user, ?Badge $nextBadge = null): int
    {
        $nextBadge = $nextBadge ?? $this->getNextBadge($user);

        if (!$nextBadge || !$nextBadge->criteria) {
            return 0;
        }

        $criteria = $nextBadge->criteria;

        // Check modules_completed first
        if (isset($criteria['modules_completed'])) {
            $required = $criteria['modules_completed'];
            $completed = Enrollment::where('user_id', $user->id)
                ->where('status', 'completed')
                ->count();

            return max(0, $required - $completed);
        }

        // Check lessons_completed
        if (isset($criteria['lessons_completed'])) {
            $required = $criteria['lessons_completed'];
            $completed = Lesson::whereHas('completions', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->count();

            return max(0, $required - $completed);
        }

        return 0;
    }

    /**
     * Check if user already earned a badge
     */
    private function userEarned(User $user, Badge $badge): bool
    {
        return $user->badges()->where('badge_id', $badge->id)->exists();
    }

    /**
     * Check if user meets ALL criteria for a badge
     */
    private function meetsAllCriteria(User $user, Badge $badge): bool
    {
        if (!$badge->criteria || empty($badge->criteria)) {
            return false;
        }

        $criteria = $badge->criteria;

        foreach ($criteria as $criterionKey => $criterionValue) {
            if (!$this->checkCriterion($user, $criterionKey, $criterionValue)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check a single criterion
     */
    private function checkCriterion(User $user, string $key, mixed $value): bool
    {
        return match ($key) {
            'lessons_completed' => $this->checkLessonsCompleted($user, $value),
            'modules_completed' => $this->checkModulesCompleted($user, $value),
            'quiz_score' => $this->checkQuizScore($user, $value),
            'quizzes_passed' => $this->checkQuizzesPassed($user, $value),
            'streak_days' => $this->checkStreakDays($user, $value),
            'perfect_scenarios' => $this->checkPerfectScenarios($user, $value),
            'ai_interactions' => $this->checkAiInteractions($user, $value),
            'reports_made' => $this->checkReportsMade($user, $value),
            default => false,
        };
    }

    private function checkLessonsCompleted(User $user, int $required): bool
    {
        $completed = Lesson::whereHas('completions', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        return $completed >= $required;
    }

    private function checkModulesCompleted(User $user, int $required): bool
    {
        $completed = Enrollment::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        return $completed >= $required;
    }

    private function checkQuizScore(User $user, int $minScore): bool
    {
        $hasHighScore = QuizAttempt::where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('score', '>=', $minScore)
            ->exists();

        return $hasHighScore;
    }

    private function checkQuizzesPassed(User $user, int $required): bool
    {
        $passed = QuizAttempt::where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('score', '>=', 70)
            ->count();

        return $passed >= $required;
    }

    private function checkStreakDays(User $user, int $required): bool
    {
        $streak = $user->current_streak ?? 0;
        return $streak >= $required;
    }

    private function checkPerfectScenarios(User $user, int $required): bool
    {
        $perfect = ScenarioAttempt::where('user_id', $user->id)
            ->where('safety_score', '>=', 90)
            ->count();

        return $perfect >= $required;
    }

    private function checkAiInteractions(User $user, int $required): bool
    {
        $interactions = $user->ai_interactions ?? 0;
        return $interactions >= $required;
    }

    private function checkReportsMade(User $user, mixed $required): bool
    {
        $reports = 0; // TODO: Query your reports table if needed
        return $reports > 0;
    }
}
