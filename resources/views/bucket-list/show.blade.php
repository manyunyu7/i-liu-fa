<x-app-layout>
    <x-slot name="title">{{ $item->title }}</x-slot>

    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('bucket-list.index') }}" class="inline-flex items-center text-duo-gray-300 hover:text-duo-gray-400 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Bucket List
            </a>

            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-4xl">{{ $item->category->icon }}</span>
                    <div>
                        <h1 class="text-3xl font-extrabold text-duo-gray-500 {{ $item->status === 'completed' ? 'line-through' : '' }}">
                            {{ $item->title }}
                        </h1>
                        <div class="flex items-center space-x-2 mt-1">
                            <x-badge :color="match($item->status) { 'completed' => 'green', 'in_progress' => 'blue', default => 'gray' }">
                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                            </x-badge>
                            <x-badge :color="match($item->priority) { 'high' => 'red', 'medium' => 'yellow', 'low' => 'gray' }">
                                {{ ucfirst($item->priority) }} Priority
                            </x-badge>
                        </div>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('bucket-list.edit', $item) }}"
                       class="p-2 text-duo-gray-300 hover:text-duo-blue hover:bg-duo-blue/10 rounded-duo">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    <form action="{{ route('bucket-list.destroy', $item) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this goal?')">
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

        @if($item->status === 'completed')
            <div class="bg-duo-green-light/20 border-2 border-duo-green rounded-duo p-4 mb-6 text-center">
                <span class="text-4xl">🎉</span>
                <p class="font-bold text-duo-green mt-2">Goal Completed!</p>
                <p class="text-sm text-duo-gray-400">Completed on {{ $item->completed_at->format('M d, Y') }}</p>
            </div>
        @endif

        <!-- Progress -->
        <x-card class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-duo-gray-500">Progress</h2>
                <span class="text-2xl font-extrabold text-duo-green">{{ $item->progress }}%</span>
            </div>
            <x-progress-bar :progress="$item->progress" color="green" size="lg" />

            @if($item->target_date)
                <div class="mt-4 pt-4 border-t border-duo-gray-100 flex items-center justify-between">
                    <span class="text-duo-gray-300 font-bold">Target Date</span>
                    <span class="font-bold {{ $item->target_date->isPast() && $item->status !== 'completed' ? 'text-duo-red' : 'text-duo-gray-500' }}">
                        {{ $item->target_date->format('F d, Y') }}
                        @if($item->target_date->isPast() && $item->status !== 'completed')
                            (Overdue)
                        @elseif($item->status !== 'completed')
                            ({{ $item->target_date->diffForHumans() }})
                        @endif
                    </span>
                </div>
            @endif

            <div class="mt-4 pt-4 border-t border-duo-gray-100 flex items-center justify-between">
                <span class="text-duo-gray-300 font-bold">Reward</span>
                <span class="font-bold text-duo-yellow">+{{ $item->xp_reward }} XP</span>
            </div>
        </x-card>

        <!-- Description -->
        @if($item->description)
            <x-card class="mb-6">
                <h2 class="text-xl font-bold text-duo-gray-500 mb-3">Description</h2>
                <p class="text-duo-gray-400 whitespace-pre-line">{{ $item->description }}</p>
            </x-card>
        @endif

        <!-- Milestones -->
        <x-card class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-duo-gray-500">Milestones</h2>
                @if($item->milestones->count() > 0)
                    <span class="text-sm font-bold text-duo-gray-200">
                        {{ $item->milestones->where('is_completed', true)->count() }}/{{ $item->milestones->count() }} completed
                    </span>
                @endif
            </div>

            @if($item->milestones->count() > 0)
                <div class="space-y-2">
                    @foreach($item->milestones as $milestone)
                        <form action="{{ route('bucket-list.milestones.toggle', $milestone) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-3 p-3 rounded-duo hover:bg-duo-gray-50 transition-colors text-left">
                                <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center flex-shrink-0 {{ $milestone->is_completed ? 'bg-duo-green border-duo-green text-white' : 'border-duo-gray-200' }}">
                                    @if($milestone->is_completed)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @endif
                                </div>
                                <span class="font-medium {{ $milestone->is_completed ? 'text-duo-gray-200 line-through' : 'text-duo-gray-500' }}">
                                    {{ $milestone->title }}
                                </span>
                            </button>
                        </form>
                    @endforeach
                </div>
            @else
                <p class="text-duo-gray-200 text-center py-4">No milestones added yet</p>
            @endif

            <!-- Add Milestone Form -->
            <form action="{{ route('bucket-list.milestones.store', $item) }}" method="POST" class="mt-4 pt-4 border-t border-duo-gray-100">
                @csrf
                <div class="flex space-x-2">
                    <input type="text" name="title" placeholder="Add a new milestone..."
                           class="flex-1 rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-2 px-3 font-medium">
                    <x-primary-button type="submit" class="px-4">Add</x-primary-button>
                </div>
            </form>
        </x-card>

        <!-- Category Info -->
        <x-card>
            <div class="flex items-center space-x-3">
                <span class="text-3xl">{{ $item->category->icon }}</span>
                <div>
                    <p class="text-sm text-duo-gray-200">Category</p>
                    <p class="font-bold text-duo-gray-500">{{ $item->category->name }}</p>
                </div>
            </div>
        </x-card>
    </div>
</x-app-layout>
