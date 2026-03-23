<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserProgress;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Enrollment;
use Carbon\Carbon;

class UserProgressSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'child')->first(); // CHANGED THIS LINE
        $lesson = Lesson::where('slug', 'password-quiz-lesson')->first();
        $enrollment = Enrollment::where('user_id', $user?->id ?? 0)
            ->where('module_id', $lesson?->module_id ?? 0)
            ->first();

        if (!($user && $lesson && $enrollment)) {
            $this->command->warn("Missing user, lesson, or enrollment for UserProgressSeeder. Skipping seeding.");
            return;
        }

        $progressData = [
            [
                'user_id'           => $user->id,
                'lesson_id'         => $lesson->id,
                'enrollment_id'     => $enrollment->id,
                'status'            => 'in_progress',
                'time_spent_seconds'=> 150,
                'started_at'        => Carbon::now()->subDays(1),
                'completed_at'      => null,
            ],
            [
                'user_id'           => $user->id,
                'lesson_id'         => $lesson->id,
                'enrollment_id'     => $enrollment->id,
                'status'            => 'completed',
                'time_spent_seconds'=> 300,
                'started_at'        => Carbon::now()->subDays(2),
                'completed_at'      => Carbon::now()->subDay(),
            ],
        ];

        foreach ($progressData as $data) {
            UserProgress::updateOrCreate(
                [
                    'user_id' => $data['user_id'],
                    'lesson_id' => $data['lesson_id'],
                ],
                $data
            );
        }
    }
}
