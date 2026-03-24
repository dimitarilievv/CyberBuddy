<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiInteraction;
use App\Models\User;
use Carbon\Carbon;

class AiInteractionSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'chat',
            'scenario_generation',
            'quiz_help',
            'content_explanation',
            'safety_check',
        ];

        $users = User::all();

        foreach ($users as $user) {
            foreach ($types as $type) {
                AiInteraction::create([
                    'user_id'         => $user->id,
                    'type'            => $type,
                    'prompt'          => "Demo prompt for $type (user: {$user->name})",
                    'response'        => "This is a generated AI response for the '$type' request.",
                    'model_used'      => 'gemini-2.5-flash',
                    'tokens_used'     => rand(20, 120),
                    'response_time_ms'=> rand(80, 300),
                    'was_helpful'     => (bool)rand(0,1),
                    'created_at'      => Carbon::now()->subDays(rand(0,5))->subMinutes(rand(0, 1440)),
                    'updated_at'      => Carbon::now(),
                ]);
            }
        }

        $this->command->info('AI interactions seeded for all users and types.');
    }
}
