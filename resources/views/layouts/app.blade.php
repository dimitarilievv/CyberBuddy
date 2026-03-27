<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="//unpkg.com/alpinejs" defer></script>
    <style>[x-cloak] { display: none !important; }</style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 flex flex-col">
    <!-- Header Navigation (Full Width) -->
    <nav class="bg-white border-b border-gray-200 fixed top-0 right-0 left-0 z-50 h-16">
        <div class="h-full px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-full">

                {{-- Logo Section --}}
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-blue-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 12c0 3.491 1.497 6.63 3.876 8.797A11.955 11.955 0 0012 21a11.955 11.955 0 005.124-1.203A11.955 11.955 0 0021 12c0-2.168-.575-4.2-1.578-5.953A11.955 11.955 0 0012 2.964z" />
                        </svg>
                    </div>
                    <span class="text-blue-500 font-bold text-base">CyberBuddy</span>

                    {{-- Child Portal Badge --}}
                    @auth
                        @if(request()->is('child*') || auth()->user()->role === 'child')
                            <span class="text-gray-400 text-xs font-semibold tracking-wider uppercase ml-2">Child Portal</span>
                        @endif
                    @endauth
                </div>

                {{-- Right side --}}
                @auth
                    <div class="flex items-center gap-6">

                        {{-- Notifications Button --}}
                        <button class="relative text-gray-400 hover:text-gray-600 transition-colors p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        {{-- User Profile --}}
                        <div class="flex items-center gap-3 ml-2">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-semibold text-gray-900 leading-none">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 leading-none mt-0.5 capitalize">{{ auth()->user()->role ?? 'child' }}</p>
                            </div>
                            <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-300 flex-shrink-0">
                                @php
                                    $profile = optional(auth()->user())->profile;
                                    $avatar = $profile->avatar ?? null;
                                @endphp

                                @if($avatar)
                                    <img src="{{ str_starts_with($avatar, 'http') ? $avatar : Storage::url($avatar) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                @else
                    {{-- Guest Navigation --}}
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                            Get Started
                        </a>
                    </div>
                @endauth

            </div>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="flex flex-1 pt-16">
        {{-- Sidebar (only when logged in) --}}
        @auth
            @include('components.sidebar')
        @endauth

        <!-- Page Content -->
        <main class="flex-1 @auth lg:ml-64 @endauth">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-200 @auth lg:ml-60 @endauth">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
            <p class="text-sm text-gray-500">
                © 2026 CyberBuddy. Your digital safety partner.
            </p>
        </div>
    </footer>
</div>
</body>
</html>
