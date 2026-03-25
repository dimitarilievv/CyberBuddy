<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;
use App\Services\UserBadgeService;
use Illuminate\Support\Facades\Auth;
use App\Services\ModuleService;
use App\Services\LeaderboardService;
use App\Services\UserProgressService;

class ChildDashboardController extends Controller
{
    public function index(ModuleService $moduleService,
                          UserBadgeService $userBadgeService,
                          LeaderboardService $leaderboardService,
                          UserProgressService $userProgressService)
    {
        $user = Auth::user();

        $allProgress = $userProgressService->getUserAllProgress($user->id);


        // Prepare/fake the needed variables (dummy values for demo):
        $modules = $moduleService->getPublishedModules()->take(4); // or whatever logic
        $currentModule = null;  // Your logic here
        $moduleProgress = 0;    // Your logic here
        $safetyLevel = 65;
        $saferThanPercent = 82;


        // Get up to 4 most recent badges for current user, using your UserBadgeService
        $recentBadges = $userBadgeService->listForUser($user->id)->sortByDesc('awarded_at')->take(4);
        $fullLeaderboard = $leaderboardService->getTop(10, 'all_time');
        $recentActivities = collect([]); // Your logic here
        $topFriends = collect([]); // Your logic here
        $myStats = $leaderboardService->getForUser($user->id, 'all_time');
        $statsPeriod = 'all_time';

        // Find the current active module and its lessons
// Let's say "current" module is the first published, or you can use your own logic
        $currentModule = $modules->first();
        $moduleProgress = 0;
        $level = 1;

        if ($currentModule) {
            // Get all lessons in this module
            $lessons = $currentModule->lessons ?? collect();
            $totalLessons = $lessons->count();

            // Count how many of those lessons are completed by the user
            $completed = 0;
            foreach ($lessons as $lesson) {
                // Find progress for this lesson
                $prog = $allProgress->first(function ($p) use ($lesson) {
                    return $p->lesson_id == $lesson->id && $p->status == 'completed';
                });
                if ($prog) $completed++;
            }

            // Each lesson in module is 10%
            if ($totalLessons > 0) {
                $moduleProgress = min(100, round(($completed / $totalLessons) * 100)); // Cap at 100%
            }

            // Level up: Level 2 if 100% complete, otherwise Level 1
            $level = $moduleProgress >= 100 ? 2 : 1;

            // Attach the level to the module object for your Blade
            $currentModule->level = $level;
        }

        return view('child.dashboard', compact(
            'modules',
            'currentModule',
            'moduleProgress','fullLeaderboard',
            'allProgress',
            'safetyLevel',
            'saferThanPercent',
            'recentBadges',
            'recentActivities',
            'topFriends',
            'myStats',
            'statsPeriod'
        ));
    }
}
