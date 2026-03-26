<?php

namespace App\Http\Controllers;

use App\Services\ModuleService;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;

class ModuleController extends Controller
{
    private ModuleService $moduleService;

    public function __construct(
        ModuleService $moduleService,
    )
    {
        $this->moduleService = $moduleService;
    }

    public function index()
    {
        $modules = $this->moduleService->getPublishedModules();

        return view('modules.index', compact('modules'));
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
