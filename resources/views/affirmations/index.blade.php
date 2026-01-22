<x-app-layout>
    <x-slot name="title">Affirmations</x-slot>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-duo-gray-500 mb-2">Affirmations</h1>
            <p class="text-duo-gray-300">Speak your truth into existence</p>
        </div>
        <a href="{{ route('affirmations.create') }}">
            <x-primary-button>
                + Create Custom
            </x-primary-button>
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-8">
        <x-stat-card icon="💫" label="Today's Sessions" :value="$todaySessions" color="blue" />
        <x-stat-card icon="🔥" label="Affirmation Streak" :value="$streak?->current_count ?? 0" color="orange" />
        <x-stat-card icon="⭐" label="Longest Streak" :value="$streak?->longest_count ?? 0" color="yellow" />
    </div>

    <!-- Categories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($categories as $category)
            <a href="{{ route('affirmations.practice', $category) }}" class="block">
                <x-card interactive class="h-full">
                    <div class="flex items-start space-x-4">
                        <div class="text-4xl" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
                            {{ $category->icon }}
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-duo-gray-500 text-lg mb-1">{{ $category->name }}</h3>
                            <p class="text-sm text-duo-gray-200 mb-3">{{ $category->description }}</p>
                            <div class="flex items-center justify-between">
                                <x-badge :color="match($category->slug) {
                                    'wealth' => 'yellow',
                                    'health' => 'green',
                                    'love' => 'pink',
                                    'success' => 'blue',
                                    'confidence' => 'orange',
                                    'gratitude' => 'purple',
                                    default => 'gray'
                                }">
                                    {{ $category->affirmations_count }} affirmations
                                </x-badge>
                                <span class="text-duo-green font-bold text-sm">Practice →</span>
                            </div>
                        </div>
                    </div>
                </x-card>
            </a>
        @endforeach
    </div>

    <!-- Daily Tip -->
    <x-card class="mt-8 bg-gradient-to-r from-duo-purple/10 to-duo-pink/10 border-duo-purple/30">
        <div class="flex items-center space-x-4">
            <span class="text-4xl">💡</span>
            <div>
                <h3 class="font-bold text-duo-purple">Daily Tip</h3>
                <p class="text-duo-gray-400">For best results, practice your affirmations first thing in the morning and right before bed. Speak them out loud with conviction!</p>
            </div>
        </div>
    </x-card>
</x-app-layout>
