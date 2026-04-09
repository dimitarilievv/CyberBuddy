<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Module;
use App\Models\Enrollment;
use App\Models\AiInteraction;
use App\Models\ReportedContent;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function resolveReport(Request $request, ReportedContent $reportedContent)
    {
        $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $reportedContent->status = 'resolved';
        $reportedContent->admin_notes = $request->admin_notes;
        $reportedContent->reviewed_by = auth()->id();
        $reportedContent->reviewed_at = now();
        $reportedContent->save();

        return back()->with('success', 'Report marked as resolved!');
    }
    public function index()
    {
        // Core stats
        $stats = [
            'total_users'        => User::count(),
            'active_users'       => User::where('is_active', true)->count(),
            'total_children'     => User::where('role', 'child')->count(),
            'total_parents'      => User::where('role', 'parent')->count(),
            'total_teachers'     => User::where('role', 'teacher')->count(),
            'total_modules'      => Module::count(),
            'published_modules'  => Module::where('is_published', true)->count(),
            'pending_reports'    => ReportedContent::where('status', 'pending')->count(),
            'total_enrollments'  => Enrollment::count(),
            'completed_jobs'     => UserProgress::where('status', 'completed')->count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentEnrollments = Enrollment::with(['user', 'module'])->latest()->take(5)->get();
        $publishedModules = Module::where('is_published', true)->latest()->take(5)->get();
        $pendingReports = ReportedContent::where('status', 'pending')->latest()->take(5)->get();
        $userProgressCompleted = UserProgress::where('status', 'completed')->latest()->take(5)->get();
        $modules = \App\Models\Module::with('author','category')->orderByDesc('updated_at')->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        $lessons = \App\Models\Lesson::orderBy('title')->get();  // add this
        $pendingLessons = \App\Models\Lesson::where('is_published', false)->latest()->get();
        $pendingQuizzes = \App\Models\Quiz::where('is_published', false)->latest()->get();
        $pendingScenarios = \App\Models\Scenario::where('is_published', false)->latest()->get();
        return view('admin.dashboard', compact(
            'stats', 'recentUsers', 'recentEnrollments',
            'publishedModules', 'pendingReports', 'userProgressCompleted',
            'modules', 'categories', 'lessons', 'pendingLessons', 'pendingQuizzes', 'pendingScenarios'
        ));
    }

    public function users(Request $request)
    {
        $users = User::orderBy('name')->paginate(30);
        return view('admin.users', compact('users'));
    }

    public function updateUserRole(User $user)
    {
        $role = request('role');

        if (! in_array($role, ['child', 'parent', 'teacher', 'admin'])) {
            return back()->with('error', 'Invalid role selected.');
        }

        $user->role = $role;
        $user->save();

        $user->syncRoles([$role]);

        return back()->with('status', "Role for {$user->name} updated to {$role}.");
    }

    public function exportUsers()
    {
        $filename = 'users_export_' . now()->format('Ymd_His') . '.csv';
        $users = User::all(['id', 'name', 'email', 'role', 'is_active']);

        $response = new StreamedResponse(function () use ($users) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Role', 'Active']);
            foreach ($users as $u) {
                fputcsv($handle, [$u->id, $u->name, $u->email, $u->role, $u->is_active ? 'active' : 'inactive']);
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        return $response;
    }
    public function modules()
    {
        $modules = Module::with('author','category')->orderByDesc('updated_at')->get();
        $categories = Category::orderBy('name')->get();
        return view('admin.modules', compact('modules', 'categories'));
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
        $data['author_id'] = auth()->id();
        $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
        $data['is_published'] = false;
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }
        Module::create($data);
        return back()->with('success', 'Module created!');
    }

    public function updateModule(Request $request, Module $module)
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
        $data['slug'] = Str::slug($data['title']) . '-' . uniqid();
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }
        $module->update($data);
        return back()->with('success', 'Module updated!');
    }

    public function destroyModule(Module $module)
    {
        $module->delete();
        return back()->with('success', 'Module deleted!');
    }

    public function publishModule(Module $module)
    {
        $module->is_published = ! $module->is_published;
        $module->published_at = $module->is_published ? now() : null;
        $module->save();
        return back()->with('success', $module->is_published ? 'Module published!' : 'Module unpublished!');
    }


}

