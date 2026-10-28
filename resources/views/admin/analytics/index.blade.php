@extends('layouts.admin', ['title' => 'Postal Census & Analytics'])

@section('header', 'Postal Census & Correspondence Velocity Analytics')

@section('content')
<div class="space-y-8">
    <!-- Top KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Correspondence Velocity</span>
            <span class="text-3xl font-serif font-bold text-[#783C46] mt-1 block">{{ $lettersPerUser }}</span>
            <span class="text-[11px] text-[#8A7972] mt-0.5 block">Avg letters per active member</span>
        </div>

        <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Reading Latency</span>
            <span class="text-3xl font-serif font-bold text-[#B79A61] mt-1 block">{{ $avgReadHours }}h</span>
            <span class="text-[11px] text-[#8A7972] mt-0.5 block">Avg time from delivery to opening</span>
        </div>

        <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">System Resonance</span>
            <span class="text-3xl font-serif font-bold text-[#405B4B] mt-1 block">{{ $avgCompatibility }}%</span>
            <span class="text-[11px] text-[#405B4B] mt-0.5 block">Average pairing score</span>
        </div>

        <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Kindred Pairs (90%+)</span>
            <span class="text-3xl font-serif font-bold text-[#2D2420] mt-1 block">{{ number_format($totalKindredPairs) }}</span>
            <span class="text-[11px] text-[#8A7972] mt-0.5 block">High-affinity connections</span>
        </div>
    </div>

    <!-- Charts Row: 14-Day Registration Growth & 14-Day Postal Volume -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Registration Velocity -->
        <div class="bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-[#E0D7D0] pb-3">
                <div>
                    <h3 class="font-serif font-bold text-base text-[#2D2420]">Member Registration Velocity</h3>
                    <p class="text-xs text-[#8A7972]">New registrations across the past 14 days.</p>
                </div>
            </div>

            @php
                $maxReg = max(1, max(array_column($dailyRegistrations, 'count')));
            @endphp
            <div class="h-48 flex items-end justify-between gap-1.5 pt-4">
                @foreach($dailyRegistrations as $item)
                    @php $pct = ($item['count'] / $maxReg) * 100; @endphp
                    <div class="flex-1 flex flex-col items-center group relative">
                        <!-- Tooltip -->
                        <div class="absolute -top-7 hidden group-hover:block bg-[#2D2420] text-white text-[9px] px-1.5 py-0.5 rounded shadow z-10 whitespace-nowrap">
                            {{ $item['date'] }}: {{ $item['count'] }}
                        </div>
                        <div class="w-full bg-[#783C46] rounded-t transition-all group-hover:bg-[#5C2E36]" style="height: {{ max(4, $pct) }}%;"></div>
                        <span class="text-[8px] text-[#8A7972] mt-1 rotate-45 origin-left truncate w-6">{{ $item['date'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Letters Volume -->
        <div class="bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-[#E0D7D0] pb-3">
                <div>
                    <h3 class="font-serif font-bold text-base text-[#2D2420]">Letter Dispatch &amp; Reading Volume</h3>
                    <p class="text-xs text-[#8A7972]">Dispatches (Burgundy) vs Verified Read (Forest) past 14 days.</p>
                </div>
            </div>

            @php
                $maxLetters = max(1, max(array_column($dailyLetters, 'dispatched')), max(array_column($dailyLetters, 'read')));
            @endphp
            <div class="h-48 flex items-end justify-between gap-2 pt-4">
                @foreach($dailyLetters as $item)
                    @php
                        $dispPct = ($item['dispatched'] / $maxLetters) * 100;
                        $readPct = ($item['read'] / $maxLetters) * 100;
                    @endphp
                    <div class="flex-1 flex flex-col items-center group relative">
                        <div class="absolute -top-8 hidden group-hover:block bg-[#2D2420] text-white text-[9px] px-1.5 py-0.5 rounded shadow z-10 whitespace-nowrap">
                            {{ $item['date'] }} | Sent: {{ $item['dispatched'] }} | Read: {{ $item['read'] }}
                        </div>
                        <div class="w-full flex items-end justify-center space-x-0.5">
                            <div class="w-1/2 bg-[#783C46] rounded-t" style="height: {{ max(3, $dispPct) }}%;"></div>
                            <div class="w-1/2 bg-[#405B4B] rounded-t" style="height: {{ max(3, $readPct) }}%;"></div>
                        </div>
                        <span class="text-[8px] text-[#8A7972] mt-1 rotate-45 origin-left truncate w-6">{{ $item['date'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Demographics & Accoutrements Row -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Language Demographics -->
        <div class="lg:col-span-5 bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-4">
            <div class="border-b border-[#E0D7D0] pb-3">
                <h3 class="font-serif font-bold text-base text-[#2D2420]">Language Census</h3>
                <p class="text-xs text-[#8A7972]">Distribution of member preferred correspondence languages.</p>
            </div>

            @php $totalLang = max(1, $languageCounts->sum('total')); @endphp
            <div class="space-y-3">
                @forelse($languageCounts as $lc)
                    @php
                        $langPct = round(($lc->total / $totalLang) * 100, 1);
                        $langName = $languagesMap[$lc->preferred_language] ?? strtoupper($lc->preferred_language);
                    @endphp
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs">
                            <span class="font-medium text-[#2D2420]">{{ $langName }}</span>
                            <span class="font-bold text-[#783C46]">{{ $lc->total }} ({{ $langPct }}%)</span>
                        </div>
                        <div class="w-full bg-[#FAF4EB] h-2 rounded-full overflow-hidden border border-[#E0D7D0]">
                            <div class="bg-[#783C46] h-full" style="width: {{ $langPct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-[#8A7972] italic">No language data recorded.</p>
                @endforelse
            </div>
        </div>

        <!-- Popular Stationery Themes, Stamps & Seals -->
        <div class="lg:col-span-7 bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-5">
            <div class="border-b border-[#E0D7D0] pb-3">
                <h3 class="font-serif font-bold text-base text-[#2D2420]">Most Cherished Stationery &amp; Accoutrements</h3>
                <p class="text-xs text-[#8A7972]">Top chosen papers, postage stamps, and wax seals by active correspondents.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <!-- Top Themes -->
                <div class="space-y-2">
                    <span class="text-[10px] uppercase font-bold text-[#8A7972] block border-b border-[#F0EAE4] pb-1">Top Writing Papers</span>
                    @forelse($popularThemes as $t)
                        <div class="flex items-center justify-between p-2 rounded bg-[#FAF8F5] border border-[#E0D7D0]">
                            <span class="font-serif font-bold text-[#2D2420] truncate">{{ $t->name }}</span>
                            <span class="font-mono text-[10px] text-[#783C46] font-bold">{{ $t->letters_count }} letters</span>
                        </div>
                    @empty
                        <span class="text-[11px] text-[#8A7972] italic">None yet</span>
                    @endforelse
                </div>

                <!-- Top Stamps -->
                <div class="space-y-2">
                    <span class="text-[10px] uppercase font-bold text-[#8A7972] block border-b border-[#F0EAE4] pb-1">Top Postage Stamps</span>
                    @forelse($popularStamps as $s)
                        <div class="flex items-center justify-between p-2 rounded bg-[#FAF8F5] border border-[#E0D7D0]">
                            <span class="font-bold text-[#2D2420] truncate">{{ $s->name }}</span>
                            <span class="font-mono text-[10px] text-[#405B4B] font-bold">{{ $s->letters_count }} affixed</span>
                        </div>
                    @empty
                        <span class="text-[11px] text-[#8A7972] italic">None yet</span>
                    @endforelse
                </div>

                <!-- Top Wax Seals -->
                <div class="space-y-2">
                    <span class="text-[10px] uppercase font-bold text-[#8A7972] block border-b border-[#F0EAE4] pb-1">Top Wax Seals</span>
                    @forelse($popularSeals as $ws)
                        <div class="flex items-center justify-between p-2 rounded bg-[#FAF8F5] border border-[#E0D7D0]">
                            <div class="flex items-center space-x-1.5 truncate">
                                <span class="w-3 h-3 rounded-full border border-black/20" style="background-color: {{ $ws->color_hex }}"></span>
                                <span class="font-bold text-[#2D2420] truncate">{{ $ws->name }}</span>
                            </div>
                            <span class="font-mono text-[10px] text-[#B79A61] font-bold">{{ $ws->letters_count }} sealed</span>
                        </div>
                    @empty
                        <span class="text-[11px] text-[#8A7972] italic">None yet</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
