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
            return is_array($answer) && count($answer) > 0;
        }

        return !empty($answer);
    }

    public function submit()
    {
        $score = $this->calculateScore();

        $attempt = \App\Models\QuizAttempt::create([
            'quiz_id' => $this->quizId,
            'user_id' => auth()->id(),
            'score' => $score,
            'status' => 'completed',
            'submitted_at' => now(),
        ]);

        foreach ($this->selectedAnswers as $questionId => $answer) {
            $selectedAnswer = is_array($answer) ? json_encode($answer) : $answer;

            \App\Models\QuizAttemptAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'selected_answer' => $selectedAnswer,
            ]);
        }

        // 🔴 Користи го постоечкиот route
        return redirect()->route('quiz_attempts.show', $attempt->id);
    }

    private function calculateScore()
    {
        $score = 0;
        foreach ($this->selectedAnswers as $questionId => $userAnswer) {
            $question = $this->quiz->questions()->find($questionId);
            if ($question) {
                $correctAnswers = $question->correct_answer;

                if (is_string($correctAnswers)) {
                    $correctAnswers = json_decode($correctAnswers, true) ?? [];
                }

                if (!is_array($correctAnswers)) {
                    $correctAnswers = [$correctAnswers];
                }

                if ($question->type === 'multiple_choice') {
                    $userAnswerArray = is_array($userAnswer) ? $userAnswer : [$userAnswer];
                    sort($userAnswerArray);
                    sort($correctAnswers);

                    if ($userAnswerArray === $correctAnswers) {
                        $score++;
                    }
                } else {
                    if ($userAnswer === ($correctAnswers[0] ?? null)) {
                        $score++;
                    }
                }
            }
        }
        return $score;
    }

    public function render()
    {
        $questions = $this->quiz->questions ?? collect();
        $currentQuestion = isset($questions[$this->currentQuestionIndex]) ? $questions[$this->currentQuestionIndex] : null;

        $currentAnswer = null;
        if ($currentQuestion && isset($currentQuestion->id)) {
            $currentAnswer = $this->selectedAnswers[$currentQuestion->id] ?? null;
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
