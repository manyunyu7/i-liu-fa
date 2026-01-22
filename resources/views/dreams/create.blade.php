<x-app-layout>
    <x-slot name="title">Create Dream</x-slot>

    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('dreams.index') }}" class="inline-flex items-center text-duo-gray-300 hover:text-duo-gray-400 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Dreams
            </a>
            <h1 class="text-3xl font-extrabold text-duo-gray-500">Create Your Dream</h1>
            <p class="text-duo-gray-300">Visualize what you want to manifest into reality</p>
        </div>

        <x-card>
            <form action="{{ route('dreams.store') }}" method="POST">
                @csrf

                <!-- Title -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Dream Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="e.g., My Dream Home, Financial Freedom"
                           class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                    @error('title')
                        <p class="mt-1 text-sm text-duo-red">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Category *</label>
                    <select name="dream_category_id" required
                            class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('dream_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->icon }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Detailed Description</label>
                    <textarea name="description" rows="4"
                              placeholder="Describe your dream in vivid detail. What does it look like? How does it feel?"
                              class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium resize-none">{{ old('description') }}</textarea>
                    <p class="mt-2 text-sm text-duo-gray-200">
                        The more detail you add, the more powerful your visualization becomes.
                    </p>
                </div>

                <!-- Affirmation -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Personal Affirmation</label>
                    <textarea name="affirmation" rows="2"
                              placeholder="e.g., I am living in my dream home, surrounded by abundance..."
                              class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium resize-none">{{ old('affirmation') }}</textarea>
                    <p class="mt-2 text-sm text-duo-gray-200">
                        Write an affirmation as if your dream has already come true.
                    </p>
                </div>

                <!-- Tips Card -->
                <div class="bg-duo-purple/10 rounded-duo p-4 mb-6">
                    <h3 class="font-bold text-duo-purple mb-2">Manifestation Tips</h3>
                    <ul class="text-sm text-duo-gray-400 space-y-1">
                        <li>• Visualize your dream daily with emotion</li>
                        <li>• Write in your dream journal regularly</li>
                        <li>• Speak your affirmation with conviction</li>
                        <li>• Take inspired action towards your dream</li>
                        <li>• Trust the process and stay patient</li>
                    </ul>
                </div>

                <!-- Submit -->
                <div class="flex justify-end space-x-4 pt-4 border-t border-duo-gray-100">
                    <a href="{{ route('dreams.index') }}">
                        <x-secondary-button type="button">Cancel</x-secondary-button>
                    </a>
                    <x-primary-button type="submit">Create Dream</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
