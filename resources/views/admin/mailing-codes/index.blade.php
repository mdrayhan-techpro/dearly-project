@extends('layouts.admin', ['title' => 'Mailing Code Directory'])

@section('header', 'Mailing Code Directory & Fleet Control')

@section('content')
<div class="space-y-6">
    <!-- Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Total Issued Codes</span>
            <span class="text-2xl font-serif font-bold text-[#2D2420] mt-1 block">{{ number_format($stats['total']) }}</span>
            <span class="text-[11px] text-[#8A7972] mt-0.5 block">Allocated postal identifiers</span>
        </div>

        <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Active Delivery Routes</span>
            <span class="text-2xl font-serif font-bold text-[#405B4B] mt-1 block">{{ number_format($stats['active']) }}</span>
            <span class="text-[11px] text-[#405B4B] mt-0.5 block">Able to receive correspondence</span>
        </div>

        <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Deactivated / Suspended</span>
            <span class="text-2xl font-serif font-bold text-[#783C46] mt-1 block">{{ number_format($stats['inactive']) }}</span>
            <span class="text-[11px] text-[#8A7972] mt-0.5 block">Retired or account suspended</span>
        </div>

        <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Connections-Only</span>
            <span class="text-2xl font-serif font-bold text-[#B79A61] mt-1 block">{{ number_format($stats['connections_only']) }}</span>
            <span class="text-[11px] text-[#8A7972] mt-0.5 block">Restricted privacy policy</span>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm">
        <form action="{{ route('admin.mailing-codes.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative min-w-[260px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by postal code (e.g. DEARLY-...), nickname, or email..."
                    class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] focus:border-[#783C46] focus:ring-[#783C46] pl-8 py-2">
                <svg class="w-4 h-4 text-[#8A7972] absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <div class="flex items-center space-x-2">
                <label class="text-xs text-[#8A7972]">Status:</label>
                <select name="status" class="text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] py-2 px-2.5 text-[#2D2420]">
                    <option value="all">All</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive / Suspended</option>
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <label class="text-xs text-[#8A7972]">Policy:</label>
                <select name="policy" class="text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] py-2 px-2.5 text-[#2D2420]">
                    <option value="all">All Policies</option>
                    <option value="anyone" {{ request('policy') === 'anyone' ? 'selected' : '' }}>Open to Anyone</option>
                    <option value="accepted_connections" {{ request('policy') === 'accepted_connections' ? 'selected' : '' }}>Accepted Connections Only</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-[#783C46] text-white text-xs font-semibold rounded hover:bg-[#5C2E36] transition-colors">
                Filter
            </button>

            @if(request()->anyFilled(['search', 'status', 'policy']))
                <a href="{{ route('admin.mailing-codes.index') }}" class="text-xs text-[#8A7972] hover:text-[#783C46] underline">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg border border-[#E0D7D0] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[680px]">
            <thead class="bg-[#FAF8F5] text-[#8A7972] uppercase text-[10px] tracking-wider border-b border-[#E0D7D0]">
                <tr>
                    <th class="py-3 px-4">Postal Mailing Code</th>
                    <th class="py-3 px-4">Assigned Member</th>
                    <th class="py-3 px-4">Contact Privacy Policy</th>
                    <th class="py-3 px-4">Delivery Status</th>
                    <th class="py-3 px-4">Last Rotated / Updated</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F0EAE4]">
                @forelse($codes as $c)
                    <tr class="hover:bg-[#FAF6EE] transition-colors">
                        <td class="py-3 px-4">
                            <span class="inline-block px-2.5 py-1 rounded bg-[#FAF4EB] border border-[#B79A61]/40 text-[#783C46] font-mono font-bold text-xs tracking-wider">
                                {{ $c->code }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @if($c->user)
                                <a href="{{ route('admin.users.show', $c->user->id) }}" class="font-bold text-[#2D2420] hover:text-[#783C46] block">
                                    {{ $c->user->nickname }}
                                </a>
                                <span class="text-[10px] text-[#8A7972] block">{{ $c->user->email }}</span>
                            @else
                                <span class="text-[#8A7972] italic">Orphaned Code</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="capitalize text-[11px] text-[#5A4A42]">
                                {{ str_replace('_', ' ', $c->permissions_policy) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            @if($c->is_active)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#EFF4F0] text-[#1C2E24] border border-[#405B4B]/30">
                                    Active
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800">
                                    Deactivated
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-[#8A7972] text-[11px]">
                            {{ $c->updated_at->format('M d, Y') }}
                        </td>
                        <td class="py-3 px-4 text-right space-x-2">
                            <form action="{{ route('admin.mailing-codes.toggle', $c->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs font-semibold text-[#8A7972] hover:text-[#2D2420] underline">
                                    {{ $c->is_active ? 'Disable' : 'Enable' }}
                                </button>
                            </form>

                            <button type="button" onclick="openRotateModal({{ $c->id }}, '{{ $c->code }}', '{{ addslashes($c->user?->nickname ?? 'Unknown') }}')"
                                class="text-xs font-semibold text-[#783C46] hover:underline">
                                Rotate Code
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-[#8A7972] italic">
                            No mailing codes found matching query.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        @if($codes->hasPages())
            <div class="p-4 border-t border-[#E0D7D0]">
                {{ $codes->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Rotate Code Modal -->
<div id="rotateModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6 border border-[#E0D7D0] shadow-2xl space-y-4">
        <div class="flex items-center justify-between border-b border-[#E0D7D0] pb-3">
            <h3 class="font-serif font-bold text-base text-[#2D2420]">Re-issue New Postal Mailing Code</h3>
            <button type="button" onclick="document.getElementById('rotateModal').classList.add('hidden')" class="text-[#8A7972] text-lg font-bold">&times;</button>
        </div>

        <p class="text-xs text-[#5A4A42]">
            Rotating this postal code will immediately retire <span id="modalCurrentCode" class="font-mono font-bold text-[#783C46]"></span> for member <span id="modalMemberName" class="font-bold"></span> and issue a brand-new cryptographic postal identifier.
        </p>

        <form id="rotateForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Administrative Note / Reason</label>
                <input type="text" name="reason" placeholder="e.g. Member requested address refresh, privacy concern..."
                    class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] focus:border-[#783C46] focus:ring-[#783C46] p-2">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-[#E0D7D0]">
                <button type="button" onclick="document.getElementById('rotateModal').classList.add('hidden')" class="px-3 py-1.5 text-xs text-[#8A7972] hover:underline">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-[#783C46] text-white text-xs font-semibold rounded hover:bg-[#5C2E36]">
                    Generate &amp; Re-issue
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRotateModal(id, code, name) {
        document.getElementById('rotateForm').action = '/admin/mailing-codes/' + id + '/rotate';
        document.getElementById('modalCurrentCode').textContent = code;
        document.getElementById('modalMemberName').textContent = name;
        document.getElementById('rotateModal').classList.remove('hidden');
    }
</script>
@endsection
