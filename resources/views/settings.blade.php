@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-5xl mx-auto">

            {{-- Page Header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Profile & Settings</h1>
                <p class="text-sm text-gray-500 mt-1">Manage your account preferences, accessibility, and privacy controls.</p>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 w-full">

                    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Avatar --}}
                        <div class="flex items-center gap-5 mb-6 pb-6 border-b border-gray-100">
                            <div class="relative">
                                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-cyan-100 to-cyan-300 flex items-center justify-center overflow-hidden ring-4 ring-white shadow-md">
                                    @if($profile->avatar)
                                        <img src="{{ Storage::url($profile->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-10 h-10 text-cyan-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-1">Profile Picture</p>
                                <p class="text-xs text-gray-400 mb-3">This avatar will be visible on the leaderboard and in class discussions.</p>
                                <div class="flex items-center gap-2">
                                    <label class="cursor-pointer px-4 py-1.5 bg-white border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition font-medium">
                                        Change Photo
                                        <input type="file" name="avatar" class="sr-only" accept="image/*">
                                    </label>
                                </div>
                                @error('avatar')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Name & Date of Birth --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Display Name</label>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:border-transparent transition"
                                    placeholder="Your name"
                                >
                                @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Date of Birth</label>
                                <input
                                    type="date"
                                    name="date_of_birth"
                                    value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:border-transparent transition"
                                >
                                @error('date_of_birth')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- School & Email --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">School</label>
                                <input
                                    type="text"
                                    name="school"
                                    value="{{ old('school', $profile->school) }}"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:border-transparent transition"
                                    placeholder="Your school"
                                >
                                @error('school')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                                <input
                                    type="email"
                                    value="{{ $user->email }}"
                                    class="w-full px-3 py-2.5 border border-gray-100 rounded-xl text-sm bg-gray-50 text-gray-400 cursor-not-allowed"
                                    disabled
                                >
                                <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Email can only be changed by an administrator.
                                </p>
                            </div>
                        </div>

                        {{-- Password Change --}}
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
                                <input
                                    type="password"
                                    name="password"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:border-transparent transition"
                                    placeholder="Leave blank to keep current password"
                                >
                                @error('password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm New Password</label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="w-full px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:border-transparent transition"
                                    placeholder="Confirm password"
                                >
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                            <button
                                type="submit"
                                class="px-5 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold rounded-xl flex items-center gap-2 transition"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                </svg>
                                Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
