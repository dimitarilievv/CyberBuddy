<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $quiz = Quiz::first();

        if (!$quiz) {
            return;
        }

        $questions = [
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'Која од овие лозинки е најбезбедна?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => '123456',
                    'B' => 'password',
                    'C' => 'M@rk0_2024!',
                    'D' => 'marko'
                ],
                'correct_answer' => ['C'],
                'explanation' => 'Лозинката C има големи букви, бројки и специјални знаци.',
                'points' => 10,
                'sort_order' => 1,
            ],
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'Треба ли да ја споделиш лозинката со другар?',
                'type' => 'true_false',
                'options' => [
                    'A' => 'Точно',
                    'B' => 'Неточно'
                ],
                'correct_answer' => ['B'],
                'explanation' => 'Лозинките никогаш не треба да се споделуваат.',
                'points' => 5,
                'sort_order' => 2,
            ],
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
