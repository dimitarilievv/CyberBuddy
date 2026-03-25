<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserBadgeService;
use App\Services\ModuleService;
use App\Services\UserProgressService;
use Carbon\Carbon;

class ParentDashboardController extends Controller
{
    public function index(Request $request, UserBadgeService $badgeService, ModuleService $moduleService, UserProgressService $progressService)
    {
        $parent = $request->user();
        $chartDays = intval($request->input('chart_days', 7)); // fallback to 7 if not given
        if (!in_array($chartDays, [7, 30])) $chartDays = 7;

        $children = $parent->children()->with([
            'enrollments.module.category',
            'profile',
            'userBadges.badge',
            'userProgress.lesson.module.category'
        ])->get();

        // Build N day date keys for chart
        $xpPerDay = [];
        for ($i = $chartDays - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $xpPerDay[$date->format('Y-m-d')] = 0;
        }

        $allBadges = collect();
        $allActivities = collect();

        // Skill proficiency per category
        $categories = $moduleService->getPublishedModules()->map(function($m) { return $m->category->name ?? 'Other'; })->unique()->values();
        $proficiency = [];
        foreach ($categories as $category) {
            $proficiency[$category] = 0;
        }
        $modulesByCategory = [];

        foreach($children as $child) {
            $childBadges = $child->userBadges ?? collect();
            $allBadges = $allBadges->concat($childBadges);
            foreach ($childBadges as $ub) {
                $when = $ub->earned_at ?? $ub->awarded_at ?? $ub->created_at;
                if ($when) {
                    $day = Carbon::parse($when)->format('Y-m-d');
                    if (isset($xpPerDay[$day])) {
                        $xpPerDay[$day] += 300;
                    }
                }
                $allActivities->push([
                    'type' => 'badge_earned',
                    'child' => $child,
                    'badge' => $ub->badge,
                    'created_at' => $when
                ]);
            }
            foreach($child->userProgress as $progress) {
                if($progress->status == 'completed' && $progress->created_at) {
                    $day = Carbon::parse($progress->created_at)->format('Y-m-d');
                    if (isset($xpPerDay[$day])) {
                        $xpPerDay[$day] += 300;
                    }
                    $allActivities->push([
                        'type' => 'lesson_complete',
                        'child' => $child,
                        'lesson' => $progress->lesson,
                        'module' => $progress->lesson->module ?? null,
                        'created_at' => $progress->completed_at,
                    ]);
                }
            }

            // Skill Proficiency
            foreach($child->enrollments as $enrollment) {
                $mod = $enrollment->module;
                $cat = $mod->category->name ?? 'Other';
                $modulesByCategory[$cat][] = $mod->id;
                if ($enrollment->progress_percentage > 0) {
                    $proficiency[$cat] += $enrollment->progress_percentage;
                }
            }
        }

        // Flatten/protect category arrays & average proficiency
        foreach ($proficiency as $cat => &$score) {
            $count = isset($modulesByCategory[$cat]) ? count(array_unique($modulesByCategory[$cat])) : 1;
            $score = $count ? intval(round($score/$count)) : 0;
        }

        $totalBadges = $allBadges->count();
        $totalXP = $totalBadges * 300;
        $modulesReady = $moduleService->getPublishedModules()->count();

        // Take only the most recent 5 for Recent Activity, sort by created_at desc
        $recentActivities = $allActivities->sortByDesc('created_at')->take(5);

        // Safety insights - static, you can make dynamic based on data if you want
        $safetyInsights = [
            [
                'title' => 'Phishing Risk',
                'icon'  => '⚠️',
                'desc'  => 'A child struggled with “Suspicious Email” scenarios. Review email safety tips.',
            ],
            [
                'title' => 'Privacy Star',
                'icon'  => '🌟',
                'desc'  => 'A child scored 100% on “Social Media Privacy” module. Great awareness!',
            ],
            [
                'title' => 'Next Milestone',
                'icon' => '🎯',
                'desc' => 'Completing “MFA Basics” will earn the Security Lock badge. Only 1 lesson away!',
            ]
        ];

        // Get all published modules and group by category
        $modules = $moduleService->getPublishedModules();
        $categories = $modules->map(fn($m) => $m->category->name ?? 'Other')->unique()->values();

        $proficiency = [];
        foreach ($categories as $category) {
            $proficiency[$category] = [
                'completed' => 0,
                'total' => 0
            ];
        }

// Get all progress for all children (grouped)
        foreach ($children as $child) {
            foreach ($modules as $module) {
                $cat = $module->category->name ?? 'Other';
                $lessons = $module->lessons ?? collect();
                $totalLessons = $lessons->count();
                $completed = 0;
                // For each lesson, check if ANY of this child’s userProgress is completed for that lesson
                foreach ($lessons as $lesson) {
                    $hasCompleted = $child->userProgress->first(fn($p) =>
                        $p->lesson_id == $lesson->id && $p->status == 'completed'
                    );
                    if ($hasCompleted) $completed++;
                }
                $proficiency[$cat]['completed'] += $completed;
                $proficiency[$cat]['total'] += $totalLessons;
            }
        }

// Now, get percent proficiency per category
        $proficiencyPercents = [];
        foreach ($proficiency as $cat => $arr) {
            if ($arr['total'] > 0) {
                $proficiencyPercents[$cat] = intval(round(($arr['completed'] / $arr['total']) * 100));
            } else {
                $proficiencyPercents[$cat] = 0; // No lessons in this category
            }
        }
        // Safety tip of the day
        $safetyTip = "Remind kids that websites should never ask for a home address or phone number without a parent present.";

        return view('parent.dashboard', compact(
            'parent',
            'children',
            'totalBadges',
            'totalXP',
            'modulesReady',
            'recentActivities',
            'proficiency',
            'xpPerDay',
            'safetyInsights',
            'safetyTip',
            'xpPerDay',
            'chartDays',
            'proficiencyPercents',
        ));
    }

    public function addChild(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $parent = $request->user();
        $child = \App\Models\User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => bcrypt($request->password),
            'role'       => 'child',
            'parent_id'  => $parent->id,
            'is_active'  => true,
        ]);

        // Optionally: create user profile etc.

        return redirect()->route('parent.dashboard')->with('success', 'Child created successfully!');
    }

    public function attachChild(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $parent = $request->user();
        $child = \App\Models\User::where('email', $request->email)->where('role', 'child')->first();

        if ($child) {
            $child->parent_id = $parent->id;
            $child->save();
            return back()->with('success', 'Existing child linked!');
        } else {
            return back()->with('error', 'No child account found with that email.');
        }
    }
}
