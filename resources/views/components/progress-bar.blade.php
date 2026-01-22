@props(['progress' => 0, 'color' => 'green', 'size' => 'md', 'showLabel' => false])

@php
    $colorClasses = [
        'green' => 'from-duo-green to-duo-green-light',
        'blue' => 'from-duo-blue to-duo-blue-dark',
        'purple' => 'from-duo-purple to-duo-purple-dark',
        'orange' => 'from-duo-orange to-duo-orange-dark',
        'yellow' => 'from-duo-yellow to-duo-orange',
        'pink' => 'from-duo-pink to-duo-purple',
    ];

    $sizeClasses = [
        'sm' => 'h-2',
        'md' => 'h-4',
        'lg' => 'h-6',
    ];

    $gradientClass = $colorClasses[$color] ?? $colorClasses['green'];
    $heightClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="w-full">
    @if($showLabel)
        <div class="flex justify-between text-sm font-bold text-duo-gray-300 mb-1">
            <span>Progress</span>
            <span>{{ $progress }}%</span>
        </div>
    @endif
    <div class="w-full {{ $heightClass }} bg-duo-gray-100 rounded-full overflow-hidden">
        <div class="h-full bg-gradient-to-r {{ $gradientClass }} rounded-full transition-all duration-500 ease-out"
             style="width: {{ min(100, max(0, $progress)) }}%">
        </div>
    </div>
</div>
