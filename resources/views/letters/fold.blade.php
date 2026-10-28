@extends('layouts.app', ['title' => __('Fold & Seal Your Letter')])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
    x-data="{
        folded: false,
        selectedStamp: {{ $stamps->first()->id }},
        selectedSeal: {{ $seals->first()->id }},
        recipientCode: '{{ old('recipient_code', $letter->recipient?->mailingCode?->code ?? '') }}',
        reducedMotion: false,
        sealing: false,

        foldLetter() {
            this.folded = true;
        },

        unfoldLetter() {
            this.folded = false;
        }
    }">

    <!-- Top Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-[#E9DDC7] pb-4 mb-8 gap-3">
        <div>
            <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">{{ __('Packaging Studio') }}</span>
            <h1 class="font-serif text-2xl font-bold text-[#342A25]">
                {{ __('Fold, Stamp & Seal Your Letter') }}
            </h1>
        </div>

        <div class="flex flex-wrap items-center gap-3 text-xs">
            <a href="{{ route('drafts.edit', $letter->id) }}" class="text-[#5A4A42] hover:text-[#783C46] flex items-center space-x-1">
                <span>&larr;</span> <span>{{ __('Back to Writing') }}</span>
            </a>
            <label class="flex items-center space-x-1.5 text-[11px] text-[#5A4A42] cursor-pointer">
                <input type="checkbox" x-model="reducedMotion" class="rounded text-[#783C46] focus:ring-0">
                <span>{{ __('Simple Motion') }}</span>
            </label>
        </div>
    </div>

    <form method="POST" action="{{ route('letters.send', $letter->id) }}">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left: Envelope Visual Sequence Area -->
            <div class="lg:col-span-7 flex flex-col items-center">
                <!-- Folding Container -->
                <div class="w-full max-w-md relative min-h-[380px] flex items-center justify-center p-4">
                    <!-- State A: Unfolded Letter Preview -->
                    <div x-show="!folded" x-transition.opacity.duration.300ms class="w-full paper-sheet p-6 rounded-lg shadow-paper border border-[#E9DDC7] space-y-3">
                        <div class="border-b border-[#E9DDC7] pb-2 flex justify-between items-center text-xs text-[#B79A61]">
                            <span>{{ __('Unfolded Letter') }}</span>
                            <span class="font-bold text-[#342A25]">{{ $letter->title }}</span>
                        </div>
                        <p class="font-handwriting text-xl text-[#783C46]">{{ $letter->greeting ?: __('Dear Friend,') }}</p>
                        <p class="font-serif text-xs leading-relaxed text-[#342A25] line-clamp-6">
                            {{ $letter->body }}
                        </p>
                        <p class="font-handwriting text-xl text-[#783C46] text-right">{{ $letter->signoff ?: __('With warmth,') }}</p>

                        <div class="pt-4 text-center">
                            <button type="button" @click="foldLetter()" class="px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm transition-all transform hover:-translate-y-0.5">
                                {{ __('Fold Into Envelope &darr;') }}
                            </button>
                        </div>
                    </div>

                    <!-- State B: Folded Letter Inside Envelope with Postage Stamp & Wax Seal -->
                    <div x-show="folded" x-cloak
                        class="w-full bg-[#EFE6D5] rounded-xl shadow-envelope border border-[#D6C7AE] p-6 relative flex flex-col justify-between min-h-[260px] transition-all"
                        :class="reducedMotion ? '' : 'transform hover:scale-[1.01]'">

                        <!-- Envelope Top Flap Simulation -->
                        <div class="absolute inset-x-0 top-0 h-1/2 bg-[#E7DBC7] rounded-t-xl border-b border-[#D6C7AE]/70 envelope-flap flex items-end justify-center pb-2">
                        </div>

                        <!-- Top Right: Affixed Stamp -->
                        <div class="absolute top-5 right-5 z-20">
                            @foreach($stamps as $stamp)
                                <div x-show="selectedStamp == {{ $stamp->id }}" class="postage-stamp w-14 h-16 p-1 flex flex-col items-center justify-between text-center bg-white shadow-sm">
                                    <span class="text-[7px] text-[#B79A61] uppercase tracking-wider">{{ __('Airmail') }}</span>
                                    <span class="text-base text-[#783C46]">❦</span>
                                    <span class="text-[7px] font-bold text-[#342A25] truncate max-w-[50px]">{{ $stamp->name }}</span>
                                </div>
                            @endforeach
                            <div class="postal-mark absolute -bottom-2 -left-3 px-2 py-0.5 text-[7px]">
                                {{ __('Dispatched') }}
                            </div>
                        </div>

                        <!-- Center: Recipient Address Placement -->
                        <div class="my-auto z-10 text-center space-y-1">
                            <p class="text-[10px] uppercase tracking-widest text-[#8A7972] font-semibold">{{ __('Deliver To The Postbox Of:') }}</p>
                            <p class="font-serif text-lg font-bold text-[#342A25]" x-text="recipientCode ? recipientCode : '{{ __('Enter Mailing Code Below') }}'"></p>
                            <p class="text-[10px] text-[#B79A61]">{{ __('The Dearly Postal Network') }}</p>
                        </div>

                        <!-- Center Bottom: Embossed Digital Wax Seal -->
                        <div class="absolute -bottom-5 inset-x-0 flex justify-center z-20">
                            @foreach($seals as $seal)
                                <div x-show="selectedSeal == {{ $seal->id }}"
                                    class="wax-seal w-12 h-12 text-base font-serif font-bold shadow-seal"
                                    style="background: radial-gradient(circle at 35% 35%, {{ $seal->color_hex }} 0%, #342A25 120%);">
                                    {{ $seal->symbol_svg ?: 'D' }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div x-show="folded" x-cloak class="mt-4 text-center">
                    <button type="button" @click="unfoldLetter()" class="text-xs text-[#5A4A42] hover:text-[#783C46] underline">
                        &larr; {{ __('Unfold & Review Letter') }}
                    </button>
                </div>
            </div>

            <!-- Right: Controls & Final Dispatch -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Address Card -->
                <div class="paper-sheet p-6 rounded-xl border border-[#E9DDC7] shadow-paper space-y-4">
                    <h3 class="font-serif text-base font-bold text-[#342A25]">{{ __('Recipient Postal Address') }}</h3>

                    <div>
                        <label for="recipient_code" class="block text-xs font-semibold text-[#342A25] mb-1">
                            {{ __('Mailing Code') }} <span class="text-[#783C46]">*</span>
                        </label>
                        <input id="recipient_code" type="text" name="recipient_code" x-model="recipientCode" required
                            placeholder="DEARLY-XXXX-YYYY"
                            class="w-full text-xs font-mono font-bold tracking-wider rounded-md border-[#D6C7AE] bg-[#FAF6EE] focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                        <p class="text-[10px] text-[#5A4A42] mt-1">
                            {{ __('Format: DEARLY-XXXX-YYYY. Must belong to an eligible, active recipient.') }}
                        </p>
                    </div>
                </div>

                <!-- Stamp Picker -->
                <div class="paper-sheet p-6 rounded-xl border border-[#E9DDC7] shadow-paper space-y-3">
                    <h3 class="font-serif text-sm font-bold text-[#342A25]">{{ __('Choose Postage Stamp') }}</h3>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($stamps as $stamp)
                            <label class="postage-stamp p-1.5 flex flex-col items-center justify-between text-center cursor-pointer rounded border transition-all"
                                :class="selectedStamp == {{ $stamp->id }} ? 'ring-2 ring-[#783C46] bg-[#FAF6EE]' : 'hover:bg-white opacity-80'">
                                <input type="radio" name="postage_stamp_id" value="{{ $stamp->id }}" x-model="selectedStamp" class="hidden">
                                <span class="text-xs text-[#783C46]">❦</span>
                                <span class="text-[8px] font-bold text-[#342A25] truncate w-full">{{ $stamp->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Wax Seal Picker -->
                <div class="paper-sheet p-6 rounded-xl border border-[#E9DDC7] shadow-paper space-y-3">
                    <h3 class="font-serif text-sm font-bold text-[#342A25]">{{ __('Choose Digital Wax Seal') }}</h3>
                    <div class="flex items-center space-x-3">
                        @foreach($seals as $seal)
                            <label class="cursor-pointer transition-transform hover:scale-105" title="{{ $seal->name }}">
                                <input type="radio" name="wax_seal_id" value="{{ $seal->id }}" x-model="selectedSeal" class="hidden">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-serif text-xs font-bold text-white shadow-sm border"
                                    :class="selectedSeal == {{ $seal->id }} ? 'ring-2 ring-[#342A25] scale-110' : 'opacity-85'"
                                    style="background-color: {{ $seal->color_hex }};">
                                    {{ $seal->symbol_svg ?: 'D' }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Send Confirmation -->
                <div class="pt-2">
                    <button type="submit"
                        :disabled="!recipientCode.trim()"
                        class="w-full py-3.5 px-6 text-xs font-semibold uppercase tracking-wider text-white rounded-md shadow-md transition-all flex items-center justify-center space-x-2"
                        :class="recipientCode.trim() ? 'bg-[#783C46] hover:bg-[#582A32] transform hover:-translate-y-0.5 cursor-pointer' : 'bg-gray-400 opacity-60 cursor-not-allowed'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span>{{ __('Seal & Dispatch Letter') }}</span>
                    </button>
                    <p class="text-[10px] text-center text-[#5A4A42] mt-2">
                        {{ __('Counts toward your daily sending cap (5 letters/day). Read receipt will notify you when opened.') }}
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
