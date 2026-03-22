<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizAttempt;
use App\Models\QuestionAnswer;
use App\Models\Question;
use App\Models\User;

class QuestionAnswerSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::take(5)->get();
        $questions = Question::all();

        foreach ($users as $user) {
            foreach ($questions->take(10) as $question) {

                $attempt = QuizAttempt::create([
                    'quiz_id' => $question->quiz_id,
                    'user_id' => $user->id,
                    'score' => rand(50, 100),
                    'time_spent_seconds' => rand(30, 600),
                    'status' => rand(0,1) ? 'passed' : 'failed',
                    'ai_feedback' => 'Good job! Keep learning cybersecurity!',
                    'started_at' => now()->subMinutes(rand(10,100)),
                    'completed_at' => now(),
                ]);

                $options = $question->options ?? [];

                if (empty($options)) {
                    continue;
                }

                $randomOption = $options[array_rand($options)];

                $isCorrect = $randomOption == $question->correct_answer;

                QuestionAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'given_answer' => $randomOption,
                    'is_correct' => $isCorrect,
                    'points_earned' => $isCorrect ? $question->points : 0,
                ]);
            }
        }
    }
}
