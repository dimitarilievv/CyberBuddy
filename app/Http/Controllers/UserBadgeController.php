<?php

namespace App\Http\Controllers;

use App\Services\UserBadgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBadgeController extends Controller
{
    public function __construct(private UserBadgeService $service) {}

    // List badges for current user
    public function myBadges()
    {
        $userId = Auth::id();
        $userBadges = $this->service->listForUser($userId);

        return view('user_badges.index', compact('userBadges'));
    }

    // Assign a badge to a user (admin or gamification logic)
    public function award(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'badge_id' => 'required|integer|exists:badges,id',
            'reason' => 'nullable|string|max:255',
        ]);

        $this->service->award($data['user_id'], $data['badge_id'], $data['reason'] ?? null);

        return back()->with('success', 'Badge awarded!');
    }

    // Optionally, show all users with a badge
    public function usersWithBadge($badgeId)
    {
        $userBadges = $this->service->listUsersForBadge($badgeId);

        return view('user_badges.users', compact('userBadges'));
    }
}
