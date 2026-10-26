@extends('layouts.app', ['title' => 'Incoming Postbox'])

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Header & Search Toolbar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-[#E9DDC7] pb-6 mb-8 gap-4">
        <div>
            <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">Incoming Mail</span>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#342A25] mt-1">
                Your Received Letters
            </h1>
        </div>

        <!-- Filter Tabs & Search -->
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="flex items-center space-x-1 bg-[#FAF6EE] p-1 rounded-md border border-[#E9DDC7] text-xs">
                <a href="{{ route('mailbox.index', ['filter' => 'all']) }}" class="px-3 py-1.5 rounded {{ $filter === 'all' ? 'bg-[#783C46] text-white font-bold' : 'text-[#5A4A42] hover:text-[#783C46]' }}">
                    All
                </a>
                <a href="{{ route('mailbox.index', ['filter' => 'unread']) }}" class="px-3 py-1.5 rounded {{ $filter === 'unread' ? 'bg-[#783C46] text-white font-bold' : 'text-[#5A4A42] hover:text-[#783C46]' }}">
                    Unopened
                </a>
                <a href="{{ route('mailbox.index', ['filter' => 'read']) }}" class="px-3 py-1.5 rounded {{ $filter === 'read' ? 'bg-[#783C46] text-white font-bold' : 'text-[#5A4A42] hover:text-[#783C46]' }}">
                    Read
                </a>
            </div>

            <form method="GET" action="{{ route('mailbox.index') }}" class="relative w-full sm:w-auto">
                <input type="text" name="q" value="{{ $search }}" placeholder="Search letters or sender..."
                    class="text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE] focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20 pl-8 pr-3 py-1.5 w-full sm:w-56">
                <svg class="w-4 h-4 text-[#B79A61] absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
        </div>
    </div>

    @if($letters->isEmpty())
        <div class="paper-sheet p-12 rounded-xl border border-[#E9DDC7] shadow-paper text-center max-w-lg mx-auto space-y-3">
            <div class="text-4xl text-[#B79A61]">📭</div>
            <h2 class="font-serif text-lg font-bold text-[#342A25]">Your postbox is quiet today</h2>
            <p class="text-xs text-[#5A4A42] leading-relaxed">
                @if($search || $filter !== 'all')
                    No correspondence matched your search filters.
                @else
                    No letters yet. Somewhere out there, your first one is waiting. Send a letter or browse your kindred spirit matches.
                @endif
            </p>
            <div class="pt-2 flex justify-center space-x-3">
                <a href="{{ route('discovery.matches') }}" class="px-4 py-2 text-xs font-semibold uppercase text-white bg-[#783C46] hover:bg-[#582A32] rounded">
                    Browse Matches
                </a>
                <a href="{{ route('letters.compose') }}" class="px-4 py-2 text-xs font-semibold uppercase text-[#342A25] bg-[#FAF6EE] border border-[#D6C7AE] hover:bg-[#E9DDC7] rounded">
                    Write a Letter
                </a>
            </div>
        </div>
    @else
        <!-- Envelope Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($letters as $letter)
                <a href="{{ route('letters.show', $letter->id) }}" class="bg-[#FAF6EE] p-6 rounded-xl border border-[#E9DDC7] hover:border-[#783C46] shadow-sm hover:shadow-md transition-all relative block group">
                    <!-- Top Postmark & Stamp -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="text-[10px] font-mono text-[#B79A61] block">{{ $letter->created_at->format('M d, Y') }}</span>
                            @if(!$letter->is_read)
                                <span class="inline-flex items-center text-[10px] text-[#783C46] font-bold mt-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#783C46] mr-1"></span>
                                    Unopened
                                </span>
                            @else
                                <span class="text-[10px] text-[#5A4A42] mt-1 block">Opened</span>
                            @endif
                        </div>

                        <!-- Mini Postage Stamp -->
                        <div class="postage-stamp w-10 h-12 p-0.5 flex flex-col items-center justify-between text-center bg-white shadow-xs">
                            <span class="text-[6px] text-[#B79A61]">POST</span>
                            <span class="text-xs text-[#783C46]">❦</span>
                        </div>
                    </div>

                    <!-- Subject & Sender -->
                    <div class="my-4">
                        <h3 class="font-serif font-bold text-base text-[#342A25] group-hover:text-[#783C46] transition-colors truncate">
                            {{ $letter->title }}
                        </h3>
                        <p class="text-xs text-[#5A4A42] mt-1 truncate">
                            From: <strong class="text-[#342A25]">{{ $letter->sender->nickname }}</strong>
                        </p>
                    </div>

                    <!-- Bottom Wax Seal Accent & Open Prompt -->
                    <div class="pt-4 border-t border-[#E9DDC7] flex items-center justify-between text-xs">
                        <div class="wax-seal w-7 h-7 text-[10px] font-bold">
                            {{ $letter->waxSeal?->symbol_svg ?: 'D' }}
                        </div>
                        <span class="text-[#783C46] font-semibold text-xs group-hover:translate-x-1 transition-transform">
                            Break Seal &rarr;
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $letters->links() }}
        </div>
    @endif
</div>
@endsection
