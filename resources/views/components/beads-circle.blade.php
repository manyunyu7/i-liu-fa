@props([
    'progress' => 0,
    'total' => 100,
    'size' => 'md',
    'color' => 'green',
    'label' => '',
    'sublabel' => '',
    'icon' => null,
    'animated' => true,
])

@php
    $percentage = $total > 0 ? min(100, ($progress / $total) * 100) : 0;

    $sizes = [
        'sm' => ['container' => 'w-16 h-16', 'stroke' => 4, 'radius' => 28, 'text' => 'text-sm', 'icon' => 'text-lg'],
        'md' => ['container' => 'w-24 h-24', 'stroke' => 6, 'radius' => 42, 'text' => 'text-lg', 'icon' => 'text-2xl'],
        'lg' => ['container' => 'w-32 h-32', 'stroke' => 8, 'radius' => 56, 'text' => 'text-2xl', 'icon' => 'text-3xl'],
        'xl' => ['container' => 'w-40 h-40', 'stroke' => 10, 'radius' => 70, 'text' => 'text-3xl', 'icon' => 'text-4xl'],
    ];

    $colors = [
        'green' => ['stroke' => '#58CC02', 'bg' => '#E5E5E5', 'text' => 'text-duo-green'],
        'blue' => ['stroke' => '#1CB0F6', 'bg' => '#E5E5E5', 'text' => 'text-duo-blue'],
        'purple' => ['stroke' => '#CE82FF', 'bg' => '#E5E5E5', 'text' => 'text-duo-purple'],
        'yellow' => ['stroke' => '#FFC800', 'bg' => '#E5E5E5', 'text' => 'text-duo-yellow'],
        'orange' => ['stroke' => '#FF9600', 'bg' => '#E5E5E5', 'text' => 'text-duo-orange'],
    ];

    $sizeConfig = $sizes[$size] ?? $sizes['md'];
    $colorConfig = $colors[$color] ?? $colors['green'];

    $circumference = 2 * pi() * $sizeConfig['radius'];
    $dashOffset = $circumference - ($percentage / 100) * $circumference;
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center']) }}>
    <div class="relative {{ $sizeConfig['container'] }}">
        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
            {{-- Background circle --}}
            <circle
                cx="50"
                cy="50"
                r="{{ $sizeConfig['radius'] }}"
                fill="none"
                stroke="{{ $colorConfig['bg'] }}"
                stroke-width="{{ $sizeConfig['stroke'] }}"
            />

            {{-- Progress circle --}}
            <circle
                cx="50"
                cy="50"
                r="{{ $sizeConfig['radius'] }}"
                fill="none"
                stroke="{{ $colorConfig['stroke'] }}"
                stroke-width="{{ $sizeConfig['stroke'] }}"
                stroke-linecap="round"
                stroke-dasharray="{{ $circumference }}"
                stroke-dashoffset="{{ $dashOffset }}"
                class="{{ $animated ? 'transition-all duration-1000 ease-out' : '' }}"
            />

            {{-- Decorative beads along the circle --}}
            @for($i = 0; $i < 12; $i++)
                @php
                    $angle = ($i / 12) * 360;
                    $beadProgress = ($i / 12) * 100;
                    $isActive = $beadProgress <= $percentage;
                    $x = 50 + $sizeConfig['radius'] * cos(deg2rad($angle - 90));
                    $y = 50 + $sizeConfig['radius'] * sin(deg2rad($angle - 90));
                @endphp
                <circle
                    cx="{{ $x }}"
                    cy="{{ $y }}"
                    r="{{ $sizeConfig['stroke'] / 2 + 1 }}"
                    fill="{{ $isActive ? $colorConfig['stroke'] : $colorConfig['bg'] }}"
                    class="{{ $animated ? 'transition-all duration-300' : '' }}"
                    style="{{ $animated ? 'transition-delay: ' . ($i * 50) . 'ms' : '' }}"
                />
            @endfor
        </svg>

        {{-- Center content --}}
        <div class="absolute inset-0 flex flex-col items-center justify-center">
            @if($icon)
                <span class="{{ $sizeConfig['icon'] }}">{{ $icon }}</span>
            @else
                <span class="{{ $sizeConfig['text'] }} font-extrabold {{ $colorConfig['text'] }}">
                    {{ round($percentage) }}%
                </span>
            @endif
        </div>
    </div>

    @if($label)
        <span class="mt-2 font-bold text-duo-gray-500">{{ $label }}</span>
    @endif
    @if($sublabel)
        <span class="text-sm text-duo-gray-300">{{ $sublabel }}</span>
    @endif
</div>
