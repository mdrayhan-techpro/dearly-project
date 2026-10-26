@extends('layouts.admin', ['title' => 'Platform Settings'])

@section('header', 'Global Platform Settings & Brand Controls')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="bg-white p-6 sm:p-8 rounded-lg border border-[#E0D7D0] shadow-sm">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Branding -->
            <div class="space-y-4">
                <h3 class="font-serif font-bold text-base text-[#2D2420] border-b border-[#E0D7D0] pb-2">Platform Identity</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#2D2420] mb-1">Application Name</label>
                        <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'Dearly') }}" required
                            class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-[#2D2420] mb-1">Brand Tagline</label>
                        <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? 'Letters Find Their Way.') }}" required
                            class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-[#2D2420] mb-1">Default Platform Language</label>
                        <select name="default_language" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
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
                            class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
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
                        <p class="text-[10px] text-[#8A7972] mt-1">Default 90%. Matches meeting this score qualify for notifications.</p>
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

            <!-- Brand Color Tokens -->
            <div class="space-y-4 border-t border-[#E0D7D0] pt-6">
                <h3 class="font-serif font-bold text-base text-[#2D2420] border-b border-[#E0D7D0] pb-2">Brand Palette Tokens (Hex)</h3>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-xs">
                    <div>
                        <label class="block font-semibold mb-1">Paper Ivory</label>
                        <input type="text" name="brand_color_ivory" value="{{ $settings['brand_color_ivory'] ?? '#F6F0E5' }}" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Postal Burgundy</label>
                        <input type="text" name="brand_color_burgundy" value="{{ $settings['brand_color_burgundy'] ?? '#783C46' }}" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Ink Brown</label>
                        <input type="text" name="brand_color_ink" value="{{ $settings['brand_color_ink'] ?? '#342A25' }}" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Muted Brass</label>
                        <input type="text" name="brand_color_brass" value="{{ $settings['brand_color_brass'] ?? '#B79A61' }}" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Forest Green</label>
                        <input type="text" name="brand_color_forest" value="{{ $settings['brand_color_forest'] ?? '#405B4B' }}" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                    </div>
                </div>
            </div>

            <!-- Toggles: Maintenance & Registration -->
            <div class="space-y-3 border-t border-[#E0D7D0] pt-6">
                <h3 class="font-serif font-bold text-base text-[#2D2420]">Access Flags</h3>
                <label class="flex items-center space-x-3 text-xs cursor-pointer">
                    <input type="checkbox" name="registration_enabled" value="1" {{ !empty($settings['registration_enabled']) ? 'checked' : '' }}
                        class="rounded text-[#783C46] focus:ring-[#783C46]/20">
                    <span class="font-semibold text-[#2D2420]">New Post Office Registrations Open</span>
                </label>

                <label class="flex items-center space-x-3 text-xs cursor-pointer">
                    <input type="checkbox" name="maintenance_mode" value="1" {{ !empty($settings['maintenance_mode']) ? 'checked' : '' }}
                        class="rounded text-[#783C46] focus:ring-[#783C46]/20">
                    <span class="font-semibold text-red-800">Maintenance Mode Active (Public routes paused)</span>
                </label>
            </div>

            <div class="pt-6 border-t border-[#E0D7D0] flex justify-end">
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm">
                    Save Platform Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
