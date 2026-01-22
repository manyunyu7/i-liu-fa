<x-app-layout>
    <x-slot name="title">{{ ucfirst($type) }} Reflection</x-slot>

    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('reflections.index') }}" class="text-duo-gray-300 hover:text-duo-green transition-colors mb-4 inline-flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Back to Reflections</span>
        </a>
        <h1 class="text-3xl font-extrabold text-duo-gray-500 mt-4">
            {{ match($type) {
                'morning' => '🌅 Morning Reflection',
                'evening' => '🌙 Evening Reflection',
                'gratitude' => '🙏 Gratitude Journal',
                default => '📝 Daily Reflection'
            } }}
        </h1>
        <p class="text-duo-gray-300">{{ $date->format('l, F j, Y') }}</p>
    </div>

    <form action="{{ route('reflections.store') }}" method="POST">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="reflection_date" value="{{ $date->format('Y-m-d') }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Mood Selection -->
                <x-card>
                    <h2 class="text-xl font-bold text-duo-gray-500 mb-4">How are you feeling?</h2>
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                        @foreach($moods as $key => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="mood" value="{{ $key }}" class="sr-only peer">
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
                               value="7"
                               class="w-full accent-duo-green"
                               oninput="document.getElementById('mood-value').textContent = this.value">
                        <div class="flex justify-between text-sm text-duo-gray-300 mt-1">
                            <span>Low</span>
                            <span id="mood-value" class="font-bold text-duo-green">7</span>
                            <span>High</span>
                        </div>
                    </div>
                </x-card>

                @if($type === 'gratitude')
                    <!-- Gratitude Items -->
                    <x-card x-data="{ items: ['', '', ''] }">
                        <h2 class="text-xl font-bold text-duo-gray-500 mb-4">What are you grateful for today?</h2>
                        <p class="text-duo-gray-300 text-sm mb-4">List at least 3 things you're grateful for.</p>

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

                @if($type === 'morning')
                    <!-- Morning Intentions -->
                    <x-card>
                        <h2 class="text-xl font-bold text-duo-gray-500 mb-4">Set Your Intentions</h2>
                        <label class="block text-sm font-bold text-duo-gray-500 mb-2">
                            What do you want to accomplish today?
                        </label>
                        <textarea name="intentions"
                                  rows="4"
                                  placeholder="Today I will focus on..."
                                  class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0 transition-colors">{{ old('intentions') }}</textarea>
                    </x-card>
                @endif

                @if($type === 'evening')
                    <!-- Evening Review -->
                    <x-card>
                        <h2 class="text-xl font-bold text-duo-gray-500 mb-4">Review Your Day</h2>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-duo-gray-500 mb-2">
                                    What went well today? (Highlights)
                                </label>
                                <textarea name="highlights"
                                          rows="3"
                                          placeholder="The best parts of my day were..."
                                          class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0 transition-colors">{{ old('highlights') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-duo-gray-500 mb-2">
                                    What was challenging?
                                </label>
                                <textarea name="challenges"
                                          rows="3"
                                          placeholder="I faced some challenges with..."
                                          class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0 transition-colors">{{ old('challenges') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-duo-gray-500 mb-2">
                                    What did you learn?
                                </label>
                                <textarea name="lessons"
                                          rows="3"
                                          placeholder="Today I learned that..."
                                          class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0 transition-colors">{{ old('lessons') }}</textarea>
                            </div>
                        </div>
                    </x-card>
                @endif

                <!-- Additional Notes -->
                <x-card>
                    <h2 class="text-xl font-bold text-duo-gray-500 mb-4">Additional Notes</h2>
                    <textarea name="notes"
                              rows="4"
                              placeholder="Any other thoughts you want to capture..."
                              class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0 transition-colors">{{ old('notes') }}</textarea>
                </x-card>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- XP Reward -->
                <x-card class="bg-gradient-to-br from-duo-yellow/10 to-duo-orange/10 border-duo-yellow/30">
                    <div class="flex items-center space-x-3">
                        <span class="text-3xl">⚡</span>
                        <div>
                            <p class="font-bold text-duo-gray-500">XP Reward</p>
                            <p class="text-2xl font-extrabold text-duo-yellow">
                                +{{ match($type) { 'morning' => 15, 'evening' => 20, 'gratitude' => 10, default => 10 } }} XP
                            </p>
                        </div>
                    </div>
                </x-card>

                <!-- Tips -->
                <x-card>
                    <h3 class="font-bold text-duo-gray-500 mb-3">
                        {{ match($type) {
                            'morning' => '🌅 Morning Tips',
                            'evening' => '🌙 Evening Tips',
                            'gratitude' => '🙏 Gratitude Tips',
                            default => '📝 Reflection Tips'
                        } }}
                    </h3>
                    <ul class="text-sm text-duo-gray-400 space-y-2">
                        @if($type === 'morning')
                            <li>• Start with how you're feeling right now</li>
                            <li>• Set clear, achievable intentions</li>
                            <li>• Visualize your ideal day</li>
                            <li>• Focus on 1-3 main priorities</li>
                        @elseif($type === 'evening')
                            <li>• Celebrate your wins, big and small</li>
                            <li>• Be honest but kind about challenges</li>
                            <li>• Look for lessons in difficulties</li>
                            <li>• End with something positive</li>
                        @else
                            <li>• Be specific about what you're grateful for</li>
                            <li>• Include both big and small things</li>
                            <li>• Think about people, experiences, and things</li>
                            <li>• Feel the gratitude as you write</li>
                        @endif
                    </ul>
                </x-card>

                <!-- Submit -->
                <x-button type="submit" color="green" size="lg" class="w-full">
                    Save Reflection
                </x-button>
            </div>
        </div>
    </form>
</x-app-layout>
