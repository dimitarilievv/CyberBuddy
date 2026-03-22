<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use App\Models\Badge;

interface BadgeRepositoryInterface
{
    public function getActiveBadges(): iterable;
    public function userHasBadge(User $user, Badge $badge): bool;
    public function attachBadgeToUser(User $user, Badge $badge): void;
}

