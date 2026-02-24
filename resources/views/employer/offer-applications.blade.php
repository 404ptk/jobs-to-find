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
          <span class="text-gray-500">{{ $applications->total() }}
            {{ Str::plural('application', $applications->total()) }}</span>
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
        <x-toolbar :total="$applications->total()" :currentPerPage="10" :perPageOptions="[10, 20, 50]"
          routeName="offer.applications" :routeParams="['id' => $jobOffer->id]" :showGridButtons="false" />

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
          <table class="w-full">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-200">
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Applicant</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">CV</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Applied</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @foreach($applications as $index => $application)
                <tr class="hover:bg-gray-50 transition">
                  <td class="px-6 py-4 text-sm text-gray-400 font-medium">{{ $applications->firstItem() + $index }}</td>
                  <td class="px-6 py-4">
                    <div class="flex items-center cursor-pointer group" onclick="openUserModal({{ $application->user_id }})">
                      <div
                        class="shrink-0 h-10 w-10 rounded-full overflow-hidden border border-gray-300 group-hover:border-blue-400 transition">
                        @if($application->user && $application->user->avatar)
                          <img src="{{ asset('storage/' . $application->user->avatar) }}" alt="{{ $application->first_name }}"
                            class="h-full w-full object-cover">
                        @else
                          <img src="{{ asset('images/default-avatar.svg') }}" alt="Default Avatar"
                            class="h-full w-full object-cover">
                        @endif
                      </div>
                      <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition">
                          {{ $application->first_name }} {{ $application->last_name }}
                        </div>
                        <div class="text-xs text-gray-500">{{ $application->email }}</div>
                      </div>
                    </div>
                  </td>
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
                  <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                              @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                              @elseif($application->status === 'reviewed') bg-blue-100 text-blue-800
                              @elseif($application->status === 'accepted') bg-green-100 text-green-800
                              @elseif($application->status === 'rejected') bg-red-100 text-red-800
                              @endif">
                      {{ ucfirst($application->status) }}
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                      <button
                        class="px-3 py-1.5 bg-gray-100 text-gray-600 text-xs font-medium rounded-lg hover:bg-gray-200 transition cursor-pointer"
                        title="Message (coming soon)">
                        Message
                      </button>
                      @if($application->status !== 'accepted' && $application->status !== 'rejected')
                        <form action="{{ route('application.accept', $application->id) }}" method="POST" class="inline">
                          @csrf
                          <button type="submit"
                            class="px-3 py-1.5 bg-green-50 text-green-700 text-xs font-medium rounded-lg hover:bg-green-100 transition cursor-pointer">
                            Accept
                          </button>
                        </form>
                        <form action="{{ route('application.reject', $application->id) }}" method="POST" class="inline">
                          @csrf
                          <button type="submit"
                            class="px-3 py-1.5 bg-red-50 text-red-700 text-xs font-medium rounded-lg hover:bg-red-100 transition cursor-pointer">
                            Reject
                          </button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @if($applications->hasPages())
          <div class="mt-6">
            {{ $applications->links() }}
          </div>
        @endif
      @endif
    </div>
  </div>

  <div id="user-profile-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-opacity-30 backdrop-blur-sm transition-opacity" onclick="hideUserModal()"></div>
    <div
      class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto transform transition-all scale-95 opacity-0 relative"
      id="user-modal-content">
      <button onclick="hideUserModal()"
        class="absolute top-4 right-4 text-white hover:text-gray-200 z-20 cursor-pointer bg-black bg-opacity-30 rounded-full p-1 hover:bg-opacity-50 transition">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
      <div id="user-modal-body">
        <div class="flex justify-center items-center h-64">
          <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
          </svg>
        </div>
      </div>
    </div>
  </div>

  <script>
    const userModal = document.getElementById('user-profile-modal');
    const userModalContent = document.getElementById('user-modal-content');
    const userModalBody = document.getElementById('user-modal-body');

    function openUserModal(userId) {
      userModal.classList.remove('hidden');
      userModalBody.innerHTML = `
                  <div class="flex justify-center items-center h-64">
                      <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                  </div>
              `;

      setTimeout(() => {
        userModalContent.classList.remove('scale-95', 'opacity-0');
        userModalContent.classList.add('scale-100', 'opacity-100');
      }, 10);

      fetch(`/admin/user/${userId}/partial`)
        .then(response => response.text())
        .then(html => {
          userModalBody.innerHTML = html;
        })
        .catch(error => {
          userModalBody.innerHTML = '<p class="text-red-500 text-center p-8">Error loading profile. Please try again.</p>';
          console.error('Error:', error);
        });
    }

    function hideUserModal() {
      userModalContent.classList.remove('scale-100', 'opacity-100');
      userModalContent.classList.add('scale-95', 'opacity-0');
      setTimeout(() => {
        userModal.classList.add('hidden');
      }, 200);
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && !userModal.classList.contains('hidden')) {
        hideUserModal();
      }
    });
  </script>
@endsection