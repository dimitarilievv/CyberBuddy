<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-50 text-cyan-700 text-xs font-semibold uppercase tracking-wide border border-cyan-100">
                    Interactive Scenario
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3">
                    {{ $scenario->title }}
                </h1>
            </div>

            <a href="{{ route('modules.index') }}"
               class="shrink-0 bg-white hover:bg-slate-50 text-slate-700 font-semibold px-5 py-2 rounded-full border border-slate-200 shadow-sm transition">
                Exit to Modules
            </a>
        </div>

        <!-- Mission Status (light blue bar) -->
        <div class="bg-sky-100/70 border border-sky-200 rounded-2xl px-5 py-4">
            <div class="flex items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-sky-500 text-white flex items-center justify-center">
                        <!-- simple target-ish icon -->
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                            <path d="M12 21a9 9 0 1 0-9-9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M12 15a3 3 0 1 0-3-3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            <path d="M21 12h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="leading-tight">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-sky-700">
                            Current Mission
                        </p>
                        <p class="text-slate-900 font-bold text-sm">
                            {{ $scenario->module->name ?? 'Module' }} - Security Training
                        </p>
                    </div>
                </div>

                <div class="text-right">
                    <p class="text-xs font-semibold text-slate-700">
                        Scenario {{ $scenarioProgress }} of {{ $totalScenarios }}
                    </p>
                    <div class="mt-2 w-44 h-2 bg-white/70 rounded-full overflow-hidden border border-sky-200">
                        <div class="h-full bg-sky-500"
                             style="width: {{ ($scenarioProgress / max(1, $totalScenarios)) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hero Card (image left, message right) -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <!-- Image -->
                <div class="relative min-h-[280px] lg:min-h-[380px] bg-slate-100">
                    @if($scenario->image_url)
                        <img src="{{ $scenario->image_url }}"
                             alt="{{ $scenario->title }}"
                             class="absolute inset-0 w-full h-full object-cover">
                        <!-- soft fade to the right like screenshot -->
                        <div class="absolute inset-0 bg-gradient-to-r from-black/20 via-black/10 to-white/90"></div>
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-sky-400 to-cyan-500 flex items-center justify-center text-white">
                            <span class="text-6xl">{{ $scenario->icon ?? '📱' }}</span>
                        </div>
                    @endif
                </div>

                <!-- Message -->
                <div class="p-7 sm:p-10">
                    <div class="flex items-center gap-3 text-slate-600">
                        <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center border border-slate-200">
                            <svg class="w-5 h-5 text-sky-600" viewBox="0 0 24 24" fill="none">
                                <path d="M4 6h16v10H7l-3 3V6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <p class="text-xs font-extrabold uppercase tracking-widest">
                            Incoming Message
                        </p>
                    </div>

                    <div class="mt-5 border-l-4 border-sky-500 pl-5">
                        <p class="text-slate-800 text-lg leading-relaxed font-medium">
                            “{{ $scenario->situation }}”
                        </p>
                    </div>

                    <div class="mt-8 flex items-center gap-3 text-slate-500 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center">1</span>
                            <span class="w-5 h-5 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center">2</span>
                            <span class="w-5 h-5 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center">3</span>
                        </div>
                        <span class="font-semibold uppercase tracking-wide">
                            {{ $choices->count() }} choices available
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Choices Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-slate-900 font-bold">
                <span class="w-6 h-6 rounded-full border border-sky-200 bg-sky-50 text-sky-700 flex items-center justify-center">
                    ?
                </span>
                <span>What will you do?</span>
            </div>
            <span class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">
                Decision Required
            </span>
        </div>

        <!-- Choices Grid (3 cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($choices as $choice)
                @php
                    $isSelected = $selectedChoice && $selectedChoice['id'] === $choice->id;
                @endphp

                <button
                    wire:click="selectChoice({{ $choice->id }})"
                    class="text-left bg-white border rounded-2xl p-6 shadow-sm transition
                           {{ $isSelected ? 'border-sky-500 ring-2 ring-sky-100' : 'border-slate-200 hover:border-slate-300 hover:shadow' }}"
                >
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-sky-50 border border-sky-100 flex items-center justify-center text-sky-700 text-xl">
                            {{ $choice->icon ?? '⚡' }}
                        </div>

                        <div class="min-w-0">
                            <h3 class="font-extrabold text-slate-900">
                                {{ $choice->choice_text }}
                            </h3>
                            <p class="mt-2 text-sm text-slate-600 leading-relaxed">
                                {{ $choice->consequence }}
                            </p>
                        </div>
                    </div>
                </button>
            @endforeach
        </div>

        <!-- Submit -->
        <div class="pt-2">
            <button
                wire:click="submit"
                {{ !$selectedChoice ? 'disabled' : '' }}
                class="w-full sm:w-auto px-6 py-3 rounded-xl font-bold text-white transition
                       bg-sky-500 hover:bg-sky-600 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                {{ $selectedChoice ? 'Submit Choice' : 'Select an option' }}
            </button>
        </div>

        <!-- Explanation Box (optional) -->
        @if($showExplanation && $currentExplanation)
            <div class="bg-sky-50 border border-sky-200 rounded-2xl p-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white border border-sky-200 flex items-center justify-center">
                        💡
                    </div>
                    <div>
                        <h3 class="font-extrabold text-slate-900">Why is this important?</h3>
                        <p class="mt-1 text-slate-700 leading-relaxed">{{ $currentExplanation }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Inline Result (shown after submit) -->
        @if(isset($showResult) && $showResult && isset($resultAttempt) && $resultAttempt)
            <div class="mt-6 bg-white border border-slate-200 rounded-2xl p-6">
                <h3 class="text-lg font-bold">Result</h3>
                <p class="mt-2">Score: <strong>{{ $resultAttempt->safety_score }}%</strong></p>
                <p class="mt-1">Feedback: <em>{{ $resultAttempt->ai_feedback }}</em></p>

                <div class="mt-4 flex gap-3">
                    {{-- Back to lessons (module page) --}}
                    <button wire:click="backToLessons"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded">
                        Back to Modules
                    </button>

                    {{-- Next lesson: active when available, otherwise show disabled button --}}
                    @if($nextLesson)
                        <a href="{{ route('lessons.show', [$nextLesson->module_id, $nextLesson->id]) }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Next Lesson</a>
                    @else
                        <button disabled class="bg-green-500 text-white px-4 py-2 rounded opacity-50 cursor-not-allowed">Next Lesson</button>
                    @endif
                </div>
            </div>
        @endif

        <!-- Safety Tips (bottom bar) -->
        <div class="bg-cyan-50 border border-cyan-200 rounded-2xl p-6">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-white border border-cyan-200 flex items-center justify-center">
                    ⚠️
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900">Safety Reminder</h3>
                    <p class="mt-1 text-sm text-slate-700 leading-relaxed">
                        If you ever feel pressured to give away your password or personal details, stop and think. High-pressure "limited time" offers are a common trick used by scammers!
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
