@extends('layouts.app', ['title' => $about['title'] ?? __('About the Dearly Post Office')])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="paper-sheet p-8 sm:p-12 rounded-xl border border-[#E9DDC7] shadow-paper">
        <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">{{ $about['badge'] }}</span>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#342A25] mt-2 mb-6">
            {{ $about['heading'] }}
        </h1>

        <div class="prose prose-stone text-sm leading-relaxed text-[#342A25] space-y-4">
            @foreach(preg_split('/(\r\n\r\n|\n\n)/', $about['content']) as $para)
                @if(trim($para) !== '')
                    <p class="leading-relaxed">
                        {!! nl2br(e(trim($para))) !!}
                    </p>
                @endif
            @endforeach

            <div class="border-t border-[#E9DDC7] pt-6 mt-8 flex items-center justify-between">
                <div>
                    <p class="font-handwriting text-3xl text-[#783C46]">{{ $about['postmaster_title'] }}</p>
                    <p class="text-xs text-[#B79A61]">{{ $about['postmaster_sub'] }}</p>
                </div>
                <div class="postal-mark px-4 py-1">
                    {{ $about['postal_mark'] }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
