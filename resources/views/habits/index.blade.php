<x-app-layout>
    <x-slot name="title">Habits</x-slot>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-duo-gray-500 mb-2">Habit Tracker</h1>
            <p class="text-duo-gray-300">Build positive habits one day at a time</p>
        </div>
        <a href="{{ route('habits.create') }}">
            <x-primary-button>
                + New Habit
            </x-primary-button>
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-8">
        <x-stat-card icon="🔄" label="Total Habits" :value="$stats['total']" color="blue" />
        <x-stat-card icon="⚡" label="Active" :value="$stats['active']" color="green" />
        <x-stat-card icon="✅" label="Completed Today" :value="$stats['completed_today']" color="orange" />
    </div>

    <!-- Habits List -->
    @if($habits->count() > 0)
        <div class="space-y-4">
            @foreach($habits as $habit)
                @php
                    $todayLog = $habit->logs->where('log_date', today()->toDateString())->first();
                    $todayCount = $todayLog?->count ?? 0;
                    $progress = ($todayCount / $habit->target_count) * 100;
                    $isComplete = $todayCount >= $habit->target_count;
                @endphp

                <x-card class="{{ $isComplete ? 'bg-duo-green-light/10 border-duo-green' : '' }} {{ !$habit->is_active ? 'opacity-50' : '' }}">
                    <div class="flex items-center space-x-4">
                        <!-- Icon -->
                        <div class="w-14 h-14 rounded-duo flex items-center justify-center text-3xl flex-shrink-0"
                             style="background-color: {{ $habit->color }}20">
                            {{ $habit->icon }}
                        </div>

                        <!-- Info -->
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <h3 class="font-bold text-duo-gray-500 text-lg">{{ $habit->name }}</h3>
                                @if(!$habit->is_active)
                                    <x-badge size="sm" color="gray">Inactive</x-badge>
                                @endif
                                @if($habit->streak > 0)
                                    <span class="flex items-center text-duo-orange font-bold text-sm">
                                        🔥 {{ $habit->streak }} day streak
                                    </span>
                                @endif
                            </div>

                            @if($habit->description)
                                <p class="text-sm text-duo-gray-200 mb-2">{{ $habit->description }}</p>
                            @endif

                            <!-- Progress Bar -->
                            <div class="flex items-center space-x-3">
                                <div class="flex-1">
                                    <x-progress-bar :progress="$progress" color="green" size="md" />
                                </div>
                                <span class="text-sm font-bold text-duo-gray-300 whitespace-nowrap">
                                    {{ $todayCount }}/{{ $habit->target_count }}
                                </span>
                            </div>

                            <div class="flex items-center space-x-2 mt-2 text-xs text-duo-gray-200">
                                <x-badge size="sm" color="gray">{{ ucfirst($habit->frequency) }}</x-badge>
                                <span>+{{ $habit->xp_per_completion }} XP per completion</span>
                            </div>
                        </div>

                        <!-- Action -->
                        <div class="flex flex-col items-center space-y-2 flex-shrink-0">
                            @if($habit->is_active)
                                <form action="{{ route('habits.log', $habit) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="w-14 h-14 rounded-full font-bold text-lg transition-all {{ $isComplete ? 'bg-duo-green text-white cursor-default' : 'bg-duo-gray-100 text-duo-gray-400 hover:bg-duo-green hover:text-white' }}"
                                            {{ $isComplete ? 'disabled' : '' }}>
                                        {{ $isComplete ? '✓' : '+1' }}
                                    </button>
                                </form>
                            @endif

                            <div class="flex space-x-1">
                                <a href="{{ route('habits.edit', $habit) }}" class="p-1 text-duo-gray-200 hover:text-duo-blue">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('habits.destroy', $habit) }}" method="POST" onsubmit="return confirm('Delete this habit?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-duo-gray-200 hover:text-duo-red">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Week View -->
                    <div class="mt-4 pt-4 border-t border-duo-gray-100">
                        <p class="text-xs text-duo-gray-200 mb-2">Last 7 days</p>
                        <div class="flex space-x-1">
                            @for($i = 6; $i >= 0; $i--)
                                @php
                                    $date = today()->subDays($i);
                                    $dayLog = $habit->logs->where('log_date', $date->toDateString())->first();
                                    $dayComplete = $dayLog && $dayLog->count >= $habit->target_count;
                                @endphp
                                <div class="flex-1 text-center">
                                    <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center text-xs font-bold {{ $dayComplete ? 'bg-duo-green text-white' : 'bg-duo-gray-100 text-duo-gray-300' }}">
                                        {{ $dayComplete ? '✓' : $date->format('j') }}
                                    </div>
                                    <span class="text-xs text-duo-gray-200 block mt-1">{{ $date->format('D') }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>
    @else
        <x-card class="text-center py-12">
            <span class="text-6xl mb-4 block">🔄</span>
            <h3 class="text-xl font-bold text-duo-gray-500 mb-2">No habits yet</h3>
            <p class="text-duo-gray-300 mb-6">Start building positive habits!</p>
            <a href="{{ route('habits.create') }}">
                <x-primary-button>Create Your First Habit</x-primary-button>
            </a>
        </x-card>
    @endif
</x-app-layout>
