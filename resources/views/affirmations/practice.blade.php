<x-app-layout>
    <x-slot name="title">Practice - {{ $category->name }}</x-slot>

    <div x-data="affirmationPractice()" class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="{{ route('affirmations.index') }}" class="inline-flex items-center text-duo-gray-300 hover:text-duo-gray-400 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Categories
            </a>
            <div class="flex items-center justify-center space-x-2 mb-2">
                <span class="text-4xl">{{ $category->icon }}</span>
                <h1 class="text-2xl font-extrabold text-duo-gray-500">{{ $category->name }}</h1>
            </div>
            <p class="text-duo-gray-300">Repeat each affirmation 3 times with feeling</p>
        </div>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between text-sm font-bold text-duo-gray-300 mb-2">
                <span>Progress</span>
                <span x-text="`${currentIndex + 1} / {{ count($affirmations) }}`"></span>
            </div>
            <div class="h-4 bg-duo-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-duo-green to-duo-green-light rounded-full transition-all duration-500"
                     :style="`width: ${((currentIndex + 1) / {{ count($affirmations) }}) * 100}%`"></div>
            </div>
        </div>

        <!-- Affirmation Card -->
        <div class="relative">
            @foreach($affirmations as $index => $affirmation)
                <div x-show="currentIndex === {{ $index }}"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-8"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     class="bg-white border-2 border-duo-gray-100 rounded-duo-xl p-8 shadow-duo-md">

                    <!-- Affirmation Content -->
                    <div class="text-center mb-8">
                        <p class="text-2xl lg:text-3xl font-bold text-duo-gray-500 leading-relaxed">
                            "{{ $affirmation->content }}"
                        </p>
                    </div>

                    <!-- Repeat Counter -->
                    <div class="flex justify-center space-x-4 mb-8">
                        <template x-for="i in 3" :key="i">
                            <button @click="incrementRepeat({{ $affirmation->id }})"
                                    class="w-12 h-12 rounded-full border-2 flex items-center justify-center transition-all duration-200"
                                    :class="repeats[{{ $affirmation->id }}] >= i
                                        ? 'bg-duo-green border-duo-green text-white scale-110'
                                        : 'border-duo-gray-200 text-duo-gray-200 hover:border-duo-green'">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        </template>
                    </div>

                    <p class="text-center text-duo-gray-300 mb-6">
                        Tap a circle each time you repeat the affirmation
                    </p>

                    <!-- XP Preview -->
                    <div class="text-center">
                        <span class="inline-flex items-center px-4 py-2 rounded-full bg-duo-yellow/20 text-duo-orange font-bold">
                            <span class="mr-2">⚡</span>
                            +10 XP when complete
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between mt-8">
            <button @click="previous()"
                    x-show="currentIndex > 0"
                    class="px-6 py-3 rounded-duo border-2 border-duo-gray-100 font-bold text-duo-gray-300 hover:bg-duo-gray-50 transition-colors">
                Previous
            </button>

            <div x-show="currentIndex === 0"></div>

            <button @click="next()"
                    x-show="currentIndex < {{ count($affirmations) - 1 }}"
                    :disabled="repeats[affirmations[currentIndex].id] < 3"
                    class="px-6 py-3 rounded-duo font-bold transition-all duration-200"
                    :class="repeats[affirmations[currentIndex].id] >= 3
                        ? 'bg-duo-green text-white border-b-4 border-duo-green-dark hover:bg-duo-green-dark'
                        : 'bg-duo-gray-100 text-duo-gray-200 cursor-not-allowed'">
                Continue
            </button>

            <button @click="complete()"
                    x-show="currentIndex === {{ count($affirmations) - 1 }}"
                    :disabled="repeats[affirmations[currentIndex].id] < 3"
                    class="px-6 py-3 rounded-duo font-bold transition-all duration-200"
                    :class="repeats[affirmations[currentIndex].id] >= 3
                        ? 'bg-duo-green text-white border-b-4 border-duo-green-dark hover:bg-duo-green-dark'
                        : 'bg-duo-gray-100 text-duo-gray-200 cursor-not-allowed'">
                Finish Practice
            </button>
        </div>

        <!-- Completion Modal -->
        <div x-show="showComplete"
             x-transition
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white rounded-duo-xl p-8 max-w-md mx-4 text-center">
                <div class="text-6xl mb-4 animate-bounce">🎉</div>
                <h2 class="text-2xl font-extrabold text-duo-gray-500 mb-2">Amazing Work!</h2>
                <p class="text-duo-gray-300 mb-6">You completed your affirmation practice!</p>

                <div class="bg-duo-yellow/20 rounded-duo p-4 mb-6">
                    <div class="text-3xl font-extrabold text-duo-orange">+<span x-text="totalXp"></span> XP</div>
                    <div class="text-sm font-bold text-duo-gray-300">earned this session</div>
                </div>

                <a href="{{ route('affirmations.index') }}">
                    <x-primary-button class="w-full">
                        Continue
                    </x-primary-button>
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function affirmationPractice() {
            return {
                currentIndex: 0,
                repeats: {},
                showComplete: false,
                totalXp: 0,
                startTime: Date.now(),
                affirmations: @json($affirmations->map(fn($a) => ['id' => $a->id])),

                init() {
                    this.affirmations.forEach(a => {
                        this.repeats[a.id] = 0;
                    });
                },

                incrementRepeat(id) {
                    if (this.repeats[id] < 3) {
                        this.repeats[id]++;
                    }
                },

                next() {
                    if (this.repeats[this.affirmations[this.currentIndex].id] >= 3) {
                        this.completeAffirmation(this.affirmations[this.currentIndex].id);
                        this.currentIndex++;
                    }
                },

                previous() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                    }
                },

                async completeAffirmation(id) {
                    const duration = Math.round((Date.now() - this.startTime) / 1000);
                    try {
                        const response = await fetch(`/affirmations/${id}/complete`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ duration })
                        });
                        const data = await response.json();
                        this.totalXp += data.xp_earned;
                    } catch (e) {
                        console.error('Failed to complete affirmation', e);
                    }
                },

                async complete() {
                    if (this.repeats[this.affirmations[this.currentIndex].id] >= 3) {
                        await this.completeAffirmation(this.affirmations[this.currentIndex].id);
                        this.showComplete = true;
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
