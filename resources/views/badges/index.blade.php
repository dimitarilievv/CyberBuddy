@extends('layouts.app')

@section('content')

    <div class="min-h-screen bg-gray-50 px-6 py-8 font-sans">
        <div class="max-w-5xl mx-auto">

            {{-- ── Awarded alert ── --}}
            @if(session('awarded') && count(session('awarded')))
                <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 rounded-2xl px-5 py-4 mb-6">
                    <svg class="text-emerald-500 mt-0.5 shrink-0" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <div>
                        <p class="font-bold text-emerald-900 text-sm">🎉 New badges unlocked!</p>
                        <ul class="mt-1 pl-4 list-disc">
                            @foreach(session('awarded') as $awardedBadge)
                                <li class="text-xs text-emerald-700">{{ $awardedBadge->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- ── PHP vars ── --}}
            @php
                $earnedCount = $userBadgeSlugs ? count($userBadgeSlugs) : 0;
                $totalCount  = $badges->count();
                $progressPct = $totalCount > 0 ? round(($earnedCount / $totalCount) * 100) : 0;
                $earnedSlugs = $userBadgeSlugs ?? [];
                $nextBadge   = $nextBadge ?? null;
                // $streakDays comes from the controller (prefers leaderboard current_streak)
                // Use leaderboardPoints (controller-provided) if available, otherwise fallback to user total_points
                $totalPoints = isset($leaderboardPoints) ? $leaderboardPoints : (auth()->user()->total_points ?? 0);
            @endphp

            {{-- ── Top row ── --}}
            <div class="flex gap-4 mb-6 items-stretch flex-wrap">

                {{-- Header card --}}
                <div class="flex-1 bg-white rounded-2xl shadow-sm p-6 flex items-center gap-4 min-w-0">
                    <div class="rounded-xl bg-blue-600 flex items-center justify-center shrink-0" style="width:52px;height:52px;">
                        <svg class="text-white" xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl font-extrabold text-gray-900 mb-0.5">My Achievements</h1>
                        <p class="text-sm text-gray-500 mb-3">You're doing great, {{ auth()->user()->name ?? 'Explorer' }}!</p>
                        <div class="flex justify-between text-xs font-semibold text-gray-700 mb-1.5">
                            <span>Collection Progress</span>
                            <span>{{ $earnedCount }} / {{ $totalCount }} Badges</span>
                        </div>
                        <div class="bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-blue-600 to-blue-400 transition-all duration-500"
                                 style="width:{{ $progressPct }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- Stat cards --}}
                <div class="flex gap-3">
                    <div class="bg-white rounded-2xl shadow-sm px-5 py-4 text-center flex flex-col items-center justify-center min-w-[110px]">
                        <span class="text-2xl mb-1">🔥</span>
                        <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ $streakDays }}</p>
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mt-1">Current Streak</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm px-5 py-4 text-center flex flex-col items-center justify-center min-w-[110px]">
                        <span class="text-2xl mb-1">⭐</span>
                        <p class="text-2xl font-extrabold text-gray-900 leading-none">{{ number_format($totalPoints) }}</p>
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mt-1">Total Points</p>
                    </div>
                </div>
            </div>

            {{-- ── Filter bar ── --}}
            <div class="flex items-center gap-3 mb-6 flex-wrap">

                {{-- Status filter --}}
                <div class="flex bg-white rounded-xl p-1 gap-1 shadow-sm" id="statusGroup">
                    <button class="filter-status px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 text-white cursor-pointer border-none" data-status="all">All</button>
                    <button class="filter-status px-3.5 py-1.5 rounded-lg text-xs font-semibold text-gray-500 bg-transparent cursor-pointer border-none hover:bg-gray-100 transition-colors" data-status="unlocked">Unlocked</button>
                    <button class="filter-status px-3.5 py-1.5 rounded-lg text-xs font-semibold text-gray-500 bg-transparent cursor-pointer border-none hover:bg-gray-100 transition-colors" data-status="locked">Locked</button>
                </div>

                {{-- Type filter --}}
                <div class="flex bg-white rounded-xl p-1 gap-1 shadow-sm" id="typeGroup">
                    <button class="filter-type px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 text-white cursor-pointer border-none" data-type="all">All</button>
                    @foreach(['completion' => 'Completion', 'score' => 'Score', 'streak' => 'Streak', 'special' => 'Special'] as $val => $label)
                        <button class="filter-type px-3.5 py-1.5 rounded-lg text-xs font-semibold text-gray-500 bg-transparent cursor-pointer border-none hover:bg-gray-100 transition-colors" data-type="{{ $val }}">{{ $label }}</button>
                    @endforeach
                </div>

                {{-- Search --}}
                <div class="ml-auto relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input id="badgeSearch"
                           type="text"
                           placeholder="Search badges..."
                           class="pl-9 pr-4 py-2 text-xs text-gray-700 bg-white border border-gray-200 rounded-xl outline-none w-48 focus:border-blue-500 transition-colors">
                </div>
            </div>

            {{-- ── Badge Grid ── --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-8" id="badgeGrid">
                @foreach($badges as $badge)
                    @php
                        $earned      = in_array($badge->slug, $earnedSlugs);
                        $color = $badge->color ?? '#6B7280';

                        $hint = null;
                        if (!$earned && $badge->criteria) {
                            $cr = $badge->criteria;
                            if (isset($cr['lessons_completed']))    $hint = "Complete {$cr['lessons_completed']} lesson(s) to unlock.";
                            elseif (isset($cr['modules_completed'])) $hint = "Complete {$cr['modules_completed']} module(s) to unlock.";
                            elseif (isset($cr['quiz_score']))        $hint = "Score {$cr['quiz_score']}% on a quiz to unlock.";
                            elseif (isset($cr['quizzes_passed']))    $hint = "Pass {$cr['quizzes_passed']} quizzes to unlock.";
                            elseif (isset($cr['streak_days']))       $hint = "Study {$cr['streak_days']} days in a row to unlock.";
                            elseif (isset($cr['perfect_scenarios'])) $hint = "Complete {$cr['perfect_scenarios']} perfect scenarios.";
                            elseif (isset($cr['ai_interactions']))   $hint = "Make {$cr['ai_interactions']} AI interactions.";
                            elseif (isset($cr['reports_made']))      $hint = "Report inappropriate content.";
                        }
                    @endphp

                    <div class="badge-card bg-white rounded-2xl shadow-sm p-4 flex flex-col hover:-translate-y-1 hover:shadow-md transition-all duration-200 {{ $earned ? '' : 'opacity-80' }}"
                         data-status="{{ $earned ? 'unlocked' : 'locked' }}"
                         data-type="{{ $badge->type }}"
                         data-name="{{ strtolower($badge->name) }} {{ strtolower($badge->description) }}">

                        {{-- Type label --}}
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">{{ ucfirst($badge->type) }}</p>

                        {{-- Icon: earned = badge icon, locked = lock only (no overlap) --}}
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                             style="background-color: {{ $color }}20;">
                            @if($earned)
                                @include('badges._icon', ['icon' => $badge->icon, 'color' => $color, 'size' => 30])
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                     fill="none" stroke="{{ $color }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Name & description --}}
                        <h3 class="text-sm font-bold text-gray-900 text-center mb-1">{{ $badge->name }}</h3>
                        <p class="text-xs text-gray-500 text-center leading-relaxed mb-auto">{{ $badge->description }}</p>

                        {{-- Footer --}}
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            @if($earned)
                                <div class="flex items-center gap-1.5 text-xs text-gray-400 font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    Unlocked
                                </div>
                            @elseif($hint)
                                <p class="text-xs font-semibold text-amber-500 leading-snug">{{ $hint }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ── Next Badge CTA ── --}}
            @if($nextBadge)
                <div class="bg-blue-50 rounded-2xl p-6 flex items-center gap-4 flex-wrap">
                    <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center shrink-0 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-extrabold text-gray-900 mb-1">Want your next badge?</h3>
                        <p class="text-sm text-gray-600 mb-3">
                            The <strong class="text-gray-900">{{ $nextBadge->name }}</strong> badge is waiting for you! {{ $nextBadge->description }}.
                        </p>
                        <div class="flex gap-2.5 flex-wrap">
                            <a href="{{ route('modules.index') }}"
                               class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition-colors no-underline">
                                Go to Lesson
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                            </a>
                            <a href="{{ route('modules.index') }}"
                               class="inline-flex items-center text-xs font-bold px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-colors no-underline">
                                View Modules
                            </a>
                            <form method="POST" action="{{ route('badges.check') }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="text-xs font-bold px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-colors bg-transparent cursor-pointer">
                                    Check for New Badges
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        </div>{{-- /max-width --}}
    </div>

    {{-- ── Filter + Search JS ── --}}
    <script>
        (function () {
            let activeStatus = 'all';
            let activeType   = 'all';
            const cards  = document.querySelectorAll('.badge-card');
            const search = document.getElementById('badgeSearch');

            function setActive(groupId, clicked) {
                document.querySelectorAll('#' + groupId + ' button').forEach(b => {
                    b.classList.remove('bg-blue-600', 'text-white');
                    b.classList.add('text-gray-500', 'bg-transparent');
                });
                clicked.classList.add('bg-blue-600', 'text-white');
                clicked.classList.remove('text-gray-500', 'bg-transparent');
            }

            function applyFilters() {
                const q = search.value.toLowerCase().trim();
                cards.forEach(card => {
                    const ok = (activeStatus === 'all' || card.dataset.status === activeStatus)
                        && (activeType   === 'all' || card.dataset.type   === activeType)
                        && (!q || card.dataset.name.includes(q));
                    card.style.display = ok ? '' : 'none';
                });
            }

            document.querySelectorAll('.filter-status').forEach(btn => {
                btn.addEventListener('click', () => {
                    setActive('statusGroup', btn);
                    activeStatus = btn.dataset.status;
                    applyFilters();
                });
            });

            document.querySelectorAll('.filter-type').forEach(btn => {
                btn.addEventListener('click', () => {
                    setActive('typeGroup', btn);
                    activeType = btn.dataset.type;
                    applyFilters();
                });
            });

            search.addEventListener('input', applyFilters);
        })();
    </script>

@endsection
