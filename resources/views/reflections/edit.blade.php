<x-app-layout>
    <x-slot name="title">Edit {{ ucfirst($reflection->type) }} Reflection</x-slot>

    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('reflections.show', $reflection) }}" class="text-duo-gray-300 hover:text-duo-green transition-colors mb-4 inline-flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Back to Reflection</span>
        </a>
        <h1 class="text-3xl font-extrabold text-duo-gray-500 mt-4">
            Edit {{ ucfirst($reflection->type) }} Reflection
        </h1>
        <p class="text-duo-gray-300">{{ $reflection->reflection_date->format('l, F j, Y') }}</p>
    </div>

    <form action="{{ route('reflections.update', $reflection) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Mood Selection -->
                <x-card>
                    <h2 class="text-xl font-bold text-duo-gray-500 mb-4">How are you feeling?</h2>
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                        @foreach($moods as $key => $label)
                            <label class="cursor-pointer">
                                <input type="radio"
                                       name="mood"
                                       value="{{ $key }}"
                                       {{ old('mood', $reflection->mood) === $key ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="p-3 border-2 border-duo-gray-100 rounded-duo text-center transition-all peer-checked:border-duo-green peer-checked:bg-duo-green/10 hover:border-duo-gray-200">
                                    <span class="text-2xl block mb-1">{{ explode(' ', $label)[0] }}</span>
                                    <span class="text-xs text-duo-gray-400">{{ explode(' ', $label)[1] ?? '' }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <!-- Mood Score -->
                    <div class="mt-6">
                        <label class="block text-sm font-bold text-duo-gray-500 mb-2">Mood Score (1-10)</label>
                        <input type="range"
                               name="mood_score"
                               min="1"
                               max="10"
                               value="{{ old('mood_score', $reflection->mood_score ?? 7) }}"
                               class="w-full accent-duo-green"
                               oninput="document.getElementById('mood-value').textContent = this.value">
                        <div class="flex justify-between text-sm text-duo-gray-300 mt-1">
                            <span>Low</span>
                            <span id="mood-value" class="font-bold text-duo-green">{{ old('mood_score', $reflection->mood_score ?? 7) }}</span>
                            <span>High</span>
                        </div>
                    </div>
                </x-card>

                @if($reflection->type === 'gratitude')
                    <!-- Gratitude Items -->
                    @php
                        $gratitudeItems = old('gratitude_items', $reflection->gratitude_items ?? ['', '', '']);
                    @endphp
                    <x-card x-data="{ items: @js($gratitudeItems) }">
                        <h2 class="text-xl font-bold text-duo-gray-500 mb-4">What are you grateful for today?</h2>

                        <div class="space-y-3">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex items-center space-x-3">
                                    <span class="text-duo-gray-300 font-bold" x-text="index + 1 + '.'"></span>
                                    <input type="text"
                                           x-model="items[index]"
                                           :name="'gratitude_items[' + index + ']'"
                                           placeholder="I'm grateful for..."
                                           class="flex-1 px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0 transition-colors">
                                </div>
                            </template>
                        </div>

                        <button type="button"
                                @click="items.push('')"
                                class="mt-4 text-duo-green font-bold text-sm hover:underline">
                            + Add another item
                        </button>
                    </x-card>
                @endif

                @if($reflection->type === 'morning')
                    <!-- Intentions -->
                    <x-card>
                        <h2 class="text-xl font-bold text-duo-gray-500 mb-4">Intentions</h2>
                        <textarea name="intentions"
                                  rows="4"
                                  class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0 transition-colors">{{ old('intentions', $reflection->intentions) }}</textarea>
                    </x-card>
                @endif

                @if($reflection->type === 'evening')
                    <!-- Evening Review -->
                    <x-card>
                        <h2 class="text-xl font-bold text-duo-gray-500 mb-4">Review</h2>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-duo-gray-500 mb-2">Highlights</label>
                                <textarea name="highlights"
                                          rows="3"
                                          class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0 transition-colors">{{ old('highlights', $reflection->highlights) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-duo-gray-500 mb-2">Challenges</label>
                                <textarea name="challenges"
                                          rows="3"
                                          class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0 transition-colors">{{ old('challenges', $reflection->challenges) }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-duo-gray-500 mb-2">Lessons Learned</label>
                                <textarea name="lessons"
                                          rows="3"
                                          class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0 transition-colors">{{ old('lessons', $reflection->lessons) }}</textarea>
                            </div>
                        </div>
                    </x-card>
                @endif

                <!-- Notes -->
                <x-card>
                    <h2 class="text-xl font-bold text-duo-gray-500 mb-4">Additional Notes</h2>
                    <textarea name="notes"
                              rows="4"
                              class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0 transition-colors">{{ old('notes', $reflection->notes) }}</textarea>
                </x-card>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="space-y-3">
                    <x-button type="submit" color="green" size="lg" class="w-full">
                        Save Changes
                    </x-button>
                    <a href="{{ route('reflections.show', $reflection) }}" class="block">
                        <x-button type="button" color="gray" size="lg" class="w-full">
                            Cancel
                        </x-button>
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
