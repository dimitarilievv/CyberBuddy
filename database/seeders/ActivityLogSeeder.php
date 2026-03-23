<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->first();

        if (! $user) {
            return;
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'login',
            'description' => 'User logged in.',
            'loggable_type' => null,
            'loggable_id' => null,
            'metadata' => ['method' => 'password'],
            'ip_address' => '127.0.0.1',
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'lesson_completed',
            'description' => 'Completed a lesson.',
            'loggable_type' => null,
            'loggable_id' => null,
            'metadata' => ['lesson' => 'Intro to Phishing'],
            'ip_address' => '127.0.0.1',
        ]);
    }
}
