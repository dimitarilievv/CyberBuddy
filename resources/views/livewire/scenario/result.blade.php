<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Result Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r {{ $isPassed ? 'from-green-500 to-green-600' : 'from-orange-500 to-orange-600' }} px-6 py-8 text-center">
                <div class="mb-4">
                    @if($isPassed)
                        <svg class="w-16 h-16 mx-auto text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    @else
                        <svg class="w-16 h-16 mx-auto text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-14a1 1 0 00-1 1v4a1 1 0 102 0V5a1 1 0 00-1-1zm0 10a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </div>
                <h1 class="text-4xl font-bold text-white mb-2">{{ $isPassed ? 'Great Choice!' : 'Good Try!' }}</h1>
                <p class="text-white/90">{{ $attempt->scenario->title }}</p>
            </div>

            <!-- Score Section -->
            <div class="px-6 py-8">
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-blue-50 rounded-lg p-6 text-center">
                        <div class="text-4xl font-bold text-blue-600">{{ $attempt->safety_score }}/100</div>
                        <p class="text-gray-600 text-sm mt-2">Safety Score</p>
                    </div>

                    <div class="bg-{{ $isPassed ? 'green' : 'orange' }}-50 rounded-lg p-6 text-center">
                        <div class="text-lg font-bold text-{{ $isPassed ? 'green' : 'orange' }}-600">
                            {{ $isPassed ? 'SAFE' : 'RISKY' }}
                        </div>
                        <p class="text-gray-600 text-sm mt-2">Assessment</p>
                    </div>
                </div>

                <!-- Selected Choice -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-bold text-gray-900 mb-2">Your Choice:</h3>
                    <p class="text-gray-700">{{ $attempt->chosenChoice->choice_text }}</p>
                </div>

                <!-- AI Feedback -->
                @if($attempt->ai_feedback)
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <h3 class="font-semibold text-yellow-900 mb-2">💡 Analysis:</h3>
                        <p class="text-yellow-800">{{ $attempt->ai_feedback }}</p>
                    </div>
                @endif

                <!-- Time Spent -->
                @if($attempt->time_spent_seconds)
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <p class="text-gray-700">
                            <span class="font-semibold">Time Spent:</span>
                            @php
                                $minutes = intdiv($attempt->time_spent_seconds, 60);
                                $seconds = $attempt->time_spent_seconds % 60;
                            @endphp
                            {{ sprintf('%02d:%02d', $minutes, $seconds) }}
                        </p>
                    </div>
                @endif

                <!-- Badges -->
                @if(!empty($badgesEarned))
                    <div class="mb-8">
                        <h3 class="font-bold text-lg mb-4">🏆 Badges Earned:</h3>
                        <div class="grid grid-cols-{{ count($badgesEarned) }} gap-4">
                            @foreach($badgesEarned as $badge)
                                <div class="bg-yellow-50 border-2 border-yellow-300 rounded-lg p-4 text-center">
                                    <div class="text-4xl mb-2">{{ $badge['icon'] }}</div>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $badge['name'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex gap-4 pt-6 border-t">
                    <a href="{{ route('scenarios.index') }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg text-center transition">
                        Back to Scenarios
                    </a>
                    <a href="{{ route('scenario.history') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-lg text-center transition">
                        View History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
