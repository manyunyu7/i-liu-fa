<x-app-layout>
    <x-slot name="title">Add Bucket List Goal</x-slot>

    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('bucket-list.index') }}" class="inline-flex items-center text-duo-gray-300 hover:text-duo-gray-400 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Bucket List
            </a>
            <h1 class="text-3xl font-extrabold text-duo-gray-500">Add New Goal</h1>
            <p class="text-duo-gray-300">What do you want to achieve in your life?</p>
        </div>

        <x-card>
            <form action="{{ route('bucket-list.store') }}" method="POST" x-data="{ milestones: [''] }">
                @csrf

                <!-- Title -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Goal Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="e.g., Visit Japan"
                           class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                    @error('title')
                        <p class="mt-1 text-sm text-duo-red">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Description</label>
                    <textarea name="description" rows="3"
                              placeholder="Describe your goal in detail..."
                              class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium resize-none">{{ old('description') }}</textarea>
                </div>

                <!-- Category -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Category *</label>
                    <select name="bucket_list_category_id" required
                            class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('bucket_list_category_id') == $category->id ? 'selected' : '' }}>
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
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>

                    <!-- Target Date -->
                    <div>
                        <label class="block text-sm font-bold text-duo-gray-400 mb-2">Target Date</label>
                        <input type="date" name="target_date" value="{{ old('target_date') }}"
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               class="w-full rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-3 px-4 font-medium">
                    </div>
                </div>

                <!-- Milestones -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-duo-gray-400 mb-2">Milestones (optional)</label>
                    <p class="text-sm text-duo-gray-200 mb-3">Break down your goal into smaller steps</p>

                    <template x-for="(milestone, index) in milestones" :key="index">
                        <div class="flex items-center space-x-2 mb-2">
                            <input type="text" :name="`milestones[${index}]`" x-model="milestones[index]"
                                   placeholder="e.g., Book flights"
                                   class="flex-1 rounded-duo border-2 border-duo-gray-100 focus:border-duo-green focus:ring-duo-green py-2 px-3 font-medium">
                            <button type="button" @click="milestones.splice(index, 1)" x-show="milestones.length > 1"
                                    class="p-2 text-duo-red hover:bg-duo-red/10 rounded-full">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </template>

                    <button type="button" @click="milestones.push('')"
                            class="text-duo-blue font-bold text-sm hover:underline">
                        + Add another milestone
                    </button>
                </div>

                <!-- Submit -->
                <div class="flex justify-end space-x-4 pt-4 border-t border-duo-gray-100">
                    <a href="{{ route('bucket-list.index') }}">
                        <x-secondary-button type="button">Cancel</x-secondary-button>
                    </a>
                    <x-primary-button type="submit">Create Goal</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
