@extends('layouts.admin', ['title' => 'Question Bank'])

@section('header', 'Question Bank & Questionnaire Engine')

@section('content')
<div class="space-y-6">
    <!-- Questionnaire Completion Funnel Cards -->
    <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm space-y-3">
        <div class="flex items-center justify-between border-b border-[#E0D7D0] pb-2">
            <div>
                <h3 class="font-serif font-bold text-sm text-[#2D2420]">Postal Questionnaire Engagement Funnel</h3>
                <p class="text-[11px] text-[#8A7972]">Completed member volume across the 4 postal questionnaire tiers.</p>
            </div>
            <span class="text-xs font-bold text-[#405B4B]">{{ number_format($funnelStats['total_completed']) }} Total Completed</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-1">
            <div class="p-3 bg-[#FAF8F5] rounded border border-[#E0D7D0] text-center">
                <span class="text-[10px] uppercase tracking-wider text-[#8A7972] font-semibold block">Brief (20 Qs)</span>
                <span class="text-xl font-serif font-bold text-[#2D2420] mt-0.5 block">{{ number_format($funnelStats['len_20']) }}</span>
                <span class="text-[10px] text-[#405B4B] block font-medium">Core Discovery</span>
            </div>

            <div class="p-3 bg-[#FAF8F5] rounded border border-[#E0D7D0] text-center">
                <span class="text-[10px] uppercase tracking-wider text-[#8A7972] font-semibold block">Standard (30 Qs)</span>
                <span class="text-xl font-serif font-bold text-[#783C46] mt-0.5 block">{{ number_format($funnelStats['len_30']) }}</span>
                <span class="text-[10px] text-[#5A4A42] block font-medium">Balanced Pen-Pal</span>
            </div>

            <div class="p-3 bg-[#FAF8F5] rounded border border-[#E0D7D0] text-center">
                <span class="text-[10px] uppercase tracking-wider text-[#8A7972] font-semibold block">Deep (40 Qs)</span>
                <span class="text-xl font-serif font-bold text-[#B79A61] mt-0.5 block">{{ number_format($funnelStats['len_40']) }}</span>
                <span class="text-[10px] text-[#5A4A42] block font-medium">Philosophic Depth</span>
            </div>

            <div class="p-3 bg-[#FAF8F5] rounded border border-[#E0D7D0] text-center">
                <span class="text-[10px] uppercase tracking-wider text-[#8A7972] font-semibold block">Profound (50 Qs)</span>
                <span class="text-xl font-serif font-bold text-[#405B4B] mt-0.5 block">{{ number_format($funnelStats['len_50']) }}</span>
                <span class="text-[10px] text-[#405B4B] block font-medium">Exquisite Kindred</span>
            </div>
        </div>
    </div>

    <!-- Filter & Action Bar -->
    <div class="bg-white p-4 rounded-lg border border-[#E0D7D0] shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4">
        <form method="GET" action="{{ route('admin.questions.index') }}" class="flex items-center space-x-3 w-full sm:w-auto">
            <select name="dimension" class="text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] py-2 px-3 text-[#2D2420]">
                <option value="">All 17 Dimensions</option>
                @foreach($dimensions as $dimKey => $dimName)
                    <option value="{{ $dimKey }}" {{ $dimension === $dimKey ? 'selected' : '' }}>{{ $dimName }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-[#783C46] hover:bg-[#582A32] rounded">
                Filter
            </button>
            @if($dimension)
                <a href="{{ route('admin.questions.index') }}" class="text-xs text-[#8A7972] underline">Reset</a>
            @endif
        </form>

        <a href="{{ route('admin.questions.create') }}" class="px-5 py-2 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm">
            + New Discovery Question
        </a>
    </div>

    <!-- Questions Table -->
    <div class="bg-white rounded-lg border border-[#E0D7D0] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[700px]">
            <thead class="bg-[#FAF8F5] border-b border-[#E0D7D0] text-[#8A7972] uppercase text-[10px] tracking-wider font-semibold">
                <tr>
                    <th class="py-3 px-4">Order #</th>
                    <th class="py-3 px-4">Tier Depth</th>
                    <th class="py-3 px-4">Dimension</th>
                    <th class="py-3 px-4">Question Text</th>
                    <th class="py-3 px-4">Choices</th>
                    <th class="py-3 px-4">Weight</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F0EAE4] text-[#2D2420]">
                @foreach($questions as $q)
                    <tr class="hover:bg-[#FAF6EE]/80 transition-colors">
                        <td class="py-3 px-4 font-mono font-bold text-[#8A7972]">
                            {{ $q->order_index }}
                        </td>
                        <td class="py-3 px-4">
                            @if($q->order_index <= 20)
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-[#FAF4EB] text-[#783C46] border border-[#E0D7D0]">Tier 1 (Brief)</span>
                            @elseif($q->order_index <= 30)
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-800 border border-amber-200">Tier 2 (Standard)</span>
                            @elseif($q->order_index <= 40)
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-blue-50 text-blue-800 border border-blue-200">Tier 3 (Deep)</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-[#EFF4F0] text-[#1C2E24] border border-[#405B4B]/30">Tier 4 (Profound)</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-[#783C46]/10 text-[#783C46]">
                                {{ ucwords(str_replace('_', ' ', $q->dimension)) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-serif font-bold text-xs max-w-sm truncate" title="{{ $q->question_text }}">
                            {{ $q->question_text }}
                        </td>
                        <td class="py-3 px-4 text-[11px] text-[#8A7972]">
                            {{ $q->options->count() }} Choices (A–E)
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-[#405B4B]">
                            {{ $q->weight }}x
                        </td>
                        <td class="py-3 px-4">
                            <form method="POST" action="{{ route('admin.questions.toggle', $q->id) }}">
                                @csrf
                                <button type="submit" class="px-2 py-0.5 rounded text-[10px] font-bold {{ $q->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $q->is_active ? 'Active' : 'Archived' }}
                                </button>
                            </form>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('admin.questions.edit', $q->id) }}" class="text-xs text-[#783C46] font-semibold hover:underline">
                                Edit &rarr;
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

    @if($questions->hasPages())
        <div>
            {{ $questions->links() }}
        </div>
    @endif
</div>
@endsection
