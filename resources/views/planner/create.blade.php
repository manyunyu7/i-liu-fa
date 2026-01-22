<x-app-layout>
    <x-slot name="title">Add Task</x-slot>

    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('planner.index', ['date' => $date->format('Y-m-d')]) }}" class="inline-flex items-center text-duo-gray-300 hover:text-duo-gray-400 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Planner
            </a>
            <h1 class="text-3xl font-extrabold text-duo-gray-500">Add New Task</h1>
            <p class="text-duo-gray-300">Plan your day with intention</p>
        </div>

        <x-card>
            <form action="{{ route('planner.store') }}" method="POST">
                @csrf

                <!-- Title -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Task Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="e.g., Morning meditation"
                           class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                    @error('title')
                        <p class="mt-1 text-sm text-duo-red">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Description</label>
                    <textarea name="description" rows="2"
                              placeholder="Add more details..."
                              class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium resize-none">{{ old('description') }}</textarea>
                </div>

                <!-- Date -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Date *</label>
                    <input type="date" name="task_date" value="{{ old('task_date', $date->format('Y-m-d')) }}" required
                           class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                </div>

                <!-- Task Type -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Task Type *</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(['intention' => ['icon' => '💜', 'label' => 'Intention', 'color' => 'purple'], 'goal' => ['icon' => '🎯', 'label' => 'Goal', 'color' => 'blue'], 'habit' => ['icon' => '🔄', 'label' => 'Habit', 'color' => 'green'], 'task' => ['icon' => '✓', 'label' => 'Task', 'color' => 'gray']] as $type => $info)
                            <label class="cursor-pointer">
                                <input type="radio" name="task_type" value="{{ $type }}" class="sr-only peer" {{ old('task_type', 'task') === $type ? 'checked' : '' }}>
                                <div class="p-3 rounded-duo border-2 border-duo-gray-100 text-center transition-all peer-checked:border-duo-{{ $info['color'] }} peer-checked:bg-duo-{{ $info['color'] }}/10">
                                    <span class="text-2xl block mb-1">{{ $info['icon'] }}</span>
                                    <span class="text-sm font-bold text-duo-gray-400">{{ $info['label'] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Priority -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Priority *</label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['low' => ['label' => 'Low', 'color' => 'gray'], 'medium' => ['label' => 'Medium', 'color' => 'yellow'], 'high' => ['label' => 'High', 'color' => 'red']] as $priority => $info)
                            <label class="cursor-pointer">
                                <input type="radio" name="priority" value="{{ $priority }}" class="sr-only peer" {{ old('priority', 'medium') === $priority ? 'checked' : '' }}>
                                <div class="p-3 rounded-duo border-2 border-duo-gray-100 text-center transition-all peer-checked:border-duo-{{ $info['color'] }} peer-checked:bg-duo-{{ $info['color'] }}/10">
                                    <span class="font-bold text-duo-gray-400">{{ $info['label'] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- XP Info -->
                <div class="bg-duo-yellow/10 rounded-duo p-4 mb-6">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">⚡</span>
                        <div>
                            <p class="font-bold text-duo-gray-500">Earn XP by completing tasks!</p>
                            <p class="text-sm text-duo-gray-300">Intentions: +20 XP | Goals: +25 XP | Habits: +10 XP | Tasks: +15 XP</p>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-end space-x-4 pt-4 border-t border-duo-gray-100">
                    <a href="{{ route('planner.index', ['date' => $date->format('Y-m-d')]) }}">
                        <x-secondary-button type="button">Cancel</x-secondary-button>
                    </a>
                    <x-primary-button type="submit">Add Task</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
