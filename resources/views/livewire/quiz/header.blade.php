<div class="flex items-center gap-2 text-2xl font-bold text-gray-900">
    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>

    {{-- Timer --}}
    @livewire('quiz.timer', ['quizId' => $quiz->id])
</div>
