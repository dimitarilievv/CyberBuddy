<?php

namespace App\Livewire\Scenario;

use App\Models\Scenario;
use App\Models\ScenarioAttempt;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\UserProgress;
use Livewire\Component;

class Attempt extends Component
{
    public Scenario $scenario;
    public $selectedChoice = null;
    public $showExplanation = false;
    public $currentExplanation = null;
    public $startTime;

    // Result-related state
    public ?ScenarioAttempt $resultAttempt = null;
    public bool $showResult = false;
    public ?Lesson $nextLesson = null;

    public function mount(Scenario $scenario)
    {
        \Log::info('🔵 Scenario mount', [
            'scenario_id' => $scenario->id,
            'scenario_title' => $scenario->title,
            'choices_count' => $scenario->choices()->count(),
        ]);

        $this->scenario = $scenario->load('choices', 'lesson.module');
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

        // Keep result on the same page: store attempt and show result UI
        $this->resultAttempt = $attempt->fresh(['scenario', 'user', 'chosenChoice']);
        $this->showResult = true;

        // Compute next lesson (if available)
        try {
            $lesson = $this->scenario->lesson;
            if ($lesson) {
                $this->nextLesson = Lesson::where('module_id', $lesson->module_id)
                    ->where('sort_order', '>', ($lesson->sort_order ?? 0))
                    ->orderBy('sort_order')
                    ->first();
            }
        } catch (\Throwable $e) {
            \Log::warning('Could not compute next lesson', ['error' => $e->getMessage()]);
            $this->nextLesson = null;
        }
    }

    // Add method to handle "Back to Lessons" action
    public function backToLessons()
    {
        $lesson = $this->scenario->lesson ?? null;

        // If no lesson or user not authenticated, just redirect to modules index
        if (!$lesson || !auth()->check()) {
            return redirect()->route('modules.index');
        }

        $module = $lesson->module;
        if (!$module) {
            return redirect()->route('modules.index');
        }

        // Always mark the current lesson as completed for the user (they finished the scenario)
        $enrollment = Enrollment::where('user_id', auth()->id())
            ->where('module_id', $module->id)
            ->first();

        if ($enrollment) {
            UserProgress::updateOrCreate(
                ['user_id' => auth()->id(), 'lesson_id' => $lesson->id],
                [
                    'enrollment_id' => $enrollment->id,
                    'status' => 'completed',
                    'completed_at' => now(),
                ]
            );

            // Recalculate module progress percentage and update enrollment
            // Count all lessons in the module so progress matches the module's true size
            $totalLessons = $module->lessons()->count();
            $completedLessons = UserProgress::where('enrollment_id', $enrollment->id)
                ->where('status', 'completed')
                ->count();

            $percentage = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;

            $enrollment->update([
                'progress_percentage' => $percentage,
                'status' => $percentage >= 100 ? 'completed' : 'in_progress',
                'completed_at' => $percentage >= 100 ? now() : null,
            ]);
        }

        // Always redirect to the modules index (do not send user to module page with its "back" button)
        return redirect()->route('modules.index');
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
            'showResult' => $this->showResult,
            'resultAttempt' => $this->resultAttempt,
            'nextLesson' => $this->nextLesson,
        ])->layout('layouts.app');
    }
}
