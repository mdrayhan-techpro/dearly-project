@extends('layouts.admin', ['title' => 'Review Report #' . $report->id])

@section('header', 'Review Safety Report #' . $report->id)

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-[#E0D7D0] pb-4">
            <div>
                <span class="text-xs uppercase tracking-wider text-[#8A7972] font-semibold">Moderation Record</span>
                <h2 class="font-serif text-xl font-bold text-[#2D2420]">Report #{{ $report->id }} Details</h2>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="text-xs text-[#783C46] hover:underline">&larr; Back to Queue</a>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs">
            <div class="p-3 bg-[#FAF6EE] rounded border border-[#E0D7D0]">
                <span class="text-[#8A7972] block">Reporter:</span>
                <strong class="text-sm text-[#2D2420]">{{ $report->reporter->nickname }}</strong>
                <span class="text-[10px] text-[#8A7972] block font-mono">{{ $report->reporter->email }}</span>
            </div>

            <div class="p-3 bg-[#FAF6EE] rounded border border-[#E0D7D0]">
                <span class="text-[#8A7972] block">Reported Member:</span>
                <strong class="text-sm text-[#783C46]">{{ $report->reportedUser->nickname }}</strong>
                <span class="text-[10px] text-[#8A7972] block font-mono">{{ $report->reportedUser->email }}</span>
                @if($report->reportedUser->is_suspended)
                    <span class="text-[10px] bg-red-100 text-red-800 px-1.5 py-0.5 rounded font-bold">Currently Suspended</span>
                @endif
            </div>
        </div>

        <div class="text-xs space-y-2">
            <p><strong>Reason Category:</strong> <span class="capitalize text-[#783C46] font-semibold">{{ str_replace('_', ' ', $report->reason) }}</span></p>
            <p><strong>Reported At:</strong> {{ $report->created_at->format('F j, Y \a\t g:i A') }}</p>
            @if($report->details)
                <div class="p-3 bg-gray-50 rounded border border-gray-200">
                    <span class="text-[#8A7972] block font-semibold mb-1">Details Provided by Reporter:</span>
                    <p class="text-[#2D2420] italic">{{ $report->details }}</p>
                </div>
            @endif
        </div>

        <!-- Associated Letter Preview (Authorized moderation review) -->
        @if($report->letter)
            <div class="border-t border-[#E0D7D0] pt-4 space-y-2">
                <h4 class="font-serif font-bold text-sm text-[#2D2420]">Reported Letter Content</h4>
                <div class="paper-sheet p-5 rounded border border-[#E0D7D0] text-xs space-y-2">
                    <p class="font-bold text-sm text-[#342A25]">{{ $report->letter->title }}</p>
                    <p class="text-[#5A4A42] whitespace-pre-line leading-relaxed">{{ $report->letter->body }}</p>
                </div>
            </div>
        @endif

        <!-- Action Form -->
        <div class="border-t border-[#E0D7D0] pt-6 space-y-4">
            <h4 class="font-serif font-bold text-sm text-[#2D2420]">Moderator Action</h4>

            <form method="POST" action="{{ route('admin.reports.action', $report->id) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-[#2D2420] mb-1">Action Decision</label>
                    <select name="action" required class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                        <option value="suspend">Suspend Reported Member Account</option>
                        <option value="dismiss">Dismiss Report (No Violation Found)</option>
                        <option value="review">Mark as Reviewed / Investigating</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#2D2420] mb-1">Internal Moderator Notes</label>
                    <textarea name="admin_notes" rows="3" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]" placeholder="Reason for action taken...">{{ $report->admin_notes }}</textarea>
                </div>

                <button type="submit" class="px-5 py-2.5 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm">
                    Execute Moderation Action
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
