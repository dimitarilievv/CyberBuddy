{{-- resources/views/user_progress/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>User Progress for {{ $lesson->title }}</h1>

    @if($progress)
        <p>Status: <strong>{{ ucfirst($progress->status) }}</strong></p>
{{--        <p>Time Spent: {{ $progress->time_spent_seconds }} seconds</p>--}}
        <p>Started At: {{ $progress->started_at?->format('Y-m-d H:i') ?? 'N/A' }}</p>
        <p>Completed At: {{ $progress->completed_at?->format('Y-m-d H:i') ?? 'N/A' }}</p>
    @else
        <p>No progress found for this lesson.</p>
    @endif
@endsection
