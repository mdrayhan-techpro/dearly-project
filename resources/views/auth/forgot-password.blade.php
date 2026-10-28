@extends('layouts.app', ['title' => __('Recover Your Postbox Access')])

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="paper-sheet p-8 rounded-xl border border-[#E9DDC7] shadow-paper">
        <!-- Stamp & Header -->
        <div class="text-center mb-8">
            <div class="wax-seal w-12 h-12 mx-auto mb-3 font-serif font-bold text-lg shadow-seal">
                D
            </div>
            <h1 class="font-serif text-2xl font-bold text-[#342A25]">{{ __('Forgot Password') }}</h1>
            <p class="text-xs text-[#5A4A42] mt-1">{{ __('Enter your registered email to receive a recovery key') }}</p>
        </div>

        @if (session('status'))
            <div class="mb-5 p-3 rounded-md bg-[#405B4B]/10 border border-[#405B4B]/30 text-xs text-[#405B4B] text-center font-medium">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold text-[#342A25] mb-1">{{ __('Registered Email Address') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                @error('email')
                    <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-2.5 px-4 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md shadow-sm transition-all">
                {{ __('Send Recovery Link') }}
            </button>
        </form>

        <p class="text-xs text-center text-[#5A4A42] mt-6">
            {{ __('Remembered your credentials?') }}
            <a href="{{ route('login') }}" class="text-[#783C46] hover:underline font-semibold">{{ __('Return to Sign In') }}</a>
        </p>
    </div>
</div>
@endsection

