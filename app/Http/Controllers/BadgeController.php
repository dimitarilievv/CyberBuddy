<?php

namespace App\Http\Controllers;

use App\Services\BadgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    protected BadgeService $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    public function index()
    {
        $user = Auth::user();
        $badges = $user->badges;
        return view('badges.index', compact('badges'));
    }

    public function checkAndAward()
    {
        $user = Auth::user();
        $awarded = $this->badgeService->checkAndAward($user);
        return redirect()->route('badges.index')->with('awarded', $awarded);
    }
}

