@extends('layouts.admin', ['title' => 'Moderation Reports'])

@section('header', 'Community Moderation Queue')

@section('content')
<div class="space-y-6">
    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-lg border border-[#E0D7D0] shadow-sm flex items-center space-x-2 text-xs">
        <span class="text-[#8A7972] font-semibold">Filter:</span>
        <a href="{{ route('admin.reports.index', ['status' => 'open']) }}" class="px-3 py-1 rounded {{ $status === 'open' ? 'bg-[#783C46] text-white font-bold' : 'text-[#2D2420] hover:bg-[#FAF6EE]' }}">
            Open Reports
        </a>
        <a href="{{ route('admin.reports.index', ['status' => 'action_taken']) }}" class="px-3 py-1 rounded {{ $status === 'action_taken' ? 'bg-[#783C46] text-white font-bold' : 'text-[#2D2420] hover:bg-[#FAF6EE]' }}">
            Action Taken
        </a>
        <a href="{{ route('admin.reports.index', ['status' => 'dismissed']) }}" class="px-3 py-1 rounded {{ $status === 'dismissed' ? 'bg-[#783C46] text-white font-bold' : 'text-[#2D2420] hover:bg-[#FAF6EE]' }}">
            Dismissed
        </a>
        <a href="{{ route('admin.reports.index', ['status' => 'all']) }}" class="px-3 py-1 rounded {{ $status === 'all' ? 'bg-[#783C46] text-white font-bold' : 'text-[#2D2420] hover:bg-[#FAF6EE]' }}">
            All
        </a>
    </div>

    <!-- Reports Table -->
    @if($reports->isEmpty())
        <div class="bg-white p-8 rounded-lg border border-[#E0D7D0] text-center text-xs text-[#8A7972] italic">
            No moderation reports in this queue.
        </div>
    @else
        <div class="bg-white rounded-lg border border-[#E0D7D0] shadow-sm overflow-hidden">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FAF6EE] border-b border-[#E0D7D0] text-[#8A7972] uppercase text-[10px] tracking-wider font-semibold">
                    <tr>
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Reported Member</th>
                        <th class="py-3 px-4">Reporter</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Reported At</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F0EAE4] text-[#2D2420]">
                    @foreach($reports as $rep)
                        <tr class="hover:bg-[#FAF6EE]/80 transition-colors">
                            <td class="py-3 px-4 font-mono text-[#8A7972]">#{{ $rep->id }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.users.show', $rep->reported_user_id) }}" class="font-bold text-[#783C46] hover:underline">
                                    {{ $rep->reportedUser->nickname }}
                                </a>
                                @if($rep->reportedUser->is_suspended)
                                    <span class="text-[9px] bg-red-100 text-red-800 px-1.5 py-0.5 rounded font-bold ml-1">Suspended</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-[#5A4A42]">
                                {{ $rep->reporter->nickname }}
                            </td>
                            <td class="py-3 px-4 capitalize font-semibold">
                                {{ str_replace('_', ' ', $rep->reason) }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $rep->status === 'open' ? 'bg-amber-100 text-amber-800' : ($rep->status === 'action_taken' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700') }}">
                                    {{ strtoupper($rep->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-[#8A7972]">
                                {{ $rep->created_at->diffForHumans() }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('admin.reports.show', $rep->id) }}" class="text-xs text-[#783C46] font-semibold hover:underline">
                                    Review &rarr;
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            {{ $reports->links() }}
        </div>
    @endif
</div>
@endsection
