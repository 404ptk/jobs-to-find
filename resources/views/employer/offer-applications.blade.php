@extends('layouts.app')

@section('title', 'Applications for ' . $jobOffer->title . ' - Jobs to Find')

@section('content')
  <div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-5xl mx-auto">
      <div class="mb-8">
        <a href="{{ route('my-offers') }}"
          class="inline-flex items-center text-blue-600 hover:text-blue-800 transition mb-4">
          <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Back to My Offers
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Applications</h1>
        <p class="text-gray-600 mt-1">
          For: <span class="font-semibold text-gray-800">{{ $jobOffer->title }}</span>
          <span class="text-gray-400 mx-2">·</span>
          <span class="text-gray-500">{{ $applications->count() }}
            {{ Str::plural('application', $applications->count()) }}</span>
        </p>
      </div>

      @if($applications->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-12 text-center border border-gray-200">
          <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">No applications yet</h3>
          <p class="text-gray-600">No one has applied to this job offer yet. Check back later!</p>
        </div>
      @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
          <table class="w-full">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-200">
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">First Name</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Name</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">CV</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Applied</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @foreach($applications as $index => $application)
                <tr class="hover:bg-gray-50 transition">
                  <td class="px-6 py-4 text-sm text-gray-400 font-medium">{{ $index + 1 }}</td>
                  <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $application->first_name }}</td>
                  <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $application->last_name }}</td>
                  <td class="px-6 py-4 text-sm">
                    @if($application->cv_path)
                      <a href="{{ route('application.download-cv', $application->id) }}"
                        class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition text-xs font-medium">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download CV
                      </a>
                    @else
                      <span class="text-gray-400 text-xs italic">No CV attached</span>
                    @endif
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-500">{{ $application->created_at->diffForHumans() }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
@endsection