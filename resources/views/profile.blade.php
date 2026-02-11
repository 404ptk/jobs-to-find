@extends('layouts.app')

@section('title', 'Profile - Jobs to Find')

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

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-200 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Username</label>
                        <p class="text-lg text-gray-900 font-medium">{{ Auth::user()->username }}</p>
                    </div>

                    <div class="bg-gray-200 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                        <p class="text-lg text-gray-900">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-200 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-500 mb-1">First Name</label>
                        <p class="text-lg text-gray-900">{{ Auth::user()->first_name }}</p>
                    </div>

                    <div class="bg-gray-200 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Last Name</label>
                        <p class="text-lg text-gray-900">{{ Auth::user()->last_name }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-200 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Country</label>
                        <p class="text-lg text-gray-900">{{ Auth::user()->country }}</p>
                    </div>

                    <div class="bg-gray-200 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Date of Birth</label>
                        <p class="text-lg text-gray-900">
                            {{ Auth::user()->date_of_birth ? Auth::user()->date_of_birth->format('F d, Y') : 'Not provided' }}
                        </p>
                    </div>
                </div>

                <div class="bg-gray-200 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Student Status</label>
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

                @if(Auth::user()->cv_path)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <label class="block text-sm font-medium text-gray-500 mb-2">CV/Resume</label>
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
                    <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Edit Profile
                    </button>
                    <a href="/" class="ml-4 text-blue-600 hover:underline">Head back home</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
