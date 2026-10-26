@extends('layouts.admin', ['title' => 'System Audit Logs'])

@section('header', 'Security & Administration Audit Trail')

@section('content')
<div class="space-y-6">
    <!-- Filter Toolbar -->
    <div class="bg-white p-4 rounded-lg border border-[#E0D7D0] shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex flex-wrap items-center gap-3 w-full">
            <input type="text" name="action" value="{{ $action ?? '' }}" placeholder="Filter by action name (e.g. suspend, setting, stationery)..."
                class="text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE] focus:ring-[#783C46]/20 px-3 py-2 w-full sm:w-80">

            <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#783C46] hover:bg-[#582A32] rounded">
                Filter Logs
            </button>
            @if($action)
                <a href="{{ route('admin.audit-logs.index') }}" class="text-xs text-[#8A7972] hover:text-[#783C46] underline">Clear</a>
            @endif
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-lg border border-[#E0D7D0] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-[#FAF6EE] border-b border-[#E0D7D0] text-[#8A7972] uppercase text-[10px] tracking-wider font-semibold">
                    <tr>
                        <th class="px-4 py-3">Timestamp (UTC)</th>
                        <th class="px-4 py-3">Administrator</th>
                        <th class="px-4 py-3">Action</th>
                        <th class="px-4 py-3">Target Resource</th>
                        <th class="px-4 py-3">IP Address</th>
                        <th class="px-4 py-3">Metadata / Changes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E0D7D0]">
                    @forelse($logs as $log)
                        <tr class="hover:bg-[#FAF6EE]/50 transition-colors">
                            <td class="px-4 py-3 text-[#5C4F4A] whitespace-nowrap font-mono text-[11px]">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-4 py-3 font-medium text-[#2E2421]">
                                @if($log->admin)
                                    <div>{{ $log->admin->profile->nickname ?? $log->admin->name }}</div>
                                    <div class="text-[10px] text-[#8A7972]">{{ $log->admin->email }}</div>
                                @else
                                    <span class="text-[#8A7972] italic">System / Deleted</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold tracking-wide uppercase
                                    @if(str_contains($log->action, 'suspend') || str_contains($log->action, 'delete'))
                                        bg-red-50 text-red-700 border border-red-200
                                    @elseif(str_contains($log->action, 'update') || str_contains($log->action, 'setting'))
                                        bg-amber-50 text-amber-700 border border-amber-200
                                    @else
                                        bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @endif">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-[#5C4F4A] font-mono text-[11px]">
                                @if($log->target_type)
                                    {{ class_basename($log->target_type) }} #{{ $log->target_id }}
                                @else
                                    <span class="text-[#8A7972]">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-mono text-[11px] text-[#8A7972]">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                            <td class="px-4 py-3 text-[#5C4F4A]">
                                @if($log->changes)
                                    <details class="cursor-pointer">
                                        <summary class="text-[11px] text-[#783C46] hover:underline font-mono">
                                            View payload ({{ count(is_array($log->changes) ? $log->changes : json_decode($log->changes, true) ?? []) }} fields)
                                        </summary>
                                        <pre class="mt-2 p-2 bg-[#FAF6EE] text-[10px] font-mono rounded border border-[#D6C7AE] max-w-sm overflow-x-auto whitespace-pre-wrap">{{ is_string($log->changes) ? $log->changes : json_encode($log->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                    </details>
                                @else
                                    <span class="text-[#8A7972] text-[11px]">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-[#8A7972]">
                                <i class="fas fa-clipboard-check text-2xl mb-2 text-[#D6C7AE]"></i>
                                <p>No administrative audit logs found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-[#E0D7D0] bg-[#FAF6EE]">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
