<x-app-layout>
    <x-slot name="title">Favorite Quotes</x-slot>

    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('quotes.index') }}" class="text-duo-gray-300 hover:text-duo-green transition-colors mb-4 inline-flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Back to Quotes</span>
        </a>
        <h1 class="text-3xl font-extrabold text-duo-gray-500 mt-4">My Favorite Quotes</h1>
        <p class="text-duo-gray-300">Your personal collection of inspiration</p>
    </div>

    <!-- Quotes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($quotes as $quote)
            <x-card class="relative border-2 border-duo-purple/20">
                <div class="flex items-start justify-between">
                    <span class="text-4xl text-duo-purple/30">"</span>
                    <form action="{{ route('quotes.favorite', $quote) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-2xl hover:scale-110 transition-transform">
                            ❤️
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
                    </div>
                    <span class="text-xs font-bold px-2 py-1 bg-duo-gray-50 text-duo-gray-400 rounded-full">
                        {{ ucfirst($quote->category) }}
                    </span>
                </div>
            </x-card>
        @empty
            <div class="col-span-2">
                <x-card class="text-center py-12">
                    <span class="text-6xl">💭</span>
                    <h3 class="text-xl font-bold text-duo-gray-500 mt-4">No Favorites Yet</h3>
                    <p class="text-duo-gray-300 mt-2">Start adding quotes to your favorites!</p>
                    <a href="{{ route('quotes.index') }}" class="mt-4 inline-block">
                        <x-button color="green" size="md">Browse Quotes</x-button>
                    </a>
                </x-card>
            </div>
        @endforelse
    </div>

    {{ $quotes->links() }}
</x-app-layout>
