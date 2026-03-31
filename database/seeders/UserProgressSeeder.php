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
        $children = User::where('role', 'child')->get();

        if ($children->isEmpty()) {
            $this->command->warn('No children users found.');
            return;
        }

        $progressCount = 0;

        foreach ($children as $child) {
            $enrollments = Enrollment::where('user_id', $child->id)->get();

            foreach ($enrollments as $enrollment) {
                $lessons = Lesson::where('module_id', $enrollment->module_id)
                    ->where('is_published', true)
                    ->get();

                foreach ($lessons as $lesson) {
                    // Determine if this lesson should be completed based on enrollment progress
                    $isCompleted = rand(1, 100) <= $enrollment->progress_percentage;

                    $status = $isCompleted ? 'completed' : 'in_progress';
                    $timeSpent = $isCompleted
                        ? $lesson->estimated_minutes * 60
                        : rand(30, $lesson->estimated_minutes * 60);

                    $startedAt = $enrollment->enrolled_at->copy()->addDays(rand(0, 5));
                    $completedAt = $isCompleted
                        ? $startedAt->copy()->addMinutes($lesson->estimated_minutes)
                        : null;

                    UserProgress::updateOrCreate(
                        [
                            'user_id' => $child->id,
                            'lesson_id' => $lesson->id,
                        ],
                        [
                            'enrollment_id' => $enrollment->id,
                            'status' => $status,
                            'time_spent_seconds' => $timeSpent,
                            'started_at' => $startedAt,
                            'completed_at' => $completedAt,
                        ]
                    );

                    $progressCount++;
                }
            }
        }

        $this->command->info("UserProgressSeeder complete. Created/updated {$progressCount} progress records.");
    }
}
