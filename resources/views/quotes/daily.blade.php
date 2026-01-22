<x-app-layout>
    <x-slot name="title">Today's Quote</x-slot>

    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <span class="text-6xl">✨</span>
            <h1 class="text-3xl font-extrabold text-duo-gray-500 mt-4">Your Daily Inspiration</h1>
            <p class="text-duo-gray-300">{{ now()->format('l, F j, Y') }}</p>
        </div>

        @if($quote)
            <!-- Quote Card -->
            <x-card class="bg-gradient-to-br from-duo-green/5 to-duo-blue/5 border-2 border-duo-green/20">
                <div class="text-center py-8">
                    <span class="text-6xl text-duo-green/30">"</span>

                    <blockquote class="text-2xl md:text-3xl text-duo-gray-500 font-bold leading-relaxed px-4 mt-4">
                        {{ $quote->content }}
                    </blockquote>

                    <div class="mt-8">
                        @if($quote->author)
                            <p class="text-xl text-duo-green font-bold">— {{ $quote->author }}</p>
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
                    </div>
                </div>
            </x-card>

            <!-- Category Badge -->
            <div class="text-center mt-6">
                <span class="inline-block px-4 py-2 bg-duo-gray-50 text-duo-gray-400 rounded-full text-sm font-bold">
                    {{ ucfirst($quote->category) }}
                </span>
            </div>
        @else
            <x-card class="text-center py-12">
                <span class="text-6xl">💭</span>
                <h3 class="text-xl font-bold text-duo-gray-500 mt-4">No Quote Available</h3>
                <p class="text-duo-gray-300 mt-2">Check back soon for daily inspiration!</p>
            </x-card>
        @endif

        <!-- Navigation -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('quotes.index') }}">
                <x-button color="gray" size="md">Browse All Quotes</x-button>
            </a>
            <a href="{{ route('quotes.random') }}">
                <x-button color="green" size="md">Random Quote</x-button>
            </a>
        </div>

        <!-- Tip Card -->
        <x-card class="mt-8">
            <div class="flex items-start space-x-4">
                <span class="text-3xl">💡</span>
                <div>
                    <h3 class="font-bold text-duo-gray-500">Pro Tip</h3>
                    <p class="text-sm text-duo-gray-300 mt-1">
                        Start your day by reading your daily quote and reflecting on how you can apply its wisdom.
                        Add your favorites to revisit them whenever you need a boost!
                    </p>
                </div>
            </div>
        </x-card>
    </div>
</x-app-layout>
