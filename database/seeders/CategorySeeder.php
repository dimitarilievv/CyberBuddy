<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Онлајн Безбедност',
                'slug' => 'online-safety',
                'description' => 'Научи како да бидеш безбеден на интернет',
                'icon' => '🛡️',
                'sort_order' => 1,
                'children' => [
                    [
                        'name' => 'Лозинки и Автентикација',
                        'slug' => 'passwords-auth',
                        'description' => 'Како да креираш и чуваш силни лозинки',
                        'icon' => '🔑',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Фишинг и Измами',
                        'slug' => 'phishing-scams',
                        'description' => 'Препознавање на онлајн измами',
                        'icon' => '🎣',
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Безбедно Пребарување',
                        'slug' => 'safe-browsing',
                        'description' => 'Како безбедно да пребаруваш на интернет',
                        'icon' => '🌐',
                        'sort_order' => 3,
                    ],
                ],
            ],
            [
                'name' => 'Социјални Мрежи',
                'slug' => 'social-media',
                'description' => 'Безбедно користење на социјални мрежи',
                'icon' => '📱',
                'sort_order' => 2,
                'children' => [
                    [
                        'name' => 'Приватност на Профил',
                        'slug' => 'profile-privacy',
                        'description' => 'Заштита на личните информации',
                        'icon' => '🔒',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Дигитален Отпечаток',
                        'slug' => 'digital-footprint',
                        'description' => 'Што оставаш зад себе на интернет',
                        'icon' => '👣',
                        'sort_order' => 2,
                    ],
                ],
            ],
            [
                'name' => 'Сајбер Булинг',
                'slug' => 'cyberbullying',
                'description' => 'Препознавање и справување со онлајн малтретирање',
                'icon' => '🚫',
                'sort_order' => 3,
                'children' => [
                    [
                        'name' => 'Препознавање Булинг',
                        'slug' => 'recognizing-bullying',
                        'description' => 'Како да препознаеш сајбер булинг',
                        'icon' => '👀',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Реагирaње и Пријавување',
                        'slug' => 'reporting-bullying',
                        'description' => 'Што да направиш ако си жртва или сведок',
                        'icon' => '🆘',
                        'sort_order' => 2,
                    ],
                ],
            ],
            [
                'name' => 'Лични Податоци',
                'slug' => 'personal-data',
                'description' => 'Заштита на личните информации онлајн',
                'icon' => '🔐',
                'sort_order' => 4,
            ],
            [
                'name' => 'Дигитално Здравје',
                'slug' => 'digital-wellness',
                'description' => 'Баланс помеѓу онлајн и офлајн живот',
                'icon' => '💚',
                'sort_order' => 5,
            ],
            [
                'name' => 'Гејминг Безбедност',
                'slug' => 'gaming-safety',
                'description' => 'Безбедно играње онлајн игри',
                'icon' => '🎮',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);

            $parent = Category::create($categoryData);

            foreach ($children as $childData) {
                $childData['parent_id'] = $parent->id;
                Category::create($childData);
            }
        }
    }
}
