<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Lesson;
use App\Services\AiContentCreatorService;
use Illuminate\Http\Request;

class TeacherAIContentController extends Controller
{
    public function __construct(private AiContentCreatorService $aiService) {}

    // ── Lesson ─
    public function showLessonForm()
    {
        $modules = Module::orderBy('title')->get();
        return view('teacher.ai.lesson', compact('modules'));
    }

    public function generateLesson(Request $request)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,id',
            'topic'     => 'required|string|max:200',
        ]);

        try {
            $lesson = $this->aiService->generateLesson(
                (int) $request->module_id,
                $request->topic
            );
            return back()->with('success', "Lesson created: \"{$lesson->title}\"")->with('result', $lesson);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Generation failed: ' . $e->getMessage());
        }
    }

    // ── Quiz ─
    public function showQuizForm()
    {
        $lessons = Lesson::orderBy('title')->get();
        return view('teacher.ai.quiz', compact('lessons'));
    }

    public function generateQuiz(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'topic'     => 'required|string|max:200',
        ]);

        try {
            $quiz = $this->aiService->generateQuiz(
                (int) $request->lesson_id,
                $request->topic
            );
            return back()->with('success', "Quiz created: \"{$quiz->title}\"")->with('result', $quiz);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Generation failed: ' . $e->getMessage());
        }
    }

    // ── Scenario ─
    public function showScenarioForm()
    {
        $lessons = Lesson::orderBy('title')->get();
        return view('teacher.ai.scenario', compact('lessons'));
    }

    public function generateScenario(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'topic'     => 'required|string|max:200',
        ]);

        try {
            $scenario = $this->aiService->generateScenario(
                (int) $request->lesson_id,
                $request->topic
            );
            return back()->with('success', "Scenario created: \"{$scenario->title}\"")->with('result', $scenario);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Generation failed: ' . $e->getMessage());
        }
    }
}
