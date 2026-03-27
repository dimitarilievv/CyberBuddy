<?php

namespace App\Livewire\Quiz;

use App\Models\Quiz;
use Livewire\Component;

class Timer extends Component
{
    public $quizId;
    public $timeLeft;

    #[Reactive]
    public $duration;

    public function mount($quizId)
    {
        $this->quizId = $quizId;
        $quiz = Quiz::find($quizId);
        $this->timeLeft = $quiz->time_limit * 60; // Convert to seconds
        $this->duration = $this->timeLeft;
    }

    public function render()
    {
        return view('livewire.quiz.timer');
    }
}
