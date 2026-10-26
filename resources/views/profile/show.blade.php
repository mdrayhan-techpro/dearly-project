@extends('layouts.app', ['title' => 'Profile & Privacy'])

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12" x-data="{ selectedInterests: {{ json_encode($profile->interests ?? []) }} }">
    <div class="paper-sheet p-8 sm:p-10 rounded-xl border border-[#E9DDC7] shadow-paper space-y-8">
        <!-- Header -->
        <div class="border-b border-[#E9DDC7] pb-6 flex items-center justify-between">
            <div>
                <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">User Settings</span>
                <h1 class="font-serif text-2xl font-bold text-[#342A25] mt-1">Profile & Privacy Settings</h1>
            </div>
            <div class="wax-seal w-10 h-10 text-sm font-serif font-bold">
                {{ substr($user->nickname, 0, 1) }}
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nickname -->
            <div>
                <label for="nickname" class="block text-xs font-semibold text-[#342A25] mb-1">Public Pen-Name (Nickname)</label>
                <input id="nickname" type="text" name="nickname" value="{{ old('nickname', $profile->nickname) }}" required
                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE] focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                <p class="text-[10px] text-[#5A4A42] mt-1">Visible to pen friends. Your real name and email address are never exposed.</p>
            </div>

            <!-- Language & Country -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="preferred_language" class="block text-xs font-semibold text-[#342A25] mb-1">Preferred Language</label>
                    <select id="preferred_language" name="preferred_language" required class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                        @foreach($supportedLocales as $code => $locale)
                            <option value="{{ $code }}" {{ old('preferred_language', $profile->preferred_language) == $code ? 'selected' : '' }}>
                                {{ $locale['native'] }} ({{ $locale['name'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="country" class="block text-xs font-semibold text-[#342A25] mb-1">Country or Region</label>
                    <input id="country" type="text" name="country" value="{{ old('country', $profile->country) }}"
                        class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">
                </div>
            </div>

            <!-- Bio -->
            <div>
                <label for="bio" class="block text-xs font-semibold text-[#342A25] mb-1">Introduction Bio</label>
                <textarea id="bio" name="bio" rows="3" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE]">{{ old('bio', $profile->bio) }}</textarea>
            </div>

            <!-- Interests Selection -->
            <div>
                <label class="block text-xs font-semibold text-[#342A25] mb-2">Interests (Choose up to 6)</label>
                <div class="flex flex-wrap gap-2 max-h-40 overflow-y-auto p-2 bg-[#FAF6EE] rounded-md border border-[#E9DDC7]">
                    @foreach($popularInterests as $interest)
                        <label class="inline-flex items-center px-3 py-1.5 rounded-full text-xs cursor-pointer border transition-colors"
                            :class="selectedInterests.includes('{{ $interest }}') ? 'bg-[#783C46] text-white border-[#783C46]' : 'bg-[#FAF6EE] text-[#342A25] border-[#D6C7AE] hover:bg-[#E9DDC7]'">
                            <input type="checkbox" name="interests[]" value="{{ $interest }}" class="hidden"
                                @change="if ($el.checked) { if(selectedInterests.length < 6) selectedInterests.push('{{ $interest }}'); else $el.checked = false; } else { selectedInterests = selectedInterests.filter(i => i !== '{{ $interest }}'); }">
                            <span>{{ $interest }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Privacy Controls: Pause Matching -->
            <div class="pt-4 border-t border-[#E9DDC7] space-y-3">
                <h3 class="font-serif font-bold text-sm text-[#342A25]">Discovery Privacy</h3>
                <label class="flex items-start space-x-3 cursor-pointer">
                    <input type="checkbox" name="is_matching_paused" value="1" {{ $profile->is_matching_paused ? 'checked' : '' }}
                        class="rounded text-[#783C46] focus:ring-[#783C46]/20 mt-0.5">
                    <div class="text-xs">
                        <strong class="text-[#342A25] block">Pause Friendship Discovery</strong>
                        <span class="text-[#5A4A42]">Hide your profile from new match results while keeping all existing correspondence active.</span>
                    </div>
                </label>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm">
                    Save Changes
                </button>
            </div>
        </form>

        <!-- Danger Zone: Close Account -->
        <div class="border-t border-[#E9DDC7] pt-6">
            <h3 class="font-serif font-bold text-sm text-red-800">Close Post Office Box</h3>
            <p class="text-xs text-[#5A4A42] mt-1 leading-relaxed">
                Closing your account permanently deactivates your mailing code and removes your profile from discovery. Your past letters to other correspondents will have your nickname anonymized.
            </p>

            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you wish to close your Dearly account? This action cannot be undone.');" class="mt-4">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded">
                    Delete & Close Account
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
