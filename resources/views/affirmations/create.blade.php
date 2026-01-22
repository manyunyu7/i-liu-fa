<x-app-layout>
    <x-slot name="title">Create Affirmation</x-slot>

    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('affirmations.index') }}" class="inline-flex items-center text-duo-gray-300 hover:text-duo-gray-400 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Affirmations
            </a>
            <h1 class="text-3xl font-extrabold text-duo-gray-500">Create Your Affirmation</h1>
            <p class="text-duo-gray-300">Write a powerful statement that resonates with you</p>
        </div>

        <x-card>
            <form action="{{ route('affirmations.store') }}" method="POST">
                @csrf

                <!-- Category -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Category</label>
                    <select name="affirmation_category_id" required
                            class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('affirmation_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->icon }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('affirmation_category_id')
                        <p class="mt-1 text-sm text-duo-red">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Your Affirmation</label>
                    <textarea name="content" rows="4" required
                              placeholder="I am worthy of love and abundance..."
                              class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium resize-none">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-duo-red">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-duo-gray-200">
                        Tips: Start with "I am", "I have", or "I attract". Use present tense and positive language.
                    </p>
                </div>

                <!-- Tips Card -->
                <div class="bg-duo-blue/10 rounded-duo p-4 mb-6">
                    <h3 class="font-bold text-duo-blue mb-2">Writing Powerful Affirmations</h3>
                    <ul class="text-sm text-duo-gray-400 space-y-1">
                        <li>• Use first person ("I am", "I have")</li>
                        <li>• Keep it positive (avoid "not", "don't")</li>
                        <li>• Write in present tense</li>
                        <li>• Make it personal and meaningful</li>
                        <li>• Keep it believable to you</li>
                    </ul>
                </div>

                <!-- Submit -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('affirmations.index') }}">
                        <x-secondary-button type="button">Cancel</x-secondary-button>
                    </a>
                    <x-primary-button type="submit">Create Affirmation</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
