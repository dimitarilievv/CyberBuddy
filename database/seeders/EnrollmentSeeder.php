<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Module;
use Carbon\Carbon;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        // Get all children
        $children = User::where('role', 'child')->get();
        $modules = Module::where('is_published', true)->get();

        if ($children->isEmpty() || $modules->isEmpty()) {
            $this->command->warn('No children or modules found. Skipping.');
            return;
        }

        $enrollments = [];

        // Create specific enrollment scenarios for Ana (if exists)
        $ana = User::where('email', 'ana@cyberbuddy.mk')->first();
        if ($ana) {
            $enrollments[] = [
                'user' => $ana,
                'modules' => [
                    ['slug' => 'strong-passwords', 'progress' => 100, 'status' => 'completed'],
                    ['slug' => 'recognize-phishing', 'progress' => 80, 'status' => 'in_progress'],
                    ['slug' => 'social-media-safety', 'progress' => 50, 'status' => 'in_progress'],
                    ['slug' => 'stop-cyberbullying', 'progress' => 100, 'status' => 'completed'],
                    ['slug' => 'safe-gaming', 'progress' => 30, 'status' => 'enrolled'],
                ],
            ];
        }

        // Create specific enrollment scenarios for Marko (if exists)
        $marko = User::where('email', 'marko@cyberbuddy.mk')->first();
        if ($marko) {
            $enrollments[] = [
                'user' => $marko,
                'modules' => [
                    ['slug' => 'strong-passwords', 'progress' => 60, 'status' => 'in_progress'],
                    ['slug' => 'safe-gaming', 'progress' => 90, 'status' => 'in_progress'],
                    ['slug' => 'device-security', 'progress' => 40, 'status' => 'in_progress'],
                ],
            ];
        }

        // Create generic enrollments for other children
        foreach ($children as $child) {
            // Skip if we already created specific enrollments for this child
            $existingEnrollment = collect($enrollments)->firstWhere('user.id', $child->id);
            if ($existingEnrollment) {
                continue;
            }

            $randomModules = $modules->random(min(rand(2, 4), $modules->count()));
            $moduleData = [];

            foreach ($randomModules as $module) {
                $progress = rand(0, 100);
                $status = 'enrolled';
                if ($progress >= 100) $status = 'completed';
                elseif ($progress >= 50) $status = 'in_progress';

                $moduleData[] = [
                    'slug' => $module->slug,
                    'progress' => $progress,
                    'status' => $status,
                ];
            }

            $enrollments[] = [
                'user' => $child,
                'modules' => $moduleData,
            ];
        }

        // Create the enrollments
        $created = 0;
        foreach ($enrollments as $enrollmentData) {
            $user = $enrollmentData['user'];

            foreach ($enrollmentData['modules'] as $moduleInfo) {
                $module = Module::where('slug', $moduleInfo['slug'])->first();

                if (!$module) {
                    continue;
                }

                $enrolledAt = Carbon::now()->subDays(rand(1, 30));
                $completedAt = null;

                if ($moduleInfo['status'] === 'completed') {
                    $completedAt = $enrolledAt->copy()->addDays(rand(1, 14));
                }

                Enrollment::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'module_id' => $module->id,
                    ],
                    [
                        'status' => $moduleInfo['status'],
                        'progress_percentage' => $moduleInfo['progress'],
                        'enrolled_at' => $enrolledAt,
                        'completed_at' => $completedAt,
                    ]
                );

                $created++;
            }
        }

        $this->command->info("EnrollmentSeeder complete. Created {$created} enrollments.");
    }
}
