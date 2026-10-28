@extends('layouts.app', ['title' => __('The Letter-Writing Studio')])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
    x-data="{
        letterId: {{ $letter->id ?: 'null' }},
        title: '{{ addslashes($letter->title) }}',
        greeting: '{{ addslashes($letter->greeting ?: 'Dear Friend,') }}',
        body: `{{ addslashes($letter->body) }}`,
        signoff: '{{ addslashes($letter->signoff ?: 'With warmth,') }}',
        selectedTheme: {{ $letter->stationery_theme_id ?: $themes->first()->id }},
        recipientCode: '{{ $recipient?->mailingCode?->code ?? request('code') }}',
        saving: false,
        saveStatus: '',

        get wordCount() {
            return this.body.trim() ? this.body.trim().split(/\s+/).length : 0;
        },

        get charCount() {
            return this.body.length;
        },

        saveDraft() {
            if (!this.title.trim() || !this.body.trim()) {
                alert('{{ __('Please enter a title and letter body before saving draft.') }}');
                return;
            }

            this.saving = true;
            this.saveStatus = '{{ __('Saving draft...') }}';

            fetch('{{ route('drafts.save') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id: this.letterId,
                    title: this.title,
                    greeting: this.greeting,
                    body: this.body,
                    signoff: this.signoff,
                    stationery_theme_id: this.selectedTheme,
                    recipient_code: this.recipientCode
                })
            })
            .then(res => res.json())
            .then(data => {
                this.saving = false;
                this.letterId = data.letter_id;
                this.saveStatus = '{{ __('Draft saved at') }} ' + data.saved_at;
                setTimeout(() => { if (this.saveStatus.includes('{{ __('Draft saved') }}')) this.saveStatus = ''; }, 3000);
            })
            .catch(err => {
                this.saving = false;
                this.saveStatus = '{{ __('Error saving draft') }}';
            });
        },

        proceedToFold() {
            if (!this.title.trim() || !this.body.trim()) {
                alert('{{ __('Please write your letter before folding.') }}');
                return;
            }
            $refs.composerForm.submit();
        }
    }">

    <!-- Top Studio Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-[#E9DDC7] pb-4 mb-8 gap-4">
        <div>
            <span class="text-xs font-serif tracking-widest uppercase text-[#B79A61] font-semibold">{{ __('The Letter Composer') }}</span>
            <h1 class="font-serif text-2xl font-bold text-[#342A25]">
                {{ $letter->id ? __('Editing Letter') : __('Compose a Letter') }}
            </h1>
        </div>

        <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs">
            <span class="text-[#405B4B] font-mono text-[11px]" x-text="saveStatus"></span>
            <button type="button" @click="saveDraft()" class="px-4 py-2 font-semibold text-[#5A4A42] hover:text-[#783C46] bg-[#FAF6EE] border border-[#D6C7AE] rounded transition-colors">
                {{ __('Save Draft') }}
            </button>
            <button type="button" @click="proceedToFold()" class="px-5 py-2 font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-sm transition-all transform hover:-translate-y-0.5">
                {{ __('Fold My Letter &rarr;') }}
            </button>
        </div>
    </div>

    <!-- Main Composer Form -->
    <form x-ref="composerForm" method="POST" action="{{ route('drafts.save') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="id" :value="letterId">

        <!-- Recipient Postal Address & Theme Selector -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 paper-sheet p-5 rounded-lg border border-[#E9DDC7] shadow-sm">
            <div class="md:col-span-7">
                <label for="recipient_code" class="block text-xs font-semibold text-[#342A25] mb-1">
                    {{ __('Recipient Mailing Code') }}
                </label>
                <div class="relative">
                    <input id="recipient_code" type="text" name="recipient_code" x-model="recipientCode"
                        placeholder="e.g. DEARLY-7K9M-2Q8P"
                        class="w-full text-xs font-mono font-bold tracking-wider rounded-md border-[#D6C7AE] bg-[#FAF6EE] focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                </div>
                <p class="text-[10px] text-[#5A4A42] mt-1">
                    {{ __('Enter your pen friend’s private postal address or leave blank while drafting.') }}
                </p>
            </div>

            <div class="md:col-span-5">
                <label for="stationery_theme_id" class="block text-xs font-semibold text-[#342A25] mb-1">
                    {{ __('Stationery Paper Theme') }}
                </label>
                <select id="stationery_theme_id" name="stationery_theme_id" x-model="selectedTheme"
                    class="w-full text-xs rounded-md border-[#D6C7AE] bg-[#FAF6EE] focus:border-[#783C46] focus:ring focus:ring-[#783C46]/20">
                    @foreach($themes as $theme)
                        <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Archival Stationery Writing Sheet -->
        <div class="paper-sheet p-5 sm:p-8 lg:p-12 rounded-xl border border-[#E9DDC7] shadow-paper space-y-6 transition-colors duration-300">
            <!-- Title / Subject -->
            <div>
                <input type="text" name="title" x-model="title" required
                    placeholder="{{ __('Letter Title or Subject...') }}"
                    class="w-full font-serif text-xl sm:text-2xl font-bold border-0 border-b border-[#E9DDC7] bg-transparent focus:border-[#783C46] focus:ring-0 px-0 text-[#342A25] placeholder:text-[#B79A61]">
            </div>

            <!-- Greeting -->
            <div>
                <input type="text" name="greeting" x-model="greeting"
                    placeholder="{{ __('Greeting (e.g. Dearest Friend,)') }}"
                    class="w-full font-handwriting text-2xl border-0 border-b border-dashed border-[#E9DDC7] bg-transparent focus:border-[#783C46] focus:ring-0 px-0 text-[#783C46] placeholder:text-[#B79A61]">
            </div>

            <!-- Main Body (Paper-Lined Editor) -->
            <div>
                <textarea name="body" x-model="body" rows="18" required
                    placeholder="{{ __('Take a deep breath and begin writing your thoughts...') }}"
                    class="w-full font-serif text-sm sm:text-base leading-loose border-0 bg-transparent focus:ring-0 px-0 text-[#342A25] resize-y placeholder:text-[#5A4A42]/60"></textarea>
            </div>

            <!-- Signoff -->
            <div class="pt-4 border-t border-[#E9DDC7] flex flex-col sm:flex-row justify-between items-end gap-4">
                <div class="w-full sm:w-64">
                    <input type="text" name="signoff" x-model="signoff"
                        placeholder="{{ __('Signoff (e.g. With warmth,)') }}"
                        class="w-full font-handwriting text-2xl border-0 border-b border-dashed border-[#E9DDC7] bg-transparent focus:border-[#783C46] focus:ring-0 px-0 text-[#783C46]">
                </div>

                <!-- Word & Character Counts -->
                <div class="text-[11px] font-mono text-[#B79A61] flex items-center space-x-3">
                    <span x-text="`${wordCount} {{ __('words') }}`"></span>
                    <span>•</span>
                    <span x-text="`${charCount} {{ __('characters') }}`"></span>
                </div>
            </div>
        </div>

        <!-- Bottom Action Bar -->
        <div class="flex items-center justify-between pt-4">
            <a href="{{ route('mailbox.drafts') }}" class="text-xs text-[#5A4A42] hover:text-[#783C46]">
                &larr; {{ __('View Drafts') }}
            </a>
            <div class="flex items-center space-x-3">
                <button type="button" @click="saveDraft()" class="px-4 py-2 text-xs font-semibold text-[#5A4A42] hover:text-[#783C46] bg-[#FAF6EE] border border-[#D6C7AE] rounded transition-colors">
                    {{ __('Save Draft') }}
                </button>
                <button type="button" @click="proceedToFold()" class="px-6 py-2.5 text-xs font-semibold uppercase tracking-wider text-white bg-[#783C46] hover:bg-[#582A32] rounded shadow-md transition-all">
                    {{ __('Fold My Letter &rarr;') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
