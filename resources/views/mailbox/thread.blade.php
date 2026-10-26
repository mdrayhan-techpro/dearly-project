@extends('layouts.app', ['title' => 'Correspondence Thread'])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex items-center justify-between border-b border-[#E9DDC7] pb-4 mb-8">
        <div>
            <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">Correspondence Archive</span>
            <h1 class="font-serif text-2xl font-bold text-[#342A25]">
                Letters with {{ $otherParticipant->nickname }}
            </h1>
        </div>

        <div class="flex items-center space-x-3 text-xs">
            <a href="{{ route('mailbox.index') }}" class="text-[#5A4A42] hover:text-[#783C46]">
                &larr; Back to Mailbox
            </a>
            <a href="{{ route('letters.compose', ['code' => $otherParticipant->mailingCode?->code]) }}" class="px-5 py-2 font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm">
                Write Letter &rarr;
            </a>
        </div>
    </div>

    <!-- Letters List in Thread -->
    <div class="space-y-6">
        @foreach($letters as $letter)
            <div class="paper-sheet p-6 sm:p-8 rounded-xl border border-[#E9DDC7] shadow-paper space-y-4">
                <div class="flex items-center justify-between border-b border-[#E9DDC7] pb-3 text-xs">
                    <div>
                        <span class="font-bold {{ $letter->sender_id === auth()->id() ? 'text-[#783C46]' : 'text-[#342A25]' }}">
                            {{ $letter->sender_id === auth()->id() ? 'You' : $letter->sender->nickname }}
                        </span>
                        <span class="text-[#5A4A42] text-[11px] ml-2 font-mono">
                            {{ $letter->sent_at ? $letter->sent_at->format('M j, Y \a\t g:i A') : $letter->created_at->format('M j, Y') }}
                        </span>
                    </div>

                    <a href="{{ route('letters.show', $letter->id) }}" class="text-[#783C46] font-semibold text-xs hover:underline">
                        Open Full Sheet &rarr;
                    </a>
                </div>

                <h3 class="font-serif font-bold text-base text-[#342A25]">
                    {{ $letter->title }}
                </h3>

                <p class="font-serif text-xs sm:text-sm leading-relaxed text-[#342A25] whitespace-pre-line line-clamp-6">
                    {{ $letter->body }}
                </p>
            </div>
        @endforeach
    </div>
</div>
@endsection
