@extends('layouts.app')

@section('title', $jobOffer->title . ' - Jobs to Find')

@section('content')
<div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="mb-6">
            <a href="javascript:history.back()" class="inline-flex items-center text-gray-600 hover:text-blue-600 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to search
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-8 text-white">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold mb-3">{{ $jobOffer->title }}</h1>
                        <p class="text-xl text-blue-100 mb-4">{{ $jobOffer->company_name }}</p>
                        
                        <div class="flex flex-wrap gap-4 text-sm">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $jobOffer->location->city }}, {{ $jobOffer->location->country }}
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ ucfirst(str_replace('-', ' ', $jobOffer->employment_type)) }}
                            </div>
                            
                            @if($jobOffer->salary_range)
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $jobOffer->salary_range }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <span class="ml-4 px-4 py-2 bg-white text-green-600 rounded-full text-sm font-semibold">
                        Active
                    </span>
                </div>

                <div class="flex items-center gap-3 text-sm text-blue-100">
                    <span>Posted {{ $jobOffer->created_at->diffForHumans() }}</span>
                    @if($jobOffer->expires_at)
                        <span>•</span>
                        <span>Expires {{ $jobOffer->expires_at->format('F d, Y') }}</span>
                    @endif
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-8">
                        <section>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Job Description
                            </h2>
                            <div class="text-gray-700 leading-relaxed">
                                {!! nl2br(e($jobOffer->description)) !!}
                            </div>
                        </section>

                        @if($jobOffer->requirements)
                            <section>
                                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    Requirements
                                </h2>
                                <div class="text-gray-700 leading-relaxed bg-gray-50 p-6 rounded-lg">
                                    {!! nl2br(e($jobOffer->requirements)) !!}
                                </div>
                            </section>
                        @endif

                        @auth
                            @if(Auth::user()->account_type === 'job_seeker')
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Ready to apply?</h3>
                                    <p class="text-gray-700 mb-4">Submit your application for this position and our team will get back to you soon.</p>
                                    <button class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Apply Now
                                    </button>
                                </div>
                            @endif
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Want to apply?</h3>
                                <p class="text-gray-700 mb-4">Please log in or create an account to submit your application.</p>
                                <div class="flex gap-3">
                                    <a href="/login" class="flex-1 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition text-center">
                                        Login
                                    </a>
                                    <a href="/register" class="flex-1 px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition text-center">
                                        Register
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </div>

                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Job Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Category</p>
                                    <p class="font-medium text-gray-900">{{ $jobOffer->category->name }}</p>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-4">
                                    <p class="text-sm text-gray-600 mb-1">Location</p>
                                    <p class="font-medium text-gray-900">{{ $jobOffer->location->city }}</p>
                                    <p class="text-sm text-gray-600">{{ $jobOffer->location->region }}, {{ $jobOffer->location->country }}</p>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-4">
                                    <p class="text-sm text-gray-600 mb-1">Employment Type</p>
                                    <p class="font-medium text-gray-900">{{ ucfirst(str_replace('-', ' ', $jobOffer->employment_type)) }}</p>
                                </div>
                                
                                @if($jobOffer->salary_range)
                                    <div class="border-t border-gray-200 pt-4">
                                        <p class="text-sm text-gray-600 mb-1">Salary Range</p>
                                        <p class="font-medium text-gray-900">{{ $jobOffer->salary_range }}</p>
                                    </div>
                                @endif
                                
                                <div class="border-t border-gray-200 pt-4">
                                    <p class="text-sm text-gray-600 mb-1">Posted</p>
                                    <p class="font-medium text-gray-900">{{ $jobOffer->created_at->format('F d, Y') }}</p>
                                </div>
                                
                                @if($jobOffer->expires_at)
                                    <div class="border-t border-gray-200 pt-4">
                                        <p class="text-sm text-gray-600 mb-1">Expires</p>
                                        <p class="font-medium text-gray-900">{{ $jobOffer->expires_at->format('F d, Y') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">About Company</h3>
                            <p class="font-medium text-gray-900 mb-2">{{ $jobOffer->company_name }}</p>
                            <p class="text-sm text-gray-600">View all jobs from this company</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Share this job</h3>
                            <div class="flex gap-2">
                                <button class="flex-1 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition text-sm font-medium">
                                    Copy link
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
