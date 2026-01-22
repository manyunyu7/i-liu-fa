@props([
    'total' => 5,
    'completed' => 0,
    'size' => 'md',
    'color' => 'green',
    'showLabels' => false,
    'labels' => [],
    'animated' => true,
])

@php
    $sizes = [
        'sm' => 'w-3 h-3',
        'md' => 'w-4 h-4',
        'lg' => 'w-6 h-6',
        'xl' => 'w-8 h-8',
    ];

    $connectorSizes = [
        'sm' => 'h-0.5',
        'md' => 'h-1',
        'lg' => 'h-1.5',
        'xl' => 'h-2',
    ];

    $colors = [
        'green' => [
            'completed' => 'bg-duo-green border-duo-green-dark',
            'incomplete' => 'bg-duo-gray-100 border-duo-gray-200',
            'connector_done' => 'bg-duo-green',
            'connector_pending' => 'bg-duo-gray-200',
        ],
        'blue' => [
            'completed' => 'bg-duo-blue border-duo-blue',
            'incomplete' => 'bg-duo-gray-100 border-duo-gray-200',
            'connector_done' => 'bg-duo-blue',
            'connector_pending' => 'bg-duo-gray-200',
        ],
        'purple' => [
            'completed' => 'bg-duo-purple border-duo-purple',
            'incomplete' => 'bg-duo-gray-100 border-duo-gray-200',
            'connector_done' => 'bg-duo-purple',
            'connector_pending' => 'bg-duo-gray-200',
        ],
        'yellow' => [
            'completed' => 'bg-duo-yellow border-duo-yellow-dark',
            'incomplete' => 'bg-duo-gray-100 border-duo-gray-200',
            'connector_done' => 'bg-duo-yellow',
            'connector_pending' => 'bg-duo-gray-200',
        ],
        'orange' => [
            'completed' => 'bg-duo-orange border-duo-orange',
            'incomplete' => 'bg-duo-gray-100 border-duo-gray-200',
            'connector_done' => 'bg-duo-orange',
            'connector_pending' => 'bg-duo-gray-200',
        ],
    ];

    $beadSize = $sizes[$size] ?? $sizes['md'];
    $connectorSize = $connectorSizes[$size] ?? $connectorSizes['md'];
    $colorScheme = $colors[$color] ?? $colors['green'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center']) }}>
    @for($i = 0; $i < $total; $i++)
        @php
            $isCompleted = $i < $completed;
            $isCurrent = $i === $completed;
        @endphp

        {{-- Bead --}}
        <div class="flex flex-col items-center">
            <div class="relative">
                <div class="{{ $beadSize }} rounded-full border-2 transition-all duration-300 {{ $isCompleted ? $colorScheme['completed'] : $colorScheme['incomplete'] }} {{ $isCurrent && $animated ? 'animate-pulse ring-2 ring-offset-2 ring-duo-green/50' : '' }}">
                    @if($isCompleted)
                        <svg class="w-full h-full text-white p-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </div>
                @if($isCurrent && $animated)
                    <div class="absolute -inset-1 rounded-full bg-duo-green/20 animate-ping"></div>
                @endif
            </div>

            @if($showLabels && isset($labels[$i]))
                <span class="mt-1 text-xs font-medium {{ $isCompleted ? 'text-duo-gray-500' : 'text-duo-gray-300' }}">
                    {{ $labels[$i] }}
                </span>
            @endif
        </div>

        {{-- Connector (except after last bead) --}}
        @if($i < $total - 1)
            <div class="flex-1 mx-1 {{ $connectorSize }} rounded-full transition-all duration-300 {{ $i < $completed ? $colorScheme['connector_done'] : $colorScheme['connector_pending'] }}"></div>
        @endif
    @endfor
</div>
