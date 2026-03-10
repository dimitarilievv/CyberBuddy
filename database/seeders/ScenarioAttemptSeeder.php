<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Scenario;
use App\Models\ScenarioChoice;
use App\Models\ScenarioAttempt;

class ScenarioAttemptSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $scenario = Scenario::first();
        $choice = ScenarioChoice::first();

        if (!$user || !$scenario || !$choice) {
            return;
        }

        ScenarioAttempt::create([
            'scenario_id' => $scenario->id,
            'user_id' => $user->id,
            'chosen_choice_id' => $choice->id,
            'safety_score' => $choice->safety_score,
            'ai_feedback' => $choice->ai_explanation,
            'time_spent_seconds' => 25,
        ]);
    }
}
