@extends('layouts.app', ['title' => 'First-Time Post Office Setup'])

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16" x-data="{ step: 1, selectedInterests: {{ json_encode($profile->interests ?? []) }} }">
    <div class="paper-sheet p-8 sm:p-10 rounded-xl border border-[#E9DDC7] shadow-paper">
        <!-- Postal Header -->
        <div class="text-center mb-8 border-b border-[#E9DDC7] pb-6">
            <div class="wax-seal w-12 h-12 mx-auto mb-3 font-serif font-bold text-lg shadow-seal">
                D
            </div>
            <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">Postmaster Registration</span>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[#342A25] mt-1">
                Establish Your Postal Identity
            </h1>
            <p class="text-xs text-[#5A4A42] mt-2 max-w-md mx-auto">
                Dearly is a privacy-first correspondence platform. Choose your public pen-name and sharing preferences.
            </p>

            <!-- Wizard Step Indicator -->
            <div class="flex items-center justify-center space-x-6 mt-6 text-xs">
                <button type="button" @click="step = 1" :class="step === 1 ? 'font-bold text-[#783C46] border-b-2 border-[#783C46]' : 'text-[#8A7972]'">1. Pen-Name & Age</button>
                <span class="text-[#D6C7AE]">/</span>
                <button type="button" @click="step = 2" :class="step === 2 ? 'font-bold text-[#783C46] border-b-2 border-[#783C46]' : 'text-[#8A7972]'">2. Language & Bio</button>
                <span class="text-[#D6C7AE]">/</span>
                <button type="button" @click="step = 3" :class="step === 3 ? 'font-bold text-[#783C46] border-b-2 border-[#783C46]' : 'text-[#8A7972]'">3. Passions & Rhythm</button>
            </div>
        </div>

        <form method="POST" action="{{ route('onboarding.store') }}" class="space-y-6">
            @csrf

            <!-- STEP 1: Nickname & Adult Safeguard -->
            <div x-show="step === 1" class="space-y-5">
                <div>
                    <label for="nickname" class="block text-xs font-semibold text-[#342A25] mb-1">
                        Public Pen-Name (Nickname) <span class="text-[#783C46]">*</span>
                    </label>
                    <input id="nickname" type="text" name="nickname" value="{{ old('nickname', $profile->nickname) }}" required
                        placeholder="e.g. VintageDreamer, HeatherReader"
                        class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                    <p class="text-[10px] text-[#5A4A42] mt-1">
                        This is the only name other pen friends will ever see. Letters will be addressed to this name.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="age" class="block text-xs font-semibold text-[#342A25] mb-1">
                            Your Age <span class="text-[#783C46]">*</span>
                        </label>
                        <input id="age" type="number" name="age" min="18" max="120" value="{{ old('age', $profile->age ?: 25) }}" required
                            class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                        <p class="text-[10px] text-[#5A4A42] mt-1">
                            Adult-only policy (minimum 18). Protects minors and prevents mismatched demographics.
                        </p>
                    </div>

                    <div>
                        <label for="gender" class="block text-xs font-semibold text-[#342A25] mb-1">
                            Self-Selected Gender (Optional)
                        </label>
                        <select id="gender" name="gender" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                            <option value="prefer_not_to_say" {{ old('gender', $profile->gender) == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                            <option value="female" {{ old('gender', $profile->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="male" {{ old('gender', $profile->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="non_binary" {{ old('gender', $profile->gender) == 'non_binary' ? 'selected' : '' }}>Non-binary</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="button" @click="step = 2" class="px-5 py-2.5 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md transition-all">
                        Continue to Step 2 &rarr;
                    </button>
                </div>
            </div>

            <!-- STEP 2: Language, Country & Bio -->
            <div x-show="step === 2" class="space-y-5" style="display: none;">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="preferred_language" class="block text-xs font-semibold text-[#342A25] mb-1">
                            Primary Correspondence Language <span class="text-[#783C46]">*</span>
                        </label>
                        <select id="preferred_language" name="preferred_language" required class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                            @foreach($supportedLocales as $code => $locale)
                                <option value="{{ $code }}" {{ old('preferred_language', $profile->preferred_language ?: app()->getLocale()) == $code ? 'selected' : '' }}>
                                    {{ $locale['native'] }} ({{ $locale['name'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="country" class="block text-xs font-semibold text-[#342A25] mb-1">
                            Country or Broad Region (Optional)
                        </label>
                        <input id="country" type="text" name="country" value="{{ old('country', $profile->country) }}" placeholder="e.g. Scotland, Japan, Argentina"
                            class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                    </div>
                </div>

                <div>
                    <label for="bio" class="block text-xs font-semibold text-[#342A25] mb-1">
                        Short Postal Introduction (Bio)
                    </label>
                    <textarea id="bio" name="bio" rows="3" placeholder="Tell potential pen friends about the quiet things you enjoy: morning tea, birdwatching, second-hand bookshops..."
                        class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">{{ old('bio', $profile->bio) }}</textarea>
                </div>

                <div class="pt-4 flex justify-between">
                    <button type="button" @click="step = 1" class="px-4 py-2 text-xs font-semibold text-[#5A4A42] hover:text-[#783C46]">
                        &larr; Back
                    </button>
                    <button type="button" @click="step = 3" class="px-5 py-2.5 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md transition-all">
                        Continue to Step 3 &rarr;
                    </button>
                </div>
            </div>

            <!-- STEP 3: Passions & Cadence -->
            <div x-show="step === 3" class="space-y-5" style="display: none;">
                <div>
                    <label class="block text-xs font-semibold text-[#342A25] mb-2">
                        Select Your Passions & Quiet Interests (Choose up to 6)
                    </label>
                    <div class="flex flex-wrap gap-2 max-h-48 overflow-y-auto p-2 bg-[#F6F0E5]/40 rounded-md border border-[#E9DDC7]">
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

                <div>
                    <label for="response_cadence" class="block text-xs font-semibold text-[#342A25] mb-1">
                        Preferred Response Rhythm
                    </label>
                    <select id="response_cadence" name="response_cadence" class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#F6F0E5]/60 focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                        <option value="weekly">Weekly (Frequent correspondence)</option>
                        <option value="biweekly" selected>Biweekly (Every 2–3 weeks, balanced)</option>
                        <option value="monthly">Monthly (Slow, deep letters)</option>
                        <option value="seasonal">Seasonal (4 to 6 long letters per year)</option>
                    </select>
                </div>

                <div class="pt-6 border-t border-[#E9DDC7] flex justify-between items-center">
                    <button type="button" @click="step = 2" class="px-4 py-2 text-xs font-semibold text-[#5A4A42] hover:text-[#783C46]">
                        &larr; Back
                    </button>
                    <button type="submit" class="px-8 py-3 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded-md shadow-md transition-all">
                        Establish My Postbox &rarr;
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
