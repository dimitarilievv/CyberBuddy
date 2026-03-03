<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // Completion беџови
            [
                'name' => 'Прв Чекор',
                'slug' => 'first-step',
                'description' => 'Заврши ја првата лекција',
                'icon' => '🌟',
                'color' => '#F59E0B',
                'type' => 'completion',
                'criteria' => ['lessons_completed' => 1],
            ],
            [
                'name' => 'Истражувач',
                'slug' => 'explorer',
                'description' => 'Заврши 5 лекции',
                'icon' => '🧭',
                'color' => '#3B82F6',
                'type' => 'completion',
                'criteria' => ['lessons_completed' => 5],
            ],
            [
                'name' => 'Сајбер Херој',
                'slug' => 'cyber-hero',
                'description' => 'Заврши цел модул',
                'icon' => '🦸',
                'color' => '#8B5CF6',
                'type' => 'completion',
                'criteria' => ['modules_completed' => 1],
            ],
            [
                'name' => 'Мајстор на Безбедност',
                'slug' => 'safety-master',
                'description' => 'Заврши 5 модули',
                'icon' => '🏆',
                'color' => '#EF4444',
                'type' => 'completion',
                'criteria' => ['modules_completed' => 5],
            ],

            // Score беџови
            [
                'name' => 'Одличен',
                'slug' => 'excellent',
                'description' => 'Добиј 100% на квиз',
                'icon' => '💯',
                'color' => '#22C55E',
                'type' => 'score',
                'criteria' => ['quiz_score' => 100],
            ],
            [
                'name' => 'Квиз Шампион',
                'slug' => 'quiz-champion',
                'description' => 'Положи 10 квизови',
                'icon' => '🎓',
                'color' => '#14B8A6',
                'type' => 'score',
                'criteria' => ['quizzes_passed' => 10],
            ],

            // Streak беџови
            [
                'name' => 'Посветен',
                'slug' => 'dedicated',
                'description' => 'Учи 3 дена по ред',
                'icon' => '🔥',
                'color' => '#F97316',
                'type' => 'streak',
                'criteria' => ['streak_days' => 3],
            ],
            [
                'name' => 'Неуморен',
                'slug' => 'unstoppable',
                'description' => 'Учи 7 дена по ред',
                'icon' => '⚡',
                'color' => '#DC2626',
                'type' => 'streak',
                'criteria' => ['streak_days' => 7],
            ],
            [
                'name' => 'Легенда',
                'slug' => 'legend',
                'description' => 'Учи 30 дена по ред',
                'icon' => '👑',
                'color' => '#7C3AED',
                'type' => 'streak',
                'criteria' => ['streak_days' => 30],
            ],

            // Special беџови
            [
                'name' => 'Сценарио Стратег',
                'slug' => 'scenario-strategist',
                'description' => 'Заврши 5 сценарија со максимален скор',
                'icon' => '🎯',
                'color' => '#06B6D4',
                'type' => 'special',
                'criteria' => ['perfect_scenarios' => 5],
            ],
            [
                'name' => 'AI Пријател',
                'slug' => 'ai-friend',
                'description' => 'Направи 10 AI интеракции',
                'icon' => '🤖',
                'color' => '#EC4899',
                'type' => 'special',
                'criteria' => ['ai_interactions' => 10],
            ],
            [
                'name' => 'Помошник',
                'slug' => 'helper',
                'description' => 'Пријави несоодветна содржина',
                'icon' => '🤝',
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
