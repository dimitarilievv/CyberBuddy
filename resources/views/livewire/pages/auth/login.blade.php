<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen bg-slate-50 flex flex-col items-center justify-center px-4 py-12">

    {{-- Branding Above Card --}}
    <div class="flex items-center gap-3 mb-8">
        <div class="w-8 h-8 rounded-md bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-200">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.955 11.955 0 003 12c0 3.491 1.497 6.63 3.876 8.797A11.955 11.955 0 0012 21a11.955 11.955 0 005.124-1.203A11.955 11.955 0 0021 12c0-2.168-.575-4.2-1.578-5.953A11.955 11.955 0 0012 2.964z"></path>
            </svg>
        </div>
        <span class="text-slate-900 font-extrabold text-2xl tracking-tight">
            Cyber<span class="text-blue-600">Buddy</span>
        </span>
    </div>

    {{-- Card --}}
    <div class="w-full max-w-md bg-white border border-slate-200 rounded-3xl shadow-xl shadow-slate-200/60 p-10">

        {{-- Heading --}}
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight mb-2 text-center">Welcome back</h1>
        <p class="text-sm text-slate-500 mb-8 text-center">Ready to continue your digital adventure?</p>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="mb-6 px-4 py-3 rounded-xl bg-green-50 border border-green-100 text-green-700 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit="login" class="space-y-6">

            {{-- Email --}}
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">
                    Email address
                </label>
                <input
                    wire:model="form.email"
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition"
                >
                @error('form.email')
                <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <div class="flex justify-between mb-2">
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-widest">
                        Password
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" wire:navigate class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition">
                            Forgot?
                        </a>
                    @endif
                </div>
                <input
                    wire:model="form.password"
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition"
                >
                @error('form.password')
                <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center gap-2.5">
                <input
                    wire:model="form.remember"
                    id="remember"
                    type="checkbox"
                    name="remember"
                    class="w-4 h-4 rounded border-slate-300 bg-white text-blue-600 focus:ring-blue-500/20 cursor-pointer"
                >
                <label for="remember" class="text-sm font-medium text-slate-600 cursor-pointer select-none">
                    Keep me signed in
                </label>
            </div>

            {{-- Actions --}}
            <button
                type="submit"
                class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 transition-all duration-200"
            >
                <span>Sign in to Dashboard</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>

            <p class="text-center text-sm text-slate-500 pt-2">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Get Started</a>
            </p>

        </form>
    </div>
</div>
