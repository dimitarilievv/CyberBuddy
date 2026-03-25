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
        $quizDevice = Quiz::where('title', 'Quiz: Device Security')->first();
        $quizPrivacy = Quiz::where('title', 'Quiz: Privacy Settings')->first();
        $quizFootprint = Quiz::where('title', 'Quiz: Digital Footprint')->first();
        $quizFakeNews = Quiz::where('title', 'Quiz: Fake News Detection')->first();
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
            // Device Security Quiz
            [
                'quiz_id' => $quizDevice->id,
                'question_text' => 'Why should you update your device regularly?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => 'To get new games',
                    'B' => 'To fix bugs and protect from hackers',
                    'C' => 'To make it slower',
                    'D' => 'To change the color',
                ],
                'correct_answer' => ['B'],
                'explanation' => 'Updates fix bugs and protect your device from hackers.',
                'points' => 5,
                'sort_order' => 1,
            ],
            [
                'quiz_id' => $quizDevice->id,
                'question_text' => 'What is the safest way to lock your device?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => 'No lock',
                    'B' => 'PIN or password',
                    'C' => 'Leave it open',
                    'D' => 'Let your friend set the password',
                ],
                'correct_answer' => ['B'],
                'explanation' => 'Always use a PIN, password, or fingerprint.',
                'points' => 5,
                'sort_order' => 2,
            ],
            // Privacy Settings Quiz
            [
                'quiz_id' => $quizPrivacy->id,
                'question_text' => 'What does privacy mean online?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => 'Sharing everything',
                    'B' => 'Keeping personal info safe',
                    'C' => 'Posting your address',
                    'D' => 'Telling strangers your secrets',
                ],
                'correct_answer' => ['B'],
                'explanation' => 'Privacy means keeping your personal information safe.',
                'points' => 5,
                'sort_order' => 1,
            ],
            [
                'quiz_id' => $quizPrivacy->id,
                'question_text' => 'Who should you accept as friends on social media?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => 'Anyone',
                    'B' => 'Only people you know',
                    'C' => 'Everyone in your city',
                    'D' => 'No one',
                ],
                'correct_answer' => ['B'],
                'explanation' => 'Only accept friend requests from people you know.',
                'points' => 5,
                'sort_order' => 2,
            ],
            // Digital Footprint Quiz
            [
                'quiz_id' => $quizFootprint->id,
                'question_text' => 'What is a digital footprint?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => 'Your shoe size',
                    'B' => 'Your online activity trace',
                    'C' => 'A type of virus',
                    'D' => 'A computer part',
                ],
                'correct_answer' => ['B'],
                'explanation' => 'A digital footprint is the trace you leave online.',
                'points' => 5,
                'sort_order' => 1,
            ],
            [
                'quiz_id' => $quizFootprint->id,
                'question_text' => 'How can you clean up your digital footprint?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => 'Delete old posts',
                    'B' => 'Remove unused accounts',
                    'C' => 'Check privacy settings',
                    'D' => 'All of the above',
                ],
                'correct_answer' => ['D'],
                'explanation' => 'All of these help clean up your digital footprint.',
                'points' => 5,
                'sort_order' => 2,
            ],
            // Fake News Detection Quiz
            [
                'quiz_id' => $quizFakeNews->id,
                'question_text' => 'How can you spot fake news?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => 'Check the source',
                    'B' => 'Look for other reports',
                    'C' => 'Ask an adult',
                    'D' => 'All of the above',
                ],
                'correct_answer' => ['D'],
                'explanation' => 'All of these are good ways to spot fake news.',
                'points' => 5,
                'sort_order' => 1,
            ],
            [
                'quiz_id' => $quizFakeNews->id,
                'question_text' => 'What should you do before sharing news online?',
                'type' => 'multiple_choice',
                'options' => [
                    'A' => 'Share it right away',
                    'B' => 'Fact-check it first',
                    'C' => 'Ignore it',
                    'D' => 'Send to everyone',
                ],
                'correct_answer' => ['B'],
                'explanation' => 'Always fact-check before sharing news.',
                'points' => 5,
                'sort_order' => 2,
            ],
        ];
        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
