@props([
    'headers' => [],
    'pagination' => null,
    'alignLastRight' => true,
    'sort' => null,
    'direction' => 'asc'
])

<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @foreach($headers as $header)
                        @php
                            $isSortable = is_array($header) && isset($header['sort']);
                            $label = is_array($header) ? $header['label'] : $header;
                            $sortKey = $isSortable ? $header['sort'] : null;
                            $isActive = $sort === $sortKey;
                            $nextDirection = $isActive && $direction === 'asc' ? 'desc' : 'asc';
                            
                            $queryParams = array_merge(request()->query(), [
                                'sort' => $sortKey,
                                'direction' => $nextDirection,
                                'page' => 1 // Reset to page 1 when sorting
                            ]);
                        @endphp
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider {{ $loop->last && $alignLastRight ? 'text-right' : '' }}">
                            @if($isSortable)
                                <a href="{{ request()->fullUrlWithQuery($queryParams) }}" class="flex items-center group hover:text-gray-700 transition-colors {{ $loop->last && $alignLastRight ? 'justify-end' : '' }}">
                                    <span>{{ $label }}</span>
                                    <span class="ml-1 shrink-0 transition-opacity {{ $isActive ? 'opacity-100' : 'opacity-0 group-hover:opacity-50' }}">
                                        @if($isActive && $direction === 'desc')
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                            </svg>
                                        @else
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                            </svg>
                                        @endif
                                    </span>
                                </a>
                            @else
                                {{ $label }}
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    
    @if($pagination && $pagination->hasPages())
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $pagination->links() }}
        </div>
    @endif
</div>
