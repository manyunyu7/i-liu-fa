<x-app-layout>
    <x-slot name="title">Rewards Shop</x-slot>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-duo-gray-500">Rewards Shop</h1>
        <p class="text-duo-gray-300">Spend your gems on powerful rewards!</p>
    </div>

    <!-- Gems Balance -->
    <div class="mb-8">
        <x-card class="bg-gradient-to-r from-duo-purple to-duo-blue text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm font-medium">Your Balance</p>
                    <p class="text-4xl font-extrabold">{{ number_format(auth()->user()->gems) }}</p>
                    <p class="text-white/80">Gems</p>
                </div>
                <div class="text-6xl">💎</div>
            </div>
        </x-card>
    </div>

    <!-- User's Available Rewards -->
    @if($userRewards->isNotEmpty())
        <div class="mb-8">
            <h2 class="text-xl font-bold text-duo-gray-500 mb-4">Your Rewards</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($userRewards as $userReward)
                    <x-card class="border-2 border-duo-green">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-3xl">{{ $userReward->reward->icon }}</span>
                                <h3 class="font-bold text-duo-gray-500 mt-2">{{ $userReward->reward->name }}</h3>
                                <p class="text-sm text-duo-gray-300">{{ $userReward->reward->description }}</p>
                                @if($userReward->expires_at)
                                    <p class="text-xs text-duo-orange mt-1">Expires: {{ $userReward->expires_at->format('M j, Y') }}</p>
                                @endif
                            </div>
                            <span class="bg-duo-green/10 text-duo-green text-xs font-bold px-2 py-1 rounded-full">Owned</span>
                        </div>
                        @if($userReward->reward->type !== 'streak_freeze')
                            <form action="{{ route('rewards.use', $userReward->id) }}" method="POST" class="mt-4">
                                @csrf
                                <x-button type="submit" color="green" size="sm" class="w-full">
                                    Use Now
                                </x-button>
                            </form>
                        @endif
                    </x-card>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Available Rewards -->
    @foreach($rewards as $type => $typeRewards)
        <div class="mb-8">
            <h2 class="text-xl font-bold text-duo-gray-500 mb-4">
                @switch($type)
                    @case('streak_freeze')
                        🧊 Streak Freezes
                        @break
                    @case('xp_boost')
                        ⚡ XP Boosts
                        @break
                    @case('gems')
                        💎 Gem Packs
                        @break
                    @case('badge')
                        🏅 Badges
                        @break
                    @default
                        🎁 Other Rewards
                @endswitch
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($typeRewards as $reward)
                    <x-card class="hover:border-duo-green transition-colors">
                        <div class="text-center">
                            <span class="text-5xl">{{ $reward->icon }}</span>
                            <h3 class="font-bold text-duo-gray-500 mt-4">{{ $reward->name }}</h3>
                            <p class="text-sm text-duo-gray-300 mt-1">{{ $reward->description }}</p>

                            <div class="mt-4 flex items-center justify-center space-x-1">
                                <span class="text-2xl">💎</span>
                                <span class="text-2xl font-bold text-duo-purple">{{ number_format($reward->cost_gems) }}</span>
                            </div>

                            <form action="{{ route('rewards.purchase', $reward) }}" method="POST" class="mt-4">
                                @csrf
                                @if(auth()->user()->canAfford($reward))
                                    <x-button type="submit" color="purple" size="md" class="w-full">
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
                @endforeach
            </div>
        </div>
    @endforeach

    @if($rewards->isEmpty())
        <x-card class="text-center py-12">
            <span class="text-6xl">🏪</span>
            <h3 class="text-xl font-bold text-duo-gray-500 mt-4">Shop Coming Soon!</h3>
            <p class="text-duo-gray-300 mt-2">Exciting rewards are on the way. Keep earning gems!</p>
        </x-card>
    @endif

    <!-- How to Earn Gems -->
    <div class="mt-8">
        <x-card>
            <h3 class="font-bold text-duo-gray-500 mb-4">💡 How to Earn Gems</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                <div class="flex items-start space-x-3">
                    <span class="text-2xl">🔥</span>
                    <div>
                        <p class="font-bold text-duo-gray-500">Maintain Streaks</p>
                        <p class="text-duo-gray-300">Earn 5 gems per week of streak</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-2xl">🏆</span>
                    <div>
                        <p class="font-bold text-duo-gray-500">Unlock Achievements</p>
                        <p class="text-duo-gray-300">Gems for reaching milestones</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-2xl">⬆️</span>
                    <div>
                        <p class="font-bold text-duo-gray-500">Level Up</p>
                        <p class="text-duo-gray-300">10 gems per level gained</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-2xl">✅</span>
                    <div>
                        <p class="font-bold text-duo-gray-500">Complete Tasks</p>
                        <p class="text-duo-gray-300">Bonus gems for daily completion</p>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</x-app-layout>
