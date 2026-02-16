@extends('layouts.app')

@section('title', 'Profile - Jobs to Find')

@php
    $currentUser = Auth::user();
    $realIsOwner = Auth::check() && Auth::id() === $user->id;
    $isAdmin = Auth::check() && $currentUser->account_type === 'admin';
    
    $isPreview = request()->has('preview') && request()->get('preview') == '1';
    
    $isOwner = $realIsOwner && !$isPreview;
    $isAdminView = $isAdmin && !$isPreview;

    $shouldShow = function($field) use ($isOwner, $isAdminView, $user) {
        return $isOwner || $isAdminView || $user->isFieldVisible($field);
    };
@endphp

@section('head')
    @if($isOwner)
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <style>
        .cropper-view-box,
        .cropper-face {
            border-radius: 50%;
        }
    </style>
    @endif
@endsection

@section('content')
<div class="min-h-[calc(100vh-8rem)] py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-4xl mx-auto">
        
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $isOwner ? 'My Profile' : $user->first_name . '\'s Profile' }}</h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $isOwner ? 'Manage your personal information and account settings.' : 'View user profile details.' }}
                </p>
            </div>
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold tracking-wide uppercase {{ $user->account_type === 'job_seeker' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                {{ $user->account_type === 'job_seeker' ? 'Job Seeker' : 'Employer' }}
            </span>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 flex items-start">
                <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            @if($isOwner)
            <form action="{{ route('profile.update') }}" method="POST" id="profileForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            @endif

                <div class="relative bg-gradient-to-r from-blue-600 to-indigo-700 h-32 sm:h-48">
                </div>
                
                <div class="px-8 pb-8">
                    <div class="relative flex flex-col sm:flex-row items-center sm:items-end -mt-16 mb-8 gap-6">
                        <div class="relative group">
                            <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-full overflow-hidden border-4 border-white shadow-lg bg-white">
                                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.svg') }}" 
                                     alt="Profile Avatar" 
                                     class="w-full h-full object-cover">
                            </div>
                            @if($isOwner)
                            <label for="avatar" class="absolute bottom-1 right-1 bg-white text-gray-700 hover:text-blue-600 p-2 rounded-full shadow-md border border-gray-200 cursor-pointer transition-all duration-200 transform translate-y-1/4 sm:translate-y-0" title="Change Avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" >
                            </label>
                            @endif
                        </div>
                        <div class="text-center sm:text-left flex-1 mt-2 sm:mt-0">
                            <h2 class="text-2xl font-bold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h2>
                            <p class="text-gray-500 font-medium">@ {{ $user->username }}</p>
                            
                            <div class="mt-2 flex items-center justify-center sm:justify-start text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ $user->email }}
                                @if($isOwner)
                                <span class="mx-2 text-gray-300">|</span>
                                <span class="flex items-center text-gray-500 text-xs" title="Private">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    Private
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @error('avatar')
                        <p class="mb-6 text-sm text-center sm:text-left text-red-500 bg-red-50 p-2 rounded">{{ $message }}</p>
                    @enderror

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        
                        <div class="lg:col-span-2 space-y-8">
                            
                            @if($shouldShow('bio'))
                            <div id="bio_container" class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                        About Me
                                    </h3>
                                    @if($isOwner)
                                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                                        <input type="hidden" name="privacy_settings[bio]" value="0">
                                        <input type="checkbox" name="privacy_settings[bio]" value="1" id="privacy_bio" {{ $user->isFieldVisible('bio') ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                        <label for="privacy_bio" class="cursor-pointer">Public</label>
                                    </div>
                                    @endif
                                </div>
                                
                                <div id="bio_display" class="prose prose-sm max-w-none text-gray-600 bg-gray-50 p-4 rounded-xl border border-gray-100 min-h-[5rem]">
                                    @if($user->bio)
                                        <p class="whitespace-pre-line">{{ $user->bio }}</p>
                                    @else
                                        <p class="text-gray-400 italic">Tell us a little bit about yourself...</p>
                                    @endif
                                </div>
                                @if($isOwner)
                                <div id="bio_edit" class="hidden">
                                    <textarea 
                                        id="bio" 
                                        name="bio" 
                                        rows="5"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm @error('bio') border-red-500 @enderror"
                                        maxlength="1000"
                                        placeholder="I am a passionate developer..."
                                    >{{ old('bio', $user->bio) }}</textarea>
                                    <div class="mt-2 flex justify-between items-center">
                                        @error('bio')
                                            <p class="text-sm text-red-500">{{ $message }}</p>
                                        @else
                                            <span></span>
                                        @enderror
                                        <p class="text-xs text-gray-400">Max 1000 characters</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                            
                            <hr class="border-gray-100">

                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                        Skills
                                    </h3>
                                    @if($isOwner)
                                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                                        <input type="hidden" name="privacy_settings[skills]" value="0">
                                        <input type="checkbox" name="privacy_settings[skills]" value="1" id="privacy_skills" {{ $user->isFieldVisible('skills') ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                        <label for="privacy_skills" class="cursor-pointer">Public</label>
                                    </div>
                                    @endif
                                </div>
                                
                                @if($shouldShow('skills'))
                                <div id="skills_container">
                                    <div id="skills_display">
                                        @php
                                            $userSkills = $user->skills;
                                        @endphp
                                        @if($userSkills->count() > 0)
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($userSkills as $skill)
                                                    <span class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 text-sm font-medium rounded-full border border-blue-200">
                                                        {{ $skill->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-gray-400 italic bg-gray-50 p-4 rounded-xl border border-gray-100">No skills added yet...</p>
                                        @endif
                                    </div>
                                    @if($isOwner)
                                    <div id="skills_edit" class="hidden">
                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                                            <p class="text-xs text-gray-600 mb-3">Select up to 20 skills from the list below:</p>
                                            <div class="max-h-60 overflow-y-auto space-y-1">
                                                @if(isset($availableSkills) && $availableSkills->count() > 0)
                                                    @foreach($availableSkills as $skill)
                                                        <label class="flex items-center p-2 hover:bg-white rounded-lg transition cursor-pointer">
                                                            <input type="checkbox" 
                                                                   name="skills[]" 
                                                                   value="{{ $skill->id }}" 
                                                                   {{ $userSkills->contains($skill->id) ? 'checked' : '' }}
                                                                   class="skill-checkbox rounded text-blue-600 focus:ring-blue-500 mr-3">
                                                            <span class="text-sm text-gray-700">{{ $skill->name }}</span>
                                                        </label>
                                                    @endforeach
                                                @else
                                                    <p class="text-sm text-gray-500 p-4">No skills available. Please contact administrator.</p>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-500 mt-3">
                                                <span id="skills-count">{{ $userSkills->count() }}</span> / 20 skills selected
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>

                            <hr class="border-gray-100">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($isOwner || $isAdminView)
                                <div id="first_name_container">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                                    <div id="first_name_display" class="py-2.5 px-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800 font-medium">
                                        {{ $user->first_name }}
                                    </div>
                                    @if($isOwner)
                                    <div id="first_name_edit" class="hidden">
                                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm @error('first_name') border-red-500 @enderror" required>
                                    </div>
                                    @endif
                                </div>

                                <div id="last_name_container">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                                    <div id="last_name_display" class="py-2.5 px-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800 font-medium">
                                        {{ $user->last_name }}
                                    </div>
                                    @if($isOwner)
                                    <div id="last_name_edit" class="hidden">
                                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm @error('last_name') border-red-500 @enderror" required>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                @if($shouldShow('country'))
                                <div id="country_container">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-sm font-medium text-gray-700">Country</label>
                                        @if($isOwner)
                                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                                            <input type="hidden" name="privacy_settings[country]" value="0">
                                            <input type="checkbox" name="privacy_settings[country]" value="1" id="privacy_country" {{ $user->isFieldVisible('country') ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                            <label for="privacy_country" class="cursor-pointer">Public</label>
                                        </div>
                                        @endif
                                    </div>
                                    <div id="country_display" class="py-2.5 px-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800">
                                        {{ $user->country }}
                                    </div>
                                    @if($isOwner)
                                    <div id="country_edit" class="hidden">
                                        <input type="text" id="country" name="country" value="{{ old('country', $user->country) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm @error('country') border-red-500 @enderror" required>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                @if($shouldShow('date_of_birth'))
                                <div id="date_of_birth_container">
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-sm font-medium text-gray-700">
                                            Date of Birth
                                        </label>
                                        @if($isOwner)
                                        <div class="flex items-center space-x-2 text-xs text-gray-500">
                                            <input type="hidden" name="privacy_settings[date_of_birth]" value="0">
                                            <input type="checkbox" name="privacy_settings[date_of_birth]" value="1" id="privacy_dob" {{ $user->isFieldVisible('date_of_birth') ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                            <label for="privacy_dob" class="cursor-pointer">Public</label>
                                        </div>
                                        @endif
                                    </div>
                                    <div id="date_of_birth_display" class="py-2.5 px-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-800 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $user->date_of_birth ? $user->date_of_birth->format('F d, Y') : 'Not provided' }}
                                    </div>
                                    @if($isOwner)
                                    <div id="date_of_birth_edit" class="hidden">
                                        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}" max="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm @error('date_of_birth') border-red-500 @enderror">
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-6">
                            
                            @if($user->account_type === 'job_seeker')
                                <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                                    <h3 class="text-sm font-bold text-blue-900 uppercase tracking-wide mb-3 flex items-center justify-between">
                                        Education Status
                                        @if($isOwner)
                                        <div class="flex items-center space-x-2 text-xs text-blue-700 font-normal normal-case">
                                            <input type="hidden" name="privacy_settings[education]" value="0">
                                            <input type="checkbox" name="privacy_settings[education]" value="1" id="privacy_education" {{ $user->isFieldVisible('education') ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                            <label for="privacy_education" class="cursor-pointer">Public</label>
                                        </div>
                                        @endif
                                    </h3>
                                    
                                    @if($shouldShow('education'))
                                    <div id="is_student_container">
                                        <div id="is_student_display">
                                            @if($user->is_student)
                                                <div class="flex items-center text-blue-700">
                                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/></svg>
                                                    </div>
                                                    <span class="font-medium">Currently a Student</span>
                                                </div>
                                            @else
                                                <div class="flex items-center text-gray-500">
                                                    <span class="text-sm">Not currently a student</span>
                                                </div>
                                            @endif
                                        </div>
                                        @if($isOwner)
                                        <div id="is_student_edit" class="hidden">
                                            <label class="flex items-start cursor-pointer p-2 hover:bg-white rounded-lg transition">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" name="is_student" id="is_student" value="1" {{ old('is_student', $user->is_student) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label for="is_student" class="font-medium text-gray-700">I am currently a student</label>
                                                </div>
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            @endif

                            <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4 flex items-center">
                                    Social Profiles
                                </h3>
                                
                                <div class="space-y-4">
                                    @if($shouldShow('github_url'))
                                    <div id="github_url_container">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-gray-700" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                                <span class="text-sm font-medium text-gray-700">GitHub</span>
                                            </div>
                                            @if($isOwner)
                                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                <input type="hidden" name="privacy_settings[github_url]" value="0">
                                                <input type="checkbox" name="privacy_settings[github_url]" value="1" id="privacy_github" {{ $user->isFieldVisible('github_url') ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                                <label for="privacy_github" class="cursor-pointer">Public</label>
                                            </div>
                                            @endif
                                        </div>
                                        <div id="github_url_display">
                                            @if($user->github_url)
                                                <a href="{{ $user->github_url }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 hover:underline truncate block w-full" title="{{ $user->github_url }}">
                                                    {{ Str::limit($user->github_url, 30) }}
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-400">Not connected</span>
                                            @endif
                                        </div>
                                        @if($isOwner)
                                        <div id="github_url_edit" class="hidden mt-1">
                                            <input type="url" id="github_url" name="github_url" value="{{ old('github_url', $user->github_url) }}" placeholder="https://github.com/username" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            @error('github_url')
                                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                    
                                    <hr class="border-gray-100">

                                    @if($shouldShow('linkedin_url'))
                                    <div id="linkedin_url_container">
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-blue-700" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                                <span class="text-sm font-medium text-gray-700">LinkedIn</span>
                                            </div>
                                            @if($isOwner)
                                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                <input type="hidden" name="privacy_settings[linkedin_url]" value="0">
                                                <input type="checkbox" name="privacy_settings[linkedin_url]" value="1" id="privacy_linkedin" {{ $user->isFieldVisible('linkedin_url') ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                                <label for="privacy_linkedin" class="cursor-pointer">Public</label>
                                            </div>
                                            @endif
                                        </div>
                                        <div id="linkedin_url_display">
                                            @if($user->linkedin_url)
                                                <a href="{{ $user->linkedin_url }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 hover:underline truncate block w-full" title="{{ $user->linkedin_url }}">
                                                    {{ Str::limit($user->linkedin_url, 30) }}
                                                </a>
                                            @else
                                                <span class="text-sm text-gray-400">Not connected</span>
                                            @endif
                                        </div>
                                        @if($isOwner)
                                        <div id="linkedin_url_edit" class="hidden mt-1">
                                            <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $user->linkedin_url) }}" placeholder="https://linkedin.com/in/username" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            @error('linkedin_url')
                                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            @if($user->cv_path)
                                @if($shouldShow('cv_path'))
                                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3 flex items-center justify-between">
                                        Resume
                                        @if($isOwner)
                                        <div class="flex items-center space-x-2 text-xs text-gray-500 normal-case font-normal">
                                            <input type="hidden" name="privacy_settings[cv_path]" value="0">
                                            <input type="checkbox" name="privacy_settings[cv_path]" value="1" id="privacy_cv" {{ $user->isFieldVisible('cv_path') ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                                            <label for="privacy_cv" class="cursor-pointer">Public</label>
                                        </div>
                                        @endif
                                    </h3>
                                    <a href="{{ $user->cv_path }}" class="flex items-center justify-center w-full px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:text-blue-600 transition shadow-sm font-medium text-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Download CV
                                    </a>
                                </div>
                                @endif
                            @endif
                            
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-8 border-t border-gray-100 flex flex-col sm:flex-row justify-end items-center gap-4">
                        <div id="view-mode" class="w-full sm:w-auto flex flex-col sm:flex-row gap-4">
                            @if($realIsOwner && $isPreview)
                            @else
                                <a href="/" class="text-center sm:text-left text-gray-500 hover:text-gray-700 font-medium px-4 py-2">
                                    Back to Home
                                </a>
                            @endif
                            @if($realIsOwner)
                                @if($isPreview)
                                    <a href="{{ route('profile') }}" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow-sm flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        Back to edit profile
                                    </a>
                                @else
                                    <a href="{{ route('profile', ['preview' => 1]) }}" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition shadow-sm flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Preview
                                    </a>
                                    <button type="button" onclick="enableEditMode()" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow-sm flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Edit Profile
                                    </button>
                                @endif
                            @endif
                        </div>
                        
                        @if($isOwner)
                        <div id="edit-mode" class="hidden w-full sm:w-auto flex flex-col sm:flex-row gap-3">
                            <button type="button" onclick="cancelEditMode()" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition shadow-sm">
                                Cancel
                            </button>
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition shadow-sm flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Save Changes
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            @if($isOwner)
            </form>
            @endif
        </div>
    </div>
</div>

@if($isOwner)
<div id="avatar-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-white/10 backdrop-blur-md transition-opacity" aria-hidden="true"></div>

    <div class="relative bg-white rounded-xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all scale-100">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900" id="modal-title">
                Adjust Profile Picture
            </h3>
            <button type="button" id="close-modal-x" class="text-gray-400 hover:text-gray-500 cursor-pointer">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6">
            <div class="relative w-full h-[400px] bg-slate-100 rounded-lg overflow-hidden border border-gray-200">
                <img id="avatar-image-preview" class="max-w-full" src="">
            </div>
            
            <div class="mt-4 flex items-center justify-center space-x-4">
                <button type="button" id="zoom-out" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition cursor-pointer" title="Zoom Out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7" />
                    </svg>
                </button>
                <button type="button" id="zoom-in" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition cursor-pointer" title="Zoom In">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                    </svg>
                </button>
                <div class="w-px h-6 bg-gray-300 mx-2"></div>
                <button type="button" id="rotate-left" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition cursor-pointer" title="Rotate Left">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                    </svg>
                </button>
                <button type="button" id="rotate-right" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-full transition cursor-pointer" title="Rotate Right">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10h-10a8 8 0 00-8 8v2M21 10l-6 6m6-6l-6-6" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
            <button type="button" id="cancel-crop" class="px-4 py-2 bg-white text-gray-700 font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition shadow-sm cursor-pointer">
                Cancel
            </button>
            <button type="button" id="crop-button" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow-sm flex items-center cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Avatar
            </button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    const avatarInput = document.getElementById('avatar');
    const avatarModal = document.getElementById('avatar-modal');
    const avatarImage = document.getElementById('avatar-image-preview');
    let cropper = null;

    function closeAvatarModal() {
        avatarModal.classList.add('hidden');
        avatarInput.value = '';
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    }

    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                alert('File size exceeds 2MB limit.');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                avatarImage.src = e.target.result;
                avatarModal.classList.remove('hidden');
                
                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(avatarImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.8,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                    background: false,
                });
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('zoom-in').addEventListener('click', function() {
        if(cropper) cropper.zoom(0.1);
    });
    
    document.getElementById('zoom-out').addEventListener('click', function() {
        if(cropper) cropper.zoom(-0.1);
    });

    document.getElementById('rotate-left').addEventListener('click', function() {
        if(cropper) cropper.rotate(-90);
    });
    
    document.getElementById('rotate-right').addEventListener('click', function() {
        if(cropper) cropper.rotate(90);
    });

    document.getElementById('cancel-crop').addEventListener('click', closeAvatarModal);
    document.getElementById('close-modal-x').addEventListener('click', closeAvatarModal);

    document.getElementById('crop-button').addEventListener('click', function() {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400,
        });

        canvas.toBlob(function(blob) {
            const file = new File([blob], "avatar.jpg", { type: "image/jpeg" });
            
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            avatarInput.files = dataTransfer.files;

            avatarModal.classList.add('hidden');
            
            setTimeout(() => {
                document.getElementById('profileForm').submit();
            }, 100);

        }, 'image/jpeg', 0.9);
    });

    const editableFields = [
        'first_name', 'last_name', 'country', 'date_of_birth', 'bio', 
        'github_url', 'linkedin_url', 'skills'
    ];
    @if($user->account_type === 'job_seeker')
        editableFields.push('is_student');
    @endif

    let skillCheckboxes = [];
    let skillsCountElement = null;
    const MAX_SKILLS = 20;

    function initializeSkillsManagement() {
        skillCheckboxes = document.querySelectorAll('.skill-checkbox');
        skillsCountElement = document.getElementById('skills-count');
        
        skillCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSkillsCount);
        });
        
        updateSkillsCount();
    }

    function updateSkillsCount() {
        const checkedCount = document.querySelectorAll('.skill-checkbox:checked').length;
        if (skillsCountElement) {
            skillsCountElement.textContent = checkedCount;
        }

        skillCheckboxes.forEach(checkbox => {
            if (!checkbox.checked && checkedCount >= MAX_SKILLS) {
                checkbox.disabled = true;
                checkbox.parentElement.classList.add('opacity-50', 'cursor-not-allowed');
            } else if (checkbox.disabled && checkedCount < MAX_SKILLS) {
                checkbox.disabled = false;
                checkbox.parentElement.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }

    function enableEditMode() {
        document.getElementById('view-mode').classList.add('hidden');
        document.getElementById('edit-mode').classList.remove('hidden');

        editableFields.forEach(fieldId => {
            const displayElement = document.getElementById(fieldId + '_display');
            const editElement = document.getElementById(fieldId + '_edit');
            
            if (displayElement && editElement) {
                displayElement.classList.add('hidden');
                editElement.classList.remove('hidden');
            }
        });

        initializeSkillsManagement();
    }

    function cancelEditMode() {
        document.getElementById('view-mode').classList.remove('hidden');
        document.getElementById('edit-mode').classList.add('hidden');

        document.getElementById('profileForm').reset();

        editableFields.forEach(fieldId => {
            const displayElement = document.getElementById(fieldId + '_display');
            const editElement = document.getElementById(fieldId + '_edit');
            
            if (displayElement && editElement) {
                displayElement.classList.remove('hidden');
                editElement.classList.add('hidden');
            }
        });
    }

</script>
@endif
@endsection
