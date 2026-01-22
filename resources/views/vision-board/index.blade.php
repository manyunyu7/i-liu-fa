<x-app-layout>
    <x-slot name="title">Vision Boards</x-slot>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-duo-gray-500 mb-2">Vision Boards</h1>
            <p class="text-duo-gray-300">Visualize your dreams and manifest your goals</p>
        </div>
        <a href="{{ route('vision-board.create') }}">
            <x-button color="green" icon="plus">
                Create Board
            </x-button>
        </a>
    </div>

    @if($boards->isEmpty())
        <!-- Empty State -->
        <x-card class="text-center py-12">
            <span class="text-6xl mb-4 block">🎨</span>
            <h3 class="text-xl font-bold text-duo-gray-500 mb-2">Create Your First Vision Board</h3>
            <p class="text-duo-gray-300 mb-6">
                Vision boards help you visualize your dreams and keep you motivated.<br>
                Add images, quotes, and goals to manifest your ideal life.
            </p>
            <a href="{{ route('vision-board.create') }}">
                <x-button color="green" size="lg">
                    Get Started
                </x-button>
            </a>
        </x-card>
    @else
        <!-- Vision Boards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($boards as $board)
                <a href="{{ route('vision-board.show', $board) }}" class="group">
                    <x-card class="hover:shadow-lg transition-shadow overflow-hidden {{ $board->is_primary ? 'ring-2 ring-duo-yellow' : '' }}">
                        <!-- Preview Area -->
                        <div class="h-40 -mx-6 -mt-6 mb-4 relative overflow-hidden"
                             style="background-color: {{ $board->background_color }}">
                            @if($board->background_image)
                                <img src="{{ $board->background_image }}"
                                     alt=""
                                     class="w-full h-full object-cover opacity-50">
                            @endif

                            <!-- Theme Overlay -->
                            @if($board->theme === 'cosmic')
                                <div class="absolute inset-0 bg-gradient-to-br from-purple-900/50 to-indigo-900/50"></div>
                            @endif

                            <!-- Items Preview -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-4xl opacity-60">
                                    {{ match($board->theme) {
                                        'cosmic' => '✨',
                                        'nature' => '🌿',
                                        'sunset' => '🌅',
                                        'ocean' => '🌊',
                                        default => '🎯'
                                    } }}
                                </span>
                            </div>

                            @if($board->is_primary)
                                <div class="absolute top-2 right-2">
                                    <x-badge color="yellow" size="sm">Primary</x-badge>
                                </div>
                            @endif
                        </div>

                        <!-- Board Info -->
                        <h3 class="font-bold text-duo-gray-500 group-hover:text-duo-green transition-colors mb-1">
                            {{ $board->title }}
                        </h3>

                        @if($board->description)
                            <p class="text-sm text-duo-gray-300 line-clamp-2 mb-3">
                                {{ $board->description }}
                            </p>
                        @endif

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-duo-gray-200">
                                {{ $board->items_count }} {{ Str::plural('item', $board->items_count) }}
                            </span>
                            <span class="text-duo-gray-200">
                                {{ $board->updated_at->diffForHumans() }}
                            </span>
                        </div>
                    </x-card>
                </a>
            @endforeach
        </div>
    @endif

    <!-- Tips Card -->
    <x-card class="mt-8 bg-gradient-to-r from-duo-purple/10 to-duo-pink/10 border-duo-purple/30">
        <div class="flex items-start space-x-4">
            <span class="text-4xl">💡</span>
            <div>
                <h3 class="font-bold text-duo-purple mb-2">Vision Board Tips</h3>
                <ul class="text-duo-gray-400 text-sm space-y-1">
                    <li>• Include images that represent your goals and dreams</li>
                    <li>• Add powerful affirmations that resonate with you</li>
                    <li>• Review your vision board daily for maximum impact</li>
                    <li>• Update it as your dreams evolve and manifest</li>
                </ul>
            </div>
        </div>
    </x-card>
</x-app-layout>
