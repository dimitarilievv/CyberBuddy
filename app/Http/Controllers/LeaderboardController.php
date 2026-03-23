<?php

namespace App\Http\Controllers;

use App\Services\LeaderboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function __construct(private LeaderboardService $service) {}

    // Show the leaderboard for a period (weekly, monthly, alltime)
    public function index(Request $request)
    {
        $period = $request->get('period', 'all_time');
        $top = $this->service->getTop(10, $period);

        return view('leaderboard.index', compact('top', 'period'));
    }

    // Show current user's stats
    public function myStats(Request $request)
    {
        $userId = Auth::id();
        $period = $request->get('period', 'all_time');
        $stats = $this->service->getForUser($userId, $period);

        return view('leaderboard.my_stats', compact('stats', 'period'));
    }
}
