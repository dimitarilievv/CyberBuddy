@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Activity Logs</h1>

        <ul class="space-y-4">
            @forelse($logs as $log)
                <li class="bg-white shadow p-4 rounded flex justify-between items-start">
                    <div>
                        <p class="font-semibold">{{ $log->action }}</p>
                        <p class="text-sm text-gray-600">{{ $log->description }}</p>

                        <p class="text-xs text-gray-500 mt-2">
                            User: {{ $log->user->name ?? ('User #' . $log->user_id) }}
                            • IP: {{ $log->ip_address ?? '-' }}
                            • {{ optional($log->created_at)->format('d M Y H:i') }}
                        </p>
                    </div>

                    <a href="{{ route('activity_logs.show', $log->id) }}"
                       class="bg-blue-500 text-white px-3 py-1 rounded">
                        View
                    </a>
                </li>
            @empty
                <li class="bg-white shadow p-4 rounded">
                    <p class="text-gray-600">No logs found.</p>
                </li>
            @endforelse
        </ul>

        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    </div>
@endsection
