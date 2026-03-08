@extends('layouts.app')

@section('title', 'My Companies - Jobs to Find')

@section('content')
  <div class="min-h-[calc(100vh-8rem)] py-12 px-4">
    <div class="max-w-7xl mx-auto">
      @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
          <div class="flex items-center">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
          </div>
        </div>
      @endif

      @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
          <div class="flex items-center">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <p class="text-red-700 font-medium">{{ session('error') }}</p>
          </div>
        </div>
      @endif

      <div class="flex items-center justify-between mb-8">
        <div>
          <h1 class="text-3xl font-bold text-gray-900">My Companies</h1>
          <p class="text-gray-600 mt-1">Manage up to 3 companies for your job offers</p>
        </div>
        @if($companies->count() < 3)
          <a href="{{ route('companies.create') }}"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Register New Company
          </a>
        @else
          <div class="px-6 py-3 bg-gray-100 text-gray-500 rounded-lg font-medium flex items-center cursor-not-allowed"
            title="Max 3 companies reached">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Limit Reached (3/3)
          </div>
        @endif
      </div>

      @if($companies->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-12 text-center border border-gray-200">
          <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">No companies registered</h3>
          <p class="text-gray-600 mb-6">You need to register at least one company to start posting job offers.</p>
          <a href="{{ route('companies.create') }}"
            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
            Register Your First Company
          </a>
        </div>
      @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          @foreach($companies as $company)
            <div
              class="bg-white rounded-lg shadow-sm hover:shadow-md transition border border-gray-200 overflow-hidden flex flex-col">
              <div class="p-6 flex-1">
                <div class="flex items-center mb-4">
                  @if($company->logo_path)
                    <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->name }}"
                      class="w-16 h-16 object-cover rounded-lg mr-4 border border-gray-100">
                  @else
                    <div class="w-16 h-16 bg-blue-50 rounded-lg flex items-center justify-center mr-4">
                      <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                      </svg>
                    </div>
                  @endif
                  <div>
                    <h3 class="text-lg font-bold text-gray-900 line-clamp-1">{{ $company->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $company->location }}</p>
                  </div>
                </div>

                <p class="text-gray-600 text-sm line-clamp-3 mb-4">{{ $company->description }}</p>

                <div class="space-y-2 mb-4 pt-4 border-t border-gray-50">
                  <div class="flex justify-between text-xs">
                    <span class="text-gray-500 uppercase tracking-wider font-semibold">NIP</span>
                    <span class="text-gray-900">{{ $company->nip }}</span>
                  </div>
                  <div class="flex justify-between text-xs">
                    <span class="text-gray-500 uppercase tracking-wider font-semibold">Founded</span>
                    <span class="text-gray-900">{{ $company->founded_at ?: 'N/A' }}</span>
                  </div>
                  <div class="flex justify-between text-xs">
                    <span class="text-gray-500 uppercase tracking-wider font-semibold">Active Offers</span>
                    <span class="text-gray-900">{{ $company->jobOffers()->where('is_active', true)->count() }}</span>
                  </div>
                </div>
              </div>

              <div class="p-4 bg-gray-50 border-t border-gray-100 flex gap-2">
                <a href="{{ route('companies.edit', $company->id) }}"
                  class="flex-1 flex items-center justify-center px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  Edit
                </a>
                <button data-action="delete" data-company-id="{{ $company->id }}" data-name="{{ $company->name }}"
                  class="px-4 py-2 bg-white border border-red-200 text-red-600 text-sm font-medium rounded-lg hover:bg-red-50 transition cursor-pointer">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

  <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
    <div
      class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 transform transition-all scale-95 opacity-0 border border-gray-300 pointer-events-auto"
      id="modal-content">
      <div class="flex items-center mb-4">
        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
          <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
        </div>
        <h3 class="ml-4 text-xl font-bold text-gray-900">Delete Company</h3>
      </div>
      <p class="text-gray-600 mb-2">Are you sure you want to delete <span id="company-name-span"
          class="font-bold text-gray-900"></span>?</p>
      <p class="text-red-500 text-sm mb-6">All job offers associated with this company will be updated. This action cannot
        be undone.</p>
      <div class="flex gap-3">
        <button id="modal-cancel"
          class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition cursor-pointer">
          Cancel
        </button>
        <button id="modal-confirm"
          class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition cursor-pointer">
          Delete Company
        </button>
      </div>
    </div>
  </div>

  <script>
    let currentCompanyId = null;
    const modal = document.getElementById('delete-modal');
    const modalContent = document.getElementById('modal-content');
    const modalCancel = document.getElementById('modal-cancel');
    const modalConfirm = document.getElementById('modal-confirm');
    const companyNameSpan = document.getElementById('company-name-span');

    function showModal() {
      modal.classList.remove('hidden');
      setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
      }, 10);
    }

    function hideModal() {
      modalContent.classList.remove('scale-100', 'opacity-100');
      modalContent.classList.add('scale-95', 'opacity-0');
      setTimeout(() => {
        modal.classList.add('hidden');
      }, 200);
    }

    document.querySelectorAll('[data-action="delete"]').forEach(button => {
      button.addEventListener('click', function (e) {
        e.preventDefault();
        currentCompanyId = this.getAttribute('data-company-id');
        const name = this.getAttribute('data-name');
        companyNameSpan.textContent = name;
        showModal();
      });
    });

    modalCancel.addEventListener('click', hideModal);

    modalConfirm.addEventListener('click', function () {
      if (currentCompanyId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/companies/${currentCompanyId}`;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
      }
    });

    modal.addEventListener('click', function (e) {
      if (e.target === modal) hideModal();
    });
  </script>
@endsection