@props([
    'progress' => 0,
    'total' => 100,
    'size' => 'md',
    'color' => 'green',
    'label' => '',
    'sublabel' => '',
    'icon' => null,
    'animated' => true,
    'showGlow' => true,
    'pulsing' => false,
    'celebrateComplete' => true,
])

@php
    $percentage = $total > 0 ? min(100, ($progress / $total) * 100) : 0;
    $isComplete = $percentage >= 100;

    $sizes = [
        'sm' => ['container' => 'w-16 h-16', 'stroke' => 4, 'radius' => 28, 'text' => 'text-sm', 'icon' => 'text-lg', 'beadRadius' => 2],
        'md' => ['container' => 'w-24 h-24', 'stroke' => 6, 'radius' => 42, 'text' => 'text-lg', 'icon' => 'text-2xl', 'beadRadius' => 3],
        'lg' => ['container' => 'w-32 h-32', 'stroke' => 8, 'radius' => 56, 'text' => 'text-2xl', 'icon' => 'text-3xl', 'beadRadius' => 4],
        'xl' => ['container' => 'w-40 h-40', 'stroke' => 10, 'radius' => 70, 'text' => 'text-3xl', 'icon' => 'text-4xl', 'beadRadius' => 5],
    ];

    $colors = [
        'green' => ['stroke' => '#58CC02', 'strokeLight' => '#7FDD4A', 'bg' => '#E5E5E5', 'text' => 'text-duo-green', 'glow' => 'drop-shadow-[0_0_8px_rgba(88,204,2,0.5)]'],
        'blue' => ['stroke' => '#1CB0F6', 'strokeLight' => '#4FC3F7', 'bg' => '#E5E5E5', 'text' => 'text-duo-blue', 'glow' => 'drop-shadow-[0_0_8px_rgba(28,176,246,0.5)]'],
        'purple' => ['stroke' => '#CE82FF', 'strokeLight' => '#DBA4FF', 'bg' => '#E5E5E5', 'text' => 'text-duo-purple', 'glow' => 'drop-shadow-[0_0_8px_rgba(206,130,255,0.5)]'],
        'yellow' => ['stroke' => '#FFC800', 'strokeLight' => '#FFD633', 'bg' => '#E5E5E5', 'text' => 'text-duo-yellow', 'glow' => 'drop-shadow-[0_0_8px_rgba(255,200,0,0.5)]'],
        'orange' => ['stroke' => '#FF9600', 'strokeLight' => '#FFAB33', 'bg' => '#E5E5E5', 'text' => 'text-duo-orange', 'glow' => 'drop-shadow-[0_0_8px_rgba(255,150,0,0.5)]'],
    ];

    $sizeConfig = $sizes[$size] ?? $sizes['md'];
    $colorConfig = $colors[$color] ?? $colors['green'];

    $circumference = 2 * pi() * $sizeConfig['radius'];
    $dashOffset = $circumference - ($percentage / 100) * $circumference;
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center']) }}
     x-data="{
         mounted: false,
         currentProgress: 0,
         targetProgress: {{ $percentage }}
     }"
     x-init="
         setTimeout(() => {
             mounted = true;
             if ({{ $animated ? 'true' : 'false' }}) {
                 const interval = setInterval(() => {
                     if (currentProgress < targetProgress) {
                         currentProgress = Math.min(currentProgress + 2, targetProgress);
                     } else {
                         clearInterval(interval);
                     }
                 }, 20);
             } else {
                 currentProgress = targetProgress;
             }
         }, 100)
     ">
    <div class="relative {{ $sizeConfig['container'] }} {{ $pulsing ? 'animate-pulse' : '' }}">
        {{-- Glow effect --}}
        @if($showGlow && $percentage > 0)
            <div class="absolute inset-0 {{ $colorConfig['glow'] }} opacity-50 blur-sm rounded-full"></div>
        @endif

        <svg class="w-full h-full transform -rotate-90 {{ $showGlow && $percentage > 0 ? $colorConfig['glow'] : '' }}" viewBox="0 0 100 100">
            {{-- Gradient definition --}}
            <defs>
                <linearGradient id="progressGradient-{{ $color }}" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="{{ $colorConfig['stroke'] }}" />
                    <stop offset="100%" stop-color="{{ $colorConfig['strokeLight'] }}" />
                </linearGradient>
                <filter id="glow-{{ $color }}">
                    <feGaussianBlur stdDeviation="2" result="coloredBlur"/>
                    <feMerge>
                        <feMergeNode in="coloredBlur"/>
                        <feMergeNode in="SourceGraphic"/>
                    </feMerge>
                </filter>
            </defs>

            {{-- Background circle --}}
            <circle
                cx="50"
                cy="50"
                r="{{ $sizeConfig['radius'] }}"
                fill="none"
                stroke="{{ $colorConfig['bg'] }}"
                stroke-width="{{ $sizeConfig['stroke'] }}"
                opacity="0.5"
            />

            {{-- Progress circle --}}
            <circle
                cx="50"
                cy="50"
                r="{{ $sizeConfig['radius'] }}"
                fill="none"
                stroke="url(#progressGradient-{{ $color }})"
                stroke-width="{{ $sizeConfig['stroke'] }}"
                stroke-linecap="round"
                stroke-dasharray="{{ $circumference }}"
                x-bind:stroke-dashoffset="{{ $circumference }} - (currentProgress / 100) * {{ $circumference }}"
                filter="{{ $showGlow ? 'url(#glow-' . $color . ')' : '' }}"
                class="origin-center"
            />

            {{-- Decorative beads along the circle --}}
            @for($i = 0; $i < 12; $i++)
                @php
                    $angle = ($i / 12) * 360;
                    $beadProgress = ($i / 12) * 100;
                    $x = 50 + $sizeConfig['radius'] * cos(deg2rad($angle - 90));
                    $y = 50 + $sizeConfig['radius'] * sin(deg2rad($angle - 90));
                @endphp
                <circle
                    cx="{{ $x }}"
                    cy="{{ $y }}"
                    r="{{ $sizeConfig['beadRadius'] }}"
                    x-bind:fill="currentProgress >= {{ $beadProgress }} ? '{{ $colorConfig['stroke'] }}' : '{{ $colorConfig['bg'] }}'"
                    class="transition-all duration-300"
                    style="transition-delay: {{ $i * 50 }}ms"
                >
                    @if($animated)
                        <animate
                            attributeName="r"
                            values="{{ $sizeConfig['beadRadius'] }};{{ $sizeConfig['beadRadius'] + 1 }};{{ $sizeConfig['beadRadius'] }}"
                            dur="2s"
                            begin="{{ $i * 0.1 }}s"
                            repeatCount="indefinite"
                        />
                    @endif
                </circle>
            @endfor

            {{-- Leading dot indicator --}}
            @if($percentage > 0 && $percentage < 100)
                @php
                    $leadAngle = ($percentage / 100) * 360;
                    $leadX = 50 + $sizeConfig['radius'] * cos(deg2rad($leadAngle - 90));
                    $leadY = 50 + $sizeConfig['radius'] * sin(deg2rad($leadAngle - 90));
                @endphp
                <circle
                    cx="{{ $leadX }}"
                    cy="{{ $leadY }}"
                    r="{{ $sizeConfig['beadRadius'] + 2 }}"
                    fill="white"
                    stroke="{{ $colorConfig['stroke'] }}"
                    stroke-width="2"
                    class="animate-pulse"
                />
            @endif
        </svg>

        {{-- Center content --}}
        <div class="absolute inset-0 flex flex-col items-center justify-center">
            @if($icon)
                <span class="{{ $sizeConfig['icon'] }} {{ $isComplete && $celebrateComplete ? 'animate-bounce' : '' }}">{{ $icon }}</span>
            @else
                <span class="{{ $sizeConfig['text'] }} font-extrabold {{ $colorConfig['text'] }}"
                      x-text="Math.round(currentProgress) + '%'">
                    {{ round($percentage) }}%
                </span>
            @endif
            @if($isComplete && $celebrateComplete)
                <span class="text-xs animate-pulse">Complete!</span>
            @endif
        </div>

        {{-- Celebration particles --}}
        @if($isComplete && $celebrateComplete && $animated)
            @for($i = 0; $i < 6; $i++)
                @php
                    $particleAngle = ($i / 6) * 360;
                @endphp
                <div class="absolute w-2 h-2 rounded-full bg-duo-yellow animate-[particle_1s_ease-out_infinite]"
                     style="
                         top: 50%;
                         left: 50%;
                         animation-delay: {{ $i * 150 }}ms;
                         --angle: {{ $particleAngle }}deg;
                     "></div>
            @endfor
        @endif
    </div>

    @if($label)
        <span class="mt-2 font-bold text-duo-gray-500">{{ $label }}</span>
    @endif
    @if($sublabel)
        <span class="text-sm text-duo-gray-300">{{ $sublabel }}</span>
    @endif
</div>

<style>
@keyframes particle {
    0% {
        transform: translate(-50%, -50%) rotate(var(--angle)) translateY(0) scale(1);
        opacity: 1;
    }
    100% {
        transform: translate(-50%, -50%) rotate(var(--angle)) translateY(-30px) scale(0);
        opacity: 0;
    }
}
</style>
