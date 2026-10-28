@extends('layouts.admin', ['title' => 'Dashboard'])

@section('header', 'Postmaster Command Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Key Metric Counters -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
        <a href="{{ route('admin.users.index') }}" class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm hover:border-[#783C46]/50 transition-colors block">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Total Registered Users</span>
            <span class="text-3xl font-serif font-bold text-[#2D2420] mt-1 block">{{ number_format($metrics['total_users']) }}</span>
            <span class="text-[11px] text-[#405B4B] font-medium mt-1 block">{{ $metrics['active_users'] }} active / {{ $metrics['suspended_users'] }} suspended</span>
        </a>

        <a href="{{ route('admin.questions.index') }}" class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm hover:border-[#783C46]/50 transition-colors block">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Completed Questionnaires</span>
            <span class="text-3xl font-serif font-bold text-[#783C46] mt-1 block">{{ number_format($metrics['completed_questionnaires']) }}</span>
            <span class="text-[11px] text-[#5A4A42] font-medium mt-1 block">Ready for Matching Engine</span>
        </a>

        <a href="{{ route('admin.matches.index') }}" class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm hover:border-[#783C46]/50 transition-colors block">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Generated Matches (90%+)</span>
            <span class="text-3xl font-serif font-bold text-[#405B4B] mt-1 block">{{ number_format($metrics['matches_generated']) }}</span>
            <span class="text-[11px] text-[#5A4A42] font-medium mt-1 block">Active Compatibility Pairings &rarr;</span>
        </a>

        <a href="{{ route('admin.letters.index') }}" class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm hover:border-[#783C46]/50 transition-colors block">
            <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold block">Letters Dispatched</span>
            <span class="text-3xl font-serif font-bold text-[#B79A61] mt-1 block">{{ number_format($metrics['letters_sent']) }}</span>
            <span class="text-[11px] text-[#405B4B] font-medium mt-1 block">{{ $metrics['letters_read'] }} verified read &rarr;</span>
        </a>
    </div>


    <!-- Middle Split: Recent Users & Moderation Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Recent Users Table -->
        <div class="lg:col-span-7 bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-[#E0D7D0] pb-3">
                <h3 class="font-serif font-bold text-base text-[#2D2420]">Recent Postal Registrations</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs text-[#783C46] font-semibold hover:underline">View All &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[500px]">
                    <thead class="text-[#8A7972] uppercase text-[10px] tracking-wider border-b border-[#E0D7D0]">
                        <tr>
                            <th class="pb-2">User / Pseudonym</th>
                            <th class="pb-2">Mailing Code</th>
                            <th class="pb-2">Role</th>
                            <th class="pb-2">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F0EAE4]">
                        @foreach($recentUsers as $user)
                            <tr class="hover:bg-[#FAF6EE] transition-colors">
                                <td class="py-2.5">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="font-bold text-[#2D2420] hover:text-[#783C46]">
                                        {{ $user->nickname }}
                                    </a>
                                    <span class="text-[10px] text-[#8A7972] block">{{ $user->email }}</span>
                                </td>
                                <td class="py-2.5 font-mono text-[10px] text-[#B79A61]">
                                    {{ $user->mailingCode?->code ?? 'Pending' }}
                                </td>
                                <td class="py-2.5 capitalize text-[11px]">
                                    {{ str_replace('_', ' ', $user->role) }}
                                </td>
                                <td class="py-2.5">
                                    @if($user->is_suspended)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-800">Suspended</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800">Active</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Open Safety Reports -->
        <div class="lg:col-span-5 bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-[#E0D7D0] pb-3">
                <div class="flex items-center space-x-2">
                    <h3 class="font-serif font-bold text-base text-[#2D2420]">Moderation Queue</h3>
                    @if($metrics['open_reports'] > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#783C46] text-white">
                            {{ $metrics['open_reports'] }} open
                        </span>
                    @endif
                </div>
                <a href="{{ route('admin.reports.index') }}" class="text-xs text-[#783C46] font-semibold hover:underline">All Reports &rarr;</a>
            </div>

            @if($recentReports->isEmpty())
                <p class="text-xs text-[#8A7972] italic py-6 text-center">No open moderation reports. Post office is peaceful.</p>
            @else
                <div class="space-y-3">
                    @foreach($recentReports as $rep)
                        <div class="p-3 bg-[#FAF6EE] rounded border border-[#E0D7D0] space-y-1">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-[#783C46] capitalize">{{ str_replace('_', ' ', $rep->reason) }}</span>
                                <span class="text-[10px] text-[#8A7972]">{{ $rep->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-[#2D2420]">
                                Reported: <strong>{{ $rep->reportedUser->nickname }}</strong> by {{ $rep->reporter->nickname }}
                            </p>
                            <div class="pt-1 text-right">
                                <a href="{{ route('admin.reports.show', $rep->id) }}" class="text-xs text-[#783C46] font-semibold hover:underline">
                                    Review Report &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
