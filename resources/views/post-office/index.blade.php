@extends('layouts.app', ['title' => 'My Post Office Desk'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Top Desk Greeting & Mailing Code Banner -->
    <div class="paper-sheet p-6 sm:p-8 rounded-xl border border-[#E9DDC7] shadow-paper mb-10 relative overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
            <div class="md:col-span-8 space-y-2">
                <span class="text-xs font-serif uppercase tracking-widest text-[#B79A61] font-semibold">Post Office Desk</span>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#342A25]">
                    Welcome back, {{ $user->nickname }}.
                </h1>
                <p class="text-xs sm:text-sm text-[#5A4A42] leading-relaxed max-w-xl">
                    Your quiet sanctuary for thoughtful correspondence. Below is your postal desk with incoming envelopes, drafts, and discovered kindred spirits.
                </p>
            </div>

            <!-- Postal Address Badge -->
            <div class="md:col-span-4 bg-[#FAF6EE] p-5 rounded-lg border-2 border-dashed border-[#B79A61] flex flex-col justify-between" x-data="{ copied: false }">
                <div>
                    <span class="text-[10px] uppercase tracking-wider text-[#5A4A42] font-semibold block">Your Private Mailing Code</span>
                    <span class="font-mono text-base font-bold text-[#783C46] tracking-wider block mt-1 select-all">
                        {{ $mailingCode->code }}
                    </span>
                    <p class="text-[10px] text-[#5A4A42] mt-1">Share this address with prospective correspondents.</p>
                </div>
                <div class="mt-3 flex items-center justify-between">
                    <button type="button" @click="navigator.clipboard.writeText('{{ $mailingCode->code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="px-3 py-1.5 text-[11px] font-semibold text-[#783C46] bg-[#F6F0E5] hover:bg-[#E9DDC7] rounded border border-[#D6C7AE] transition-colors">
                        <span x-show="!copied">Copy Code</span>
                        <span x-show="copied" x-cloak class="text-[#405B4B]">Copied! ✓</span>
                    </button>
                    <a href="{{ route('mailing-code.show') }}" class="text-[11px] text-[#B79A61] hover:underline">Settings</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stat Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <a href="{{ route('mailbox.index') }}" class="p-4 bg-[#FAF6EE] rounded-lg border border-[#E9DDC7] hover:border-[#783C46] transition-all group shadow-sm">
            <span class="block text-2xl font-serif font-bold text-[#783C46] group-hover:scale-105 transition-transform">
                {{ $unreadCount }}
            </span>
            <span class="text-xs text-[#5A4A42] font-medium">Unopened Letters</span>
        </a>

        <a href="{{ route('discovery.matches') }}" class="p-4 bg-[#FAF6EE] rounded-lg border border-[#E9DDC7] hover:border-[#783C46] transition-all group shadow-sm">
            <span class="block text-2xl font-serif font-bold text-[#405B4B] group-hover:scale-105 transition-transform">
                {{ $matchesCount }}
            </span>
            <span class="text-xs text-[#5A4A42] font-medium">Pen-Pal Matches (90%+)</span>
        </a>

        <a href="{{ route('mailbox.drafts') }}" class="p-4 bg-[#FAF6EE] rounded-lg border border-[#E9DDC7] hover:border-[#783C46] transition-all group shadow-sm">
            <span class="block text-2xl font-serif font-bold text-[#B79A61] group-hover:scale-105 transition-transform">
                {{ $draftsCount }}
            </span>
            <span class="text-xs text-[#5A4A42] font-medium">Drafts in Progress</span>
        </a>

        <a href="{{ route('mailbox.sent') }}" class="p-4 bg-[#FAF6EE] rounded-lg border border-[#E9DDC7] hover:border-[#783C46] transition-all group shadow-sm">
            <span class="block text-2xl font-serif font-bold text-[#342A25] group-hover:scale-105 transition-transform">
                {{ $sentCount }}
            </span>
            <span class="text-xs text-[#5A4A42] font-medium">Letters Dispatched</span>
        </a>
    </div>

    <!-- Main Workspace Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Recent Incoming Mail -->
        <div class="lg:col-span-8 space-y-6">
            <div class="flex items-center justify-between border-b border-[#E9DDC7] pb-3">
                <div class="flex items-center space-x-2">
                    <span class="font-serif text-lg font-bold text-[#342A25]">Incoming Mailbox</span>
                    @if($unreadCount > 0)
                        <span class="bg-[#783C46] text-white text-[10px] px-2 py-0.5 rounded-full font-bold">
                            {{ $unreadCount }} new
                        </span>
                    @endif
                </div>
                <a href="{{ route('mailbox.index') }}" class="text-xs text-[#783C46] hover:underline font-semibold">
                    View All Mail &rarr;
                </a>
            </div>

            @if($recentInbox->isEmpty())
                <div class="paper-sheet p-8 rounded-lg border border-[#E9DDC7] text-center space-y-3">
                    <div class="text-3xl text-[#B79A61]">📭</div>
                    <h3 class="font-serif text-base font-bold text-[#342A25]">No letters yet</h3>
                    <p class="text-xs text-[#5A4A42] max-w-sm mx-auto leading-relaxed">
                        Somewhere out there, your first letter is waiting. Share your mailing code or discover a kindred spirit to start correspondence.
                    </p>
                    <div class="pt-2 flex justify-center space-x-3">
                        <a href="{{ route('discovery.choose-length') }}" class="px-4 py-2 text-xs font-semibold uppercase text-white bg-[#783C46] hover:bg-[#582A32] rounded">
                            Find a Pen Friend
                        </a>
                        <a href="{{ route('letters.compose') }}" class="px-4 py-2 text-xs font-semibold uppercase text-[#342A25] bg-[#FAF6EE] border border-[#D6C7AE] hover:bg-[#E9DDC7] rounded">
                            Write a Letter
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($recentInbox as $letter)
                        <a href="{{ route('letters.show', $letter->id) }}" class="p-5 rounded-lg border border-[#E9DDC7] bg-[#FAF6EE] hover:bg-[#F2ECE1] transition-all shadow-sm hover:shadow-md relative block group">
                            <!-- Visual Stamp -->
                            <div class="absolute top-4 right-4">
                                <div class="postage-stamp w-9 h-11 p-0.5 flex flex-col items-center justify-between text-center bg-white">
                                    <span class="text-[6px] text-[#B79A61]">POST</span>
                                    <span class="text-xs text-[#783C46]">❦</span>
                                </div>
                            </div>

                            <div class="pr-12">
                                <span class="text-[10px] font-mono text-[#B79A61]">{{ $letter->created_at->format('M d, Y') }}</span>
                                <h4 class="font-serif font-bold text-sm text-[#342A25] group-hover:text-[#783C46] transition-colors truncate">
                                    {{ $letter->title }}
                                </h4>
                                <p class="text-xs text-[#5A4A42] mt-1">From: <strong class="text-[#342A25]">{{ $letter->sender->nickname }}</strong></p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-[#E9DDC7] flex items-center justify-between text-[11px]">
                                @if(!$letter->is_read)
                                    <span class="inline-flex items-center text-[#783C46] font-bold">
                                        <span class="w-2 h-2 rounded-full bg-[#783C46] mr-1.5"></span>
                                        Unopened Envelope
                                    </span>
                                @else
                                    <span class="text-[#5A4A42]">Read</span>
                                @endif
                                <span class="text-[#B79A61] group-hover:translate-x-0.5 transition-transform">Break Seal &rarr;</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Actions & Drafts & Notifications -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Quick Actions -->
            <div class="paper-sheet p-5 rounded-lg border border-[#E9DDC7] shadow-paper space-y-3">
                <h3 class="font-serif text-sm font-bold text-[#342A25]">Postal Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('letters.compose') }}" class="w-full flex items-center justify-between p-2.5 rounded bg-[#783C46] hover:bg-[#582A32] text-white text-xs font-semibold transition-colors">
                        <span>Write New Letter</span>
                        <span>✎</span>
                    </a>
                    <a href="{{ route('discovery.choose-length') }}" class="w-full flex items-center justify-between p-2.5 rounded bg-[#FAF6EE] hover:bg-[#E9DDC7] text-[#342A25] border border-[#D6C7AE] text-xs font-semibold transition-colors">
                        <span>Take Discovery Questionnaire</span>
                        <span>★</span>
                    </a>
                    <a href="{{ route('discovery.matches') }}" class="w-full flex items-center justify-between p-2.5 rounded bg-[#FAF6EE] hover:bg-[#E9DDC7] text-[#342A25] border border-[#D6C7AE] text-xs font-semibold transition-colors">
                        <span>Browse 90%+ Matches</span>
                        <span>♥</span>
                    </a>
                </div>
            </div>

            <!-- In-Progress Drafts -->
            <div class="paper-sheet p-5 rounded-lg border border-[#E9DDC7] shadow-paper space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-serif text-sm font-bold text-[#342A25]">Unfinished Drafts</h3>
                    <a href="{{ route('mailbox.drafts') }}" class="text-[11px] text-[#783C46] hover:underline">View All</a>
                </div>

                @if($recentDrafts->isEmpty())
                    <p class="text-xs text-[#5A4A42] italic py-2">No drafts saved at your desk.</p>
                @else
                    <ul class="space-y-2 text-xs">
                        @foreach($recentDrafts as $draft)
                            <li class="p-2.5 bg-[#FAF6EE] rounded border border-[#E9DDC7] flex justify-between items-center hover:bg-[#F2ECE1] transition-colors">
                                <div class="truncate pr-2">
                                    <a href="{{ route('drafts.edit', $draft->id) }}" class="font-semibold text-[#342A25] hover:text-[#783C46] truncate block">
                                        {{ $draft->title }}
                                    </a>
                                    <span class="text-[10px] text-[#5A4A42]">Updated {{ $draft->updated_at->diffForHumans() }}</span>
                                </div>
                                <a href="{{ route('drafts.edit', $draft->id) }}" class="text-[#783C46] font-bold text-xs" title="Continue Writing">
                                    &rarr;
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Notifications Drawer -->
            @if($unreadNotifications->isNotEmpty())
                <div class="paper-sheet p-5 rounded-lg border border-[#E9DDC7] shadow-paper space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-serif text-sm font-bold text-[#342A25]">Postal Updates</h3>
                        <a href="{{ route('notifications.index') }}" class="text-[11px] text-[#783C46] hover:underline">All</a>
                    </div>
                    <ul class="space-y-2 text-xs">
                        @foreach($unreadNotifications as $notif)
                            <li class="p-2.5 bg-[#FAF6EE] rounded border border-[#E9DDC7] space-y-1">
                                <p class="font-semibold text-[#342A25]">{{ $notif->title }}</p>
                                <p class="text-[11px] text-[#5A4A42] leading-relaxed">{{ $notif->message }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
