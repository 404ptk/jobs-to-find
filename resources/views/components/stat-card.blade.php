@props([
    'title',
    'value',
    'color' => 'blue',
])

@php
    $colors = [
        'blue' => [
            'border' => 'border-blue-500',
            'bg' => 'bg-blue-100',
            'text' => 'text-blue-600',
        ],
        'green' => [
            'border' => 'border-green-500',
            'bg' => 'bg-green-100',
            'text' => 'text-green-600',
        ],
        'yellow' => [
            'border' => 'border-yellow-500',
            'bg' => 'bg-yellow-100',
            'text' => 'text-yellow-600',
        ],
        'purple' => [
            'border' => 'border-purple-500',
            'bg' => 'bg-purple-100',
            'text' => 'text-purple-600',
        ],
        'orange' => [
            'border' => 'border-orange-500',
            'bg' => 'bg-orange-100',
            'text' => 'text-orange-600',
        ],
        'pink' => [
            'border' => 'border-pink-500',
            'bg' => 'bg-pink-100',
            'text' => 'text-pink-600',
        ],
        'red' => [
            'border' => 'border-red-500',
            'bg' => 'bg-red-100',
            'text' => 'text-red-600',
        ],
    ];

    $currentColor = $colors[$color] ?? $colors['blue'];
@endphp

<div class="bg-white rounded-lg shadow-sm p-4 border-l-4 {{ $currentColor['border'] }}">
    <div class="flex items-center">
        <div class="p-3 rounded-full {{ $currentColor['bg'] }} {{ $currentColor['text'] }} mr-3">
            {{ $slot }}
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900 leading-tight">{{ $value }}</p>
        </div>
    </div>
</div>
