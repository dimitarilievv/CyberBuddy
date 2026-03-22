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
                $attempt = QuizAttempt::factory()->create([
                    'quiz_id' => $question->quiz_id,
                    'user_id' => $user->id
                ]);

                QuestionAnswer::create([
                    'quiz_attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'given_answer' => $question->options()->inRandomOrder()->first()->id,
                    'is_correct' => (bool)rand(0,1),
                    'points_earned' => rand(0, $question->points)
                ]);
            }
        }
    }
}
