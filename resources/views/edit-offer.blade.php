@extends('layouts.app')

@section('title', 'Edit Job Offer - Jobs to Find')

@section('content')
<div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('my-offers') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to My Offers
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Job Offer</h1>
            <p class="text-gray-600 mt-1">Update the details of your job posting</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-8">
            <form action="{{ route('offer.update', $jobOffer->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Job Title <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="{{ old('title', $jobOffer->title) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                        placeholder="e.g. Senior Full Stack Developer"
                        required
                    >
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Company Name <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="company_name" 
                        name="company_name" 
                        value="{{ old('company_name', $jobOffer->company_name) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('company_name') border-red-500 @enderror"
                        placeholder="e.g. TechCorp Solutions"
                        required
                    >
                    @error('company_name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="category_id" 
                            name="category_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $jobOffer->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Location <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="location_id" 
                            name="location_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Select location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id', $jobOffer->location_id) == $location->id ? 'selected' : '' }}>
                                    {{ $location->city }}, {{ $location->country }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="employment_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Employment Type <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="employment_type" 
                            name="employment_type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('employment_type') border-red-500 @enderror"
                            required
                        >
                            <option value="">Select employment type</option>
                            @foreach($employmentTypes as $type)
                                <option value="{{ $type }}" {{ old('employment_type', $jobOffer->employment_type) == $type ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('-', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('employment_type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">
                            Offer Expiration Date <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="expires_at" 
                            name="expires_at" 
                            value="{{ old('expires_at', $jobOffer->expires_at ? $jobOffer->expires_at->format('Y-m-d') : '') }}"
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('expires_at') border-red-500 @enderror"
                            required
                        >
                        @error('expires_at')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Salary Range -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Salary Range in EUR (optional)
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <input 
                                type="number" 
                                id="salary_min" 
                                name="salary_min" 
                                value="{{ old('salary_min', $jobOffer->salary_min) }}"
                                step="0.01"
                                min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('salary_min') border-red-500 @enderror"
                                placeholder="Minimum salary"
                            >
                            @error('salary_min')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <input 
                                type="number" 
                                id="salary_max" 
                                name="salary_max" 
                                value="{{ old('salary_max', $jobOffer->salary_max) }}"
                                step="0.01"
                                min="0"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('salary_max') border-red-500 @enderror"
                                placeholder="Maximum salary"
                            >
                            @error('salary_max')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Leave empty if you prefer not to disclose salary information</p>
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Job Description <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="8"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                        placeholder="Describe the job role, responsibilities, and what you're looking for..."
                        required
                    >{{ old('description', $jobOffer->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Press Enter for line breaks</p>
                </div>

                <div class="mb-8">
                    <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">
                        Requirements <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="requirements" 
                        name="requirements" 
                        rows="8"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('requirements') border-red-500 @enderror"
                        placeholder="List the required skills, qualifications, and experience..."
                        required
                    >{{ old('requirements', $jobOffer->requirements) }}</textarea>
                    @error('requirements')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Press Enter for line breaks</p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-900">Original Post Date</p>
                            <p class="text-sm text-blue-700 mt-1">
                                This offer was originally posted on {{ $jobOffer->created_at->format('F d, Y') }}. Editing will not change the post date.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a 
                        href="{{ route('my-offers') }}" 
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium"
                    >
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium"
                    >
                        Update Job Offer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    input[type="date"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
    }
</style>
@endsection
