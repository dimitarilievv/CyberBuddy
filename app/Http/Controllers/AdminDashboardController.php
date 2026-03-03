<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Module;
use App\Models\Enrollment;
use App\Models\AiInteraction;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_children' => User::where('role', 'child')->count(),
            'total_parents' => User::where('role', 'parent')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_modules' => Module::count(),
            'published_modules' => Module::where('is_published', true)->count(),
            'total_enrollments' => Enrollment::count(),
            'total_ai_interactions' => AiInteraction::count(),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentEnrollments = Enrollment::with(['user', 'module'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentEnrollments'));
    }
}
