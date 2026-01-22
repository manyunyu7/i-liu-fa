<x-app-layout>
    <x-slot name="title">Motivational Quotes</x-slot>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-duo-gray-500">Motivational Quotes</h1>
                <p class="text-duo-gray-300">Get inspired with daily wisdom!</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('quotes.daily') }}">
                    <x-button color="yellow" size="md">Today's Quote</x-button>
                </a>
                <a href="{{ route('quotes.favorites') }}">
                    <x-button color="purple" size="md">My Favorites</x-button>
                </a>
            </div>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('quotes.index') }}"
           class="px-4 py-2 rounded-full text-sm font-bold transition-colors {{ !$category ? 'bg-duo-green text-white' : 'bg-duo-gray-50 text-duo-gray-500 hover:bg-duo-gray-100' }}">
            All
        </a>
        @foreach($categories as $key => $label)
            <a href="{{ route('quotes.index', ['category' => $key]) }}"
               class="px-4 py-2 rounded-full text-sm font-bold transition-colors {{ $category === $key ? 'bg-duo-green text-white' : 'bg-duo-gray-50 text-duo-gray-500 hover:bg-duo-gray-100' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Quotes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($quotes as $quote)
            <x-card class="relative">
                <div class="flex items-start justify-between">
                    <span class="text-4xl text-duo-green/30">"</span>
                    <form action="{{ route('quotes.favorite', $quote) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-2xl hover:scale-110 transition-transform">
                            {{ in_array($quote->id, $favoriteIds) ? '❤️' : '🤍' }}
                        </button>
                    </form>
                </div>

                <blockquote class="text-lg text-duo-gray-500 font-medium mt-2 mb-4">
                    {{ $quote->content }}
                </blockquote>

                <div class="flex items-center justify-between">
                    <div>
                        @if($quote->author)
                            <p class="text-duo-gray-400 font-bold">— {{ $quote->author }}</p>
                        @endif
                        @if($quote->source)
                            <p class="text-sm text-duo-gray-200">{{ $quote->source }}</p>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-duo-gray-300">
                        <span>❤️</span>
                        <span>{{ $quote->likes_count }}</span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-duo-gray-100">
                    <span class="text-xs font-bold px-2 py-1 bg-duo-gray-50 text-duo-gray-400 rounded-full">
                        {{ ucfirst($quote->category) }}
                    </span>
                </div>
            </x-card>
        @empty
            <div class="col-span-2">
                <x-card class="text-center py-12">
                    <span class="text-6xl">💭</span>
                    <h3 class="text-xl font-bold text-duo-gray-500 mt-4">No Quotes Yet</h3>
                    <p class="text-duo-gray-300 mt-2">Inspirational quotes coming soon!</p>
                </x-card>
            </div>
        @endforelse
    </div>

    {{ $quotes->links() }}
</x-app-layout>
