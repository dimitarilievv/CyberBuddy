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
                'name' => 'Online Safety',
                'slug' => 'online-safety',
                'description' => 'Learn how to stay safe on the internet',
                'icon' => 'shield',
                'sort_order' => 1,
                'children' => [
                    [
                        'name' => 'Passwords and Authentication',
                        'slug' => 'passwords-auth',
                        'description' => 'How to create and keep strong passwords',
                        'icon' => 'key',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Phishing and Scams',
                        'slug' => 'phishing-scams',
                        'description' => 'Recognize online scams',
                        'icon' => 'hook',
                        'sort_order' => 2,
                    ],
                    [
                        'name' => 'Safe Browsing',
                        'slug' => 'safe-browsing',
                        'description' => 'How to browse the web safely',
                        'icon' => 'globe',
                        'sort_order' => 3,
                    ],
                ],
            ],
            [
                'name' => 'Social Media',
                'slug' => 'social-media',
                'description' => 'Use social networks safely',
                'icon' => 'mobile',
                'sort_order' => 2,
                'children' => [
                    [
                        'name' => 'Profile Privacy',
                        'slug' => 'profile-privacy',
                        'description' => 'Protect your personal information',
                        'icon' => 'lock',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Digital Footprint',
                        'slug' => 'digital-footprint',
                        'description' => 'What you leave behind online',
                        'icon' => 'footprint',
                        'sort_order' => 2,
                    ],
                ],
            ],
            [
                'name' => 'Cyberbullying',
                'slug' => 'cyberbullying',
                'description' => 'Recognize and respond to online bullying',
                'icon' => 'ban',
                'sort_order' => 3,
                'children' => [
                    [
                        'name' => 'Recognizing Bullying',
                        'slug' => 'recognizing-bullying',
                        'description' => 'How to spot cyberbullying',
                        'icon' => 'eye',
                        'sort_order' => 1,
                    ],
                    [
                        'name' => 'Responding and Reporting',
                        'slug' => 'reporting-bullying',
                        'description' => 'What to do if you are a target or a witness',
                        'icon' => 'sos',
                        'sort_order' => 2,
                    ],
                ],
            ],
            [
                'name' => 'Personal Data',
                'slug' => 'personal-data',
                'description' => 'Protect personal information online',
                'icon' => 'safe',
                'sort_order' => 4,
            ],
            [
                'name' => 'Digital Wellness',
                'slug' => 'digital-wellness',
                'description' => 'Balance online and offline life',
                'icon' => 'heart',
                'sort_order' => 5,
            ],
            [
                'name' => 'Gaming Safety',
                'slug' => 'gaming-safety',
                'description' => 'Play online games safely',
                'icon' => 'gamepad',
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
