<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Repositories\Interfaces\ModuleRepositoryInterface;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Services\UserProgressService;
use App\Services\BadgeService;

class ModuleService
{
    private UserProgressService $userProgressService;
    private BadgeService $badgeService;
    public function __construct(
        private ModuleRepositoryInterface $moduleRepo,
        private EnrollmentRepositoryInterface $enrollmentRepo,
        UserProgressService $userProgressService,
        BadgeService $badgeService,
    ) {
        $this->userProgressService = $userProgressService;
        $this->badgeService = $badgeService;
    }

    public function getPublishedModules()
    {
        return $this->moduleRepo->getPublished();
    }

    public function getModuleBySlug(string $slug)
    {
        return $this->moduleRepo->findBySlug($slug);
    }

    public function getModulesForUser(int $userId, string $audience)
    {
        return $this->moduleRepo->getByAudience($audience);
    }

    public function enrollUser(int $userId, int $moduleId)
    {
        return $this->enrollmentRepo->enroll($userId, $moduleId);
    }

    public function isUserEnrolled(int $userId, int $moduleId): bool
    {
        return $this->enrollmentRepo->isEnrolled($userId, $moduleId);
    }

    public function getPopularModules(int $limit = 5)
    {
        return $this->moduleRepo->getPopular($limit);
    }

    public function createModule(array $data)
    {
        return \App\Models\Module::create($data);
    }

    public function updateModule(\App\Models\Module $module, array $data)
    {
        $module->update($data);
        return $module;
    }

    public function deleteModule(\App\Models\Module $module)
    {
        return $module->delete();
    }
    public function getAllModules()
    {
        return $this->moduleRepo->getAllModules();
    }
    public function getPublishedModulesPaginated($perPage = 9)
    {
        return $this->moduleRepo->getPublishedPaginated($perPage);
    }
    public function getTotalMissionCount(): int
    {
        return $this->moduleRepo->getPublished()->count();
    }

    public function getUserMissionsCompletedCount(int $userId): int
    {
        return Enrollment::where('user_id', $userId)
            ->whereNotNull('completed_at')
            ->count();
    }
    public function getUserModuleProgress(int $userId, int $moduleId)
    {
        $module = \App\Models\Module::with('lessons')->find($moduleId);
        if (!$module) {
            return [
                'status' => 'not_started',
                'progress' => 0,
            ];
        }
        $lessonIds = $module->lessons->pluck('id');
        if ($lessonIds->isEmpty()) {
            return [
                'status' => 'not_started',
                'progress' => 0,
            ];
        }
        $completedLessons = $this->userProgressService->getUserAllProgress($userId)
            ->whereIn('lesson_id', $lessonIds)
            ->where('status', 'completed')
            ->count();
        $progress = round(($completedLessons / $lessonIds->count()) * 100);
        $status = 'not_started';
        if ($completedLessons === $lessonIds->count()) {
            $status = 'completed';
        } elseif ($completedLessons > 0) {
            $status = 'in_progress';
        }
        return [
            'status' => $status,
            'progress' => $progress,
        ];
    }
    public function getUserCompletedModulesCount(int $userId): int
    {
        $modules = \App\Models\Module::where('is_published', true)->get();
        $completedCount = 0;
        foreach ($modules as $module) {
            $lessonIds = $module->lessons()->pluck('id');
            if ($lessonIds->isEmpty()) continue;
            $completedLessons = $this->userProgressService->getUserAllProgress($userId)
                ->whereIn('lesson_id', $lessonIds)
                ->where('status', 'completed')
                ->count();
            if ($completedLessons === $lessonIds->count()) {
                $completedCount++;
            }
        }
        return $completedCount;
    }

    /**
     * Returns all data needed for the modules index view (missions, progress, badges, leaderboard, filter, etc)
     */
    public function getDashboardData($user, $filter, $perPage, $queryParams = [])
    {
        $modulesQuery = \App\Models\Module::where('is_published', true)
            ->with(['category', 'tags', 'author'])
            ->withCount('lessons')
            ->orderBy('sort_order');

        if ($user) {
            $userEnrollments = $this->enrollmentRepo->getUserEnrollments($user->id);
            $completedModuleIds = $userEnrollments->where('status', 'completed')->pluck('module_id');
            $inProgressModuleIds = $userEnrollments->where('status', '!=', 'completed')->pluck('module_id');
            $enrolledModuleIds = $userEnrollments->pluck('module_id');

            if ($filter === 'completed') {
                $modulesQuery->whereIn('id', $completedModuleIds);
            } elseif ($filter === 'in_progress') {
                $modulesQuery->whereIn('id', $inProgressModuleIds);
            } elseif ($filter === 'not_started') {
                $modulesQuery->whereNotIn('id', $enrolledModuleIds);
            }
        } else {
            if ($filter !== 'all') {
                $filter = 'all';
            }
        }

        $modules = $modulesQuery->paginate($perPage)->appends($queryParams);
        $missionsCompleted = 0;
        $totalMissions = $this->getTotalMissionCount();
        if ($user) {
            $user->loadMissing('badges', 'leaderboard');
            $missionsCompleted = $this->getUserCompletedModulesCount($user->id);
        }

        // Next badge milestone logic
        $nextBadge = null;
        $missionsToNextBadge = null;
        $badgeName = null;
        if ($user) {
            $badges = $this->badgeService->getActiveBadges();
            foreach ($badges as $badge) {
                if (!$user->badges->contains($badge->id) && isset($badge->criteria['modules_completed'])) {
                    $nextBadge = $badge;
                    $missionsToNextBadge = max(0, $badge->criteria['modules_completed'] - $missionsCompleted);
                    $badgeName = $badge->name;
                    break;
                }
            }
        }

        // Leaderboard stats for user
        $leaderboardStats = null;
        if ($user && $user->leaderboard) {
            $leaderboardStats = [
                'points' => $user->leaderboard->total_points,
                'streak' => $user->leaderboard->current_streak,
                'badges' => $user->leaderboard->badges_earned,
            ];
        }

        return [
            'modules' => $modules,
            'missionsCompleted' => $missionsCompleted,
            'totalMissions' => $totalMissions,
            'user' => $user,
            'filter' => $filter,
            'nextBadge' => $nextBadge,
            'missionsToNextBadge' => $missionsToNextBadge,
            'badgeName' => $badgeName,
            'leaderboardStats' => $leaderboardStats,
        ];
    }
}
