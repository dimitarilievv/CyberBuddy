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
        // === PASSWORD QUIZ ===
        $quizLesson = Lesson::where('slug', 'password-quiz-lesson')->first();

        if ($quizLesson) {
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
                    'question_text' => 'Is it safe to use the same password for all your accounts?',
                    'type' => 'true_false',
                    'options' => [
                        'A' => 'True',
                        'B' => 'False',
                    ],
                    'correct_answer' => ['B'],
                    'explanation' => 'It is not safe to use the same password everywhere. If one account gets hacked, all your accounts are at risk.',
                    'points' => 2,
                    'sort_order' => 2,
                ],
                [
                    'question_text' => 'What is a good length for a strong password?',
                    'type' => 'multiple_choice',
                    'options' => [
                        'A' => '4-6 characters',
                        'B' => '8-10 characters',
                        'C' => '12+ characters',
                        'D' => 'Any length is fine',
                    ],
                    'correct_answer' => ['C'],
                    'explanation' => 'Strong passwords should be at least 12 characters long. The longer, the harder it is to guess.',
                    'points' => 2,
                    'sort_order' => 3,
                ],
                [
                    'question_text' => 'What should you do if a website asks you to change your password regularly?',
                    'type' => 'multiple_choice',
                    'options' => [
                        'A' => 'Ignore it, it\'s not important',
                        'B' => 'Use the same password with a number change',
                        'C' => 'Create a new, strong password',
                        'D' => 'Stop using the website',
                    ],
                    'correct_answer' => ['C'],
                    'explanation' => 'Changing passwords regularly helps keep your accounts secure. Always create a new, strong password.',
                    'points' => 2,
                    'sort_order' => 4,
                ],
            ];

            foreach ($questions as $question) {
                $question['quiz_id'] = $quiz->id;
                Question::create($question);
            }
        }

        // === DEVICE SECURITY QUIZ ===
        $deviceLesson = Lesson::where('slug', 'why-updates-matter')->first();

        if ($deviceLesson) {
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

            $questions = [
                [
                    'question_text' => 'Why are software updates important?',
                    'type' => 'multiple_choice',
                    'options' => [
                        'A' => 'They add new features only',
                        'B' => 'They fix security bugs and protect from hackers',
                        'C' => 'They make your device slower',
                        'D' => 'They are not important',
                    ],
                    'correct_answer' => ['B'],
                    'explanation' => 'Updates fix security vulnerabilities that hackers could use to access your device.',
                    'points' => 2,
                    'sort_order' => 1,
                ],
                [
                    'question_text' => 'Is it safe to download apps from outside the official app store?',
                    'type' => 'true_false',
                    'options' => [
                        'A' => 'True - it\'s fine',
                        'B' => 'False - it can be dangerous',
                    ],
                    'correct_answer' => ['B'],
                    'explanation' => 'Apps from outside official stores may contain malware or viruses.',
                    'points' => 2,
                    'sort_order' => 2,
                ],
                [
                    'question_text' => 'What should you do before connecting to public Wi-Fi?',
                    'type' => 'multiple_choice',
                    'options' => [
                        'A' => 'Nothing, it\'s safe',
                        'B' => 'Turn on VPN if possible',
                        'C' => 'Share your password with everyone',
                        'D' => 'Disable all security settings',
                    ],
                    'correct_answer' => ['B'],
                    'explanation' => 'Public Wi-Fi can be risky. Using a VPN helps protect your data.',
                    'points' => 2,
                    'sort_order' => 3,
                ],
            ];

            foreach ($questions as $question) {
                $question['quiz_id'] = $quizDevice->id;
                Question::create($question);
            }
        }

        // === PRIVACY QUIZ ===
        $privacyLesson = Lesson::where('slug', 'what-is-privacy')->first();

        if ($privacyLesson) {
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

            $questions = [
                [
                    'question_text' => 'What information is safe to share on social media?',
                    'type' => 'multiple_choice',
                    'options' => [
                        'A' => 'Your home address',
                        'B' => 'Your favorite hobby',
                        'C' => 'Your phone number',
                        'D' => 'Your school name and schedule',
                    ],
                    'correct_answer' => ['B'],
                    'explanation' => 'Sharing hobbies is generally safe. Personal contact info and location details should stay private.',
                    'points' => 2,
                    'sort_order' => 1,
                ],
                [
                    'question_text' => 'Should you accept friend requests from people you don\'t know?',
                    'type' => 'true_false',
                    'options' => [
                        'A' => 'True - the more friends, the better',
                        'B' => 'False - only accept people you know',
                    ],
                    'correct_answer' => ['B'],
                    'explanation' => 'Only accept friend requests from people you know in real life to protect your privacy.',
                    'points' => 2,
                    'sort_order' => 2,
                ],
                [
                    'question_text' => 'Why do apps ask for permissions?',
                    'type' => 'multiple_choice',
                    'options' => [
                        'A' => 'To access features they need',
                        'B' => 'To spy on you',
                        'C' => 'To make money from your data',
                        'D' => 'Both A and C',
                    ],
                    'correct_answer' => ['D'],
                    'explanation' => 'Apps need permissions for features, but some collect data for advertising. Only grant necessary permissions.',
                    'points' => 2,
                    'sort_order' => 3,
                ],
            ];

            foreach ($questions as $question) {
                $question['quiz_id'] = $quizPrivacy->id;
                Question::create($question);
            }
        }

        // === DIGITAL FOOTPRINT QUIZ ===
        $footprintLesson = Lesson::where('slug', 'what-is-digital-footprint')->first();

        if ($footprintLesson) {
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

            $questions = [
                [
                    'question_text' => 'What is a digital footprint?',
                    'type' => 'multiple_choice',
                    'options' => [
                        'A' => 'The size of your computer',
                        'B' => 'The trail of data you leave online',
                        'C' => 'A type of computer virus',
                        'D' => 'Your email password',
                    ],
                    'correct_answer' => ['B'],
                    'explanation' => 'Your digital footprint is all the information about you that exists online.',
                    'points' => 2,
                    'sort_order' => 1,
                ],
                [
                    'question_text' => 'Can deleted posts be found later?',
                    'type' => 'true_false',
                    'options' => [
                        'A' => 'True - people might have screenshots',
                        'B' => 'False - deleted means gone forever',
                    ],
                    'correct_answer' => ['A'],
                    'explanation' => 'Even if you delete a post, someone might have taken a screenshot or saved it.',
                    'points' => 2,
                    'sort_order' => 2,
                ],
                [
                    'question_text' => 'How can you check your digital footprint?',
                    'type' => 'multiple_choice',
                    'options' => [
                        'A' => 'Google your name',
                        'B' => 'Check privacy settings',
                        'C' => 'Review old posts',
                        'D' => 'All of the above',
                    ],
                    'correct_answer' => ['D'],
                    'explanation' => 'Searching your name, checking settings, and reviewing posts helps you understand your footprint.',
                    'points' => 2,
                    'sort_order' => 3,
                ],
            ];

            foreach ($questions as $question) {
                $question['quiz_id'] = $quizFootprint->id;
                Question::create($question);
            }
        }

        // === FAKE NEWS QUIZ ===
        $fakeNewsLesson = Lesson::where('slug', 'spotting-fake-news')->first();

        if ($fakeNewsLesson) {
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

            $questions = [
                [
                    'question_text' => 'What should you do before sharing a news article?',
                    'type' => 'multiple_choice',
                    'options' => [
                        'A' => 'Share it immediately',
                        'B' => 'Check if it comes from a reliable source',
                        'C' => 'Only share if it has many likes',
                        'D' => 'Share it without reading',
                    ],
                    'correct_answer' => ['B'],
                    'explanation' => 'Always check the source and verify information before sharing.',
                    'points' => 2,
                    'sort_order' => 1,
                ],
                [
                    'question_text' => 'Is it safe to believe everything you see on social media?',
                    'type' => 'true_false',
                    'options' => [
                        'A' => 'True - people only post truth',
                        'B' => 'False - always verify',
                    ],
                    'correct_answer' => ['B'],
                    'explanation' => 'Not everything on social media is true. Always fact-check before believing or sharing.',
                    'points' => 2,
                    'sort_order' => 2,
                ],
                [
                    'question_text' => 'What is a red flag that a news story might be fake?',
                    'type' => 'multiple_choice',
                    'options' => [
                        'A' => 'The website has a strange URL',
                        'B' => 'It has spelling mistakes',
                        'C' => 'It makes you very emotional',
                        'D' => 'All of the above',
                    ],
                    'correct_answer' => ['D'],
                    'explanation' => 'Fake news often has strange URLs, mistakes, and tries to make you emotional.',
                    'points' => 2,
                    'sort_order' => 3,
                ],
            ];

            foreach ($questions as $question) {
                $question['quiz_id'] = $quizFakeNews->id;
                Question::create($question);
            }
        }

        $this->command->info('Quizzes seeded successfully!');
    }
}
