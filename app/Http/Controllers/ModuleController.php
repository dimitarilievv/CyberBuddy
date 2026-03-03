<?php

namespace App\Http\Controllers;

use App\Services\ModuleService;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    private ModuleService $moduleService;

    public function __construct(
        ModuleService $moduleService,
    ) {
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
            ->with('success', 'Успешно се запиша во модулот!');
    }
}
