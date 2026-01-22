@props([
    'type' => 'button',
    'color' => 'green',
    'size' => 'md',
    'icon' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-bold uppercase tracking-wide transition-all duration-100 focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-duo border-b-4 hover:brightness-95 active:border-b-0 active:mt-1 active:mb-[-4px]';

    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-xs',
        'lg' => 'px-8 py-4 text-base',
        default => 'px-6 py-3 text-sm',
    };

    $colorClasses = match($color) {
        'green' => 'bg-duo-green border-duo-green-dark text-white focus:ring-duo-green',
        'blue' => 'bg-duo-blue border-duo-blue/80 text-white focus:ring-duo-blue',
        'yellow' => 'bg-duo-yellow border-duo-yellow-dark text-duo-gray-500 focus:ring-duo-yellow',
        'orange' => 'bg-duo-orange border-duo-orange/80 text-white focus:ring-duo-orange',
        'purple' => 'bg-duo-purple border-duo-purple/80 text-white focus:ring-duo-purple',
        'red' => 'bg-red-500 border-red-600 text-white focus:ring-red-500',
        'gray' => 'bg-duo-gray-100 border-duo-gray-200 text-duo-gray-500 focus:ring-duo-gray-200',
        default => 'bg-duo-green border-duo-green-dark text-white focus:ring-duo-green',
    };
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$baseClasses $sizeClasses $colorClasses"]) }}>
    @if($icon)
        <span class="mr-2">
            @if($icon === 'plus')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            @elseif($icon === 'check')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            @elseif($icon === 'arrow-right')
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            @endif
        </span>
    @endif
    {{ $slot }}
</button>
