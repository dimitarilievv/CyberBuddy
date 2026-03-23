@extends('layouts.app')

@section('content')
    <h1>Your Enrollments</h1>
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    <ul>
        @forelse($enrollments as $enrollment)
            <li>
                Module: {{ $enrollment->module->title ?? 'N/A' }}
                | Status: <strong>{{ ucfirst($enrollment->status) }}</strong>
                | Progress: {{ $enrollment->progress_percentage ?? 0 }}%
                | Enrolled: {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->format('Y-m-d') : 'N/A' }}
                <a href="{{ route('modules.show', $enrollment->module_id) }}">View Module</a>
            </li>
        @empty
            <li>You are not enrolled in any modules yet.</li>
        @endforelse
    </ul>
@endsection
