<x-app-layout>
    <x-slot name="title">Statistics & Analytics</x-slot>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-duo-gray-500">Your Progress</h1>
                <p class="text-duo-gray-300">Track your growth and achievements</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-duo-gray-300">Time Period:</span>
                <select onchange="window.location.href='?period='+this.value"
                        class="px-4 py-2 border-2 border-duo-gray-100 rounded-duo text-sm font-bold focus:border-duo-green focus:ring-0">
                    <option value="7" {{ $period == '7' ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30" {{ $period == '30' ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90" {{ $period == '90' ? 'selected' : '' }}>Last 90 days</option>
                    <option value="365" {{ $period == '365' ? 'selected' : '' }}>Last year</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Overall Stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <x-card class="text-center">
            <span class="text-3xl">⭐</span>
            <p class="text-2xl font-extrabold text-duo-green mt-2">{{ number_format($overallStats['total_xp']) }}</p>
            <p class="text-sm text-duo-gray-300">Total XP</p>
        </x-card>
        <x-card class="text-center">
            <span class="text-3xl">🏅</span>
            <p class="text-2xl font-extrabold text-duo-blue mt-2">{{ $overallStats['current_level'] }}</p>
            <p class="text-sm text-duo-gray-300">Level</p>
        </x-card>
        <x-card class="text-center">
            <span class="text-3xl">🔥</span>
            <p class="text-2xl font-extrabold text-duo-orange mt-2">{{ $overallStats['current_streak'] }}</p>
            <p class="text-sm text-duo-gray-300">Current Streak</p>
        </x-card>
        <x-card class="text-center">
            <span class="text-3xl">🏆</span>
            <p class="text-2xl font-extrabold text-duo-yellow mt-2">{{ $overallStats['longest_streak'] }}</p>
            <p class="text-sm text-duo-gray-300">Best Streak</p>
        </x-card>
        <x-card class="text-center">
            <span class="text-3xl">💎</span>
            <p class="text-2xl font-extrabold text-duo-purple mt-2">{{ number_format($overallStats['gems']) }}</p>
            <p class="text-sm text-duo-gray-300">Gems</p>
        </x-card>
        <x-card class="text-center">
            <span class="text-3xl">📅</span>
            <p class="text-lg font-bold text-duo-gray-500 mt-2">{{ $overallStats['member_since'] }}</p>
            <p class="text-sm text-duo-gray-300">Member Since</p>
        </x-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Activity Stats -->
        <x-card>
            <h2 class="text-xl font-bold text-duo-gray-500 mb-4">Recent Activity (Last {{ $period }} days)</h2>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">💫</span>
                        <span class="text-duo-gray-500">Affirmations Completed</span>
                    </div>
                    <span class="font-bold text-duo-green">{{ $activityStats['affirmations_completed'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">🔄</span>
                        <span class="text-duo-gray-500">Habits Logged</span>
                    </div>
                    <span class="font-bold text-duo-blue">{{ $activityStats['habits_logged'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">✅</span>
                        <span class="text-duo-gray-500">Tasks Completed</span>
                    </div>
                    <span class="font-bold text-duo-purple">{{ $activityStats['tasks_completed'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">📝</span>
                        <span class="text-duo-gray-500">Reflections Written</span>
                    </div>
                    <span class="font-bold text-duo-yellow">{{ $activityStats['reflections_written'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">🌟</span>
                        <span class="text-duo-gray-500">Dreams Manifested</span>
                    </div>
                    <span class="font-bold text-duo-orange">{{ $activityStats['dreams_manifested'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">🎯</span>
                        <span class="text-duo-gray-500">Bucket List Items Done</span>
                    </div>
                    <span class="font-bold text-duo-red">{{ $activityStats['bucket_items_completed'] }}</span>
                </div>
            </div>
        </x-card>

        <!-- XP Breakdown -->
        <x-card>
            <h2 class="text-xl font-bold text-duo-gray-500 mb-4">XP Breakdown (Last {{ $period }} days)</h2>
            @php
                $totalXpPeriod = array_sum($xpBreakdown);
            @endphp
            @if($totalXpPeriod > 0)
                <div class="space-y-3">
                    @foreach($xpBreakdown as $source => $amount)
                        @if($amount > 0)
                            @php
                                $percentage = ($amount / $totalXpPeriod) * 100;
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-duo-gray-500">{{ $source }}</span>
                                    <span class="font-bold text-duo-green">+{{ number_format($amount) }} XP</span>
                                </div>
                                <div class="h-2 bg-duo-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-duo-green rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t border-duo-gray-100">
                    <div class="flex justify-between">
                        <span class="font-bold text-duo-gray-500">Total XP Earned</span>
                        <span class="font-bold text-duo-green">+{{ number_format($totalXpPeriod) }} XP</span>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <span class="text-4xl">📊</span>
                    <p class="text-duo-gray-300 mt-2">No XP earned in this period yet!</p>
                </div>
            @endif
        </x-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Weekly Activity Heatmap -->
        <x-card>
            <h2 class="text-xl font-bold text-duo-gray-500 mb-4">This Week's Activity</h2>
            <div class="grid grid-cols-7 gap-2">
                @foreach($weeklyActivity as $day => $count)
                    @php
                        $intensity = $count > 0 ? min(100, $count * 20) : 0;
                    @endphp
                    <div class="text-center">
                        <div class="w-full aspect-square rounded-duo flex items-center justify-center text-white font-bold text-lg"
                             style="background-color: {{ $count > 0 ? "rgba(88, 204, 2, " . ($intensity / 100) . ")" : '#f0f0f0' }};">
                            {{ $count }}
                        </div>
                        <p class="text-xs text-duo-gray-300 mt-1">{{ $day }}</p>
                    </div>
                @endforeach
            </div>
        </x-card>

        <!-- Mood Trends -->
        <x-card>
            <h2 class="text-xl font-bold text-duo-gray-500 mb-4">Mood Trends</h2>
            @if(count($moodTrends['mood_distribution']) > 0)
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-duo-gray-300">Average Mood Score</span>
                        <span class="text-2xl font-bold text-duo-green">
                            {{ number_format($moodTrends['average_score'] ?? 0, 1) }}/10
                        </span>
                    </div>
                </div>
                <div class="space-y-2">
                    <p class="text-sm font-bold text-duo-gray-500">Mood Distribution</p>
                    @foreach($moodTrends['mood_distribution'] as $mood => $count)
                        <div class="flex items-center justify-between text-sm">
                            <span class="capitalize">{{ \App\Models\Reflection::getMoodEmoji($mood) }} {{ $mood }}</span>
                            <span class="font-bold">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <span class="text-4xl">😊</span>
                    <p class="text-duo-gray-300 mt-2">Start logging reflections to track your mood!</p>
                </div>
            @endif
        </x-card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Feature Usage -->
        <x-card>
            <h2 class="text-xl font-bold text-duo-gray-500 mb-4">Your Collection</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-duo-gray-50 rounded-duo">
                    <p class="text-2xl font-bold text-duo-green">{{ $featureStats['total_affirmations'] }}</p>
                    <p class="text-sm text-duo-gray-300">Affirmations</p>
                </div>
                <div class="p-4 bg-duo-gray-50 rounded-duo">
                    <p class="text-2xl font-bold text-duo-blue">{{ $featureStats['active_habits'] }}/{{ $featureStats['total_habits'] }}</p>
                    <p class="text-sm text-duo-gray-300">Active Habits</p>
                </div>
                <div class="p-4 bg-duo-gray-50 rounded-duo">
                    <p class="text-2xl font-bold text-duo-yellow">{{ $featureStats['manifested_dreams'] }}/{{ $featureStats['total_dreams'] }}</p>
                    <p class="text-sm text-duo-gray-300">Manifested Dreams</p>
                </div>
                <div class="p-4 bg-duo-gray-50 rounded-duo">
                    <p class="text-2xl font-bold text-duo-orange">{{ $featureStats['bucket_list_completed'] }}/{{ $featureStats['bucket_list_items'] }}</p>
                    <p class="text-sm text-duo-gray-300">Bucket List Done</p>
                </div>
                <div class="p-4 bg-duo-gray-50 rounded-duo">
                    <p class="text-2xl font-bold text-duo-purple">{{ $featureStats['vision_boards'] }}</p>
                    <p class="text-sm text-duo-gray-300">Vision Boards</p>
                </div>
                <div class="p-4 bg-duo-gray-50 rounded-duo">
                    <p class="text-2xl font-bold text-duo-red">{{ $featureStats['favorite_quotes'] }}</p>
                    <p class="text-sm text-duo-gray-300">Favorite Quotes</p>
                </div>
            </div>
        </x-card>

        <!-- Achievement Progress -->
        <x-card>
            <h2 class="text-xl font-bold text-duo-gray-500 mb-4">Achievements</h2>
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-duo-gray-300">Unlocked</span>
                    <span class="font-bold text-duo-green">{{ $achievementStats['unlocked'] }}/{{ $achievementStats['total'] }}</span>
                </div>
                <div class="h-3 bg-duo-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-duo-green rounded-full" style="width: {{ $achievementStats['percentage'] }}%"></div>
                </div>
            </div>

            @if($achievementStats['recent']->isNotEmpty())
                <p class="text-sm font-bold text-duo-gray-500 mb-3">Recently Unlocked</p>
                <div class="space-y-2">
                    @foreach($achievementStats['recent'] as $userAchievement)
                        <div class="flex items-center space-x-3 p-2 bg-duo-gray-50 rounded-duo">
                            <span class="text-2xl">{{ $userAchievement->achievement->icon }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-duo-gray-500 text-sm truncate">{{ $userAchievement->achievement->name }}</p>
                                <p class="text-xs text-duo-gray-300">{{ $userAchievement->unlocked_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-duo-gray-300">No achievements unlocked yet!</p>
                </div>
            @endif

            <a href="{{ route('achievements.index') }}" class="mt-4 block">
                <x-button color="gray" size="sm" class="w-full">View All Achievements</x-button>
            </a>
        </x-card>
    </div>
</x-app-layout>
