@extends('layouts.app', ['title' => $howItWorks['heading'] ?? __('How Dearly Works')])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="paper-sheet p-8 sm:p-12 rounded-xl border border-[#E9DDC7] shadow-paper">
        <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">{{ $howItWorks['badge'] }}</span>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#342A25] mt-2 mb-6">
            {{ $howItWorks['heading'] }}
        </h1>

        <div class="space-y-8 text-sm text-[#342A25]">
            @foreach($howItWorks['steps'] as $item)
                <div class="flex items-start space-x-4">
                    <div class="w-8 h-8 rounded-full bg-[#E9DDC7] text-[#783C46] font-serif font-bold text-sm flex items-center justify-center flex-shrink-0 mt-0.5">
                        {{ $item['step'] ?? $loop->iteration }}
                    </div>
                    <div>
                        <h3 class="font-serif font-bold text-base text-[#342A25] mb-1">{{ $item['title'] ?? '' }}</h3>
                        <p class="text-xs text-[#5A4A42] leading-relaxed">
                            {{ $item['desc'] ?? '' }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="{{ auth()->check() ? route('discovery.choose-length') : route('register') }}" class="inline-flex items-center px-6 py-3 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md shadow-sm transition-all">
                {{ $howItWorks['cta_text'] ?? __('Start Your First Letter') }}
            </a>
        </div>
    </div>
</div>
@endsection
