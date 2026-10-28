@extends('layouts.app', ['title' => __('Set New Password')])

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="paper-sheet p-8 rounded-xl border border-[#E9DDC7] shadow-paper">
        <!-- Stamp & Header -->
        <div class="text-center mb-8">
            <div class="wax-seal w-12 h-12 mx-auto mb-3 font-serif font-bold text-lg shadow-seal">
                D
            </div>
            <h1 class="font-serif text-2xl font-bold text-[#342A25]">{{ __('Set New Password') }}</h1>
            <p class="text-xs text-[#5A4A42] mt-1">{{ __('Seal your postbox with a new secure passphrase') }}</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-xs font-semibold text-[#342A25] mb-1">{{ __('Email Address') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required autofocus
                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                @error('email')
                    <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-[#342A25] mb-1">{{ __('New Password') }}</label>
                <input id="password" type="password" name="password" required
                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                @error('password')
                    <p class="text-[11px] text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-[#342A25] mb-1">{{ __('Confirm New Password') }}</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
            </div>

            <button type="submit" class="w-full py-2.5 px-4 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md shadow-sm transition-all">
                {{ __('Update Password & Sign In') }}
            </button>
        </form>
    </div>
</div>
@endsection

