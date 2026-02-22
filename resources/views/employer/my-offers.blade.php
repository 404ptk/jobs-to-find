@extends('layouts.app')

@section('title', 'My Offers - Jobs to Find')

@section('content')
<div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-7xl mx-auto">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Job Offers</h1>
                <p class="text-gray-600 mt-1">Manage your posted job opportunities</p>
            </div>
            <a href="{{ route('offer.create') }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create New Offer
            </a>
        </div>

        @if($jobOffers->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No job offers yet</h3>
                <p class="text-gray-600">Start by creating your first job offer to attract talented candidates.</p>
            </div>
        @else
            <x-toolbar 
                :total="$jobOffers->total()"
                :currentPerPage="9"
                :perPageOptions="[6, 9, 12, 24]"
                routeName="my-offers"
                gridId="myOffersGrid"
                storageKey="myOffersGridLayout"
                :defaultColumns="3"
            />
            
            <div id="myOffersGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($jobOffers as $offer)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition border border-gray-200">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-2">
                                        {{ $offer->title }}
                                    </h3>
                                    <p class="text-sm text-gray-600">{{ $offer->company_name }}</p>
                                </div>
                                @if(!$offer->is_approved)
                                    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                        Pending Approval
                                    </span>
                                @else
                                    <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full {{ $offer->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $offer->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                @endif
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $offer->location->city }}, {{ $offer->location->country }}
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ ucfirst(str_replace('-', ' ', $offer->employment_type)) }}
                                </div>

                                @if($offer->salary_min || $offer->salary_max)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                            <div class="flex gap-2">
                                <a href="{{ route('job.show', $offer->id) }}" class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition text-center">
                                    View Details
                                </a>
                                <a href="{{ route('offer.applications', $offer->id) }}" class="px-4 py-2 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-lg hover:bg-emerald-200 transition flex items-center" title="Check applications">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('offer.edit', $offer->id) }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button data-action="delete" data-offer-id="{{ $offer->id }}" data-title="{{ $offer->title }}" class="delete-btn px-4 py-2 bg-gray-100 text-red-600 text-sm rounded-lg hover:bg-red-50 transition cursor-pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="text-gray-600 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    {{ number_format($offer->views_count) }} Views
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ number_format($offer->applications->count()) }} Applications
                                </span>
                            </div>
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

<div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
    <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 transform transition-all scale-95 opacity-0 border border-gray-300 pointer-events-auto" id="modal-content">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="ml-4 text-xl font-bold text-gray-900">Confirm Deletion</h3>
        </div>
        <p class="text-gray-600 mb-2">Are you sure you want to delete this job offer?</p>
        <p class="text-gray-900 font-semibold mb-4" id="offer-title"></p>
        <p class="text-gray-500 text-sm mb-6">This action cannot be undone and the offer will be permanently removed.</p>
        <div class="flex gap-3">
            <button id="modal-cancel" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition cursor-pointer">
                No, Cancel
            </button>
            <button id="modal-confirm" class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition cursor-pointer">
                Yes, Delete
            </button>
        </div>
    </div>
</div>

<script>
    let currentOfferId = null;
    const modal = document.getElementById('delete-modal');
    const modalContent = document.getElementById('modal-content');
    const modalCancel = document.getElementById('modal-cancel');
    const modalConfirm = document.getElementById('modal-confirm');
    const offerTitle = document.getElementById('offer-title');

    function showModal() {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    document.querySelectorAll('[data-action="delete"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            currentOfferId = this.getAttribute('data-offer-id');
            const title = this.getAttribute('data-title');
            offerTitle.textContent = title;
            showModal();
        });
    });

    modalCancel.addEventListener('click', function() {
        hideModal();
        currentOfferId = null;
    });

    modalConfirm.addEventListener('click', function() {
        if (currentOfferId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/offer/${currentOfferId}/delete`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            hideModal();
            currentOfferId = null;
        }
    });
</script>
@endsection
