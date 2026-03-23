@extends('layouts.app')

@section('content')
    <h1>My Leaderboard Stats ({{ ucfirst($period) }})</h1>

    @if($stats)
        <ul>
            <li><strong>Rank:</strong> {{ $stats->rank ?? '-' }}</li>
            <li><strong>Total Points:</strong> {{ $stats->total_points }}</li>
            <li><strong>Modules Completed:</strong> {{ $stats->modules_completed }}</li>
            <li><strong>Quizzes Passed:</strong> {{ $stats->quizzes_passed }}</li>
            <li><strong>Badges Earned:</strong> {{ $stats->badges_earned }}</li>
            <li><strong>Current Streak:</strong> {{ $stats->current_streak }}</li>
            <li><strong>Longest Streak:</strong> {{ $stats->longest_streak }}</li>
        </ul>
    @else
        <p>No stats available for you in this period.</p>
    @endif
@endsection
