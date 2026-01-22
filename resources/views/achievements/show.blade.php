<x-app-layout>
    <x-slot name="title">{{ $achievement->name }}</x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <!-- Back Link -->
        <a href="{{ route('achievements.index') }}" class="inline-flex items-center text-duo-blue hover:text-duo-blue-dark font-bold">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Achievements
        </a>

        <!-- Achievement Card -->
        <div class="bg-white rounded-duo shadow-duo p-8 {{ $achievement->is_unlocked ? 'border-2 border-duo-yellow' : '' }}">
            <div class="text-center mb-6">
                <!-- Badge -->
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full mb-4 {{ $achievement->is_unlocked ? '' : 'grayscale opacity-50' }}"
                     style="background-color: {{ $achievement->badge_color }}20">
                    <span class="text-6xl">{{ $achievement->icon }}</span>
                </div>

                <!-- Status Badge -->
                @if($achievement->is_unlocked)
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-duo-green/10 text-duo-green text-sm font-bold mb-2">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Unlocked!
                    </div>
                @else
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-duo-gray-100 text-duo-gray-400 text-sm font-bold mb-2">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        Locked
                    </div>
                @endif

                <h1 class="text-2xl font-extrabold text-duo-gray-500 mb-2">{{ $achievement->name }}</h1>
                <p class="text-duo-gray-300">{{ $achievement->description }}</p>
            </div>

            <!-- Progress / Unlocked Info -->
            <div class="mb-6">
                @if($achievement->is_unlocked)
                    <div class="bg-duo-yellow/10 rounded-duo p-4 text-center">
                        <p class="text-sm text-duo-gray-400">Unlocked on</p>
                        <p class="text-lg font-bold text-duo-yellow">{{ $achievement->unlocked_at->format('F j, Y') }}</p>
                        <p class="text-sm text-duo-gray-300">{{ $achievement->unlocked_at->diffForHumans() }}</p>
                    </div>
                @else
                    <div class="bg-duo-gray-50 rounded-duo p-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-bold text-duo-gray-400">Progress</span>
                            <span class="font-bold text-duo-gray-500">{{ round($achievement->progress) }}%</span>
                        </div>
                        <x-progress-bar :progress="$achievement->progress" color="yellow" />
                    </div>
                @endif
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-duo-gray-50 rounded-duo p-4 text-center">
                    <p class="text-2xl font-bold text-duo-yellow">+{{ $achievement->xp_reward }}</p>
                    <p class="text-sm text-duo-gray-300">XP Reward</p>
                </div>
                <div class="bg-duo-gray-50 rounded-duo p-4 text-center">
                    <p class="text-2xl font-bold text-duo-purple capitalize">{{ $achievement->category }}</p>
                    <p class="text-sm text-duo-gray-300">Category</p>
                </div>
            </div>

            <!-- Share Button (only if unlocked) -->
            @if($achievement->is_unlocked)
                <div class="border-t border-duo-gray-100 pt-6">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-duo-gray-300">Share your achievement with friends!</p>
                        <x-share-button
                            :title="'I just unlocked the ' . $achievement->name . ' achievement on DuoManifest!'"
                            :text="$achievement->description . ' - Join me on my journey of personal growth!'"
                            :url="route('achievements.share-card', [$achievement, $user->id])"
                        />
                    </div>
                </div>
            @endif
        </div>

        <!-- Requirements -->
        <x-card>
            <h2 class="font-bold text-duo-gray-500 mb-4">How to Unlock</h2>
            <div class="flex items-start space-x-3">
                <span class="text-2xl">
                    {{ match($achievement->requirement_type) {
                        'streak_days', 'streak' => '🔥',
                        'total_xp', 'xp_total' => '⭐',
                        'level' => '📈',
                        'affirmations_completed' => '💫',
                        'bucket_list_completed' => '🎯',
                        'dreams_manifested' => '🌟',
                        'planner_tasks_completed' => '✅',
                        default => '🏆'
                    } }}
                </span>
                <div>
                    <p class="font-bold text-duo-gray-500">
                        {{ match($achievement->requirement_type) {
                            'streak_days', 'streak' => 'Maintain a ' . $achievement->requirement_value . ' day streak',
                            'total_xp', 'xp_total' => 'Earn ' . number_format($achievement->requirement_value) . ' total XP',
                            'level' => 'Reach level ' . $achievement->requirement_value,
                            'affirmations_completed' => 'Complete ' . $achievement->requirement_value . ' affirmation sessions',
                            'bucket_list_completed' => 'Complete ' . $achievement->requirement_value . ' bucket list items',
                            'dreams_manifested' => 'Manifest ' . $achievement->requirement_value . ' dreams',
                            'planner_tasks_completed' => 'Complete ' . $achievement->requirement_value . ' planner tasks',
                            default => 'Complete special requirements'
                        } }}
                    </p>
                    @if(!$achievement->is_unlocked)
                        <p class="text-sm text-duo-gray-300 mt-1">
                            You're {{ round($achievement->progress) }}% of the way there. Keep going!
                        </p>
                    @endif
                </div>
            </div>
        </x-card>
    </div>
</x-app-layout>
