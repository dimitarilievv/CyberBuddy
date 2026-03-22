<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Scenario;
use App\Models\ScenarioChoice;
class ScenarioChoiceSeeder extends Seeder
{
    public function run(): void
    {
        $scenario = Scenario::first();
        if (!$scenario) {
            return;
        }
        $choices = [
            [
                'scenario_id' => $scenario->id,
                'choice_text' => 'I agree to meet the person.',
                'consequence' => 'This can lead to a dangerous situation because you do not know who they are.',
                'safety_score' => 0,
                'is_recommended' => false,
                'ai_explanation' => 'You should never meet strangers from the internet.',
                'sort_order' => 1,
            ],
            [
                'scenario_id' => $scenario->id,
                'choice_text' => 'I keep messaging to get to know them.',
                'consequence' => 'This can lead to sharing personal information.',
                'safety_score' => 30,
                'is_recommended' => false,
                'ai_explanation' => 'Chatting with strangers can be risky.',
                'sort_order' => 2,
            ],
            [
                'scenario_id' => $scenario->id,
                'choice_text' => 'I block them and tell my parents.',
                'consequence' => 'This is the safest decision.',
                'safety_score' => 100,
                'is_recommended' => true,
                'ai_explanation' => 'Blocking and telling a parent is the right step for online safety.',
                'sort_order' => 3,
            ],
        ];
        foreach ($choices as $choice) {
            ScenarioChoice::create($choice);
        }
    }
}
