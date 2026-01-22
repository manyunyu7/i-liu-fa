<x-app-layout>
    <x-slot name="title">Edit - {{ $dream->title }}</x-slot>

    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('dreams.show', $dream) }}" class="inline-flex items-center text-duo-gray-300 hover:text-duo-gray-400 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Dream
            </a>
            <h1 class="text-3xl font-extrabold text-duo-gray-500">Edit Dream</h1>
        </div>

        <x-card>
            <form action="{{ route('dreams.update', $dream) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Dream Title *</label>
                    <input type="text" name="title" value="{{ old('title', $dream->title) }}" required
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
                            <option value="{{ $category->id }}" {{ old('dream_category_id', $dream->dream_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->icon }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Status *</label>
                    <select name="status" required
                            class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                        <option value="dreaming" {{ old('status', $dream->status) == 'dreaming' ? 'selected' : '' }}>Dreaming</option>
                        <option value="manifesting" {{ old('status', $dream->status) == 'manifesting' ? 'selected' : '' }}>Manifesting</option>
                        <option value="manifested" {{ old('status', $dream->status) == 'manifested' ? 'selected' : '' }}>Manifested</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium resize-none">{{ old('description', $dream->description) }}</textarea>
                </div>

                <!-- Affirmation -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Personal Affirmation</label>
                    <textarea name="affirmation" rows="2"
                              class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium resize-none">{{ old('affirmation', $dream->affirmation) }}</textarea>
                </div>

                <!-- Submit -->
                <div class="flex justify-end space-x-4 pt-4 border-t border-duo-gray-100">
                    <a href="{{ route('dreams.show', $dream) }}">
                        <x-secondary-button type="button">Cancel</x-secondary-button>
                    </a>
                    <x-primary-button type="submit">Save Changes</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
