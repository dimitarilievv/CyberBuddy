<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Scenario;
use App\Models\ScenarioChoice;
use Illuminate\Database\Seeder;

class ScenarioSeeder extends Seeder
{
    public function run(): void
    {
        $phishingLesson = Lesson::where('slug', 'phishing-scenario')->first();

        // Сценарио 1: Сомнителен Емаил
        $scenario1 = Scenario::create([
            'lesson_id' => $phishingLesson?->id,
            'title' => 'Сомнителен Емаил од "Банка"',
            'description' => 'Тестирај дали можеш да препознаеш фишинг емаил',
            'situation' => 'Добиваш емаил од "Стопанска Банка" кој вели: "Вашата сметка е блокирана! Кликнете ТУКА за да ја потврдите вашата идентичност во рок од 24 часа или сметката ќе биде затворена засекогаш!" Емаилот има лого на банката но адресата е: security@st0panska-banka-verify.com',
            'difficulty' => 'medium',
            'age_group' => '10-13',
            'is_published' => true,
        ]);

        ScenarioChoice::insert([
            [
                'scenario_id' => $scenario1->id,
                'choice_text' => 'Веднаш кликнам на линкот и ги внесувам моите податоци — не сакам да ја изгубам сметката!',
                'consequence' => 'Штотуку ги даде твоите податоци на хакер! Тие сега можат да пристапат до твојата сметка и да ти ги украдат парите.',
                'safety_score' => 0,
                'is_recommended' => false,
                'ai_explanation' => 'Банките НИКОГАШ не бараат лични податоци преку емаил. Адресата "st0panska-banka-verify.com" е лажна — има нула наместо "о" и додатен текст.',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario1->id,
                'choice_text' => 'Го проверувам емаилот подобро — гледам дека адресата е чудна и НЕ кликнувам на линкот.',
                'consequence' => 'Браво! Забележа дека адресата не е вистинска. Ова е типичен фишинг обид.',
                'safety_score' => 80,
                'is_recommended' => false,
                'ai_explanation' => 'Добро е што го провери емаилот, но уште подобро е да го пријавиш.',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario1->id,
                'choice_text' => 'Не кликнувам на линкот, го пријавувам емаилот како спам и им кажувам на родителите.',
                'consequence' => 'Одлично! Го направи најбезбедното нешто — не кликна, пријави и побара помош од возрасен.',
                'safety_score' => 100,
                'is_recommended' => true,
                'ai_explanation' => 'Ова е најдобриот одговор! Никогаш не кликнувај на сомнителни линкови, секогаш пријави и побарај помош.',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Сценарио 2: Непознат на Социјална Мрежа
        $scenario2 = Scenario::create([
            'lesson_id' => null,
            'title' => 'Непознат Пријател на Instagram',
            'description' => 'Што правиш кога непознат ти праќа порака на социјална мрежа?',
            'situation' => 'На Instagram добиваш порака од непозната личност со профилна слика на тинејџер: "Здраво! Те видов во коментарите на @gamingmk. Играш Fortnite? Јас сум од Битола. Кажи ми во кое училиште одиш да видам дали имаме заеднички другари!"',
            'difficulty' => 'easy',
            'age_group' => '10-13',
            'is_published' => true,
        ]);

        ScenarioChoice::insert([
            [
                'scenario_id' => $scenario2->id,
                'choice_text' => 'Му кажувам во кое училиште одам и разменуваме Instagram имиња на другари.',
                'consequence' => 'Опасно! Не знаеш кој е вистински зад тој профил. Можеби не е тинејџер воопшто.',
                'safety_score' => 0,
                'is_recommended' => false,
                'ai_explanation' => 'НИКОГАШ не споделувај лични информации (училиште, адреса, телефон) со непознати онлајн.',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario2->id,
                'choice_text' => 'Не одговарам на пораката и го блокирам профилот.',
                'consequence' => 'Добро решение! Блокирањето е безбеден избор.',
                'safety_score' => 80,
                'is_recommended' => false,
                'ai_explanation' => 'Блокирањето е добро, но уште подобро е да го пријавиш профилот.',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scenario_id' => $scenario2->id,
                'choice_text' => 'Не одговарам, го блокирам, го пријавувам профилот и им кажувам на родителите.',
                'consequence' => 'Перфектно! Најбезбедната опција — блокирај, пријави, кажи на возрасен.',
                'safety_score' => 100,
                'is_recommended' => true,
                'ai_explanation' => 'Три чекори: НЕ одговарај, БЛОКИРАЈ, КАЖИ на возрасeн. Секогаш!',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
