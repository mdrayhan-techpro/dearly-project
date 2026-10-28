@extends('layouts.app', ['title' => __('403 — Sealed Parchment')])

@section('content')
<div class="max-w-lg mx-auto px-4 py-20 text-center">
    <div class="paper-sheet p-10 rounded-2xl border border-[#E9DDC7] shadow-paper">
        <div class="wax-seal w-16 h-16 mx-auto mb-4 font-serif font-bold text-2xl shadow-seal bg-[#582A32]">
            &#128274;
        </div>
        <div class="inline-block px-3 py-1 mb-4 rounded-full border border-[#B79A61]/50 bg-[#FAF6EE] text-[11px] font-serif uppercase tracking-widest text-[#783C46] font-semibold">
            {{ __('Error 403 — Access Restricted') }}
        </div>
        <h1 class="font-serif text-3xl font-bold text-[#342A25] mb-3">
            {{ __('This Seal Remains Unbroken') }}
        </h1>
        <p class="text-sm text-[#5A4A42] leading-relaxed mb-8">
            {{ __('You do not possess the authority or permission to inspect this private dispatch. Dearly strictly guards the privacy of every member.') }}
        </p>

        <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-3 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm transition-all">
            {{ __('Return to Safety') }}
        </a>
    </div>
</div>
@endsection

