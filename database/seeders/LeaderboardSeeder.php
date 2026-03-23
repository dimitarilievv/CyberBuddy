<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Leaderboard;
use App\Models\User;
use Illuminate\Support\Arr;

class LeaderboardSeeder extends Seeder
{
    public function run(): void
    {
        // Use periods that match the enum in the migration
        $periods = ['all_time', 'monthly', 'weekly'];
        $users = User::whereIn('role', ['child', 'teacher', 'parent', 'admin'])->get();

        foreach ($periods as $period) {
            $rank = 1;
            // Shuffle to simulate changing weekly/monthly ranks
            $shuffled = $users->shuffle();

            foreach ($shuffled as $user) {
                Leaderboard::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'period'  => $period
                    ],
                    [
                        'total_points'        => rand(50, 1000),
                        'modules_completed'   => rand(0, 10),
                        'quizzes_passed'      => rand(0, 15),
                        'scenarios_completed' => rand(0, 20),
                        'badges_earned'       => rand(0, 8),
                        'current_streak'      => rand(0, 7),
                        'longest_streak'      => rand(0, 30),
                        'rank'                => $rank++
                    ]
                );
            }
        }

        $this->command->info('Leaderboard entries seeded for all users and periods.');
    }
}
