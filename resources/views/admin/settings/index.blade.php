@extends('layouts.admin', ['title' => 'Platform Settings'])

@section('header', 'Global Platform Settings & Multi-Language Editorial Controls')

@section('content')
<div class="max-w-5xl space-y-8">
    <form method="POST" action="{{ route('admin.settings.update') }}" id="settings-form" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Core Configuration Card -->
        <div class="bg-white p-6 sm:p-8 rounded-lg border border-[#E0D7D0] shadow-sm space-y-8">
            <!-- Branding -->
            <div class="space-y-4">
                <h3 class="font-serif font-bold text-base text-[#2D2420] border-b border-[#E0D7D0] pb-2">Platform Identity</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#2D2420] mb-1">Application Name</label>
                        <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'Dearly') }}" required
                            class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE] focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#2D2420] mb-1">Brand Tagline</label>
                        <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? 'Letters Find Their Way.') }}" required
                            class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE] focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#2D2420] mb-1">Default Platform Language</label>
                        <select name="default_language" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE] focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                            @foreach($supportedLocales as $code => $locale)
                                <option value="{{ $code }}" {{ ($settings['default_language'] ?? 'en') === $code ? 'selected' : '' }}>
                                    {{ $locale['native'] }} ({{ $locale['name'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#2D2420] mb-1">Official Postal Contact Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? 'postmaster@dearlyletters.com') }}" required
                            class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE] focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                    </div>
                </div>
            </div>

            <!-- Algorithm & Sending Limits -->
            <div class="space-y-4 border-t border-[#E0D7D0] pt-6">
                <h3 class="font-serif font-bold text-base text-[#2D2420] border-b border-[#E0D7D0] pb-2">Matching & Daily Postal Limits</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#2D2420] mb-1">Default Match Threshold (%)</label>
                        <input type="number" name="match_threshold" min="50" max="100" value="{{ old('match_threshold', $settings['match_threshold'] ?? 90) }}" required
                            class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                        <p class="text-[10px] text-[#8A7972] mt-1">Default 90%. Matches meeting this score qualify for pen-friend introduction.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#2D2420] mb-1">Max Letters Sent / Day</label>
                        <input type="number" name="max_letters_per_day" min="1" max="50" value="{{ old('max_letters_per_day', $settings['max_letters_per_day'] ?? 5) }}" required
                            class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                        <p class="text-[10px] text-[#8A7972] mt-1">Default 5 letters/day. Prevents spam and preserves slow correspondence.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#2D2420] mb-1">Max First Introductions / Day</label>
                        <input type="number" name="max_first_contacts_per_day" min="1" max="20" value="{{ old('max_first_contacts_per_day', $settings['max_first_contacts_per_day'] ?? 2) }}" required
                            class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                        <p class="text-[10px] text-[#8A7972] mt-1">Limits cold-outreach to 2 new pen friends daily.</p>
                    </div>
                </div>
            </div>

            <!-- Access Flags -->
            <div class="space-y-3 border-t border-[#E0D7D0] pt-6">
                <h3 class="font-serif font-bold text-base text-[#2D2420]">Access Flags</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-center space-x-3 text-xs cursor-pointer p-3 bg-[#FAF6EE] rounded-md border border-[#E0D7D0]">
                        <input type="checkbox" name="registration_enabled" value="1" {{ !empty($settings['registration_enabled']) ? 'checked' : '' }}
                            class="rounded text-[#783C46] focus:ring-[#783C46]/20">
                        <span class="font-semibold text-[#2D2420]">New Post Office Registrations Open</span>
                    </label>

                    <label class="flex items-center space-x-3 text-xs cursor-pointer p-3 bg-[#FAF6EE] rounded-md border border-[#E0D7D0]">
                        <input type="checkbox" name="maintenance_mode" value="1" {{ !empty($settings['maintenance_mode']) ? 'checked' : '' }}
                            class="rounded text-[#783C46] focus:ring-[#783C46]/20">
                        <span class="font-semibold text-red-800">Maintenance Mode Active (Public routes paused)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Multi-Language Editorial & Public Content Management Card -->
        <div class="bg-white p-6 sm:p-8 rounded-lg border border-[#E0D7D0] shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-[#E0D7D0] gap-4">
                <div>
                    <h3 class="font-serif font-bold text-lg text-[#2D2420] flex items-center space-x-2">
                        <span>Editorial Content & Translations</span>
                        <span class="text-xs font-sans font-normal px-2 py-0.5 bg-[#FAF6EE] border border-[#B79A61]/60 text-[#783C46] rounded-full">
                            Hybrid Multi-Locale
                        </span>
                    </h3>
                    <p class="text-xs text-[#8A7972] mt-1">
                        Edit content for About, How It Works, and Sanctuary Policies. Use the AI auto-translator or customize each language directly.
                    </p>
                </div>

                <!-- ✨ Auto-Generate Button -->
                <div>
                    <button type="button" id="btn-auto-translate" onclick="runAutoTranslate()"
                        class="inline-flex items-center space-x-2 px-4 py-2.5 text-xs font-semibold uppercase tracking-wider text-white bg-gradient-to-r from-[#783C46] to-[#943B4A] hover:from-[#5E2B35] hover:to-[#783C46] rounded-md shadow transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-[#783C46]/40">
                        <span id="translate-spinner" class="hidden animate-spin">⟳</span>
                        <span id="translate-icon">✨</span>
                        <span id="translate-label">Auto-Generate Other Languages</span>
                    </button>
                </div>
            </div>

            <!-- Translation Status Notification Banner -->
            <div id="translate-status" class="hidden p-3 rounded-md text-xs font-medium"></div>

            <!-- Language Switcher Tabs -->
            <div class="flex flex-wrap items-center gap-2 border-b border-[#E0D7D0] pb-2">
                @foreach($supportedLocales as $code => $locale)
                    <button type="button" onclick="switchLocaleTab('{{ $code }}')"
                        id="tab-btn-{{ $code }}"
                        class="locale-tab-btn px-4 py-2 text-xs font-semibold rounded-md border transition-all {{ $code === 'en' ? 'bg-[#783C46] text-white border-[#783C46]' : 'bg-[#FAF6EE] text-[#5A4A42] border-[#D6C7AE] hover:bg-[#E9DDC7]' }}">
                        {{ $locale['native'] }} <span class="opacity-75 uppercase text-[10px]">({{ $code }})</span>
                    </button>
                @endforeach
            </div>

            <!-- Tab Panels for Each Language -->
            @foreach($supportedLocales as $code => $locale)
                <div id="tab-panel-{{ $code }}" class="locale-panel space-y-6 {{ $code === 'en' ? '' : 'hidden' }}" {{ $locale['dir'] === 'rtl' ? 'dir="rtl"' : '' }}>
                    <div class="p-3 bg-[#FAF6EE] rounded border border-[#E9DDC7] flex items-center justify-between text-xs">
                        <span class="font-serif font-bold text-[#783C46]">
                            Editing Content in: {{ $locale['native'] }} ({{ $locale['name'] }})
                        </span>
                        <span class="text-[10px] text-[#8A7972] uppercase tracking-wider font-mono">
                            Direction: {{ strtoupper($locale['dir']) }}
                        </span>
                    </div>

                    <!-- About Page Fields -->
                    <div class="p-5 rounded-lg border border-[#E9DDC7] bg-[#FAF6EE]/40 space-y-4">
                        <div class="flex items-center justify-between border-b border-[#E9DDC7] pb-2">
                            <h4 class="font-serif font-bold text-sm text-[#342A25]">About Page Content</h4>
                            <span class="text-[10px] text-[#B79A61] uppercase tracking-wider font-mono">public/about</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#2D2420] mb-1">About Page Subtitle / Badge</label>
                                <input type="text" name="localized[{{ $code }}][about_badge]"
                                    id="input-{{ $code }}-about_badge"
                                    value="{{ old("localized.{$code}.about_badge", $localizedContent['about_badge'][$code] ?? '') }}"
                                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-white p-2">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-[#2D2420] mb-1">About Page Main Heading</label>
                                <input type="text" name="localized[{{ $code }}][about_heading]"
                                    id="input-{{ $code }}-about_heading"
                                    value="{{ old("localized.{$code}.about_heading", $localizedContent['about_heading'][$code] ?? '') }}"
                                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-white p-2">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#2D2420] mb-1">About Story Paragraphs (Nostalgic Ethos)</label>
                            <textarea name="localized[{{ $code }}][about_content]"
                                id="input-{{ $code }}-about_content"
                                rows="6"
                                class="w-full text-xs rounded-md border-[#D6C7AE] bg-white p-2 leading-relaxed">{{ old("localized.{$code}.about_content", $localizedContent['about_content'][$code] ?? '') }}</textarea>
                            <p class="text-[10px] text-[#8A7972] mt-1">Separate paragraphs with a blank line.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                            <div>
                                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Sign-off Title</label>
                                <input type="text" name="localized[{{ $code }}][about_postmaster_title]"
                                    id="input-{{ $code }}-about_postmaster_title"
                                    value="{{ old("localized.{$code}.about_postmaster_title", $localizedContent['about_postmaster_title'][$code] ?? '') }}"
                                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-white p-2">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Sign-off Subtitle</label>
                                <input type="text" name="localized[{{ $code }}][about_postmaster_sub]"
                                    id="input-{{ $code }}-about_postmaster_sub"
                                    value="{{ old("localized.{$code}.about_postmaster_sub", $localizedContent['about_postmaster_sub'][$code] ?? '') }}"
                                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-white p-2">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Postal Archival Mark</label>
                                <input type="text" name="localized[{{ $code }}][about_postal_mark]"
                                    id="input-{{ $code }}-about_postal_mark"
                                    value="{{ old("localized.{$code}.about_postal_mark", $localizedContent['about_postal_mark'][$code] ?? '') }}"
                                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-white p-2">
                            </div>
                        </div>
                    </div>

                    <!-- How It Works Fields -->
                    <div class="p-5 rounded-lg border border-[#E9DDC7] bg-[#FAF6EE]/40 space-y-4">
                        <div class="flex items-center justify-between border-b border-[#E9DDC7] pb-2">
                            <h4 class="font-serif font-bold text-sm text-[#342A25]">How It Works Page</h4>
                            <span class="text-[10px] text-[#B79A61] uppercase tracking-wider font-mono">public/how-it-works</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Badge</label>
                                <input type="text" name="localized[{{ $code }}][how_it_works_badge]"
                                    id="input-{{ $code }}-how_it_works_badge"
                                    value="{{ old("localized.{$code}.how_it_works_badge", $localizedContent['how_it_works_badge'][$code] ?? '') }}"
                                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-white p-2">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#2D2420] mb-1">Main Heading</label>
                                <input type="text" name="localized[{{ $code }}][how_it_works_heading]"
                                    id="input-{{ $code }}-how_it_works_heading"
                                    value="{{ old("localized.{$code}.how_it_works_heading", $localizedContent['how_it_works_heading'][$code] ?? '') }}"
                                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-white p-2">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#2D2420] mb-1">CTA Button Text</label>
                                <input type="text" name="localized[{{ $code }}][how_it_works_cta_text]"
                                    id="input-{{ $code }}-how_it_works_cta_text"
                                    value="{{ old("localized.{$code}.how_it_works_cta_text", $localizedContent['how_it_works_cta_text'][$code] ?? '') }}"
                                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-white p-2">
                            </div>
                        </div>
                    </div>

                    <!-- Sanctuary Policies & Charters -->
                    <div class="p-5 rounded-lg border border-[#E9DDC7] bg-[#FAF6EE]/40 space-y-4">
                        <div class="flex items-center justify-between border-b border-[#E9DDC7] pb-2">
                            <h4 class="font-serif font-bold text-sm text-[#342A25]">Sanctuary Policies & Charters</h4>
                            <span class="text-[10px] text-[#B79A61] uppercase tracking-wider font-mono">public/privacy, terms, guidelines</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#2D2420] mb-1">Privacy Policy</label>
                            <textarea name="localized[{{ $code }}][privacy_policy]"
                                id="input-{{ $code }}-privacy_policy"
                                rows="5"
                                class="w-full text-xs rounded-md border-[#D6C7AE] bg-white p-2 leading-relaxed font-mono text-[11px]">{{ old("localized.{$code}.privacy_policy", $localizedContent['privacy_policy'][$code] ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#2D2420] mb-1">Terms of Service</label>
                            <textarea name="localized[{{ $code }}][terms_of_service]"
                                id="input-{{ $code }}-terms_of_service"
                                rows="5"
                                class="w-full text-xs rounded-md border-[#D6C7AE] bg-white p-2 leading-relaxed font-mono text-[11px]">{{ old("localized.{$code}.terms_of_service", $localizedContent['terms_of_service'][$code] ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-[#2D2420] mb-1">Community Guidelines</label>
                            <textarea name="localized[{{ $code }}][community_guidelines]"
                                id="input-{{ $code }}-community_guidelines"
                                rows="5"
                                class="w-full text-xs rounded-md border-[#D6C7AE] bg-white p-2 leading-relaxed font-mono text-[11px]">{{ old("localized.{$code}.community_guidelines", $localizedContent['community_guidelines'][$code] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Sticky Submit Footer Bar -->
        <div class="p-4 bg-white rounded-lg border border-[#E0D7D0] shadow-sm flex items-center justify-between">
            <span class="text-xs text-[#8A7972]">
                Changes affect public routes immediately across all active sessions.
            </span>
            <button type="submit" class="px-8 py-3 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-md transition-all">
                Save Platform Settings & All Localized Content
            </button>
        </div>
    </form>
</div>

<script>
function switchLocaleTab(locale) {
    // Switch active tab button styling
    document.querySelectorAll('.locale-tab-btn').forEach(btn => {
        btn.classList.remove('bg-[#783C46]', 'text-white', 'border-[#783C46]');
        btn.classList.add('bg-[#FAF6EE]', 'text-[#5A4A42]', 'border-[#D6C7AE]');
    });

    const activeBtn = document.getElementById('tab-btn-' + locale);
    if (activeBtn) {
        activeBtn.classList.add('bg-[#783C46]', 'text-white', 'border-[#783C46]');
        activeBtn.classList.remove('bg-[#FAF6EE]', 'text-[#5A4A42]', 'border-[#D6C7AE]');
    }

    // Toggle panels
    document.querySelectorAll('.locale-panel').forEach(panel => {
        panel.classList.add('hidden');
    });

    const activePanel = document.getElementById('tab-panel-' + locale);
    if (activePanel) {
        activePanel.classList.remove('hidden');
    }
}

async function runAutoTranslate() {
    const btn = document.getElementById('btn-auto-translate');
    const spinner = document.getElementById('translate-spinner');
    const icon = document.getElementById('translate-icon');
    const label = document.getElementById('translate-label');
    const statusBox = document.getElementById('translate-status');

    // Collect English master fields
    const fieldKeys = [
        'about_badge',
        'about_heading',
        'about_content',
        'about_postmaster_title',
        'about_postmaster_sub',
        'about_postal_mark',
        'how_it_works_badge',
        'how_it_works_heading',
        'how_it_works_cta_text',
        'privacy_policy',
        'terms_of_service',
        'community_guidelines'
    ];

    const texts = {};
    fieldKeys.forEach(k => {
        const el = document.getElementById('input-en-' + k);
        if (el && el.value.trim() !== '') {
            texts[k] = el.value.trim();
        }
    });

    if (Object.keys(texts).length === 0) {
        alert('Please ensure the English tab contains text to translate.');
        return;
    }

    // UI Feedback: Loading
    btn.disabled = true;
    spinner.classList.remove('hidden');
    icon.classList.add('hidden');
    label.innerText = 'Translating into 6 languages...';
    statusBox.className = 'p-3 rounded-md text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200 block';
    statusBox.innerText = '✨ Auto-translation service is running in background. Translating into Bengali, Hindi, Arabic, Spanish, French, and Portuguese...';

    try {
        const response = await fetch('{{ route('admin.settings.auto-translate') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                source_locale: 'en',
                texts: texts
            })
        });

        const data = await response.json();

        if (data.success && data.translations) {
            // Populate target language inputs
            let updatedCount = 0;
            for (const [locale, fields] of Object.entries(data.translations)) {
                for (const [fieldKey, translatedValue] of Object.entries(fields)) {
                    const inputEl = document.getElementById(`input-${locale}-${fieldKey}`);
                    if (inputEl) {
                        inputEl.value = translatedValue;
                        updatedCount++;
                    }
                }
            }

            statusBox.className = 'p-3 rounded-md text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200 block';
            statusBox.innerHTML = `<strong>✓ Translation complete!</strong> Populated across all 6 language tabs. You can now click any tab above (e.g. বাংলা, العربية) to review and edit any phrasing, then click 'Save Platform Settings' below.`;
        } else {
            throw new Error(data.message || 'Translation could not be completed.');
        }
    } catch (err) {
        statusBox.className = 'p-3 rounded-md text-xs font-medium bg-red-50 text-red-800 border border-red-200 block';
        statusBox.innerText = 'Notice: Translation service encountered an issue: ' + err.message;
    } finally {
        btn.disabled = false;
        spinner.classList.add('hidden');
        icon.classList.remove('hidden');
        label.innerText = 'Auto-Generate Other Languages';
    }
}
</script>
@endsection
