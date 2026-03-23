<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use App\Models\Resource;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        // Example: attach resources to a lesson with slug 'password-quiz-lesson'
        $lesson = Lesson::where('slug', 'password-quiz-lesson')->first();

        if (!$lesson) {
            $this->command->warn("Lesson with slug 'password-quiz-lesson' not found. Skipping ResourceSeeder.");
            return;
        }

        $resources = [
            [
                'lesson_id'   => $lesson->id,
                'title'       => 'What is a Strong Password? (PDF)',
                'description' => 'A quick PDF guide on strong passwords.',
                'type'        => 'pdf',
                'file_path'   => 'resources/passwords/strong-passwords.pdf',
                'file_size'   => 225000,
                'sort_order'  => 1,
            ],
            [
                'lesson_id'   => $lesson->id,
                'title'       => 'Password Security Video',
                'description' => 'Watch this video to understand why passwords matter.',
                'type'        => 'video',
                'url'=> 'https://www.youtube.com/watch?v=example',
                'file_path'   => null,
                'file_size'   => null,
                'sort_order'  => 2,
            ],
            [
                'lesson_id'   => $lesson->id,
                'title'       => 'Password Manager Apps',
                'description' => 'A list of recommended password managers.',
                'type'        => 'link',
                'url'=> 'https://www.cyberaware.gov/password-managers',
                'file_path'   => null,
                'file_size'   => null,
                'sort_order'  => 3,
            ],
        ];

        foreach ($resources as $resData) {
            Resource::create($resData);
        }
    }
}
