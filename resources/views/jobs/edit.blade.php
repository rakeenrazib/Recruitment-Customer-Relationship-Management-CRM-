<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-black text-stone-900">Edit</h2>
            <a href="{{ route('jobs.show', $job) }}" class="text-sm font-bold text-stone-500">Back</a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-stone-50 py-12">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-stone-200 bg-white p-8 shadow-sm">
                <form method="POST" action="{{ route('jobs.update', $job) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="title" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Job Title</label>
                        <input id="title" type="text" name="title" value="{{ old('title', $job->title) }}" required class="w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
                    </div>
                    <div>
                        <label for="location" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Location</label>
                        <input id="location" type="text" name="location" value="{{ old('location', $job->location) }}" required class="w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="salary" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Salary</label>
                            <input id="salary" type="number" step="0.01" name="salary" value="{{ old('salary', $job->salary) }}" class="w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
                        </div>
                        <div>
                            <label for="job_type" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Job Type</label>
                            <select id="job_type" name="job_type" class="w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">
                                <option value="full-time" @selected(old('job_type', $job->job_type) === 'full-time')>Full-time</option>
                                <option value="part-time" @selected(old('job_type', $job->job_type) === 'part-time')>Part-time</option>
                                <option value="remote" @selected(old('job_type', $job->job_type) === 'remote')>Remote</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="description" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Description</label>
                        <textarea id="description" name="description" rows="6" required class="w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">{{ old('description', $job->description) }}</textarea>
                    </div>
                    <div>
                        <label for="requirements" class="mb-2 block text-xs font-bold uppercase tracking-[0.25em] text-stone-400">Requirements</label>
                        <textarea id="requirements" name="requirements" rows="5" class="w-full rounded-2xl border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900">{{ old('requirements', $job->requirements) }}</textarea>
                    </div>
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 rounded-2xl bg-stone-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-stone-700">Save</button>
                        <a href="{{ route('jobs.show', $job) }}" class="flex-1 rounded-2xl border border-stone-200 px-4 py-3 text-center text-sm font-bold text-stone-700">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
