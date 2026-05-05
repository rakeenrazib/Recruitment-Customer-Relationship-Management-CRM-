@php
    $record = $evaluationRecord;
    $assessmentType = old('assessment_type', $record?->assessment_type ?? '');
    $rubrics = $record?->rubrics ?? [];
@endphp

<form
    action="{{ $formAction }}"
    method="POST"
    class="space-y-4"
    x-data="{
        strategy: '{{ $assessmentType }}',
        generalScore: '{{ old('general_score', $record?->general_score) }}',
        codingRubric: '{{ old('coding_rubric', $rubrics['coding_rubric'] ?? '') }}',
        skillCompetency: '{{ old('skill_competency', $rubrics['skill_competency'] ?? '') }}',
        communicationScore: '{{ old('communication_score', $rubrics['communication_score'] ?? '') }}',
        salaryFitScore: '{{ old('salary_fit_score', $rubrics['salary_fit_score'] ?? '') }}',
        availabilityScore: '{{ old('availability_score', $rubrics['availability_score'] ?? '') }}',
        professionalismScore: '{{ old('professionalism_score', $rubrics['professionalism_score'] ?? '') }}',
        leadershipScore: '{{ old('leadership_score', $rubrics['leadership_score'] ?? '') }}',
        teamworkScore: '{{ old('teamwork_score', $rubrics['teamwork_score'] ?? '') }}',
        adaptabilityScore: '{{ old('adaptability_score', $rubrics['adaptability_score'] ?? '') }}',
        conflictHandlingScore: '{{ old('conflict_handling_score', $rubrics['conflict_handling_score'] ?? '') }}',
        plagiarismDetected: {{ old('plagiarism_detected', $rubrics['plagiarism_detected'] ?? false) ? 'true' : 'false' }},
        normalize(value) {
            return value === '' ? null : parseFloat(value);
        },
        hasSpecificScores() {
            return [
                this.codingRubric,
                this.skillCompetency,
                this.communicationScore,
                this.salaryFitScore,
                this.availabilityScore,
                this.professionalismScore,
                this.leadershipScore,
                this.teamworkScore,
                this.adaptabilityScore,
                this.conflictHandlingScore,
            ].some((value) => value !== '' && value !== null);
        },
        average(values) {
            const numericValues = values
                .map((value) => this.normalize(value))
                .filter((value) => value !== null);

            if (numericValues.length === 0) {
                return '';
            }

            return (numericValues.reduce((sum, value) => sum + value, 0) / numericValues.length).toFixed(2);
        },
        calculatedGeneral() {
            if (!this.hasSpecificScores()) {
                return '';
            }

            if (this.strategy === 'technical_assessment') {
                return this.average([this.codingRubric, this.skillCompetency]);
            }

            if (this.strategy === 'hr_feedback') {
                return this.average([this.communicationScore, this.salaryFitScore, this.availabilityScore, this.professionalismScore]);
            }

            if (this.strategy === 'behavioral_assessment') {
                return this.average([this.leadershipScore, this.teamworkScore, this.adaptabilityScore, this.conflictHandlingScore]);
            }

            return '';
        },
        effectiveGeneral() {
            return this.hasSpecificScores() ? this.calculatedGeneral() : this.generalScore;
        },
        generalDisabled() {
            return this.hasSpecificScores();
        },
        rubricDisabled() {
            return this.generalScore !== '' && this.generalScore !== null;
        }
    }"
>
    @csrf
    @if($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div>
        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Assessment Type</label>
        <select name="assessment_type" x-model="strategy" class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
            <option value="">Select assessment type</option>
            @foreach($assessmentLabels as $key => $label)
                <option value="{{ $key }}" @selected($assessmentType === $key)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">General Evaluation</label>
        <input type="number" min="0" max="5" step="0.01" x-model="generalScore" :disabled="generalDisabled()" class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 disabled:cursor-not-allowed disabled:bg-stone-100" placeholder="Out of 5">
        <input type="hidden" name="general_score" :value="effectiveGeneral()">
        <p class="mt-2 text-xs text-stone-500">Either enter a general evaluation directly, or leave it blank and let the rubric scores calculate it automatically.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Comments</label>
            <textarea name="comments" rows="4" class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">{{ old('comments', $record?->comments) }}</textarea>
        </div>
        <div>
            <label class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Recommendation</label>
            <textarea name="recommendation" rows="4" class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">{{ old('recommendation', $record?->recommendation) }}</textarea>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Strengths</label>
            <input type="text" name="strengths" value="{{ old('strengths', $record?->strengths) }}" placeholder="Comma separated strengths" class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
        </div>
        <div>
            <label class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Weaknesses</label>
            <input type="text" name="weaknesses" value="{{ old('weaknesses', $record?->weaknesses) }}" placeholder="Comma separated weaknesses" class="block w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
        </div>
    </div>

    <div x-show="strategy === 'technical_assessment'" x-cloak class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-4">
        <label class="mb-3 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Technical Assessment Rubrics</label>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-stone-400">Coding Rubric</label>
                <input type="number" min="0" max="5" step="0.01" name="coding_rubric" x-model="codingRubric" :disabled="rubricDisabled()" class="block w-full rounded-2xl border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 disabled:cursor-not-allowed disabled:bg-stone-100">
            </div>
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-stone-400">Skill Competency</label>
                <input type="number" min="0" max="5" step="0.01" name="skill_competency" x-model="skillCompetency" :disabled="rubricDisabled()" class="block w-full rounded-2xl border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 disabled:cursor-not-allowed disabled:bg-stone-100">
            </div>
        </div>
        <label class="mt-4 flex items-center gap-3 text-sm transition-opacity" :class="rubricDisabled() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'">
            <input type="checkbox" name="plagiarism_detected" value="1" x-model="plagiarismDetected" class="h-5 w-5 rounded border-stone-300 text-rose-600 shadow-sm focus:ring-rose-600 disabled:cursor-not-allowed" :disabled="rubricDisabled()">
            <span class="font-bold transition-colors" :class="plagiarismDetected ? 'text-rose-600' : 'text-stone-700'">Flag for Plagiarism</span>
        </label>
    </div>

    <div x-show="strategy === 'hr_feedback'" x-cloak class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-4">
        <label class="mb-3 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">HR Feedback Rubrics</label>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-stone-400">Communication</label>
                <input type="number" min="0" max="5" step="0.01" name="communication_score" x-model="communicationScore" :disabled="rubricDisabled()" class="block w-full rounded-2xl border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 disabled:cursor-not-allowed disabled:bg-stone-100">
            </div>
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-stone-400">Salary Expectation Fit</label>
                <input type="number" min="0" max="5" step="0.01" name="salary_fit_score" x-model="salaryFitScore" :disabled="rubricDisabled()" class="block w-full rounded-2xl border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 disabled:cursor-not-allowed disabled:bg-stone-100">
            </div>
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-stone-400">Availability</label>
                <input type="number" min="0" max="5" step="0.01" name="availability_score" x-model="availabilityScore" :disabled="rubricDisabled()" class="block w-full rounded-2xl border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 disabled:cursor-not-allowed disabled:bg-stone-100">
            </div>
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-stone-400">Professionalism</label>
                <input type="number" min="0" max="5" step="0.01" name="professionalism_score" x-model="professionalismScore" :disabled="rubricDisabled()" class="block w-full rounded-2xl border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 disabled:cursor-not-allowed disabled:bg-stone-100">
            </div>
        </div>
    </div>

    <div x-show="strategy === 'behavioral_assessment'" x-cloak class="rounded-[1.5rem] border border-stone-200 bg-stone-50 p-4">
        <label class="mb-3 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Behavioral Assessment Rubrics</label>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-stone-400">Leadership</label>
                <input type="number" min="0" max="5" step="0.01" name="leadership_score" x-model="leadershipScore" :disabled="rubricDisabled()" class="block w-full rounded-2xl border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 disabled:cursor-not-allowed disabled:bg-stone-100">
            </div>
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-stone-400">Teamwork</label>
                <input type="number" min="0" max="5" step="0.01" name="teamwork_score" x-model="teamworkScore" :disabled="rubricDisabled()" class="block w-full rounded-2xl border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 disabled:cursor-not-allowed disabled:bg-stone-100">
            </div>
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-stone-400">Adaptability</label>
                <input type="number" min="0" max="5" step="0.01" name="adaptability_score" x-model="adaptabilityScore" :disabled="rubricDisabled()" class="block w-full rounded-2xl border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 disabled:cursor-not-allowed disabled:bg-stone-100">
            </div>
            <div>
                <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-stone-400">Conflict Handling</label>
                <input type="number" min="0" max="5" step="0.01" name="conflict_handling_score" x-model="conflictHandlingScore" :disabled="rubricDisabled()" class="block w-full rounded-2xl border-stone-200 bg-white px-4 py-3 text-sm text-stone-900 disabled:cursor-not-allowed disabled:bg-stone-100">
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-600">
        Calculated result:
        <span class="font-black text-stone-900" x-text="effectiveGeneral() ? `${effectiveGeneral()} out of 5` : 'Waiting for input'"></span>
    </div>

    <button type="submit" class="w-full rounded-2xl bg-stone-900 px-4 py-3 text-sm font-bold text-white">
        {{ $record ? 'Update Evaluation' : 'Publish Evaluation' }}
    </button>
</form>
