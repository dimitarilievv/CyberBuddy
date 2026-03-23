{{-- resources/views/user_badges/users.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1>Users With This Badge</h1>
    <ul>
        @forelse($userBadges as $userBadge)
            <li>
                {{ $userBadge->user->name ?? 'Unknown User' }}
                @if($userBadge->reason)
                    — <em>{{ $userBadge->reason }}</em>
                @endif
                (Earned: {{ $userBadge->earned_at?->format('Y-m-d') ?? 'N/A' }})
            </li>
        @empty
            <li>No users have earned this badge yet.</li>
        @endforelse
    </ul>
@endsection
