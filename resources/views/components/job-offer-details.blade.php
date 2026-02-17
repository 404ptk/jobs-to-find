@props(['jobOffer'])
<div class="bg-white rounded-lg shadow-lg overflow-hidden h-full">
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
                    
                    @if($jobOffer->salary_min || $jobOffer->salary_max)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            @if($jobOffer->salary_min && $jobOffer->salary_max)
                                {{ number_format($jobOffer->salary_min, 0) }} - {{ number_format($jobOffer->salary_max, 0) }} {{ $jobOffer->currency }}
                            @elseif($jobOffer->salary_min)
                                From {{ number_format($jobOffer->salary_min, 0) }} {{ $jobOffer->currency }}
                            @else
                                Up to {{ number_format($jobOffer->salary_max, 0) }} {{ $jobOffer->currency }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            
            @if(!$jobOffer->is_approved)
                <span class="ml-4 px-4 py-2 bg-yellow-500 text-white rounded-full text-sm font-semibold shadow-md">
                    Pending Approval
                </span>
            @else
                <span class="ml-4 px-4 py-2 bg-white text-green-600 rounded-full text-sm font-semibold">
                    Active
                </span>
            @endif
        </div>

        <div class="flex items-center gap-3 text-sm text-blue-100">
            <span>Posted {{ $jobOffer->created_at->diffForHumans() }}</span>
            @if($jobOffer->expires_at)
                <span>•</span>
                <span>Expires {{ $jobOffer->expires_at->format('F d, Y') }}</span>
            @endif
        </div>
    </div>

    @if($jobOffer->expires_at)
        @php
            $now = \Carbon\Carbon::now();
            $expiresAt = \Carbon\Carbon::parse($jobOffer->expires_at);
            $totalDays = \Carbon\Carbon::parse($jobOffer->created_at)->diffInDays($expiresAt);
            $daysLeft = (int) $now->diffInDays($expiresAt, false);
            $percentage = $totalDays > 0 ? max(0, min(100, ($daysLeft / $totalDays) * 100)) : 0;
            
            if ($daysLeft < 0) {
                $barColor = 'bg-gray-400';
                $textColor = 'text-gray-700';
                $message = 'This offer has expired';
            } elseif ($daysLeft <= 3) {
                $barColor = 'bg-red-500';
                $textColor = 'text-red-700';
                $message = $daysLeft == 0 ? 'Expires today!' : ($daysLeft == 1 ? 'Expires tomorrow!' : "Expires in {$daysLeft} days");
            } elseif ($daysLeft <= 7) {
                $barColor = 'bg-orange-500';
                $textColor = 'text-orange-700';
                $message = "Expires in {$daysLeft} days";
            } elseif ($daysLeft <= 14) {
                $barColor = 'bg-yellow-500';
                $textColor = 'text-yellow-700';
                $message = "Expires in {$daysLeft} days";
            } else {
                $barColor = 'bg-green-500';
                $textColor = 'text-green-700';
                $message = "Expires in {$daysLeft} days";
            }
        @endphp

        <div class="px-8 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 {{ $textColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium {{ $textColor }}">{{ $message }}</span>
                </div>
                <span class="text-xs text-gray-500">{{ $expiresAt->format('M d, Y') }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
            </div>
        </div>
    @endif

    <div class="p-8">
        @if(!$jobOffer->is_approved)
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 mb-6 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Pending Approval</h3>
                        <p class="text-gray-700">
                            @if(Auth::check() && Auth::user()->account_type === 'admin')
                                This job offer is awaiting admin approval. You can approve or reject it below.
                            @else
                                This job offer is currently under review by our administrators. It will be publicly visible once approved.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endif

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
                
                <div class="lg:hidden mt-8">
                     <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Job Details</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Category</p>
                                <p class="font-medium text-gray-900">{{ $jobOffer->category->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Employment Type</p>
                                <p class="font-medium text-gray-900">{{ ucfirst(str_replace('-', ' ', $jobOffer->employment_type)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Experience Level</p>
                                <p class="font-medium text-gray-900">{{ ucfirst($jobOffer->experience_level) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Remote Work</p>
                                <p class="font-medium text-gray-900">{{ $jobOffer->is_remote ? 'Remote' : 'On-site' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">About the Company</h3>
                        <div class="flex items-center mb-4">
                            <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xl mr-4">
                                {{ strtoupper(substr($jobOffer->company_name, 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $jobOffer->company_name }}</h4>
                                <a href="#" class="text-sm text-blue-600 hover:underline">View all jobs</a>
                            </div>
                        </div>
                        @if($jobOffer->company_website)
                            <a href="{{ $jobOffer->company_website }}" target="_blank" class="text-sm text-gray-600 hover:text-blue-600 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                Visit Website
                            </a>
                        @endif
                    </div>
                </div>

                @auth
                    @if(Auth::user()->account_type === 'admin' && !$jobOffer->is_approved)
                        <div class="mt-8 bg-yellow-50 border-2 border-yellow-400 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                Admin Actions - Pending Approval
                            </h3>
                            <p class="text-gray-700 mb-4">This job offer is awaiting approval. Review and approve or reject it.</p>
                            <div class="flex gap-3">
                                <form action="{{ route('admin.approve-offer', $jobOffer->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition flex items-center justify-center cursor-pointer">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.reject-offer', $jobOffer->id) }}" method="POST" class="flex-1" id="reject-form-{{ $jobOffer->id }}">
                                    @csrf
                                    <button type="button" data-action="reject" data-offer-id="{{ $jobOffer->id }}" class="w-full px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition flex items-center justify-center cursor-pointer">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif(Auth::user()->account_type === 'job_seeker')
                        @php
                            $existingApplication = \App\Models\Application::where('user_id', Auth::id())
                                ->where('job_offer_id', $jobOffer->id)
                                ->first();
                        @endphp
                        
                        @if($existingApplication)
                            <div class="mt-8 bg-green-50 border border-green-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Application Status</h3>
                                <div class="flex items-center justify-center py-2">
                                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-lg font-semibold text-green-700">You already applied</span>
                                </div>
                                <p class="text-gray-600 text-center mt-2">Applied on {{ $existingApplication->created_at->format('F j, Y') }}</p>
                                <div class="mt-4 text-center">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                        @if($existingApplication->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($existingApplication->status === 'reviewed') bg-blue-100 text-blue-800
                                        @elseif($existingApplication->status === 'accepted') bg-green-100 text-green-800
                                        @elseif($existingApplication->status === 'rejected') bg-red-100 text-red-800
                                        @endif">
                                        Status: {{ ucfirst($existingApplication->status) }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Ready to apply?</h3>
                                <p class="text-gray-700 mb-4">Submit your application for this position and our team will get back to you soon.</p>
                                <button onclick="showApplyModal({{ $jobOffer->id }})" class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition flex items-center justify-center cursor-pointer">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Apply Now
                                </button>
                            </div>
                        @endif
                    @elseif(Auth::user()->account_type === 'admin')
                        <div class="mt-8 bg-red-50 border border-red-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Administrator Actions</h3>
                            <p class="text-gray-700 mb-4">Manage this job offer</p>
                            <button class="w-full px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition flex items-center justify-center cursor-pointer">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete Offer
                            </button>
                        </div>
                    @endif
                @else
                    <div class="mt-8 bg-gray-50 border border-gray-200 rounded-lg p-6">
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

            <div class="hidden lg:block space-y-6">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Job Details</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Category</p>
                            <p class="font-medium text-gray-900">{{ $jobOffer->category->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Employment Type</p>
                            <p class="font-medium text-gray-900">{{ ucfirst(str_replace('-', ' ', $jobOffer->employment_type)) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Experience Level</p>
                            <p class="font-medium text-gray-900">{{ ucfirst($jobOffer->experience_level) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Remote Work</p>
                            <p class="font-medium text-gray-900">{{ $jobOffer->is_remote ? 'Remote' : 'On-site' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">About the Company</h3>
                    <div class="flex items-center mb-4">
                        <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xl mr-4">
                            {{ strtoupper(substr($jobOffer->company_name, 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $jobOffer->company_name }}</h4>
                            <a href="#" class="text-sm text-blue-600 hover:underline">View all jobs</a>
                        </div>
                    </div>
                    @if($jobOffer->company_website)
                        <a href="{{ $jobOffer->company_website }}" target="_blank" class="text-sm text-gray-600 hover:text-blue-600 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Visit Website
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>