<x-app-layout>
    <x-slot name="title">Create Habit</x-slot>

    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('habits.index') }}" class="inline-flex items-center text-duo-gray-300 hover:text-duo-gray-400 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Habits
            </a>
            <h1 class="text-3xl font-extrabold text-duo-gray-500">Create New Habit</h1>
            <p class="text-duo-gray-300">Build a positive routine one day at a time</p>
        </div>

        <x-card>
            <form action="{{ route('habits.store') }}" method="POST" x-data="{ selectedIcon: '✓', selectedColor: '#58CC02' }">
                @csrf

                <!-- Name -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Habit Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="e.g., Drink 8 glasses of water"
                           class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                    @error('name')
                        <p class="mt-1 text-sm text-duo-red">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Description</label>
                    <textarea name="description" rows="2"
                              placeholder="Why is this habit important to you?"
                              class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium resize-none">{{ old('description') }}</textarea>
                </div>

                <!-- Icon -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Icon</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['✓', '💧', '🏃', '📚', '🧘', '💪', '🍎', '💤', '🎯', '✍️', '🧹', '💊', '🌅', '🙏'] as $icon)
                            <button type="button" @click="selectedIcon = '{{ $icon }}'"
                                    class="w-12 h-12 text-2xl rounded-duo border-2 transition-all"
                                    :class="selectedIcon === '{{ $icon }}' ? 'border-duo-green bg-duo-green-light/20' : 'border-duo-gray-100 hover:border-duo-gray-200'">
                                {{ $icon }}
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="icon" x-model="selectedIcon">
                </div>

                <!-- Color -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Color</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['#58CC02' => 'Green', '#1CB0F6' => 'Blue', '#CE82FF' => 'Purple', '#FF9600' => 'Orange', '#FF4B4B' => 'Red', '#FFC800' => 'Yellow', '#FF86D0' => 'Pink'] as $color => $name)
                            <button type="button" @click="selectedColor = '{{ $color }}'"
                                    class="w-10 h-10 rounded-full border-4 transition-all"
                                    style="background-color: {{ $color }}"
                                    :class="selectedColor === '{{ $color }}' ? 'border-duo-gray-500 scale-110' : 'border-transparent hover:scale-105'">
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" name="color" x-model="selectedColor">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <!-- Frequency -->
                    <div>
                        <label class="block text-sm font-bold text-duo-gray-400 mb-2">Frequency *</label>
                        <select name="frequency" required
                                class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                            <option value="daily" {{ old('frequency', 'daily') === 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('frequency') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        </select>
                    </div>

                    <!-- Target Count -->
                    <div>
                        <label class="block text-sm font-bold text-duo-gray-400 mb-2">Target Count *</label>
                        <input type="number" name="target_count" value="{{ old('target_count', 1) }}" required min="1" max="100"
                               class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                        <p class="text-xs text-duo-gray-200 mt-1">How many times to complete?</p>
                    </div>
                </div>

                <!-- Tips -->
                <div class="bg-duo-blue/10 rounded-duo p-4 mb-6">
                    <h3 class="font-bold text-duo-blue mb-2">Habit Building Tips</h3>
                    <ul class="text-sm text-duo-gray-400 space-y-1">
                        <li>• Start small - one habit at a time</li>
                        <li>• Stack habits with existing routines</li>
                        <li>• Never miss twice in a row</li>
                        <li>• Celebrate small wins</li>
                    </ul>
                </div>

                <!-- Submit -->
                <div class="flex justify-end space-x-4 pt-4 border-t border-duo-gray-100">
                    <a href="{{ route('habits.index') }}">
                        <x-secondary-button type="button">Cancel</x-secondary-button>
                    </a>
                    <x-primary-button type="submit">Create Habit</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
