<div class="flex flex-col space-y-4 mb-4">
  @forelse($messages as $message)
    @if($message->sender_id === auth()->id())
      <div class="flex justify-end">
        <div class="max-w-[80%] bg-blue-600 text-white rounded-2xl rounded-tr-none px-4 py-2 shadow-sm">
          <p class="text-sm">{{ $message->content }}</p>
          <div class="flex items-center justify-end mt-1 space-x-1">
            <span class="text-[10px] opacity-75">{{ $message->created_at->format('H:i') }}</span>
            @if($message->read_at)
              <svg class="w-3 h-3 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                <path
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
              </svg>
            @endif
          </div>
        </div>
      </div>
    @else
      <div class="flex justify-start">
        <div class="flex items-start max-w-[80%]">
          <div class="shrink-0 h-8 w-8 rounded-full overflow-hidden mr-2 border border-gray-200 bg-gray-50 mt-1">
            @if($otherUser->avatar)
              <img src="{{ asset('storage/' . $otherUser->avatar) }}" alt="{{ $otherUser->username }}"
                class="h-full w-full object-cover">
            @else
              <img src="{{ asset('images/default-avatar.svg') }}" alt="Default Avatar" class="h-full w-full object-cover">
            @endif
          </div>
          <div class="bg-gray-100 text-gray-800 rounded-2xl rounded-tl-none px-4 py-2 shadow-sm">
            <p class="text-sm">{{ $message->content }}</p>
            <span class="text-[10px] text-gray-500 mt-1 block">{{ $message->created_at->format('H:i') }}</span>
          </div>
        </div>
      </div>
    @endif
  @empty
    <div class="flex flex-col items-center justify-center py-12 text-center">
      <div class="bg-blue-50 p-4 rounded-full mb-4">
        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
      </div>
      <p class="text-gray-500 text-sm italic">No messages yet. Send a message to start the conversation!</p>
    </div>
  @endforelse
</div>