@extends('layouts.app')

@section('content')
    <h1>Enrollments for Module #{{ $moduleId }}</h1>
    <ul>
        @forelse($enrollments as $enrollment)
            <li>
                User: {{ $enrollment->user->name ?? 'N/A' }}
                | Status: <strong>{{ ucfirst($enrollment->status) }}</strong>
                | Progress: {{ $enrollment->progress_percentage ?? 0 }}%
                | Enrolled: {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('Y-m-d') : 'N/A' }}
            </li>
        @empty
            <li>No enrollments for this module yet.</li>
        @endforelse
    </ul>
@endsection
