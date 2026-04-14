<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class UserStatsService
{
    /**
     * Add points to user
     */
    public function addPoints(User $user, int $points): void
    {
        $user->increment('total_points', $points);
    }

    /**
     * Update streak (call this after lesson completion)
     */
    public function updateStreak(User $user): void
    {
        $today = Carbon::today();
        $lastActivityDate = $user->last_activity_at ? Carbon::parse($user->last_activity_at)->toDateString() : null;
        $todayString = $today->toDateString();

        // If activity was today, don't change streak
        if ($lastActivityDate === $todayString) {
            return;
        }

        // If activity was yesterday, increment streak
        if ($lastActivityDate === $today->subDay()->toDateString()) {
            $user->increment('current_streak', 1);
        } else {
            // Reset streak if it's been more than 1 day
            $user->update(['current_streak' => 1]);
        }

        // Update last activity date
        $user->update(['last_activity_at' => now()]);
    }

    /**
     * Increment AI interactions
     */
    public function incrementAiInteractions(User $user): void
    {
        $user->increment('ai_interactions', 1);
    }
}
