@extends('layouts.app', ['title' => __('Maintenance — Restoring the Post Office')])

@section('content')
<div class="max-w-lg mx-auto px-4 py-20 text-center">
    <div class="paper-sheet p-10 rounded-2xl border border-[#E9DDC7] shadow-paper">
        <div class="wax-seal w-16 h-16 mx-auto mb-4 font-serif font-bold text-2xl shadow-seal bg-[#405B4B]">
            &#9881;
        </div>
        <div class="inline-block px-3 py-1 mb-4 rounded-full border border-[#B79A61]/50 bg-[#FAF6EE] text-[11px] font-serif uppercase tracking-widest text-[#405B4B] font-semibold">
            {{ __('Quiet Hours — System Maintenance') }}
        </div>
        <h1 class="font-serif text-3xl font-bold text-[#342A25] mb-3">
            {{ __('Restoring the Archives') }}
        </h1>
        <p class="text-sm text-[#5A4A42] leading-relaxed mb-6">
            {{ __('The Post Office is undergoing routine preservation, ink resupply, and ledger updates. Our letter carriers will resume deliveries very shortly.') }}
        </p>

        <p class="text-xs text-[#8A7972] font-mono">
            {{ __('Expected resumption: Moments away') }}
        </p>
    </div>
</div>
@endsection

