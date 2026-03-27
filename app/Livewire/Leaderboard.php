<?php

namespace App\Livewire;

use App\Services\LeaderboardService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Leaderboard extends Component
{
    public string $period = 'all_time';

    public $top;
    public $sorted;

    public function mount(string $period = 'all_time')
    {
        $this->period = $period;
        $this->loadData();
    }

    public function setPeriod(string $period): void
    {
        $this->period = $period;
        $this->loadData();
    }

    protected function loadData(): void
    {
        $service = app(LeaderboardService::class);
        $this->top = $service->getTop(10, $this->period);
        $this->sorted = $this->top ? $this->top->sortByDesc('total_points')->values() : collect();
    }

    public function render()
    {
        return view('livewire.leaderboard');
    }
}
