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
        $teacher = User::where('email', 'teacher@cyberbuddy.mk')->first();

        $modules = [
            [
                'title' => 'Силни Лозинки - Твојата Прва Одбрана',
                'slug' => 'strong-passwords',
                'description' => 'Научи зошто лозинките се важни и како да креираш силна лозинка што никој не може да ја погоди. Ќе научиш трикови за паметење на сложени лозинки.',
                'audience' => 'child',
                'difficulty' => 'beginner',
                'age_group' => '10-13',
                'category_slug' => 'passwords-auth',
                'estimated_duration' => 20,
                'sort_order' => 1,
                'is_published' => true,
                'tags' => ['beginner', 'interactive', 'recommended'],
            ],
            [
                'title' => 'Препознај го Фишингот',
                'slug' => 'recognize-phishing',
                'description' => 'Научи како да препознаеш лажни емаили, пораки и веб страници кои се обидуваат да ги украдат твоите податоци.',
                'audience' => 'child',
                'difficulty' => 'intermediate',
                'age_group' => '10-13',
                'category_slug' => 'phishing-scams',
                'estimated_duration' => 30,
                'sort_order' => 2,
                'is_published' => true,
                'tags' => ['intermediate', 'scenario', 'popular'],
            ],
            [
                'title' => 'Безбедност на Социјални Мрежи',
                'slug' => 'social-media-safety',
                'description' => 'Научи како безбедно да ги користиш Instagram, TikTok и другите социјални мрежи без да ја загрозиш твојата приватност.',
                'audience' => 'child',
                'difficulty' => 'beginner',
                'age_group' => '10-13',
                'category_slug' => 'profile-privacy',
                'estimated_duration' => 25,
                'sort_order' => 3,
                'is_published' => true,
                'tags' => ['beginner', 'popular', 'new'],
            ],
            [
                'title' => 'Стоп за Сајбер Булинг',
                'slug' => 'stop-cyberbullying',
                'description' => 'Научи како да го препознаеш сајбер булингот, што да направиш ако ти или некој друг е жртва, и како да бидеш добар дигитален граѓанин.',
                'audience' => 'child',
                'difficulty' => 'beginner',
                'age_group' => '10-13',
                'category_slug' => 'recognizing-bullying',
                'estimated_duration' => 25,
                'sort_order' => 4,
                'is_published' => true,
                'tags' => ['beginner', 'mandatory', 'scenario'],
            ],
            [
                'title' => 'Безбедно Гејминг',
                'slug' => 'safe-gaming',
                'description' => 'Научи како безбедно да играш онлајн игри, да ги заштитиш своите акаунти и да препознаеш опасности во гејминг светот.',
                'audience' => 'child',
                'difficulty' => 'beginner',
                'age_group' => '10-13',
                'category_slug' => 'gaming-safety',
                'estimated_duration' => 20,
                'sort_order' => 5,
                'is_published' => true,
                'tags' => ['beginner', 'interactive', 'new'],
            ],
            [
                'title' => 'Водич за Родители: Онлајн Безбедност',
                'slug' => 'parent-guide-online-safety',
                'description' => 'Научете како да ги заштитите вашите деца онлајн, поставување на родителски контроли и комуникација за дигитална безбедност.',
                'audience' => 'parent',
                'difficulty' => 'beginner',
                'age_group' => '18+',
                'category_slug' => 'online-safety',
                'estimated_duration' => 30,
                'sort_order' => 6,
                'is_published' => true,
                'tags' => ['recommended'],
            ],
        ];

        foreach ($modules as $moduleData) {
            $category = Category::where('slug', $moduleData['category_slug'])->first();
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
