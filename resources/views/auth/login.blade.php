@extends('layouts.app', ['title' => __('Sign In to Your Post Office Box')])

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="paper-sheet p-8 rounded-xl border border-[#E9DDC7] shadow-paper">
        <!-- Stamp & Crest Header -->
        <div class="text-center mb-8">
            <div class="wax-seal w-12 h-12 mx-auto mb-3 font-serif font-bold text-lg shadow-seal">
                D
            </div>
            <h1 class="font-serif text-2xl font-bold text-[#342A25]">{{ __('Sign In') }}</h1>
            <p class="text-xs text-[#5A4A42] mt-1">{{ __('Open your private correspondence postbox') }}</p>
        </div>

        <!-- Google OAuth Primary Button -->
        <div class="mb-6">
            <a href="{{ route('auth.google.redirect') }}" class="w-full flex items-center justify-center space-x-3 px-4 py-3 border border-[#D6C7AE] rounded-md shadow-sm bg-white hover:bg-[#F9F6F0] text-xs font-semibold text-[#342A25] transition-all">
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>{{ __('Continue with Google') }}</span>
            </a>
            <p class="text-[10px] text-center text-[#5A4A42] mt-1.5">{{ __('Your Google email is never revealed to pen friends.') }}</p>
        </div>

        <div class="relative my-6 text-center">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-[#E9DDC7]"></div></div>
            <span class="relative bg-[#FAF6EE] px-3 text-[11px] text-[#B79A61] uppercase tracking-wider">{{ __('or sign in with password') }}</span>
        </div>

        <!-- Standard Login Form -->
        <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold text-[#342A25] mb-1">{{ __('Email Address') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-[#342A25] mb-1">{{ __('Password') }}</label>
                <input id="password" type="password" name="password" required
                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="remember" class="rounded border-[#D6C7AE] text-[#783C46] focus:ring-[#783C46]/20">
                    <span class="text-[#5A4A42]">{{ __('Remember me') }}</span>
                </label>
            </div>

            <button type="submit" class="w-full py-2.5 px-4 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md shadow-sm transition-all">
                {{ __('Unlock Postbox') }}
            </button>
        </form>

        <p class="text-xs text-center text-[#5A4A42] mt-6">
            {{ __("Don't have a post office box yet?") }}
            <a href="{{ route('register') }}" class="text-[#783C46] hover:underline font-semibold">{{ __('Join Dearly') }}</a>
        </p>

        <!-- Quick Developer Test Personas -->
        <div class="mt-8 pt-6 border-t border-dashed border-[#B79A61]/60">
            <span class="block text-[11px] font-serif font-bold text-[#783C46] uppercase tracking-wider text-center mb-3">
                ★ {{ __('Instant Test Personas (One-Click)') }} ★
            </span>
            <div class="grid grid-cols-2 gap-2 text-[11px]">
                <form method="POST" action="{{ route('dev.login') }}">
                    @csrf
                    <input type="hidden" name="email" value="eleanor@dearlyletters.com">
                    <button type="submit" class="w-full p-2 bg-[#F6F0E5] hover:bg-[#E9DDC7] border border-[#D6C7AE] rounded text-left">
                        <span class="font-bold text-[#342A25] block">Eleanor Vance</span>
                        <span class="text-[9px] text-[#5A4A42]">{{ __('Botanist • Age 28') }}</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('dev.login') }}">
                    @csrf
                    <input type="hidden" name="email" value="julian@dearlyletters.com">
                    <button type="submit" class="w-full p-2 bg-[#F6F0E5] hover:bg-[#E9DDC7] border border-[#D6C7AE] rounded text-left">
                        <span class="font-bold text-[#342A25] block">Julian Cross</span>
                        <span class="text-[9px] text-[#5A4A42]">{{ __('Photographer • Age 31') }}</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('dev.login') }}">
                    @csrf
                    <input type="hidden" name="email" value="sophie@dearlyletters.com">
                    <button type="submit" class="w-full p-2 bg-[#F6F0E5] hover:bg-[#E9DDC7] border border-[#D6C7AE] rounded text-left">
                        <span class="font-bold text-[#342A25] block">Sophie Bordeaux</span>
                        <span class="text-[9px] text-[#5A4A42]">{{ __('Astronomy • Age 26') }}</span>
                    </button>
                </form>

                <form method="POST" action="{{ route('dev.login') }}">
                    @csrf
                    <input type="hidden" name="email" value="admin@dearlyletters.com">
                    <input type="hidden" name="admin" value="1">
                    <button type="submit" class="w-full p-2 bg-[#783C46]/10 hover:bg-[#783C46]/20 border border-[#783C46]/40 rounded text-left">
                        <span class="font-bold text-[#783C46] block">{{ __('Chief Postmaster') }}</span>
                        <span class="text-[9px] text-[#783C46]">{{ __('Super Admin Access') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
