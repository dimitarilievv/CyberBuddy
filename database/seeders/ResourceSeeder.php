<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use App\Models\Resource;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $resourcesCreated = 0;

        // === PASSWORD LESSON RESOURCES ===
        $passwordLesson = Lesson::where('slug', 'password-quiz-lesson')->first();
        if ($passwordLesson) {
            $resources = [
                [
                    'title'       => 'What is a Strong Password? (PDF)',
                    'description' => 'A quick PDF guide on strong passwords. Learn the basics of creating secure passwords that protect your accounts.',
                    'type'        => 'pdf',
                    'file_path'   => 'resources/passwords/strong-passwords-guide.pdf',
                    'file_size'   => 225000,
                    'sort_order'  => 1,
                ],
                [
                    'title'       => 'Password Security Video',
                    'description' => 'Watch this fun animated video to understand why passwords matter and how to create strong ones.',
                    'type'        => 'video',
                    'url'         => 'https://www.youtube.com/watch?v=example-password-security',
                    'file_path'   => null,
                    'file_size'   => null,
                    'sort_order'  => 2,
                ],
                [
                    'title'       => 'Password Manager Apps',
                    'description' => 'A list of recommended password managers for kids and families. These apps help you remember strong passwords safely.',
                    'type'        => 'link',
                    'url'         => 'https://www.cyberaware.gov/password-managers',
                    'file_path'   => null,
                    'file_size'   => null,
                    'sort_order'  => 3,
                ],
                [
                    'title'       => 'Password Strength Checker',
                    'description' => 'Interactive tool to test how strong your passwords are.',
                    'type'        => 'link',
                    'url'         => 'https://www.security.org/how-secure-is-my-password/',
                    'file_path'   => null,
                    'file_size'   => null,
                    'sort_order'  => 4,
                ],
            ];

            foreach ($resources as $resData) {
                $resData['lesson_id'] = $passwordLesson->id;
                Resource::firstOrCreate(
                    [
                        'lesson_id' => $passwordLesson->id,
                        'title' => $resData['title'],
                    ],
                    $resData
                );
                $resourcesCreated++;
            }
        }

        // === PHISHING LESSON RESOURCES ===
        $phishingLesson = Lesson::where('slug', 'phishing-scenario')->first();
        if ($phishingLesson) {
            $resources = [
                [
                    'title'       => 'Phishing Email Examples',
                    'description' => 'PDF showing real examples of phishing emails and how to spot them.',
                    'type'        => 'pdf',
                    'file_path'   => 'resources/phishing/phishing-examples.pdf',
                    'file_size'   => 180000,
                    'sort_order'  => 1,
                ],
                [
                    'title'       => 'How to Spot Phishing Video',
                    'description' => 'Watch this short video to learn the red flags of phishing attempts.',
                    'type'        => 'video',
                    'url'         => 'https://www.youtube.com/watch?v=example-phishing',
                    'file_path'   => null,
                    'file_size'   => null,
                    'sort_order'  => 2,
                ],
                [
                    'title'       => 'Report Phishing Website',
                    'description' => 'Official government site to report phishing attempts.',
                    'type'        => 'link',
                    'url'         => 'https://reportphishing.gov',
                    'file_path'   => null,
                    'file_size'   => null,
                    'sort_order'  => 3,
                ],
            ];

            foreach ($resources as $resData) {
                $resData['lesson_id'] = $phishingLesson->id;
                Resource::firstOrCreate(
                    [
                        'lesson_id' => $phishingLesson->id,
                        'title' => $resData['title'],
                    ],
                    $resData
                );
                $resourcesCreated++;
            }
        }

        // === SOCIAL MEDIA SAFETY RESOURCES ===
        $socialLesson = Lesson::where('slug', 'social-privacy-settings')->first();
        if ($socialLesson) {
            $resources = [
                [
                    'title'       => 'Social Media Privacy Checklist',
                    'description' => 'Printable checklist for securing your social media accounts.',
                    'type'        => 'pdf',
                    'file_path'   => 'resources/social/privacy-checklist.pdf',
                    'file_size'   => 150000,
                    'sort_order'  => 1,
                ],
                [
                    'title'       => 'Privacy Settings Guide by Platform',
                    'description' => 'Step-by-step guides for Instagram, TikTok, and Snapchat privacy settings.',
                    'type'        => 'link',
                    'url'         => 'https://www.commonsensemedia.org/privacy-settings',
                    'file_path'   => null,
                    'file_size'   => null,
                    'sort_order'  => 2,
                ],
                [
                    'title'       => 'Digital Footprint Game',
                    'description' => 'Fun interactive game about managing your digital footprint.',
                    'type'        => 'link',
                    'url'         => 'https://www.digitalfootprintgame.org',
                    'file_path'   => null,
                    'file_size'   => null,
                    'sort_order'  => 3,
                ],
            ];

            foreach ($resources as $resData) {
                $resData['lesson_id'] = $socialLesson->id;
                Resource::firstOrCreate(
                    [
                        'lesson_id' => $socialLesson->id,
                        'title' => $resData['title'],
                    ],
                    $resData
                );
                $resourcesCreated++;
            }
        }

        // === CYBERBULLYING RESOURCES ===
        $bullyingLesson = Lesson::where('slug', 'what-is-cyberbullying')->first();
        if ($bullyingLesson) {
            $resources = [
                [
                    'title'       => 'Stop Cyberbullying Guide',
                    'description' => 'PDF guide on what to do if you or someone you know is being cyberbullied.',
                    'type'        => 'pdf',
                    'file_path'   => 'resources/bullying/stop-cyberbullying.pdf',
                    'file_size'   => 200000,
                    'sort_order'  => 1,
                ],
                [
                    'title'       => 'How to Be an Upstander',
                    'description' => 'Video about standing up to bullying and supporting others.',
                    'type'        => 'video',
                    'url'         => 'https://www.youtube.com/watch?v=example-upstander',
                    'file_path'   => null,
                    'file_size'   => null,
                    'sort_order'  => 2,
                ],
                [
                    'title'       => 'Helpline for Kids',
                    'description' => 'Free confidential helpline for children experiencing bullying.',
                    'type'        => 'link',
                    'url'         => 'https://www.childhelpline.org',
                    'file_path'   => null,
                    'file_size'   => null,
                    'sort_order'  => 3,
                ],
            ];

            foreach ($resources as $resData) {
                $resData['lesson_id'] = $bullyingLesson->id;
                Resource::firstOrCreate(
                    [
                        'lesson_id' => $bullyingLesson->id,
                        'title' => $resData['title'],
                    ],
                    $resData
                );
                $resourcesCreated++;
            }
        }

        // === GAMING SAFETY RESOURCES ===
        $gamingLesson = Lesson::where('slug', 'safe-gaming-tips')->first();
        if ($gamingLesson) {
            $resources = [
                [
                    'title'       => 'Gaming Safety Tips Poster',
                    'description' => 'Printable poster with gaming safety rules.',
                    'type'        => 'pdf',
                    'file_path'   => 'resources/gaming/safety-poster.pdf',
                    'file_size'   => 175000,
                    'sort_order'  => 1,
                ],
                [
                    'title'       => 'Safe Gaming Video',
                    'description' => 'Tips for staying safe while playing online games.',
                    'type'        => 'video',
                    'url'         => 'https://www.youtube.com/watch?v=example-gaming-safety',
                    'file_path'   => null,
                    'file_size'   => null,
                    'sort_order'  => 2,
                ],
                [
                    'title'       => 'Parent Guide to Gaming',
                    'description' => 'Guide for parents about gaming safety and parental controls.',
                    'type'        => 'link',
                    'url'         => 'https://www.parentsguide.org/gaming',
                    'file_path'   => null,
                    'file_size'   => null,
                    'sort_order'  => 3,
                ],
            ];

            foreach ($resources as $resData) {
                $resData['lesson_id'] = $gamingLesson->id;
                Resource::firstOrCreate(
                    [
                        'lesson_id' => $gamingLesson->id,
                        'title' => $resData['title'],
                    ],
                    $resData
                );
                $resourcesCreated++;
            }
        }

        // === FAKE NEWS RESOURCES ===
        $fakeNewsLesson = Lesson::where('slug', 'spotting-fake-news')->first();
        if ($fakeNewsLesson) {
            $resources = [
                [
                    'title'       => 'Fake News Checklist',
                    'description' => 'PDF checklist for verifying news before sharing.',
                    'type'        => 'pdf',
                    'file_path'   => 'resources/fakenews/checklist.pdf',
                    'file_size'   => 125000,
                    'sort_order'  => 1,
                ],
                [
                    'title'       => 'Fact-Checking Websites',
                    'description' => 'List of reliable fact-checking websites to verify information.',
                    'type'        => 'link',
                    'url'         => 'https://www.snopes.com',
                    'file_path'   => null,
                    'file_size'   => null,
                    'sort_order'  => 2,
                ],
                [
                    'title'       => 'How to Spot Fake News Video',
                    'description' => 'Fun video about fact-checking and media literacy.',
                    'type'        => 'video',
                    'url'         => 'https://www.youtube.com/watch?v=example-fakenews',
                    'file_path'   => null,
                    'file_size'   => null,
                    'sort_order'  => 3,
                ],
            ];

            foreach ($resources as $resData) {
                $resData['lesson_id'] = $fakeNewsLesson->id;
                Resource::firstOrCreate(
                    [
                        'lesson_id' => $fakeNewsLesson->id,
                        'title' => $resData['title'],
                    ],
                    $resData
                );
                $resourcesCreated++;
            }
        }

        $this->command->info("ResourceSeeder complete. Created/updated {$resourcesCreated} resources.");
    }
}
