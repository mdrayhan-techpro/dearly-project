@extends('layouts.app', ['title' => __('Choose Questionnaire Depth')])

@section('content')
<div class="max-w-4xl mx-auto px-4 py-16">
    <div class="text-center max-w-2xl mx-auto mb-12">
        <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">{{ __('Kindred Spirit Discovery') }}</span>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#342A25] mt-2">
            {{ __('Choose Your Questionnaire Depth') }}
        </h1>
        <p class="text-xs sm:text-sm text-[#5A4A42] mt-3 leading-relaxed">
            {{ __('Select how many questions you wish to answer. Each questionnaire covers 17 personality, lifestyle, and communication dimensions to find correspondents with 90%+ compatibility.') }}
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($questionnaires as $q)
            <div class="paper-sheet p-6 rounded-xl border border-[#E9DDC7] shadow-paper flex flex-col justify-between hover:border-[#783C46] transition-all group">
                <div>
                    <div class="w-12 h-12 rounded-full bg-[#E9DDC7] text-[#783C46] font-serif font-bold text-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        {{ $q->length }}
                    </div>
                    <h3 class="font-serif text-lg font-bold text-[#342A25] mb-1">
                        {{ $q->length }} {{ __('Questions') }}
                    </h3>
                    <p class="text-[11px] text-[#B79A61] uppercase tracking-wider font-semibold mb-3">
                        @if($q->length == 20)
                            {{ __('Introductory Discovery (~5 min)') }}
                        @elseif($q->length == 30)
                            {{ __('Balanced Alignment (~8 min)') }}
                        @elseif($q->length == 40)
                            {{ __('Detailed Correspondence (~12 min)') }}
                        @else
                            {{ __('Deep Soul Discovery (~15 min)') }}
                        @endif
                    </p>
                    <p class="text-xs text-[#5A4A42] leading-relaxed">
                        @if($q->length == 20)
                            {{ __('A thoughtful selection of foundational questions touching on communication, reading, and solitude.') }}
                        @elseif($q->length == 30)
                            {{ __('Comprehensive exploration of daily rituals, humor, creative hobbies, and emotional openness.') }}
                        @elseif($q->length == 40)
                            {{ __('Deep dive into ethical perspectives, long-term correspondence, music, and travel curiosities.') }}
                        @else
                            {{ __('Our fullest, most nuanced questionnaire spanning all 17 dimensions for profound kindred alignment.') }}
                        @endif
                    </p>
                </div>

                <div class="pt-6 mt-6 border-t border-[#E9DDC7]">
                    <a href="{{ route('discovery.questionnaire', $q->length) }}" class="w-full inline-flex items-center justify-center py-2.5 px-4 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm transition-all">
                        {{ __('Begin') }} {{ $q->length }}Q &rarr;
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
