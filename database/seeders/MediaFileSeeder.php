<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;
use App\Models\MediaFile;

class MediaFileSeeder extends Seeder
{
    public function run(): void
    {
        $lesson = Lesson::first();

        if (!$lesson) {
            return;
        }

        $mediaFiles = [
            [
                'mediable_type' => Lesson::class,
                'mediable_id' => $lesson->id,
                'file_name' => 'password-lesson-thumbnail.png',
                'file_path' => '/media/lessons/password-thumbnail.png',
                'mime_type' => 'image/png',
                'file_size' => 204800,
                'type' => 'image',
                'alt_text' => 'Password lesson thumbnail',
                'sort_order' => 1,
            ],
            [
                'mediable_type' => Lesson::class,
                'mediable_id' => $lesson->id,
                'file_name' => 'password-security-video.mp4',
                'file_path' => '/media/lessons/password-video.mp4',
                'mime_type' => 'video/mp4',
                'file_size' => 2048000,
                'type' => 'video',
                'alt_text' => 'Password security video',
                'sort_order' => 2,
            ],
            [
                'mediable_type' => Lesson::class,
                'mediable_id' => $lesson->id,
                'file_name' => 'password-audio-guide.mp3',
                'file_path' => '/media/lessons/password-audio.mp3',
                'mime_type' => 'audio/mpeg',
                'file_size' => 1024000,
                'type' => 'audio',
                'alt_text' => 'Audio explanation',
                'sort_order' => 3,
            ],
        ];

        foreach ($mediaFiles as $media) {
            MediaFile::create($media);
        }
    }
}
