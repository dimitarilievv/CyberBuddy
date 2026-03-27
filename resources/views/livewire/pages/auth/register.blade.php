<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen bg-slate-50 flex flex-col items-center justify-center px-4 py-12">

    {{-- Branding Above Card --}}
    <div class="flex items-center gap-3 mb-8">
        <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-200">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <span class="text-slate-900 font-extrabold text-2xl tracking-tight">
            Cyber<span class="text-blue-600">Buddy</span>
        </span>
    </div>

    {{-- Card --}}
    <div class="w-full max-w-md bg-white border border-slate-200 rounded-3xl shadow-xl shadow-slate-200/60 p-10">

        {{-- Heading --}}
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight mb-2 text-center">Join the adventure</h1>
        <p class="text-sm text-slate-500 mb-8 text-center">Start your journey to becoming a digital hero.</p>

        <form wire:submit="register" class="space-y-5">

            {{-- Name --}}
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">
                    Full Name
                </label>
                <input
                    wire:model="name"
                    id="name"
                    type="text"
                    name="name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Alex Explorer"
                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition"
                >
                @error('name')
                <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email Address --}}
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">
                    Email address
                </label>
                <input
                    wire:model="email"
                    id="email"
                    type="email"
                    name="email"
                    required
                    autocomplete="username"
                    placeholder="hero@example.com"
                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition"
                >
                @error('email')
                <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">
                        Password
                    </label>
                    <input
                        wire:model="password"
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition"
                    >
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">
                        Confirm
                    </label>
                    <input
                        wire:model="password_confirmation"
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition"
                    >
                </div>
            </div>
            @error('password')
            <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
            @enderror

            {{-- Actions --}}
            <div class="pt-2">
                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.98] text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 transition-all duration-200"
                >
                    <span>Create Account</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </div>

            <p class="text-center text-sm text-slate-500">
                Already have an account?
                <a href="{{ route('login') }}" wire:navigate class="text-blue-600 font-bold hover:underline">Sign in</a>
            </p>

        </form>
    </div>
</div>
