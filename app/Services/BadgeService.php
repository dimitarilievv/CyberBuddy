<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use App\Models\UserProgress;
use App\Models\QuizAttempt;
use App\Models\ScenarioAttempt;

class BadgeService
{
    public function checkAndAward(User $user): array
    {
        $awarded = [];

        $badges = Badge::where('is_active', true)->get();

        foreach ($badges as $badge) {
            if ($user->badges->contains($badge->id)) {
                continue;
            }

            if ($this->meetsCriteria($user, $badge)) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
                $awarded[] = $badge;
            }
        }

        return $awarded;
    }

    private function meetsCriteria(User $user, Badge $badge): bool
    {
        $criteria = $badge->criteria;

        if (isset($criteria['lessons_completed'])) {
            $count = UserProgress::where('user_id', $user->id)->where('status', 'completed')->count();
            if ($count < $criteria['lessons_completed']) return false;
        }

        if (isset($criteria['modules_completed'])) {
            $count = $user->enrollments()->where('status', 'completed')->count();
            if ($count < $criteria['modules_completed']) return false;
        }

        if (isset($criteria['quiz_score'])) {
            $hasPerfect = QuizAttempt::where('user_id', $user->id)
                ->where('percentage', '>=', $criteria['quiz_score'])
                ->exists();
            if (!$hasPerfect) return false;
        }

        if (isset($criteria['quizzes_passed'])) {
            $count = QuizAttempt::where('user_id', $user->id)->where('passed', true)->count();
            if ($count < $criteria['quizzes_passed']) return false;
        }

        if (isset($criteria['perfect_scenarios'])) {
            $count = ScenarioAttempt::where('user_id', $user->id)
                ->where('safety_score', 100)
                ->count();
            if ($count < $criteria['perfect_scenarios']) return false;
        }

        if (isset($criteria['ai_interactions'])) {
            $count = \App\Models\AiInteraction::where('user_id', $user->id)->count();
            if ($count < $criteria['ai_interactions']) return false;
        }

        return true;
    }
}
