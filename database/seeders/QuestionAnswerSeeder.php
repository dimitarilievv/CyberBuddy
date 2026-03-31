<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizAttempt;
use App\Models\QuestionAnswer;
use App\Models\Question;
use App\Models\User;
use App\Models\Quiz;
use Carbon\Carbon;

class QuestionAnswerSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users (children and parents)
        $users = User::whereIn('role', ['child', 'parent'])->get();

        if ($users->count() === 0) {
            $this->command->error('No users found.');
            return;
        }

        // Get all quizzes
        $quizzes = Quiz::all();

        if ($quizzes->count() === 0) {
            $this->command->error('No quizzes found. Please run QuizSeeder first.');
            return;
        }

        $totalAttempts = 0;

        // For each user, create attempts for some quizzes
        foreach ($users as $user) {
            // Each user attempts 2-4 random quizzes
            $randomQuizzes = $quizzes->random(min(rand(2, 4), $quizzes->count()));

            foreach ($randomQuizzes as $quiz) {
                // Get questions for this quiz
                $questions = Question::where('quiz_id', $quiz->id)->get();

                if ($questions->count() === 0) {
                    continue;
                }

                // Calculate score based on how many questions are answered correctly
                $correctCount = 0;
                $answers = [];

                // Answer each question
                foreach ($questions as $question) {
                    $options = $question->options ?? [];

                    // Determine if user answers correctly (70% chance if first attempt, varies for subsequent attempts)
                    $isCorrect = false;
                    $givenAnswer = null;

                    if ($question->type === 'true_false') {
                        // For true/false, random answer
                        $possibleAnswers = ['A', 'B'];
                        $givenAnswer = $possibleAnswers[array_rand($possibleAnswers)];
                        $isCorrect = $givenAnswer === ($question->correct_answer[0] ?? '');
                    } else {
                        // For multiple choice
                        if (count($options) > 0) {
                            // Randomly pick an option key (A, B, C, D)
                            $optionKeys = array_keys($options);
                            $givenAnswer = $optionKeys[array_rand($optionKeys)];
                            $isCorrect = in_array($givenAnswer, $question->correct_answer ?? []);
                        }
                    }

                    if ($isCorrect) {
                        $correctCount++;
                    }

                    $answers[] = [
                        'question_id' => $question->id,
                        'given_answer' => $givenAnswer,
                        'is_correct' => $isCorrect,
                        'points_earned' => $isCorrect ? $question->points : 0,
                    ];
                }

                // Calculate total score percentage
                $totalPoints = $questions->sum('points');
                $earnedPoints = collect($answers)->sum('points_earned');
                $scorePercentage = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100) : 0;

                // Determine if passed (based on quiz passing score)
                $passed = $scorePercentage >= $quiz->passing_score;

                // Create a single quiz attempt
                $attempt = QuizAttempt::create([
                    'quiz_id' => $quiz->id,
                    'user_id' => $user->id,
                    'score' => $scorePercentage,
                    'time_spent_seconds' => rand(60, 600), // 1-10 minutes
                    'status' => $passed ? 'completed' : 'failed',
                    'ai_feedback' => $passed
                        ? "Great job! You passed the {$quiz->title}. Keep up the good work!"
                        : "Good effort! You scored {$scorePercentage}%. Review the material and try again to improve your score.",
                    'started_at' => Carbon::now()->subMinutes(rand(10, 1440)), // Random time in last 24 hours
                    'completed_at' => Carbon::now(),
                ]);

                // Create answers for each question
                foreach ($answers as $answer) {
                    QuestionAnswer::create([
                        'quiz_attempt_id' => $attempt->id,
                        'question_id' => $answer['question_id'],
                        'given_answer' => $answer['given_answer'],
                        'is_correct' => $answer['is_correct'],
                        'points_earned' => $answer['points_earned'],
                    ]);
                }

                $totalAttempts++;
            }
        }

        // Also create some attempts for specific users to make data more realistic
        $specificUsers = User::whereIn('email', ['ana@cyberbuddy.mk', 'marko@cyberbuddy.mk'])->get();

        foreach ($specificUsers as $user) {
            $passwordQuiz = Quiz::where('title', 'Quiz: Strong Passwords')->first();
            if ($passwordQuiz) {
                $questions = Question::where('quiz_id', $passwordQuiz->id)->get();

                if ($questions->count() > 0) {
                    // Ana gets a perfect score
                    $isAna = $user->email === 'ana@cyberbuddy.mk';

                    $answers = [];
                    $correctCount = 0;

                    foreach ($questions as $question) {
                        $isCorrect = $isAna ? true : (rand(0, 1) == 1);
                        $givenAnswer = null;

                        if ($isCorrect) {
                            $givenAnswer = $question->correct_answer[0] ?? 'A';
                            $correctCount++;
                        } else {
                            $options = array_keys($question->options ?? ['A', 'B']);
                            $givenAnswer = $options[array_rand($options)];
                        }

                        $answers[] = [
                            'question_id' => $question->id,
                            'given_answer' => $givenAnswer,
                            'is_correct' => $isCorrect,
                            'points_earned' => $isCorrect ? $question->points : 0,
                        ];
                    }

                    $totalPoints = $questions->sum('points');
                    $earnedPoints = collect($answers)->sum('points_earned');
                    $scorePercentage = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100) : 0;
                    $passed = $scorePercentage >= $passwordQuiz->passing_score;

                    $attempt = QuizAttempt::create([
                        'quiz_id' => $passwordQuiz->id,
                        'user_id' => $user->id,
                        'score' => $scorePercentage,
                        'time_spent_seconds' => $isAna ? 120 : 180,
                        'status' => $passed ? 'completed' : 'failed',
                        'ai_feedback' => $passed
                            ? "Excellent work! You really understand password security!"
                            : "Keep practicing! Remember to use long passwords with mixed characters.",
                        'started_at' => Carbon::now()->subDays(rand(1, 7)),
                        'completed_at' => Carbon::now(),
                    ]);

                    foreach ($answers as $answer) {
                        QuestionAnswer::create([
                            'quiz_attempt_id' => $attempt->id,
                            'question_id' => $answer['question_id'],
                            'given_answer' => $answer['given_answer'],
                            'is_correct' => $answer['is_correct'],
                            'points_earned' => $answer['points_earned'],
                        ]);
                    }

                    $totalAttempts++;
                }
            }
        }

        $this->command->info('Quiz attempts and answers seeded successfully!');
        $this->command->info("Total attempts created: {$totalAttempts}");
    }
}
