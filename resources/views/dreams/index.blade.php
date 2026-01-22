<x-app-layout>
    <x-slot name="title">Dreams</x-slot>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-duo-gray-500 mb-2">Dreams & Manifestations</h1>
            <p class="text-duo-gray-300">Visualize your ideal life and manifest it into reality</p>
        </div>
        <a href="{{ route('dreams.create') }}">
            <x-primary-button>
                + New Dream
            </x-primary-button>
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-4 gap-4 mb-8">
        <x-stat-card icon="🌟" label="Total Dreams" :value="$stats['total']" color="purple" />
        <x-stat-card icon="💭" label="Dreaming" :value="$stats['dreaming']" color="blue" />
        <x-stat-card icon="✨" label="Manifesting" :value="$stats['manifesting']" color="orange" />
        <x-stat-card icon="🎉" label="Manifested" :value="$stats['manifested']" color="green" />
    </div>

    <!-- Filters -->
    <x-card class="mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <select name="category" onchange="this.form.submit()"
                        class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-2 px-3 font-medium text-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->icon }} {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <select name="status" onchange="this.form.submit()"
                        class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-2 px-3 font-medium text-sm">
                    <option value="">All Status</option>
                    <option value="dreaming" {{ request('status') == 'dreaming' ? 'selected' : '' }}>Dreaming</option>
                    <option value="manifesting" {{ request('status') == 'manifesting' ? 'selected' : '' }}>Manifesting</option>
                    <option value="manifested" {{ request('status') == 'manifested' ? 'selected' : '' }}>Manifested</option>
                </select>
            </div>
            @if(request()->hasAny(['category', 'status']))
                <a href="{{ route('dreams.index') }}" class="text-duo-red font-bold text-sm flex items-center">
                    Clear filters
                </a>
            @endif
        </form>
    </x-card>

    <!-- Dreams Grid -->
    @if($dreams->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($dreams as $dream)
                <a href="{{ route('dreams.show', $dream) }}" class="block">
                    <x-card interactive class="h-full {{ $dream->status === 'manifested' ? 'bg-gradient-to-br from-duo-green-light/10 to-duo-yellow/10 border-duo-green' : ($dream->status === 'manifesting' ? 'bg-gradient-to-br from-duo-purple/10 to-duo-pink/10' : '') }}">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <span class="text-xl">{{ $dream->category->icon }}</span>
                                <x-badge size="sm" :color="match($dream->status) { 'manifested' => 'green', 'manifesting' => 'purple', default => 'blue' }">
                                    {{ ucfirst($dream->status) }}
                                </x-badge>
                            </div>
                            @if($dream->status === 'manifested')
                                <span class="text-2xl">🎉</span>
                            @endif
                        </div>

                        <h3 class="font-bold text-duo-gray-500 text-lg mb-2">{{ $dream->title }}</h3>

                        @if($dream->description)
                            <p class="text-sm text-duo-gray-300 mb-3 line-clamp-2">{{ $dream->description }}</p>
                        @endif

                        @if($dream->affirmation)
                            <div class="bg-duo-purple/10 rounded-duo p-3 mb-3">
                                <p class="text-sm text-duo-purple italic">"{{ Str::limit($dream->affirmation, 60) }}"</p>
                            </div>
                        @endif

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-duo-gray-200">{{ $dream->journalEntries->count() }} journal entries</span>
                            <span class="font-bold text-duo-yellow">+{{ $dream->xp_reward }} XP</span>
                        </div>

                        @if($dream->manifestation_date)
                            <div class="mt-3 pt-3 border-t border-duo-gray-100 text-sm text-duo-green font-bold">
                                Manifested: {{ $dream->manifestation_date->format('M d, Y') }}
                            </div>
                        @endif
                    </x-card>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $dreams->links() }}
        </div>
    @else
        <x-card class="text-center py-12">
            <span class="text-6xl mb-4 block">🌟</span>
            <h3 class="text-xl font-bold text-duo-gray-500 mb-2">No dreams yet</h3>
            <p class="text-duo-gray-300 mb-6">Start visualizing your ideal life!</p>
            <a href="{{ route('dreams.create') }}">
                <x-primary-button>Create Your First Dream</x-primary-button>
            </a>
        </x-card>
    @endif

    <!-- Manifestation Quote -->
    <x-card class="mt-8 bg-gradient-to-r from-duo-purple/10 to-duo-pink/10 border-duo-purple/30">
        <div class="flex items-center space-x-4">
            <span class="text-4xl">🔮</span>
            <div>
                <h3 class="font-bold text-duo-purple">The Power of Manifestation</h3>
                <p class="text-duo-gray-400 italic">"Whatever the mind can conceive and believe, it can achieve." - Napoleon Hill</p>
            </div>
        </div>
    </x-card>
</x-app-layout>
