<div class="flex items-center mb-6 bg-gray-50 p-3 rounded-lg border border-gray-100">
  <div class="shrink-0 h-12 w-12 rounded-full overflow-hidden border-2 border-white shadow-sm">
    @if($user->avatar)
      <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->username }}" class="h-full w-full object-cover">
    @else
      <img src="{{ asset('images/default-avatar.svg') }}" alt="Default Avatar" class="h-full w-full object-cover">
    @endif
  </div>
  <div class="ml-4">
    <div class="text-sm font-semibold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</div>
    <div class="text-xs text-gray-500">{{ '@' . $user->username }}</div>
  </div>
</div>

<form action="#" method="POST"
  onsubmit="event.preventDefault(); alert('Message sending will be implemented soon!'); hideMessageModal();">
  @csrf
  <div class="mb-4">
    <label for="message-content" class="block text-sm font-medium text-gray-700 mb-1">Your Message</label>
    <textarea id="message-content" name="message" rows="4"
      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
      placeholder="Write your message here..." required></textarea>
  </div>
  <div class="flex justify-end gap-3 mt-6">
    <button type="button" onclick="hideMessageModal()"
      class="px-4 py-2 text-gray-700 font-medium hover:text-gray-900 transition cursor-pointer">
      Cancel
    </button>
    <button type="submit"
      class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-md cursor-pointer flex items-center">
      <span>Send Message</span>
      <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
      </svg>
    </button>
  </div>
</form>