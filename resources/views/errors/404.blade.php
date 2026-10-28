@extends('layouts.app', ['title' => __('404 — A Letter Lost to Time')])

@section('content')
<div class="max-w-lg mx-auto px-4 py-20 text-center">
    <div class="paper-sheet p-10 rounded-2xl border border-[#E9DDC7] shadow-paper">
        <div class="wax-seal w-16 h-16 mx-auto mb-4 font-serif font-bold text-2xl shadow-seal opacity-90">
            ?
        </div>
        <div class="inline-block px-3 py-1 mb-4 rounded-full border border-[#B79A61]/50 bg-[#FAF6EE] text-[11px] font-serif uppercase tracking-widest text-[#783C46] font-semibold">
            {{ __('Error 404 — Missing Dispatch') }}
        </div>
        <h1 class="font-serif text-3xl font-bold text-[#342A25] mb-3">
            {{ __('A Letter Lost to Time') }}
        </h1>
        <p class="text-sm text-[#5A4A42] leading-relaxed mb-8">
            {{ __('The destination or correspondence you are seeking has vanished from our ledger, or perhaps was never posted. Even the most diligent carriers sometimes find an empty shelf.') }}
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
            <a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm transition-all">
                {{ __('Return to Post Office') }}
            </a>
            <a href="javascript:history.back()" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 text-xs font-semibold uppercase tracking-wider text-[#342A25] bg-[#FAF6EE] hover:bg-[#E9DDC7] border border-[#B79A61]/60 rounded transition-all">
                {{ __('Go Back') }}
            </a>
        </div>
    </div>
</div>
@endsection

