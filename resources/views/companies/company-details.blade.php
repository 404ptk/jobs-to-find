@extends('layouts.app')

@section('title', $company->name . ' - Company Details')

@section('content')
    <div class="min-h-[calc(100vh-8rem)] py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="mb-6">
                <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-600 hover:text-blue-600 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-10 text-white">
                    <div class="flex flex-col md:flex-row md:items-start gap-6">
                        <div class="shrink-0">
                            @if($company->logo_path)
                                <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->name }}"
                                    class="w-24 h-24 rounded-xl object-cover bg-white p-1 shadow-lg">
                            @else
                                <div class="w-24 h-24 rounded-xl bg-white/15 border border-white/30 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1">
                            <h1 class="text-3xl font-bold mb-2">{{ $company->name }}</h1>
                            <div class="flex flex-wrap items-center gap-4 text-blue-100 text-sm">
                                <span class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    @if($company->location)
                                        {{ $company->location->city }}, {{ $company->location->country }}
                                    @else
                                        Location not specified
                                    @endif
                                </span>

                                @if($company->founded_at)
                                    <span class="inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Founded in {{ $company->founded_at }}
                                    </span>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-6">
                                <div class="bg-white/10 rounded-lg px-4 py-3">
                                    <p class="text-xs text-blue-100 uppercase">Active Offers</p>
                                    <p class="text-xl font-bold">{{ $company->active_offers_count }}</p>
                                </div>
                                <div class="bg-white/10 rounded-lg px-4 py-3">
                                    <p class="text-xs text-blue-100 uppercase">All Offers</p>
                                    <p class="text-xl font-bold">{{ $company->total_offers_count }}</p>
                                </div>
                                <div class="bg-white/10 rounded-lg px-4 py-3">
                                    <p class="text-xs text-blue-100 uppercase">NIP</p>
                                    <p class="text-sm font-semibold truncate">{{ $company->nip }}</p>
                                </div>
                                <div class="bg-white/10 rounded-lg px-4 py-3">
                                    <p class="text-xs text-blue-100 uppercase">Owner</p>
                                    <p class="text-sm font-semibold truncate">{{ $company->user->username ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">About Company</h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $company->description }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900">Open Positions</h2>
                    <span class="text-sm text-gray-500">{{ $offers->total() }} jobs</span>
                </div>

                @if($offers->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-600">No active offers from this company right now.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($offers as $offer)
                            <a href="{{ route('job.show', $offer->id) }}" class="block border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-sm transition">
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $offer->title }}</h3>
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p>{{ $offer->location->city }}, {{ $offer->location->country }}</p>
                                    <p>{{ $offer->employment_type === 'b2b' ? 'B2B' : ucfirst(str_replace('-', ' ', $offer->employment_type)) }}</p>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">Posted {{ $offer->created_at->diffForHumans() }}</p>
                            </a>
                        @endforeach
                    </div>

                    @if($offers->hasPages())
                        <div class="mt-6">
                            {{ $offers->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
