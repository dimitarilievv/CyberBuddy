<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Scenario;
use App\Models\ScenarioChoice;
use App\Models\ScenarioAttempt;
use Carbon\Carbon;

class ScenarioAttemptSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users (parents and children)
        $users = User::whereIn('role', ['child', 'parent'])->get();
        $scenarios = Scenario::all();

        if ($users->count() === 0 || $scenarios->count() === 0) {
            $this->command->error('No users or scenarios found. Please run UserSeeder and ScenarioSeeder first.');
            return;
        }

        // For each scenario, create multiple attempts by different users
        foreach ($scenarios as $scenario) {
            $choices = ScenarioChoice::where('scenario_id', $scenario->id)->get();

            if ($choices->count() === 0) {
                $this->command->warn("No choices found for scenario: {$scenario->title}");
                continue;
            }

            // Get a few random users
            $randomUsers = $users->random(min(3, $users->count()));

            foreach ($randomUsers as $user) {
                // Choose a random choice (good, bad, or mixed)
                $randomChoice = $choices->random();

                // Calculate realistic time spent (between 15 and 120 seconds)
                $timeSpent = rand(15, 120);

                // Create the attempt
                ScenarioAttempt::create([
                    'scenario_id' => $scenario->id,
                    'user_id' => $user->id,
                    'chosen_choice_id' => $randomChoice->id,
                    'safety_score' => $randomChoice->safety_score,
                    'ai_feedback' => $randomChoice->ai_explanation,
                    'time_spent_seconds' => $timeSpent,
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        // Create specific attempts for known users to make the data more realistic

        // Find specific children by email (from your UserSeeder)
        $ana = User::where('email', 'ana@cyberbuddy.mk')->first();
        $marko = User::where('email', 'marko@cyberbuddy.mk')->first();

        if ($ana && $scenarios->count() > 0) {
            // Ana's attempts (a careful child)
            $phishingScenario = Scenario::where('title', 'Suspicious Email from "Bank"')->first();
            if ($phishingScenario) {
                $bestChoice = ScenarioChoice::where('scenario_id', $phishingScenario->id)
                    ->where('is_recommended', true)
                    ->first();

                if ($bestChoice) {
                    ScenarioAttempt::create([
                        'scenario_id' => $phishingScenario->id,
                        'user_id' => $ana->id,
                        'chosen_choice_id' => $bestChoice->id,
                        'safety_score' => $bestChoice->safety_score,
                        'ai_feedback' => $bestChoice->ai_explanation,
                        'time_spent_seconds' => 45,
                        'created_at' => Carbon::now()->subDays(5),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }

            $socialScenario = Scenario::where('title', 'Unknown Friend Request')->first();
            if ($socialScenario) {
                $bestChoice = ScenarioChoice::where('scenario_id', $socialScenario->id)
                    ->where('is_recommended', true)
                    ->first();

                if ($bestChoice) {
                    ScenarioAttempt::create([
                        'scenario_id' => $socialScenario->id,
                        'user_id' => $ana->id,
                        'chosen_choice_id' => $bestChoice->id,
                        'safety_score' => $bestChoice->safety_score,
                        'ai_feedback' => $bestChoice->ai_explanation,
                        'time_spent_seconds' => 30,
                        'created_at' => Carbon::now()->subDays(3),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }

        if ($marko && $scenarios->count() > 0) {
            // Marko's attempts (a learning child - sometimes makes mistakes)
            $phishingScenario = Scenario::where('title', 'Suspicious Email from "Bank"')->first();
            if ($phishingScenario) {
                $badChoice = ScenarioChoice::where('scenario_id', $phishingScenario->id)
                    ->where('is_recommended', false)
                    ->where('safety_score', '<', 50)
                    ->first();

                if ($badChoice) {
                    ScenarioAttempt::create([
                        'scenario_id' => $phishingScenario->id,
                        'user_id' => $marko->id,
                        'chosen_choice_id' => $badChoice->id,
                        'safety_score' => $badChoice->safety_score,
                        'ai_feedback' => $badChoice->ai_explanation,
                        'time_spent_seconds' => 20,
                        'created_at' => Carbon::now()->subDays(10),
                        'updated_at' => Carbon::now(),
                    ]);
                }

                // Marko tries again and learns (second attempt with better choice)
                $goodChoice = ScenarioChoice::where('scenario_id', $phishingScenario->id)
                    ->where('is_recommended', true)
                    ->first();

                if ($goodChoice) {
                    ScenarioAttempt::create([
                        'scenario_id' => $phishingScenario->id,
                        'user_id' => $marko->id,
                        'chosen_choice_id' => $goodChoice->id,
                        'safety_score' => $goodChoice->safety_score,
                        'ai_feedback' => $goodChoice->ai_explanation,
                        'time_spent_seconds' => 35,
                        'created_at' => Carbon::now()->subDays(2),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }

            $bullyingScenario = Scenario::where('title', 'Witnessing Cyberbullying')->first();
            if ($bullyingScenario) {
                $okChoice = ScenarioChoice::where('scenario_id', $bullyingScenario->id)
                    ->where('safety_score', '>=', 80)
                    ->where('is_recommended', false)
                    ->first();

                if ($okChoice) {
                    ScenarioAttempt::create([
                        'scenario_id' => $bullyingScenario->id,
                        'user_id' => $marko->id,
                        'chosen_choice_id' => $okChoice->id,
                        'safety_score' => $okChoice->safety_score,
                        'ai_feedback' => $okChoice->ai_explanation,
                        'time_spent_seconds' => 50,
                        'created_at' => Carbon::now()->subDays(7),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }

        // Create attempts for a parent user
        $parent = User::where('role', 'parent')->first();
        if ($parent && $scenarios->count() > 0) {
            $parentScenario = Scenario::where('title', 'Witnessing Cyberbullying')->first();
            if ($parentScenario) {
                $bestChoice = ScenarioChoice::where('scenario_id', $parentScenario->id)
                    ->where('is_recommended', true)
                    ->first();

                if ($bestChoice) {
                    ScenarioAttempt::create([
                        'scenario_id' => $parentScenario->id,
                        'user_id' => $parent->id,
                        'chosen_choice_id' => $bestChoice->id,
                        'safety_score' => $bestChoice->safety_score,
                        'ai_feedback' => $bestChoice->ai_explanation,
                        'time_spent_seconds' => 40,
                        'created_at' => Carbon::now()->subDays(1),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }

        $this->command->info('Scenario attempts seeded successfully!');
        $this->command->info('Total attempts created: ' . ScenarioAttempt::count());
    }
}
