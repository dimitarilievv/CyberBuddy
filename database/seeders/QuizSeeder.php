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
                'explanation' => 'It is not safe to use the same password everywhere.',
                'points' => 2,
                'sort_order' => 2,
            ],
        ];
        foreach ($questions as $question) {
            Question::create($question);
        }
        $deviceLesson = Lesson::where('slug', 'why-updates-matter')->first();
        $quizDevice = Quiz::create([
            'lesson_id' => $deviceLesson->id,
            'title' => 'Quiz: Device Security',
            'description' => 'Test your knowledge about device safety!',
            'passing_score' => 60,
            'time_limit_minutes' => 8,
            'max_attempts' => 3,
            'shuffle_questions' => true,
            'show_correct_answers' => true,
            'is_published' => true,
        ]);
        $privacyLesson = Lesson::where('slug', 'what-is-privacy')->first();
        $quizPrivacy = Quiz::create([
            'lesson_id' => $privacyLesson->id,
            'title' => 'Quiz: Privacy Settings',
            'description' => 'Test your knowledge about privacy online!',
            'passing_score' => 60,
            'time_limit_minutes' => 8,
            'max_attempts' => 3,
            'shuffle_questions' => true,
            'show_correct_answers' => true,
            'is_published' => true,
        ]);
        $footprintLesson = Lesson::where('slug', 'what-is-digital-footprint')->first();
        $quizFootprint = Quiz::create([
            'lesson_id' => $footprintLesson->id,
            'title' => 'Quiz: Digital Footprint',
            'description' => 'Test your knowledge about your digital footprint!',
            'passing_score' => 60,
            'time_limit_minutes' => 8,
            'max_attempts' => 3,
            'shuffle_questions' => true,
            'show_correct_answers' => true,
            'is_published' => true,
        ]);
        $fakeNewsLesson = Lesson::where('slug', 'spotting-fake-news')->first();
        $quizFakeNews = Quiz::create([
            'lesson_id' => $fakeNewsLesson->id,
            'title' => 'Quiz: Fake News Detection',
            'description' => 'Test your knowledge about spotting fake news!',
            'passing_score' => 60,
            'time_limit_minutes' => 8,
            'max_attempts' => 3,
            'shuffle_questions' => true,
            'show_correct_answers' => true,
            'is_published' => true,
        ]);
    }
}
