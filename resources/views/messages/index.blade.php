@extends('layouts.app')

@section('title', 'Messages - Jobs to Find')

@section('content')
  <div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-7xl mx-auto">
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
        <p class="text-gray-600 mt-1">Your conversation history with employers and job seekers.</p>
      </div>

      @if($contacts->isEmpty())
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
      @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-50">
          @foreach($contacts as $contact)
            <div class="p-6 hover:bg-gray-50 transition cursor-pointer flex items-center justify-between group"
              onclick="openConversationModal({{ $contact['user']->id }}, '{{ $contact['user']->first_name }} {{ $contact['user']->last_name }}', '{{ $contact['user']->avatar }}')">
              <div class="flex items-center flex-1 min-w-0">
                <div
                  class="shrink-0 h-14 w-14 rounded-full overflow-hidden border-2 border-white shadow-sm ring-2 ring-gray-100 group-hover:ring-blue-100 transition">
                  @if($contact['user']->avatar)
                    <img src="{{ asset('storage/' . $contact['user']->avatar) }}" alt="{{ $contact['user']->username }}"
                      class="h-full w-full object-cover">
                  @else
                    <img src="{{ asset('images/default-avatar.svg') }}" alt="Default Avatar" class="h-full w-full object-cover">
                  @endif
                </div>
                <div class="ml-4 flex-1 min-w-0">
                  <div class="flex items-center justify-between mb-1">
                    <h3 class="text-sm font-bold text-gray-900 truncate">
                      {{ $contact['user']->first_name }} {{ $contact['user']->last_name }}
                    </h3>
                    <span class="text-xs text-gray-400">
                      {{ $contact['latest_message']->created_at->diffForHumans() }}
                    </span>
                  </div>
                  <p
                    class="text-sm text-gray-500 truncate {{ $contact['unread_count'] > 0 ? 'font-semibold text-gray-900' : '' }}">
                    {{ $contact['latest_message']->content }}
                  </p>
                </div>
              </div>

              <div class="ml-6 flex items-center">
                @if($contact['unread_count'] > 0)
                  <span
                    class="inline-flex items-center justify-center w-6 h-6 bg-blue-600 text-white text-[10px] font-bold rounded-full shadow-sm">
                    {{ $contact['unread_count'] }}
                  </span>
                @endif
                <svg
                  class="ml-4 w-5 h-5 text-gray-300 group-hover:text-blue-500 transition transform group-hover:translate-x-1"
                  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
@endsection