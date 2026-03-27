<div class="bg-white border-b border-gray-200 sticky top-16 z-40">
    <div class="py-6 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-600 font-semibold uppercase tracking-wide">
                Progress: Question {{ $currentQuestion }} of {{ $totalQuestions }}
            </p>
            <div class="w-64 bg-gray-200 rounded-full h-2 mt-2">
                <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" style="width: {{ $totalQuestions > 0 ? ($currentQuestion / $totalQuestions) * 100 : 0 }}%"></div>
            </div>
        </div>
        <div class="flex items-center gap-2 text-2xl font-bold text-gray-900">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <livewire:quiz.timer :quiz-id="$quiz->id" />
        </div>
    </div>
</div>
