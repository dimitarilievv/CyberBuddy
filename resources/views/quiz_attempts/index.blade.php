@extends('layouts.app')

@section('title', 'My Quiz Attempts')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">My Quiz Attempts</h1>

        @if($attempts->isEmpty())
            <p class="text-gray-600">You haven't attempted any quizzes yet.</p>
        @else
            <table class="min-w-full bg-white shadow rounded-lg">
                <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 text-left">Quiz</th>
                    <th class="p-3 text-left">Score</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Time Spent</th>
                    <th class="p-3 text-left">Started At</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($attempts as $attempt)
                    @php
                        $timeSpent = isset($attempt->time_spent_seconds) && $attempt->time_spent_seconds !== null
                            ? gmdate('H:i:s', (int) $attempt->time_spent_seconds)
                            : '-';
                        $startedAt = $attempt->started_at ? $attempt->started_at->format('Y-m-d H:i') : '-';
                    @endphp
                    <tr class="border-t">
                        <td class="p-3">{{ $attempt->quiz->title }}</td>
                        <td class="p-3">{{ $attempt->score }}%</td>
                        <td class="p-3 capitalize">{{ $attempt->status }}</td>
                        <td class="p-3">{{ $timeSpent }}</td>
                        <td class="p-3">{{ $startedAt }}</td>
                        <td class="p-3">
                            <a href="{{ route('quiz_attempts.show', $attempt->id) }}" class="text-blue-500 hover:underline">View</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $attempts->links() }}
            </div>
        @endif
    </div>
@endsection
