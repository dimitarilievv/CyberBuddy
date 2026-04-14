<?php

namespace App\Http\Controllers;

use App\Services\BadgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Leaderboard;

class BadgeController extends Controller
{
    protected BadgeService $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    public function index()
    {
        $user = Auth::user();

        // ✅ Refresh user data from database to get latest stats
        $user->refresh();

        // Prefer showing leaderboard totals when available (all_time period)
        $leaderboardEntry = Leaderboard::where('user_id', $user->id)
            ->where('period', 'all_time')
            ->first();

        $leaderboardPoints = $leaderboardEntry ? $leaderboardEntry->total_points : ($user->total_points ?? 0);

        $badges = $this->badgeService->getActiveBadges();
        $userBadgeSlugs = $user->badges()->pluck('slug')->toArray();
        $earnedCount = count($userBadgeSlugs);
        $totalCount = $badges->count();
        $progressPct = $totalCount > 0 ? round(($earnedCount / $totalCount) * 100) : 0;

        $nextBadge = $this->badgeService->getNextBadge($user);

        $streakDays = $leaderboardEntry ? $leaderboardEntry->current_streak : ($user->current_streak ?? 0);
        // Prefer leaderboard total points when available so badges page matches leaderboard
        $totalPoints = $leaderboardEntry ? $leaderboardEntry->total_points : ($user->total_points ?? 0);
        $aiInteractions = $user->ai_interactions ?? 0;

        return view('badges.index', compact(
            'badges',
            'userBadgeSlugs',
            'earnedCount',
            'totalCount',
            'progressPct',
            'nextBadge',
            'streakDays',
            'totalPoints',
            'aiInteractions',
            'leaderboardEntry',
            'leaderboardPoints'
        ));
    }

    public function checkAndAward()
    {
        $user = Auth::user();
        $awarded = $this->badgeService->checkAndAward($user);

        if ($awarded->isNotEmpty()) {
            return redirect()->route('badges.index')->with('awarded', $awarded);
        }

        return redirect()->route('badges.index')->with('info', 'No new badges earned yet.');
    }
}
