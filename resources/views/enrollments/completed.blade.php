@extends('layouts.app')

@section('content')
    <h1>Completed Modules</h1>
    <ul>
        @forelse($completed as $enrollment)
            <li>
                Module: {{ $enrollment->module->title ?? 'N/A' }}
                | Completed: {{ $enrollment->completed_at ? $enrollment->completed_at->format('Y-m-d') : 'N/A' }}
                <a href="{{ route('modules.show', $enrollment->module_id) }}">View Module</a>
            </li>
        @empty
            <li>You have not completed any modules yet.</li>
        @endforelse
    </ul>
@endsection
