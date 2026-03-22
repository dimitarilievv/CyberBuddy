<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // Completion badges
            [
                'name' => 'First Step',
                'slug' => 'first-step',
                'description' => 'Complete your first lesson',
                'icon' => 'star',
                'color' => '#F59E0B',
                'type' => 'completion',
                'criteria' => ['lessons_completed' => 1],
            ],
            [
                'name' => 'Explorer',
                'slug' => 'explorer',
                'description' => 'Complete 5 lessons',
                'icon' => 'compass',
                'color' => '#3B82F6',
                'type' => 'completion',
                'criteria' => ['lessons_completed' => 5],
            ],
            [
                'name' => 'Cyber Hero',
                'slug' => 'cyber-hero',
                'description' => 'Complete a full module',
                'icon' => 'hero',
                'color' => '#8B5CF6',
                'type' => 'completion',
                'criteria' => ['modules_completed' => 1],
            ],
            [
                'name' => 'Safety Master',
                'slug' => 'safety-master',
                'description' => 'Complete 5 modules',
                'icon' => 'trophy',
                'color' => '#EF4444',
                'type' => 'completion',
                'criteria' => ['modules_completed' => 5],
            ],

            // Score badges
            [
                'name' => 'Excellent',
                'slug' => 'excellent',
                'description' => 'Score 100% on a quiz',
                'icon' => 'score-100',
                'color' => '#22C55E',
                'type' => 'score',
                'criteria' => ['quiz_score' => 100],
            ],
            [
                'name' => 'Quiz Champion',
                'slug' => 'quiz-champion',
                'description' => 'Pass 10 quizzes',
                'icon' => 'cap',
                'color' => '#14B8A6',
                'type' => 'score',
                'criteria' => ['quizzes_passed' => 10],
            ],

            // Streak badges
            [
                'name' => 'Dedicated',
                'slug' => 'dedicated',
                'description' => 'Study 3 days in a row',
                'icon' => 'fire',
                'color' => '#F97316',
                'type' => 'streak',
                'criteria' => ['streak_days' => 3],
            ],
            [
                'name' => 'Unstoppable',
                'slug' => 'unstoppable',
                'description' => 'Study 7 days in a row',
                'icon' => 'bolt',
                'color' => '#DC2626',
                'type' => 'streak',
                'criteria' => ['streak_days' => 7],
            ],
            [
                'name' => 'Legend',
                'slug' => 'legend',
                'description' => 'Study 30 days in a row',
                'icon' => 'crown',
                'color' => '#7C3AED',
                'type' => 'streak',
                'criteria' => ['streak_days' => 30],
            ],

            // Special badges
            [
                'name' => 'Scenario Strategist',
                'slug' => 'scenario-strategist',
                'description' => 'Complete 5 scenarios with a perfect score',
                'icon' => 'target',
                'color' => '#06B6D4',
                'type' => 'special',
                'criteria' => ['perfect_scenarios' => 5],
            ],
            [
                'name' => 'AI Friend',
                'slug' => 'ai-friend',
                'description' => 'Make 10 AI interactions',
                'icon' => 'robot',
                'color' => '#EC4899',
                'type' => 'special',
                'criteria' => ['ai_interactions' => 10],
            ],
            [
                'name' => 'Helper',
                'slug' => 'helper',
                'description' => 'Report inappropriate content',
                'icon' => 'handshake',
                'color' => '#10B981',
                'type' => 'special',
                'criteria' => ['reports_made' => 1],
            ],
        ];

        foreach ($badges as $badge) {
            Badge::create($badge);
        }
    }
}
