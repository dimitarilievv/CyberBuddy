@extends('layouts.app')

@section('title', 'AI Content Suggestions')

@section('content')
    <div class="max-w-3xl mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">AI Content Suggestions</h1>

            <a href="{{ route('ai_suggestions.create') }}" class="bg-blue-500 text-white px-3 py-1 rounded">
                New Suggestion
            </a>
        </div>

        <div class="mb-4">
            <a href="{{ route('ai_suggestions.index', ['status' => 'pending']) }}" class="underline mr-3">Pending</a>
            <a href="{{ route('ai_suggestions.index', ['status' => 'approved']) }}" class="underline mr-3">Approved</a>
            <a href="{{ route('ai_suggestions.index', ['status' => 'rejected']) }}" class="underline">Rejected</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <ul class="space-y-4">
            @forelse($suggestions as $s)
                <li class="bg-white shadow p-4 rounded">
                    <p class="font-semibold">{{ $s->title }}</p>
                    <p class="text-sm text-gray-600">Type: {{ $s->content_type }}</p>
                    <p class="text-sm text-gray-600">Status: {{ $s->status }}</p>

                    <p class="text-sm text-gray-700 mt-2">{{ $s->suggested_content }}</p>

                    <p class="text-xs text-gray-500 mt-2">
                        Submitted: {{ optional($s->created_at)->format('d M Y H:i') }}
                    </p>

                    <div class="flex gap-2 mt-3">
                        @if($s->status === 'pending')
                            <form method="POST" action="{{ route('ai_suggestions.approve', $s->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="admin_notes" placeholder="Notes (optional)"
                                       class="border rounded px-2 py-1 text-sm mr-2">
                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="{{ route('ai_suggestions.reject', $s->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="admin_notes" placeholder="Notes (optional)"
                                       class="border rounded px-2 py-1 text-sm mr-2">
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded">
                                    Reject
                                </button>
                            </form>
                        @endif
                    </div>
                </li>
            @empty
                <li class="bg-white shadow p-4 rounded">
                    <p class="text-gray-600">No suggestions.</p>
                </li>
            @endforelse
        </ul>

        <div class="mt-6">
            {{ $suggestions->links() }}
        </div>
    </div>
@endsection
