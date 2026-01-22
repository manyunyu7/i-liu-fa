<x-app-layout>
    <x-slot name="title">Streak Freezes</x-slot>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-duo-gray-500">Streak Freezes</h1>
        <p class="text-duo-gray-300">Protect your streak when you can't practice!</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Available Freezes -->
            <x-card class="bg-gradient-to-br from-duo-blue to-duo-purple text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 font-medium">Available Streak Freezes</p>
                        <p class="text-5xl font-extrabold mt-2">{{ $available }}</p>
                    </div>
                    <div class="text-7xl">🧊</div>
                </div>

                @if($available > 0)
                    <form action="{{ route('streak-freeze.use') }}" method="POST" class="mt-6">
                        @csrf
                        <x-button type="submit" color="gray" size="lg" class="w-full bg-white/20 hover:bg-white/30 border-white/30">
                            Use Streak Freeze for Today
                        </x-button>
                    </form>
                @else
                    <div class="mt-6">
                        <a href="{{ route('rewards.index') }}">
                            <x-button type="button" color="gray" size="lg" class="w-full bg-white/20 hover:bg-white/30 border-white/30">
                                Get More in the Shop
                            </x-button>
                        </a>
                    </div>
                @endif
            </x-card>

            <!-- How It Works -->
            <x-card>
                <h2 class="text-xl font-bold text-duo-gray-500 mb-4">How Streak Freezes Work</h2>
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-duo-blue/10 rounded-full flex items-center justify-center text-xl">1</div>
                        <div>
                            <p class="font-bold text-duo-gray-500">Use Before Missing a Day</p>
                            <p class="text-sm text-duo-gray-300">Apply a streak freeze when you know you can't practice to protect your streak.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-duo-green/10 rounded-full flex items-center justify-center text-xl">2</div>
                        <div>
                            <p class="font-bold text-duo-gray-500">Streak Stays Intact</p>
                            <p class="text-sm text-duo-gray-300">Your streak won't reset even if you miss that day's activities.</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="w-10 h-10 bg-duo-yellow/10 rounded-full flex items-center justify-center text-xl">3</div>
                        <div>
                            <p class="font-bold text-duo-gray-500">One Per Day</p>
                            <p class="text-sm text-duo-gray-300">You can only use one streak freeze per day, so use them wisely!</p>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- History -->
            @if($freezes->isNotEmpty())
                <x-card>
                    <h2 class="text-xl font-bold text-duo-gray-500 mb-4">Freeze History</h2>
                    <div class="space-y-3">
                        @foreach($freezes as $freeze)
                            <div class="flex items-center justify-between p-3 bg-duo-gray-50 rounded-duo">
                                <div class="flex items-center space-x-3">
                                    <span class="text-2xl">🧊</span>
                                    <div>
                                        <p class="font-medium text-duo-gray-500">{{ $freeze->freeze_date->format('l, F j, Y') }}</p>
                                        <p class="text-sm text-duo-gray-300">Type: {{ ucfirst($freeze->type) }}</p>
                                    </div>
                                </div>
                                <span class="text-sm {{ $freeze->is_used ? 'text-duo-green' : 'text-duo-orange' }}">
                                    {{ $freeze->is_used ? 'Used' : 'Active' }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{ $freezes->links() }}
                </x-card>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Stats -->
            <x-card>
                <h3 class="font-bold text-duo-gray-500 mb-4">Statistics</h3>
                <dl class="space-y-3">
                    <div class="flex justify-between">
                        <dt class="text-duo-gray-300">Available</dt>
                        <dd class="font-bold text-duo-blue">{{ $available }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-duo-gray-300">Total Used</dt>
                        <dd class="font-bold text-duo-gray-500">{{ $used }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-duo-gray-300">Current Streak</dt>
                        <dd class="font-bold text-duo-orange">{{ auth()->user()->current_streak }} days 🔥</dd>
                    </div>
                </dl>
            </x-card>

            <!-- Purchase More -->
            <x-card class="border-2 border-duo-purple">
                <h3 class="font-bold text-duo-gray-500 mb-4">Need More Freezes?</h3>
                <p class="text-sm text-duo-gray-300 mb-4">Visit the shop to purchase streak freezes with your gems!</p>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-duo-gray-500">Your gems:</span>
                    <span class="font-bold text-duo-purple">💎 {{ number_format(auth()->user()->gems) }}</span>
                </div>
                <a href="{{ route('rewards.index') }}">
                    <x-button type="button" color="purple" size="md" class="w-full">
                        Visit Shop
                    </x-button>
                </a>
            </x-card>

            <!-- Quick Purchase -->
            @php
                $freezeReward = \App\Models\Reward::where('type', 'streak_freeze')->active()->first();
            @endphp
            @if($freezeReward)
                <x-card>
                    <h3 class="font-bold text-duo-gray-500 mb-4">Quick Purchase</h3>
                    <div class="text-center">
                        <span class="text-5xl">🧊</span>
                        <p class="font-bold text-duo-gray-500 mt-2">{{ $freezeReward->name }}</p>
                        <div class="flex items-center justify-center space-x-1 mt-2">
                            <span class="text-xl">💎</span>
                            <span class="text-xl font-bold text-duo-purple">{{ $freezeReward->cost_gems }}</span>
                        </div>
                        <form action="{{ route('streak-freeze.purchase') }}" method="POST" class="mt-4">
                            @csrf
                            @if(auth()->user()->gems >= $freezeReward->cost_gems)
                                <x-button type="submit" color="green" size="md" class="w-full">
                                    Purchase
                                </x-button>
                            @else
                                <x-button type="button" color="gray" size="md" class="w-full opacity-50" disabled>
                                    Not Enough Gems
                                </x-button>
                            @endif
                        </form>
                    </div>
                </x-card>
            @endif
        </div>
    </div>
</x-app-layout>
