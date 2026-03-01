<div id="conversation-modal" class="hidden fixed inset-0 z-70 items-center justify-center p-4">
  <div class="fixed inset-0 bg-opacity-30 backdrop-blur-sm transition-opacity" onclick="hideConversationModal()"></div>
  <div
    class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] flex flex-col transform transition-all scale-95 opacity-0 relative overflow-hidden"
    id="conversation-modal-content">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white z-10">
      <div class="flex items-center" id="conversation-header-user">
      </div>
      <button onclick="hideConversationModal()"
        class="text-gray-400 hover:text-gray-600 transition cursor-pointer p-1 rounded-full hover:bg-gray-100">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <div id="conversation-modal-body" class="flex-1 overflow-y-auto p-6 bg-gray-50/50">
      <div class="flex justify-center items-center h-full">
        <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
          viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
          </path>
        </svg>
      </div>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-white">
      <form id="conversation-reply-form" class="flex items-end gap-2">
        @csrf
        <input type="hidden" name="receiver_id" id="conversation-receiver-id">
        <div class="flex-1 relative">
          <textarea name="content" id="conversation-reply-content" rows="1"
            class="w-full px-4 py-2 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm resize-none pr-10 min-h-[42px] max-h-32"
            placeholder="Type a message..." required></textarea>
        </div>
        <button type="submit"
          class="shrink-0 bg-blue-600 text-white p-2.5 rounded-full hover:bg-blue-700 transition shadow-md cursor-pointer flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed group">
          <svg class="w-5 h-5 transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
          </svg>
        </button>
      </form>
    </div>
  </div>
</div>

<script>
  const convModal = document.getElementById('conversation-modal');
  const convContent = document.getElementById('conversation-modal-content');
  const convBody = document.getElementById('conversation-modal-body');
  const convHeaderUser = document.getElementById('conversation-header-user');
  const replyForm = document.getElementById('conversation-reply-form');
  const replyContent = document.getElementById('conversation-reply-content');
  const receiverIdInput = document.getElementById('conversation-receiver-id');

  function openConversationModal(userId, userName = 'Chat', userAvatar = null) {
    if (typeof hideMessageModal === 'function') hideMessageModal();

    convModal.classList.remove('hidden');
    convModal.classList.add('flex');
    receiverIdInput.value = userId;

    const avatarSrc = userAvatar ? (userAvatar.startsWith('http') ? userAvatar : `/storage/${userAvatar}`) : '/images/default-avatar.svg';
    convHeaderUser.innerHTML = `
            <div class="shrink-0 h-10 w-10 rounded-full overflow-hidden border border-gray-200">
                <img src="${avatarSrc}" alt="${userName}" class="h-full w-full object-cover">
            </div>
            <div class="ml-3">
                <div class="text-sm font-bold text-gray-900">${userName}</div>
                <div class="flex items-center">
                    <span class="flex w-2 h-2 bg-green-500 rounded-full mr-1.5 focus:ring-0"></span>
                    <span class="text-[10px] text-gray-500 font-medium">Online</span>
                </div>
            </div>
        `;

    convBody.innerHTML = `
            <div class="flex justify-center items-center h-full">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        `;

    setTimeout(() => {
      convContent.classList.remove('scale-95', 'opacity-0');
      convContent.classList.add('scale-100', 'opacity-100');
    }, 10);

    fetchConversation(userId);
  }

  function fetchConversation(userId) {
    fetch(`/messages/conversation/${userId}`)
      .then(response => response.text())
      .then(html => {
        convBody.innerHTML = html;
        scrollToBottom();

      })
      .catch(error => {
        console.error('Error:', error);
        convBody.innerHTML = '<p class="text-red-500 text-center p-4">Error loading conversation.</p>';
      });
  }

  function hideConversationModal() {
    convContent.classList.remove('scale-100', 'opacity-100');
    convContent.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
      convModal.classList.remove('flex');
      convModal.classList.add('hidden');
    }, 200);
  }

  function scrollToBottom() {
    convBody.scrollTop = convBody.scrollHeight;
  }

  replyContent.addEventListener('input', function () {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
  });

  replyForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const content = replyContent.value.trim();
    const receiverId = receiverIdInput.value;

    if (!content) return;

    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;

    fetch('/messages', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        receiver_id: receiverId,
        content: content
      })
    })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          replyContent.value = '';
          replyContent.style.height = 'auto';
          fetchConversation(receiverId);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Failed to send message.');
      })
      .finally(() => {
        submitBtn.disabled = false;
      });
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !convModal.classList.contains('hidden')) {
      hideConversationModal();
    }
  });
</script>