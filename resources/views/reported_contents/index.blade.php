@extends('layouts.app')

@section('title', 'Reported Content')

@section('content')
    <div class="max-w-4xl mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Reported Content</h1>

            <a href="{{ route('reported_contents.create') }}" class="bg-blue-500 text-white px-3 py-1 rounded">
                Report content
            </a>
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
            @forelse($reports as $r)
                <li class="bg-white shadow p-4 rounded flex justify-between items-start">
                    <div>
                        <p class="font-semibold">{{ $r->reason }}</p>
                        <p class="text-sm text-gray-600">Status: {{ $r->status }}</p>
                        <p class="text-xs text-gray-500 mt-2">
                            {{ optional($r->created_at)->format('d M Y H:i') }}
                        </p>
                    </div>

                    <a href="{{ route('reported_contents.show', $r->id) }}"
                       class="bg-blue-500 text-white px-3 py-1 rounded">
                        View
                    </a>
                </li>
            @empty
                <li class="bg-white shadow p-4 rounded">
                    <p class="text-gray-600">No reports.</p>
                </li>
            @endforelse
        </ul>

        <div class="mt-6">
            {{ $reports->links() }}
        </div>
    </div>
@endsection
