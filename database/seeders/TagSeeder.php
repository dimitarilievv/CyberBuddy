<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Почетник', 'slug' => 'beginner', 'color' => '#22C55E'],
            ['name' => 'Среден', 'slug' => 'intermediate', 'color' => '#F59E0B'],
            ['name' => 'Напреден', 'slug' => 'advanced', 'color' => '#EF4444'],
            ['name' => 'Интерактивно', 'slug' => 'interactive', 'color' => '#8B5CF6'],
            ['name' => 'Видео', 'slug' => 'video', 'color' => '#EC4899'],
            ['name' => 'AI Генерирано', 'slug' => 'ai-generated', 'color' => '#06B6D4'],
            ['name' => 'Сценарио', 'slug' => 'scenario', 'color' => '#F97316'],
            ['name' => 'Квиз', 'slug' => 'quiz', 'color' => '#14B8A6'],
            ['name' => 'Задолжително', 'slug' => 'mandatory', 'color' => '#DC2626'],
            ['name' => 'Препорачано', 'slug' => 'recommended', 'color' => '#2563EB'],
            ['name' => 'Ново', 'slug' => 'new', 'color' => '#7C3AED'],
            ['name' => 'Популарно', 'slug' => 'popular', 'color' => '#DB2777'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
