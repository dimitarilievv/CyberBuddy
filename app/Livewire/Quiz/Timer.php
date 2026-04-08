<?php

namespace App\Livewire\Quiz;

use App\Models\Quiz;
use Livewire\Component;
use Livewire\Attributes\Reactive;
class Timer extends Component
{
    public int $quizId;
    public int $timeLeft = 0;   // seconds

    public function mount(int $quizId): void
    {
        $this->quizId = $quizId;

        $quiz = Quiz::findOrFail($quizId);

        $minutes = $quiz->time_limit ?? 10;
        $this->timeLeft = $minutes * 60;
    }

    public function render()
    {
        return view('livewire.quiz.timer');
    }
}
