@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8 space-y-10">

        @if(session('success'))
            <div class="bg-green-50 text-green-800 px-4 py-2 rounded mb-4 font-semibold">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 text-red-800 px-4 py-2 rounded mb-4 font-semibold">{{ session('error') }}</div>
        @endif

        <!-- HEADER & CARD ROW -->
        <div class="mb-6" x-data="{ showModal: false, tab: null }">
            <h1 class="text-2xl font-bold mb-1">Parent Overview</h1>
            <div class="text-gray-500 leading-tight mb-6">
                Monitor and support {{ $parent->name }}'s digital safety education.
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
                <div class="bg-white rounded-2xl p-5 shadow flex flex-col items-center justify-center">
{{--                    <img src="{{ $parent->avatar ?? '/img/parent-avatar.svg' }}" class="w-14 h-14 rounded-full mb-2 border-2 border-blue-100 shadow" alt="Parent Avatar">--}}
                    <div class="font-semibold text-lg">{{ $parent->name }}'s Family</div>
{{--                    <div class="text-sm text-gray-400">{{ $children->pluck('profile.grade')->unique()->join(', ') }}</div>--}}
                    <div class="flex flex-wrap gap-2 mt-2">
                        @foreach($children as $child)
                            <span class="bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-xs font-bold">{{ $child->name }}</span>
                        @endforeach
                        <button
                            @click="showModal = true; tab = null"
                            type="button"
                            class="border border-dashed text-gray-400 rounded-full px-3 py-1 text-xs hover:bg-blue-50 hover:text-blue-700 transition focus:outline-none">
                            &plus; Add Child
                        </button>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow flex flex-col items-center justify-center">
                    <span class="block text-xs text-gray-500 mb-1 font-semibold">Total XP</span>
                    <span class="text-3xl font-bold text-orange-600 mb-2">{{ number_format($totalXP) }}</span>
                    <div class="flex justify-center items-center text-xs text-gray-400">Badges: +{{ $totalBadges }}, +{{ intval($totalBadges * 300) }} XP</div>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow flex flex-col items-center justify-center">
                    <span class="block text-xs text-gray-500 mb-1 font-semibold">Badges Earned</span>
                    <span class="text-3xl font-bold text-blue-700 mb-2">{{ $totalBadges }}</span>
                    <div class="flex justify-center items-center text-xs text-gray-400">All children</div>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow flex flex-col items-center justify-center">
                    <span class="block text-xs text-gray-500 mb-1 font-semibold">Modules Ready</span>
                    <span class="text-3xl font-bold text-slate-700 mb-2">{{ str_pad($modulesReady,2,'0',STR_PAD_LEFT) }}</span>
                    <div class="flex justify-center items-center text-xs text-gray-400">For assignment</div>
                </div>
            </div>


            <!-- MODAL: Add/Attach Child -->
            <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-30 z-40 transition-opacity" x-cloak @click="showModal = false"></div>
            <div x-show="showModal" class="fixed inset-0 flex items-center justify-center z-50" x-cloak>
                <div class="bg-white rounded-xl shadow-xl w-full max-w-md px-6 py-8 relative">
                    <button class="absolute top-3 right-4 text-gray-400 hover:text-gray-700" @click="showModal = false">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <template x-if="!tab">
                        <div class="flex gap-2">
                            <button
                                @click="tab = 'new'"
                                class="flex-1 bg-blue-600 text-white font-semibold rounded-lg px-4 py-3 text-lg hover:bg-blue-700 transition">
                                Create New Child Profile
                            </button>
                            <button
                                @click="tab = 'existing'"
                                class="flex-1 bg-gray-100 text-gray-700 font-semibold rounded-lg px-4 py-3 text-lg hover:bg-blue-100 hover:text-blue-700 transition">
                                Attach Existing Profile
                            </button>
                        </div>
                    </template>

                    <div x-show="tab === 'new'">
                        <div class="mb-4 flex gap-2">
                            <button @click="tab = null" class="text-blue-500 text-xs font-semibold hover:underline">&larr; Back</button>
                            <span class="font-bold text-gray-700 text-base">Create New Child</span>
                        </div>
                        <form action="{{ route('parent.add-child') }}" method="POST" class="flex flex-col gap-2">
                            @csrf
                            <div>
                                <label class="block text-xs mb-1 font-semibold">Child Name</label>
                                <input name="name" type="text" required class="w-full rounded p-2 border border-gray-200" />
                            </div>
                            <div>
                                <label class="block text-xs mb-1 font-semibold">Child Email</label>
                                <input name="email" type="email" required class="w-full rounded p-2 border border-gray-200" />
                            </div>
                            <div>
                                <label class="block text-xs mb-1 font-semibold">Password</label>
                                <input name="password" type="password" required class="w-full rounded p-2 border border-gray-200" />
                            </div>
                            <div>
                                <label class="block text-xs mb-1 font-semibold">Confirm Password</label>
                                <input name="password_confirmation" type="password" required class="w-full rounded p-2 border border-gray-200" />
                            </div>
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 rounded hover:bg-blue-700 transition mt-2">+ Create Child</button>
                        </form>
                    </div>

                    <div x-show="tab === 'existing'">
                        <div class="mb-4 flex gap-2">
                            <button @click="tab = null" class="text-blue-500 text-xs font-semibold hover:underline">&larr; Back</button>
                            <span class="font-bold text-gray-700 text-base">Attach Existing Child</span>
                        </div>
                        <form action="{{ route('parent.attach-child') }}" method="POST" class="flex flex-col gap-2">
                            @csrf
                            <div>
                                <label class="block text-xs mb-1 font-semibold">Child's Email Address</label>
                                <input name="email" type="email" required placeholder="Search for child by email..." class="w-full rounded p-2 border border-gray-200" />
                            </div>
                            <button type="submit" class="bg-green-600 text-white font-bold py-2 rounded hover:bg-green-700 transition mt-2">Attach Existing</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /MODAL -->
        </div>

        <!-- The rest of your dashboard below stays the same as before... -->

        <!-- Main Insights: Graph & Safety -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Learning Consistency -->
            <div class="bg-white rounded-2xl p-6 shadow col-span-2 flex flex-col">
                <div class="flex justify-between items-center mb-3">
                    <span class="font-bold text-gray-800">Learning Consistency</span>
                    <div>
                        <a href="{{ route(Route::currentRouteName(), ['chart_days'=>7]) }}"
                           class="px-3 py-1 rounded {{ $chartDays == 7 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700' }}">
                            Last 7 Days
                        </a>
                        <a href="{{ route(Route::currentRouteName(), ['chart_days'=>30]) }}"
                           class="px-3 py-1 rounded ml-1 {{ $chartDays == 30 ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700' }}">
                            Last 30 Days
                        </a>
                    </div>
                </div>
                <div class="h-64 w-full flex flex-col items-center justify-end">
                    {{-- Chart.js line chart --}}
                    <canvas id="xpChart" class="w-full" height="210"></canvas>
                </div>
                <div class="flex justify-between text-xs text-gray-400 mt-4 px-2">
                    @foreach($xpPerDay as $date => $xp)
                        <div class="flex flex-col items-center min-w-[36px]">
                            <span class="font-semibold text-gray-700">{{ \Carbon\Carbon::parse($date)->format($chartDays == 7 ? 'D' : 'M d') }}</span>
                            <span class="text-xs text-blue-600 font-bold">{{ $xp }} XP</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const ctx = document.getElementById('xpChart').getContext('2d');
                    // Destroy Chart instance if exists (SPA/Livewire issues)
                    if(window.xpChartInstance) { window.xpChartInstance.destroy(); }
                    window.xpChartInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode(array_map(fn($d) => \Carbon\Carbon::parse($d)->format($chartDays == 7 ? 'D' : 'M d'), array_keys($xpPerDay))) !!},
                            datasets: [{
                                label: 'XP Earned',
                                data: {!! json_encode(array_values($xpPerDay)) !!},
                                fill: false,
                                borderColor: '#3b82f6',
                                backgroundColor: '#3b82f6',
                                tension: 0.4,
                                pointRadius: 5,
                                pointHoverRadius: 8,
                                pointBackgroundColor: '#2563eb',
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false }},
                            scales: {
                                x: { grid: { display: false }},
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 100, color: "#9ca3af" },
                                    title: { display: true, text: 'XP', color: '#6b7280', font: { size: 12 } }
                                }
                            }
                        }
                    });
                });
            </script>
            <!-- Safety Insights -->
            <div class="bg-white rounded-2xl p-6 shadow flex flex-col">
                <span class="font-bold text-gray-800 mb-2">Safety Insights</span>
                @foreach($safetyInsights as $insight)
                    <div class="flex items-start gap-3 mb-4 bg-blue-50 rounded-lg p-3">
                        <span class="text-2xl">{{ $insight['icon'] }}</span>
                        <div>
                            <div class="font-semibold text-gray-700">{{ $insight['title'] }}</div>
                            <div class="text-xs text-gray-500">{{ $insight['desc'] }}</div>
                        </div>
                    </div>
                @endforeach
                <a href="#" class="block text-xs text-gray-500 mt-2 hover:text-blue-600 font-semibold">View All Insights</a>
            </div>
        </div>

        <!-- Skill Proficiency and Recent Activity Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Skill Proficiency -->
            <div class="col-span-2 bg-white rounded-2xl shadow p-6 flex flex-col">
                <span class="font-bold text-gray-800 mb-3">Skill Proficiency</span>
                <div>
                    @foreach($proficiencyPercents as $cat => $percent)
                        <div class="flex items-center mb-3">
                            <div class="w-28 font-bold text-sm text-gray-700">{{ $cat }}</div>
                            <div class="flex-1 mx-2 h-5 bg-gray-200 rounded-full relative">
                                <div class="h-5 bg-orange-400 rounded-full" style="width:{{ $percent }}%"></div>
                            </div>
                            <div class="w-10 text-right font-bold text-orange-600">{{ $percent }}%</div>
                        </div>
                    @endforeach
                </div>
                <div class="text-xs text-gray-400 mt-2">
                    Based on {{ $children->count() }} child{{ $children->count() == 1 ? '' : 'ren' }}'s lesson completion.
                </div>
            </div>
            <!-- Recent Activity (last 5) -->
            <div class="bg-white rounded-2xl shadow p-6">
                <span class="font-bold text-gray-800 mb-3 block">Recent Activity</span>
                <ul class="divide-y divide-gray-100">
                    @forelse($recentActivities as $activity)
                        <li class="py-3 flex gap-2 items-center">
                            @if($activity['type'] === 'lesson_complete')
                                <span class="text-blue-600 text-lg">✔️</span>
                                <span>
                            <span class="font-semibold text-gray-700">{{ $activity['lesson']->title ?? '[Lesson]' }}</span>
                            <span class="text-xs text-gray-500 ml-1">lesson complete &bull; {{ $activity['created_at'] ? $activity['created_at']->diffForHumans() : '' }}</span>
                        </span>
                            @elseif($activity['type'] === 'badge_earned')
                                <span class="text-orange-500 text-lg">🏅</span>
                                <span>
                            <span class="font-semibold text-gray-700">{{ $activity['badge']->name ?? '[Badge]' }}</span>
                            <span class="text-xs text-gray-500 ml-1">badge earned &bull; {{ $activity['created_at'] ? $activity['created_at']->diffForHumans() : '' }}</span>
                        </span>
                            @else
                                <span class="text-gray-400">[Activity]</span>
                            @endif
                        </li>
                    @empty
                        <li class="text-gray-400 py-4 text-center">No recent activity found.</li>
                    @endforelse
                </ul>
                <a href="#" class="block mt-4 text-xs text-blue-500 font-bold hover:underline text-center">See Full History</a>
            </div>
        </div>

        <!-- Safety Tip of the Day -->
        <div class="bg-blue-50 rounded-2xl p-6 flex flex-col md:flex-row items-center gap-4 shadow">
            <div class="flex items-center gap-3">
                <span class="bg-blue-100 rounded-full p-2"><svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-width="2" d="M12 22c.414 0 .75-.336.75-.75v-8.25c0-.414-.336-.75-.75-.75s-.75.336-.75.75v8.25c0 .414.336.75.75.75z"/><path stroke-linecap="round" stroke-width="2" d="M18.364 5.636a9 9 0 11-12.728 0"/><path stroke-linecap="round" d="M12 2v2"/></svg></span>
                <div>
                    <div class="font-semibold text-blue-800">CyberBuddy Safety Tip of the Day</div>
                    <div class="text-gray-600 text-sm mt-1">{{ $safetyTip }}</div>
                </div>
            </div>
            <div class="flex-1 flex justify-end w-full">
                <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg px-4 py-2 font-bold shadow">Read Safety Guide</a>
            </div>
        </div>
    </div>
@endsection
