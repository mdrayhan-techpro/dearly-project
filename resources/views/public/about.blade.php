@extends('layouts.app', ['title' => 'About the Dearly Post Office'])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="paper-sheet p-8 sm:p-12 rounded-xl border border-[#E9DDC7] shadow-paper">
        <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">Our Origin & Ethos</span>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#342A25] mt-2 mb-6">
            The Lost Art of the Slow Letter
        </h1>

        <div class="prose prose-stone text-sm leading-relaxed text-[#342A25] space-y-4">
            <p>
                In the late decades of the twentieth century, correspondence had a rhythm dictated by distance and paper. One sat at a desk with a warm drink, dipped a pen in ink or rolled a fresh sheet into a typewriter, and poured hours of quiet thought into pages destined for someone across mountains or oceans.
            </p>
            <p>
                The days between dispatch and reply were not empty silence—they were filled with joyful anticipation. You knew that somewhere out in the world, your words were traveling in a mailbag, being carried onto trains or ferries, and waiting to be placed into a mailbox.
            </p>
            <p>
                Modern communication has given us instantaneous reach at the expense of intimacy. Notifications arrive like alarms, typing dots demand immediate replies, and messages have dwindled into brief, distracted bursts.
            </p>
            <p>
                <strong>Dearly</strong> was created as a peaceful antidote. We have preserved the emotional rituals of traditional pen friendships—personalized stationery, collectible postage stamps, digital wax seals, and patient delivery cadences—while harnessing modern cryptography and server-side compatibility matching to connect kindred spirits safely across the globe.
            </p>

            <div class="border-t border-[#E9DDC7] pt-6 mt-8 flex items-center justify-between">
                <div>
                    <p class="font-handwriting text-3xl text-[#783C46]">The Postmaster</p>
                    <p class="text-xs text-[#B79A61]">The Dearly Sanctuary</p>
                </div>
                <div class="postal-mark px-4 py-1">
                    Archival Record
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
