@extends('layouts.app', ['title' => 'Notifications'])

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between border-b border-[#E9DDC7] pb-4 mb-6">
        <div>
            <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">Post Office Bells</span>
            <h1 class="font-serif text-2xl font-bold text-[#342A25]">
                Postal Notifications
            </h1>
        </div>

        <form method="POST" action="{{ route('notifications.read-all') }}">
            @csrf
            <button type="submit" class="text-xs text-[#783C46] hover:underline font-semibold">
                Mark All as Read
            </button>
        </form>
    </div>

    @if($notifications->isEmpty())
        <div class="paper-sheet p-8 rounded-xl border border-[#E9DDC7] shadow-paper text-center space-y-2">
            <p class="font-serif text-base text-[#342A25]">No notifications at this time.</p>
            <p class="text-xs text-[#5A4A42]">Updates about new letters, read receipts, and matches will arrive here.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($notifications as $notif)
                <div class="paper-sheet p-4 rounded-lg border border-[#E9DDC7] shadow-sm flex items-start justify-between gap-4 {{ !$notif->is_read ? 'bg-[#FAF6EE] border-l-4 border-l-[#783C46]' : 'opacity-85' }}">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <h4 class="font-serif font-bold text-sm text-[#342A25]">{{ $notif->title }}</h4>
                            <span class="text-[10px] text-[#B79A61] font-mono">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-[#5A4A42] leading-relaxed">{{ $notif->message }}</p>
                    </div>

                    <div class="flex items-center space-x-2 flex-shrink-0">
                        @if($notif->action_url)
                            <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-white bg-[#783C46] hover:bg-[#582A32] rounded">
                                    View &rarr;
                                </button>
                            </form>
                        @elseif(!$notif->is_read)
                            <form method="POST" action="{{ route('notifications.read', $notif->id) }}">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-[11px] text-[#5A4A42] hover:text-[#783C46]">
                                    Mark Read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
