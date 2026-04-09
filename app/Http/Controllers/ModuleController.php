<?php

namespace App\Http\Controllers;

use App\Services\ModuleService;
use App\Services\BadgeService;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;

class ModuleController extends Controller
{
    private ModuleService $moduleService;
    private BadgeService $badgeService;
    private EnrollmentRepositoryInterface $enrollmentRepo;

    public function __construct(
        ModuleService $moduleService,
        BadgeService $badgeService,
        EnrollmentRepositoryInterface $enrollmentRepo,
    )
    {
        $this->moduleService = $moduleService;
        $this->badgeService = $badgeService;
        $this->enrollmentRepo = $enrollmentRepo;
    }

    public function index(Request $request)
    {
        $user   = auth()->user();
        $filter = $request->query('filter', 'all');
        $perPage = 9;

        $audience = null;
        if ($user && $user->hasRole('parent')) {
            $audience = 'parent';
        } elseif ($user && $user->hasRole('child')) {
            $audience = 'child';
        }

        $queryParams = $request->query();
        if ($audience) {
            $queryParams['audience'] = $audience;
        }

        $dashboardData = $this->moduleService->getDashboardData($user, $filter, $perPage, $queryParams);

        return view('modules.index', $dashboardData);
    }

    public function show(string $slug)
    {
        $module = $this->moduleService->getModuleBySlug($slug);
        $isEnrolled = false;

        if (auth()->check()) {
            $isEnrolled = $this->moduleService->isUserEnrolled(auth()->id(), $module->id);
        }

        return view('modules.show', compact('module', 'isEnrolled'));
    }

    public function enroll(string $slug)
    {
        $module = $this->moduleService->getModuleBySlug($slug);
        $this->moduleService->enrollUser(auth()->id(), $module->id);

        $firstLesson = $module->lessons()->where('is_published', true)->orderBy('sort_order')->first();
        if ($firstLesson) {
            return redirect()->route('lessons.show', [$module->id, $firstLesson->id])
                ->with('success', 'Successfully enrolled! Mission started.');
        }
        return redirect()->route('modules.show', $slug)
            ->with('success', 'Successfully enrolled!');
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('modules.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'difficulty'  => 'nullable|string|max:50',
            'age_group'   => 'nullable|string|max:50',
            'estimated_duration' => 'nullable|string|max:50',
        ]);
        $data['author_id'] = auth()->id(); // make sure teacher is logged in
        $data['slug'] = Str::slug($data['title']) . '-' . uniqid();

        $this->moduleService->createModule($data);

        return redirect()->route('modules.index')->with('success', 'Module created!');
    }

    public function edit(Module $module)
    {
        $categories = Category::orderBy('name')->get();
        return view('modules.edit', compact('module', 'categories'));
    }

    public function update(Request $request, Module $module)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'difficulty'  => 'nullable|string|max:50',
            'age_group'   => 'nullable|string|max:50',
            'estimated_duration' => 'nullable|string|max:50',
        ]);
        $data['slug'] = Str::slug($data['title']) . '-' . uniqid();

        $this->moduleService->updateModule($module, $data);

        return redirect()->route('modules.index')->with('success', 'Module updated!');
    }

    public function destroy(Module $module)
    {
        $this->moduleService->deleteModule($module);
        return redirect()->route('modules.index')->with('success', 'Module deleted!');
    }
}
