<div class="min-h-screen bg-gradient-to-b from-slate-50 to-slate-100 py-8 px-4">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <p class="text-cyan-600 text-sm font-semibold uppercase tracking-wide">Interactive Scenario</p>
                <h1 class="text-4xl font-bold text-slate-900 mt-1">{{ $scenario->title }}</h1>
            </div>
            <a href="{{ route('modules.index') }}" class="bg-white hover:bg-slate-50 text-slate-700 font-semibold px-6 py-2 rounded-lg border border-slate-200 transition">
                Exit to Modules
            </a>
        </div>

        <!-- Mission Status -->
        <div class="bg-gradient-to-r from-cyan-50 to-blue-50 border border-cyan-200 rounded-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-cyan-500 flex items-center justify-center text-white text-xl">
                        🎯
                    </div>
                    <div>
                        <p class="text-cyan-600 text-sm font-bold uppercase">CURRENT MISSION</p>
                        <p class="text-slate-900 font-bold text-lg">{{ $scenario->module->name ?? 'Module' }} - Security Training</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-cyan-600 text-sm font-bold">Scenario {{ $scenarioProgress }} of {{ $totalScenarios }}</p>
                    <div class="w-32 h-2 bg-slate-200 rounded-full mt-2 overflow-hidden">
                        <div class="h-full bg-cyan-500 transition-all" style="width: {{ ($scenarioProgress / $totalScenarios) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Scenario Image & Message (Left) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                    <!-- Image -->
                    @if($scenario->image_url)
                        <div class="h-80 bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center overflow-hidden">
                            <img src="{{ $scenario->image_url }}" alt="{{ $scenario->title }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="h-80 bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-white">
                            <span class="text-6xl">{{ $scenario->icon ?? '📱' }}</span>
                        </div>
                    @endif

                    <!-- Message Section -->
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-6 h-6 rounded-full bg-cyan-500 flex items-center justify-center text-white text-sm">
                                ✓
                            </div>
                            <p class="text-cyan-600 text-xs font-bold uppercase tracking-widest">INCOMING MESSAGE</p>
                        </div>

                        <p class="text-slate-800 text-lg leading-relaxed italic border-l-4 border-cyan-500 pl-6 py-4">
                            "{{ $scenario->scenario_text }}"
                        </p>

                        <!-- Message Indicator -->
                        <div class="mt-6 flex items-center gap-2 text-slate-500 text-sm">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            <span class="ml-2">{{ $choices->count() }} CHOICES AVAILABLE</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Choices (Right) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden sticky top-8">
                    <div class="bg-gradient-to-r from-cyan-500 to-blue-500 p-6">
                        <div class="flex items-center gap-2">
                            <div class="text-2xl">❓</div>
                            <h2 class="text-white font-bold text-lg">What will you do?</h2>
                        </div>
                        <p class="text-cyan-100 text-xs mt-2 uppercase tracking-wide font-semibold">DECISION REQUIRED</p>
                    </div>

                    <div class="p-6 space-y-4">
                        @if($choices->count() > 0)
                            @foreach($choices as $choice)
                                @php
                                    $isSelected = $selectedChoice && $selectedChoice['id'] === $choice->id;
                                @endphp

                                <button
                                    wire:click="selectChoice({{ $choice->id }})"
                                    class="w-full text-left p-4 rounded-lg border-2 transition-all {{ $isSelected ? 'border-cyan-500 bg-cyan-50' : 'border-slate-200 bg-slate-50 hover:border-cyan-300 hover:bg-white' }}"
                                >
                                    <div class="flex items-start gap-3">
                                        <div class="text-xl flex-shrink-0 mt-1">{{ $choice->icon ?? '⚡' }}</div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-bold text-slate-900 text-sm mb-1">{{ $choice->choice_text }}</h3>
                                            <p class="text-slate-600 text-xs leading-snug">{{ $choice->consequence }}</p>
                                        </div>
                                        @if($isSelected)
                                            <svg class="w-5 h-5 text-cyan-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </div>
                                </button>
                            @endforeach
                        @endif

                        <!-- Submit Button -->
                        <button
                            wire:click="submit"
                            {{ !$selectedChoice ? 'disabled' : '' }}
                            class="w-full mt-6 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-bold py-3 px-4 rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            @if($selectedChoice)
                                Submit Choice
                            @else
                                Select an option
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Explanation Box -->
        @if($showExplanation && $currentExplanation)
            <div class="mt-8 bg-gradient-to-r from-cyan-50 to-blue-50 border-l-4 border-cyan-500 p-6 rounded-lg">
                <div class="flex items-start gap-4">
                    <div class="text-3xl flex-shrink-0">💡</div>
                    <div>
                        <h3 class="font-bold text-cyan-900 mb-2">Why is this important?</h3>
                        <p class="text-slate-700 leading-relaxed">{{ $currentExplanation }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Safety Tips -->
        <div class="mt-8 bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 p-6 rounded-lg">
            <div class="flex items-start gap-4">
                <div class="text-2xl flex-shrink-0">⚠️</div>
                <div>
                    <h3 class="font-bold text-amber-900 mb-1">Safety Reminder</h3>
                    <p class="text-slate-700 text-sm">
                        If you ever feel pressured to give away your password or personal details, stop and think. High-pressure "limited time" offers are a common trick used by scammers!
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
