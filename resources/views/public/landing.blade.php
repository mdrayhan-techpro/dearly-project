@extends('layouts.app', ['title' => 'Letters Find Their Way — Vintage Digital Pen-Pal Sanctuary'])

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden py-16 md:py-24 border-b border-[#E9DDC7]/80 bg-gradient-to-b from-[#FAF6EE] to-[#F6F0E5]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Left Headline & CTAs -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full border border-[#B79A61]/50 bg-[#FAF6EE] shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-[#783C46] animate-pulse"></span>
                    <span class="text-xs font-serif tracking-widest uppercase text-[#783C46] font-semibold">The Slow Digital Post Office</span>
                </div>

                <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-[#342A25] leading-tight">
                    Somewhere in the world, someone is waiting to hear from you.
                </h1>

                <p class="text-lg sm:text-xl text-[#5A4A42] font-normal leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    Rediscover the beautiful art of friendship, one letter at a time. No endless feeds, no instant messaging pressure—just thoughtful words on vintage stationery, sealed with digital wax.
                </p>

                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center lg:justify-start space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ auth()->check() ? route('discovery.choose-length') : route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-sm font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md shadow-md transition-all transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Find My Pen Friend
                    </a>
                    <a href="{{ route('how-it-works') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-sm font-semibold uppercase tracking-wider text-[#342A25] bg-[#FAF6EE] hover:bg-[#E9DDC7] border border-[#B79A61]/60 rounded-md shadow-sm transition-all">
                        Explore the Post Office
                    </a>
                </div>

                <!-- Privacy Trust Pill -->
                <div class="pt-2 flex items-center justify-center lg:justify-start space-x-6 text-xs text-[#5A4A42]">
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-[#405B4B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>Private Mailing Codes</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-[#405B4B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>No Email Disclosure</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-[#405B4B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>90%+ Compatibility Engine</span>
                    </div>
                </div>
            </div>

            <!-- Right Interactive Illustrated Vintage Desk & Envelope -->
            <div class="lg:col-span-5 relative flex justify-center">
                <div class="relative w-full max-w-md transform rotate-1 hover:rotate-0 transition-transform duration-500">
                    <!-- Letter Paper Sheet Layer -->
                    <div class="paper-sheet p-8 rounded-lg shadow-paper relative border border-[#E9DDC7]">
                        <!-- Postage Stamp top-right -->
                        <div class="absolute top-6 right-6">
                            <div class="postage-stamp w-14 h-16 p-1 flex flex-col items-center justify-between text-center">
                                <span class="text-[8px] font-mono text-[#B79A61] uppercase tracking-wider">Airmail</span>
                                <div class="text-[#783C46] text-xl">❦</div>
                                <span class="text-[8px] font-bold text-[#342A25]">25¢</span>
                            </div>
                            <div class="postal-mark absolute -bottom-2 -left-4 px-2 py-0.5 text-[8px]">
                                Oct 26
                            </div>
                        </div>

                        <!-- Date & Greeting -->
                        <p class="text-xs font-mono text-[#B79A61] mb-4">Postmark: Edinburgh, Scotland</p>
                        <h3 class="font-handwriting text-3xl text-[#342A25] mb-4">Dearest Friend,</h3>

                        <p class="font-serif text-sm leading-relaxed text-[#342A25] mb-4">
                            "The autumn rain has been drumming against my attic window all morning. I steeped a warm pot of black tea and found myself wondering how the changing season feels where you are..."
                        </p>

                        <div class="border-t border-[#E9DDC7] pt-4 flex justify-between items-end">
                            <div>
                                <p class="text-[11px] text-[#5A4A42]">Addressed to:</p>
                                <p class="font-mono text-xs font-bold text-[#783C46]">DEARLY-7K9M-2Q8P</p>
                            </div>
                            <div class="wax-seal w-10 h-10 text-xs shadow-seal font-serif font-bold">
                                D
                            </div>
                        </div>
                    </div>

                    <!-- Decorative postal envelope backdrop -->
                    <div class="absolute -bottom-4 -left-4 w-full h-full bg-[#EFE6D5] rounded-lg -z-10 border border-[#D6C7AE] shadow-md transform -rotate-2"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Genuine Community Stats (Honest collected statistics, no fabricated counts) -->
<section class="py-10 bg-[#FAF6EE] border-b border-[#E9DDC7]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
            <div class="p-4 bg-[#F6F0E5] rounded-lg border border-[#E9DDC7]/60">
                <span class="block font-serif text-3xl font-bold text-[#783C46]">{{ number_format($stats['letters_delivered']) }}</span>
                <span class="text-xs uppercase tracking-wider text-[#5A4A42] font-medium">Letters Dispatched & Delivered</span>
            </div>
            <div class="p-4 bg-[#F6F0E5] rounded-lg border border-[#E9DDC7]/60">
                <span class="block font-serif text-3xl font-bold text-[#405B4B]">{{ number_format($stats['active_pen_friends']) }}</span>
                <span class="text-xs uppercase tracking-wider text-[#5A4A42] font-medium">Verified Postal Members</span>
            </div>
            <div class="p-4 bg-[#F6F0E5] rounded-lg border border-[#E9DDC7]/60">
                <span class="block font-serif text-3xl font-bold text-[#B79A61]">{{ number_format($stats['matches_found']) }}</span>
                <span class="text-xs uppercase tracking-wider text-[#5A4A42] font-medium">Kindred Friendships Matched</span>
            </div>
        </div>
    </div>
</section>

<!-- How Dearly Works (4 Illustrated Steps) -->
<section class="py-20 bg-[#F6F0E5]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">The Postal Journey</span>
            <h2 class="font-serif text-3xl sm:text-4xl font-bold text-[#342A25] mt-2">
                How correspondence finds its rhythm.
            </h2>
            <p class="text-sm text-[#5A4A42] mt-3">
                Experience a ritual where every word matters and letters are collectibles of human thought.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Step 1 -->
            <div class="bg-[#FAF6EE] p-6 rounded-lg border border-[#E9DDC7] shadow-sm relative group hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-full bg-[#E9DDC7] text-[#783C46] font-serif font-bold text-base flex items-center justify-center mb-4">
                    1
                </div>
                <h3 class="font-serif text-lg font-bold text-[#342A25] mb-2">Discovery Questionnaire</h3>
                <p class="text-xs text-[#5A4A42] leading-relaxed">
                    Choose 20, 30, 40, or 50 single-answer questions exploring your communication style, introversion, routines, and values.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="bg-[#FAF6EE] p-6 rounded-lg border border-[#E9DDC7] shadow-sm relative group hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-full bg-[#E9DDC7] text-[#783C46] font-serif font-bold text-base flex items-center justify-center mb-4">
                    2
                </div>
                <h3 class="font-serif text-lg font-bold text-[#342A25] mb-2">90%+ Matching Engine</h3>
                <p class="text-xs text-[#5A4A42] leading-relaxed">
                    Our server-side weighted scoring algorithm calculates deep compatibility, presenting kindred spirits with common passions.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="bg-[#FAF6EE] p-6 rounded-lg border border-[#E9DDC7] shadow-sm relative group hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-full bg-[#E9DDC7] text-[#783C46] font-serif font-bold text-base flex items-center justify-center mb-4">
                    3
                </div>
                <h3 class="font-serif text-lg font-bold text-[#342A25] mb-2">Fold, Stamp & Seal</h3>
                <p class="text-xs text-[#5A4A42] leading-relaxed">
                    Compose on authentic vintage stationery, fold your letter, pick collectible postage stamps, and apply a personalized wax seal.
                </p>
            </div>

            <!-- Step 4 -->
            <div class="bg-[#FAF6EE] p-6 rounded-lg border border-[#E9DDC7] shadow-sm relative group hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-full bg-[#E9DDC7] text-[#783C46] font-serif font-bold text-base flex items-center justify-center mb-4">
                    4
                </div>
                <h3 class="font-serif text-lg font-bold text-[#342A25] mb-2">Mailbox Delivery</h3>
                <p class="text-xs text-[#5A4A42] leading-relaxed">
                    Letters travel to your recipient's private post office box. Authentic read receipts trigger only when they open the sealed envelope.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Stationery & Collectibles Showcase -->
<section class="py-20 bg-[#FAF6EE] border-t border-[#E9DDC7]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">Postal Craftsmanship</span>
            <h2 class="font-serif text-3xl sm:text-4xl font-bold text-[#342A25] mt-2">
                Tactile papers, rare stamps, and wax seals.
            </h2>
            <p class="text-sm text-[#5A4A42] mt-3">
                Every letter is unique. Choose from handcrafted themes, collectible vintage postage, and embossed seals.
            </p>
        </div>

        <!-- Themes preview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
            @foreach($themes as $theme)
                <div class="p-5 rounded-lg border border-[#E9DDC7] shadow-sm text-center" style="background-color: {{ $theme->background_css }}; color: {{ $theme->text_color }};">
                    <div class="text-2xl mb-2">✉</div>
                    <h4 class="font-serif font-bold text-sm">{{ $theme->name }}</h4>
                    <p class="text-[11px] opacity-75 mt-1">Archival Stationery</p>
                </div>
            @endforeach
        </div>

        <!-- Stamps row -->
        <div class="bg-[#F6F0E5] p-6 rounded-xl border border-[#E9DDC7] flex flex-wrap items-center justify-center gap-6">
            @foreach($stamps as $stamp)
                <div class="postage-stamp w-16 h-20 p-1 flex flex-col items-center justify-between text-center bg-[#FAF6EE]">
                    <span class="text-[7px] font-mono text-[#B79A61] uppercase">{{ substr($stamp->theme_category, 0, 7) }}</span>
                    <span class="text-lg text-[#783C46]">❦</span>
                    <span class="text-[8px] font-bold text-[#342A25]">{{ $stamp->name }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Privacy Promise Section -->
<section class="py-20 bg-[#2A1F1B] text-[#E5DCD6]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-6 space-y-4">
                <span class="text-xs uppercase tracking-widest text-[#B79A61] font-semibold">Privacy By Design</span>
                <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white leading-tight">
                    Your personal identity remains private forever.
                </h2>
                <p class="text-sm text-[#C5B8B1] leading-relaxed">
                    Dearly is engineered so you can share your deepest reflections without ever compromising your real-world safety or private credentials.
                </p>
                <div class="pt-4 space-y-3 text-xs">
                    <div class="flex items-start space-x-3">
                        <span class="text-[#B79A61] text-base">✓</span>
                        <p><strong class="text-white">Pseudonymous Nicknames:</strong> Other members only ever see your chosen nickname.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="text-[#B79A61] text-base">✓</span>
                        <p><strong class="text-white">Hidden Private Emails:</strong> Your email address and Google account IDs are never exposed.</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="text-[#B79A61] text-base">✓</span>
                        <p><strong class="text-white">Recipient-Specific Codes:</strong> Letters are addressed with postal codes (e.g., DEARLY-7K9M-2Q8P).</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <span class="text-[#B79A61] text-base">✓</span>
                        <p><strong class="text-white">Daily Sending Caps:</strong> 5 letters per day prevents spam and safeguards intentional correspondence.</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-6 bg-[#342722] p-8 rounded-xl border border-[#4E3B33] shadow-xl">
                <div class="border-b border-[#4E3B33] pb-4 mb-4">
                    <h3 class="font-serif text-lg font-bold text-white">What Correspondents See</h3>
                    <p class="text-xs text-[#B79A61]">Sample Public Postal Card</p>
                </div>
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-[#4E3B33]/60">
                        <span class="text-[#C5B8B1]">Public Nickname</span>
                        <span class="font-bold text-white">EleanorVance</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-[#4E3B33]/60">
                        <span class="text-[#C5B8B1]">Postal Address</span>
                        <span class="font-mono text-[#B79A61]">DEARLY-ELNR-7K9M</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-[#4E3B33]/60">
                        <span class="text-[#C5B8B1]">Age Group</span>
                        <span class="text-white">28 (Adult Verified)</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-[#4E3B33]/60">
                        <span class="text-[#C5B8B1]">Shared Passions</span>
                        <span class="text-white">Reading, Botany, Fountain Pens</span>
                    </div>
                    <div class="flex justify-between py-1.5 text-emerald-400 font-semibold">
                        <span>Personal Email & Real Identity</span>
                        <span class="uppercase tracking-wider text-[10px]">Strictly Redacted / Hidden</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQs Section -->
<section class="py-20 bg-[#F6F0E5]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">Questions & Answers</span>
            <h2 class="font-serif text-3xl font-bold text-[#342A25] mt-2">
                Frequently Asked Questions
            </h2>
        </div>

        <div class="space-y-4">
            @foreach($faqs as $faq)
                <div class="bg-[#FAF6EE] p-6 rounded-lg border border-[#E9DDC7] shadow-sm">
                    <h3 class="font-serif text-base font-bold text-[#342A25] mb-2">{{ $faq['q'] }}</h3>
                    <p class="text-xs text-[#5A4A42] leading-relaxed">{{ $faq['a'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Final CTA Section -->
<section class="py-20 bg-[#FAF6EE] border-t border-[#E9DDC7] text-center">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="wax-seal w-14 h-14 mx-auto text-xl font-serif font-bold shadow-seal">
            D
        </div>
        <h2 class="font-serif text-3xl sm:text-4xl font-bold text-[#342A25]">
            Open your digital mailbox today.
        </h2>
        <p class="text-sm text-[#5A4A42] max-w-xl mx-auto leading-relaxed">
            Begin the journey of slow, meaningful correspondence. Discover someone whose thoughts align with yours and send your first sealed letter.
        </p>
        <div class="pt-2">
            <a href="{{ auth()->check() ? route('discovery.choose-length') : route('register') }}" class="inline-flex items-center px-8 py-4 text-sm font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md shadow-md transition-all transform hover:-translate-y-0.5">
                Join the Post Office
            </a>
        </div>
    </div>
</section>
@endsection
