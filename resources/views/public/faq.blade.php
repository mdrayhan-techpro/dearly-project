@extends('layouts.app', ['title' => __('Frequently Asked Questions')])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="paper-sheet p-8 sm:p-12 rounded-xl border border-[#E9DDC7] shadow-paper">
        <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">{{ __('Post Office Desk') }}</span>
        <h1 class="font-serif text-3xl font-bold text-[#342A25] mt-2 mb-6">
            {{ __('Frequently Asked Questions') }}
        </h1>

        <div class="space-y-6">
            @forelse($faqs as $faq)
                <div class="border-b border-[#E9DDC7] pb-4">
                    <h3 class="font-serif font-bold text-base text-[#342A25] mb-2">{{ $faq['q'] }}</h3>
                    <p class="text-xs text-[#5A4A42] leading-relaxed">{{ $faq['a'] }}</p>
                </div>
            @empty
                <p class="text-xs text-[#5A4A42]">{{ __('No questions published at this time.') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
