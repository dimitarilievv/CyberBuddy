{{-- resources/views/user_progress/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>User Progress</h1>
    @forelse($lessons as $lesson)
        <h2>{{ $lesson->title }}</h2>
        <ul>
            @forelse($progress->where('lesson_id', $lesson->id) as $item)
                <li>
                    Status: <strong>{{ ucfirst($item->status) }}</strong>
                    | Time spent: {{ $item->time_spent_seconds }} seconds
                    | Started: {{ $item->started_at?->format('Y-m-d H:i') ?? 'N/A' }}
                    | Completed: {{ $item->completed_at?->format('Y-m-d H:i') ?? 'N/A' }}
                </li>
            @empty
                <li>No progress found for this lesson.</li>
            @endforelse
        </ul>
    @empty
        <p>No lessons found.</p>
    @endforelse
@endsection
