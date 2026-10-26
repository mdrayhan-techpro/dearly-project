@extends('layouts.app', ['title' => 'Unfinished Drafts'])

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-[#E9DDC7] pb-6 mb-8 gap-4">
        <div>
            <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">Postal Desk</span>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#342A25] mt-1">
                Your Drafts
            </h1>
            <p class="text-xs text-[#5A4A42] mt-1">Little thoughts waiting to become sealed letters.</p>
        </div>

        <a href="{{ route('letters.compose') }}" class="px-5 py-2 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm transition-all">
            Compose New &rarr;
        </a>
    </div>

    @if($letters->isEmpty())
        <div class="paper-sheet p-12 rounded-xl border border-[#E9DDC7] shadow-paper text-center max-w-lg mx-auto space-y-3">
            <div class="text-3xl text-[#B79A61]">✎</div>
            <h2 class="font-serif text-lg font-bold text-[#342A25]">Your desk is clear</h2>
            <p class="text-xs text-[#5A4A42] leading-relaxed">
                You have no unfinished drafts saved at your desk.
            </p>
            <div class="pt-2">
                <a href="{{ route('letters.compose') }}" class="px-5 py-2.5 text-xs font-semibold uppercase text-white bg-[#783C46] hover:bg-[#582A32] rounded">
                    Start a New Draft
                </a>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($letters as $letter)
                <div class="paper-sheet p-6 rounded-xl border border-[#E9DDC7] shadow-paper flex flex-col justify-between hover:border-[#783C46] transition-all">
                    <div>
                        <div class="flex items-center justify-between text-[10px] text-[#B79A61] font-mono mb-2">
                            <span>Draft</span>
                            <span>{{ $letter->updated_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="font-serif font-bold text-base text-[#342A25] mb-2 truncate">
                            {{ $letter->title }}
                        </h3>
                        <p class="text-xs text-[#5A4A42] line-clamp-3 leading-relaxed mb-4">
                            {{ $letter->body }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-[#E9DDC7] flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <!-- Duplicate -->
                            <form method="POST" action="{{ route('drafts.duplicate', $letter->id) }}">
                                @csrf
                                <button type="submit" class="text-xs text-[#5A4A42] hover:text-[#783C46]" title="Duplicate draft">
                                    Copy
                                </button>
                            </form>
                            <span>•</span>
                            <!-- Discard -->
                            <form method="POST" action="{{ route('drafts.destroy', $letter->id) }}" onsubmit="return confirm('Discard this draft permanently?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-[#783C46] hover:underline" title="Discard draft">
                                    Discard
                                </button>
                            </form>
                        </div>

                        <a href="{{ route('drafts.edit', $letter->id) }}" class="px-4 py-1.5 text-xs font-semibold text-white bg-[#783C46] hover:bg-[#582A32] rounded">
                            Continue &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $letters->links() }}
        </div>
    @endif
</div>
@endsection
