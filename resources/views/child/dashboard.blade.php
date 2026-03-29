@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8 space-y-10">

        <!-- Top: Current Adventure + Safety Level -->
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Current Adventure -->
            <div class="flex-1 bg-blue-50 rounded-2xl p-6 flex items-center shadow-md">
                <div class="flex-1">
                    <span class="inline-block bg-blue-600 text-white text-xs font-semibold rounded-full px-3 py-1 mb-2">CURRENT ADVENTURE</span>
                    <h2 class="text-2xl font-bold mb-1">
                        Level {{ $currentModule->level ?? '-' }}
                        <span class="text-blue-600">&bull;</span>
                        {{ $currentModule->title ?? 'No module in progress' }}
                    </h2>
                    <p class="text-gray-500 mb-4">{{ $currentModule->description ?? 'Start your first mission!' }}</p>
                    @if(isset($moduleProgress))
                        <div class="mb-3">
                            <small class="text-gray-500">Module Progress</small>
                            <div class="w-full bg-blue-100 h-2 rounded mt-1 mb-1">
                                <div class="bg-blue-500 h-2 rounded transition-all duration-300" style="width: {{ $moduleProgress }}%"></div>
                            </div>
                            <small class="font-bold text-blue-600">{{ $moduleProgress }}%</small>
                        </div>
                    @endif
                    @if(isset($currentModule))
                        <a href="{{ route('modules.show', $currentModule->slug) }}"
                           class="inline-block mt-3 px-5 py-2 bg-blue-600 text-white rounded-full shadow hover:bg-blue-700 transition">Continue Mission</a>
                    @endif
                </div>
                <div class="hidden md:flex ml-6 items-center">
                    <img src="{{ $currentModule->thumbnail ?? '/img/default-module.svg' }}"
                         alt="Adventure" class="w-28 h-28 object-contain rounded-xl shadow-lg" />
                </div>
            </div>
            <!-- Safety Level -->
            <div class="w-full md:w-72 flex flex-col items-center justify-center bg-white border border-blue-100 rounded-2xl p-6 shadow text-center">
                <h4 class="text-blue-600 font-bold text-sm tracking-wide">SAFETY LEVEL</h4>
                <div class="text-3xl font-bold mb-1 tracking-wide">MASTER</div>
                <div class="flex justify-center items-center w-full my-2">
                    <svg viewBox="0 0 36 36" class="w-24 h-24">
                        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                        <circle cx="18" cy="18" r="15.9155" fill="none"
                                stroke="#2563eb" stroke-width="3.5"
                                stroke-linecap="round"
                                stroke-dasharray="{{ $safetyLevel ?? 65 }}, 100"
                                transform="rotate(-90 18 18)"/>
                        <text x="18" y="21" text-anchor="middle" font-size="11" font-weight="bold" fill="#2563eb">
                            {{ $safetyLevel ?? 65 }}%
                        </text>
                    </svg>
                </div>
                <div class="text-gray-400 text-xs min-h-8 mt-2">
                    You're safer than {{ $saferThanPercent ?? 82 }}% of other kids this week!
                </div>
            </div>
        </div>

        <!-- Next Training Missions (Modules) -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xl font-bold text-gray-900">Next Training Missions</h3>
                <a href="{{ route('modules.index') }}"
                   class="text-blue-500 text-sm font-semibold hover:underline">View All Lessons &raquo;</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
                @forelse($modules as $module)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 flex flex-col h-full group">
                        <img src="{{ $module->thumbnail ?? '/img/default-module.svg' }}" class="w-full rounded-t-xl object-cover h-28" alt="module thumbnail">
                        <div class="p-4 flex-1 flex flex-col">
                            <span class="inline-block bg-gray-200 text-gray-700 text-xs font-semibold rounded px-2 py-1 mb-2">{{ $module->category->name ?? 'Uncategorized' }}</span>
                            <div class="font-bold text-lg mb-1 text-gray-800 group-hover:text-blue-600 transition">{{ $module->title }}</div>
                            <div class="text-xs text-gray-400 mb-2 flex gap-1 items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/><path d="M12 7v5l3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                {{ $module->estimated_duration ?? '5' }} min
                            </div>
                            <div class="font-semibold text-blue-600 text-xs mb-3">+{{ $module->xp ?? '50' }} XP</div>
                            <a href="{{ route('modules.show', $module->slug) }}" class="mt-auto text-center inline-block py-2 px-4 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold text-xs shadow-sm transition">Start Mission</a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-10 text-gray-400">No missions found.</div>
                @endforelse
            </div>
        </div>

        <!-- Badges & All Lesson Progress -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Recent Badges -->
            <div class="bg-white rounded-xl shadow p-5">
                <div class="flex justify-between items-center mb-4">
                    <span class="font-bold text-gray-700">Recent Badges</span>
                    <a href="{{ route('badges.index') }}" class="text-blue-500 text-xs font-semibold hover:underline">
                        Gallery &raquo;
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @forelse ($recentBadges as $userBadge)
                        @php
                            $badge = $userBadge->badge;
                            $color = $badge->color ?? '#6B7280';
                            $earnedDate = $userBadge->earned_at
                                ? \Carbon\Carbon::parse($userBadge->earned_at)->format('M d, Y')
                                : null;
                        @endphp

                        <div class="rounded-2xl p-4 flex flex-col items-center text-center transition-all duration-200 hover:-translate-y-1 hover:shadow-md cursor-pointer group"
                             style="background-color: {{ $color }}15;">

                            {{-- Icon --}}
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3"
                                 style="background-color: {{ $color }}25;">
                                @include('badges._icon', ['icon' => $badge->icon, 'color' => $color, 'size' => 28])
                            </div>

                            {{-- Name --}}
                            <p class="font-bold text-sm text-gray-800 leading-tight mb-1 group-hover:text-blue-600 transition-colors">
                                {{ $badge->name }}
                            </p>

                            {{-- Type pill --}}
                            <span class="text-[10px] font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full mb-2"
                                  style="color: {{ $color }}; background-color: {{ $color }}20;">
                    {{ ucfirst($badge->type) }}
                </span>

                            {{-- Date --}}
                            @if($earnedDate)
                                <p class="text-[11px] text-gray-400 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2.2"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    {{ $earnedDate }}
                                </p>
                            @endif
                        </div>

                    @empty
                        <div class="col-span-2 py-8 flex flex-col items-center gap-2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.5"
                                 stroke-linecap="round" stroke-linejoin="round" class="text-gray-300">
                                <circle cx="12" cy="8" r="6"/>
                                <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
                            </svg>
                            <p class="text-sm font-medium">No badges yet.</p>
                            <a href="{{ route('modules.index') }}" class="text-xs text-blue-500 font-semibold hover:underline">
                                Start earning →
                            </a>
                        </div>
                    @endforelse
                </div>

                {{-- Footer: show total count if more than displayed --}}
                @if($recentBadges->count() > 0)
                    <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                        <p class="text-xs text-gray-400">
                            Showing {{ $recentBadges->count() }} recent
                            {{ Str::plural('badge', $recentBadges->count()) }}
                        </p>
                        <a href="{{ route('badges.index') }}"
                           class="text-xs font-bold text-blue-600 hover:text-blue-700 transition-colors">
                            View all →
                        </a>
                    </div>
                @endif
            </div>

            <!-- All Lesson Progress -->
            <div class="bg-white rounded-xl shadow p-5 flex flex-col">
                <h4 class="font-bold text-lg mb-4 text-gray-800">Your Lesson Progress</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                        <tr class="bg-blue-50">
                            <th class="py-2 px-2">Lesson</th>
                            <th class="py-2 px-2">Status</th>
                            <th class="py-2 px-2 text-right">Started</th>
                            <th class="py-2 px-2 text-right">Completed</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($allProgress as $progress)
                            <tr>
                                <td class="py-1 px-2">{{ $progress->lesson->title ?? 'N/A' }}</td>
                                <td class="py-1 px-2">
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                                {{ ($progress->status ?? '') === 'completed' ? 'bg-green-100 text-green-700' : ( ($progress->status ?? '') === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600') }}">
                                {{ ucfirst($progress->status ?? 'unknown') }}
                            </span>
                                </td>
                                <td class="py-1 px-2 text-right">
                                    {{ $progress->started_at ? $progress->started_at->format('M d, Y H:i') : '-' }}
                                </td>
                                <td class="py-1 px-2 text-right">
                                    {{ $progress->completed_at ? $progress->completed_at->format('M d, Y H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-gray-400 text-center">No progress data available.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- My Leaderboard Stats -->
        <div class="bg-white rounded-2xl shadow p-6">
            <h4 class="font-bold text-lg mb-4 text-gray-800">My Leaderboard Stats ({{ ucfirst($statsPeriod ?? 'all_time') }})</h4>
            @if($myStats)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-y-3 gap-x-6 text-gray-700">
                    <div><span class="font-semibold">Rank:</span> {{ $myStats->rank ?? '-' }}</div>
                    <div><span class="font-semibold">Total Points:</span> {{ $myStats->total_points }}</div>
                    <div><span class="font-semibold">Modules Completed:</span> {{ $myStats->modules_completed }}</div>
                    <div><span class="font-semibold">Quizzes Passed:</span> {{ $myStats->quizzes_passed }}</div>
                    <div><span class="font-semibold">Badges Earned:</span> {{ $myStats->badges_earned }}</div>
                    <div><span class="font-semibold">Current Streak:</span> {{ $myStats->current_streak }}</div>
                    <div><span class="font-semibold">Longest Streak:</span> {{ $myStats->longest_streak }}</div>
                </div>
            @else
                <div class="text-gray-400 py-6 text-center">No stats available for you in this period.</div>
            @endif
        </div>

        <!-- Full Leaderboard Table -->
        <div class="bg-white rounded-2xl shadow p-6">
            <h4 class="font-bold text-lg mb-4 text-gray-800">Leaderboard – Top 10</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                    <tr class="bg-blue-50">
                        <th class="py-2 px-2">#</th>
                        <th class="py-2 px-2">User</th>
                        <th class="py-2 px-2 text-right">Points</th>
                        <th class="py-2 px-2 text-right">Modules</th>
                        <th class="py-2 px-2 text-right">Quizzes</th>
                        <th class="py-2 px-2 text-right">Badges</th>
                        <th class="py-2 px-2 text-right">Current Streak</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($fullLeaderboard as $place => $entry)
                        <tr class="{{ ($entry->is_self ?? false) ? 'bg-blue-100 font-bold' : '' }}">
                            <td class="py-1 px-2">{{ $place + 1 }}</td>
                            <td class="py-1 px-2">
                                {{ $entry->user->name ?? 'N/A' }}
                                @if(!empty($entry->is_self))
                                    <span class="ml-1 inline-block text-xs bg-blue-200 text-blue-900 rounded px-2">(You)</span>
                                @endif
                            </td>
                            <td class="py-1 px-2 text-right text-blue-600 font-bold">{{ $entry->total_points }}</td>
                            <td class="py-1 px-2 text-right">{{ $entry->modules_completed }}</td>
                            <td class="py-1 px-2 text-right">{{ $entry->quizzes_passed }}</td>
                            <td class="py-1 px-2 text-right">{{ $entry->badges_earned }}</td>
                            <td class="py-1 px-2 text-right">{{ $entry->current_streak }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-8 text-gray-400 text-center">No leaderboard data available.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
