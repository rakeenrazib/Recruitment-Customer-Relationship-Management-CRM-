<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Post a Job</h2>
            <a href="{{ route('jobs.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition">&larr; Back to Jobs</a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h3 class="text-xl font-extrabold text-gray-900 mb-8">Create New Job Listing</h3>

                <form method="POST" action="{{ route('jobs.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="title" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Job Title</label>
                        <input id="title" type="text" name="title" value="{{ old('title') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                            placeholder="e.g. Senior Software Engineer">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="company" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Company</label>
                        <input id="company" type="text" name="company" value="{{ old('company') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                            placeholder="e.g. Acme Corp">
                        @error('company') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Location</label>
                        <input id="location" type="text" name="location" value="{{ old('location') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                            placeholder="e.g. Dhaka, Bangladesh or Remote">
                        @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="salary" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Salary (Optional)</label>
                        <input id="salary" type="number" step="0.01" name="salary" value="{{ old('salary') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                            placeholder="e.g. 50000">
                        @error('salary') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="job_type" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Job Type</label>
                        <select id="job_type" name="job_type"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm">
                            <option value="full-time" @if(old('job_type','full-time')==='full-time') selected @endif>Full-time</option>
                            <option value="part-time" @if(old('job_type')==='part-time') selected @endif>Part-time</option>
                            <option value="remote"    @if(old('job_type')==='remote')    selected @endif>Remote</option>
                        </select>
                        @error('job_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Description</label>
                        <textarea id="description" name="description" rows="6" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                            placeholder="Describe the role, responsibilities, and requirements...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full py-3 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition shadow-sm">
                        Post Job
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>