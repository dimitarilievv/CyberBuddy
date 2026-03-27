<?php

namespace App\Livewire\Scenario;

use App\Models\Scenario;
use App\Models\ScenarioAttempt;
use Livewire\Component;

class Attempt extends Component
{
    public Scenario $scenario;
    public $selectedChoice = null;
    public $showExplanation = false;
    public $currentExplanation = null;
    public $startTime;

    public function mount(Scenario $scenario)
    {
        \Log::info('🔵 Scenario mount', [
            'scenario_id' => $scenario->id,
            'scenario_title' => $scenario->title,
            'choices_count' => $scenario->choices()->count(),
        ]);

        $this->scenario = $scenario->load('choices');
        $this->startTime = time();

        \Log::info('🔵 After load', [
            'loaded_choices' => $this->scenario->choices->count(),
        ]);
    }

    public function selectChoice($choiceId)
    {
        $choice = $this->scenario->choices->find($choiceId);

        if (!$choice) {
            \Log::error('❌ Choice not found', ['choice_id' => $choiceId]);
            return;
        }

        $this->selectedChoice = [
            'id' => $choiceId,
            'text' => $choice->choice_text,
            'score' => $choice->safety_score,
            'consequence' => $choice->consequence,
            'icon' => $choice->icon ?? '⚡',
        ];

        $this->currentExplanation = $choice->ai_explanation;
        $this->showExplanation = true;
    }

    public function submit()
    {
        if (!$this->selectedChoice) {
            session()->flash('error', 'Please select a choice');
            return;
        }

        $timeSpent = time() - $this->startTime;

        $attempt = ScenarioAttempt::create([
            'scenario_id' => $this->scenario->id,
            'user_id' => auth()->id(),
            'chosen_choice_id' => $this->selectedChoice['id'],
            'safety_score' => $this->selectedChoice['score'],
            'ai_feedback' => $this->currentExplanation,
            'time_spent_seconds' => $timeSpent,
        ]);

        return redirect()->route('scenario.result', $attempt->id);
    }

    public function render()
    {
        $choices = $this->scenario->choices;
        $scenarioProgress = $this->scenario->order ?? 1;
        $totalScenarios = Scenario::count();

        \Log::info('🔵 Render', [
            'choices' => $choices->count(),
            'scenario_id' => $this->scenario->id,
        ]);

        return view('livewire.scenario.attempt', [
            'choices' => $choices,
            'selectedChoice' => $this->selectedChoice,
            'scenarioProgress' => $scenarioProgress,
            'totalScenarios' => $totalScenarios,
        ])->layout('layouts.app');
    }
}
