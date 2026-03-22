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
                'question_text' => 'Which of these passwords is the most secure?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => '123456',
                    'B' => 'password',
                    'C' => 'M@rk0_2024!',
                    'D' => 'marko'
                ],
                'correct_answer' => ['C'],
                'explanation' => 'Password C has uppercase letters, numbers, and special characters.',
                'points' => 10,
                'sort_order' => 1,
            ],
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'Should you share your password with a friend?',
                'type' => 'true_false',
                'options' => [
                    'A' => 'True',
                    'B' => 'False'
                ],
                'correct_answer' => ['B'],
                'explanation' => 'Passwords should never be shared.',
                'points' => 5,
                'sort_order' => 2,
            ],
        ];
        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
