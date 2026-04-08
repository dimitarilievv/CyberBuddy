<div>
    <!-- Quiz Card -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
        <!-- Quiz Info Banner -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2 text-white">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="font-semibold">{{ $quiz->title }}</span>
            </div>
            <span class="text-white text-sm font-semibold bg-white/20 px-3 py-1 rounded-full">Read carefully!</span>
        </div>

        <!-- Question / Review Area -->
        <div class="p-8">

            {{-- DEBUG: remove later --}}
{{--            <pre class="text-xs bg-gray-100 p-2 mb-4">--}}
{{--selectedAnswers: @json($selectedAnswers ?? [])--}}
{{--            </pre>--}}

            @if($showReview)
                {{-- REVIEW SCREEN --}}
                <div class="text-center py-12">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">
                        Review your answers
                    </h2>
                    <p class="text-gray-600 mb-6">
                        You have reached the end of the quiz. You can go back to change answers, or finish the quiz now.
                    </p>

                    <div class="flex items-center justify-center gap-4">
                        <button
                            wire:click="$set('showReview', false)"
                            class="px-6 py-3 rounded-full border border-gray-300 text-gray-700 font-semibold hover:bg-gray-100 transition"
                        >
                            Go Back to Questions
                        </button>

                        <button
                            wire:click="submit"
                            wire:loading.attr="disabled"
                            class="px-6 py-3 rounded-full bg-blue-500 hover:bg-blue-600 text-white font-semibold transition"
                        >
                            Finish Quiz
                        </button>
                    </div>
                </div>

            @elseif($currentQuestion)
                <!-- Progress -->
                <div class="mb-6">
                    <p class="text-sm text-gray-600 font-semibold uppercase tracking-wide mb-2">
                        PROGRESS: Question {{ $questionNumber }} of {{ $totalQuestions }}
                    </p>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full transition-all duration-300"
                             style="width: {{ ($questionNumber / $totalQuestions) * 100 }}%">
                        </div>
                    </div>
                </div>

                <!-- Question Type Badge -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 leading-relaxed flex-1">
                        {{ $currentQuestion->question_text }}
                    </h2>
                    @if($currentQuestion->type === 'multiple_choice')
                        <span class="ml-4 bg-purple-100 text-purple-800 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">
                            Multiple Choice
                        </span>
                    @elseif($currentQuestion->type === 'true_false')
                        <span class="ml-4 bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">
                            True/False
                        </span>
                    @else
                        <span class="ml-4 bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full whitespace-nowrap">
                            Single Choice
                        </span>
                    @endif
                </div>

                <!-- Answer Options -->
                @if(count($options) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        @foreach($options as $optionKey => $optionText)
                            @php
                                // Display key: numeric option keys -> A,B,C,...; string keys are used as-is
                                $displayKey = is_numeric($optionKey) ? chr(65 + $loop->index) : (string) $optionKey;

                                $isSelected = false;
                                if ($currentQuestion->type === 'multiple_choice') {
                                    $isSelected = is_array($currentAnswer) && in_array($displayKey, $currentAnswer, true);
                                } else {
                                    $isSelected = $currentAnswer === $displayKey;
                                }
                            @endphp

                            <label
                                wire:key="q-{{ $currentQuestion->id }}-opt-{{ $displayKey }}"
                                class="relative flex items-center p-6 border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 cursor-pointer transition group pl-6 {{ $isSelected ? 'border-blue-500 bg-blue-50' : '' }}"
                            >
                                @if($currentQuestion->type === 'multiple_choice')
                                    <input
                                        wire:key="input-q-{{ $currentQuestion->id }}-opt-{{ $displayKey }}"
                                        type="checkbox"
                                        name="answer_{{ $currentQuestion->id }}[]"
                                        value="{{ $displayKey }}"
                                        wire:model="selectedAnswers.{{ $currentQuestion->id }}.{{ $displayKey }}"
                                        class="left-4 top-4 w-5 h-5"
                                        style="accent-color: #0ea5e9;"
                                    >
                                @else
                                    <input
                                        wire:key="input-q-{{ $currentQuestion->id }}-opt-{{ $displayKey }}"
                                        type="radio"
                                        name="answer_{{ $currentQuestion->id }}"
                                        value="{{ $displayKey }}"
                                        wire:model="selectedAnswers.{{ $currentQuestion->id }}"
                                        class="left-4 top-4 w-5 h-5"
                                        style="accent-color: #0ea5e9;"
                                    >
                                @endif

                                <div class="ml-4 flex-1">
                                    <span class="block text-sm font-semibold text-gray-900 mb-1">{{ $displayKey }}</span>
                                    <span class="text-gray-700">{{ $optionText }}</span>
                                </div>

                                <span
                                    class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full border-2 border-gray-300 group-hover:border-blue-500 text-sm font-semibold text-gray-400 group-hover:text-blue-600 transition {{ $isSelected ? 'border-blue-500 text-blue-600 bg-blue-50' : '' }}"
                                >
                                    {{ $displayKey }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No options available for this question.</p>
                @endif

                <!-- Navigation -->
                <div class="flex items-center justify-between mt-12 pt-6 border-t border-gray-200">
                    <button
                        wire:click="previousQuestion"
                        {{ $questionNumber === 1 ? 'disabled' : '' }}
                        class="flex items-center gap-2 text-gray-600 hover:text-gray-900 font-semibold transition disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Previous
                    </button>

                    <span class="text-sm text-gray-600">
                        @if($this->canProceed())
                            Question {{ $questionNumber }} of {{ $totalQuestions }}
                        @else
                            Pick an answer to continue
                        @endif
                    </span>

                    <button
                        wire:click="goNextOrReview"
                        wire:loading.attr="disabled"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-8 py-3 rounded-full transition flex items-center gap-2"
                    >
                        @if($questionNumber === $totalQuestions)
                            Review &amp; Finish
                        @else
                            Next Question
                        @endif
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

            @else
                <!-- No Questions -->
                <div class="text-center py-12">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Questions Available</h3>
                    <p class="text-gray-600 mb-6">This quiz doesn't have any questions yet.</p>
                    <a href="{{ route('modules.index') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-3 rounded-lg transition">
                        Back to Modules
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Buddy Hint -->
    @if($currentQuestion && $currentQuestion->explanation)
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-yellow-400 p-6 rounded-lg flex gap-4">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 8a1 1 0 000 2h6a1 1 0 000-2H8z" clip-rule="evenodd" />
                </svg>
            </div>
            <div>
                <button wire:click="toggleHint" class="font-semibold text-gray-900 hover:text-yellow-700 transition text-left">
                    💡 Need a hint from Buddy?
                </button>
                @if($showHint)
                    <p class="text-sm text-gray-700 mt-2">{{ $currentQuestion->explanation }}</p>
                @endif
            </div>
        </div>
    @endif
</div>
