@extends('layouts.app')

@section('title', 'Job Offers - Jobs to Find')

@section('content')
<div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Available Job Offers</h1>
            <p class="text-gray-600">
                @if(isset($validated) && array_filter($validated))
                    Showing results for your search
                @else
                    Browse all available job opportunities
                @endif
            </p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Refine Your Search</h2>
                <button onclick="toggleSearchForm()" class="text-blue-600 hover:text-blue-700 text-sm font-medium cursor-pointer">
                    <span id="toggle-text">Hide filters</span>
                </button>
            </div>
            
            <form method="GET" action="{{ route('search') }}" id="search-form">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Job title, company, keywords..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                        <select name="country" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All countries</option>
                            @foreach($countries as $country)
                                <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <select name="city" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All cities</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Employment Type</label>
                        <select name="employment_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All types</option>
                            @foreach($employmentTypes as $type)
                                <option value="{{ $type }}" {{ request('employment_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('-', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest first</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest first</option>
                            <option value="salary_high" {{ request('sort') == 'salary_high' ? 'selected' : '' }}>Highest salary</option>
                            <option value="salary_low" {{ request('sort') == 'salary_low' ? 'selected' : '' }}>Lowest salary</option>
                        </select>
                    </div>
                </div>

                <div class="flex gap-3 mt-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium cursor-pointer">
                        Apply Filters
                    </button>
                    <a href="{{ route('search') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                        Clear All
                    </a>
                </div>
            </form>
        </div>

        @if(isset($validated) && array_filter($validated))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-900">Active filters:</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @if(!empty($validated['search']))
                                <span class="px-3 py-1 bg-white text-blue-800 text-sm rounded-full">
                                    Search: "{{ $validated['search'] }}"
                                </span>
                            @endif
                            @if(!empty($validated['country']))
                                <span class="px-3 py-1 bg-white text-blue-800 text-sm rounded-full">
                                    Country: {{ $validated['country'] }}
                                </span>
                            @endif
                            @if(!empty($validated['city']))
                                <span class="px-3 py-1 bg-white text-blue-800 text-sm rounded-full">
                                    City: {{ $validated['city'] }}
                                </span>
                            @endif
                            @if(!empty($validated['category']))
                                <span class="px-3 py-1 bg-white text-blue-800 text-sm rounded-full">
                                    Category: {{ ucfirst(str_replace('-', ' ', $validated['category'])) }}
                                </span>
                            @endif
                            @if(!empty($validated['employment_type']))
                                <span class="px-3 py-1 bg-white text-blue-800 text-sm rounded-full">
                                    Type: {{ ucfirst(str_replace('-', ' ', $validated['employment_type'])) }}
                                </span>
                            @endif
                            @if(!empty($validated['sort']) && $validated['sort'] !== 'newest')
                                <span class="px-3 py-1 bg-white text-blue-800 text-sm rounded-full">
                                    Sort: {{ 
                                        $validated['sort'] === 'oldest' ? 'Oldest first' : 
                                        ($validated['sort'] === 'salary_high' ? 'Highest salary' : 
                                        ($validated['sort'] === 'salary_low' ? 'Lowest salary' : 'Newest first'))
                                    }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($jobOffers->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No job offers found</h3>
                <p class="text-gray-600 mb-6">Try adjusting your search criteria or browse all available positions.</p>
                <a href="/" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    Back to Search
                </a>
            </div>
        @else
            <x-toolbar 
                :total="$jobOffers->total()"
                :currentPerPage="10"
                :perPageOptions="[10, 20, 30]"
                routeName="search"
                gridId="jobOffersGrid"
                storageKey="jobOffersGridLayout"
                :defaultColumns="2"
            />

            <div id="jobOffersGrid" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($jobOffers as $offer)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition border border-gray-200 hover:bg-gray-100">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <a href="{{ route('job.show', $offer->id) }}">
                                        <h3 class="text-xl font-semibold text-gray-900 mb-2 hover:text-blue-600 transition cursor-pointer">
                                            {{ $offer->title }}
                                        </h3>
                                    </a>
                                    <p class="text-base text-gray-700 font-medium">{{ $offer->company_name }}</p>
                                </div>
                                <span class="ml-4 px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 whitespace-nowrap">
                                    Active
                                </span>
                            </div>

                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                {{ Str::limit($offer->description, 150) }}
                            </p>

                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $offer->location->city }}
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ ucfirst(str_replace('-', ' ', $offer->employment_type)) }}
                                </div>

                                @if($offer->salary_min || $offer->salary_max)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        @if($offer->salary_min && $offer->salary_max)
                                            {{ number_format($offer->salary_min, 0) }} - {{ number_format($offer->salary_max, 0) }} {{ $offer->currency }}
                                        @elseif($offer->salary_min)
                                            From {{ number_format($offer->salary_min, 0) }} {{ $offer->currency }}
                                        @else
                                            Up to {{ number_format($offer->salary_max, 0) }} {{ $offer->currency }}
                                        @endif
                                    </div>
                                @endif

                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    {{ $offer->category->name }}
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-xs text-gray-500 mb-4 pt-4 border-t border-gray-100">
                                <span>Posted {{ $offer->created_at->diffForHumans() }}</span>
                                @if($offer->expires_at)
                                    <span>Expires {{ $offer->expires_at->format('M d, Y') }}</span>
                                @endif
                            </div>

                            <div class="flex gap-3">
                                <a href="{{ route('job.show', $offer->id) }}" class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition text-center">
                                    View Details
                                </a>
                                @auth
                                    @if(Auth::user()->account_type === 'job_seeker')
                                        <button class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition cursor-pointer">
                                            Apply Now
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($jobOffers->hasPages())
                <div class="mt-8">
                    {{ $jobOffers->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

@push('scripts')
<script>
    function toggleSearchForm() {
        const form = document.getElementById('search-form');
        const toggleText = document.getElementById('toggle-text');
        
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
            toggleText.textContent = 'Hide filters';
        } else {
            form.classList.add('hidden');
            toggleText.textContent = 'Show filters';
        }
    }
</script>
@endpush

@endsection
