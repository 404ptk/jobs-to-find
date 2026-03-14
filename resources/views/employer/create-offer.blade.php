@extends('layouts.app')

@section('title', 'Create Job Offer - Jobs to Find')

@section('content')
    <div class="min-h-[calc(100vh-8rem)] py-12 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <a href="{{ route('my-offers') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to My Offers
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Create New Job Offer</h1>
                <p class="text-gray-600 mt-1">Fill in the details to post a new job opportunity</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-8">
                <form action="{{ route('offer.store') }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Job Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                            placeholder="e.g. Senior Full Stack Developer" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="company_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Company <span class="text-red-500">*</span>
                        </label>
                        <select id="company_id" name="company_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('company_id') border-red-500 @enderror"
                            onchange="handleCompanyChange(this)" required>
                            <option value="">Select company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                            @if($companies->count() < 3)
                                <option value="create_new" class="text-blue-600 font-bold">+ Create New Company</option>
                            @endif
                        </select>
                        @error('company_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="company_name_container" class="mb-6 {{ old('company_id') ? 'hidden' : '' }}">
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Company Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('company_name') border-red-500 @enderror"
                            placeholder="e.g. TechCorp Solutions" required>
                        @error('company_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="parent_category" class="block text-sm font-medium text-gray-700 mb-2">
                                Main Category <span class="text-red-500">*</span>
                            </label>
                            <select id="parent_category" name="parent_category"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                                <option value="">Select category</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_category') == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Position <span class="text-red-500">*</span>
                            </label>
                            <select id="category_id" name="category_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror"
                                required>
                                <option value="">Select position</option>
                                @foreach($subCategories as $sub)
                                    <option value="{{ $sub->id }}" 
                                        data-parent="{{ $sub->parent_id }}"
                                        {{ old('category_id') == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                Country <span class="text-red-500">*</span>
                            </label>
                            <select id="country" name="country"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                                <option value="">Select country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country }}" {{ old('country') == $country ? 'selected' : '' }}>
                                        {{ $country }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="location_id" class="block text-sm font-medium text-gray-700 mb-2">
                                City <span class="text-red-500">*</span>
                            </label>
                            <select id="location_id" name="location_id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location_id') border-red-500 @enderror"
                                required>
                                <option value="">Select city</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" 
                                        data-country="{{ $location->country }}"
                                        {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                        {{ $location->city }}
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
                            <select id="employment_type" name="employment_type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('employment_type') border-red-500 @enderror"
                                required>
                                <option value="">Select employment type</option>
                                @foreach($employmentTypes as $type)
                                    <option value="{{ $type }}" {{ old('employment_type') == $type ? 'selected' : '' }}>
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
                            <input type="date" id="expires_at" name="expires_at" value="{{ old('expires_at') }}"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('expires_at') border-red-500 @enderror"
                                required>
                            @error('expires_at')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Salary Range in EUR (optional)
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <input type="number" id="salary_min" name="salary_min" value="{{ old('salary_min') }}"
                                    step="10" min="0"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('salary_min') border-red-500 @enderror"
                                    placeholder="Minimum salary">
                                @error('salary_min')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="number" id="salary_max" name="salary_max" value="{{ old('salary_max') }}"
                                    step="10" min="0"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('salary_max') border-red-500 @enderror"
                                    placeholder="Maximum salary">
                                @error('salary_max')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Leave empty if you prefer not to disclose salary information
                        </p>
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Job Description <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="8"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                            placeholder="Describe the job role, responsibilities, and what you're looking for..."
                            required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Press Enter for line breaks</p>
                    </div>

                    <div class="mb-8">
                        <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">
                            Requirements <span class="text-red-500">*</span>
                        </label>
                        <textarea id="requirements" name="requirements" rows="8"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('requirements') border-red-500 @enderror"
                            placeholder="List the required skills, qualifications, and experience..."
                            required>{{ old('requirements') }}</textarea>
                        @error('requirements')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Press Enter for line breaks</p>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Skills <span class="text-gray-400">(optional)</span>
                        </label>
                        <p class="text-sm text-gray-500 mb-3">Select relevant skills for this position</p>
                        
                        <div class="relative mb-3">
                            <input type="text" id="skills-search" placeholder="Search skills..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                autocomplete="off">
                            <svg class="w-5 h-5 absolute right-3 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        <div id="selected-skills" class="flex flex-wrap gap-2 mb-3"></div>

                        <div id="skills-list" class="max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-2 bg-gray-50">
                            @foreach($skills as $skill)
                                <label class="skill-item flex items-center px-3 py-2 hover:bg-white rounded cursor-pointer transition" data-name="{{ strtolower($skill->name) }}">
                                    <input type="checkbox" name="skills[]" value="{{ $skill->id }}" 
                                        class="skill-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
                                        {{ in_array($skill->id, old('skills', [])) ? 'checked' : '' }}>
                                    <span class="ml-3 text-sm text-gray-700">{{ $skill->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('skills')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('my-offers') }}"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium cursor-pointer">
                            Create Job Offer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function handleCompanyChange(select) {
        const container = document.getElementById('company_name_container');
        const input = document.getElementById('company_name');
        
        if (select.value === 'create_new') {
            window.location.href = "{{ route('companies.create') }}";
            return;
        }

        if (select.value) {
            container.classList.add('hidden');
            input.removeAttribute('required');
        } else {
            container.classList.remove('hidden');
            input.setAttribute('required', 'required');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('company_id');
        if (select) handleCompanyChange(select);

        const countrySelect = document.getElementById('country');
        const citySelect = document.getElementById('location_id');
        const cityOptions = Array.from(citySelect.options);

        function filterCities() {
            const selectedCountry = countrySelect.value;
            const currentCityId = citySelect.value;
            
            citySelect.innerHTML = '<option value="">Select city</option>';
            
            let cityStillAvailable = false;

            cityOptions.forEach(option => {
                if (option.value === "") return;
                if (option.dataset.country === selectedCountry) {
                    const newOption = option.cloneNode(true);
                    if (newOption.value === currentCityId) {
                        newOption.selected = true;
                        cityStillAvailable = true;
                    }
                    citySelect.appendChild(newOption);
                }
            });

            if (!cityStillAvailable && currentCityId !== "") {
                citySelect.value = "";
            }
        }

        if (countrySelect && citySelect) {
            countrySelect.addEventListener('change', filterCities);
            if (countrySelect.value) {
                filterCities();
            }
        }

        const categorySelect = document.getElementById('parent_category');
        const positionSelect = document.getElementById('category_id');
        const positionOptions = Array.from(positionSelect.options);

        function filterPositions() {
            const selectedParentId = categorySelect.value;
            const currentPositionId = positionSelect.value;
            
            positionSelect.innerHTML = '<option value="">Select position</option>';
            
            let positionStillAvailable = false;

            positionOptions.forEach(option => {
                if (option.value === "") return;
                if (option.dataset.parent === selectedParentId) {
                    const newOption = option.cloneNode(true);
                    if (newOption.value === currentPositionId) {
                        newOption.selected = true;
                        positionStillAvailable = true;
                    }
                    positionSelect.appendChild(newOption);
                }
            });

            if (!positionStillAvailable && currentPositionId !== "") {
                positionSelect.value = "";
            }
        }

        if (categorySelect && positionSelect) {
            categorySelect.addEventListener('change', filterPositions);
            if (categorySelect.value) {
                filterPositions();
            }
        }

        const searchInput = document.getElementById('skills-search');
        const skillItems = document.querySelectorAll('.skill-item');
        const checkboxes = document.querySelectorAll('.skill-checkbox');
        const selectedContainer = document.getElementById('selected-skills');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            skillItems.forEach(item => {
                const name = item.dataset.name;
                item.style.display = name.includes(query) ? '' : 'none';
            });
        });

        function renderSelectedSkills() {
            selectedContainer.innerHTML = '';
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const name = cb.closest('.skill-item').querySelector('span').textContent.trim();
                    const tag = document.createElement('span');
                    tag.className = 'inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full';
                    tag.innerHTML = `${name}<button type="button" class="ml-1 hover:text-blue-600 cursor-pointer" data-id="${cb.value}">&times;</button>`;
                    tag.querySelector('button').addEventListener('click', function() {
                        cb.checked = false;
                        renderSelectedSkills();
                    });
                    selectedContainer.appendChild(tag);
                }
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', renderSelectedSkills);
        });

        renderSelectedSkills();
    });
</script>

<style>
        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
        }
    </style>
@endsection