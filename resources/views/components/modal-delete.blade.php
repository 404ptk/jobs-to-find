@props([
    'name',
    'title' => 'Confirm Deletion',
    'message' => 'Are you sure you want to delete this item? This action cannot be undone.',
    'confirmText' => 'Delete',
    'cancelText' => 'Cancel'
])

<div id="{{ $name }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none">
    <div class="fixed inset-0 bg-transparent backdrop-blur-sm transition-opacity" onclick="closeModal('{{ $name }}')"></div>
    
    <div id="{{ $name }}-content" class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 transform transition-all scale-95 opacity-0 border border-gray-300 pointer-events-auto relative">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="ml-4 text-xl font-bold text-gray-900">{{ $title }}</h3>
        </div>
        
        <p class="text-gray-600 mb-6">{{ $message }}</p>
        
        <div class="flex gap-3">
            <button onclick="closeModal('{{ $name }}')" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition cursor-pointer">
                {{ $cancelText }}
            </button>
            <button id="{{ $name }}-confirm-btn" class="flex-1 px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition cursor-pointer">
                {{ $confirmText }}
            </button>
        </div>
    </div>
</div>
