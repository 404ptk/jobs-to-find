@props(['jobOffer'])

<div id="apply-modal-{{ $jobOffer->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-opacity-30 backdrop-blur-sm transition-opacity"
        onclick="hideApplyModal({{ $jobOffer->id }})"></div>
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full transform transition-all scale-95 opacity-0 relative"
        id="apply-modal-content-{{ $jobOffer->id }}">
        <button onclick="hideApplyModal({{ $jobOffer->id }})"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-20 cursor-pointer">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Apply for Position</h2>
            <p class="text-gray-600 mb-6">{{ $jobOffer->title }} at {{ $jobOffer->company_name }}</p>

            <form id="apply-form-{{ $jobOffer->id }}" action="{{ route('job-offers.apply', $jobOffer->id) }}"
                method="POST" enctype="multipart/form-data" class="space-y-6"
                onsubmit="handleApplySubmit(event, {{ $jobOffer->id }})">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name_{{ $jobOffer->id }}"
                            class="block text-sm font-medium text-gray-700 mb-2">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="first_name_{{ $jobOffer->id }}" name="first_name"
                            value="{{ old('first_name', Auth::user()->first_name ?? '') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="last_name_{{ $jobOffer->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="last_name_{{ $jobOffer->id }}" name="last_name"
                            value="{{ old('last_name', Auth::user()->last_name ?? '') }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label for="email_{{ $jobOffer->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email_{{ $jobOffer->id }}" name="email"
                        value="{{ old('email', Auth::user()->email ?? '') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="cv_{{ $jobOffer->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                        Upload CV/Resume <span class="text-red-500">*</span>
                    </label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="cv_{{ $jobOffer->id }}"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Upload a file</span>
                                    <input id="cv_{{ $jobOffer->id }}" name="cv" type="file" class="sr-only"
                                        accept=".pdf,.doc,.docx" required>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX up to 10MB</p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Your application will be sent to the employer. Make sure all information is correct
                                before submitting.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="hideApplyModal({{ $jobOffer->id }})"
                        class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition"
                        id="cancel-btn-{{ $jobOffer->id }}">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition flex items-center justify-center"
                        id="submit-btn-{{ $jobOffer->id }}">
                        <span id="submit-text-{{ $jobOffer->id }}">Submit Application</span>
                        <svg id="submit-spinner-{{ $jobOffer->id }}" class="hidden animate-spin h-5 w-5 text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                </div>

                <div id="success-message-{{ $jobOffer->id }}"
                    class="hidden bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-green-700 font-medium">Your application has been submitted successfully!</p>
                    </div>
                </div>

                <div id="error-message-{{ $jobOffer->id }}"
                    class="hidden bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-red-700 font-medium" id="error-text-{{ $jobOffer->id }}">An error occurred.
                            Please try again.</p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function handleApplySubmit(event, offerId) {
        event.preventDefault();

        const form = document.getElementById(`apply-form-${offerId}`);
        const submitBtn = document.getElementById(`submit-btn-${offerId}`);
        const submitText = document.getElementById(`submit-text-${offerId}`);
        const submitSpinner = document.getElementById(`submit-spinner-${offerId}`);
        const cancelBtn = document.getElementById(`cancel-btn-${offerId}`);
        const successMessage = document.getElementById(`success-message-${offerId}`);
        const errorMessage = document.getElementById(`error-message-${offerId}`);
        const errorText = document.getElementById(`error-text-${offerId}`);

        successMessage.classList.add('hidden');
        errorMessage.classList.add('hidden');

        submitBtn.disabled = true;
        cancelBtn.disabled = true;
        submitText.classList.add('hidden');
        submitSpinner.classList.remove('hidden');

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'An error occurred');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    successMessage.classList.remove('hidden');
                    form.querySelectorAll('input, button[type="submit"]').forEach(el => el.disabled = true);

                    setTimeout(() => {
                        hideApplyModal(offerId);
                        window.location.reload();
                    }, 2000);
                } else {
                    throw new Error(data.message || 'An error occurred');
                }
            })
            .catch(error => {
                console.error('Application error:', error);
                errorText.textContent = error.message || 'An error occurred. Please try again.';
                errorMessage.classList.remove('hidden');

                submitBtn.disabled = false;
                cancelBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitSpinner.classList.add('hidden');
            });
    }

    function showApplyModal(offerId) {
        const modal = document.getElementById(`apply-modal-${offerId}`);
        const modalContent = document.getElementById(`apply-modal-content-${offerId}`);

        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideApplyModal(offerId) {
        const modal = document.getElementById(`apply-modal-${offerId}`);
        const modalContent = document.getElementById(`apply-modal-content-${offerId}`);

        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('[id^="apply-modal-"]:not(.hidden)');
            openModals.forEach(modal => {
                const offerId = modal.id.replace('apply-modal-', '');
                hideApplyModal(offerId);
            });
        }
    });
</script>