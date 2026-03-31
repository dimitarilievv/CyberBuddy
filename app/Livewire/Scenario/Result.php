<?php

namespace App\Livewire\Scenario;

use App\Models\ScenarioAttempt;
use Livewire\Component;

class Result extends Component
{
    public ScenarioAttempt $attempt;
    public $isPassed = false;
    public $badgesEarned = [];

    public function mount(ScenarioAttempt $attempt)
    {
        $this->attempt = $attempt->load('scenario', 'user', 'chosenChoice');
        $this->isPassed = $this->attempt->safety_score >= 70;
        $this->checkBadges();
    }

    private function checkBadges()
    {
        if ($this->isPassed) {
            $this->badgesEarned[] = ['name' => 'Safety Champion', 'icon' => '🏆'];
        }

        if ($this->attempt->safety_score === 100) {
            $this->badgesEarned[] = ['name' => 'Perfect Score', 'icon' => '⭐'];
        }

        // Avoid calling undefined relationship on User; query ScenarioAttempt directly
        $userId = $this->attempt->user_id ?? ($this->attempt->user->id ?? null);

        if ($userId) {
            $successfulAttempts = ScenarioAttempt::where('user_id', $userId)
                ->where('safety_score', '>=', 70)
                ->count();

            if ($successfulAttempts >= 5) {
                $this->badgesEarned[] = ['name' => 'Safety Expert', 'icon' => '🎓'];
            }
        }
    }

    public function render()
    {
        return view('livewire.scenario.result', [
            'attempt' => $this->attempt,
            'isPassed' => $this->isPassed,
            'badgesEarned' => $this->badgesEarned,
        ])->layout('layouts.app');
    }
}
