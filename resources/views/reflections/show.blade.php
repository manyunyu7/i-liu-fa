<x-app-layout>
    <x-slot name="title">{{ ucfirst($reflection->type) }} Reflection</x-slot>

    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('reflections.index') }}" class="text-duo-gray-300 hover:text-duo-green transition-colors mb-4 inline-flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Back to Reflections</span>
        </a>
        <div class="flex items-center justify-between mt-4">
            <div>
                <h1 class="text-3xl font-extrabold text-duo-gray-500">
                    {{ match($reflection->type) {
                        'morning' => '🌅 Morning Reflection',
                        'evening' => '🌙 Evening Reflection',
                        'gratitude' => '🙏 Gratitude Journal',
                        default => '📝 Daily Reflection'
                    } }}
                </h1>
                <p class="text-duo-gray-300">{{ $reflection->reflection_date->format('l, F j, Y') }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('reflections.edit', $reflection) }}">
                    <x-button color="gray" size="sm">Edit</x-button>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Mood -->
            @if($reflection->mood)
                <x-card>
                    <h2 class="text-lg font-bold text-duo-gray-500 mb-4">Mood</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-5xl">{{ App\Models\Reflection::getMoodEmoji($reflection->mood) }}</span>
                        <div>
                            <p class="text-xl font-bold text-duo-gray-500">{{ ucfirst($reflection->mood) }}</p>
                            @if($reflection->mood_score)
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="text-duo-gray-300">Score:</span>
                                    <div class="flex items-center">
                                        <span class="font-bold text-duo-green">{{ $reflection->mood_score }}</span>
                                        <span class="text-duo-gray-200">/10</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </x-card>
            @endif

            <!-- Gratitude Items -->
            @if($reflection->gratitude_items && count($reflection->gratitude_items) > 0)
                <x-card>
                    <h2 class="text-lg font-bold text-duo-gray-500 mb-4">Gratitude List</h2>
                    <ul class="space-y-3">
                        @foreach($reflection->gratitude_items as $index => $item)
                            <li class="flex items-start space-x-3">
                                <span class="text-duo-green font-bold">{{ $index + 1 }}.</span>
                                <span class="text-duo-gray-500">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </x-card>
            @endif

            <!-- Intentions -->
            @if($reflection->intentions)
                <x-card>
                    <h2 class="text-lg font-bold text-duo-gray-500 mb-4">Intentions</h2>
                    <p class="text-duo-gray-500 whitespace-pre-wrap">{{ $reflection->intentions }}</p>
                </x-card>
            @endif

            <!-- Highlights -->
            @if($reflection->highlights)
                <x-card>
                    <h2 class="text-lg font-bold text-duo-gray-500 mb-4">Highlights</h2>
                    <p class="text-duo-gray-500 whitespace-pre-wrap">{{ $reflection->highlights }}</p>
                </x-card>
            @endif

            <!-- Challenges -->
            @if($reflection->challenges)
                <x-card>
                    <h2 class="text-lg font-bold text-duo-gray-500 mb-4">Challenges</h2>
                    <p class="text-duo-gray-500 whitespace-pre-wrap">{{ $reflection->challenges }}</p>
                </x-card>
            @endif

            <!-- Lessons -->
            @if($reflection->lessons)
                <x-card>
                    <h2 class="text-lg font-bold text-duo-gray-500 mb-4">Lessons Learned</h2>
                    <p class="text-duo-gray-500 whitespace-pre-wrap">{{ $reflection->lessons }}</p>
                </x-card>
            @endif

            <!-- Notes -->
            @if($reflection->notes)
                <x-card>
                    <h2 class="text-lg font-bold text-duo-gray-500 mb-4">Additional Notes</h2>
                    <p class="text-duo-gray-500 whitespace-pre-wrap">{{ $reflection->notes }}</p>
                </x-card>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Meta Info -->
            <x-card>
                <h3 class="font-bold text-duo-gray-500 mb-4">Details</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-duo-gray-300">Type</dt>
                        <dd class="font-medium text-duo-gray-500">{{ ucfirst($reflection->type) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-duo-gray-300">Date</dt>
                        <dd class="font-medium text-duo-gray-500">{{ $reflection->reflection_date->format('M j, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-duo-gray-300">XP Earned</dt>
                        <dd class="font-medium text-duo-yellow">+{{ $reflection->xp_earned }} XP</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-duo-gray-300">Created</dt>
                        <dd class="font-medium text-duo-gray-500">{{ $reflection->created_at->diffForHumans() }}</dd>
                    </div>
                </dl>
            </x-card>

            <!-- Danger Zone -->
            <x-card class="border-red-200">
                <h3 class="font-bold text-red-600 mb-4">Delete Reflection</h3>
                <p class="text-sm text-duo-gray-400 mb-4">This action cannot be undone.</p>
                <form action="{{ route('reflections.destroy', $reflection) }}"
                      method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this reflection?')">
                    @csrf
                    @method('DELETE')
                    <x-button type="submit" color="red" size="sm">
                        Delete
                    </x-button>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
