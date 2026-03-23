@extends('layouts.app')

@section('content')
    <h1>My Badges</h1>
    <ul>
        @forelse($userBadges as $userBadge)
            <li>
                <strong>{{ $userBadge->badge->name }}</strong>
                @if($userBadge->reason) - <em>{{ $userBadge->reason }}</em> @endif
                (Earned: {{ $userBadge->earned_at?->format('Y-m-d') ?? 'N/A' }})
            </li>
        @empty
            <li>You have not earned any badges yet.</li>
        @endforelse
    </ul>
@endsection
