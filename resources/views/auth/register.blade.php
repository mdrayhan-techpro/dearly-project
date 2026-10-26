@extends('layouts.app', ['title' => __('Establish a Post Office Box')])

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="paper-sheet p-8 rounded-xl border border-[#E9DDC7] shadow-paper">
        <div class="text-center mb-8">
            <div class="wax-seal w-12 h-12 mx-auto mb-3 font-serif font-bold text-lg shadow-seal">
                D
            </div>
            <h1 class="font-serif text-2xl font-bold text-[#342A25]">{{ __('Join Dearly') }}</h1>
            <p class="text-xs text-[#5A4A42] mt-1">{{ __('Establish your private postal box') }}</p>
        </div>

        <form method="POST" action="{{ route('register.submit') }}" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-xs font-semibold text-[#342A25] mb-1">{{ __('Your Name') }}</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-[#342A25] mb-1">{{ __('Private Email Address') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                <p class="text-[10px] text-[#5A4A42] mt-1">{{ __('Never revealed to other pen friends.') }}</p>
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-[#342A25] mb-1">{{ __('Password') }}</label>
                <input id="password" type="password" name="password" required
                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-[#342A25] mb-1">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
            </div>

            <button type="submit" class="w-full py-2.5 px-4 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md shadow-sm transition-all">
                {{ __('Establish Box') }}
            </button>
        </form>

        <p class="text-xs text-center text-[#5A4A42] mt-6">
            {{ __('Already have an established box?') }}
            <a href="{{ route('login') }}" class="text-[#783C46] hover:underline font-semibold">{{ __('Sign In') }}</a>
        </p>
    </div>
</div>
@endsection
