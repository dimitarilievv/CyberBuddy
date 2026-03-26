<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserProgress;
use App\Models\QuizAttempt;
use App\Models\ScenarioAttempt;
use App\Models\AIInteraction;
use App\Repositories\Interfaces\BadgeRepositoryInterface;

class BadgeService
{
    protected BadgeRepositoryInterface $badgeRepository;

    public function __construct(BadgeRepositoryInterface $badgeRepository)
    {
        $this->badgeRepository = $badgeRepository;
    }

    public function checkAndAward(User $user): array
    {
        $awarded = [];

        $user->loadMissing('badges');

        $badges = $this->badgeRepository->getActiveBadges();

        foreach ($badges as $badge) {
            if ($this->badgeRepository->userHasBadge($user, $badge)) {
                continue;
            }

            if ($this->meetsCriteria($user, $badge)) {
                $this->badgeRepository->attachBadgeToUser($user, $badge);

                $awarded[] = $badge;
            }
        }

        return $awarded;
    }

    public function getActiveBadges()
    {
        return $this->badgeRepository->getActiveBadges();
    }

    private function meetsCriteria(User $user, $badge): bool
    {
        $criteria = $badge->criteria ?? [];

        if (isset($criteria['lessons_completed'])) {
            $count = UserProgress::where('user_id', $user->id)
                ->where('status', 'completed')
                ->count();

            if ($count < $criteria['lessons_completed']) return false;
        }

        if (isset($criteria['modules_completed'])) {
            $count = $user->enrollments()
                ->where('status', 'completed')
                ->count();

            if ($count < $criteria['modules_completed']) return false;
        }

        if (isset($criteria['quiz_score'])) {
            $hasScore = QuizAttempt::where('user_id', $user->id)
                ->where('score', '>=', $criteria['quiz_score'])
                ->exists();

            if (!$hasScore) return false;
        }

        if (isset($criteria['quizzes_passed'])) {
            $count = QuizAttempt::where('user_id', $user->id)
                ->where('status', 'passed')
                ->count();

            if ($count < $criteria['quizzes_passed']) return false;
        }

        if (isset($criteria['perfect_scenarios'])) {
            $count = ScenarioAttempt::where('user_id', $user->id)
                ->where('safety_score', 100)
                ->count();

            if ($count < $criteria['perfect_scenarios']) return false;
        }

        if (isset($criteria['ai_interactions'])) {
            $count = AIInteraction::where('user_id', $user->id)->count();

            if ($count < $criteria['ai_interactions']) return false;
        }

        return true;
    }
}
