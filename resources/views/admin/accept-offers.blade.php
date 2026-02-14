@extends('layouts.app')

@section('title', 'Accept Offers - Admin Panel')

@section('content')
<div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pending Job Offers</h1>
            <p class="text-gray-600 mt-1">Review and approve job offers before they go live</p>
        </div>

        <x-toolbar
            :total="$pendingOffers->total()"
            routeName="admin.accept-offers"
            gridId="acceptOffersGrid"
            :defaultColumns="3"
        />

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($pendingOffers->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">All caught up!</h3>
                <p class="text-gray-600">There are no pending job offers to review at the moment.</p>
            </div>
        @else
            <div id="acceptOffersGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pendingOffers as $offer)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition border-2 border-yellow-200">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <span class="inline-block px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800 mb-2">
                                        Pending Approval
                                    </span>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-2">
                                        {{ $offer->title }}
                                    </h3>
                                    <p class="text-sm text-gray-600">{{ $offer->company_name }}</p>
                                </div>
                            </div>

                            <div class="space-y-2 mb-4">
                                <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                    <div class="flex items-center text-sm font-medium text-gray-900 mb-1">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $offer->user->first_name }} {{ $offer->user->last_name }}
                                    </div>
                                    <div class="text-xs text-gray-500 ml-6">{{ '@' . $offer->user->username }}</div>
                                    <div class="flex gap-3 mt-2 ml-6">
                                        @php
                                            $userActiveOffers = \App\Models\JobOffer::where('user_id', $offer->user_id)->where('is_active', true)->where('is_approved', true)->count();
                                            $userPendingOffers = \App\Models\JobOffer::where('user_id', $offer->user_id)->where('is_active', true)->where('is_approved', false)->count();
                                        @endphp
                                        <span class="text-xs text-green-600 font-medium">
                                            <span class="font-bold">{{ $userActiveOffers }}</span> active
                                        </span>
                                        <span class="text-xs text-yellow-600 font-medium">
                                            <span class="font-bold">{{ $userPendingOffers }}</span> pending
                                        </span>
                                    </div>
                                </div>

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
                                <span>Submitted {{ $offer->created_at->diffForHumans() }}</span>
                            </div>

                            <div class="flex gap-2">
                                <button onclick="openOfferModal({{ $offer->id }})" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition text-center cursor-pointer">
                                    View Details
                                </button>
                            </div>

                            <div class="flex gap-2 mt-3">
                                <form action="{{ route('admin.approve-offer', $offer->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition cursor-pointer">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.reject-offer', $offer->id) }}" method="POST" class="flex-1" id="reject-form-{{ $offer->id }}">
                                    @csrf
                                    <button type="button" data-action="reject" data-offer-id="{{ $offer->id }}" class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition cursor-pointer">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($pendingOffers->hasPages())
                <div class="mt-8">
                    {{ $pendingOffers->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<div id="reject-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
    <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 transform transition-all scale-95 opacity-0 border border-gray-300 pointer-events-auto" id="modal-content">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="ml-4 text-xl font-bold text-gray-900">Confirm Rejection</h3>
        </div>
        <p class="text-gray-600 mb-6">Are you sure you want to reject this job offer? This action cannot be undone and the offer will be deactivated.</p>
        <div class="flex gap-3">
            <button id="modal-cancel" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition cursor-pointer">
                No, Cancel
            </button>
            <button id="modal-confirm" class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition cursor-pointer">
                Yes, Reject
            </button>
        </div>
    </div>
</div>

<div id="offer-details-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
    <div class="fixed inset-0 bg-transparent backdrop-blur-sm transition-opacity" onclick="hideOfferModal()"></div>
    <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto transform transition-all scale-95 opacity-0 pointer-events-auto relative" id="offer-modal-content">
        <button onclick="hideOfferModal()" class="absolute top-4 right-4 text-white hover:text-gray-200 z-10 cursor-pointer">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div id="offer-modal-body">
            <!-- Content will be loaded here -->
            <div class="flex justify-center items-center h-64">
                <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<script>
    let currentFormId = null;
    const modal = document.getElementById('reject-modal');
    const modalContent = document.getElementById('modal-content');
    const modalCancel = document.getElementById('modal-cancel');
    const modalConfirm = document.getElementById('modal-confirm');

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
        currentFormId = null;
    }

    document.querySelectorAll('[data-action="reject"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const offerId = this.getAttribute('data-offer-id');
            currentFormId = 'reject-form-' + offerId;
            showModal();
        });
    });

    modalCancel.addEventListener('click', hideModal);

    modalConfirm.addEventListener('click', function() {
        if (currentFormId) {
            document.getElementById(currentFormId).submit();
        }
    });

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            hideModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            hideModal();
        }
    });

    const offerModal = document.getElementById('offer-details-modal');
    const offerModalContent = document.getElementById('offer-modal-content');
    const offerModalBody = document.getElementById('offer-modal-body');

    function openOfferModal(offerId) {
        offerModal.classList.remove('hidden');
        // Reset content to loader
        offerModalBody.innerHTML = `
            <div class="flex justify-center items-center h-64">
                <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        `;
        
        setTimeout(() => {
            offerModalContent.classList.remove('scale-95', 'opacity-0');
            offerModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);

        // Fetch partial content
        fetch(`/admin/offer/${offerId}/partial`)
            .then(response => response.text())
            .then(html => {
                offerModalBody.innerHTML = html;
            })
            .catch(error => {
                offerModalBody.innerHTML = '<p class="text-red-500 text-center">Error loading details. Please try again.</p>';
                console.error('Error:', error);
            });
    }

    function hideOfferModal() {
        offerModalContent.classList.remove('scale-100', 'opacity-100');
        offerModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            offerModal.classList.add('hidden');
        }, 200);
    }
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !offerModal.classList.contains('hidden')) {
            hideOfferModal();
        }
    });
</script>

@endsection
