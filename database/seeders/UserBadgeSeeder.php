<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserBadge;
use App\Models\User;
use App\Models\Badge;
use Carbon\Carbon;

class UserBadgeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Get users and badges
        $children = User::where('role', 'child')->get();
        $admin = User::where('role', 'admin')->first();
        $teacher = User::where('role', 'teacher')->first();
        $badges = Badge::all()->keyBy('slug');

        // Example: Give all children the "First Step" badge
        foreach ($children as $child) {
            UserBadge::firstOrCreate([
                'user_id' => $child->id,
                'badge_id' => $badges['first-step']->id ?? 1, // fallback to id 1
            ], [
                'earned_at' => $now->subDays(rand(1, 10)),
                'reason' => 'Completed their first lesson!',
            ]);
        }

        // Demo: Give Maria Petrova (teacher) the Explorer badge
        if ($teacher && isset($badges['explorer'])) {
            UserBadge::firstOrCreate([
                'user_id' => $teacher->id,
                'badge_id' => $badges['explorer']->id,
            ], [
                'earned_at' => $now->subDays(2),
                'reason' => 'Assisted a student in learning.',
            ]);
        }

        // Demo: Give Admin the "AI Friend" badge
        if ($admin && isset($badges['ai-friend'])) {
            UserBadge::firstOrCreate([
                'user_id' => $admin->id,
                'badge_id' => $badges['ai-friend']->id,
            ], [
                'earned_at' => $now,
                'reason' => 'For testing AI features.',
            ]);
        }

        $this->command->info('User badges seeded for children, teacher, and admin.');
    }
}
