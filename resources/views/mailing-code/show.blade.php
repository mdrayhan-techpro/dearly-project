@extends('layouts.app', ['title' => 'Your Private Mailing Code'])

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12" x-data="{ copied: false }">
    <div class="paper-sheet p-8 sm:p-10 rounded-xl border border-[#E9DDC7] shadow-paper space-y-8">
        <!-- Postal Header -->
        <div class="text-center border-b border-[#E9DDC7] pb-6">
            <div class="wax-seal w-12 h-12 mx-auto mb-3 font-serif font-bold text-lg shadow-seal">
                D
            </div>
            <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">Postal Registry</span>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#342A25] mt-1">
                Your Private Mailing Code
            </h1>
            <p class="text-xs text-[#5A4A42] mt-2 max-w-md mx-auto">
                This code acts as your unique post office address. Prospective correspondents use this code to address letters to you without ever seeing your email address.
            </p>
        </div>

        <!-- The Code Presentation Card -->
        <div class="bg-[#FAF6EE] p-6 rounded-xl border-2 border-dashed border-[#B79A61] text-center space-y-4 shadow-sm">
            <span class="text-xs uppercase tracking-widest text-[#5A4A42] font-semibold block">Official Postal Address</span>
            <div class="font-mono text-2xl sm:text-3xl font-bold text-[#783C46] tracking-widest select-all">
                {{ $mailingCode->code }}
            </div>
            <div class="flex justify-center space-x-3 pt-2">
                <button type="button" @click="navigator.clipboard.writeText('{{ $mailingCode->code }}'); copied = true; setTimeout(() => copied = false, 2500)"
                    class="px-5 py-2 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm transition-all">
                    <span x-show="!copied">Copy Address</span>
                    <span x-show="copied" x-cloak class="text-white">Copied to Clipboard! ✓</span>
                </button>
            </div>
        </div>

        <!-- Permissions Policy Settings -->
        <div class="border-t border-[#E9DDC7] pt-6 space-y-4">
            <h3 class="font-serif font-bold text-base text-[#342A25]">Postal Acceptance Policy</h3>
            <p class="text-xs text-[#5A4A42] leading-relaxed">
                Control who is permitted to send letters to this mailing address.
            </p>

            <form method="POST" action="{{ route('mailing-code.policy') }}" class="space-y-3">
                @csrf
                <label class="flex items-start space-x-3 p-3 rounded-lg border border-[#E9DDC7] bg-[#FAF6EE] cursor-pointer hover:bg-[#F2ECE1] transition-colors">
                    <input type="radio" name="permissions_policy" value="anyone" {{ $mailingCode->permissions_policy === 'anyone' ? 'checked' : '' }} class="mt-0.5 text-[#783C46] focus:ring-[#783C46]/20">
                    <div class="text-xs">
                        <strong class="text-[#342A25] block">Open Postbox (Recommended)</strong>
                        <span class="text-[#5A4A42]">Any eligible member with your mailing code can send you an introductory letter.</span>
                    </div>
                </label>

                <label class="flex items-start space-x-3 p-3 rounded-lg border border-[#E9DDC7] bg-[#FAF6EE] cursor-pointer hover:bg-[#F2ECE1] transition-colors">
                    <input type="radio" name="permissions_policy" value="accepted_connections" {{ $mailingCode->permissions_policy === 'accepted_connections' ? 'checked' : '' }} class="mt-0.5 text-[#783C46] focus:ring-[#783C46]/20">
                    <div class="text-xs">
                        <strong class="text-[#342A25] block">Accepted Pen Friends Only</strong>
                        <span class="text-[#5A4A42]">Only correspondents you have mutually accepted as connections can dispatch letters to this address.</span>
                    </div>
                </label>

                <div class="pt-2">
                    <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#783C46] hover:bg-[#582A32] rounded">
                        Save Policy
                    </button>
                </div>
            </form>
        </div>

        <!-- Code Rotation (Retire / Reissue) -->
        <div class="border-t border-[#E9DDC7] pt-6 space-y-3">
            <h3 class="font-serif font-bold text-base text-[#342A25]">Retire & Rotate Address</h3>
            <p class="text-xs text-[#5A4A42] leading-relaxed">
                If you ever wish to invalidate your current mailing code, you can rotate it immediately. Existing letters and ongoing correspondence threads will be safely preserved.
            </p>

            <form method="POST" action="{{ route('mailing-code.rotate') }}" onsubmit="return confirm('Retire your current mailing code and generate a brand-new one?');">
                @csrf
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-[#783C46] bg-[#FAF6EE] hover:bg-[#E9DDC7] border border-[#D6C7AE] rounded transition-colors">
                    Generate New Mailing Code &rarr;
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
