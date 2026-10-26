@extends('layouts.admin', ['title' => 'Question Bank'])

@section('header', 'Question Bank & Compatibility Weights')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-4 rounded-lg border border-[#E0D7D0] shadow-sm flex flex-col sm:flex-row justify-between items-center gap-4">
        <form method="GET" action="{{ route('admin.questions.index') }}" class="flex items-center space-x-3 w-full sm:w-auto">
            <select name="dimension" class="text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
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
        <table class="w-full text-left text-xs">
            <thead class="bg-[#FAF6EE] border-b border-[#E0D7D0] text-[#8A7972] uppercase text-[10px] tracking-wider font-semibold">
                <tr>
                    <th class="py-3 px-4">#</th>
                    <th class="py-3 px-4">Dimension</th>
                    <th class="py-3 px-4">Question Text</th>
                    <th class="py-3 px-4">Options</th>
                    <th class="py-3 px-4">Weight</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F0EAE4] text-[#2D2420]">
                @foreach($questions as $q)
                    <tr class="hover:bg-[#FAF6EE]/80 transition-colors">
                        <td class="py-3 px-4 font-mono text-[#8A7972]">{{ $q->order_index }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-[#783C46]/10 text-[#783C46]">
                                {{ ucwords(str_replace('_', ' ', $q->dimension)) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-serif font-bold text-sm max-w-md truncate">
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

    <div>
        {{ $questions->links() }}
    </div>
</div>
@endsection
