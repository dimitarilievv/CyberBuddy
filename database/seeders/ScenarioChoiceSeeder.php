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
                'choice_text' => 'Се согласувам да се сретнеме со него.',
                'consequence' => 'Ова може да доведе до опасна ситуација бидејќи не знаеш кој е таа личност.',
                'safety_score' => 0,
                'is_recommended' => false,
                'ai_explanation' => 'Никогаш не треба да се среќаваш со непознати луѓе од интернет.',
                'sort_order' => 1,
            ],
            [
                'scenario_id' => $scenario->id,
                'choice_text' => 'Му праќам уште пораки за да го запознаам.',
                'consequence' => 'Ова може да доведе до споделување лични информации.',
                'safety_score' => 30,
                'is_recommended' => false,
                'ai_explanation' => 'Комуникација со непознати може да биде ризична.',
                'sort_order' => 2,
            ],
            [
                'scenario_id' => $scenario->id,
                'choice_text' => 'Го блокирам и им кажувам на родителите.',
                'consequence' => 'Ова е најбезбедната одлука.',
                'safety_score' => 100,
                'is_recommended' => true,
                'ai_explanation' => 'Блокирање и информирање на родител е правилен чекор за онлајн безбедност.',
                'sort_order' => 3,
            ],
        ];

        foreach ($choices as $choice) {
            ScenarioChoice::create($choice);
        }
    }
}
