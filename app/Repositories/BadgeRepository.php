<?php

namespace App\Repositories;

use App\Models\Badge;
use App\Models\User;
use App\Repositories\Interfaces\BadgeRepositoryInterface;

class BadgeRepository implements BadgeRepositoryInterface
{
    public function getActiveBadges(): iterable
    {
        return Badge::where('is_active', true)->get();
    }

    public function userHasBadge(User $user, Badge $badge): bool
    {
        return $user->badges->contains($badge->id);
    }

    public function attachBadgeToUser(User $user, Badge $badge): void
    {
        $user->badges()->attach($badge->id, [
            'earned_at' => now()
        ]);
    }
}

