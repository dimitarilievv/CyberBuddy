<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $quizLesson = Lesson::where('slug', 'password-quiz-lesson')->first();

        $quiz = Quiz::create([
            'lesson_id' => $quizLesson->id,
            'title' => 'Квиз: Силни Лозинки',
            'description' => 'Тестирај го твоето знаење за лозинки!',
            'passing_score' => 70,
            'time_limit_minutes' => 10,
            'max_attempts' => 3,
            'shuffle_questions' => true,
            'show_correct_answers' => true,
            'is_published' => true,
        ]);

        $questions = [
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'Која од овие лозинки е НАЈСИЛНА?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => '123456',
                    'B' => 'password',
                    'C' => 'М0јКуч3$ака!Коски',
                    'D' => 'ana2013',
                ],
                'correct_answer' => ['C'],
                'explanation' => 'Лозинката "М0јКуч3$ака!Коски" е најсилна бидејќи содржи големи и мали букви, бројки, специјални знаци и е долга.',
                'points' => 2,
                'sort_order' => 1,
            ],
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'Дали е безбедно да ја користиш истата лозинка за сите твои акаунти?',
                'type' => 'true_false',
                'options' => [
                    'A' => 'Точно',
                    'B' => 'Неточно',
                ],
                'correct_answer' => ['B'],
                'explanation' => 'НЕТОЧНО! Ако хакер ја дознае едната лозинка, ќе има пристап до СИТЕ твои акаунти.',
                'points' => 1,
                'sort_order' => 2,
            ],
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'Колку карактери МИНИМУМ треба да има една силна лозинка?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => '4',
                    'B' => '6',
                    'C' => '8',
                    'D' => '12',
                ],
                'correct_answer' => ['D'],
                'explanation' => 'Силна лозинка треба да има минимум 12 карактери. Подолга лозинка = побезбедна.',
                'points' => 1,
                'sort_order' => 3,
            ],
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'Кое од овие НЕ треба да го користиш како лозинка?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => 'Твојот роденден',
                    'B' => 'Име на миленичето',
                    'C' => 'Случајна реченица со бројки',
                    'D' => 'Твоето име + 123',
                ],
                'correct_answer' => ['C'],
                'explanation' => 'А, B и D се лесни за погодување. Случајна реченица со бројки е добар избор!',
                'points' => 2,
                'sort_order' => 4,
            ],
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'На кого е БЕЗБЕДНО да му ја кажеш твојата лозинка?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => 'Најдобар другар',
                    'B' => 'Наставник',
                    'C' => 'Родител/Старател',
                    'D' => 'Никој',
                ],
                'correct_answer' => ['C'],
                'explanation' => 'Лозинката може да ја знае само твој родител или старател. Не ја споделувај со другари, наставници или кој било друг.',
                'points' => 2,
                'sort_order' => 5,
            ],
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
