<x-app-layout>
    <x-slot name="title">{{ $visionBoard->title }}</x-slot>

    <div x-data="visionBoardEditor()" x-init="init()">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('vision-board.index') }}" class="text-duo-gray-300 hover:text-duo-green transition-colors mb-2 inline-flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span>Vision Boards</span>
                </a>
                <h1 class="text-2xl font-extrabold text-duo-gray-500">{{ $visionBoard->title }}</h1>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('vision-board.edit', $visionBoard) }}">
                    <x-button color="gray" size="sm">
                        Edit Board
                    </x-button>
                </a>
                <x-button color="green" size="sm" @click="showAddModal = true">
                    Add Item
                </x-button>
            </div>
        </div>

        <!-- Vision Board Canvas -->
        <div class="relative bg-white rounded-duo shadow-lg overflow-hidden"
             style="height: 600px; background-color: {{ $visionBoard->background_color }};"
             @if($visionBoard->theme === 'cosmic')
                 class="bg-gradient-to-br from-indigo-950 to-purple-950"
             @endif>

            <!-- Items -->
            @foreach($visionBoard->items as $item)
                <div class="absolute cursor-move group"
                     style="left: {{ $item->position_x }}px; top: {{ $item->position_y }}px; width: {{ $item->width }}px; height: {{ $item->height }}px; z-index: {{ $item->z_index }}; transform: rotate({{ $item->rotation }}deg);"
                     draggable="true"
                     data-item-id="{{ $item->id }}">

                    <!-- Item Content -->
                    @if($item->type === 'image' && $item->image_url)
                        <img src="{{ $item->image_url }}"
                             alt="{{ $item->title }}"
                             class="w-full h-full object-cover rounded-lg shadow-md">
                    @elseif($item->type === 'text' || $item->type === 'quote')
                        <div class="w-full h-full p-4 rounded-lg shadow-md flex items-center justify-center text-center"
                             style="background-color: {{ $item->background_color ?? '#ffffff' }}; color: {{ $item->text_color ?? '#1f2937' }};">
                            <p class="{{ $item->type === 'quote' ? 'italic' : '' }} font-medium">
                                {{ $item->content }}
                            </p>
                        </div>
                    @elseif($item->type === 'goal' && $item->dream)
                        <div class="w-full h-full p-4 rounded-lg shadow-md bg-gradient-to-br from-duo-green/20 to-duo-blue/20 border-2 border-duo-green/30">
                            <p class="font-bold text-duo-gray-500 text-sm mb-1">Goal</p>
                            <p class="text-duo-gray-400">{{ $item->dream->title }}</p>
                        </div>
                    @elseif($item->type === 'affirmation')
                        <div class="w-full h-full p-4 rounded-lg shadow-md bg-gradient-to-br from-duo-purple/20 to-duo-pink/20 border-2 border-duo-purple/30 flex items-center justify-center">
                            <p class="text-duo-purple font-bold text-center italic">
                                "{{ $item->content }}"
                            </p>
                        </div>
                    @endif

                    <!-- Item Actions (on hover) -->
                    <div class="absolute -top-2 -right-2 opacity-0 group-hover:opacity-100 transition-opacity flex space-x-1">
                        <button @click="deleteItem({{ $item->id }})"
                                class="w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach

            <!-- Empty State -->
            @if($visionBoard->items->isEmpty())
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <span class="text-6xl mb-4 block opacity-50">🎨</span>
                        <p class="text-duo-gray-400 mb-4">Your vision board is empty</p>
                        <x-button color="green" @click="showAddModal = true">
                            Add Your First Item
                        </x-button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Add Item Modal -->
        <div x-show="showAddModal"
             x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
             @click.self="showAddModal = false">
            <div class="bg-white rounded-duo shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-duo-gray-500">Add Item</h2>
                        <button @click="showAddModal = false" class="text-duo-gray-300 hover:text-duo-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Item Type Selection -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <button @click="itemType = 'image'"
                                :class="{ 'border-duo-green bg-duo-green/10': itemType === 'image' }"
                                class="p-4 border-2 border-duo-gray-100 rounded-duo text-center hover:border-duo-gray-200 transition-colors">
                            <span class="text-2xl mb-2 block">🖼️</span>
                            <span class="font-bold text-duo-gray-500">Image</span>
                        </button>
                        <button @click="itemType = 'quote'"
                                :class="{ 'border-duo-green bg-duo-green/10': itemType === 'quote' }"
                                class="p-4 border-2 border-duo-gray-100 rounded-duo text-center hover:border-duo-gray-200 transition-colors">
                            <span class="text-2xl mb-2 block">💬</span>
                            <span class="font-bold text-duo-gray-500">Quote</span>
                        </button>
                        <button @click="itemType = 'goal'"
                                :class="{ 'border-duo-green bg-duo-green/10': itemType === 'goal' }"
                                class="p-4 border-2 border-duo-gray-100 rounded-duo text-center hover:border-duo-gray-200 transition-colors">
                            <span class="text-2xl mb-2 block">🎯</span>
                            <span class="font-bold text-duo-gray-500">Goal</span>
                        </button>
                        <button @click="itemType = 'affirmation'"
                                :class="{ 'border-duo-green bg-duo-green/10': itemType === 'affirmation' }"
                                class="p-4 border-2 border-duo-gray-100 rounded-duo text-center hover:border-duo-gray-200 transition-colors">
                            <span class="text-2xl mb-2 block">✨</span>
                            <span class="font-bold text-duo-gray-500">Affirmation</span>
                        </button>
                    </div>

                    <!-- Image Input -->
                    <div x-show="itemType === 'image'" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-duo-gray-500 mb-2">Image URL</label>
                            <input type="url"
                                   x-model="newItem.image_url"
                                   placeholder="https://example.com/image.jpg"
                                   class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0">
                        </div>
                    </div>

                    <!-- Quote/Text Input -->
                    <div x-show="itemType === 'quote' || itemType === 'text'" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-duo-gray-500 mb-2">Quote</label>
                            <textarea x-model="newItem.content"
                                      rows="3"
                                      placeholder="Enter your inspiring quote..."
                                      class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0"></textarea>
                        </div>
                    </div>

                    <!-- Goal Selection -->
                    <div x-show="itemType === 'goal'" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-duo-gray-500 mb-2">Select a Dream</label>
                            <select x-model="newItem.dream_id"
                                    class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0">
                                <option value="">Choose a dream...</option>
                                @foreach($dreams as $dream)
                                    <option value="{{ $dream->id }}">{{ $dream->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Affirmation Input -->
                    <div x-show="itemType === 'affirmation'" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-duo-gray-500 mb-2">Affirmation</label>
                            <textarea x-model="newItem.content"
                                      rows="3"
                                      placeholder="I am worthy of all the good things in life..."
                                      class="w-full px-4 py-3 border-2 border-duo-gray-100 rounded-duo focus:border-duo-green focus:ring-0"></textarea>
                        </div>
                        <p class="text-sm text-duo-gray-300">Or choose from your affirmations:</p>
                        <div class="max-h-32 overflow-y-auto space-y-2">
                            @foreach($affirmations as $affirmation)
                                <button type="button"
                                        @click="newItem.content = '{{ addslashes($affirmation->content) }}'"
                                        class="w-full text-left p-2 text-sm border border-duo-gray-100 rounded-lg hover:bg-duo-gray-50 transition-colors">
                                    {{ $affirmation->content }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-3 mt-6">
                        <x-button type="button" color="gray" class="flex-1" @click="showAddModal = false">
                            Cancel
                        </x-button>
                        <x-button type="button" color="green" class="flex-1" @click="addItem()">
                            Add Item
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function visionBoardEditor() {
            return {
                showAddModal: false,
                itemType: 'image',
                newItem: {
                    image_url: '',
                    content: '',
                    dream_id: '',
                },

                init() {
                    // Initialize drag and drop for items
                },

                async addItem() {
                    const data = {
                        type: this.itemType,
                        ...this.newItem,
                        position_x: Math.floor(Math.random() * 300) + 50,
                        position_y: Math.floor(Math.random() * 300) + 50,
                    };

                    try {
                        const response = await fetch('{{ route("vision-board.items.store", $visionBoard) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify(data),
                        });

                        if (response.ok) {
                            window.location.reload();
                        }
                    } catch (error) {
                        console.error('Error adding item:', error);
                    }
                },

                async deleteItem(itemId) {
                    if (!confirm('Delete this item?')) return;

                    try {
                        const response = await fetch(`/vision-board/items/${itemId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                        });

                        if (response.ok) {
                            window.location.reload();
                        }
                    } catch (error) {
                        console.error('Error deleting item:', error);
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
