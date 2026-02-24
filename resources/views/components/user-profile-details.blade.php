<div class="relative bg-gradient-to-r from-blue-600 to-indigo-700 h-32 rounded-t-lg"></div>

@php
    $isAdmin = Auth::check() && Auth::user()->account_type === 'admin';
    $isOwner = Auth::check() && Auth::id() === $user->id;
    $canSeePrivate = $isAdmin || $isOwner;
@endphp

<div class="px-8 pb-8 -mt-16">
    <div class="flex items-center gap-6 mb-6">
        <div
            class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-lg bg-white flex-shrink-0 relative z-10">
            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.svg') }}"
                alt="Profile Avatar" class="w-full h-full object-cover">
        </div>
        <div class="flex-1 mt-16">
            <div class="flex items-center gap-3 mb-2">
                <h2 class="text-2xl font-bold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h2>
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $user->account_type === 'job_seeker' ? 'bg-blue-100 text-blue-700' : ($user->account_type === 'employer' ? 'bg-emerald-100 text-emerald-700' : 'bg-purple-100 text-purple-700') }}">
                    {{ $user->account_type === 'job_seeker' ? 'Job Seeker' : ($user->account_type === 'employer' ? 'Employer' : 'Admin') }}
                </span>
            </div>
            <p class="text-gray-500 font-medium">@ {{ $user->username }}</p>
            @if($canSeePrivate)
                <div class="flex items-center text-sm text-gray-600 mt-1">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    {{ $user->email }}
                </div>
            @endif
        </div>
    </div>

    @if($user->bio && ($canSeePrivate || $user->isFieldVisible('bio')))
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">About</h3>
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                <p class="text-gray-600 whitespace-pre-line">{{ $user->bio }}</p>
            </div>
        </div>
    @endif

    @if($user->account_type === 'job_seeker' && $user->skills->count() > 0 && ($canSeePrivate || $user->isFieldVisible('skills')))
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Skills</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($user->skills as $skill)
                    <span
                        class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 text-sm font-medium rounded-full border border-blue-200">
                        {{ $skill->name }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid grid-cols-2 gap-6 mb-6">
        <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Personal Information</h3>
            <div class="space-y-2 bg-gray-50 p-4 rounded-lg">
                @if($canSeePrivate || $user->isFieldVisible('country'))
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Country:</span>
                        <span class="font-medium text-gray-900">{{ $user->country ?? 'N/A' }}</span>
                    </div>
                @endif
                @if($user->date_of_birth && ($canSeePrivate || $user->isFieldVisible('date_of_birth')))
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Date of Birth:</span>
                        <span class="font-medium text-gray-900">{{ $user->date_of_birth->format('M d, Y') }}</span>
                    </div>
                @endif
                @if($user->account_type === 'job_seeker' && ($canSeePrivate || $user->isFieldVisible('education')))
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Student:</span>
                        <span class="font-medium text-gray-900">{{ $user->is_student ? 'Yes' : 'No' }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Social Profiles</h3>
            <div class="space-y-2 bg-gray-50 p-4 rounded-lg">
                @if($canSeePrivate || $user->isFieldVisible('github_url'))
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-2 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                        </svg>
                        @if($user->github_url)
                            <a href="{{ $user->github_url }}" target="_blank"
                                class="text-blue-600 hover:underline truncate">{{ Str::limit($user->github_url, 30) }}</a>
                        @else
                            <span class="text-gray-400">Not connected</span>
                        @endif
                    </div>
                @endif
                @if($canSeePrivate || $user->isFieldVisible('linkedin_url'))
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-2 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                        </svg>
                        @if($user->linkedin_url)
                            <a href="{{ $user->linkedin_url }}" target="_blank"
                                class="text-blue-600 hover:underline truncate">{{ Str::limit($user->linkedin_url, 30) }}</a>
                        @else
                            <span class="text-gray-400">Not connected</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="border-t border-gray-200 pt-4 text-sm text-gray-500">
        <div class="flex justify-between mb-4">
            <span>Member since {{ $user->created_at->format('F Y') }}</span>
            <span>Last updated {{ $user->updated_at->diffForHumans() }}</span>
        </div>
        <a href="{{ route('user.show', $user->username) }}"
            class="block w-full px-4 py-2.5 bg-blue-600 text-white text-center font-medium rounded-lg hover:bg-blue-700 transition">
            Go to profile page
        </a>
    </div>
</div>