<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()->pluck('id');

        if ($users->isEmpty()) {
            return;
        }

        foreach ($users as $userId) {
            Notification::create([
                'user_id' => $userId,
                'title' => 'Welcome!',
                'message' => 'Thanks for joining. Start your first lesson today.',
                'type' => 'info',
                'icon' => 'bell',
                'action_url' => '/modules',
                'is_read' => false,
                'read_at' => null,
            ]);

            Notification::create([
                'user_id' => $userId,
                'title' => 'Lesson reminder',
                'message' => 'Don’t forget to continue your lessons.',
                'type' => 'reminder',
                'icon' => 'clock',
                'action_url' => '/modules',
                'is_read' => false,
                'read_at' => null,
            ]);

            Notification::create([
                'user_id' => $userId,
                'title' => 'Achievement unlocked',
                'message' => 'You earned a new badge. Check your badges.',
                'type' => 'achievement',
                'icon' => 'trophy',
                'action_url' => '/badges',
                'is_read' => true,
                'read_at' => now()->subDays(2),
            ]);

            Notification::create([
                'user_id' => $userId,
                'title' => 'Important alert',
                'message' => 'Please review your recent activity.',
                'type' => 'alert',
                'icon' => 'warning',
                'action_url' => '/dashboard',
                'is_read' => false,
                'read_at' => null,
            ]);

            Notification::create([
                'user_id' => $userId,
                'title' => 'Parent report',
                'message' => 'A new parent report is available.',
                'type' => 'parent_report',
                'icon' => 'file',
                'action_url' => '/parent/dashboard',
                'is_read' => false,
                'read_at' => null,
            ]);
        }
    }
}
