<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5M8 11v5M3 20h18M3 4h18"/>
                    </svg>
                    <h1 class="text-2xl font-bold text-gray-900">Leaderboard</h1>
                </div>
                <p class="text-sm text-gray-400 ml-8">Climb the ranks by completing lessons and quizzes!</p>
            </div>

            {{-- Period tabs --}}
            <div class="flex items-center bg-white border border-gray-200 rounded-xl p-1 gap-1">
                @foreach(['all_time' => 'All Time', 'monthly' => 'Monthly', 'weekly' => 'Weekly'] as $key => $label)
                    <button
                        wire:click="setPeriod('{{ $key }}')"
                        class="flex items-center gap-1.5 px-4 py-1.5 rounded-lg text-sm font-medium transition-all duration-150
                               {{ $period === $key
                                   ? 'bg-blue-600 text-white shadow-sm'
                                   : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                        @if($key === 'all_time')
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/>
                            </svg>
                        @elseif($key === 'monthly')
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5"/>
                            </svg>
                        @else
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        @if($this->sorted->count() >= 3)
            @php
                $first  = $this->sorted[0];
                $second = $this->sorted[1];
                $third  = $this->sorted[2];
            @endphp

            <div class="grid grid-cols-3 gap-3 mb-8">

                {{-- #2 --}}
                <div
                    class="relative bg-white border border-gray-100 rounded-xl p-3 text-center shadow-sm overflow-hidden">
                    <span class="absolute -top-2 -right-2 text-5xl font-black text-gray-100">#2</span>

                    <div class="text-base mb-1 relative z-10">🥈</div>

                    <div
                        class="w-12 h-12 mx-auto rounded-full bg-gray-100 flex items-center justify-center text-sm font-bold text-gray-600 mb-2 relative z-10 overflow-hidden">
                        @php $secondAvatar = optional($second->user->profile)->avatar ?? null; @endphp
                        @if($secondAvatar)
                            <img src="{{ str_starts_with($secondAvatar, 'http') ? $secondAvatar : Storage::url($secondAvatar) }}" alt="{{ $second->user->name ?? 'User' }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($second->user->name ?? 'U', 0, 2)) }}
                        @endif
                    </div>

                    <p class="text-xs font-semibold text-gray-800 truncate relative z-10">
                        {{ $second->user->name ?? 'N/A' }}
                    </p>

                    <p class="text-blue-600 text-xs mt-0.5 relative z-10">
                        {{ number_format($second->total_points) }} pts
                    </p>
                </div>

                {{-- #1 --}}
                <div
                    class="relative bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-3 text-center text-white shadow overflow-hidden">
                    <span class="absolute -top-2 -right-2 text-5xl font-black text-white/20">#1</span>

                    <div class="text-lg mb-1 relative z-10">👑</div>

                    <div
                        class="w-14 h-14 mx-auto rounded-full bg-white/20 flex items-center justify-center text-base font-bold mb-2 relative z-10 overflow-hidden">
                        @php $firstAvatar = optional($first->user->profile)->avatar ?? null; @endphp
                        @if($firstAvatar)
                            <img src="{{ str_starts_with($firstAvatar, 'http') ? $firstAvatar : Storage::url($firstAvatar) }}" alt="{{ $first->user->name ?? 'User' }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($first->user->name ?? 'U', 0, 2)) }}
                        @endif
                    </div>

                    <p class="text-xs font-bold truncate relative z-10">
                        {{ $first->user->name ?? 'N/A' }}
                    </p>

                    <p class="text-blue-100 text-xs mt-0.5 relative z-10">
                        {{ number_format($first->total_points) }} pts
                    </p>
                </div>

                {{-- #3 --}}
                <div
                    class="relative bg-white border border-gray-100 rounded-xl p-3 text-center shadow-sm overflow-hidden">
                    <span class="absolute -top-2 -right-2 text-5xl font-black text-orange-100">#3</span>

                    <div class="text-base mb-1 relative z-10">🥉</div>

                    <div
                        class="w-12 h-12 mx-auto rounded-full bg-orange-100 flex items-center justify-center text-sm font-bold text-orange-500 mb-2 relative z-10 overflow-hidden">
                        @php $thirdAvatar = optional($third->user->profile)->avatar ?? null; @endphp
                        @if($thirdAvatar)
                            <img src="{{ str_starts_with($thirdAvatar, 'http') ? $thirdAvatar : Storage::url($thirdAvatar) }}" alt="{{ $third->user->name ?? 'User' }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($third->user->name ?? 'U', 0, 2)) }}
                        @endif
                    </div>

                    <p class="text-xs font-semibold text-gray-800 truncate relative z-10">
                        {{ $third->user->name ?? 'N/A' }}
                    </p>

                    <p class="text-blue-600 text-xs mt-0.5 relative z-10">
                        {{ number_format($third->total_points) }} pts
                    </p>
                </div>

            </div>
        @endif
        {{-- Current user rank banner --}}
        @php $myEntry = $top->firstWhere('user_id', auth()->id()); @endphp
        @if($myEntry)
            <div class="bg-blue-600 rounded-2xl px-6 py-4 flex flex-wrap items-center justify-between gap-4 mb-6">
                <div>
                    <p class="text-blue-200 text-xs font-semibold uppercase tracking-widest mb-0.5">Your Current
                        Rank</p>
                    <p class="text-white text-3xl font-black">
                        #{{ $myEntry->rank ?? $sorted->search(fn($e) => $e->user_id === auth()->id()) + 1 }}</p>
                </div>
                <div class="flex gap-8">
                    <div class="text-center">
                        <p class="text-blue-200 text-xs uppercase tracking-widest mb-0.5">Total Points</p>
                        <p class="text-white text-xl font-bold">{{ number_format($myEntry->total_points) }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-blue-200 text-xs uppercase tracking-widest mb-0.5">Current Streak</p>
                        <p class="text-white text-xl font-bold">{{ $myEntry->current_streak ?? 0 }} 🔥</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700">Top Contributors</span>
                </div>
                <span class="text-xs text-gray-400">{{ ucfirst(str_replace('_', ' ', $period)) }}</span>
            </div>

            <table class="w-full">
                <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-6 py-3 w-16">
                        Rank
                    </th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 py-3">
                        Member
                    </th>
                    <th class="text-right text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 py-3">
                        Points
                    </th>
                    <th class="text-right text-xs font-semibold text-gray-400 uppercase tracking-wider px-4 py-3 hidden sm:table-cell">
                        Streak
                    </th>
                    <th class="text-right text-xs font-semibold text-gray-400 uppercase tracking-wider px-6 py-3 hidden md:table-cell">
                        Badges
                    </th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                @forelse($sorted as $place => $entry)
                    @php $isMe = $entry->user_id === auth()->id(); @endphp
                    <tr class="{{ $isMe ? 'bg-blue-50' : 'hover:bg-gray-50' }} transition-colors duration-150">
                        <td class="px-6 py-4">
                            @if($place === 0)
                                <span
                                    class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">#1</span>
                            @elseif($place === 1)
                                <span
                                    class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 text-gray-600 text-xs font-bold">#2</span>
                            @elseif($place === 2)
                                <span
                                    class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-orange-100 text-orange-600 text-xs font-bold">#3</span>
                            @else
                                <span class="text-sm text-gray-400 font-medium pl-1">#{{ $place + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-full {{ $isMe ? 'bg-blue-200 text-blue-800' : 'bg-gray-100 text-gray-600' }} flex items-center justify-center text-sm font-semibold shrink-0 overflow-hidden">
                                    @php $rowAvatar = optional($entry->user->profile)->avatar ?? null; @endphp
                                    @if($rowAvatar)
                                        <img src="{{ str_starts_with($rowAvatar, 'http') ? $rowAvatar : Storage::url($rowAvatar) }}" alt="{{ $entry->user->name ?? 'User' }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($entry->user->name ?? 'U', 0, 2)) }}
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold {{ $isMe ? 'text-blue-700' : 'text-gray-800' }}">
                                        {{ $entry->user->name ?? 'N/A' }}
                                        @if($isMe)
                                            <span
                                                class="ml-1.5 text-[10px] bg-blue-100 text-blue-600 font-bold px-1.5 py-0.5 rounded">YOU</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $entry->modules_completed ?? 0 }} modules
                                        · {{ $entry->quizzes_passed ?? 0 }} quizzes</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <span
                                class="text-sm font-bold {{ $isMe ? 'text-blue-700' : 'text-gray-800' }}">{{ number_format($entry->total_points) }}</span>
                        </td>
                        <td class="px-4 py-4 text-right hidden sm:table-cell">
                            @if($entry->current_streak)
                                <span class="text-sm text-orange-500 font-medium">{{ $entry->current_streak }} 🔥</span>
                            @else
                                <span class="text-sm text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right hidden md:table-cell">
                            @if($entry->badges_earned)
                                <span
                                    class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-full">🏅 {{ $entry->badges_earned }}</span>
                            @else
                                <span class="text-sm text-gray-300">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <p class="text-sm font-semibold text-gray-500">No entries yet</p>
                            <p class="text-xs text-gray-400 mt-1">Complete modules to appear on the leaderboard!</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
