<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\User;
use App\Models\UserProgress;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TeacherDashboardController extends Controller
{
    use AuthorizesRequests;

    public function approveLesson(Lesson $lesson)
    {
        $lesson->is_published = true;
        $lesson->save();
        return back()->with('success', 'Lesson approved!');
    }

    public function approveQuiz(Quiz $quiz)
    {
        $quiz->is_published = true;
        $quiz->save();
        return back()->with('success', 'Quiz approved!');
    }

    public function approveScenario(Scenario $scenario)
    {
        $scenario->is_published = true;
        $scenario->save();
        return back()->with('success', 'Scenario approved!');
    }

    public function rejectLesson(Lesson $lesson)
    {
        // authorize optionally
        // $this->authorize('delete', $lesson);
        $lesson->delete();
        return back()->with('success', 'Lesson suggestion declined and removed.');
    }

    public function rejectQuiz(Quiz $quiz)
    {
        $quiz->delete();
        return back()->with('success', 'Quiz suggestion declined and removed.');
    }

    public function rejectScenario(Scenario $scenario)
    {
        $scenario->delete();
        return back()->with('success', 'Scenario suggestion declined and removed.');
    }

    public function index(Request $request)
    {
        $teacher = $request->user();

        // 1. All your modules (w/ enrollments and category info)
        $myModules = Module::where('author_id', $teacher->id)
            ->withCount('enrollments')
            ->with('category')
            ->latest()
            ->get();

        // 2. Total Students: All children in system
        $totalStudents = User::where('role', 'child')->count();

        // 3. Safety Score: static for now
        $safetyScore = [
            'score' => 84,
            'trend' => 'stable',
        ];

        // 4. Lessons Completed: All UserProgress completed for ALL modules by this teacher
        $teacherModuleIDs = $myModules->pluck('id');
        $lessonsCompleted = UserProgress::whereIn('enrollment_id',
            Enrollment::whereIn('module_id', $teacherModuleIDs)->pluck('id')
        )
            ->where('status', 'completed')
            ->count();

        // How many lessons completed today for those modules
        $lessonsCompletedToday = UserProgress::whereIn('enrollment_id',
            Enrollment::whereIn('module_id', $teacherModuleIDs)->pluck('id')
        )
            ->where('status', 'completed')
            ->whereDate('completed_at', now()->toDateString())
            ->count();

        // 5. Active Alerts (hardcoded for now)
        // 7. Pending AI suggestions (quizzes and scenarios not published)
        $pendingLessons = Lesson::where('is_published', false)->orderBy('created_at', 'desc')->get();
        $pendingQuizzes = Quiz::where('is_published', false)->orderBy('created_at', 'desc')->get();
        $pendingScenarios = Scenario::where('is_published', false)->orderBy('created_at', 'desc')->get();

       // Now it is safe to use...
        $activeAlerts = $pendingLessons->count() + $pendingQuizzes->count() + $pendingScenarios->count();
        // 6. Manage Modules list (already in $myModules above)

        // 7. Pending AI suggestions (quizzes and scenarios not published)
        $pendingLessons = Lesson::where('is_published', false)->orderBy('created_at', 'desc')->get();
        $pendingQuizzes = Quiz::where('is_published', false)->orderBy('created_at', 'desc')->get();
        $pendingScenarios = Scenario::where('is_published', false)->orderBy('created_at', 'desc')->get();

        // 8. Recent Student Activity (e.g. last 5 UserProgress, badges, leaderboard moves—customize as desired)
        $recentActivities = collect();
        // Example: Completed lesson
        $recentLessonProgress = UserProgress::whereIn('enrollment_id',
            Enrollment::whereIn('module_id', $teacherModuleIDs)->pluck('id')
        )
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->with(['user', 'lesson'])
            ->take(5)
            ->get();
        foreach ($recentLessonProgress as $progress) {
            $recentActivities->push([
                'user' => $progress->user ?? null,
                'message' => "completed '{$progress->lesson->title}'",
                'icon' => '✅',
                'time_ago' => $progress->completed_at ? $progress->completed_at->diffForHumans() : '',
            ]);
        }
        // You can also add failed quizzes, earned badges, leaderboard, etc., to $recentActivities

        // 9. For Content Architect – no backend needed (static for now, or use as future hook)
        $lessons = \App\Models\Lesson::orderBy('title')->get();

        return view('teacher.dashboard', compact(
            'myModules', 'totalStudents', 'safetyScore', 'lessonsCompleted', 'lessonsCompletedToday',
            'activeAlerts', 'pendingLessons', 'pendingQuizzes', 'pendingScenarios', 'recentActivities','lessons'
        ));

    }
    public function storeModule(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'thumbnail' => 'nullable|file|image|max:2048',
            'audience' => 'required|in:child,parent',
            'difficulty' => 'nullable|string|max:50',
            'age_group' => 'nullable|string|max:50',
            'estimated_duration' => 'nullable|string|max:50',
        ]);
        $data['author_id'] = $request->user()->id;
        $data['slug'] = \Str::slug($data['title']) . '-' . uniqid();
        $data['is_published'] = false;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Module::create($data);
        return back()->with('success', 'Module created!');
    }

    public function updateModule(Request $request, Module $module)
    {
        $this->authorize('update', $module);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'thumbnail' => 'nullable|file|image|max:2048',
            'audience' => 'required|in:child,parent',
            'difficulty' => 'nullable|string|max:50',
            'age_group' => 'nullable|string|max:50',
            'estimated_duration' => 'nullable|string|max:50',
        ]);
        $data['slug'] = \Str::slug($data['title']) . '-' . uniqid();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }
        if ($request->input('publish')) {
            $data['is_published'] = true;
            $data['published_at'] = now();
        }
        $module->update($data);

        return back()->with('success', 'Module updated!');
    }

    public function destroyModule(Module $module)
    {
        $this->authorize('delete', $module);
        $module->delete();
        return back()->with('success', 'Module deleted!');
    }

    public function assignLessons(Request $request, Module $module)
    {
        $request->validate(['lessons' => 'nullable|array']);
        $module->lessons()->sync($request->lessons ?? []);
        return back()->with('success', 'Lessons assigned!');
    }
    public function publishModule(Module $module)
    {
        $this->authorize('update', $module);

        $module->is_published = true;
        $module->published_at = now();
        $module->save();

        return back()->with('success', 'Module published!');
    }
}
