<x-app-layout>
    <x-slot name="title">Edit - {{ $item->title }}</x-slot>

    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('bucket-list.show', $item) }}" class="inline-flex items-center text-duo-gray-300 hover:text-duo-gray-400 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Goal
            </a>
            <h1 class="text-3xl font-extrabold text-duo-gray-500">Edit Goal</h1>
        </div>

        <x-card>
            <form action="{{ route('bucket-list.update', $item) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Goal Title *</label>
                    <input type="text" name="title" value="{{ old('title', $item->title) }}" required
                           class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                    @error('title')
                        <p class="mt-1 text-sm text-duo-red">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium resize-none">{{ old('description', $item->description) }}</textarea>
                </div>

                <!-- Category -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Category *</label>
                    <select name="bucket_list_category_id" required
                            class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('bucket_list_category_id', $item->bucket_list_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->icon }} {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-bold text-duo-gray-400 mb-2">Priority *</label>
                        <select name="priority" required
                                class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                            <option value="low" {{ old('priority', $item->priority) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $item->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $item->priority) == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>

                    <!-- Target Date -->
                    <div>
                        <label class="block text-sm font-bold text-duo-gray-400 mb-2">Target Date</label>
                        <input type="date" name="target_date" value="{{ old('target_date', $item->target_date?->format('Y-m-d')) }}"
                               class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                    </div>
                </div>

                <!-- Progress -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Progress: <span id="progress-value">{{ old('progress', $item->progress) }}%</span></label>
                    <input type="range" name="progress" min="0" max="100" value="{{ old('progress', $item->progress) }}"
                           oninput="document.getElementById('progress-value').textContent = this.value + '%'"
                           class="w-full h-4 rounded-full appearance-none bg-duo-gray-100 cursor-pointer">
                    <div class="flex justify-between text-xs text-duo-gray-200 mt-1">
                        <span>0%</span>
                        <span>100%</span>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-end space-x-4 pt-4 border-t border-duo-gray-100">
                    <a href="{{ route('bucket-list.show', $item) }}">
                        <x-secondary-button type="button">Cancel</x-secondary-button>
                    </a>
                    <x-primary-button type="submit">Save Changes</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
