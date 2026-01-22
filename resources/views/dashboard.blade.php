<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-duo-gray-500 mb-2">
            Welcome back, {{ auth()->user()->name }}!
        </h1>
        <p class="text-duo-gray-300">Keep up the great work on your manifestation journey.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-stat-card icon="🔥" label="Day Streak" :value="$stats['streak']" color="orange" />
        <x-stat-card icon="⚡" label="Total XP" :value="number_format($stats['total_xp'])" color="yellow" />
        <x-stat-card icon="👑" label="Level" :value="$stats['level']" color="purple" />
        <x-stat-card icon="💫" label="Affirmations Today" :value="$stats['affirmations_today']" color="blue" />
    </div>

    <!-- Quick Actions -->
    <x-card class="mb-8">
        <h2 class="text-xl font-bold text-duo-gray-500 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('affirmations.index') }}" class="flex flex-col items-center p-4 rounded-duo bg-duo-green-light/10 hover:bg-duo-green-light/20 transition-colors">
                <span class="text-3xl mb-2">💫</span>
                <span class="font-bold text-duo-green text-sm">Practice Affirmations</span>
            </a>
            <a href="{{ route('bucket-list.create') }}" class="flex flex-col items-center p-4 rounded-duo bg-duo-blue/10 hover:bg-duo-blue/20 transition-colors">
                <span class="text-3xl mb-2">🎯</span>
                <span class="font-bold text-duo-blue text-sm">Add to Bucket List</span>
            </a>
            <a href="{{ route('dreams.create') }}" class="flex flex-col items-center p-4 rounded-duo bg-duo-purple/10 hover:bg-duo-purple/20 transition-colors">
                <span class="text-3xl mb-2">🌟</span>
                <span class="font-bold text-duo-purple text-sm">Create a Dream</span>
            </a>
            <a href="{{ route('planner.create') }}" class="flex flex-col items-center p-4 rounded-duo bg-duo-orange/10 hover:bg-duo-orange/20 transition-colors">
                <span class="text-3xl mb-2">📅</span>
                <span class="font-bold text-duo-orange text-sm">Plan Your Day</span>
            </a>
        </div>
    </x-card>

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Today's Tasks -->
        <x-card>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-duo-gray-500">Today's Tasks</h2>
                <a href="{{ route('planner.index') }}" class="text-sm font-bold text-duo-blue hover:underline">View All</a>
            </div>

            @if($todayTasks->count() > 0)
                <div class="space-y-3">
                    @foreach($todayTasks as $task)
                        <div class="flex items-center space-x-3 p-3 rounded-duo {{ $task->is_completed ? 'bg-duo-green-light/10' : 'bg-duo-gray-50' }}">
                            <form action="{{ route('planner.toggle', $task) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors {{ $task->is_completed ? 'bg-duo-green border-duo-green text-white' : 'border-duo-gray-200 hover:border-duo-green' }}">
                                    @if($task->is_completed)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @endif
                                </button>
                            </form>
                            <div class="flex-1">
                                <p class="font-bold text-duo-gray-400 {{ $task->is_completed ? 'line-through text-duo-gray-200' : '' }}">
                                    {{ $task->title }}
                                </p>
                                <x-badge size="sm" :color="match($task->task_type) { 'intention' => 'purple', 'goal' => 'blue', 'habit' => 'green', default => 'gray' }">
                                    {{ ucfirst($task->task_type) }}
                                </x-badge>
                            </div>
                            <span class="text-sm font-bold text-duo-yellow">+{{ $task->xp_reward }} XP</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <span class="text-4xl mb-2 block">📅</span>
                    <p class="text-duo-gray-300 font-bold">No tasks for today</p>
                    <a href="{{ route('planner.create') }}" class="text-duo-blue font-bold hover:underline">Add your first task</a>
                </div>
            @endif
        </x-card>

        <!-- Active Habits -->
        <x-card>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-duo-gray-500">Today's Habits</h2>
                <a href="{{ route('habits.index') }}" class="text-sm font-bold text-duo-blue hover:underline">View All</a>
            </div>

            @if($activeHabits->count() > 0)
                <div class="space-y-3">
                    @foreach($activeHabits as $habit)
                        @php
                            $log = $habit->logs->first();
                            $progress = $log ? ($log->count / $habit->target_count) * 100 : 0;
                            $isComplete = $log && $log->count >= $habit->target_count;
                        @endphp
                        <div class="p-3 rounded-duo {{ $isComplete ? 'bg-duo-green-light/10' : 'bg-duo-gray-50' }}">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xl">{{ $habit->icon }}</span>
                                    <span class="font-bold text-duo-gray-400">{{ $habit->name }}</span>
                                </div>
                                <form action="{{ route('habits.log', $habit) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-full text-sm font-bold transition-colors {{ $isComplete ? 'bg-duo-green text-white' : 'bg-duo-gray-100 text-duo-gray-400 hover:bg-duo-green hover:text-white' }}" {{ $isComplete ? 'disabled' : '' }}>
                                        {{ $isComplete ? 'Done!' : '+1' }}
                                    </button>
                                </form>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="flex-1">
                                    <x-progress-bar :progress="$progress" color="green" size="sm" />
                                </div>
                                <span class="text-xs font-bold text-duo-gray-300">
                                    {{ $log ? $log->count : 0 }}/{{ $habit->target_count }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <span class="text-4xl mb-2 block">🔄</span>
                    <p class="text-duo-gray-300 font-bold">No habits yet</p>
                    <a href="{{ route('habits.create') }}" class="text-duo-blue font-bold hover:underline">Create your first habit</a>
                </div>
            @endif
        </x-card>
    </div>

    <!-- Recent Achievements -->
    @if($recentAchievements->count() > 0)
        <x-card class="mt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-duo-gray-500">Recent Achievements</h2>
                <a href="{{ route('achievements.index') }}" class="text-sm font-bold text-duo-blue hover:underline">View All</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($recentAchievements as $userAchievement)
                    <div class="flex items-center space-x-3 p-3 rounded-duo bg-duo-yellow/10">
                        <span class="text-3xl">{{ $userAchievement->achievement->icon }}</span>
                        <div>
                            <p class="font-bold text-duo-gray-500">{{ $userAchievement->achievement->name }}</p>
                            <p class="text-xs text-duo-gray-200">{{ $userAchievement->unlocked_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card>
    @endif

    <!-- Progress Overview with Beads -->
    <x-card class="mt-6">
        <h2 class="text-xl font-bold text-duo-gray-500 mb-6">Your Progress</h2>

        {{-- Circular Progress Beads --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <x-beads-circle
                :progress="$stats['bucket_list_completed']"
                :total="max(1, $stats['bucket_list_total'] ?? 10)"
                size="md"
                color="green"
                label="Bucket List"
                :sublabel="$stats['bucket_list_completed'] . ' completed'"
            />
            <x-beads-circle
                :progress="$stats['dreams_manifested']"
                :total="max(1, $stats['dreams_total'] ?? 5)"
                size="md"
                color="purple"
                label="Dreams"
                :sublabel="$stats['dreams_manifested'] . ' manifested'"
            />
            <x-beads-circle
                :progress="$stats['tasks_completed_today']"
                :total="max(1, $stats['tasks_total_today'] ?? 5)"
                size="md"
                color="blue"
                label="Today's Tasks"
                :sublabel="$stats['tasks_completed_today'] . ' done'"
            />
            <x-beads-circle
                :progress="min($stats['affirmations_today'], 10)"
                :total="10"
                size="md"
                color="orange"
                label="Affirmations"
                :sublabel="$stats['affirmations_today'] . ' practiced'"
            />
        </div>

        {{-- Level Progress Beads --}}
        <div class="border-t border-duo-gray-100 pt-6">
            <div class="flex items-center justify-between mb-2">
                <span class="font-bold text-duo-gray-400">Level {{ $stats['level'] }} Progress</span>
                <span class="text-sm font-bold text-duo-green">{{ auth()->user()->xp_to_next_level }} XP to next level</span>
            </div>
            <x-beads-progress
                :total="10"
                :completed="(int) ceil(auth()->user()->level_progress / 10)"
                size="lg"
                color="yellow"
            />
        </div>
    </x-card>

    {{-- Weekly Journey Progress --}}
    <x-card class="mt-6">
        <h2 class="text-xl font-bold text-duo-gray-500 mb-4">This Week's Journey</h2>
        <x-beads-progress
            :total="7"
            :completed="$stats['streak'] > 7 ? 7 : $stats['streak']"
            size="xl"
            color="green"
            :showLabels="true"
            :labels="['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']"
        />
        <p class="text-center text-sm text-duo-gray-300 mt-4">
            @if($stats['streak'] >= 7)
                You're on fire! Perfect week!
            @elseif($stats['streak'] > 0)
                Keep going! {{ 7 - $stats['streak'] }} more days for a perfect week!
            @else
                Start your streak today!
            @endif
        </p>
    </x-card>
</x-app-layout>
