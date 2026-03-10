@extends('layouts.app')

@section('title')
  @yield('code') - @yield('message')
@endsection

@section('content')
  <div class="flex-1 flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
      <div class="mb-8">
        <h1 class="text-9xl font-extrabold text-blue-600 opacity-20">
          @yield('code')
        </h1>
        <div class="relative -mt-20">
          <p class="text-2xl font-bold text-gray-800 mb-2">
            @yield('message')
          </p>
          @hasSection('description')
            <p class="text-gray-600 mb-8">
              @yield('description')
            </p>
          @endif
        </div>
      </div>

      <div class="space-y-4">
        <a href="/"
          class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-lg">
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
            </path>
          </svg>
          Back to main page
        </a>
      </div>
    </div>
  </div>
@endsection