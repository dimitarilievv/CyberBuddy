@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header & User Info -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h2 class="text-xs font-semibold text-cyan-600 uppercase mb-1 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 4C9.243 4 7 6.243 7 9c0 5.25 5 11 5 11s5-5.75 5-11c0-2.757-2.243-5-5-5z"/></svg>
                    Curriculum
                </h2>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Missions Control</h1>
                <span class="text-gray-500">Choose a topic and become a digital superhero!</span>
            </div>
            <!-- Next Milestone -->
            <div class="flex items-center bg-gray-100 rounded-lg px-6 py-3 shadow-sm mt-6 md:mt-0 w-fit">
                @if($nextBadge && $missionsToNextBadge > 0)
                    <span class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded font-bold mr-3">Next Milestone:</span>
                    <div>
                        <span class="font-semibold text-gray-800">{{ $missionsToNextBadge }} mission{{ $missionsToNextBadge > 1 ? 's' : '' }}</span> for <span class="font-semibold text-blue-600">"{{ $badgeName }}"</span> Badge
                    </div>
                @else
                    <span class="inline-block bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-bold mr-3">All badge milestones achieved!</span>
                @endif
            </div>
        </div>
        <!-- User Stats -->
        <div class="bg-white border rounded-xl shadow-md p-6 flex flex-wrap md:flex-nowrap justify-between items-center mb-8">
            <div>
                <div class="text-sm text-gray-500">Your Training Grounds</div>
                @if($user)
                    <div class="font-semibold text-lg text-gray-800">
                        Level {{ $user->level ?? 1 }} Cyber Scout
                        • {{ $missionsCompleted }}/{{ $totalMissions }} Missions Completed
                    </div>
                @else
                    <div class="font-semibold text-lg text-gray-800">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> to track your missions!
                    </div>
                @endif
            </div>
            <div class="flex gap-8 mt-4 md:mt-0 text-center">
                <div>
                    <div class="text-2xl font-bold text-blue-600">{{ $leaderboardStats['points'] ?? 0 }}</div>
                    <div class="text-gray-500 text-xs">Points</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-yellow-500">{{ $leaderboardStats['streak'] ?? 0 }}</div>
                    <div class="text-gray-500 text-xs">Streak</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-cyan-600">{{ $leaderboardStats['badges'] ?? 0 }}</div>
                    <div class="text-gray-500 text-xs">Badges</div>
                </div>
            </div>
        </div>
        <!-- Filter Bar -->
        <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
            <form method="GET" class="flex gap-2">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <button type="submit" name="filter" value="all" class="{{ $filter == 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }} font-semibold px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">All</button>
                <button type="submit" name="filter" value="not_started" class="{{ $filter == 'not_started' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }} px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Not Started</button>
                <button type="submit" name="filter" value="in_progress" class="{{ $filter == 'in_progress' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }} px-4 py-2 rounded-lg text-sm hover:bg-blue-700">In Progress</button>
                <button type="submit" name="filter" value="completed" class="{{ $filter == 'completed' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600' }} px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Completed</button>
            </form>
{{--            <div class="relative">--}}
{{--                <input type="text" class="border border-gray-200 rounded-lg pl-10 pr-4 py-2 w-56 placeholder-gray-400 text-sm" placeholder="Search missions...">--}}
{{--                <svg class="absolute left-3 top-2 text-gray-400 h-5 w-5 pointer-events-none" fill="currentColor" viewBox="0 0 20 20"><path d="M8 4a6 6 0 100 12A6 6 0 008 4zM2 8a6 6 0 1112 0A6 6 0 012 8zm11.293 7.707a1 1 0 01-1.414 0l-2.387-2.387A7.962 7.962 0 018 16a8 8 0 118-8 8 8 0 01-4 6.93l2.387 2.387a1 1 0 010 1.414z"/></svg>--}}
{{--            </div>--}}
        </div>
        <!-- Module Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($modules as $module)
                @php
                    $progressData = $user ? app('App\\Services\\ModuleService')->getUserModuleProgress($user->id, $module->id) : ['status' => 'not_started', 'progress' => 0];
                    $progress = $progressData['progress'];
                    $status = $progressData['status'];
                @endphp
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-4 flex flex-col min-h-[360px] relative">
                    <img src="{{ $module->thumbnail ?? 'public/img/modules/cyber.jpeg' }}" alt="No thumbnail provided" class="rounded-xl object-cover w-full h-32 mb-3 bg-gray-100" />
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">{{ $module->title }}</h2>
                    <p class="text-gray-600 text-sm mb-4 flex-1">{{ $module->description }}</p>
                    <div class="mb-3">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-gray-500">Module Progress</span>
                            <span class="font-bold text-blue-600">{{ $progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                    <div>
                        @if($status == 'completed')
                            <button class="w-full bg-gray-300 text-gray-700 font-semibold py-2 rounded-lg cursor-default" disabled>Completed</button>
                        @elseif($status == 'enrolled' && $progress > 0)
                            <a href="{{ route('modules.show', $module->slug) }}" class="w-full inline-block bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 text-center">Continue Mission</a>
                        @elseif($status == 'enrolled')
                            <a href="{{ route('modules.show', $module->slug) }}" class="w-full inline-block bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 text-center">Review Content</a>
                        @else
                            <form method="POST" action="{{ route('modules.enroll', $module->slug) }}">
                                @csrf
                                <button type="submit" class="w-full bg-blue-50 text-blue-700 font-semibold py-2 rounded-lg hover:bg-blue-100 border border-blue-500">Start Mission</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <!-- Pagination Example -->
        <div class="mt-8 flex justify-center items-center gap-6 text-gray-500 text-sm">
            {{ $modules->links() }}
        </div>
    </div>
@endsection
