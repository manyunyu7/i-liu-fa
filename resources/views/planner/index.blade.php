<x-app-layout>
    <x-slot name="title">Planner</x-slot>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-duo-gray-500 mb-2">Daily Planner</h1>
            <p class="text-duo-gray-300">Plan your day with intention</p>
        </div>
        <a href="{{ route('planner.create', ['date' => $date->format('Y-m-d')]) }}">
            <x-primary-button>
                + Add Task
            </x-primary-button>
        </a>
    </div>

    <!-- Today Stats -->
    <div class="grid grid-cols-3 gap-4 mb-8">
        <x-stat-card icon="📋" label="Today's Tasks" :value="$stats['total_today']" color="blue" />
        <x-stat-card icon="✅" label="Completed" :value="$stats['completed_today']" color="green" />
        <x-stat-card icon="⏳" label="Pending" :value="$stats['pending_today']" color="orange" />
    </div>

    <!-- Date Navigation -->
    <x-card class="mb-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('planner.index', ['date' => $date->copy()->subDay()->format('Y-m-d'), 'view' => $view]) }}"
               class="p-2 text-duo-gray-300 hover:text-duo-gray-500 hover:bg-duo-gray-50 rounded-duo">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>

            <div class="text-center">
                <h2 class="text-xl font-bold text-duo-gray-500">
                    @if($view === 'day')
                        {{ $date->format('l, F d, Y') }}
                    @elseif($view === 'week')
                        {{ $startDate->format('M d') }} - {{ $endDate->format('M d, Y') }}
                    @else
                        {{ $date->format('F Y') }}
                    @endif
                </h2>
                @if($date->isToday())
                    <span class="text-sm text-duo-green font-bold">Today</span>
                @endif
            </div>

            <a href="{{ route('planner.index', ['date' => $date->copy()->addDay()->format('Y-m-d'), 'view' => $view]) }}"
               class="p-2 text-duo-gray-300 hover:text-duo-gray-500 hover:bg-duo-gray-50 rounded-duo">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        <!-- View Switcher -->
        <div class="flex justify-center space-x-2 mt-4">
            @foreach(['day' => 'Day', 'week' => 'Week', 'month' => 'Month'] as $v => $label)
                <a href="{{ route('planner.index', ['date' => $date->format('Y-m-d'), 'view' => $v]) }}"
                   class="px-4 py-2 rounded-duo font-bold text-sm transition-colors {{ $view === $v ? 'bg-duo-green text-white' : 'text-duo-gray-300 hover:bg-duo-gray-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <!-- Quick Jump to Today -->
        @if(!$date->isToday())
            <div class="text-center mt-4">
                <a href="{{ route('planner.index') }}" class="text-duo-blue font-bold text-sm hover:underline">
                    Jump to Today
                </a>
            </div>
        @endif
    </x-card>

    <!-- Tasks -->
    @if($view === 'day')
        @php $dayTasks = $tasks->get($date->format('Y-m-d'), collect()); @endphp
        @if($dayTasks->count() > 0)
            <div class="space-y-3">
                @foreach($dayTasks as $task)
                    <x-card class="{{ $task->is_completed ? 'bg-duo-green-light/10 border-duo-green' : '' }}">
                        <div class="flex items-center space-x-4">
                            <form action="{{ route('planner.toggle', $task) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-8 h-8 rounded-full border-2 flex items-center justify-center transition-colors flex-shrink-0 {{ $task->is_completed ? 'bg-duo-green border-duo-green text-white' : 'border-duo-gray-200 hover:border-duo-green' }}">
                                    @if($task->is_completed)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @endif
                                </button>
                            </form>

                            <div class="flex-1">
                                <p class="font-bold text-duo-gray-500 {{ $task->is_completed ? 'line-through text-duo-gray-200' : '' }}">
                                    {{ $task->title }}
                                </p>
                                @if($task->description)
                                    <p class="text-sm text-duo-gray-200 mt-1">{{ Str::limit($task->description, 100) }}</p>
                                @endif
                                <div class="flex items-center space-x-2 mt-2">
                                    <x-badge size="sm" :color="match($task->task_type) { 'intention' => 'purple', 'goal' => 'blue', 'habit' => 'green', default => 'gray' }">
                                        {{ ucfirst($task->task_type) }}
                                    </x-badge>
                                    <x-badge size="sm" :color="match($task->priority) { 'high' => 'red', 'medium' => 'yellow', 'low' => 'gray' }">
                                        {{ ucfirst($task->priority) }}
                                    </x-badge>
                                </div>
                            </div>

                            <div class="text-right flex-shrink-0">
                                <span class="text-sm font-bold text-duo-yellow">+{{ $task->xp_reward }} XP</span>
                                <div class="flex space-x-1 mt-2">
                                    <a href="{{ route('planner.edit', $task) }}" class="p-1 text-duo-gray-200 hover:text-duo-blue">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('planner.destroy', $task) }}" method="POST">
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
                    </x-card>
                @endforeach
            </div>
        @else
            <x-card class="text-center py-12">
                <span class="text-6xl mb-4 block">📅</span>
                <h3 class="text-xl font-bold text-duo-gray-500 mb-2">No tasks for this day</h3>
                <p class="text-duo-gray-300 mb-6">Start planning your day!</p>
                <a href="{{ route('planner.create', ['date' => $date->format('Y-m-d')]) }}">
                    <x-primary-button>Add Your First Task</x-primary-button>
                </a>
            </x-card>
        @endif
    @else
        <!-- Week/Month View -->
        <div class="space-y-6">
            @foreach($tasks as $dateKey => $dayTasks)
                @php $taskDate = \Carbon\Carbon::parse($dateKey); @endphp
                <div>
                    <h3 class="font-bold text-duo-gray-400 mb-3 flex items-center space-x-2">
                        <span>{{ $taskDate->format('l, M d') }}</span>
                        @if($taskDate->isToday())
                            <x-badge size="sm" color="green">Today</x-badge>
                        @endif
                    </h3>
                    <div class="space-y-2">
                        @foreach($dayTasks as $task)
                            <div class="flex items-center space-x-3 p-3 bg-white border-2 border-duo-gray-100 rounded-duo {{ $task->is_completed ? 'bg-duo-green-light/10' : '' }}">
                                <form action="{{ route('planner.toggle', $task) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-6 h-6 rounded-full border-2 flex items-center justify-center {{ $task->is_completed ? 'bg-duo-green border-duo-green text-white' : 'border-duo-gray-200' }}">
                                        @if($task->is_completed)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @endif
                                    </button>
                                </form>
                                <span class="flex-1 font-medium {{ $task->is_completed ? 'line-through text-duo-gray-200' : 'text-duo-gray-500' }}">
                                    {{ $task->title }}
                                </span>
                                <x-badge size="sm" :color="match($task->task_type) { 'intention' => 'purple', 'goal' => 'blue', 'habit' => 'green', default => 'gray' }">
                                    {{ ucfirst($task->task_type) }}
                                </x-badge>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            @if($tasks->isEmpty())
                <x-card class="text-center py-12">
                    <span class="text-6xl mb-4 block">📅</span>
                    <h3 class="text-xl font-bold text-duo-gray-500 mb-2">No tasks for this period</h3>
                    <p class="text-duo-gray-300">Add some tasks to get started!</p>
                </x-card>
            @endif
        </div>
    @endif
</x-app-layout>
