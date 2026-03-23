@extends('layouts.app')

@section('title', 'My Notifications')

@section('content')
    <div class="max-w-3xl mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">My Notifications</h1>

            <form method="POST" action="{{ route('notifications.read_all') }}">
                @csrf
                @method('PATCH')
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">
                    Mark all as read
                </button>
            </form>
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
            @forelse($notifications as $notification)
                @include('notifications.partials.item', ['notification' => $notification])
            @empty
                <li class="bg-white shadow p-4 rounded">
                    <p class="text-gray-600">No notifications yet.</p>
                </li>
            @endforelse
        </ul>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection
