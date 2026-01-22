@props([
    'items' => [],
    'color' => 'green',
    'showProgress' => true,
])

@php
    $colors = [
        'green' => [
            'completed' => 'bg-duo-green border-duo-green-dark text-white',
            'current' => 'bg-white border-duo-green text-duo-green ring-4 ring-duo-green/20',
            'pending' => 'bg-duo-gray-100 border-duo-gray-200 text-duo-gray-300',
            'line_done' => 'bg-duo-green',
            'line_pending' => 'bg-duo-gray-200',
        ],
        'blue' => [
            'completed' => 'bg-duo-blue border-duo-blue text-white',
            'current' => 'bg-white border-duo-blue text-duo-blue ring-4 ring-duo-blue/20',
            'pending' => 'bg-duo-gray-100 border-duo-gray-200 text-duo-gray-300',
            'line_done' => 'bg-duo-blue',
            'line_pending' => 'bg-duo-gray-200',
        ],
        'purple' => [
            'completed' => 'bg-duo-purple border-duo-purple text-white',
            'current' => 'bg-white border-duo-purple text-duo-purple ring-4 ring-duo-purple/20',
            'pending' => 'bg-duo-gray-100 border-duo-gray-200 text-duo-gray-300',
            'line_done' => 'bg-duo-purple',
            'line_pending' => 'bg-duo-gray-200',
        ],
    ];

    $colorScheme = $colors[$color] ?? $colors['green'];
    $totalItems = count($items);
    $completedItems = collect($items)->where('completed', true)->count();
    $progress = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
@endphp

<div {{ $attributes->merge(['class' => '']) }}>
    @if($showProgress)
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-bold text-duo-gray-400">Progress</span>
            <span class="text-sm font-bold text-duo-green">{{ $completedItems }}/{{ $totalItems }} ({{ $progress }}%)</span>
        </div>
        <x-beads-progress :total="$totalItems" :completed="$completedItems" :color="$color" class="mb-6" />
    @endif

    <div class="relative">
        @foreach($items as $index => $item)
            @php
                $isCompleted = $item['completed'] ?? false;
                $isCurrent = !$isCompleted && ($index === 0 || ($items[$index - 1]['completed'] ?? false));
                $isPending = !$isCompleted && !$isCurrent;

                $stateClass = $isCompleted ? $colorScheme['completed'] : ($isCurrent ? $colorScheme['current'] : $colorScheme['pending']);
            @endphp

            <div class="relative flex items-start {{ $index < $totalItems - 1 ? 'pb-8' : '' }}">
                {{-- Vertical Line --}}
                @if($index < $totalItems - 1)
                    <div class="absolute left-5 top-10 w-0.5 h-full -ml-px {{ $isCompleted ? $colorScheme['line_done'] : $colorScheme['line_pending'] }}"></div>
                @endif

                {{-- Bead/Circle --}}
                <div class="relative flex items-center justify-center w-10 h-10 rounded-full border-2 {{ $stateClass }} flex-shrink-0 z-10 transition-all duration-300">
                    @if($isCompleted)
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @elseif($isCurrent)
                        <div class="w-3 h-3 rounded-full bg-current animate-pulse"></div>
                    @else
                        <span class="text-sm font-bold">{{ $index + 1 }}</span>
                    @endif
                </div>

                {{-- Content --}}
                <div class="ml-4 flex-1">
                    <div class="flex items-center">
                        <h4 class="text-base font-bold {{ $isCompleted ? 'text-duo-gray-500' : ($isCurrent ? 'text-duo-gray-500' : 'text-duo-gray-300') }}">
                            {{ $item['title'] ?? 'Step ' . ($index + 1) }}
                        </h4>
                        @if($isCompleted && isset($item['completed_at']))
                            <span class="ml-2 text-xs text-duo-gray-200">{{ $item['completed_at'] }}</span>
                        @endif
                    </div>
                    @if(isset($item['description']))
                        <p class="mt-1 text-sm {{ $isCompleted ? 'text-duo-gray-300' : ($isCurrent ? 'text-duo-gray-400' : 'text-duo-gray-200') }}">
                            {{ $item['description'] }}
                        </p>
                    @endif
                    @if($isCurrent && isset($item['action']))
                        <div class="mt-2">
                            {{ $item['action'] }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
