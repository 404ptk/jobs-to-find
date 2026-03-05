<div id="notifications-modal" class="hidden fixed inset-0 z-70 items-center justify-center p-4">
  <div class="fixed inset-0 bg-opacity-30 backdrop-blur-sm transition-opacity" onclick="hideNotificationsModal()"></div>
  <div
    class="bg-white rounded-xl shadow-2xl max-w-lg w-full max-h-[80vh] flex flex-col transform transition-all scale-95 opacity-0 relative overflow-hidden"
    id="notifications-modal-content">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white z-10">
      <h3 class="text-lg font-bold text-gray-900">Notifications</h3>
      <div class="flex items-center gap-2">
        <button onclick="markAllNotificationsAsRead()"
          class="text-xs text-blue-600 hover:text-blue-700 font-medium cursor-pointer">
          Mark all as read
        </button>
        <button onclick="hideNotificationsModal()"
          class="text-gray-400 hover:text-gray-600 transition cursor-pointer p-1 rounded-full hover:bg-gray-100">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>

    <div id="notifications-modal-body" class="flex-1 overflow-y-auto p-4 bg-gray-50/50">
      <div class="flex justify-center items-center h-48">
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
  const notifModal = document.getElementById('notifications-modal');
  const notifContent = document.getElementById('notifications-modal-content');
  const notifBody = document.getElementById('notifications-modal-body');
  const notifToggle = document.getElementById('notifications-toggle');
  const notifBadge = document.getElementById('notification-badge');

  document.addEventListener('DOMContentLoaded', function () {
    @auth
      fetchUnreadCount();
    @endauth

        if (notifToggle) {
      notifToggle.addEventListener('click', openNotificationsModal);
    }
  });

  function fetchUnreadCount() {
    fetch('/notifications')
      .then(response => response.json())
      .then(data => {
        updateBadge(data.unreadCount);
      });
  }

  function updateBadge(count) {
    if (count > 0) {
      notifBadge.classList.remove('hidden');
    } else {
      notifBadge.classList.add('hidden');
    }
  }

  function openNotificationsModal() {
    notifModal.classList.remove('hidden');
    notifModal.classList.add('flex');

    notifBody.innerHTML = `
            <div class="flex justify-center items-center h-48">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        `;

    setTimeout(() => {
      notifContent.classList.remove('scale-95', 'opacity-0');
      notifContent.classList.add('scale-100', 'opacity-100');
    }, 10);

    fetchNotifications();
  }

  function hideNotificationsModal() {
    notifContent.classList.remove('scale-100', 'opacity-100');
    notifContent.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
      notifModal.classList.remove('flex');
      notifModal.classList.add('hidden');
    }, 200);
  }

  function fetchNotifications() {
    fetch('/notifications')
      .then(response => response.json())
      .then(data => {
        if (data.notifications.length === 0) {
          notifBody.innerHTML = '<div class="text-center py-8 text-gray-500">No notifications yet.</div>';
          return;
        }

        let html = '<div class="space-y-3">';
        data.notifications.forEach(notification => {
          const isUnread = !notification.read_at;
          const date = new Date(notification.created_at).toLocaleString();

          html += `
                        <div class="p-4 rounded-xl transition-all ${isUnread ? 'bg-white border-l-4 border-blue-600 shadow-sm' : 'bg-gray-100/50 opacity-75'}" 
                             onclick="${isUnread ? `markAsRead('${notification.id}')` : ''}">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-xs font-semibold ${isUnread ? 'text-blue-600' : 'text-gray-500'}">
                                    ${notification.data.type === 'approval' ? 'Offer Approved' : 'Notification'}
                                </span>
                                <span class="text-[10px] text-gray-400">${date}</span>
                            </div>
                            <p class="text-sm ${isUnread ? 'text-gray-900 font-medium' : 'text-gray-600'}">
                                ${notification.data.message}
                            </p>
                            ${notification.data.job_offer_id ? `
                                <a href="/job/${notification.data.job_offer_id}" class="mt-2 inline-block text-xs text-blue-600 hover:underline">
                                    View Job Offer
                                </a>
                            ` : ''}
                        </div>
                    `;
        });
        html += '</div>';
        notifBody.innerHTML = html;
        updateBadge(data.unreadCount);
      });
  }

  function markAsRead(id) {
    fetch(`/notifications/${id}/mark-as-read`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
      .then(() => {
        fetchNotifications();
      });
  }

  function markAllNotificationsAsRead() {
    fetch('/notifications/mark-all-as-read', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
      .then(() => {
        fetchNotifications();
      });
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !notifModal.classList.contains('hidden')) {
      hideNotificationsModal();
    }
  });
</script>