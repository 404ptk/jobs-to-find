<div id="message-modal" class="hidden fixed inset-0 z-60 items-center justify-center p-4">
    <div class="fixed inset-0 bg-opacity-30 backdrop-blur-sm transition-opacity" onclick="hideMessageModal()"></div>
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full transform transition-all scale-95 opacity-0 relative overflow-hidden"
        id="message-modal-content">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Send Message</h3>
            <button onclick="hideMessageModal()" class="text-gray-400 hover:text-gray-600 transition cursor-pointer">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="message-modal-body" class="p-6">
            <div class="flex justify-center items-center h-32">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
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
    const messageModal = document.getElementById('message-modal');
    const messageModalContent = document.getElementById('message-modal-content');
    const messageModalBody = document.getElementById('message-modal-body');

    function openMessageModal(userId) {
        messageModal.classList.remove('hidden');
        messageModal.classList.add('flex');
        messageModalBody.innerHTML = `
            <div class="flex justify-center items-center h-32">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        `;

        setTimeout(() => {
            messageModalContent.classList.remove('scale-95', 'opacity-0');
            messageModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);

        fetch(`/messages/modal/${userId}`)
            .then(response => response.text())
            .then(html => {
                messageModalBody.innerHTML = html;
            })
            .catch(error => {
                messageModalBody.innerHTML = '<p class="text-red-500 text-center">Error loading message form. Please try again.</p>';
                console.error('Error:', error);
            });
    }

    function hideMessageModal() {
        messageModalContent.classList.remove('scale-100', 'opacity-100');
        messageModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            messageModal.classList.remove('flex');
            messageModal.classList.add('hidden');
        }, 200);
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !messageModal.classList.contains('hidden')) {
            hideMessageModal();
        }
    });
</script>