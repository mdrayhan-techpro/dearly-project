@extends('layouts.admin', ['title' => 'System Health & Engine Diagnostics'])

@section('header', 'System Health, Engine Diagnostics & Queue Control')

@section('content')
<div class="space-y-8 max-w-6xl">
    <!-- Quick Maintenance Action Bar -->
    <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm flex flex-wrap items-center justify-between gap-4">
        <div>
            <h3 class="font-serif font-bold text-base text-[#2D2420]">Platform Maintenance Tools</h3>
            <p class="text-xs text-[#8A7972]">Manage runtime cache, compiled views, and optimization routines.</p>
        </div>

        <div class="flex items-center space-x-3">
            <form action="{{ route('admin.system.clear-cache') }}" method="POST" onsubmit="return confirm('Clear application cache, views, and configuration?');">
                @csrf
                <button type="submit" class="px-4 py-2 bg-[#FAF8F5] text-[#783C46] border border-[#783C46]/30 hover:bg-[#783C46] hover:text-white text-xs font-semibold rounded transition-colors shadow-xs">
                    Purge All Caches
                </button>
            </form>

            <form action="{{ route('admin.system.optimize') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-[#405B4B] text-white hover:bg-[#2D4034] text-xs font-semibold rounded transition-colors shadow-xs">
                    Run Framework Optimizer
                </button>
            </form>
        </div>
    </div>

    <!-- Diagnostic Details Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-[10px] uppercase font-bold text-[#8A7972] block">PHP Environment</span>
            <span class="text-lg font-bold font-mono text-[#2D2420] mt-1 block">{{ $diagnostics['php_version'] }}</span>
        </div>

        <div class="bg-white p-4 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-[10px] uppercase font-bold text-[#8A7972] block">Laravel Framework</span>
            <span class="text-lg font-bold font-mono text-[#783C46] mt-1 block">v{{ $diagnostics['laravel_version'] }}</span>
        </div>

        <div class="bg-white p-4 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-[10px] uppercase font-bold text-[#8A7972] block">Database Storage</span>
            <span class="text-lg font-bold font-mono text-[#405B4B] mt-1 block">{{ $diagnostics['sqlite_size_mb'] }} MB</span>
            <span class="text-[10px] text-[#8A7972] block capitalize">{{ $diagnostics['database_driver'] }} DB</span>
        </div>

        <div class="bg-white p-4 rounded-lg border border-[#E0D7D0] shadow-sm">
            <span class="text-[10px] uppercase font-bold text-[#8A7972] block">Queue Driver</span>
            <span class="text-lg font-bold font-mono text-[#B79A61] mt-1 block capitalize">{{ $diagnostics['queue_connection'] }}</span>
        </div>
    </div>

    <!-- Split: Queue Monitor & Database Census -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Background Queue Monitor -->
        <div class="lg:col-span-7 bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-[#E0D7D0] pb-3">
                <div>
                    <h3 class="font-serif font-bold text-base text-[#2D2420]">Postal Dispatch &amp; Matching Queue</h3>
                    <p class="text-xs text-[#8A7972]">Asynchronous job workers for correspondence delivery and email notifications.</p>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-2.5 py-1 rounded text-xs font-bold {{ $pendingJobs > 0 ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }}">
                        {{ $pendingJobs }} Pending
                    </span>
                    <span class="px-2.5 py-1 rounded text-xs font-bold {{ $failedJobs > 0 ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700' }}">
                        {{ $failedJobs }} Failed
                    </span>
                </div>
            </div>

            <!-- Queue Action Buttons -->
            @if($failedJobs > 0)
                <div class="p-3 bg-red-50 rounded border border-red-200 flex items-center justify-between">
                    <span class="text-xs text-red-800 font-medium">{{ $failedJobs }} background jobs failed execution.</span>
                    <div class="flex items-center space-x-2">
                        <form action="{{ route('admin.system.retry-jobs') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-[#405B4B] text-white text-xs font-bold rounded hover:bg-[#2D4034]">
                                Retry All
                            </button>
                        </form>
                        <form action="{{ route('admin.system.flush-jobs') }}" method="POST" onsubmit="return confirm('Purge failed job records?');">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-red-700 text-white text-xs font-bold rounded hover:bg-red-900">
                                Flush
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Failed Jobs List -->
            @if($failedJobsList->isNotEmpty())
                <div class="space-y-2">
                    <span class="text-[11px] font-bold text-[#8A7972] uppercase">Recent Failed Jobs</span>
                    @foreach($failedJobsList as $fj)
                        <div class="p-2.5 bg-[#FAF8F5] rounded border border-[#E0D7D0] text-xs font-mono space-y-1">
                            <div class="flex justify-between font-bold text-red-800">
                                <span>#{{ $fj->id }} - {{ $fj->connection }}</span>
                                <span class="text-[10px] text-[#8A7972]">{{ $fj->failed_at }}</span>
                            </div>
                            <p class="text-[10px] text-[#5A4A42] truncate">{{ Str::limit($fj->exception, 120) }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center text-xs text-[#8A7972] italic">
                    All postal queues are healthy. No failed jobs recorded.
                </div>
            @endif
        </div>

        <!-- Database Census -->
        <div class="lg:col-span-5 bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-4">
            <div class="border-b border-[#E0D7D0] pb-3">
                <h3 class="font-serif font-bold text-base text-[#2D2420]">Database Ledger Census</h3>
                <p class="text-xs text-[#8A7972]">Live record counts across core sanctuary tables.</p>
            </div>

            <div class="space-y-2 text-xs">
                @foreach($tableCensus as $tableLabel => $count)
                    <div class="flex items-center justify-between p-2 rounded bg-[#FAF8F5] border border-[#E0D7D0]">
                        <span class="font-medium text-[#2D2420]">{{ $tableLabel }}</span>
                        <span class="font-mono font-bold text-[#783C46]">{{ number_format($count) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
