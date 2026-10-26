@extends('layouts.app', ['title' => $letter->title])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ reportModal: false }">
    <!-- Top Action Breadcrumbs -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-[#E9DDC7] pb-4 mb-8 gap-4">
        <div class="flex items-center space-x-3 text-xs">
            <a href="{{ route('mailbox.index') }}" class="text-[#5A4A42] hover:text-[#783C46] flex items-center space-x-1">
                <span>&larr;</span> <span>Back to Mailbox</span>
            </a>
            @if($letter->thread_id)
                <span class="text-[#D6C7AE]">/</span>
                <a href="{{ route('letters.thread', $letter->thread_id) }}" class="text-[#783C46] hover:underline">
                    View Complete Correspondence Thread
                </a>
            @endif
        </div>

        <div class="flex items-center space-x-3 text-xs">
            @if($letter->recipient_id === $user->id)
                <a href="{{ route('letters.compose', ['reply_to' => $letter->id]) }}" class="px-5 py-2 font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm transition-all transform hover:-translate-y-0.5">
                    Write a Reply &rarr;
                </a>
            @endif

            <!-- Archive Action -->
            <form method="POST" action="{{ route('letters.archive', $letter->id) }}">
                @csrf
                <button type="submit" class="px-3 py-2 text-[#5A4A42] hover:text-[#783C46] bg-[#FAF6EE] border border-[#D6C7AE] rounded transition-colors" title="Archive this letter">
                    Archive
                </button>
            </form>

            <!-- Delete Copy -->
            <form method="POST" action="{{ route('letters.destroy', $letter->id) }}" onsubmit="return confirm('Remove your local copy of this letter?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-2 text-[#783C46] hover:bg-[#783C46]/10 border border-[#783C46]/30 rounded transition-colors" title="Delete local copy">
                    Delete
                </button>
            </form>

            @if($letter->sender_id !== $user->id)
                <!-- Report button -->
                <button type="button" @click="reportModal = true" class="px-3 py-2 text-[#8A7972] hover:text-red-700 text-xs">
                    Report
                </button>
            @endif
        </div>
    </div>

    <!-- The Letter Paper Sheet -->
    @php
        $themeBg = $letter->stationeryTheme?->background_css ?? '#FAF6EE';
        $themeText = $letter->stationeryTheme?->text_color ?? '#342A25';
    @endphp

    <article class="paper-sheet p-8 sm:p-14 rounded-2xl shadow-envelope relative border border-[#E9DDC7]" style="background-color: {{ $themeBg }}; color: {{ $themeText }};">
        <!-- Top Right Postage Stamp with Cancellation Mark -->
        <div class="absolute top-8 right-8 z-20">
            <div class="postage-stamp w-16 h-20 p-1 flex flex-col items-center justify-between text-center bg-white shadow-sm">
                <span class="text-[7px] font-mono text-[#B79A61] uppercase tracking-wider">Airmail</span>
                <span class="text-xl text-[#783C46]">❦</span>
                <span class="text-[8px] font-bold text-[#342A25] truncate w-full">{{ $letter->postageStamp?->name ?? 'First Letter' }}</span>
            </div>
            <!-- Postal Cancellation Mark -->
            <div class="postal-mark absolute -bottom-3 -left-4 px-2 py-0.5 text-[8px] font-mono">
                {{ $letter->sent_at ? $letter->sent_at->format('M d, Y') : $letter->created_at->format('M d, Y') }}
            </div>
        </div>

        <!-- Header Metadata -->
        <div class="mb-8 pr-20 border-b border-[#E9DDC7]/60 pb-6">
            <span class="text-[10px] font-mono uppercase tracking-widest text-[#B79A61] block mb-1">
                Dispatched by {{ $letter->sender->nickname }}
            </span>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold tracking-tight mb-2">
                {{ $letter->title }}
            </h1>
            <p class="text-xs opacity-75">
                Sent {{ $letter->sent_at ? $letter->sent_at->format('F j, Y \a\t g:i A') : $letter->created_at->format('F j, Y') }}
                @if($letter->is_read && $letter->read_at)
                    • <span class="text-[#405B4B] font-semibold">Opened {{ $letter->read_at->format('M j, Y') }}</span>
                @endif
            </p>
        </div>

        <!-- Greeting -->
        @if($letter->greeting)
            <div class="font-handwriting text-2xl sm:text-3xl mb-6 text-[#783C46]">
                {{ $letter->greeting }}
            </div>
        @endif

        <!-- Letter Body -->
        <div class="font-serif text-sm sm:text-base leading-loose whitespace-pre-line space-y-4 my-8">
            {{ $letter->body }}
        </div>

        <!-- Signoff -->
        @if($letter->signoff)
            <div class="font-handwriting text-2xl sm:text-3xl mt-10 mb-4 text-[#783C46]">
                {{ $letter->signoff }}
            </div>
        @endif

        <div class="pt-8 border-t border-[#E9DDC7]/60 flex items-center justify-between text-xs opacity-75">
            <div>
                <p>Correspondent: <strong>{{ $letter->sender->nickname }}</strong></p>
                <p class="text-[10px] font-mono text-[#B79A61]">{{ $letter->sender->mailingCode?->code }}</p>
            </div>

            <!-- Wax Seal Impression -->
            <div class="wax-seal w-12 h-12 text-base font-serif font-bold shadow-seal"
                style="background: radial-gradient(circle at 35% 35%, {{ $letter->waxSeal?->color_hex ?? '#783C46' }} 0%, #342A25 120%);">
                {{ $letter->waxSeal?->symbol_svg ?: 'D' }}
            </div>
        </div>
    </article>

    <!-- Safety Report Modal -->
    <div x-show="reportModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4">
        <div class="paper-sheet p-6 sm:p-8 rounded-xl border border-[#E9DDC7] max-w-md w-full shadow-2xl relative" @click.away="reportModal = false">
            <h3 class="font-serif text-lg font-bold text-[#342A25] mb-2">Report This Letter</h3>
            <p class="text-xs text-[#5A4A42] mb-4">
                If this letter violates community guidelines (harassment, spam, or inappropriate content), report it to the postmaster.
            </p>

            <form method="POST" action="{{ route('safety.report') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="reported_user_id" value="{{ $letter->sender_id }}">
                <input type="hidden" name="letter_id" value="{{ $letter->id }}">

                <div>
                    <label class="block text-xs font-semibold text-[#342A25] mb-1">Reason for Report</label>
                    <select name="reason" required class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                        <option value="harassment">Harassment or Unwanted Contact</option>
                        <option value="spam">Spam or Unsolicited Commercial Message</option>
                        <option value="inappropriate_content">Inappropriate Content</option>
                        <option value="underage">Suspected Underage Account</option>
                        <option value="other">Other Violation</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#342A25] mb-1">Details (Optional)</label>
                    <textarea name="details" rows="3" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]" placeholder="Describe the concern..."></textarea>
                </div>

                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" @click="reportModal = false" class="px-4 py-2 text-xs font-semibold text-[#5A4A42]">Cancel</button>
                    <button type="submit" class="px-5 py-2 text-xs font-semibold uppercase tracking-wider text-white bg-red-700 hover:bg-red-800 rounded">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
