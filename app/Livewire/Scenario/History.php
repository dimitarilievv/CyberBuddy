<?php

namespace App\Livewire\Scenario;

use App\Models\ScenarioAttempt;
use Livewire\Component;
use Livewire\WithPagination;

class History extends Component
{
    use WithPagination;

    public $scenarioId;
    public $perPage = 10;

    public function mount($scenarioId = null)
    {
        $this->scenarioId = $scenarioId;
    }

    public function render()
    {
        $query = ScenarioAttempt::with('scenario', 'user', 'chosenChoice')
            ->where('user_id', auth()->id());

        if ($this->scenarioId) {
            $query->where('scenario_id', $this->scenarioId);
        }

        $attempts = $query->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.scenario.history', [
            'attempts' => $attempts,
        ])->layout('layouts.app');
    }
}
