@extends('layouts.app')

@section('title', 'Profile - Jobs to Find')

@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
    <style>
        .cropper-view-box,
        .cropper-face {
            border-radius: 50%;
        }
    </style>
@endsection

@section('content')
<div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-gray-900">
                    Profile
                </h1>
                <span class="px-4 py-2 rounded-lg text-sm font-medium {{ Auth::user()->account_type === 'job_seeker' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                    {{ Auth::user()->account_type === 'job_seeker' ? 'Job Seeker' : 'Employer' }}
                </span>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" id="profileForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="flex flex-col items-center mb-8">
                    <div class="relative group">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-gray-100 shadow-md">
                            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.svg') }}" 
                                 alt="Profile Avatar" 
                                 class="w-full h-full object-cover">
                        </div>
                        <label for="avatar" class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full shadow-lg cursor-pointer transition-colors duration-200" title="Change Avatar">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <input type="file" id="avatar" name="avatar" class="hidden" accept="image/*" >
                        </label>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">Max size 2MB. Square format recommended.</p>
                    @error('avatar')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-200 p-4 rounded-lg">
                            <label class="flex items-center text-sm font-medium text-gray-500 mb-1">
                                Username
                                <svg class="w-4 h-4 ml-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Publicly visible">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </label>
                            <p class="text-lg text-gray-900 font-medium">{{ Auth::user()->username }}</p>
                        </div>

                        <div class="bg-gray-200 p-4 rounded-lg">
                            <label class="flex items-center text-sm font-medium text-gray-500 mb-1">
                                Email
                                <svg class="w-4 h-4 ml-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Private (Hidden from public profile)">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </label>
                            <p class="text-lg text-gray-900">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div id="first_name_container">
                            <div class="bg-gray-200 p-4 rounded-lg" id="first_name_display">
                                <label class="flex items-center text-sm font-medium text-gray-500 mb-1">
                                    First Name
                                    <svg class="w-4 h-4 ml-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Publicly visible">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </label>
                                <p class="text-lg text-gray-900">{{ Auth::user()->first_name }}</p>
                            </div>
                            <div class="hidden p-4 bg-gray-200 rounded-lg" id="first_name_edit">
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input 
                                    type="text" 
                                    id="first_name" 
                                    name="first_name" 
                                    value="{{ old('first_name', Auth::user()->first_name) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('first_name') border-red-500 @enderror"
                                    required
                                >
                                @error('first_name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div id="last_name_container">
                            <div class="bg-gray-200 p-4 rounded-lg" id="last_name_display">
                                <label class="flex items-center text-sm font-medium text-gray-500 mb-1">
                                    Last Name
                                    <svg class="w-4 h-4 ml-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Publicly visible">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </label>
                                <p class="text-lg text-gray-900">{{ Auth::user()->last_name }}</p>
                            </div>
                            <div class="hidden p-4 bg-gray-200 rounded-lg" id="last_name_edit">
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input 
                                    type="text" 
                                    id="last_name" 
                                    name="last_name" 
                                    value="{{ old('last_name', Auth::user()->last_name) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('last_name') border-red-500 @enderror"
                                    required
                                >
                                @error('last_name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div id="country_container">
                            <div class="bg-gray-200 p-4 rounded-lg" id="country_display">
                                <label class="flex items-center text-sm font-medium text-gray-500 mb-1">
                                    Country
                                    <svg class="w-4 h-4 ml-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Publicly visible">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </label>
                                <p class="text-lg text-gray-900">{{ Auth::user()->country }}</p>
                            </div>
                            <div class="hidden p-4 bg-gray-200 rounded-lg" id="country_edit">
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                <input 
                                    type="text" 
                                    id="country" 
                                    name="country" 
                                    value="{{ old('country', Auth::user()->country) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('country') border-red-500 @enderror"
                                    required
                                >
                                @error('country')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div id="date_of_birth_container">
                            <div class="bg-gray-200 p-4 rounded-lg" id="date_of_birth_display">
                                <label class="flex items-center text-sm font-medium text-gray-500 mb-1">
                                    Date of Birth
                                    <svg class="w-4 h-4 ml-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Partially visible (Only age is shown publicly)">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                    <span class="ml-1 text-xs text-gray-400 font-normal">(Age is public)</span>
                                </label>
                                <p class="text-lg text-gray-900">
                                    {{ Auth::user()->date_of_birth ? Auth::user()->date_of_birth->format('F d, Y') : 'Not provided' }}
                                </p>
                            </div>
                            <div class="hidden p-4 bg-gray-200 rounded-lg" id="date_of_birth_edit">
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                <input 
                                    type="date" 
                                    id="date_of_birth" 
                                    name="date_of_birth" 
                                    value="{{ old('date_of_birth', Auth::user()->date_of_birth ? Auth::user()->date_of_birth->format('Y-m-d') : '') }}"
                                    max="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('date_of_birth') border-red-500 @enderror"
                                >
                                @error('date_of_birth')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->account_type === 'job_seeker')
                        <div id="is_student_container">
                            <div class="bg-gray-200 p-4 rounded-lg" id="is_student_display">
                                <label class="flex items-center text-sm font-medium text-gray-500 mb-1">
                                    Student Status
                                    <svg class="w-4 h-4 ml-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Publicly visible">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </label>
                                <p class="text-lg text-gray-900">
                                    @if(Auth::user()->is_student)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                            </svg>
                                            Student
                                        </span>
                                    @else
                                        <span class="text-gray-600">Not a student</span>
                                    @endif
                                </p>
                            </div>
                            <div class="hidden p-4 bg-white rounded-lg border-2 border-blue-300" id="is_student_edit">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Student Status</label>
                                <label class="flex items-center cursor-pointer mt-2" id="student_label">
                                    <input 
                                        type="checkbox" 
                                        name="is_student" 
                                        id="is_student"
                                        value="1"
                                        {{ old('is_student', Auth::user()->is_student) ? 'checked' : '' }}
                                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                    >
                                    <span class="ml-3 text-sm font-medium text-gray-700">I am currently a student</span>
                                </label>
                            </div>
                        </div>
                    @endif

                    @if(Auth::user()->cv_path)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="flex items-center text-sm font-medium text-gray-500 mb-2">
                                CV/Resume
                                <svg class="w-4 h-4 ml-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Publicly visible">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </label>
                            <a href="{{ Auth::user()->cv_path }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download CV
                            </a>
                        </div>
                    @endif

                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-900">Account Information</p>
                                <p class="text-sm text-blue-700 mt-1">
                                    Member since {{ Auth::user()->created_at->format('F d, Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <div id="view-mode">
                            <button type="button" onclick="enableEditMode()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Edit Profile
                            </button>
                            <a href="/" class="ml-4 text-blue-600 hover:underline">Head back home</a>
                        </div>

                        <div id="edit-mode" class="hidden">
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                Save Changes
                            </button>
                            <button type="button" onclick="cancelEditMode()" class="ml-4 px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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

    const editableFields = ['first_name', 'last_name', 'country', 'date_of_birth'];
    @if(Auth::user()->account_type === 'job_seeker')
        editableFields.push('is_student');
    @endif

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
@endsection
