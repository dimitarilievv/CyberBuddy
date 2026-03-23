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
        // You can adjust this logic to enroll more users/modules!
        $users = User::where('role', 'child')->take(2)->get();  // Take the first 2 children as an example
        $module = Module::first(); // Or use where('title',...) or another filter

        if ($users->isEmpty() || !$module) {
            $this->command->warn('Missing test users or modules for EnrollmentSeeder. Skipping.');
            return;
        }

        foreach ($users as $user) {
            Enrollment::firstOrCreate(
                [
                    'user_id'   => $user->id,
                    'module_id' => $module->id,
                ],
                [
                    'status'              => 'enrolled',
                    'progress_percentage' => 0,
                    'enrolled_at'         => Carbon::now()->subDays(rand(1, 10)),
                    'completed_at'        => null,
                ]
            );
        }

        $this->command->info("Enrollments seeded for ".count($users)." users in module \"{$module->title}\".");
    }
}
