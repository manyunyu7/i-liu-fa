<x-app-layout>
    <x-slot name="title">New Weekly Goal</x-slot>

    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('weekly-goals.index') }}" class="inline-flex items-center text-duo-blue hover:text-duo-blue-dark font-bold mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Goals
            </a>
            <h1 class="text-2xl font-extrabold text-duo-gray-500">Set a New Weekly Goal</h1>
            <p class="text-duo-gray-300 mt-1">Define what you want to achieve this week</p>
        </div>

        <!-- Form -->
        <x-card>
            <form action="{{ route('weekly-goals.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-bold text-duo-gray-500 mb-2">Goal Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           class="w-full px-4 py-3 rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-0 font-medium"
                           placeholder="e.g., Exercise 4 times this week"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-duo-red">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-duo-gray-500 mb-2">Description (Optional)</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-3 rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-0 font-medium"
                              placeholder="Add more details about your goal...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-duo-red">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-bold text-duo-gray-500 mb-2">Category</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($categories as $key => $category)
                            <label class="cursor-pointer">
                                <input type="radio" name="category" value="{{ $key }}"
                                       class="sr-only peer" {{ old('category', 'general') === $key ? 'checked' : '' }}>
                                <div class="p-3 rounded-duo border-2 border-duo-gray-100 peer-checked:border-duo-{{ $category['color'] }} peer-checked:bg-duo-{{ $category['color'] }}/5 hover:border-duo-gray-200 transition-colors text-center">
                                    <span class="text-2xl block mb-1">{{ $category['icon'] }}</span>
                                    <span class="text-xs font-bold text-duo-gray-400">{{ $category['label'] }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('category')
                        <p class="mt-1 text-sm text-duo-red">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target Count -->
                <div>
                    <label for="target_count" class="block text-sm font-bold text-duo-gray-500 mb-2">Target Count</label>
                    <p class="text-sm text-duo-gray-300 mb-3">How many times do you want to achieve this goal this week?</p>
                    <div class="flex items-center space-x-4" x-data="{ count: {{ old('target_count', 3) }} }">
                        <button type="button" @click="count = Math.max(1, count - 1)"
                                class="p-3 rounded-duo bg-duo-gray-100 hover:bg-duo-gray-200 text-duo-gray-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                        </button>

                        <input type="number" name="target_count" x-model="count" min="1" max="100"
                               class="w-24 px-4 py-3 rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-0 font-bold text-xl text-center">

                        <button type="button" @click="count = Math.min(100, count + 1)"
                                class="p-3 rounded-duo bg-duo-gray-100 hover:bg-duo-gray-200 text-duo-gray-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>

                        <span class="text-sm text-duo-gray-400">times this week</span>
                    </div>
                    @error('target_count')
                        <p class="mt-1 text-sm text-duo-red">{{ $message }}</p>
                    @enderror
                </div>

                <!-- XP Preview -->
                <div class="bg-duo-yellow/10 rounded-duo p-4">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">⭐</span>
                        <div>
                            <p class="text-sm text-duo-gray-400">XP Reward (based on target count)</p>
                            <p class="font-bold text-duo-yellow text-lg" x-data="{ count: {{ old('target_count', 3) }} }"
                               x-text="'+' + (30 + count * 10) + ' XP'">+60 XP</p>
                        </div>
                    </div>
                </div>

                <!-- Week -->
                <input type="hidden" name="week_start_date" value="{{ $weekStart->format('Y-m-d') }}">
                <div class="bg-duo-gray-50 rounded-duo p-4">
                    <p class="text-sm text-duo-gray-400">This goal will be added to:</p>
                    <p class="font-bold text-duo-gray-500">Week of {{ $weekStart->format('M j') }} - {{ $weekStart->copy()->endOfWeek()->format('M j, Y') }}</p>
                </div>

                <!-- Submit -->
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('weekly-goals.index') }}"
                       class="px-6 py-3 border-2 border-duo-gray-200 text-duo-gray-400 font-bold rounded-duo hover:bg-duo-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-duo-green hover:bg-duo-green-dark text-white font-bold rounded-duo transition-colors">
                        Create Goal
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
