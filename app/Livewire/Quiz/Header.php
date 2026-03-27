<?php

namespace App\Livewire\Quiz;

use App\Models\Quiz;
use Livewire\Component;
use Livewire\Attributes\On;

class Header extends Component
{
    public Quiz $quiz;
    public $currentQuestion = 1;
    public $totalQuestions = 0;

    #[On('question-changed')]
    public function updateQuestion($questionNumber)
    {
        $this->currentQuestion = $questionNumber;
    }

    public function mount()
    {
        $this->totalQuestions = $this->quiz->questions ? count($this->quiz->questions) : 0;
    }

    public function render()
    {
        return view('livewire.quiz.header');
    }
}
