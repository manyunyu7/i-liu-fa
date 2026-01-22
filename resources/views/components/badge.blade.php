@props(['color' => 'green', 'size' => 'md'])

@php
    $colorClasses = [
        'green' => 'bg-duo-green-light/20 text-duo-green border-duo-green',
        'blue' => 'bg-duo-blue/20 text-duo-blue border-duo-blue',
        'purple' => 'bg-duo-purple/20 text-duo-purple border-duo-purple',
        'orange' => 'bg-duo-orange/20 text-duo-orange border-duo-orange',
        'yellow' => 'bg-duo-yellow/20 text-duo-orange border-duo-yellow',
        'pink' => 'bg-duo-pink/20 text-duo-pink border-duo-pink',
        'red' => 'bg-duo-red/20 text-duo-red border-duo-red',
        'gray' => 'bg-duo-gray-100 text-duo-gray-300 border-duo-gray-200',
    ];

    $sizeClasses = [
        'sm' => 'text-xs px-2 py-0.5',
        'md' => 'text-sm px-3 py-1',
        'lg' => 'text-base px-4 py-2',
    ];

    $bgClass = $colorClasses[$color] ?? $colorClasses['green'];
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full border font-bold $bgClass $sizeClass"]) }}>
    {{ $slot }}
</span>
