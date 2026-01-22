<x-app-layout>
    <x-slot name="title">Edit Task</x-slot>

    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('planner.index', ['date' => $task->task_date->format('Y-m-d')]) }}" class="inline-flex items-center text-duo-gray-300 hover:text-duo-gray-400 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Planner
            </a>
            <h1 class="text-3xl font-extrabold text-duo-gray-500">Edit Task</h1>
        </div>

        <x-card>
            <form action="{{ route('planner.update', $task) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Task Title *</label>
                    <input type="text" name="title" value="{{ old('title', $task->title) }}" required
                           class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Description</label>
                    <textarea name="description" rows="2"
                              class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium resize-none">{{ old('description', $task->description) }}</textarea>
                </div>

                <!-- Date -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Date *</label>
                    <input type="date" name="task_date" value="{{ old('task_date', $task->task_date->format('Y-m-d')) }}" required
                           class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                </div>

                <!-- Task Type -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Task Type *</label>
                    <select name="task_type" required
                            class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                        <option value="intention" {{ old('task_type', $task->task_type) === 'intention' ? 'selected' : '' }}>Intention</option>
                        <option value="goal" {{ old('task_type', $task->task_type) === 'goal' ? 'selected' : '' }}>Goal</option>
                        <option value="habit" {{ old('task_type', $task->task_type) === 'habit' ? 'selected' : '' }}>Habit</option>
                        <option value="task" {{ old('task_type', $task->task_type) === 'task' ? 'selected' : '' }}>Task</option>
                    </select>
                </div>

                <!-- Priority -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Priority *</label>
                    <select name="priority" required
                            class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                        <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <!-- Submit -->
                <div class="flex justify-end space-x-4 pt-4 border-t border-duo-gray-100">
                    <a href="{{ route('planner.index', ['date' => $task->task_date->format('Y-m-d')]) }}">
                        <x-secondary-button type="button">Cancel</x-secondary-button>
                    </a>
                    <x-primary-button type="submit">Save Changes</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
