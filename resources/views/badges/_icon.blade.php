{{--
    Badge icon partial: resources/views/badges/_icon.blade.php
    Usage: @include('badges._icon', ['icon' => $badge->icon, 'color' => $badge->color])
--}}
@php
    $c = $color ?? '#6B7280';
    $strokeW = '2.2';
@endphp

@switch($icon)

    @case('star')
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="{{ $c }}" stroke="{{ $c }}" stroke-width="{{ $strokeW }}" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
        </svg>
        @break

    @case('compass')
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="{{ $c }}" stroke-width="{{ $strokeW }}" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
        </svg>
        @break

    @case('hero')
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="{{ $c }}" stroke-width="{{ $strokeW }}" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
        </svg>
        @break

    @case('trophy')
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="{{ $c }}" stroke-width="{{ $strokeW }}" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="8 21 16 21"/>
            <line x1="12" y1="17" x2="12" y2="21"/>
            <path d="M7 4H4a2 2 0 0 0-2 2v3a5 5 0 0 0 5 5"/>
            <path d="M17 4h3a2 2 0 0 1 2 2v3a5 5 0 0 1-5 5"/>
            <path d="M6 2h12v10a6 6 0 0 1-12 0V2z"/>
        </svg>
        @break

    @case('score-100')
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="{{ $c }}" stroke-width="{{ $strokeW }}" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <polyline points="9 12 11 14 15 10"/>
        </svg>
        @break

    @case('cap')
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="{{ $c }}" stroke-width="{{ $strokeW }}" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
        </svg>
        @break

    @case('fire')
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="{{ $c }}" stroke-width="{{ $strokeW }}" stroke-linecap="round" stroke-linejoin="round">
            <path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 0 1-7 7 7 7 0 0 1-7-7c0-1.5.5-3 1.5-4.5"/>
        </svg>
        @break

    @case('bolt')
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="{{ $c }}" stroke="{{ $c }}" stroke-width="{{ $strokeW }}" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
        </svg>
        @break

    @case('crown')
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="{{ $c }}" stroke-width="{{ $strokeW }}" stroke-linecap="round" stroke-linejoin="round">
            <path d="M2 4l3 12h14l3-12-6 7-4-7-4 7-6-7z"/>
            <path d="M5 20h14"/>
        </svg>
        @break

    @case('target')
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="{{ $c }}" stroke-width="{{ $strokeW }}" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/>
            <circle cx="12" cy="12" r="6"/>
            <circle cx="12" cy="12" r="2"/>
        </svg>
        @break

    @case('robot')
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="{{ $c }}" stroke-width="{{ $strokeW }}" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="11" width="18" height="10" rx="2"/>
            <circle cx="12" cy="5" r="2"/>
            <path d="M12 7v4"/>
            <line x1="8" y1="16" x2="8" y2="16"/>
            <line x1="16" y1="16" x2="16" y2="16"/>
        </svg>
        @break

    @case('handshake')
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="{{ $c }}" stroke-width="{{ $strokeW }}" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20.42 4.58a5.4 5.4 0 0 0-7.65 0l-.77.78-.77-.78a5.4 5.4 0 0 0-7.65 0C1.46 6.7 1.33 10.28 4 13l8 8 8-8c2.67-2.72 2.54-6.3.42-8.42z"/>
        </svg>
        @break

    @default
        {{-- Generic medal fallback --}}
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="{{ $c }}" stroke-width="{{ $strokeW }}" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="8" r="6"/>
            <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>
        </svg>
@endswitch
