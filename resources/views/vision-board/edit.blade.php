<x-app-layout>
    <x-slot name="title">Edit {{ $visionBoard->title }}</x-slot>

    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('vision-board.show', $visionBoard) }}" class="text-duo-gray-300 hover:text-duo-green transition-colors mb-4 inline-flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Back to Board</span>
        </a>
        <h1 class="text-3xl font-extrabold text-duo-gray-500 mt-4">Edit Vision Board</h1>
    </div>

    <form action="{{ route('vision-board.update', $visionBoard) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Form -->
            <div class="space-y-6">
                <x-card>
                    <h2 class="text-xl font-bold text-duo-gray-500 mb-6">Board Details</h2>

                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-bold text-duo-gray-500 mb-2">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="title"
                               id="title"
                               value="{{ old('title', $visionBoard->title) }}"
                               class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0 transition-colors"
                               required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-bold text-duo-gray-500 mb-2">
                            Description
                        </label>
                        <textarea name="description"
                                  id="description"
                                  rows="3"
                                  class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0 transition-colors">{{ old('description', $visionBoard->description) }}</textarea>
                    </div>

                    <!-- Options -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox"
                                   name="is_primary"
                                   id="is_primary"
                                   value="1"
                                   {{ old('is_primary', $visionBoard->is_primary) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded border-duo-gray-200 text-duo-green focus:ring-duo-green">
                            <label for="is_primary" class="text-duo-gray-500">
                                Set as primary vision board
                            </label>
                        </div>
                        <div class="flex items-center space-x-3">
                            <input type="checkbox"
                                   name="is_public"
                                   id="is_public"
                                   value="1"
                                   {{ old('is_public', $visionBoard->is_public) ? 'checked' : '' }}
                                   class="w-5 h-5 rounded border-duo-gray-200 text-duo-green focus:ring-duo-green">
                            <label for="is_public" class="text-duo-gray-500">
                                Make this board public (shareable link)
                            </label>
                        </div>
                    </div>
                </x-card>

                <!-- Danger Zone -->
                <x-card class="border-red-200">
                    <h3 class="font-bold text-red-600 mb-4">Danger Zone</h3>
                    <p class="text-duo-gray-400 text-sm mb-4">
                        Deleting this vision board will permanently remove all items.
                    </p>
                    <form action="{{ route('vision-board.destroy', $visionBoard) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this vision board?')">
                        @csrf
                        @method('DELETE')
                        <x-button type="submit" color="red" size="sm">
                            Delete Vision Board
                        </x-button>
                    </form>
                </x-card>
            </div>

            <!-- Theme Selection -->
            <div>
                <x-card>
                    <h2 class="text-xl font-bold text-duo-gray-500 mb-6">Theme</h2>

                    <div class="grid grid-cols-2 gap-4">
                        @foreach($themes as $key => $theme)
                            <label class="cursor-pointer">
                                <input type="radio"
                                       name="theme"
                                       value="{{ $key }}"
                                       {{ old('theme', $visionBoard->theme) === $key ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="p-4 border-2 border-duo-gray-100 rounded-duo transition-all peer-checked:border-duo-green peer-checked:ring-2 peer-checked:ring-duo-green/20 hover:border-duo-gray-200">
                                    <div class="h-20 rounded-lg mb-3 {{ $theme['preview'] }} flex items-center justify-center">
                                        <span class="text-2xl">
                                            {{ match($key) {
                                                'cosmic' => '✨',
                                                'nature' => '🌿',
                                                'sunset' => '🌅',
                                                'ocean' => '🌊',
                                                default => '🎯'
                                            } }}
                                        </span>
                                    </div>
                                    <p class="font-bold text-duo-gray-500 text-center">{{ $theme['name'] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </x-card>

                <!-- Submit -->
                <div class="mt-6 space-y-3">
                    <x-button type="submit" color="green" size="lg" class="w-full">
                        Save Changes
                    </x-button>
                    <a href="{{ route('vision-board.show', $visionBoard) }}" class="block">
                        <x-button type="button" color="gray" size="lg" class="w-full">
                            Cancel
                        </x-button>
                    </a>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>
