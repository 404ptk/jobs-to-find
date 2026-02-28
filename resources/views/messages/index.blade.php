@extends('layouts.app')

@section('title', 'Messages - Jobs to Find')

@section('content')
  <div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-7xl mx-auto">
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
        <p class="text-gray-600 mt-1">Your conversation history with employers and job seekers.</p>
      </div>

      <div class="bg-white rounded-lg shadow-sm p-12 text-center border border-gray-100">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-50 rounded-full mb-6">
          <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">No messages yet</h2>
        <p class="text-gray-500 max-w-sm mx-auto mb-8">
          Your message history will appear here once you start communicating with others about job offers.
        </p>
        <a href="/"
          class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition shadow-md">
          Browse Job Offers
        </a>
      </div>
    </div>
  </div>
@endsection