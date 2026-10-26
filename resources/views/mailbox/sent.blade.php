@extends('layouts.app', ['title' => 'Dispatched Letters'])

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-[#E9DDC7] pb-6 mb-8 gap-4">
        <div>
            <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">Postal Dispatches</span>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#342A25] mt-1">
                Letters Sent
            </h1>
        </div>

        <a href="{{ route('letters.compose') }}" class="px-5 py-2 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm transition-all">
            Write New Letter &rarr;
        </a>
    </div>

    @if($letters->isEmpty())
        <div class="paper-sheet p-12 rounded-xl border border-[#E9DDC7] shadow-paper text-center max-w-lg mx-auto space-y-3">
            <div class="text-4xl text-[#B79A61]">✉</div>
            <h2 class="font-serif text-lg font-bold text-[#342A25]">No dispatched letters yet</h2>
            <p class="text-xs text-[#5A4A42] leading-relaxed">
                You have not yet sent any letters through the postal network. A single heartfelt letter can begin a lifelong correspondence.
            </p>
            <div class="pt-2">
                <a href="{{ route('letters.compose') }}" class="px-5 py-2.5 text-xs font-semibold uppercase text-white bg-[#783C46] hover:bg-[#582A32] rounded">
                    Compose Your First Letter
                </a>
            </div>
        </div>
    @else
        <div class="paper-sheet rounded-xl border border-[#E9DDC7] shadow-paper overflow-hidden">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FAF6EE] border-b border-[#E9DDC7] text-[#5A4A42] uppercase text-[10px] tracking-wider font-semibold">
                    <tr>
                        <th class="py-3 px-4">Recipient</th>
                        <th class="py-3 px-4">Subject</th>
                        <th class="py-3 px-4">Sent Date</th>
                        <th class="py-3 px-4">Delivery & Read Receipt</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E9DDC7] text-[#342A25]">
                    @foreach($letters as $letter)
                        <tr class="hover:bg-[#FAF6EE]/80 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-[#342A25] block">{{ $letter->recipient?->nickname ?? 'Friend of Dearly' }}</span>
                                <span class="font-mono text-[10px] text-[#B79A61]">{{ $letter->recipient?->mailingCode?->code ?? 'N/A' }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-serif font-semibold text-sm">
                                <a href="{{ route('letters.show', $letter->id) }}" class="hover:text-[#783C46] transition-colors">
                                    {{ $letter->title }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-[#5A4A42]">
                                {{ $letter->sent_at ? $letter->sent_at->format('M d, Y H:i') : $letter->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($letter->is_read)
                                    <span class="inline-flex items-center text-[#405B4B] font-semibold text-[11px]">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Read {{ $letter->read_at ? $letter->read_at->diffForHumans() : 'by recipient' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-[#B79A61] font-medium text-[11px]">
                                        <span class="w-2 h-2 rounded-full bg-[#B79A61] mr-1.5 animate-pulse"></span>
                                        Delivered (Awaiting Opening)
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('letters.show', $letter->id) }}" class="text-[#783C46] hover:underline font-semibold text-xs">
                                    View Letter &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $letters->links() }}
        </div>
    @endif
</div>
@endsection
