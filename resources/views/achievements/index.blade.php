<x-app-layout>
    <x-slot name="title">Achievements</x-slot>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-duo-gray-500 mb-2">Achievements</h1>
        <p class="text-duo-gray-300">Celebrate your milestones and accomplishments</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-8">
        <x-stat-card icon="🏆" label="Unlocked" :value="$stats['unlocked'] . '/' . $stats['total']" color="yellow" />
        <x-stat-card icon="⭐" label="Completion" :value="round(($stats['unlocked'] / max(1, $stats['total'])) * 100) . '%'" color="purple" />
        <x-stat-card icon="⚡" label="XP from Achievements" :value="number_format($stats['total_xp_from_achievements'])" color="orange" />
    </div>

    <!-- Achievements by Category -->
    @foreach($groupedAchievements as $category => $achievements)
        <div class="mb-8">
            <h2 class="text-xl font-bold text-duo-gray-500 mb-4 flex items-center space-x-2">
                <span>{{ match($category) { 'streak' => '🔥', 'completion' => '✅', 'milestone' => '🎯', 'special' => '✨', default => '🏆' } }}</span>
                <span>{{ ucfirst($category) }} Achievements</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($achievements as $achievement)
                    <x-card class="{{ $achievement->is_unlocked ? 'bg-gradient-to-br from-duo-yellow/10 to-duo-orange/10 border-duo-yellow' : 'opacity-60' }} group hover:shadow-duo-lg transition-all">
                        <a href="{{ route('achievements.show', $achievement) }}" class="block">
                            <div class="flex items-start space-x-4">
                                <!-- Icon -->
                                <div class="w-16 h-16 rounded-duo flex items-center justify-center text-4xl flex-shrink-0 {{ $achievement->is_unlocked ? '' : 'grayscale' }} group-hover:scale-110 transition-transform"
                                     style="background-color: {{ $achievement->badge_color }}20">
                                    {{ $achievement->icon }}
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <h3 class="font-bold text-duo-gray-500 group-hover:text-duo-green transition-colors">{{ $achievement->name }}</h3>
                                        @if($achievement->is_unlocked)
                                            <span class="text-duo-green text-lg">✓</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-duo-gray-300 mb-2">{{ $achievement->description }}</p>

                                    @if(!$achievement->is_unlocked)
                                        <x-progress-bar :progress="$achievement->progress" color="yellow" size="sm" />
                                        <p class="text-xs text-duo-gray-200 mt-1">{{ round($achievement->progress) }}% complete</p>
                                    @else
                                        <p class="text-xs text-duo-green font-bold">
                                            Unlocked {{ $achievement->unlocked_at->diffForHumans() }}
                                        </p>
                                    @endif

                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="text-sm font-bold text-duo-yellow">+{{ $achievement->xp_reward }} XP</span>
                                        @if($achievement->is_unlocked)
                                            <span class="text-xs text-duo-blue opacity-0 group-hover:opacity-100 transition-opacity">
                                                Click to share
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </x-card>
                @endforeach
            </div>
        </div>
    @endforeach

    <!-- Motivation Card -->
    <x-card class="bg-gradient-to-r from-duo-purple/10 to-duo-pink/10 border-duo-purple/30">
        <div class="flex items-center space-x-4">
            <span class="text-4xl">🎖️</span>
            <div>
                <h3 class="font-bold text-duo-purple">Keep Going!</h3>
                <p class="text-duo-gray-400">Every achievement is a step forward on your journey. Stay consistent and watch your collection grow!</p>
            </div>
        </div>
    </x-card>
</x-app-layout>
