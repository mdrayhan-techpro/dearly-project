@extends('layouts.app', ['title' => 'Discovered Pen-Pal Matches'])

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-[#E9DDC7] pb-6 mb-8 gap-4">
        <div>
            <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">Kindred Correspondence</span>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#342A25] mt-1">
                Discovered Pen Friends (90%+ Compatibility)
            </h1>
            <p class="text-xs text-[#5A4A42] mt-1">
                Kindred spirits whose questionnaire answers align deeply with your own.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('discovery.choose-length') }}" class="px-4 py-2 text-xs font-semibold uppercase tracking-wider text-[#342A25] bg-[#FAF6EE] border border-[#D6C7AE] hover:bg-[#E9DDC7] rounded shadow-sm transition-all">
                Retake Questionnaire
            </a>
            <a href="{{ route('letters.compose') }}" class="px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm transition-all">
                Write a Letter
            </a>
        </div>
    </div>

    @if(!$latestAttempt)
        <!-- Empty State: Questionnaire Not Taken -->
        <div class="paper-sheet p-12 rounded-xl border border-[#E9DDC7] shadow-paper text-center max-w-xl mx-auto space-y-4">
            <div class="wax-seal w-14 h-14 mx-auto text-xl font-serif font-bold shadow-seal">
                ★
            </div>
            <h2 class="font-serif text-xl font-bold text-[#342A25]">Your Questionnaire Awaits</h2>
            <p class="text-xs text-[#5A4A42] leading-relaxed">
                To discover pen friends with genuine intellectual and emotional compatibility, take our discovery questionnaire. Choose between 20, 30, 40, or 50 questions.
            </p>
            <div class="pt-2">
                <a href="{{ route('discovery.choose-length') }}" class="inline-flex items-center px-6 py-3 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-md transition-all">
                    Choose Questionnaire Depth &rarr;
                </a>
            </div>
        </div>
    @elseif($matches->isEmpty())
        <!-- Empty State: No Matches Found Yet -->
        <div class="paper-sheet p-12 rounded-xl border border-[#E9DDC7] shadow-paper text-center max-w-xl mx-auto space-y-4">
            <div class="text-4xl text-[#B79A61]">⏳</div>
            <h2 class="font-serif text-xl font-bold text-[#342A25]">No matches today</h2>
            <p class="text-xs text-[#5A4A42] leading-relaxed">
                Sometimes the best friendships take a little time. As more thoughtful correspondents join the post office and complete questionnaires, new qualifying matches will arrive in your post office box.
            </p>
            <div class="pt-2 flex justify-center space-x-3">
                <a href="{{ route('discovery.choose-length') }}" class="px-5 py-2.5 text-xs font-semibold uppercase text-white bg-[#783C46] hover:bg-[#582A32] rounded">
                    Retake Questionnaire
                </a>
                <a href="{{ route('letters.compose') }}" class="px-5 py-2.5 text-xs font-semibold uppercase text-[#342A25] bg-[#FAF6EE] border border-[#D6C7AE] hover:bg-[#E9DDC7] rounded">
                    Write to a Known Code
                </a>
            </div>
        </div>
    @else
        <!-- Matches Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($matches as $match)
                @php
                    $matchedUser = $match->matchedUser;
                    $profile = $matchedUser->profile;
                @endphp
                <div class="paper-sheet p-6 sm:p-7 rounded-xl border border-[#E9DDC7] shadow-paper flex flex-col justify-between hover:border-[#783C46] transition-all relative">
                    <!-- Top Match Badge & Nickname -->
                    <div>
                        <div class="flex items-start justify-between border-b border-[#E9DDC7] pb-4 mb-4">
                            <div>
                                <h3 class="font-serif text-lg font-bold text-[#342A25]">
                                    {{ $matchedUser->nickname }}
                                </h3>
                                <p class="text-xs text-[#5A4A42]">
                                    Age {{ $profile->age ?? 'Adult' }}
                                    @if($profile->country)
                                        • {{ $profile->country }}
                                    @endif
                                    • <span class="uppercase text-[10px] text-[#B79A61] font-semibold">{{ $profile->preferred_language ?? 'en' }}</span>
                                </p>
                            </div>

                            <!-- Compatibility Score Pill -->
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-[#405B4B]/15 text-[#2D4135] border border-[#405B4B]/30">
                                    {{ number_format($match->compatibility_score, 1) }}% Match
                                </span>
                            </div>
                        </div>

                        <!-- Bio -->
                        @if($profile && $profile->bio)
                            <p class="text-xs text-[#342A25] italic leading-relaxed mb-4 bg-[#F6F0E5]/60 p-3 rounded border border-[#E9DDC7]/60">
                                "{{ $profile->bio }}"
                            </p>
                        @endif

                        <!-- Shared High-Scoring Dimensions -->
                        @if(!empty($match->shared_dimensions))
                            <div class="mb-4">
                                <span class="text-[10px] uppercase tracking-wider text-[#B79A61] font-semibold block mb-1.5">
                                    Aligned Dimensions
                                </span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($match->shared_dimensions as $dim)
                                        <span class="text-[11px] bg-[#FAF6EE] text-[#5A4A42] px-2.5 py-1 rounded border border-[#E9DDC7]">
                                            {{ $dim }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Passions & Interests -->
                        @if($profile && !empty($profile->interests))
                            <div class="mb-4">
                                <span class="text-[10px] uppercase tracking-wider text-[#B79A61] font-semibold block mb-1.5">
                                    Interests
                                </span>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($profile->interests as $interest)
                                        <span class="text-[10px] bg-[#E9DDC7]/60 text-[#342A25] px-2 py-0.5 rounded">
                                            {{ $interest }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Bottom Action Toolbar -->
                    <div class="pt-5 border-t border-[#E9DDC7] flex items-center justify-between gap-2">
                        <div class="text-[11px] text-[#5A4A42]">
                            Address: <code class="font-mono text-[#783C46] font-bold">{{ $matchedUser->mailingCode?->code ?? 'N/A' }}</code>
                        </div>

                        <div class="flex items-center space-x-2">
                            <!-- Dismiss -->
                            <form method="POST" action="{{ route('discovery.dismiss', $match->id) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 text-xs text-[#5A4A42] hover:text-[#783C46] border border-transparent hover:border-[#E9DDC7] rounded transition-colors" title="Dismiss this suggestion">
                                    Dismiss
                                </button>
                            </form>

                            <!-- Write Letter CTA -->
                            @if($matchedUser->mailingCode)
                                <a href="{{ route('letters.compose', ['code' => $matchedUser->mailingCode->code]) }}" class="px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm transition-all transform hover:-translate-y-0.5">
                                    Write Letter &rarr;
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
