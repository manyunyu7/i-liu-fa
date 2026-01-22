<x-app-layout>
    <x-slot name="title">Bucket List</x-slot>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-duo-gray-500 mb-2">Bucket List</h1>
            <p class="text-duo-gray-300">Track your life goals and dreams</p>
        </div>
        <a href="{{ route('bucket-list.create') }}">
            <x-primary-button>
                + Add Goal
            </x-primary-button>
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4 mb-8">
        <x-stat-card icon="🎯" label="Total Goals" :value="$stats['total']" color="blue" />
        <x-stat-card icon="🏃" label="In Progress" :value="$stats['in_progress']" color="orange" />
        <x-stat-card icon="✅" label="Completed" :value="$stats['completed']" color="green" />
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
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <select name="priority" onchange="this.form.submit()"
                        class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-2 px-3 font-medium text-sm">
                    <option value="">All Priority</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>
            @if(request()->hasAny(['category', 'status', 'priority']))
                <a href="{{ route('bucket-list.index') }}" class="text-duo-red font-bold text-sm flex items-center">
                    Clear filters
                </a>
            @endif
        </form>
    </x-card>

    <!-- Items Grid -->
    @if($items->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($items as $item)
                <a href="{{ route('bucket-list.show', $item) }}" class="block">
                    <x-card interactive class="h-full {{ $item->status === 'completed' ? 'bg-duo-green-light/10 border-duo-green' : '' }}">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <span class="text-xl">{{ $item->category->icon }}</span>
                                <x-badge size="sm" :color="match($item->priority) { 'high' => 'red', 'medium' => 'yellow', 'low' => 'gray' }">
                                    {{ ucfirst($item->priority) }}
                                </x-badge>
                            </div>
                            @if($item->status === 'completed')
                                <span class="text-2xl">✅</span>
                            @endif
                        </div>

                        <h3 class="font-bold text-duo-gray-500 text-lg mb-2 {{ $item->status === 'completed' ? 'line-through' : '' }}">
                            {{ $item->title }}
                        </h3>

                        @if($item->description)
                            <p class="text-sm text-duo-gray-300 mb-3 line-clamp-2">{{ $item->description }}</p>
                        @endif

                        <x-progress-bar :progress="$item->progress" color="green" size="sm" />

                        <div class="flex items-center justify-between mt-3 text-sm">
                            <span class="text-duo-gray-200">{{ $item->progress }}% complete</span>
                            <span class="font-bold text-duo-yellow">+{{ $item->xp_reward }} XP</span>
                        </div>

                        @if($item->target_date)
                            <div class="mt-3 pt-3 border-t border-duo-gray-100 text-sm text-duo-gray-200">
                                Target: {{ $item->target_date->format('M d, Y') }}
                            </div>
                        @endif
                    </x-card>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $items->links() }}
        </div>
    @else
        <x-card class="text-center py-12">
            <span class="text-6xl mb-4 block">🎯</span>
            <h3 class="text-xl font-bold text-duo-gray-500 mb-2">No bucket list items yet</h3>
            <p class="text-duo-gray-300 mb-6">Start adding your life goals and dreams!</p>
            <a href="{{ route('bucket-list.create') }}">
                <x-primary-button>Create Your First Goal</x-primary-button>
            </a>
        </x-card>
    @endif
</x-app-layout>
