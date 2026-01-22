<x-app-layout>
    <x-slot name="title">Random Quote</x-slot>

    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <span class="text-6xl">🎲</span>
            <h1 class="text-3xl font-extrabold text-duo-gray-500 mt-4">Random Inspiration</h1>
            <p class="text-duo-gray-300">A surprise quote just for you</p>
        </div>

        @if($quote)
            <!-- Quote Card -->
            <x-card class="bg-gradient-to-br from-duo-yellow/5 to-duo-orange/5 border-2 border-duo-yellow/20">
                <div class="text-center py-8">
                    <span class="text-6xl text-duo-yellow/30">"</span>

                    <blockquote class="text-2xl md:text-3xl text-duo-gray-500 font-bold leading-relaxed px-4 mt-4">
                        {{ $quote->content }}
                    </blockquote>

                    <div class="mt-8">
                        @if($quote->author)
                            <p class="text-xl text-duo-yellow font-bold">— {{ $quote->author }}</p>
                        @endif
                        @if($quote->source)
                            <p class="text-duo-gray-300 mt-1">{{ $quote->source }}</p>
                        @endif
                    </div>

                    <div class="mt-8 flex justify-center space-x-4">
                        <form action="{{ route('quotes.favorite', $quote) }}" method="POST">
                            @csrf
                            <x-button type="submit" color="purple" size="lg">
                                {{ auth()->user()->favoriteQuotes()->where('quote_id', $quote->id)->exists() ? '❤️ Favorited' : '🤍 Add to Favorites' }}
                            </x-button>
                        </form>
                        <a href="{{ route('quotes.random') }}">
                            <x-button color="yellow" size="lg">🎲 Another One</x-button>
                        </a>
                    </div>
                </div>
            </x-card>
        @else
            <x-card class="text-center py-12">
                <span class="text-6xl">💭</span>
                <h3 class="text-xl font-bold text-duo-gray-500 mt-4">No Quote Available</h3>
                <p class="text-duo-gray-300 mt-2">Check back soon for more quotes!</p>
            </x-card>
        @endif

        <!-- Navigation -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('quotes.index') }}">
                <x-button color="gray" size="md">Browse All Quotes</x-button>
            </a>
            <a href="{{ route('quotes.daily') }}">
                <x-button color="green" size="md">Today's Quote</x-button>
            </a>
        </div>
    </div>
</x-app-layout>
