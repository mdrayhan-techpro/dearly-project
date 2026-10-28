@extends('layouts.app', ['title' => __('Discovery Questionnaire') . " ({$length} " . __('Questions') . ")"])

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12"
    x-data="{
        attemptId: {{ $attempt->id }},
        answers: {{ json_encode($answers) }},
        totalQuestions: {{ $length }},
        saving: false,
        saveStatus: '',

        get answeredCount() {
            return Object.keys(this.answers).length;
        },

        get progressPercent() {
            return Math.round((this.answeredCount / this.totalQuestions) * 100);
        },

        selectOption(questionId, optionId) {
            this.answers[questionId] = optionId;
            this.saving = true;
            this.saveStatus = '{{ __('Saving answer...') }}';

            fetch('{{ route('discovery.save-answer') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    attempt_id: this.attemptId,
                    question_id: questionId,
                    selected_option_id: optionId
                })
            })
            .then(res => res.json())
            .then(data => {
                this.saving = false;
                this.saveStatus = '{{ __('Autosaved ✓') }}';
                setTimeout(() => { if (this.saveStatus === '{{ __('Autosaved ✓') }}') this.saveStatus = ''; }, 2000);
            })
            .catch(err => {
                this.saving = false;
                this.saveStatus = '{{ __('Error saving') }}';
            });
        }
    }">

    <!-- Sticky Progress Header -->
    <div class="sticky top-20 z-30 bg-[#FAF6EE]/95 backdrop-blur-md p-4 rounded-xl border border-[#E9DDC7] shadow-sm mb-8">
        <div class="flex items-center justify-between text-xs font-semibold mb-2">
            <div class="flex items-center space-x-2">
                <span class="font-serif text-sm font-bold text-[#342A25]">{{ $questionnaire->title }}</span>
                <span class="text-[#783C46]" x-text="`${answeredCount} {{ __('of') }} ${totalQuestions} {{ __('Answered') }}`"></span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-[11px] text-[#405B4B]" x-text="saveStatus"></span>
                <span class="font-mono text-xs font-bold text-[#783C46]" x-text="`${progressPercent}%`"></span>
            </div>
        </div>

        <!-- Progress Bar Track -->
        <div class="w-full bg-[#E9DDC7] rounded-full h-2 overflow-hidden">
            <div class="bg-[#783C46] h-2 rounded-full transition-all duration-300" :style="`width: ${progressPercent}%`"></div>
        </div>
    </div>

    <!-- Questions Container -->
    <form method="POST" action="{{ route('discovery.submit', $length) }}" class="space-y-8">
        @csrf

        @foreach($questions as $index => $q)
            <div class="paper-sheet p-6 sm:p-8 rounded-xl border border-[#E9DDC7] shadow-paper space-y-4">
                <div class="flex items-center justify-between border-b border-[#E9DDC7] pb-3">
                    <span class="text-xs font-mono font-bold text-[#B79A61]">{{ __('Question') }} {{ $index + 1 }} {{ __('of') }} {{ $length }}</span>
                    <span class="text-[10px] uppercase tracking-wider text-[#783C46] font-semibold bg-[#783C46]/10 px-2 py-0.5 rounded">
                        {{ config('dearly.dimensions.' . $q->dimension, ucwords(str_replace('_', ' ', $q->dimension))) }}
                    </span>
                </div>

                <h3 class="font-serif text-base sm:text-lg font-bold text-[#342A25] leading-snug">
                    {{ $q->question_text }}
                </h3>

                <!-- MCQ Options (A through E) -->
                <div class="space-y-2.5 pt-2">
                    @foreach($q->options as $opt)
                        <label class="flex items-start space-x-3 p-3.5 rounded-lg border cursor-pointer transition-all"
                            :class="answers[{{ $q->id }}] == {{ $opt->id }} ? 'bg-[#783C46]/10 border-[#783C46] shadow-sm' : 'bg-[#FAF6EE] border-[#E9DDC7] hover:bg-[#F2ECE1]'">
                            <input type="radio" name="question_{{ $q->id }}" value="{{ $opt->id }}" class="hidden"
                                @change="selectOption({{ $q->id }}, {{ $opt->id }})"
                                :checked="answers[{{ $q->id }}] == {{ $opt->id }}">

                            <span class="w-6 h-6 rounded-full border flex items-center justify-center text-xs font-serif font-bold flex-shrink-0 mt-0.5"
                                :class="answers[{{ $q->id }}] == {{ $opt->id }} ? 'bg-[#783C46] text-white border-[#783C46]' : 'border-[#B79A61] text-[#783C46] bg-white'">
                                {{ $opt->option_key }}
                            </span>

                            <span class="text-xs sm:text-sm text-[#342A25] leading-relaxed pt-0.5"
                                :class="answers[{{ $q->id }}] == {{ $opt->id }} ? 'font-medium' : ''">
                                {{ $opt->option_text }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach

        <!-- Final Submission Bar -->
        <div class="paper-sheet p-6 rounded-xl border border-[#E9DDC7] shadow-paper flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <p class="font-serif text-sm font-bold text-[#342A25]">{{ __('Ready to Seal Your Answers?') }}</p>
                <p class="text-xs text-[#5A4A42]">
                    {{ __('Once submitted, our compatibility algorithm will search the global directory for 90%+ kindred matches.') }}
                </p>
            </div>

            <button type="submit"
                :disabled="answeredCount < totalQuestions"
                class="w-full sm:w-auto px-8 py-3.5 text-xs font-semibold uppercase tracking-wider text-white rounded-md shadow-md transition-all"
                :class="answeredCount >= totalQuestions ? 'bg-[#783C46] hover:bg-[#582A32] transform hover:-translate-y-0.5 cursor-pointer' : 'bg-gray-400 cursor-not-allowed opacity-60'">
                {{ __('Submit & Find Matches') }} &rarr;
            </button>
        </div>
    </form>
</div>
@endsection
