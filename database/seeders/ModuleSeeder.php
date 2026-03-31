<?php
namespace Database\Seeders;
use App\Models\Module;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('email', 'teacher_marija@cyberbuddy.mk')->first();
        $modules = [
            [
                'title' => 'Strong Passwords - Your First Defense',
                'slug' => 'strong-passwords',
                'description' => 'Learn why passwords matter and how to create strong passwords that are hard to guess. You will learn memory tricks for complex passwords.',
                'audience' => 'child',
                'difficulty' => 'beginner',
                'age_group' => '10-13',
                'category_slug' => 'passwords-auth',
                'estimated_duration' => 20,
                'sort_order' => 1,
                'is_published' => true,
                'tags' => ['beginner', 'interactive', 'recommended'],
                'thumbnail' => '/img/modules/basic.gif',
            ],
            [
                'title' => 'Recognize Phishing',
                'slug' => 'recognize-phishing',
                'description' => 'Learn how to spot fake emails, messages, and websites that try to steal your data.',
                'audience' => 'child',
                'difficulty' => 'intermediate',
                'age_group' => '10-13',
                'category_slug' => 'phishing-scams',
                'estimated_duration' => 30,
                'sort_order' => 2,
                'is_published' => true,
                'tags' => ['intermediate', 'scenario', 'popular'],
                'thumbnail' => '/img/modules/basic2.gif',
            ],
            [
                'title' => 'Social Media Safety',
                'slug' => 'social-media-safety',
                'description' => 'Learn how to use Instagram, TikTok, and other social networks safely without risking your privacy.',
                'audience' => 'child',
                'difficulty' => 'beginner',
                'age_group' => '10-13',
                'category_slug' => 'profile-privacy',
                'estimated_duration' => 25,
                'sort_order' => 3,
                'is_published' => true,
                'tags' => ['beginner', 'popular', 'new'],
                'thumbnail' => '/img/modules/basic4.gif',
            ],
            [
                'title' => 'Stop Cyberbullying',
                'slug' => 'stop-cyberbullying',
                'description' => 'Learn how to recognize cyberbullying, what to do if you or someone else is targeted, and how to be a good digital citizen.',
                'audience' => 'child',
                'difficulty' => 'beginner',
                'age_group' => '10-13',
                'category_slug' => 'recognizing-bullying',
                'estimated_duration' => 25,
                'sort_order' => 4,
                'is_published' => true,
                'tags' => ['beginner', 'mandatory', 'scenario'],
                'thumbnail' => '/img/modules/basic3.gif',
            ],
            [
                'title' => 'Safe Gaming',
                'slug' => 'safe-gaming',
                'description' => 'Learn how to play online games safely, protect your accounts, and spot risks in gaming communities.',
                'audience' => 'child',
                'difficulty' => 'beginner',
                'age_group' => '10-13',
                'category_slug' => 'gaming-safety',
                'estimated_duration' => 20,
                'sort_order' => 5,
                'is_published' => true,
                'tags' => ['beginner', 'interactive', 'new'],
                'thumbnail' => '/img/modules/basic.gif',
            ],
            [
                'title' => 'Parent Guide: Online Safety',
                'slug' => 'parent-guide-online-safety',
                'description' => 'Learn how to protect your children online, set parental controls, and build healthy conversations about digital safety.',
                'audience' => 'parent',
                'difficulty' => 'beginner',
                'age_group' => '18+',
                'category_slug' => 'online-safety',
                'estimated_duration' => 30,
                'sort_order' => 6,
                'is_published' => true,
                'tags' => ['recommended'],
                'thumbnail' => '/img/modules/basic3.gif',
            ],
            [
                'title' => 'Device Security',
                'slug' => 'device-security',
                'description' => 'Learn how to keep your devices safe from hackers and viruses.',
                'audience' => 'child',
                'difficulty' => 'beginner',
                'age_group' => '10-13',
                'category_slug' => 'device-safety',
                'estimated_duration' => 20,
                'sort_order' => 7,
                'is_published' => true,
                'tags' => ['beginner', 'interactive', 'recommended'],
                'thumbnail' => '/img/modules/basic4.gif',
            ],
            [
                'title' => 'Privacy Settings',
                'slug' => 'privacy-settings',
                'description' => 'Understand privacy settings and how to protect your personal information online.',
                'audience' => 'child',
                'difficulty' => 'beginner',
                'age_group' => '10-13',
                'category_slug' => 'privacy',
                'estimated_duration' => 15,
                'sort_order' => 8,
                'is_published' => true,
                'tags' => ['beginner', 'privacy', 'interactive'],
                'thumbnail' => '/img/modules/basic3.gif',
            ],
            [
                'title' => 'Digital Footprint',
                'slug' => 'digital-footprint',
                'description' => 'Learn what a digital footprint is and how to manage it.',
                'audience' => 'child',
                'difficulty' => 'beginner',
                'age_group' => '10-13',
                'category_slug' => 'privacy',
                'estimated_duration' => 15,
                'sort_order' => 9,
                'is_published' => true,
                'tags' => ['beginner', 'privacy', 'recommended'],
                'thumbnail' => '/img/modules/basic4.gif',
            ],
            [
                'title' => 'Fake News Detection',
                'slug' => 'fake-news-detection',
                'description' => 'Learn how to spot fake news and misinformation online.',
                'audience' => 'child',
                'difficulty' => 'intermediate',
                'age_group' => '10-13',
                'category_slug' => 'media-literacy',
                'estimated_duration' => 20,
                'sort_order' => 10,
                'is_published' => true,
                'tags' => ['intermediate', 'media', 'critical-thinking'],
                'thumbnail' => '/img/modules/basic.gif',

            ],
        ];
        foreach ($modules as $moduleData) {
            $category = Category::where('slug', $moduleData['category_slug'])->first();
            if (!$category) {
                // Create the category if it does not exist
                $category = Category::create([
                    'slug' => $moduleData['category_slug'],
                    'name' => ucwords(str_replace(['-', '_'], ' ', $moduleData['category_slug'])), // FIXED: use 'name' instead of 'title'
                    'description' => $moduleData['category_slug'] . ' category',
                ]);
            }
            $tagSlugs = $moduleData['tags'];
            unset($moduleData['category_slug'], $moduleData['tags']);
            $moduleData['category_id'] = $category->id;
            $moduleData['author_id'] = $teacher->id;
            $moduleData['published_at'] = now();
            $module = Module::create($moduleData);
            $tags = Tag::whereIn('slug', $tagSlugs)->get();
            $module->tags()->attach($tags);
        }
    }
}
