@extends('layouts.app', ['title' => __('419 — Expired Wax Seal')])

@section('content')
<div class="max-w-lg mx-auto px-4 py-20 text-center">
    <div class="paper-sheet p-10 rounded-2xl border border-[#E9DDC7] shadow-paper">
        <div class="wax-seal w-16 h-16 mx-auto mb-4 font-serif font-bold text-2xl shadow-seal bg-[#B79A61]">
            &#8987;
        </div>
        <div class="inline-block px-3 py-1 mb-4 rounded-full border border-[#B79A61]/50 bg-[#FAF6EE] text-[11px] font-serif uppercase tracking-widest text-[#783C46] font-semibold">
            {{ __('Error 419 — Session Expired') }}
        </div>
        <h1 class="font-serif text-3xl font-bold text-[#342A25] mb-3">
            {{ __('Your Wax Has Cooled') }}
        </h1>
        <p class="text-sm text-[#5A4A42] leading-relaxed mb-8">
            {{ __('Your security token has expired due to quiet inactivity. Please refresh your browser window to seal your correspondence again.') }}
        </p>

        <a href="javascript:location.reload()" class="inline-flex items-center justify-center px-6 py-3 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm transition-all">
            {{ __('Refresh & Resume') }}
        </a>
    </div>
</div>
@endsection

