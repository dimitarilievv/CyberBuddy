@extends('layouts.app')

@section('content')
    <h1>Your Badges</h1>
    @if(session('awarded'))
        <div class="alert alert-success">
            <strong>New badges awarded!</strong>
            <ul>
                @foreach(session('awarded') as $badge)
                    <li>{{ $badge->name }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <ul>
        @foreach($badges as $badge)
            <li>{{ $badge->name }}</li>
        @endforeach
    </ul>
    <form method="POST" action="{{ route('badges.check') }}">
        @csrf
        <button type="submit">Check and Award Badges</button>
    </form>
@endsection

