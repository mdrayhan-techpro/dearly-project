@extends('layouts.admin', ['title' => 'Inspect Dispatch #' . $letter->id])

@section('header', 'Postal Dispatch Inspection #' . $letter->id)

@section('content')
<div class="space-y-6 max-w-5xl">
    <!-- Breadcrumb & Status Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.letters.index') }}" class="text-xs text-[#783C46] font-semibold hover:underline inline-flex items-center space-x-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Traffic Ledger</span>
        </a>

        <div class="flex items-center space-x-3">
            @if($letter->status === 'quarantined')
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-300">
                    Postal Quarantine Active
                </span>
                <form action="{{ route('admin.letters.release', $letter->id) }}" method="POST" onsubmit="return confirm('Release this dispatch from quarantine?');">
                    @csrf
                    <button type="submit" class="px-3.5 py-1.5 bg-[#405B4B] text-white text-xs font-semibold rounded hover:bg-[#2D4034] transition-colors">
                        Release from Quarantine
                    </button>
                </form>
            @else
                <button type="button" onclick="document.getElementById('quarantineModal').classList.remove('hidden')" class="px-3.5 py-1.5 bg-[#9C2A2A] text-white text-xs font-semibold rounded hover:bg-[#7D2222] transition-colors">
                    Place in Quarantine
                </button>
            @endif
        </div>
    </div>

    <!-- Letter Route Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <!-- Sender Card -->
        <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm space-y-2">
            <span class="text-[10px] uppercase tracking-wider text-[#8A7972] font-semibold block">Origin / Sender</span>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-[#783C46]/10 text-[#783C46] font-serif font-bold flex items-center justify-center text-sm">
                    {{ strtoupper(substr($letter->sender->nickname, 0, 1)) }}
                </div>
                <div>
                    <a href="{{ route('admin.users.show', $letter->sender_id) }}" class="font-serif font-bold text-base text-[#2D2420] hover:text-[#783C46]">
                        {{ $letter->sender->nickname }}
                    </a>
                    <span class="text-xs text-[#B79A61] font-mono block">{{ $letter->sender->mailingCode?->code ?? 'No Code' }}</span>
                </div>
            </div>
            <div class="pt-2 border-t border-[#F0EAE4] text-[11px] text-[#8A7972]">
                Account Status: <span class="font-semibold {{ $letter->sender->is_suspended ? 'text-red-700' : 'text-[#405B4B]' }}">{{ $letter->sender->is_suspended ? 'Suspended' : 'Active' }}</span>
            </div>
        </div>

        <!-- Recipient Card -->
        <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm space-y-2">
            <span class="text-[10px] uppercase tracking-wider text-[#8A7972] font-semibold block">Destination / Recipient</span>
            @if($letter->recipient)
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-[#405B4B]/10 text-[#405B4B] font-serif font-bold flex items-center justify-center text-sm">
                        {{ strtoupper(substr($letter->recipient->nickname, 0, 1)) }}
                    </div>
                    <div>
                        <a href="{{ route('admin.users.show', $letter->recipient_id) }}" class="font-serif font-bold text-base text-[#2D2420] hover:text-[#405B4B]">
                            {{ $letter->recipient->nickname }}
                        </a>
                        <span class="text-xs text-[#B79A61] font-mono block">{{ $letter->recipient->mailingCode?->code ?? 'No Code' }}</span>
                    </div>
                </div>
                <div class="pt-2 border-t border-[#F0EAE4] text-[11px] text-[#8A7972]">
                    Delivery: <span class="font-semibold text-[#2D2420]">{{ $letter->delivered_at ? $letter->delivered_at->format('M d, Y H:i') : 'Pending' }}</span>
                </div>
            @else
                <div class="py-3 text-xs text-[#8A7972] italic">
                    Unaddressed draft. Not dispatched to any recipient.
                </div>
            @endif
        </div>
    </div>

    <!-- Letter Stationery & Art Metadata -->
    <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm">
        <h4 class="font-serif font-bold text-sm text-[#2D2420] mb-3">Postal Stationery & Seal Accoutrements</h4>
        <div class="grid grid-cols-3 gap-4 text-xs">
            <div class="p-3 bg-[#FAF8F5] rounded border border-[#E0D7D0]">
                <span class="text-[10px] uppercase text-[#8A7972] font-semibold block">Paper Theme</span>
                <span class="font-semibold text-[#2D2420] block mt-0.5">{{ $letter->stationeryTheme?->name ?? 'Default Parchment' }}</span>
            </div>
            <div class="p-3 bg-[#FAF8F5] rounded border border-[#E0D7D0]">
                <span class="text-[10px] uppercase text-[#8A7972] font-semibold block">Postage Stamp</span>
                <span class="font-semibold text-[#2D2420] block mt-0.5">{{ $letter->postageStamp?->name ?? 'Standard Vintage' }}</span>
            </div>
            <div class="p-3 bg-[#FAF8F5] rounded border border-[#E0D7D0]">
                <span class="text-[10px] uppercase text-[#8A7972] font-semibold block">Wax Seal</span>
                <div class="flex items-center space-x-2 mt-0.5">
                    @if($letter->waxSeal)
                        <span class="w-4 h-4 rounded-full border border-black/20" style="background-color: {{ $letter->waxSeal->color_hex }}"></span>
                        <span class="font-semibold text-[#2D2420]">{{ $letter->waxSeal->name }}</span>
                    @else
                        <span class="font-semibold text-[#2D2420]">Burgundy Initial</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Letter Content Body -->
    <div class="bg-white rounded-lg border border-[#E0D7D0] shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-[#FAF8F5] border-b border-[#E0D7D0] flex items-center justify-between">
            <h3 class="font-serif font-bold text-base text-[#2D2420]">{{ $letter->title }}</h3>
            <span class="text-[11px] text-[#8A7972] font-mono">Dispatched: {{ $letter->sent_at ? $letter->sent_at->format('M d, Y H:i:s') : 'Unsent' }}</span>
        </div>
        <div class="p-8 prose max-w-none text-[#2D2420] font-serif text-sm leading-relaxed whitespace-pre-wrap bg-[#FCFBF8] border-b border-[#E0D7D0]">
@if($letter->greeting)
<p class="font-semibold mb-4">{{ $letter->greeting }}</p>
@endif
{{ $letter->body }}
@if($letter->signoff)
<p class="font-semibold mt-6">{{ $letter->signoff }}</p>
@endif
        </div>
    </div>

    <!-- Transit Events Ledger -->
    <div class="bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-4">
        <h4 class="font-serif font-bold text-base text-[#2D2420]">Postal Chain of Custody (Event Log)</h4>
        <div class="relative pl-6 border-l-2 border-[#B79A61]/40 space-y-4">
            @forelse($letter->events as $ev)
                <div class="relative">
                    <div class="absolute -left-[31px] top-0.5 w-3 h-3 rounded-full bg-[#783C46] border-2 border-white"></div>
                    <div class="text-xs">
                        <span class="font-bold text-[#2D2420] capitalize">{{ str_replace('_', ' ', $ev->event_type) }}</span>
                        <span class="text-[10px] text-[#8A7972] ml-2">{{ $ev->recorded_at->format('M d, Y H:i:s') }}</span>
                        @if(!empty($ev->metadata))
                            <pre class="mt-1 text-[10px] text-[#5A4A42] bg-[#FAF8F5] p-2 rounded border border-[#E0D7D0] overflow-x-auto">{{ json_encode($ev->metadata, JSON_PRETTY_PRINT) }}</pre>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-xs text-[#8A7972] italic">No transit chain events recorded.</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Quarantine Modal -->
<div id="quarantineModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6 border border-[#E0D7D0] shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-[#E0D7D0] pb-3">
            <h3 class="font-serif font-bold text-base text-[#9C2A2A]">Place Letter in Postal Quarantine</h3>
            <button type="button" onclick="document.getElementById('quarantineModal').classList.add('hidden')" class="text-[#8A7972] hover:text-[#2D2420] text-lg font-bold">&times;</button>
        </div>

        <p class="text-xs text-[#5A4A42]">
            Quarantining a dispatch will immediately withhold it from delivery or remove it from recipient view pending safety review.
        </p>

        <form action="{{ route('admin.letters.quarantine', $letter->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Reason for Quarantine <span class="text-red-600">*</span></label>
                <textarea name="reason" rows="3" required placeholder="Specify suspicion of spam, harassment, or policy violation..."
                    class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] focus:border-[#783C46] focus:ring-[#783C46] p-2"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-[#E0D7D0]">
                <button type="button" onclick="document.getElementById('quarantineModal').classList.add('hidden')" class="px-3 py-1.5 text-xs text-[#8A7972] hover:underline">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-[#9C2A2A] text-white text-xs font-semibold rounded hover:bg-[#7D2222]">
                    Confirm Quarantine
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
