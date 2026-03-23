@extends('layouts.app')

@section('content')
    <h1>Leaderboard ({{ ucfirst($period) }})</h1>
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Points</th>
            <th>Modules</th>
            <th>Quizzes</th>
            <th>Badges</th>
            <th>Current Streak</th>
        </tr>
        </thead>
        <tbody>
        @forelse($top as $place => $entry)
            <tr>
                <td>{{ $place + 1 }}</td>
                <td>{{ $entry->user->name ?? 'N/A' }}</td>
                <td>{{ $entry->total_points }}</td>
                <td>{{ $entry->modules_completed }}</td>
                <td>{{ $entry->quizzes_passed }}</td>
                <td>{{ $entry->badges_earned }}</td>
                <td>{{ $entry->current_streak }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No leaderboard entries found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
