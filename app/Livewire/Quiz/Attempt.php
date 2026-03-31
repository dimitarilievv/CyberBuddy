<?php

namespace App\Livewire\Quiz;

use App\Models\Quiz;
use App\Models\Module;
use Livewire\Component;
use Livewire\Attributes\On;

class Attempt extends Component
{
    public Quiz $quiz;
    public ?Module $module = null;
    public $quizId;
    public $moduleId;
    public $currentQuestionIndex = 0;
    public $selectedAnswers = [];
    public $showHint = false;

    #[On('quiz-timer-ended')]
    public function submitQuiz()
    {
        $this->submit();
    }

    public function mount($quizId = null, $moduleId = null)
    {
        if ($quizId) {
            $this->quizId = $quizId;
            $this->quiz = Quiz::with(['questions' => function($q) {
                $q->orderBy('sort_order');
            }])->findOrFail($quizId);
        }

        if ($moduleId) {
            $this->moduleId = $moduleId;
            $this->module = Module::findOrFail($moduleId);
        }

        $this->selectedAnswers = [];
    }

    public function selectAnswer($answer)
    {
        $questions = $this->quiz->questions;
        if ($this->currentQuestionIndex < count($questions)) {
            $currentQuestion = $questions[$this->currentQuestionIndex];

            if ($currentQuestion->type === 'multiple_choice') {
                if (!isset($this->selectedAnswers[$currentQuestion->id])) {
                    $this->selectedAnswers[$currentQuestion->id] = [];
                }

                if (in_array($answer, $this->selectedAnswers[$currentQuestion->id])) {
                    $this->selectedAnswers[$currentQuestion->id] = array_values(array_filter(
                        $this->selectedAnswers[$currentQuestion->id],
                        fn($a) => $a !== $answer
                    ));
                } else {
                    $this->selectedAnswers[$currentQuestion->id][] = $answer;
                }
            } else {
                $this->selectedAnswers[$currentQuestion->id] = $answer;
            }
        }
    }

    public function nextQuestion()
    {
        $questions = $this->quiz->questions;
        if ($this->currentQuestionIndex < count($questions) - 1) {
            $this->currentQuestionIndex++;
            $this->showHint = false;
            $this->dispatch('question-changed', $this->currentQuestionIndex + 1);
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
            $this->showHint = false;
            $this->dispatch('question-changed', $this->currentQuestionIndex + 1);
        }
    }

    public function toggleHint()
    {
        $this->showHint = !$this->showHint;
    }

    public function canProceed()
    {
        $currentQuestion = $this->quiz->questions[$this->currentQuestionIndex] ?? null;
        if (!$currentQuestion) return false;

        $answer = $this->selectedAnswers[$currentQuestion->id] ?? null;

        if ($currentQuestion->type === 'multiple_choice') {
            // Accept arrays with items OR a truthy scalar value
            if (is_array($answer)) {
                return count($answer) > 0;
            }
            return !empty($answer);
        }

        return !empty($answer);
    }

    public function submit()
    {
        // Ensure all questions have been answered
        $questions = $this->quiz->questions;
        foreach ($questions as $q) {
            if (!array_key_exists($q->id, $this->selectedAnswers) || $this->selectedAnswers[$q->id] === null || $this->selectedAnswers[$q->id] === '') {
                session()->flash('error', 'Please answer all questions before submitting the quiz.');
                return;
            }
        }

        // Calculate score and get per-question results
        $calculation = $this->calculateScore();
        $score = $calculation['score'] ?? 0;
        $perQuestion = $calculation['details'] ?? [];

        // Create attempt
        $attempt = \App\Models\QuizAttempt::create([
            'quiz_id' => $this->quizId,
            'user_id' => auth()->id(),
            'score' => $score,
            'status' => 'completed',
            'submitted_at' => now(),
        ]);

        // Persist answers with detailed fields
        foreach ($perQuestion as $questionId => $info) {
            // determine selected_option_id if single choice, otherwise null
            $selectedOptionId = $info['selected_option_id'] ?? null;
            $givenAnswer = $info['given_answer'] ?? null;
            $isCorrect = $info['is_correct'] ?? false;
            $pointsEarned = $info['points_earned'] ?? null;
            $aiExplanation = $info['ai_explanation'] ?? null;

            \App\Models\QuizAttemptAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'selected_option_id' => $selectedOptionId,
                'given_answer' => $givenAnswer,
                'is_correct' => $isCorrect,
                'points_earned' => $pointsEarned,
                'ai_explanation' => $aiExplanation,
            ]);
        }

        // Mark the related lesson as completed when the quiz finishes (if quiz belongs to a lesson/module)
        try {
            $quizModel = $this->quiz->loadMissing(['lesson.module']);
            $lesson = $quizModel->lesson ?? null;
            if ($lesson && auth()->check()) {
                $module = $lesson->module;
                if ($module) {
                    $enrollment = \App\Models\Enrollment::where('user_id', auth()->id())
                        ->where('module_id', $module->id)
                        ->first();

                    if ($enrollment) {
                        \App\Models\UserProgress::updateOrCreate(
                            ['user_id' => auth()->id(), 'lesson_id' => $lesson->id],
                            [
                                'enrollment_id' => $enrollment->id,
                                'status' => 'completed',
                                'completed_at' => now(),
                            ]
                        );

                        $totalLessons = $module->lessons()->where('is_published', true)->count();
                        $completedLessons = \App\Models\UserProgress::where('enrollment_id', $enrollment->id)
                            ->where('status', 'completed')
                            ->count();

                        $percentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

                        $enrollment->update([
                            'progress_percentage' => $percentage,
                            'status' => $percentage >= 100 ? 'completed' : 'in_progress',
                            'completed_at' => $percentage >= 100 ? now() : null,
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Don't block quiz submission if progress update fails
            \Log::warning('Quiz submission: could not mark lesson completed', ['error' => $e->getMessage()]);
        }

        return redirect()->route('quiz_attempts.show', $attempt->id);
    }

    private function calculateScore()
    {
        $score = 0;
        $details = [];

        foreach ($this->selectedAnswers as $questionId => $userAnswer) {
            $question = $this->quiz->questions()->find($questionId);
            if (!$question) continue;

            $correctAnswers = $question->correct_answer;

            if (is_string($correctAnswers)) {
                $decoded = json_decode($correctAnswers, true);
                $correctAnswers = $decoded === null ? [$correctAnswers] : $decoded;
            }

            if (!is_array($correctAnswers)) {
                $correctAnswers = [$correctAnswers];
            }

            // Build mapping from original option key -> display key (A/B/C...) to match view behavior
            $optionsRaw = $question->options;
            if (is_string($optionsRaw)) {
                $optionsArr = json_decode($optionsRaw, true) ?? [];
            } else {
                $optionsArr = $optionsRaw ?? [];
            }
            $optionKeyToDisplay = [];
            $idx = 0;
            foreach ($optionsArr as $optKey => $optVal) {
                $display = is_numeric($optKey) ? chr(65 + $idx) : (string)$optKey;
                $optionKeyToDisplay[(string)$optKey] = $display;
                $idx++;
            }

            // Convert correctAnswers (stored as original keys or already as display keys) to display keys
            $correctDisplay = [];
            foreach ($correctAnswers as $ca) {
                $caStr = (string)$ca;
                $correctDisplay[] = $optionKeyToDisplay[$caStr] ?? $caStr;
            }

            $isCorrect = false;
            $points = $question->points ?? 1; // fallback to 1 point if not defined
            $selectedOptionId = null;
            $givenAnswerValue = null;

            if ($question->type === 'multiple_choice') {
                // Expect userAnswer to be array of option ids or option keys
                if (is_array($userAnswer)) {
                    $userAnswerArray = $userAnswer;
                } elseif (is_string($userAnswer)) {
                    $decoded = json_decode($userAnswer, true);
                    $userAnswerArray = is_array($decoded) ? $decoded : [$userAnswer];
                } else {
                    // scalar (int/float/etc.) -> wrap into array
                    $userAnswerArray = [$userAnswer];
                }

                // Normalize to strings
                $userAnswerArray = array_values(array_map(fn($v) => (string)$v, (array)$userAnswerArray));

                // For multiple_choice, compare sets (order-insensitive) using display keys
                $normalizedCorrect = array_values(array_map(fn($v) => (string)$v, (array)$correctDisplay));
                sort($userAnswerArray);
                sort($normalizedCorrect);

                if ($userAnswerArray === $normalizedCorrect) {
                    $isCorrect = true;
                    $score += $points;
                }

                $givenAnswerValue = $userAnswerArray;
            } else {
                // single choice or text - userAnswer will be a display key (A/B/...) or text
                $givenAnswerValue = is_array($userAnswer) ? ($userAnswer[0] ?? null) : $userAnswer;

                $normalizedCorrect = array_values(array_map(fn($v) => (string)$v, (array)$correctDisplay));
                if ($givenAnswerValue !== null && in_array((string)$givenAnswerValue, $normalizedCorrect, true)) {
                    $isCorrect = true;
                    $score += $points;
                }
            }

            // Optional: request AI explanation or other processing here
            $aiExplanation = null;

            $details[$questionId] = [
                'selected_option_id' => $selectedOptionId,
                'given_answer' => $givenAnswerValue,
                'is_correct' => $isCorrect,
                'points_earned' => $isCorrect ? $points : 0,
                'ai_explanation' => $aiExplanation,
            ];
        }

        return ['score' => $score, 'details' => $details];
    }

    public function render()
    {
        $questions = $this->quiz->questions ?? collect();
        $currentQuestion = isset($questions[$this->currentQuestionIndex]) ? $questions[$this->currentQuestionIndex] : null;

        $currentAnswer = null;
        if ($currentQuestion && isset($currentQuestion->id)) {
            $raw = $this->selectedAnswers[$currentQuestion->id] ?? null;

            // Normalize per question type
            if ($currentQuestion->type === 'multiple_choice') {
                if ($raw === null) {
                    $currentAnswer = [];
                } elseif (is_array($raw)) {
                    $currentAnswer = $raw;
                } else {
                    // string or json
                    $decoded = is_string($raw) ? json_decode($raw, true) : null;
                    $currentAnswer = is_array($decoded) ? $decoded : (is_string($raw) ? [$raw] : (array)$raw);
                }
            } else {
                $currentAnswer = is_array($raw) ? ($raw[0] ?? null) : $raw;
            }
        }

        $options = [];
        if ($currentQuestion && $currentQuestion->options) {
            if (is_string($currentQuestion->options)) {
                $options = json_decode($currentQuestion->options, true) ?? [];
            } else {
                $options = $currentQuestion->options;
            }
        }

        return view('livewire.quiz.attempt', [
            'currentQuestion' => $currentQuestion,
            'currentAnswer' => $currentAnswer,
            'questionNumber' => $this->currentQuestionIndex + 1,
            'totalQuestions' => count($questions),
            'options' => $options,
            'module' => $this->module,
        ]);
    }
}
