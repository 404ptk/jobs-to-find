@extends('layouts.app')

@section('title', 'My Applications - Jobs to Find')

@section('content')
<div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Applications</h1>
            <p class="text-gray-600 mt-1">Track the status of your job applications</p>
        </div>

        <x-toolbar
            :total="$applications->total()"
            routeName="my-applications"
            gridId="applicationsGrid"
            :defaultColumns="3"
        />

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($applications->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No applications yet</h3>
                <p class="text-gray-600 mb-6">You haven't applied to any jobs yet. Start exploring opportunities!</p>
                <a href="{{ route('search') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Browse Jobs
                </a>
            </div>
        @else
            <div id="applicationsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($applications as $application)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition border border-gray-200">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'reviewed' => 'bg-blue-100 text-blue-800',
                                            'accepted' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusColor = $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-block px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }} mb-2">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-2">
                                        {{ $application->jobOffer->title }}
                                    </h3>
                                    <p class="text-sm text-gray-600">{{ $application->jobOffer->company_name }}</p>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $application->jobOffer->location->city }}, {{ $application->jobOffer->location->country }}
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ ucfirst(str_replace('-', ' ', $application->jobOffer->employment_type)) }}
                                </div>

                                @if($application->jobOffer->salary_min || $application->jobOffer->salary_max)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        @if($application->jobOffer->salary_min && $application->jobOffer->salary_max)
                                            {{ number_format($application->jobOffer->salary_min, 0) }} - {{ number_format($application->jobOffer->salary_max, 0) }} {{ $application->jobOffer->currency }}
                                        @elseif($application->jobOffer->salary_min)
                                            From {{ number_format($application->jobOffer->salary_min, 0) }} {{ $application->jobOffer->currency }}
                                        @else
                                            Up to {{ number_format($application->jobOffer->salary_max, 0) }} {{ $application->jobOffer->currency }}
                                        @endif
                                    </div>
                                @endif

                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    {{ $application->jobOffer->category->name }}
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-100">
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                    <span>Applied {{ $application->created_at->diffForHumans() }}</span>
                                </div>

                                <button onclick="openJobModal({{ $application->jobOffer->id }})" class="block w-full px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition text-center cursor-pointer">
                                    View Job Details
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($applications->hasPages())
                <div class="mt-8">
                    {{ $applications->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

@foreach($applications as $application)
    <div id="job-modal-{{ $application->jobOffer->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-opacity-30 backdrop-blur-sm transition-opacity" onclick="hideJobModal({{ $application->jobOffer->id }})"></div>
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto transform transition-all scale-95 opacity-0 relative" id="job-modal-content-{{ $application->jobOffer->id }}">
            <button onclick="hideJobModal({{ $application->jobOffer->id }})" class="absolute top-4 right-4 text-white hover:text-gray-200 z-20 cursor-pointer bg-black bg-opacity-30 rounded-full p-1 hover:bg-opacity-50 transition">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <x-job-offer-details :jobOffer="$application->jobOffer" />
        </div>
    </div>
@endforeach

<script>
    function openJobModal(jobId) {
        const modal = document.getElementById(`job-modal-${jobId}`);
        const modalContent = document.getElementById(`job-modal-content-${jobId}`);
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideJobModal(jobId) {
        const modal = document.getElementById(`job-modal-${jobId}`);
        const modalContent = document.getElementById(`job-modal-content-${jobId}`);
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('[id^="job-modal-"]:not(.hidden)');
            openModals.forEach(modal => {
                const jobId = modal.id.replace('job-modal-', '');
                hideJobModal(jobId);
            });
        }
    });
</script>

@endsection
