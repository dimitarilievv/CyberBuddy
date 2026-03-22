@extends('layouts.app')

@section('title', "Attempt for {$attempt->quiz->title}")

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Quiz: {{ $attempt->quiz->title }}</h1>
        <p class="mb-2">Status: <strong class="{{ $attempt->status === 'passed' ? 'text-green-600' : 'text-red-600' }}">{{ ucfirst($attempt->status) }}</strong></p>
        <p class="mb-2">Score: <strong>{{ $attempt->score }}%</strong></p>
        <p class="mb-2">Time Spent: <strong>{{ gmdate('H:i:s', $attempt->time_spent_seconds) }}</strong></p>

        <h2 class="text-xl font-semibold mt-6 mb-2">Questions</h2>
        <div class="space-y-4">
            @foreach($attempt->questionAnswers as $answer)
                <div class="p-4 border rounded-lg {{ $answer->is_correct ? 'bg-green-50' : 'bg-red-50' }}">
                    <p class="font-semibold">Q{{ $loop->iteration }}: {{ $answer->question->question_text }}</p>
                    <p>Given Answer: <strong>{{ $answer->given_answer }}</strong></p>
                    <p>Correct Answer: <strong>{{ $answer->question->correct_answer }}</strong></p>
                    <p>Status: <span class="{{ $answer->is_correct ? 'text-green-600' : 'text-red-600' }}">{{ $answer->is_correct ? 'Correct' : 'Incorrect' }}</span></p>
                    <p>Points Earned: <strong>{{ $answer->points_earned }}</strong></p>
                    @if($answer->ai_explanation)
                        <p class="mt-1 text-gray-600">AI Feedback: {{ $answer->ai_explanation }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        @if($attempt->ai_feedback)
            <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
                <h3 class="font-semibold">AI Encouragement</h3>
                <p>{{ $attempt->ai_feedback }}</p>
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('quiz_attempts.index') }}" class="text-blue-500 hover:underline">Back to My Attempts</a>
        </div>
    </div>
@endsection
