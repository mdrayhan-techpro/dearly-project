@extends('layouts.admin', ['title' => 'Postal Traffic Ledger'])

@section('header', 'Postal Traffic Ledger & Correspondence Monitor')

@section('content')
<div class="space-y-6">
    <!-- Status Filter Tabs -->
    <div class="flex items-center space-x-2 border-b border-[#E0D7D0] pb-2 overflow-x-auto text-xs">
        <a href="{{ route('admin.letters.index', ['status' => 'all']) }}"
            class="px-3.5 py-1.5 rounded-full font-semibold transition-colors {{ $currentStatus === 'all' ? 'bg-[#783C46] text-white' : 'bg-white text-[#5A4A42] hover:bg-[#FAF6EE] border border-[#E0D7D0]' }}">
            All Dispatches ({{ number_format($counts['all']) }})
        </a>

        <a href="{{ route('admin.letters.index', ['status' => 'delivered']) }}"
            class="px-3.5 py-1.5 rounded-full font-semibold transition-colors {{ $currentStatus === 'delivered' ? 'bg-[#783C46] text-white' : 'bg-white text-[#5A4A42] hover:bg-[#FAF6EE] border border-[#E0D7D0]' }}">
            Delivered in Postbox ({{ number_format($counts['delivered']) }})
        </a>

        <a href="{{ route('admin.letters.index', ['status' => 'read']) }}"
            class="px-3.5 py-1.5 rounded-full font-semibold transition-colors {{ $currentStatus === 'read' ? 'bg-[#783C46] text-white' : 'bg-white text-[#5A4A42] hover:bg-[#FAF6EE] border border-[#E0D7D0]' }}">
            Verified Read ({{ number_format($counts['read']) }})
        </a>

        <a href="{{ route('admin.letters.index', ['status' => 'sent']) }}"
            class="px-3.5 py-1.5 rounded-full font-semibold transition-colors {{ $currentStatus === 'sent' ? 'bg-[#783C46] text-white' : 'bg-white text-[#5A4A42] hover:bg-[#FAF6EE] border border-[#E0D7D0]' }}">
            In Transit ({{ number_format($counts['sent']) }})
        </a>

        <a href="{{ route('admin.letters.index', ['status' => 'draft']) }}"
            class="px-3.5 py-1.5 rounded-full font-semibold transition-colors {{ $currentStatus === 'draft' ? 'bg-[#783C46] text-white' : 'bg-white text-[#5A4A42] hover:bg-[#FAF6EE] border border-[#E0D7D0]' }}">
            Drafts ({{ number_format($counts['draft']) }})
        </a>

        <a href="{{ route('admin.letters.index', ['status' => 'quarantined']) }}"
            class="px-3.5 py-1.5 rounded-full font-semibold transition-colors {{ $currentStatus === 'quarantined' ? 'bg-[#9C2A2A] text-white' : 'bg-white text-[#9C2A2A] hover:bg-red-50 border border-red-200' }}">
            Quarantined ({{ number_format($counts['quarantined']) }})
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-lg border border-[#E0D7D0] shadow-sm flex items-center justify-between">
        <form action="{{ route('admin.letters.index') }}" method="GET" class="flex items-center space-x-3 w-full max-w-lg">
            <input type="hidden" name="status" value="{{ $currentStatus }}">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, sender/recipient pseudonym, or mailing code..."
                    class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] focus:border-[#783C46] focus:ring-[#783C46] pl-8 py-2">
                <svg class="w-4 h-4 text-[#8A7972] absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <button type="submit" class="px-4 py-2 bg-[#783C46] text-white text-xs font-medium rounded hover:bg-[#5C2E36] transition-colors">
                Search
            </button>
            @if(request()->filled('search'))
                <a href="{{ route('admin.letters.index', ['status' => $currentStatus]) }}" class="text-xs text-[#8A7972] hover:text-[#783C46] underline">
                    Clear
                </a>
            @endif
        </form>

        <span class="text-xs text-[#8A7972] hidden sm:inline">
            Showing {{ $letters->total() }} recorded items
        </span>
    </div>

    <!-- Letters Table -->
    <div class="bg-white rounded-lg border border-[#E0D7D0] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[720px]">
            <thead class="bg-[#FAF8F5] text-[#8A7972] uppercase text-[10px] tracking-wider border-b border-[#E0D7D0]">
                <tr>
                    <th class="py-3 px-4">Dispatch ID</th>
                    <th class="py-3 px-4">Title / Missive</th>
                    <th class="py-3 px-4">Sender</th>
                    <th class="py-3 px-4">Recipient</th>
                    <th class="py-3 px-4">Stationery & Art</th>
                    <th class="py-3 px-4">Transit Status</th>
                    <th class="py-3 px-4">Recorded</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F0EAE4]">
                @forelse($letters as $letter)
                    <tr class="hover:bg-[#FAF6EE] transition-colors {{ $letter->status === 'quarantined' ? 'bg-red-50/40' : '' }}">
                        <td class="py-3 px-4 font-mono text-[11px] font-bold text-[#8A7972]">
                            #{{ $letter->id }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-bold text-[#2D2420] block max-w-xs truncate">{{ $letter->title }}</span>
                            <span class="text-[10px] text-[#8A7972] italic block truncate max-w-xs">
                                {{ Str::limit(strip_tags($letter->body), 45) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.users.show', $letter->sender_id) }}" class="font-bold text-[#2D2420] hover:text-[#783C46]">
                                {{ $letter->sender->nickname }}
                            </a>
                            <span class="text-[10px] text-[#B79A61] font-mono block">{{ $letter->sender->mailingCode?->code ?? 'N/A' }}</span>
                        </td>
                        <td class="py-3 px-4">
                            @if($letter->recipient)
                                <a href="{{ route('admin.users.show', $letter->recipient_id) }}" class="font-bold text-[#2D2420] hover:text-[#405B4B]">
                                    {{ $letter->recipient->nickname }}
                                </a>
                                <span class="text-[10px] text-[#B79A61] font-mono block">{{ $letter->recipient->mailingCode?->code ?? 'N/A' }}</span>
                            @else
                                <span class="text-[11px] text-[#8A7972] italic">Draft / Unaddressed</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-1.5 text-[10px] text-[#8A7972]">
                                <span title="Stationery: {{ $letter->stationeryTheme?->name ?? 'Default' }}" class="px-1.5 py-0.5 rounded bg-[#FAF4EB] border border-[#E0D7D0]">
                                    {{ Str::limit($letter->stationeryTheme?->name ?? 'Paper', 10) }}
                                </span>
                                @if($letter->postageStamp)
                                    <span title="Stamp: {{ $letter->postageStamp->name }}" class="px-1.5 py-0.5 rounded bg-blue-50 text-blue-800 border border-blue-200">
                                        Stamp
                                    </span>
                                @endif
                                @if($letter->waxSeal)
                                    <span title="Seal: {{ $letter->waxSeal->name }}" class="w-3.5 h-3.5 rounded-full border border-black/20" style="background-color: {{ $letter->waxSeal->color_hex }}"></span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            @if($letter->status === 'quarantined')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800 border border-red-300">
                                    Quarantined
                                </span>
                            @elseif($letter->status === 'read' || $letter->is_read)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#EFF4F0] text-[#1C2E24] border border-[#405B4B]/30">
                                    Read
                                </span>
                            @elseif($letter->status === 'delivered')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-300">
                                    In Postbox
                                </span>
                            @elseif($letter->status === 'sent')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                    Dispatched
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-700 capitalize">
                                    {{ $letter->status }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-[#8A7972] text-[11px]">
                            {{ $letter->created_at->format('M d, Y') }}
                            <span class="text-[9px] block text-[#8A7972]/80">{{ $letter->created_at->format('H:i') }}</span>
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <a href="{{ route('admin.letters.show', $letter->id) }}" class="text-[#783C46] hover:underline font-semibold text-xs">
                                Inspect
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-[#8A7972] italic">
                            No postal correspondence matches this view filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        @if($letters->hasPages())
            <div class="p-4 border-t border-[#E0D7D0]">
                {{ $letters->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
