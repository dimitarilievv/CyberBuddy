@extends('layouts.app')

@section('title', 'Quiz Attempt')

@section('content')
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Quiz Attempt Summary</h1>

        <div class="bg-white shadow rounded p-4 mb-6">
            <p><strong>Total Questions:</strong> {{ $result['summary']['total_questions'] }}</p>
            <p><strong>Correct Answers:</strong> {{ $result['summary']['correct_answers'] }}</p>
            <p><strong>Total Points:</strong> {{ $result['summary']['total_points'] }}</p>
            <p><strong>Score:</strong> {{ $result['summary']['percentage'] }}%</p>
            <p class="mt-2 text-green-600 font-semibold">Feedback: {{ $result['feedback'] }}</p>
        </div>

        <h2 class="text-xl font-bold mb-2">Answers</h2>
        <ul class="space-y-2">
            @foreach($result['summary'] ?? [] as $key => $value)
                {{-- You can expand to show each question & answer --}}
            @endforeach
            {{-- Placeholder: If you want Livewire/Alpine.js interactive answers, can loop actual answers here --}}
        </ul>
    </div>
@endsection
