<x-app-layout>
    <x-slot name="title">Daily Reflections</x-slot>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-duo-gray-500 mb-2">Daily Reflections</h1>
            <p class="text-duo-gray-300">Track your thoughts, gratitude, and growth</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <x-stat-card icon="📝" label="Total Reflections" :value="$stats['total_reflections']" color="purple" />
        <x-stat-card icon="📅" label="This Month" :value="$stats['this_month']" color="blue" />
        <x-stat-card icon="🔥" label="Current Streak" :value="$stats['streak'] . ' days'" color="orange" />
        <x-stat-card icon="😊" label="Avg Mood" :value="number_format($stats['avg_mood'], 1) . '/10'" color="green" />
    </div>

    <!-- Today's Reflections -->
    <x-card class="mb-8">
        <h2 class="text-xl font-bold text-duo-gray-500 mb-4">Today's Reflections</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Morning -->
            <a href="{{ $hasMorning ? route('reflections.index') : route('reflections.create', ['type' => 'morning']) }}"
               class="p-4 border-2 rounded-duo {{ $hasMorning ? 'border-duo-green bg-duo-green/10' : 'border-duo-gray-100 hover:border-duo-gray-200' }} transition-colors">
                <div class="flex items-center space-x-3">
                    <span class="text-3xl">🌅</span>
                    <div>
                        <p class="font-bold text-duo-gray-500">Morning</p>
                        <p class="text-sm {{ $hasMorning ? 'text-duo-green' : 'text-duo-gray-300' }}">
                            {{ $hasMorning ? 'Completed' : 'Set your intentions' }}
                        </p>
                    </div>
                    @if($hasMorning)
                        <span class="ml-auto text-duo-green text-xl">✓</span>
                    @endif
                </div>
            </a>

            <!-- Gratitude -->
            <a href="{{ $hasGratitude ? route('reflections.index') : route('reflections.create', ['type' => 'gratitude']) }}"
               class="p-4 border-2 rounded-duo {{ $hasGratitude ? 'border-duo-green bg-duo-green/10' : 'border-duo-gray-100 hover:border-duo-gray-200' }} transition-colors">
                <div class="flex items-center space-x-3">
                    <span class="text-3xl">🙏</span>
                    <div>
                        <p class="font-bold text-duo-gray-500">Gratitude</p>
                        <p class="text-sm {{ $hasGratitude ? 'text-duo-green' : 'text-duo-gray-300' }}">
                            {{ $hasGratitude ? 'Completed' : 'What are you grateful for?' }}
                        </p>
                    </div>
                    @if($hasGratitude)
                        <span class="ml-auto text-duo-green text-xl">✓</span>
                    @endif
                </div>
            </a>

            <!-- Evening -->
            <a href="{{ $hasEvening ? route('reflections.index') : route('reflections.create', ['type' => 'evening']) }}"
               class="p-4 border-2 rounded-duo {{ $hasEvening ? 'border-duo-green bg-duo-green/10' : 'border-duo-gray-100 hover:border-duo-gray-200' }} transition-colors">
                <div class="flex items-center space-x-3">
                    <span class="text-3xl">🌙</span>
                    <div>
                        <p class="font-bold text-duo-gray-500">Evening</p>
                        <p class="text-sm {{ $hasEvening ? 'text-duo-green' : 'text-duo-gray-300' }}">
                            {{ $hasEvening ? 'Completed' : 'Review your day' }}
                        </p>
                    </div>
                    @if($hasEvening)
                        <span class="ml-auto text-duo-green text-xl">✓</span>
                    @endif
                </div>
            </a>
        </div>

        <!-- Today's Progress -->
        <div class="mt-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-duo-gray-500">Today's Progress</span>
                <span class="text-sm text-duo-gray-300">{{ ($hasMorning + $hasGratitude + $hasEvening) }}/3</span>
            </div>
            <x-beads-progress :total="3" :completed="$hasMorning + $hasGratitude + $hasEvening" color="green" />
        </div>
    </x-card>

    <!-- Past Reflections Calendar View -->
    <x-card>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-duo-gray-500">{{ $date->format('F Y') }}</h2>
            <div class="flex space-x-2">
                <a href="{{ route('reflections.index', ['date' => $date->copy()->subMonth()->format('Y-m-d')]) }}"
                   class="px-3 py-1 border border-duo-gray-200 rounded-lg hover:bg-duo-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-duo-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <a href="{{ route('reflections.index', ['date' => $date->copy()->addMonth()->format('Y-m-d')]) }}"
                   class="px-3 py-1 border border-duo-gray-200 rounded-lg hover:bg-duo-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-duo-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Reflections List -->
        @if($reflections->isEmpty())
            <div class="text-center py-12">
                <span class="text-4xl mb-4 block">📝</span>
                <p class="text-duo-gray-300">No reflections this month yet.</p>
                <p class="text-duo-gray-200 text-sm mt-2">Start your reflection journey today!</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($reflections as $dateStr => $dayReflections)
                    <div class="border-l-4 border-duo-purple pl-4">
                        <p class="font-bold text-duo-gray-500 mb-2">
                            {{ \Carbon\Carbon::parse($dateStr)->format('l, F j') }}
                            @if(\Carbon\Carbon::parse($dateStr)->isToday())
                                <span class="text-duo-green text-sm font-normal">(Today)</span>
                            @endif
                        </p>
                        <div class="space-y-2">
                            @foreach($dayReflections as $reflection)
                                <a href="{{ route('reflections.show', $reflection) }}"
                                   class="block p-3 bg-duo-gray-50 rounded-lg hover:bg-duo-gray-100 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <span class="text-xl">
                                                {{ match($reflection->type) {
                                                    'morning' => '🌅',
                                                    'evening' => '🌙',
                                                    'gratitude' => '🙏',
                                                    default => '📝'
                                                } }}
                                            </span>
                                            <div>
                                                <p class="font-medium text-duo-gray-500">
                                                    {{ ucfirst($reflection->type) }} Reflection
                                                </p>
                                                @if($reflection->mood)
                                                    <p class="text-sm text-duo-gray-300">
                                                        Mood: {{ $moods[$reflection->mood] ?? $reflection->mood }}
                                                        @if($reflection->mood_score)
                                                            ({{ $reflection->mood_score }}/10)
                                                        @endif
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="text-duo-gray-200">
                                            +{{ $reflection->xp_earned }} XP
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card>
</x-app-layout>
