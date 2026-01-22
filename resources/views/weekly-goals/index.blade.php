<x-app-layout>
    <x-slot name="title">Weekly Goals</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-duo-gray-500">Weekly Goals</h1>
                <p class="text-duo-gray-300 mt-1">Set and track your goals for the week</p>
            </div>
            <a href="{{ route('weekly-goals.create') }}"
               class="inline-flex items-center px-4 py-2 bg-duo-green hover:bg-duo-green-dark text-white font-bold rounded-duo transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Goal
            </a>
        </div>

        <!-- Week Navigation -->
        <div class="bg-white rounded-duo shadow-duo p-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('weekly-goals.index', ['week' => $weekOffset - 1]) }}"
                   class="p-2 rounded-duo hover:bg-duo-gray-100 text-duo-gray-400 hover:text-duo-gray-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>

                <div class="text-center">
                    <p class="text-lg font-bold text-duo-gray-500">
                        {{ $currentWeekStart->format('M j') }} - {{ $currentWeekEnd->format('M j, Y') }}
                    </p>
                    @if($weekOffset === 0)
                        <span class="text-xs text-duo-green font-bold">Current Week</span>
                    @elseif($weekOffset < 0)
                        <span class="text-xs text-duo-gray-300">{{ abs($weekOffset) }} week(s) ago</span>
                    @else
                        <span class="text-xs text-duo-blue font-bold">{{ $weekOffset }} week(s) ahead</span>
                    @endif
                </div>

                <a href="{{ route('weekly-goals.index', ['week' => $weekOffset + 1]) }}"
                   class="p-2 rounded-duo hover:bg-duo-gray-100 text-duo-gray-400 hover:text-duo-gray-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-4 gap-4">
            <x-stat-card icon="🎯" label="Total Goals" :value="$stats['total']" color="blue" />
            <x-stat-card icon="✅" label="Completed" :value="$stats['completed']" color="green" />
            <x-stat-card icon="⏳" label="In Progress" :value="$stats['pending']" color="yellow" />
            <x-stat-card icon="⭐" label="XP Earned" :value="$stats['xp_earned']" color="purple" />
        </div>

        <!-- Goals List -->
        @if($goals->isEmpty())
            <x-card class="text-center py-12">
                <span class="text-4xl mb-4 block">🎯</span>
                <h3 class="font-bold text-duo-gray-500 mb-2">No Goals This Week</h3>
                <p class="text-duo-gray-300 mb-4">Set some goals to stay on track!</p>
                <a href="{{ route('weekly-goals.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-duo-green hover:bg-duo-green-dark text-white font-bold rounded-duo transition-colors">
                    Create Your First Goal
                </a>
            </x-card>
        @else
            <div class="space-y-4">
                @foreach($goals as $goal)
                    @php
                        $categoryInfo = $categories[$goal->category] ?? $categories['general'];
                    @endphp
                    <x-card class="{{ $goal->is_completed ? 'bg-duo-green/5 border-duo-green' : '' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4 flex-1">
                                <!-- Category Icon -->
                                <div class="w-12 h-12 rounded-duo flex items-center justify-center text-2xl bg-duo-{{ $categoryInfo['color'] }}/10">
                                    {{ $categoryInfo['icon'] }}
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <h3 class="font-bold text-duo-gray-500 {{ $goal->is_completed ? 'line-through' : '' }}">
                                            {{ $goal->title }}
                                        </h3>
                                        @if($goal->is_completed)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-duo-green/10 text-duo-green">
                                                Completed
                                            </span>
                                        @endif
                                    </div>

                                    @if($goal->description)
                                        <p class="text-sm text-duo-gray-300 mb-2">{{ $goal->description }}</p>
                                    @endif

                                    <!-- Progress -->
                                    <div class="mb-3">
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-duo-gray-400">Progress</span>
                                            <span class="font-bold text-duo-gray-500">{{ $goal->current_count }}/{{ $goal->target_count }}</span>
                                        </div>
                                        <x-progress-bar :progress="$goal->progress_percentage" :color="$categoryInfo['color']" size="md" />
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <span class="text-xs text-duo-gray-200 bg-duo-{{ $categoryInfo['color'] }}/10 px-2 py-1 rounded">
                                                {{ $categoryInfo['label'] }}
                                            </span>
                                            <span class="text-sm font-bold text-duo-yellow">+{{ $goal->xp_reward }} XP</span>
                                        </div>

                                        @if(!$goal->is_completed)
                                            <div class="flex items-center space-x-2">
                                                <form action="{{ route('weekly-goals.decrement', $goal) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="p-2 rounded-duo bg-duo-gray-100 hover:bg-duo-gray-200 text-duo-gray-400 transition-colors"
                                                            {{ $goal->current_count <= 0 ? 'disabled' : '' }}>
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                        </svg>
                                                    </button>
                                                </form>

                                                <form action="{{ route('weekly-goals.increment', $goal) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="p-2 rounded-duo bg-duo-green hover:bg-duo-green-dark text-white transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Menu -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="p-2 rounded-duo hover:bg-duo-gray-100 text-duo-gray-300">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="5" r="2"/>
                                        <circle cx="12" cy="12" r="2"/>
                                        <circle cx="12" cy="19" r="2"/>
                                    </svg>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                     x-transition
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-duo shadow-duo-lg z-10">
                                    <a href="{{ route('weekly-goals.edit', $goal) }}"
                                       class="block px-4 py-2 text-sm text-duo-gray-500 hover:bg-duo-gray-50">
                                        Edit
                                    </a>
                                    <form action="{{ route('weekly-goals.destroy', $goal) }}" method="POST"
                                          onsubmit="return confirm('Delete this goal?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-duo-red hover:bg-duo-red/5">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </x-card>
                @endforeach
            </div>
        @endif

        <!-- Weekly Summary -->
        @if($goals->isNotEmpty())
            <x-card class="bg-gradient-to-r from-duo-blue/10 to-duo-purple/10">
                <div class="flex items-center space-x-4">
                    <span class="text-4xl">📊</span>
                    <div>
                        <h3 class="font-bold text-duo-gray-500">Weekly Summary</h3>
                        <p class="text-duo-gray-400">
                            @if($stats['completed'] === $stats['total'] && $stats['total'] > 0)
                                Amazing! You completed all your goals this week!
                            @elseif($stats['completed'] > 0)
                                Great progress! {{ round(($stats['completed'] / $stats['total']) * 100) }}% of your goals are complete.
                            @else
                                Keep going! You can complete {{ $stats['pending'] }} goal(s) this week.
                            @endif
                        </p>
                    </div>
                </div>
            </x-card>
        @endif
    </div>
</x-app-layout>
