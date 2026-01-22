@props([
    'total' => 5,
    'completed' => 0,
    'size' => 'md',
    'color' => 'green',
    'showLabels' => false,
    'labels' => [],
    'animated' => true,
    'celebrateComplete' => true,
    'showCount' => false,
])

@php
    $sizes = [
        'sm' => ['bead' => 'w-3 h-3', 'icon' => 'p-0.5'],
        'md' => ['bead' => 'w-4 h-4', 'icon' => 'p-0.5'],
        'lg' => ['bead' => 'w-6 h-6', 'icon' => 'p-1'],
        'xl' => ['bead' => 'w-8 h-8', 'icon' => 'p-1.5'],
    ];

    $connectorSizes = [
        'sm' => 'h-0.5',
        'md' => 'h-1',
        'lg' => 'h-1.5',
        'xl' => 'h-2',
    ];

    $colors = [
        'green' => [
            'completed' => 'bg-duo-green border-duo-green-dark shadow-lg shadow-duo-green/30',
            'incomplete' => 'bg-duo-gray-100 border-duo-gray-200',
            'connector_done' => 'bg-gradient-to-r from-duo-green to-duo-green',
            'connector_pending' => 'bg-duo-gray-200',
            'glow' => 'shadow-duo-green/50',
            'ring' => 'ring-duo-green/50',
        ],
        'blue' => [
            'completed' => 'bg-duo-blue border-duo-blue shadow-lg shadow-duo-blue/30',
            'incomplete' => 'bg-duo-gray-100 border-duo-gray-200',
            'connector_done' => 'bg-gradient-to-r from-duo-blue to-duo-blue',
            'connector_pending' => 'bg-duo-gray-200',
            'glow' => 'shadow-duo-blue/50',
            'ring' => 'ring-duo-blue/50',
        ],
        'purple' => [
            'completed' => 'bg-duo-purple border-duo-purple shadow-lg shadow-duo-purple/30',
            'incomplete' => 'bg-duo-gray-100 border-duo-gray-200',
            'connector_done' => 'bg-gradient-to-r from-duo-purple to-duo-purple',
            'connector_pending' => 'bg-duo-gray-200',
            'glow' => 'shadow-duo-purple/50',
            'ring' => 'ring-duo-purple/50',
        ],
        'yellow' => [
            'completed' => 'bg-duo-yellow border-duo-yellow-dark shadow-lg shadow-duo-yellow/30',
            'incomplete' => 'bg-duo-gray-100 border-duo-gray-200',
            'connector_done' => 'bg-gradient-to-r from-duo-yellow to-duo-yellow',
            'connector_pending' => 'bg-duo-gray-200',
            'glow' => 'shadow-duo-yellow/50',
            'ring' => 'ring-duo-yellow/50',
        ],
        'orange' => [
            'completed' => 'bg-duo-orange border-duo-orange shadow-lg shadow-duo-orange/30',
            'incomplete' => 'bg-duo-gray-100 border-duo-gray-200',
            'connector_done' => 'bg-gradient-to-r from-duo-orange to-duo-orange',
            'connector_pending' => 'bg-duo-gray-200',
            'glow' => 'shadow-duo-orange/50',
            'ring' => 'ring-duo-orange/50',
        ],
    ];

    $sizeConfig = $sizes[$size] ?? $sizes['md'];
    $connectorSize = $connectorSizes[$size] ?? $connectorSizes['md'];
    $colorScheme = $colors[$color] ?? $colors['green'];
    $isAllComplete = $completed >= $total;
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center']) }}
     x-data="{ mounted: false }"
     x-init="setTimeout(() => mounted = true, 100)">
    @for($i = 0; $i < $total; $i++)
        @php
            $isCompleted = $i < $completed;
            $isCurrent = $i === $completed && !$isAllComplete;
            $delay = $i * 100;
        @endphp

        {{-- Bead --}}
        <div class="flex flex-col items-center">
            <div class="relative group">
                {{-- Main bead --}}
                <div class="{{ $sizeConfig['bead'] }} rounded-full border-2 transition-all duration-500 transform
                    {{ $isCompleted ? $colorScheme['completed'] : $colorScheme['incomplete'] }}
                    {{ $isCurrent && $animated ? 'ring-2 ring-offset-2 ' . $colorScheme['ring'] : '' }}
                    {{ $animated ? 'hover:scale-110' : '' }}"
                    style="{{ $animated ? 'transition-delay: ' . $delay . 'ms' : '' }}"
                    x-bind:class="{ 'scale-100 opacity-100': mounted, 'scale-0 opacity-0': !mounted }">
                    @if($isCompleted)
                        <svg class="w-full h-full text-white {{ $sizeConfig['icon'] }} animate-[checkmark_0.3s_ease-out]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </div>

                {{-- Pulse animation for current --}}
                @if($isCurrent && $animated)
                    <div class="absolute -inset-1 rounded-full bg-duo-green/20 animate-ping"></div>
                    <div class="absolute -inset-0.5 rounded-full bg-duo-green/10 animate-pulse"></div>
                @endif

                {{-- Sparkle effect for completed --}}
                @if($isCompleted && $animated && $celebrateComplete)
                    <div class="absolute -top-1 -right-1 w-2 h-2 bg-white rounded-full animate-[sparkle_1.5s_ease-in-out_infinite]" style="animation-delay: {{ $i * 200 }}ms"></div>
                @endif

                {{-- Hover tooltip --}}
                @if($showLabels && isset($labels[$i]))
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-duo-gray-500 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                        {{ $labels[$i] }}
                    </div>
                @endif
            </div>

            @if($showLabels && isset($labels[$i]))
                <span class="mt-1 text-xs font-medium transition-colors duration-300 {{ $isCompleted ? 'text-duo-gray-500' : 'text-duo-gray-300' }}">
                    {{ $labels[$i] }}
                </span>
            @endif
        </div>

        {{-- Connector (except after last bead) --}}
        @if($i < $total - 1)
            <div class="flex-1 mx-1 {{ $connectorSize }} rounded-full overflow-hidden {{ $colorScheme['connector_pending'] }}">
                <div class="h-full {{ $colorScheme['connector_done'] }} transition-all duration-700 ease-out"
                     style="width: {{ $i < $completed ? '100%' : '0%' }}; transition-delay: {{ ($i + 1) * 100 }}ms"
                     x-bind:style="mounted ? { width: '{{ $i < $completed ? '100%' : '0%' }}' } : { width: '0%' }">
                </div>
            </div>
        @endif
    @endfor

    {{-- Completion celebration --}}
    @if($isAllComplete && $celebrateComplete && $animated)
        <div class="ml-2 animate-bounce">
            <span class="text-xl">🎉</span>
        </div>
    @endif

    {{-- Count display --}}
    @if($showCount)
        <span class="ml-3 text-sm font-bold text-duo-gray-400">{{ $completed }}/{{ $total }}</span>
    @endif
</div>

<style>
@keyframes checkmark {
    0% { transform: scale(0); opacity: 0; }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); opacity: 1; }
}

@keyframes sparkle {
    0%, 100% { transform: scale(0); opacity: 0; }
    50% { transform: scale(1); opacity: 1; }
}
</style>
