<div class="flex flex-col space-y-4 mb-4">
  @if(isset($jobOffer) && $jobOffer)
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 mb-2 flex items-center shadow-sm">
      <div class="bg-blue-100 p-2 rounded-lg mr-3">
        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 0-2 2v10a2 2 0 002 2z" />
        </svg>
      </div>
      <div>
        <p class="text-[10px] uppercase tracking-wider font-bold text-blue-500 mb-0.5">Regarding Job Offer</p>
        <p class="text-sm font-semibold text-gray-900">{{ $jobOffer->title }}</p>
      </div>
    </div>
  @endif

  @forelse($messages as $message)
    @if($message->sender_id === auth()->id())
      <div class="flex justify-end">
        <div class="max-w-[80%] bg-blue-600 text-white rounded-2xl rounded-tr-none px-4 py-2 shadow-sm">
          <p class="text-sm">{{ $message->content }}</p>
          <div class="flex items-center justify-end mt-1 space-x-1">
            <span class="text-[10px] opacity-75">{{ $message->created_at->format('H:i') }}</span>
            @if($message->read_at)
              <svg class="w-3 h-3 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
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