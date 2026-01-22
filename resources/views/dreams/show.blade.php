<x-app-layout>
    <x-slot name="title">{{ $dream->title }}</x-slot>

    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('dreams.index') }}" class="inline-flex items-center text-duo-gray-300 hover:text-duo-gray-400 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Dreams
            </a>

            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-4xl">{{ $dream->category->icon }}</span>
                    <div>
                        <h1 class="text-3xl font-extrabold text-duo-gray-500">{{ $dream->title }}</h1>
                        <div class="flex items-center space-x-2 mt-1">
                            <x-badge :color="match($dream->status) { 'manifested' => 'green', 'manifesting' => 'purple', default => 'blue' }">
                                {{ ucfirst($dream->status) }}
                            </x-badge>
                            <span class="text-sm text-duo-gray-200">{{ $dream->category->name }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('dreams.edit', $dream) }}"
                       class="p-2 text-duo-gray-300 hover:text-duo-blue hover:bg-duo-blue/10 rounded-duo">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    <form action="{{ route('dreams.destroy', $dream) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this dream?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-duo-gray-300 hover:text-duo-red hover:bg-duo-red/10 rounded-duo">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if($dream->status === 'manifested')
            <div class="bg-gradient-to-r from-duo-green-light/20 to-duo-yellow/20 border-2 border-duo-green rounded-duo p-6 mb-6 text-center">
                <span class="text-6xl mb-2 block">🎉</span>
                <h2 class="text-2xl font-extrabold text-duo-green">Dream Manifested!</h2>
                <p class="text-duo-gray-400">Manifested on {{ $dream->manifestation_date->format('F d, Y') }}</p>
            </div>
        @elseif($dream->status !== 'manifested')
            <div class="mb-6">
                <form action="{{ route('dreams.manifest', $dream) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Are you ready to mark this dream as manifested?')"
                            class="w-full py-4 bg-gradient-to-r from-duo-purple to-duo-pink text-white rounded-duo font-bold text-lg hover:opacity-90 transition-opacity">
                        ✨ Mark as Manifested
                    </button>
                </form>
            </div>
        @endif

        <!-- Description -->
        @if($dream->description)
            <x-card class="mb-6">
                <h2 class="text-xl font-bold text-duo-gray-500 mb-3">Description</h2>
                <p class="text-duo-gray-400 whitespace-pre-line">{{ $dream->description }}</p>
            </x-card>
        @endif

        <!-- Affirmation -->
        @if($dream->affirmation)
            <x-card class="mb-6 bg-gradient-to-r from-duo-purple/10 to-duo-pink/10 border-duo-purple/30">
                <h2 class="text-xl font-bold text-duo-purple mb-3">Your Affirmation</h2>
                <p class="text-lg text-duo-gray-500 italic">"{{ $dream->affirmation }}"</p>
                <p class="text-sm text-duo-gray-200 mt-3">Speak this affirmation daily with conviction!</p>
            </x-card>
        @endif

        <!-- Reward Info -->
        <x-card class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-duo-gray-500">Manifestation Reward</h2>
                    <p class="text-sm text-duo-gray-200">Earn XP when this dream manifests</p>
                </div>
                <span class="text-2xl font-extrabold text-duo-yellow">+{{ $dream->xp_reward }} XP</span>
            </div>
        </x-card>

        <!-- Journal -->
        <x-card class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-duo-gray-500">Dream Journal</h2>
                <span class="text-sm font-bold text-duo-gray-200">{{ $dream->journalEntries->count() }} entries</span>
            </div>

            <!-- Add Entry Form -->
            <form action="{{ route('dreams.journal.store', $dream) }}" method="POST" class="mb-6" x-data="{ mood: '' }">
                @csrf
                <textarea name="content" rows="3" required
                          placeholder="Write about your progress, feelings, or signs you're receiving..."
                          class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium resize-none mb-3"></textarea>

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-duo-gray-300">Mood:</span>
                        @foreach(['😊' => 'happy', '🙏' => 'grateful', '✨' => 'hopeful', '💪' => 'determined', '😌' => 'peaceful'] as $emoji => $moodValue)
                            <button type="button" @click="mood = '{{ $moodValue }}'"
                                    class="text-2xl p-1 rounded-full transition-all"
                                    :class="mood === '{{ $moodValue }}' ? 'bg-duo-yellow/20 scale-110' : 'opacity-50 hover:opacity-100'">
                                {{ $emoji }}
                            </button>
                        @endforeach
                        <input type="hidden" name="mood" x-model="mood">
                    </div>
                    <x-primary-button type="submit">Add Entry (+5 XP)</x-primary-button>
                </div>
            </form>

            <!-- Journal Entries -->
            @if($dream->journalEntries->count() > 0)
                <div class="space-y-4 border-t border-duo-gray-100 pt-4">
                    @foreach($dream->journalEntries as $entry)
                        <div class="p-4 bg-duo-gray-50 rounded-duo">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    @if($entry->mood)
                                        <span class="text-xl">{{ match($entry->mood) { 'happy' => '😊', 'grateful' => '🙏', 'hopeful' => '✨', 'determined' => '💪', 'peaceful' => '😌', default => '' } }}</span>
                                    @endif
                                    <span class="text-sm text-duo-gray-200">{{ $entry->created_at->format('M d, Y \a\t g:i A') }}</span>
                                </div>
                                <form action="{{ route('dreams.journal.destroy', $entry) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-duo-gray-200 hover:text-duo-red text-sm">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            <p class="text-duo-gray-400 whitespace-pre-line">{{ $entry->content }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-duo-gray-200">
                    <p>No journal entries yet. Start documenting your manifestation journey!</p>
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>
