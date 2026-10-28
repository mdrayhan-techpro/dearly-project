@extends('layouts.admin', ['title' => 'Kindred Match Explorer'])

@section('header', 'Kindred Pairings & Compatibility Engine')

@section('content')
<div class="space-y-6">
    <!-- Summary Metrics -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Total Pairings</span>
            <span class="text-2xl font-serif font-bold text-[#2D2420] mt-1 block">{{ number_format($stats['total_matches']) }}</span>
            <span class="text-[11px] text-[#8A7972] mt-0.5 block">Computed compatibility links</span>
        </div>

        <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Kindred (90%+)</span>
            <span class="text-2xl font-serif font-bold text-[#405B4B] mt-1 block">{{ number_format($stats['high_compatibility']) }}</span>
            <span class="text-[11px] text-[#405B4B] mt-0.5 block">Qualifying threshold</span>
        </div>

        <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Average Score</span>
            <span class="text-2xl font-serif font-bold text-[#783C46] mt-1 block">{{ $stats['avg_score'] }}%</span>
            <span class="text-[11px] text-[#8A7972] mt-0.5 block">System-wide resonance</span>
        </div>

        <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Active Correspondents</span>
            <span class="text-2xl font-serif font-bold text-[#B79A61] mt-1 block">{{ number_format($stats['connected_matches']) }}</span>
            <span class="text-[11px] text-[#8A7972] mt-0.5 block">Exchanged postal letters</span>
        </div>
    </div>

    <!-- Controls & Global Rerun Bar -->
    <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Search & Filters -->
            <form action="{{ route('admin.matches.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
                <div class="relative min-w-[240px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search pseudonym or mailing code..."
                        class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] focus:border-[#783C46] focus:ring-[#783C46] pl-8 py-2">
                    <svg class="w-4 h-4 text-[#8A7972] absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <div class="flex items-center space-x-2">
                    <label class="text-xs text-[#8A7972]">Min Score:</label>
                    <select name="min_score" class="text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] py-2 px-2.5 text-[#2D2420]">
                        <option value="">Any Score</option>
                        <option value="90" {{ request('min_score') == '90' ? 'selected' : '' }}>90% & Above (Kindred)</option>
                        <option value="80" {{ request('min_score') == '80' ? 'selected' : '' }}>80% & Above</option>
                        <option value="70" {{ request('min_score') == '70' ? 'selected' : '' }}>70% & Above</option>
                    </select>
                </div>

                <div class="flex items-center space-x-2">
                    <label class="text-xs text-[#8A7972]">Status:</label>
                    <select name="status" class="text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] py-2 px-2.5 text-[#2D2420]">
                        <option value="all">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="connected" {{ request('status') == 'connected' ? 'selected' : '' }}>Connected</option>
                        <option value="dismissed" {{ request('status') == 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                    </select>
                </div>

                <button type="submit" class="px-4 py-2 bg-[#783C46] text-white text-xs font-medium rounded hover:bg-[#5C2E36] transition-colors">
                    Filter
                </button>

                @if(request()->anyFilled(['search', 'min_score', 'status']))
                    <a href="{{ route('admin.matches.index') }}" class="text-xs text-[#8A7972] hover:text-[#783C46] underline">
                        Clear
                    </a>
                @endif
            </form>

            <!-- Re-Run Engine Actions -->
            <form action="{{ route('admin.matches.rerun') }}" method="POST" onsubmit="return confirm('Trigger global matchmaking across all completed postal questionnaires? This will evaluate all eligible members.');" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center space-x-2 px-4 py-2 bg-[#2A1F1B] text-[#B79A61] hover:text-white text-xs font-semibold rounded border border-[#B79A61]/40 hover:bg-[#3D302B] transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Run Global Kindred Engine</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Matches Table -->
    <div class="bg-white rounded-lg border border-[#E0D7D0] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[700px]">
            <thead class="bg-[#FAF8F5] text-[#8A7972] uppercase text-[10px] tracking-wider border-b border-[#E0D7D0]">
                <tr>
                    <th class="py-3 px-4">Member A (Initiator)</th>
                    <th class="py-3 px-4">Member B (Candidate)</th>
                    <th class="py-3 px-4 text-center">Compatibility</th>
                    <th class="py-3 px-4">Shared Resonance Dimensions</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F0EAE4]">
                @forelse($matches as $m)
                    <tr class="hover:bg-[#FAF6EE] transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-7 h-7 rounded-full bg-[#783C46]/10 text-[#783C46] flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($m->user->nickname, 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.users.show', $m->user_id) }}" class="font-bold text-[#2D2420] hover:text-[#783C46]">
                                        {{ $m->user->nickname }}
                                    </a>
                                    <span class="text-[10px] text-[#B79A61] font-mono block">{{ $m->user->mailingCode?->code ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-7 h-7 rounded-full bg-[#405B4B]/10 text-[#405B4B] flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($m->matchedUser->nickname, 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('admin.users.show', $m->matched_user_id) }}" class="font-bold text-[#2D2420] hover:text-[#405B4B]">
                                        {{ $m->matchedUser->nickname }}
                                    </a>
                                    <span class="text-[10px] text-[#B79A61] font-mono block">{{ $m->matchedUser->mailingCode?->code ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $m->compatibility_score >= 90 ? 'bg-[#EFF4F0] text-[#1C2E24] border border-[#405B4B]/30' : 'bg-[#FAF6EE] text-[#5A4A42] border border-[#E0D7D0]' }}">
                                    {{ number_format($m->compatibility_score, 1) }}%
                                </span>
                                <div class="w-16 bg-gray-100 h-1.5 rounded-full mt-1 overflow-hidden">
                                    <div class="h-full {{ $m->compatibility_score >= 90 ? 'bg-[#405B4B]' : 'bg-[#B79A61]' }}" style="width: {{ min(100, $m->compatibility_score) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            @if(!empty($m->shared_dimensions) && is_array($m->shared_dimensions))
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @foreach(array_slice($m->shared_dimensions, 0, 3) as $dim)
                                        <span class="inline-block px-1.5 py-0.5 rounded text-[10px] bg-[#FAF4EB] text-[#783C46] border border-[#E3D9CE]">
                                            {{ $dim }}
                                        </span>
                                    @endforeach
                                    @if(count($m->shared_dimensions) > 3)
                                        <span class="text-[10px] text-[#8A7972] self-center">+{{ count($m->shared_dimensions) - 3 }} more</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-[11px] text-[#8A7972] italic">Pending sync</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold capitalize
                                {{ $m->status === 'connected' ? 'bg-[#EFF4F0] text-[#1C2E24]' : ($m->status === 'active' ? 'bg-blue-50 text-blue-800' : 'bg-gray-100 text-gray-600') }}">
                                {{ $m->status }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <button type="button" onclick="openResonanceModal('{{ addslashes($m->user->nickname) }}', '{{ addslashes($m->matchedUser->nickname) }}', '{{ $m->compatibility_score }}', {{ json_encode($m->shared_dimensions ?? []) }})"
                                class="text-[#783C46] hover:underline font-semibold text-xs">
                                Inspect
                            </button>
                            <form action="{{ route('admin.matches.rerun') }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $m->user_id }}">
                                <button type="submit" class="text-[#8A7972] hover:text-[#2D2420] text-xs underline" title="Re-run matcher for initiator">
                                    Refresh
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-[#8A7972] italic">
                            No match results found matching criteria. Click "Run Global Kindred Engine" to compute matches.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        @if($matches->hasPages())
            <div class="p-4 border-t border-[#E0D7D0]">
                {{ $matches->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Resonance Inspection Modal -->
<div id="resonanceModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6 border border-[#E0D7D0] shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-[#E0D7D0] pb-3">
            <h3 class="font-serif font-bold text-base text-[#2D2420]">Kindred Resonance Breakdown</h3>
            <button type="button" onclick="closeResonanceModal()" class="text-[#8A7972] hover:text-[#2D2420] text-lg font-bold">&times;</button>
        </div>

        <div class="text-center py-2 space-y-2">
            <div class="flex items-center justify-center space-x-3 text-sm font-bold text-[#2D2420]">
                <span id="modalUserA" class="text-[#783C46]"></span>
                <span class="text-[#B79A61] font-serif">&amp;</span>
                <span id="modalUserB" class="text-[#405B4B]"></span>
            </div>
            <div class="inline-block px-4 py-1.5 rounded-full bg-[#EFF4F0] border border-[#405B4B]/30 text-[#1C2E24] font-bold text-lg font-serif" id="modalScore">
            </div>
        </div>

        <div class="space-y-2">
            <label class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Shared Resonance Dimensions:</label>
            <div id="modalDimensionsList" class="flex flex-wrap gap-1.5 max-h-48 overflow-y-auto p-2 bg-[#FAF8F5] rounded border border-[#E0D7D0]">
            </div>
        </div>

        <div class="pt-3 border-t border-[#E0D7D0] flex justify-end">
            <button type="button" onclick="closeResonanceModal()" class="px-4 py-2 bg-[#2D2420] text-white text-xs font-semibold rounded hover:bg-[#433731]">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function openResonanceModal(userA, userB, score, dimensions) {
        document.getElementById('modalUserA').textContent = userA;
        document.getElementById('modalUserB').textContent = userB;
        document.getElementById('modalScore').textContent = parseFloat(score).toFixed(1) + '% Compatibility';

        const list = document.getElementById('modalDimensionsList');
        list.innerHTML = '';
        if (dimensions && dimensions.length > 0) {
            dimensions.forEach(dim => {
                const tag = document.createElement('span');
                tag.className = 'px-2 py-1 rounded text-xs bg-white text-[#783C46] border border-[#E0D7D0] shadow-sm font-medium';
                tag.textContent = '✦ ' + dim;
                list.appendChild(tag);
            });
        } else {
            list.innerHTML = '<span class="text-xs text-[#8A7972] italic">No high-resonance dimensions flagged.</span>';
        }

        document.getElementById('resonanceModal').classList.remove('hidden');
    }

    function closeResonanceModal() {
        document.getElementById('resonanceModal').classList.add('hidden');
    }
</script>
@endsection
