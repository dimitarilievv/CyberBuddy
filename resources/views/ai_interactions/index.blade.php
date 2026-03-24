@extends('layouts.app')

@section('content')
    <h1>My Recent AI Interactions</h1>
    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif
    <ul>
        @forelse($interactions as $ai)
            <li style="margin-bottom: 1.5em;">
                <strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $ai->type)) }} <br>
                <strong>User:</strong> {{ $ai->user->name ?? 'N/A' }} <br>
                <strong>Prompt:</strong> {{ $ai->prompt }} <br>
                <strong>Response:</strong> {{ $ai->response }} <br>
                <strong>Model:</strong> {{ $ai->model_used ?? '-' }} <br>
                <strong>Tokens Used:</strong> {{ $ai->tokens_used ?? '-' }} <br>
                <strong>Response Time:</strong> {{ $ai->response_time_ms ? $ai->response_time_ms.' ms' : '-' }} <br>
                <strong>Was Helpful?</strong> {!! $ai->was_helpful === null ? '-' : ($ai->was_helpful ? '👍' : '👎') !!}<br>
                <small><strong>Date:</strong> {{ $ai->created_at->format('Y-m-d H:i') }}</small>
            </li>
        @empty
            <li>No interactions yet.</li>
        @endforelse
    </ul>
@endsection
