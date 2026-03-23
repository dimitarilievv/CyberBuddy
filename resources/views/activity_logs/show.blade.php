@extends('layouts.app')

@section('title', 'Activity Log Details')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Activity Log Details</h1>

            <a href="{{ route('activity_logs.index') }}" class="bg-gray-200 text-gray-800 px-3 py-1 rounded">
                Back
            </a>
        </div>

        <div class="bg-white shadow p-4 rounded space-y-3">
            <div>
                <p class="text-sm text-gray-500">Action</p>
                <p class="font-semibold">{{ $log->action }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Description</p>
                <p>{{ $log->description }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">User</p>
                <p>{{ $log->user->name ?? ('User #' . $log->user_id) }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">IP Address</p>
                <p>{{ $log->ip_address ?? '-' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Loggable</p>
                <p>
                    {{ $log->loggable_type ?? '-' }}
                    @if($log->loggable_id)
                        (ID: {{ $log->loggable_id }})
                    @endif
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Metadata</p>
                <pre class="text-xs bg-gray-50 border rounded p-3 overflow-auto">{{ json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>

            <div>
                <p class="text-sm text-gray-500">Created at</p>
                <p>{{ optional($log->created_at)->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>
@endsection
