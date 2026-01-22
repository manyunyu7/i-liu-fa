@props(['icon', 'label', 'value', 'color' => 'green'])

@php
    $colorClasses = [
        'green' => 'text-duo-green',
        'blue' => 'text-duo-blue',
        'purple' => 'text-duo-purple',
        'orange' => 'text-duo-orange',
        'yellow' => 'text-duo-yellow',
        'pink' => 'text-duo-pink',
    ];

    $textColorClass = $colorClasses[$color] ?? $colorClasses['green'];
@endphp

<x-card {{ $attributes }}>
    <div class="flex items-center space-x-4">
        <span class="text-4xl">{{ $icon }}</span>
        <div>
            <p class="text-sm font-bold text-duo-gray-200 uppercase tracking-wide">{{ $label }}</p>
            <p class="text-2xl font-extrabold {{ $textColorClass }}">{{ $value }}</p>
        </div>
    </div>
</x-card>
