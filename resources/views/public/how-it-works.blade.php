@extends('layouts.app', ['title' => 'How Dearly Works'])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="paper-sheet p-8 sm:p-12 rounded-xl border border-[#E9DDC7] shadow-paper">
        <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">The Postal Manual</span>
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-[#342A25] mt-2 mb-6">
            How Correspondence Finds Its Way
        </h1>

        <div class="space-y-8 text-sm text-[#342A25]">
            <div class="flex items-start space-x-4">
                <div class="w-8 h-8 rounded-full bg-[#E9DDC7] text-[#783C46] font-serif font-bold text-sm flex items-center justify-center flex-shrink-0 mt-0.5">
                    1
                </div>
                <div>
                    <h3 class="font-serif font-bold text-base text-[#342A25] mb-1">Establish Your Postal Identity</h3>
                    <p class="text-xs text-[#5A4A42] leading-relaxed">
                        Sign in securely with Google OAuth or email. Choose your unique public pseudonym (e.g. <em>VintageDreamer</em>). You will automatically receive a private mailing code (e.g. <code>DEARLY-7K9M-2Q8P</code>). Your personal email address and internal account credentials remain strictly hidden.
                    </p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-8 h-8 rounded-full bg-[#E9DDC7] text-[#783C46] font-serif font-bold text-sm flex items-center justify-center flex-shrink-0 mt-0.5">
                    2
                </div>
                <div>
                    <h3 class="font-serif font-bold text-base text-[#342A25] mb-1">Take the Discovery Questionnaire</h3>
                    <p class="text-xs text-[#5A4A42] leading-relaxed">
                        Select your preferred questionnaire length: 20, 30, 40, or 50 single-answer questions covering 17 deep dimensions including introversion, emotional openness, reading habits, humor, and daily routines. Your answers are saved automatically as you progress.
                    </p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-8 h-8 rounded-full bg-[#E9DDC7] text-[#783C46] font-serif font-bold text-sm flex items-center justify-center flex-shrink-0 mt-0.5">
                    3
                </div>
                <div>
                    <h3 class="font-serif font-bold text-base text-[#342A25] mb-1">Kindred Spirit Matching (90%+ Threshold)</h3>
                    <p class="text-xs text-[#5A4A42] leading-relaxed">
                        Our server-side weighted algorithm calculates dimensional compatibility against all completed members. Kindred spirits meeting the 90%+ compatibility threshold appear in your match discovery desk, highlighting shared passions and complementary traits.
                    </p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-8 h-8 rounded-full bg-[#E9DDC7] text-[#783C46] font-serif font-bold text-sm flex items-center justify-center flex-shrink-0 mt-0.5">
                    4
                </div>
                <div>
                    <h3 class="font-serif font-bold text-base text-[#342A25] mb-1">The Letter Studio: Compose, Fold & Seal</h3>
                    <p class="text-xs text-[#5A4A42] leading-relaxed">
                        Write in an uncluttered, atmospheric editor with draft autosaving. When finished, enter the folding sequence: choose your envelope, affix a collectible vintage postage stamp, and stamp your personal wax seal. Enter your recipient’s mailing code and confirm dispatch.
                    </p>
                </div>
            </div>

            <div class="flex items-start space-x-4">
                <div class="w-8 h-8 rounded-full bg-[#E9DDC7] text-[#783C46] font-serif font-bold text-sm flex items-center justify-center flex-shrink-0 mt-0.5">
                    5
                </div>
                <div>
                    <h3 class="font-serif font-bold text-base text-[#342A25] mb-1">Mailbox Arrival & Authentic Read Receipts</h3>
                    <p class="text-xs text-[#5A4A42] leading-relaxed">
                        The letter is safely placed into the recipient's post office box. Unopened envelopes await a quiet moment. Only when the recipient legitimately breaks the digital seal and renders the letter content does an authentic read receipt notify the sender: <em>"Your letter has been read."</em>
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ auth()->check() ? route('discovery.choose-length') : route('register') }}" class="inline-flex items-center px-6 py-3 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md shadow-sm transition-all">
                Start Your First Letter
            </a>
        </div>
    </div>
</div>
@endsection
