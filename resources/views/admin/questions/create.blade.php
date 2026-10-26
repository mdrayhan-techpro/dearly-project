@extends('layouts.admin', ['title' => 'Create Question'])

@section('header', 'Add Discovery Question')

@section('content')
<div class="max-w-3xl bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-6">
    <div class="flex items-center justify-between border-b border-[#E0D7D0] pb-4">
        <div>
            <h2 class="font-serif text-xl font-bold text-[#2D2420]">Create MCQ Question</h2>
            <p class="text-xs text-[#8A7972]">Each question must belong to a dimension and have exactly 5 options (A–E).</p>
        </div>
        <a href="{{ route('admin.questions.index') }}" class="text-xs text-[#783C46] hover:underline">&larr; Back</a>
    </div>

    <form method="POST" action="{{ route('admin.questions.store') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Dimension</label>
                <select name="dimension" required class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                    @foreach($dimensions as $dimKey => $dimName)
                        <option value="{{ $dimKey }}">{{ $dimName }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Compatibility Weight</label>
                <input type="number" step="0.1" name="weight" value="1.0" min="0.1" max="5.0" required
                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-[#2D2420] mb-1">Question Prompt</label>
            <textarea name="question_text" rows="3" required placeholder="When a quiet afternoon arrives..."
                class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]"></textarea>
        </div>

        <div class="border-t border-[#E0D7D0] pt-4 space-y-3">
            <h4 class="font-serif font-bold text-sm text-[#2D2420]">Answer Options (Trait Scale 1 through 5)</h4>
            @foreach(['A', 'B', 'C', 'D', 'E'] as $idx => $key)
                <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-full bg-[#783C46] text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                        {{ $key }}
                    </span>
                    <input type="text" name="options[]" required placeholder="Option {{ $key }} text..."
                        class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                </div>
            @endforeach
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm">
                Save & Publish Question
            </button>
        </div>
    </form>
</div>
@endsection
