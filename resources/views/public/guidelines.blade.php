@extends('layouts.app', ['title' => 'Community Safety & Guidelines'])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="paper-sheet p-8 sm:p-12 rounded-xl border border-[#E9DDC7] shadow-paper">
        <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">Postal Safety</span>
        <h1 class="font-serif text-3xl font-bold text-[#342A25] mt-2 mb-6">
            Community Guidelines & Safeguards
        </h1>

        <div class="prose prose-stone text-sm leading-relaxed text-[#342A25] whitespace-pre-line">
            {{ $guidelines }}
        </div>
    </div>
</div>
@endsection
