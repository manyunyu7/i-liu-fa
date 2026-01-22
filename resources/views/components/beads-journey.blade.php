@props([
    'items' => [],
    'color' => 'green',
    'showProgress' => true,
    'animated' => true,
    'celebrateComplete' => true,
])

@php
    $colors = [
        'green' => [
            'completed' => 'bg-duo-green border-duo-green-dark text-white shadow-lg shadow-duo-green/30',
            'current' => 'bg-white border-duo-green text-duo-green ring-4 ring-duo-green/20',
            'pending' => 'bg-duo-gray-100 border-duo-gray-200 text-duo-gray-300',
            'line_done' => 'bg-gradient-to-b from-duo-green to-duo-green',
            'line_pending' => 'bg-duo-gray-200',
            'highlight' => 'bg-duo-green/10',
        ],
        'blue' => [
            'completed' => 'bg-duo-blue border-duo-blue text-white shadow-lg shadow-duo-blue/30',
            'current' => 'bg-white border-duo-blue text-duo-blue ring-4 ring-duo-blue/20',
            'pending' => 'bg-duo-gray-100 border-duo-gray-200 text-duo-gray-300',
            'line_done' => 'bg-gradient-to-b from-duo-blue to-duo-blue',
            'line_pending' => 'bg-duo-gray-200',
            'highlight' => 'bg-duo-blue/10',
        ],
        'purple' => [
            'completed' => 'bg-duo-purple border-duo-purple text-white shadow-lg shadow-duo-purple/30',
            'current' => 'bg-white border-duo-purple text-duo-purple ring-4 ring-duo-purple/20',
            'pending' => 'bg-duo-gray-100 border-duo-gray-200 text-duo-gray-300',
            'line_done' => 'bg-gradient-to-b from-duo-purple to-duo-purple',
            'line_pending' => 'bg-duo-gray-200',
            'highlight' => 'bg-duo-purple/10',
        ],
        'yellow' => [
            'completed' => 'bg-duo-yellow border-duo-yellow-dark text-white shadow-lg shadow-duo-yellow/30',
            'current' => 'bg-white border-duo-yellow text-duo-yellow ring-4 ring-duo-yellow/20',
            'pending' => 'bg-duo-gray-100 border-duo-gray-200 text-duo-gray-300',
            'line_done' => 'bg-gradient-to-b from-duo-yellow to-duo-yellow',
            'line_pending' => 'bg-duo-gray-200',
            'highlight' => 'bg-duo-yellow/10',
        ],
        'orange' => [
            'completed' => 'bg-duo-orange border-duo-orange text-white shadow-lg shadow-duo-orange/30',
            'current' => 'bg-white border-duo-orange text-duo-orange ring-4 ring-duo-orange/20',
            'pending' => 'bg-duo-gray-100 border-duo-gray-200 text-duo-gray-300',
            'line_done' => 'bg-gradient-to-b from-duo-orange to-duo-orange',
            'line_pending' => 'bg-duo-gray-200',
            'highlight' => 'bg-duo-orange/10',
        ],
    ];

    $colorScheme = $colors[$color] ?? $colors['green'];
    $totalItems = count($items);
    $completedItems = collect($items)->where('completed', true)->count();
    $progress = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
    $isAllComplete = $completedItems >= $totalItems;
@endphp

<div {{ $attributes->merge(['class' => '']) }}
     x-data="{ mounted: false }"
     x-init="setTimeout(() => mounted = true, 100)">
    @if($showProgress)
        <div class="flex items-center justify-between mb-4">
            <span class="text-sm font-bold text-duo-gray-400">Progress</span>
            <div class="flex items-center space-x-2">
                <span class="text-sm font-bold text-duo-green">{{ $completedItems }}/{{ $totalItems }} ({{ $progress }}%)</span>
                @if($isAllComplete && $celebrateComplete)
                    <span class="animate-bounce">🎉</span>
                @endif
            </div>
        </div>
        <x-beads-progress :total="$totalItems" :completed="$completedItems" :color="$color" :animated="$animated" class="mb-6" />
    @endif

    <div class="relative">
        @foreach($items as $index => $item)
            @php
                $isCompleted = $item['completed'] ?? false;
                $isCurrent = !$isCompleted && ($index === 0 || ($items[$index - 1]['completed'] ?? false));
                $isPending = !$isCompleted && !$isCurrent;
                $stateClass = $isCompleted ? $colorScheme['completed'] : ($isCurrent ? $colorScheme['current'] : $colorScheme['pending']);
                $delay = $index * 150;
            @endphp

            <div class="relative flex items-start {{ $index < $totalItems - 1 ? 'pb-8' : '' }} group"
                 x-bind:class="{ 'opacity-100 translate-x-0': mounted, 'opacity-0 -translate-x-4': !mounted }"
                 style="transition: all 0.5s ease-out; transition-delay: {{ $delay }}ms">

                {{-- Vertical Line --}}
                @if($index < $totalItems - 1)
                    <div class="absolute left-5 top-10 w-0.5 h-full -ml-px overflow-hidden {{ $colorScheme['line_pending'] }}">
                        <div class="w-full {{ $colorScheme['line_done'] }} transition-all duration-700 ease-out"
                             style="height: {{ $isCompleted ? '100%' : '0%' }}"
                             x-bind:style="mounted ? { height: '{{ $isCompleted ? '100%' : '0%' }}' } : { height: '0%' }">
                        </div>
                    </div>
                @endif

                {{-- Bead/Circle --}}
                <div class="relative flex-shrink-0 z-10">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 {{ $stateClass }} transition-all duration-300 transform group-hover:scale-110">
                        @if($isCompleted)
                            <svg class="w-5 h-5 animate-[checkmark_0.3s_ease-out]" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        @elseif($isCurrent)
                            <div class="w-3 h-3 rounded-full bg-current animate-pulse"></div>
                        @else
                            <span class="text-sm font-bold">{{ $index + 1 }}</span>
                        @endif
                    </div>

                    {{-- Pulse animation for current --}}
                    @if($isCurrent && $animated)
                        <div class="absolute -inset-1 rounded-full bg-duo-green/20 animate-ping"></div>
                    @endif

                    {{-- Sparkle for completed --}}
                    @if($isCompleted && $animated)
                        <div class="absolute -top-1 -right-1 w-2 h-2 bg-white rounded-full animate-[sparkle_1.5s_ease-in-out_infinite]" style="animation-delay: {{ $index * 200 }}ms"></div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="ml-4 flex-1 p-3 rounded-lg transition-all duration-300 {{ $isCurrent ? $colorScheme['highlight'] : '' }} group-hover:bg-duo-gray-50/50">
                    <div class="flex items-center flex-wrap gap-2">
                        <h4 class="text-base font-bold transition-colors duration-300 {{ $isCompleted ? 'text-duo-gray-500' : ($isCurrent ? 'text-duo-gray-500' : 'text-duo-gray-300') }}">
                            {{ $item['title'] ?? 'Step ' . ($index + 1) }}
                        </h4>
                        @if($isCompleted)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-duo-green/10 text-duo-green">
                                Done
                            </span>
                        @elseif($isCurrent)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-duo-yellow/10 text-duo-yellow animate-pulse">
                                In Progress
                            </span>
                        @endif
                        @if($isCompleted && isset($item['completed_at']))
                            <span class="text-xs text-duo-gray-200">{{ $item['completed_at'] }}</span>
                        @endif
                    </div>
                    @if(isset($item['description']))
                        <p class="mt-1 text-sm transition-colors duration-300 {{ $isCompleted ? 'text-duo-gray-300' : ($isCurrent ? 'text-duo-gray-400' : 'text-duo-gray-200') }}">
                            {{ $item['description'] }}
                        </p>
                    @endif
                    @if(isset($item['xp']) && $isCompleted)
                        <div class="mt-2 inline-flex items-center text-xs font-bold text-duo-yellow">
                            <span class="mr-1">⭐</span> +{{ $item['xp'] }} XP earned
                        </div>
                    @endif
                    @if($isCurrent && isset($item['action']))
                        <div class="mt-3">
                            {{ $item['action'] }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Completion celebration --}}
    @if($isAllComplete && $celebrateComplete)
        <div class="mt-6 p-4 rounded-duo bg-gradient-to-r from-duo-green/10 to-duo-blue/10 border border-duo-green/20 text-center animate-[fadeIn_0.5s_ease-out]">
            <span class="text-3xl">🎊</span>
            <p class="font-bold text-duo-gray-500 mt-2">Journey Complete!</p>
            <p class="text-sm text-duo-gray-300">You've completed all {{ $totalItems }} steps. Amazing work!</p>
        </div>
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

@keyframes fadeIn {
    0% { opacity: 0; transform: translateY(10px); }
    100% { opacity: 1; transform: translateY(0); }
}
</style>
