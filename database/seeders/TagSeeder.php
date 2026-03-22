<?php
namespace Database\Seeders;
use App\Models\Tag;
use Illuminate\Database\Seeder;
class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Beginner', 'slug' => 'beginner', 'color' => '#22C55E'],
            ['name' => 'Intermediate', 'slug' => 'intermediate', 'color' => '#F59E0B'],
            ['name' => 'Advanced', 'slug' => 'advanced', 'color' => '#EF4444'],
            ['name' => 'Interactive', 'slug' => 'interactive', 'color' => '#8B5CF6'],
            ['name' => 'Video', 'slug' => 'video', 'color' => '#EC4899'],
            ['name' => 'AI Generated', 'slug' => 'ai-generated', 'color' => '#06B6D4'],
            ['name' => 'Scenario', 'slug' => 'scenario', 'color' => '#F97316'],
            ['name' => 'Quiz', 'slug' => 'quiz', 'color' => '#14B8A6'],
            ['name' => 'Mandatory', 'slug' => 'mandatory', 'color' => '#DC2626'],
            ['name' => 'Recommended', 'slug' => 'recommended', 'color' => '#2563EB'],
            ['name' => 'New', 'slug' => 'new', 'color' => '#7C3AED'],
            ['name' => 'Popular', 'slug' => 'popular', 'color' => '#DB2777'],
        ];
        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
