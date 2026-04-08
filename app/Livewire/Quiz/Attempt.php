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
    public bool $showReview = false; // <--- add this

    #[On('quiz-timer-ended')]
    public function submitQuiz()
    {
        $this->submit();
    }

    public function mount($quizId = null, $moduleId = null)
    {
        if ($quizId) {
            $this->quizId = $quizId;
            $this->quiz = Quiz::with(['questions' => function ($q) {
                $q->orderBy('sort_order');
            }])->findOrFail($quizId);
        }

        if ($moduleId) {
            $this->moduleId = $moduleId;
            $this->module = Module::findOrFail($moduleId);
        }

        $this->selectedAnswers = [];
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

    public function goNextOrReview()
    {
        $questions = $this->quiz->questions;
        $lastIndex = count($questions) - 1;

        // If we are not yet on the last question, just move to next
        if ($this->currentQuestionIndex < $lastIndex) {
            $this->nextQuestion();
            return;
        }

        // We are on the last question: switch to review mode
        $this->showReview = true;
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
        if (!$currentQuestion) {
            return false;
        }

        $answer = $this->selectedAnswers[$currentQuestion->id] ?? null;

        if ($currentQuestion->type === 'multiple_choice') {
            if (!is_array($answer)) {
                return false;
            }

            // At least one checked (truthy) value
            return collect($answer)->filter(fn ($v) => (bool) $v)->isNotEmpty();
        }

        return $answer !== null && $answer !== '';
    }

    public function submit()
    {
        \Log::info('Submit called', [
            'quiz_id' => $this->quizId,
            'selectedAnswers' => $this->selectedAnswers,
        ]);

        $questions = $this->quiz->questions;

        // Ensure all questions have been answered
        foreach ($questions as $q) {
            $answer = $this->selectedAnswers[$q->id] ?? null;

            if ($q->type === 'multiple_choice') {
                if (!is_array($answer)) {
                    \Log::warning('Submit blocked: multi-choice not array', [
                        'question_id' => $q->id,
                        'answer' => $answer,
                    ]);
                    session()->flash('error', 'Please answer all questions before submitting the quiz.');
                    return;
                }

                $hasAny = collect($answer)->filter(fn ($v) => (bool) $v)->isNotEmpty();
                if (! $hasAny) {
                    \Log::warning('Submit blocked: multi-choice empty', [
                        'question_id' => $q->id,
                        'answer' => $answer,
                    ]);
                    session()->flash('error', 'Please answer all questions before submitting the quiz.');
                    return;
                }
            } else {
                if ($answer === null || $answer === '') {
                    \Log::warning('Submit blocked: single/TF empty', [
                        'question_id' => $q->id,
                        'answer' => $answer,
                    ]);
                    session()->flash('error', 'Please answer all questions before submitting the quiz.');
                    return;
                }
            }
        }

        \Log::info('Submit passed validation', [
            'quiz_id' => $this->quizId,
        ]);

        // Calculate score and per-question details
        $calculation = $this->calculateScore();
        $score = $calculation['score'] ?? 0;
        $perQuestion = $calculation['details'] ?? [];

        try {
            $attempt = \App\Models\QuizAttempt::create([
                'quiz_id'      => $this->quizId,
                'user_id'      => auth()->id(),
                'score'        => $score,
                'status'       => 'completed',
                'submitted_at' => now(),
            ]);

            \Log::info('Submit created attempt', [
                'attempt_id' => $attempt->id,
            ]);

            foreach ($perQuestion as $questionId => $info) {
                $selectedOptionId = $info['selected_option_id'] ?? null;
                $rawGiven         = $info['given_answer'] ?? null;
                $isCorrect        = $info['is_correct'] ?? false;
                $pointsEarned     = $info['points_earned'] ?? 0;
                $aiExplanation    = $info['ai_explanation'] ?? null;

                $givenAnswer = $this->normalizeGivenAnswerToLetters($rawGiven);

                \App\Models\QuizAttemptAnswer::create([
                    'quiz_attempt_id'   => $attempt->id,
                    'question_id'       => $questionId,
                    'selected_option_id'=> $selectedOptionId,
                    'given_answer'      => $givenAnswer,
                    'is_correct'        => $isCorrect,
                    'points_earned'     => $pointsEarned,
                    'ai_explanation'    => $aiExplanation,
                ]);
            }
        } catch (\Throwable $e) {
            \Log::error('Submit failed during save', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'There was a problem saving your quiz attempt.');
            return;
        }

        \Log::info('Submit redirecting', [
            'attempt_id' => $attempt->id,
        ]);

        return redirect()->route('quiz_attempts.show', $attempt->id);
    }

    private function calculateScore(): array
    {
        $score   = 0;
        $details = [];

        foreach ($this->selectedAnswers as $questionId => $userAnswer) {
            $question = $this->quiz->questions()->find($questionId);
            if (!$question) {
                continue;
            }

            // Because of model casts, correct_answer is already an array
            $correctAnswers = $question->correct_answer ?? [];

            // Normalize options to an array
            $optionsArr = $question->options ?? [];
            $optionKeys = array_keys($optionsArr);

            // Map original option key -> letter A,B,C,...
            $keyToLetter = [];
            foreach ($optionKeys as $index => $optKey) {
                $keyToLetter[(string)$optKey] = chr(65 + $index);
            }

            $toLetter = function ($v) use ($keyToLetter) {
                $s = strtoupper((string) $v);

                // Already a single letter A–Z
                if (strlen($s) === 1 && ctype_alpha($s)) {
                    return $s;
                }

                // Exact match to an original option key
                if (array_key_exists((string)$v, $keyToLetter)) {
                    return $keyToLetter[(string)$v];
                }

                // Numeric index fallback
                if (ctype_digit((string)$v)) {
                    $n = (int)$v;
                    if ($n >= 0 && $n < 26) {
                        return chr(65 + $n);
                    }
                }

                return $s;
            };

            $isCorrect        = false;
            $points           = $question->points ?? 1;
            $selectedOptionId = null;
            $givenAnswerValue = null;

            if ($question->type === 'multiple_choice') {
                // Normalize $userAnswer into a flat array of letters
                if (is_array($userAnswer)) {
                    // handle both ['A', 'C'] and ['A' => true, 'C' => false]
                    $userValues = array_values(array_filter(
                        is_int(array_key_first($userAnswer))
                            ? $userAnswer
                            : array_keys(array_filter($userAnswer, fn($v) => (bool)$v))
                    ));
                } else {
                    $userValues = [$userAnswer];
                }

                $userLetters = array_values(array_unique(array_map($toLetter, $userValues)));



                // Normalize correct answers to letters
                $correctValues  = is_array($correctAnswers) ? $correctAnswers : [$correctAnswers];
                $correctLetters = array_values(array_unique(array_map($toLetter, $correctValues)));

                sort($userLetters);
                sort($correctLetters);

                if ($userLetters === $correctLetters) {
                    $isCorrect = true;
                    $score += $points;
                }

                $givenAnswerValue = $userLetters;
            } else {
                // single choice / true_false
                $rawValue         = is_array($userAnswer) ? ($userAnswer[0] ?? null) : $userAnswer;
                $givenAnswerValue = $rawValue === null ? null : $toLetter($rawValue);

                $correctValues  = is_array($correctAnswers) ? $correctAnswers : [$correctAnswers];
                $correctLetters = array_values(array_unique(array_map($toLetter, $correctValues)));

                if ($givenAnswerValue !== null && in_array($givenAnswerValue, $correctLetters, true)) {
                    $isCorrect = true;
                    $score += $points;
                }
            }

            $aiExplanation = null;

            $details[$questionId] = [
                'selected_option_id' => $selectedOptionId,
                'given_answer'       => $givenAnswerValue,
                'is_correct'         => $isCorrect,
                'points_earned'      => $isCorrect ? $points : 0,
                'ai_explanation'     => $aiExplanation,
            ];
        }

        return [
            'score'   => $score,
            'details' => $details,
        ];
    }

    /**
     * Ensure stored value is letters (A,B,C,...) and arrays where appropriate.
     */
    private function normalizeGivenAnswerToLetters($value)
    {
        $mapOne = function ($v) {
            if (is_string($v) && strlen($v) === 1 && ctype_alpha($v)) {
                return strtoupper($v);
            }

            if (is_int($v) || (is_string($v) && ctype_digit($v))) {
                $idx = (int)$v;
                if ($idx >= 0 && $idx < 26) {
                    return chr(65 + $idx);
                }
            }

            return (string)$v;
        };

        if (is_array($value)) {
            return array_map($mapOne, $value);
        }

        if ($value === null) {
            return null;
        }

        return $mapOne($value);
    }

    public function render()
    {
        $questions       = $this->quiz->questions ?? collect();
        $currentQuestion = $questions[$this->currentQuestionIndex] ?? null;

        $currentAnswer = null;

        if ($currentQuestion && isset($currentQuestion->id)) {
            $raw = $this->selectedAnswers[$currentQuestion->id] ?? null;

            if ($currentQuestion->type === 'multiple_choice') {
                if ($raw === null) {
                    $currentAnswer = [];
                } elseif (is_array($raw)) {
                    // When using wire:model="selectedAnswers.{id}.{letter}", $raw will be like ['A' => true, 'C' => true]
                    // We want just ['A', 'C']
                    $currentAnswer = array_keys(array_filter($raw, fn($v) => (bool)$v));
                } else {
                    $currentAnswer = [$raw];
                }
            } else {
                $currentAnswer = is_array($raw) ? ($raw[0] ?? null) : $raw;
            }
        }

        $options = [];
        if ($currentQuestion && $currentQuestion->options) {
            $options = $currentQuestion->options ?? [];
        }

        // DEBUG LOGS – remove when done
        \Log::info('QuizAttempt Livewire state', [
            'quiz_id'            => $this->quizId,
            'current_question_id'=> $currentQuestion?->id,
            'current_question_type' => $currentQuestion?->type,
            'selectedAnswers'    => $this->selectedAnswers,
            'currentAnswer'      => $currentAnswer,
            'options'            => $options,
        ]);

        return view('livewire.quiz.attempt', [
            'currentQuestion' => $currentQuestion,
            'currentAnswer'   => $currentAnswer,
            'questionNumber'  => $this->currentQuestionIndex + 1,
            'totalQuestions'  => count($questions),
            'options'         => $options,
            'module'          => $this->module,
        ]);
    }
}
