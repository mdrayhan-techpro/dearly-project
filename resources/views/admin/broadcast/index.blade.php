@extends('layouts.admin', ['title' => 'Postmaster Broadcast & Mailroom'])

@section('header', 'Postmaster General Broadcast & Postal Mailroom')

@section('content')
<div class="space-y-8" x-data="{ activeTab: 'broadcast' }">
    <!-- Sub-navigation tabs -->
    <div class="flex items-center space-x-3 border-b border-[#E0D7D0] pb-2 text-xs font-semibold">
        <button type="button" @click="activeTab = 'broadcast'"
            :class="activeTab === 'broadcast' ? 'bg-[#783C46] text-white' : 'bg-white text-[#5A4A42] hover:bg-[#FAF6EE] border border-[#E0D7D0]'"
            class="px-4 py-2 rounded-full transition-colors">
            Postmaster Dispatch Announcement
        </button>
        <button type="button" @click="activeTab = 'email_preview'"
            :class="activeTab === 'email_preview' ? 'bg-[#783C46] text-white' : 'bg-white text-[#5A4A42] hover:bg-[#FAF6EE] border border-[#E0D7D0]'"
            class="px-4 py-2 rounded-full transition-colors flex items-center space-x-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span>Transactional Email Template Previewer</span>
        </button>
    </div>

    <!-- Tab 1: Postmaster Broadcast -->
    <div x-show="activeTab === 'broadcast'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Dispatch Composer -->
            <div class="lg:col-span-7 bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-4">
                <div class="border-b border-[#E0D7D0] pb-3">
                    <h3 class="font-serif font-bold text-base text-[#2D2420]">Compose Official Postmaster Dispatch</h3>
                    <p class="text-xs text-[#8A7972]">Send an authoritative announcement from the Postmaster General to the Dearly community.</p>
                </div>

                <form action="{{ route('admin.broadcast.send') }}" method="POST" class="space-y-4" onsubmit="return confirm('Send this official broadcast to the selected audience?');">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-[#2D2420] mb-1">Missive Subject / Title <span class="text-red-600">*</span></label>
                        <input type="text" name="title" required placeholder="e.g. Autumn Equinox at the Post Office & Community Notice"
                            class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] focus:border-[#783C46] focus:ring-[#783C46] p-2.5">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#2D2420] mb-1">Target Audience</label>
                            <select name="audience" class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] p-2 text-[#2D2420]">
                                <option value="all">All Active Members ({{ $activeUsersCount }} members)</option>
                                <option value="en">English Language Members</option>
                                <option value="bn">বাংলা (Bengali) Language Members</option>
                                <option value="es">Español Language Members</option>
                                <option value="fr">Français Language Members</option>
                                <option value="de">Deutsch Language Members</option>
                                <option value="ja">日本語 Language Members</option>
                                <option value="ar">العربية Language Members</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#2D2420] mb-1">Delivery Method</label>
                            <select name="dispatch_method" class="w-full text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] p-2 text-[#2D2420]">
                                <option value="both">Both (Sealed Letter &amp; In-App Notification)</option>
                                <option value="letter">Official Sealed Letter to Postbox Only</option>
                                <option value="notification">Quick In-App Notification Bell Only</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#2D2420] mb-1">Dispatch Content Body <span class="text-red-600">*</span></label>
                        <textarea name="body" rows="6" required placeholder="Write your letter to the members of the Dearly sanctuary in slow, thoughtful prose..."
                            class="w-full text-xs font-serif leading-relaxed rounded border-[#D8CFC8] bg-[#FAF8F5] focus:border-[#783C46] focus:ring-[#783C46] p-3"></textarea>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 bg-[#783C46] text-white text-xs font-semibold uppercase tracking-wider rounded hover:bg-[#5C2E36] transition-colors shadow-sm">
                            Dispatch to Postbox Fleet
                        </button>
                    </div>
                </form>
            </div>

            <!-- Past Broadcasts Log -->
            <div class="lg:col-span-5 bg-white p-6 rounded-lg border border-[#E0D7D0] shadow-sm space-y-4">
                <div class="border-b border-[#E0D7D0] pb-3">
                    <h3 class="font-serif font-bold text-base text-[#2D2420]">Historical Postmaster Dispatches</h3>
                    <p class="text-xs text-[#8A7972]">Audit record of previously issued broadcast announcements.</p>
                </div>

                <div class="space-y-3 max-h-[460px] overflow-y-auto">
                    @forelse($recentBroadcasts as $b)
                        <div class="p-3 bg-[#FAF8F5] rounded border border-[#E0D7D0] space-y-1 text-xs">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-[#2D2420]">{{ $b->changes['title'] ?? 'Postmaster Announcement' }}</span>
                                <span class="text-[10px] text-[#8A7972]">{{ $b->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-[10px] text-[#8A7972]">
                                <span>Audience: <strong class="text-[#783C46] uppercase">{{ $b->changes['audience'] ?? 'all' }}</strong></span>
                                <span>•</span>
                                <span>Delivered: <strong>{{ $b->changes['recipients_count'] ?? 0 }} members</strong></span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-[#8A7972] italic py-8 text-center">No broadcast announcements issued yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 2: Interactive Transactional Email Previewer -->
    <div x-show="activeTab === 'email_preview'" class="space-y-4" x-cloak>
        <div class="bg-white p-5 rounded-lg border border-[#E0D7D0] shadow-sm flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center space-x-2">
                    <label class="text-xs font-semibold text-[#2D2420]">Select Mailable:</label>
                    <select id="previewMailType" onchange="refreshEmailPreview()" class="text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] py-1.5 px-3">
                        <option value="new_letter">New Letter Arrived (NewLetterMail)</option>
                        <option value="letter_read">Read Receipt Acknowledgment (LetterReadMail)</option>
                        <option value="match_found">Kindred Match Discovered (MatchFoundMail)</option>
                    </select>
                </div>

                <div class="flex items-center space-x-2">
                    <label class="text-xs font-semibold text-[#2D2420]">Language / Locale:</label>
                    <select id="previewLocale" onchange="refreshEmailPreview()" class="text-xs rounded border-[#D8CFC8] bg-[#FAF8F5] py-1.5 px-3">
                        <option value="en">English (en)</option>
                        <option value="bn">বাংলা (bn)</option>
                        <option value="es">Español (es)</option>
                        <option value="fr">Français (fr)</option>
                        <option value="de">Deutsch (de)</option>
                        <option value="ja">日本語 (ja)</option>
                        <option value="ar">العربية (ar - RTL)</option>
                    </select>
                </div>
            </div>

            <button type="button" onclick="refreshEmailPreview()" class="px-4 py-1.5 bg-[#2A1F1B] text-[#B79A61] hover:text-white text-xs font-semibold rounded">
                Refresh Preview
            </button>
        </div>

        <!-- Live Email Iframe Container -->
        <div class="bg-gray-100 p-4 rounded-lg border border-[#E0D7D0] flex justify-center shadow-inner">
            <div class="w-full max-w-2xl bg-white shadow-xl rounded-lg overflow-hidden border border-[#D8CFC8]">
                <div class="bg-[#FAF8F5] px-4 py-2 border-b border-[#E0D7D0] flex items-center justify-between text-[11px] text-[#8A7972]">
                    <span class="font-mono">From: postal-sanctuary@dearly.app</span>
                    <span id="previewLabel" class="font-semibold text-[#783C46]">Rendered HTML Email</span>
                </div>
                <iframe id="emailPreviewFrame" src="{{ route('admin.broadcast.preview-email', ['type' => 'new_letter', 'locale' => 'en']) }}"
                    class="w-full h-[650px] border-none bg-white"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    function refreshEmailPreview() {
        const type = document.getElementById('previewMailType').value;
        const locale = document.getElementById('previewLocale').value;
        const iframe = document.getElementById('emailPreviewFrame');
        iframe.src = '/admin/broadcast/preview-email?type=' + encodeURIComponent(type) + '&locale=' + encodeURIComponent(locale);
        document.getElementById('previewLabel').textContent = type + ' (' + locale + ')';
    }
</script>
@endsection
