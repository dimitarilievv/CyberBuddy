<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserBadge;
use App\Models\User;
use App\Models\Badge;
use App\Models\QuizAttempt;
use App\Models\ScenarioAttempt;
use App\Models\Enrollment;
use Carbon\Carbon;

class UserBadgeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Get users and badges
        $children = User::where('role', 'child')->get();
        $parents = User::where('role', 'parent')->get();
        $admin = User::where('role', 'admin')->first();
        $teachers = User::where('role', 'teacher')->get();
        $badges = Badge::all()->keyBy('slug');

        if ($badges->isEmpty()) {
            $this->command->warn('No badges found. Please run BadgeSeeder first.');
            return;
        }

        $badgesCreated = 0;

        // === 1. GIVE ALL CHILDREN THE "FIRST STEP" BADGE ===
        foreach ($children as $child) {
            if (isset($badges['first-step'])) {
                $result = UserBadge::firstOrCreate([
                    'user_id' => $child->id,
                    'badge_id' => $badges['first-step']->id,
                ], [
                    'earned_at' => Carbon::now()->subDays(rand(1, 30)),
                    'reason' => 'Completed their first lesson! Every expert was once a beginner. Keep going! 🌟',
                ]);

                if ($result->wasRecentlyCreated) {
                    $badgesCreated++;
                }
            }
        }

        // === 2. GIVE BADGES BASED ON QUIZ PERFORMANCE ===
        foreach ($children as $child) {
            // Get all quiz attempts for this child
            $quizAttempts = QuizAttempt::where('user_id', $child->id)
                ->where('status', 'completed')
                ->get();

            if ($quizAttempts->isNotEmpty()) {
                // Count how many quizzes they passed
                $passedQuizzes = $quizAttempts->filter(function ($attempt) {
                    return $attempt->score >= 70;
                })->count();

                // Give "Quiz Master" badge if they passed at least 3 quizzes
                if ($passedQuizzes >= 3 && isset($badges['quiz-master'])) {
                    $result = UserBadge::firstOrCreate([
                        'user_id' => $child->id,
                        'badge_id' => $badges['quiz-master']->id,
                    ], [
                        'earned_at' => Carbon::now()->subDays(rand(1, 15)),
                        'reason' => "Passed {$passedQuizzes} quizzes with flying colors! Your knowledge is growing! 🎓",
                    ]);

                    if ($result->wasRecentlyCreated) {
                        $badgesCreated++;
                    }
                }

                // Give "Perfect Score" badge if they got 100% on any quiz
                $perfectScore = $quizAttempts->contains(function ($attempt) {
                    return $attempt->score >= 100;
                });

                if ($perfectScore && isset($badges['perfect-score'])) {
                    $result = UserBadge::firstOrCreate([
                        'user_id' => $child->id,
                        'badge_id' => $badges['perfect-score']->id,
                    ], [
                        'earned_at' => Carbon::now()->subDays(rand(1, 10)),
                        'reason' => 'Achieved a perfect score on a quiz! You really know your stuff! 🏆',
                    ]);

                    if ($result->wasRecentlyCreated) {
                        $badgesCreated++;
                    }
                }
            }
        }

        // === 3. GIVE BADGES BASED ON SCENARIO PERFORMANCE ===
        foreach ($children as $child) {
            $scenarioAttempts = ScenarioAttempt::where('user_id', $child->id)->get();

            if ($scenarioAttempts->isNotEmpty()) {
                // Check if they made safe choices (safety_score >= 80)
                $safeChoices = $scenarioAttempts->filter(function ($attempt) {
                    return $attempt->safety_score >= 80;
                })->count();

                if ($safeChoices >= 3 && isset($badges['safety-hero'])) {
                    $result = UserBadge::firstOrCreate([
                        'user_id' => $child->id,
                        'badge_id' => $badges['safety-hero']->id,
                    ], [
                        'earned_at' => Carbon::now()->subDays(rand(1, 20)),
                        'reason' => "Made safe choices in {$safeChoices} scenarios! You're becoming a cybersecurity hero! 🦸",
                    ]);

                    if ($result->wasRecentlyCreated) {
                        $badgesCreated++;
                    }
                }
            }
        }

        // === 4. GIVE BADGES BASED ON COMPLETED MODULES ===
        foreach ($children as $child) {
            $completedEnrollments = Enrollment::where('user_id', $child->id)
                ->where('status', 'completed')
                ->count();

            if ($completedEnrollments >= 3 && isset($badges['module-master'])) {
                $result = UserBadge::firstOrCreate([
                    'user_id' => $child->id,
                    'badge_id' => $badges['module-master']->id,
                ], [
                    'earned_at' => Carbon::now()->subDays(rand(1, 25)),
                    'reason' => "Completed {$completedEnrollments} modules! You're on your way to becoming an expert! 📚",
                ]);

                if ($result->wasRecentlyCreated) {
                    $badgesCreated++;
                }
            }

            // Give "Explorer" badge if they completed at least 1 module
            if ($completedEnrollments >= 1 && isset($badges['explorer'])) {
                $result = UserBadge::firstOrCreate([
                    'user_id' => $child->id,
                    'badge_id' => $badges['explorer']->id,
                ], [
                    'earned_at' => Carbon::now()->subDays(rand(1, 15)),
                    'reason' => "Completed your first module! The journey to cybersecurity mastery begins! 🗺️",
                ]);

                if ($result->wasRecentlyCreated) {
                    $badgesCreated++;
                }
            }
        }

        // === 5. GIVE SPECIFIC BADGES TO ANA (HIGH ACHIEVER) ===
        $ana = User::where('email', 'ana@cyberbuddy.mk')->first();
        if ($ana) {
            $specialBadges = ['quiz-master', 'perfect-score', 'safety-hero', 'module-master', 'explorer'];
            foreach ($specialBadges as $badgeSlug) {
                if (isset($badges[$badgeSlug])) {
                    $result = UserBadge::firstOrCreate([
                        'user_id' => $ana->id,
                        'badge_id' => $badges[$badgeSlug]->id,
                    ], [
                        'earned_at' => Carbon::now()->subDays(rand(1, 20)),
                        'reason' => $this->getBadgeReason($badgeSlug, 'ana'),
                    ]);

                    if ($result->wasRecentlyCreated) {
                        $badgesCreated++;
                    }
                }
            }
        }

        // === 6. GIVE SPECIFIC BADGES TO MARKO (LEARNING) ===
        $marko = User::where('email', 'marko@cyberbuddy.mk')->first();
        if ($marko) {
            $markoBadges = ['first-step', 'explorer'];
            foreach ($markoBadges as $badgeSlug) {
                if (isset($badges[$badgeSlug])) {
                    $result = UserBadge::firstOrCreate([
                        'user_id' => $marko->id,
                        'badge_id' => $badges[$badgeSlug]->id,
                    ], [
                        'earned_at' => Carbon::now()->subDays(rand(1, 15)),
                        'reason' => $this->getBadgeReason($badgeSlug, 'marko'),
                    ]);

                    if ($result->wasRecentlyCreated) {
                        $badgesCreated++;
                    }
                }
            }
        }

        // === 7. GIVE BADGES TO TEACHERS ===
        foreach ($teachers as $teacher) {
            // Give "Educator" badge to teachers
            if (isset($badges['educator'])) {
                $result = UserBadge::firstOrCreate([
                    'user_id' => $teacher->id,
                    'badge_id' => $badges['educator']->id,
                ], [
                    'earned_at' => Carbon::now()->subDays(rand(1, 30)),
                    'reason' => 'Thank you for helping students learn about cybersecurity! 👩‍🏫',
                ]);

                if ($result->wasRecentlyCreated) {
                    $badgesCreated++;
                }
            }
        }

        // === 8. GIVE BADGES TO PARENTS ===
        foreach ($parents as $parent) {
            // Give "Protector" badge to parents
            if (isset($badges['protector'])) {
                $result = UserBadge::firstOrCreate([
                    'user_id' => $parent->id,
                    'badge_id' => $badges['protector']->id,
                ], [
                    'earned_at' => Carbon::now()->subDays(rand(1, 20)),
                    'reason' => 'Committed to keeping your family safe online! 🛡️',
                ]);

                if ($result->wasRecentlyCreated) {
                    $badgesCreated++;
                }
            }
        }

        // === 9. GIVE AI FRIEND BADGE TO ADMIN ===
        if ($admin && isset($badges['ai-friend'])) {
            $result = UserBadge::firstOrCreate([
                'user_id' => $admin->id,
                'badge_id' => $badges['ai-friend']->id,
            ], [
                'earned_at' => $now,
                'reason' => 'For being an early adopter and testing AI features! 🤖',
            ]);

            if ($result->wasRecentlyCreated) {
                $badgesCreated++;
            }
        }

        $this->command->info("UserBadgeSeeder complete! Created {$badgesCreated} new badges.");
    }

    private function getBadgeReason(string $badgeSlug, string $user): string
    {
        $reasons = [
            'quiz-master' => [
                'ana' => 'You aced 3+ quizzes! Your knowledge of cybersecurity is outstanding! 🎓',
                'default' => 'Great job on the quizzes! Keep learning and growing! 📚'
            ],
            'perfect-score' => [
                'ana' => 'Perfect score on the quiz! You really understand online safety! 🏆',
                'default' => 'Achieved a perfect score! Well done! ⭐'
            ],
            'safety-hero' => [
                'ana' => 'Always making safe choices in scenarios! You\'re a true cybersecurity hero! 🦸‍♀️',
                'default' => 'Great safety choices! You know how to stay safe online! 🛡️'
            ],
            'module-master' => [
                'ana' => 'Completed multiple modules! You\'re becoming a cybersecurity expert! 🎯',
                'default' => 'Great progress! Keep completing modules! 🚀'
            ],
            'explorer' => [
                'ana' => 'Started your cybersecurity journey! The adventure begins! 🗺️',
                'marko' => 'Took the first step! Every expert was once a beginner! 🌟',
                'default' => 'Explored new cybersecurity topics! Great curiosity! 🔍'
            ],
            'first-step' => [
                'marko' => 'Your first step into the world of cybersecurity! Welcome! 🎉',
                'default' => 'Completed your first lesson! The journey begins! 🌈'
            ],
        ];

        return $reasons[$badgeSlug][$user] ?? $reasons[$badgeSlug]['default'] ?? "Great achievement! Keep up the good work! 🎉";
    }
}
