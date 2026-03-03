<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $passwordModule = Module::where('slug', 'strong-passwords')->first();

        $lessons = [
            [
                'module_id' => $passwordModule->id,
                'title' => 'Зошто се важни лозинките?',
                'slug' => 'why-passwords-matter',
                'content' => '<h2>🔑 Лозинките се клучот до твојот дигитален свет</h2>
                <p>Замисли дека лозинката е како клучот од твојата куќа. Дали би го оставил клучот под отирачот каде секој може да го најде?</p>
                <h3>Што може да се случи со слаба лозинка?</h3>
                <ul>
                    <li>🎮 Некој може да ти го украде гејминг акаунтот</li>
                    <li>📱 Некој може да ги чита твоите пораки</li>
                    <li>👤 Некој може да се претставува како тебе</li>
                    <li>💰 Некој може да купи работи со твојот акаунт</li>
                </ul>
                <h3>Дали знаеше?</h3>
                <p>Лозинката "123456" може да се погоди за помалку од 1 секунда! А лозинката "М0јКуч3Е$ин!" би требало милиони години.</p>',
                'type' => 'text',
                'estimated_minutes' => 5,
                'sort_order' => 1,
                'is_published' => true,
            ],
            [
                'module_id' => $passwordModule->id,
                'title' => 'Како да креираш силна лозинка',
                'slug' => 'create-strong-password',
                'content' => '<h2>💪 Правила за Силна Лозинка</h2>
                <h3>Секоја силна лозинка треба да има:</h3>
                <ol>
                    <li>✅ Најмалку 12 карактери</li>
                    <li>✅ Големи букви (А, Б, В...)</li>
                    <li>✅ Мали букви (а, б, в...)</li>
                    <li>✅ Бројки (1, 2, 3...)</li>
                    <li>✅ Специјални знаци (!@#$%)</li>
                </ol>
                <h3>🎯 Трик за паметење: Метод на Реченица</h3>
                <p>Земи реченица што ја знаеш и земи ги првите букви:</p>
                <p><strong>"Мојот куче Рекс има 3 години и сака коски!"</strong></p>
                <p>Станува: <code>МкРи3гиск!</code></p>
                <h3>❌ Никогаш не користи:</h3>
                <ul>
                    <li>Твоето име или роденден</li>
                    <li>123456 или password</li>
                    <li>Иста лозинка за сè</li>
                </ul>',
                'type' => 'interactive',
                'estimated_minutes' => 7,
                'sort_order' => 2,
                'is_published' => true,
            ],
            [
                'module_id' => $passwordModule->id,
                'title' => 'Тестирај го твоето знаење!',
                'slug' => 'password-quiz-lesson',
                'content' => '<h2>📝 Време е за квиз!</h2>
                <p>Да видиме колку научи за лозинките. Одговори на прашањата и добиј беџ!</p>',
                'type' => 'text',
                'estimated_minutes' => 5,
                'sort_order' => 3,
                'is_published' => true,
            ],
        ];

        // Лекции за модулот "Препознај го Фишингот"
        $phishingModule = Module::where('slug', 'recognize-phishing')->first();

        $phishingLessons = [
            [
                'module_id' => $phishingModule->id,
                'title' => 'Што е Фишинг?',
                'slug' => 'what-is-phishing',
                'content' => '<h2>🎣 Фишинг = Дигитален Риболов</h2>
                <p>Фишинг е кога некој се обидува да те измами да ги дадеш твоите лични информации (лозинка, број на картичка) преку лажни пораки или веб страници.</p>
                <h3>Како функционира?</h3>
                <ol>
                    <li>📧 Добиваш емаил или порака што изгледа легитимно</li>
                    <li>😰 Пораката те плаши или те мами ("Твојот акаунт е хакиран!" или "Освои iPhone!")</li>
                    <li>🔗 Те праќа на лажна веб страница</li>
                    <li>😱 Ти ги внесуваш податоците и тие ги крадат</li>
                </ol>',
                'type' => 'text',
                'estimated_minutes' => 5,
                'sort_order' => 1,
                'is_published' => true,
            ],
            [
                'module_id' => $phishingModule->id,
                'title' => 'Сценарио: Сомнителен Емаил',
                'slug' => 'phishing-scenario',
                'content' => '<h2>🎭 Сценарио: Добиваш чуден емаил</h2>
                <p>Играј го ова сценарио и одлучи што ќе направиш!</p>',
                'type' => 'scenario',
                'estimated_minutes' => 10,
                'sort_order' => 2,
                'is_published' => true,
            ],
        ];

        foreach (array_merge($lessons, $phishingLessons) as $lesson) {
            Lesson::create($lesson);
        }
    }
}
