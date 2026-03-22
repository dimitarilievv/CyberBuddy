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
            'title' => 'Quiz: Strong Passwords',
            'description' => 'Test your knowledge about passwords!',
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
                'question_text' => 'Which of these passwords is the strongest?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => '123456',
                    'B' => 'password',
                    'C' => 'M0yD0g$LikesBones!',
                    'D' => 'ana2013',
                ],
                'correct_answer' => ['C'],
                'explanation' => 'Password C is strongest because it has upper/lowercase letters, numbers, special symbols, and length.',
                'points' => 2,
                'sort_order' => 1,
            ],
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'Is it safe to use the same password for all your accounts?',
                'type' => 'true_false',
                'options' => [
                    'A' => 'True',
                    'B' => 'False',
                ],
                'correct_answer' => ['B'],
                'explanation' => 'False. If one password is leaked, all accounts are at risk.',
                'points' => 1,
                'sort_order' => 2,
            ],
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'What is the minimum length for a strong password?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => '4',
                    'B' => '6',
                    'C' => '8',
                    'D' => '12',
                ],
                'correct_answer' => ['D'],
                'explanation' => 'A strong password should be at least 12 characters.',
                'points' => 1,
                'sort_order' => 3,
            ],
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'Which of these should you avoid using as a password?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => 'Your birthday',
                    'B' => 'Your pet name',
                    'C' => 'A random sentence with numbers',
                    'D' => 'Your name + 123',
                ],
                'correct_answer' => ['C'],
                'explanation' => 'A, B, and D are easy to guess. A random sentence with numbers is a better choice.',
                'points' => 2,
                'sort_order' => 4,
            ],
            [
                'quiz_id' => $quiz->id,
                'question_text' => 'Who is it safe to share your password with?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => 'Your best friend',
                    'B' => 'Your teacher',
                    'C' => 'Your parent/guardian',
                    'D' => 'No one',
                ],
                'correct_answer' => ['C'],
                'explanation' => 'Only a parent or guardian should know your password. Do not share it with anyone else.',
                'points' => 2,
                'sort_order' => 5,
            ],
        ];
        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
