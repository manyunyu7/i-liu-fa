<!-- Top Header -->
<header class="sticky top-0 z-10 bg-white border-b-2 border-duo-gray-100">
    <div class="flex items-center justify-between h-16 px-4 lg:px-8">
        <!-- Mobile Menu Button -->
        <button @click="sidebarOpen = true" class="p-2 text-duo-gray-300 lg:hidden">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Page Title (Mobile) -->
        <div class="lg:hidden">
            <span class="text-xl font-bold text-duo-gray-500">{{ $title ?? 'Dashboard' }}</span>
        </div>

        <!-- Stats -->
        <div class="hidden lg:flex items-center space-x-6">
            <!-- Streak -->
            <div class="flex items-center space-x-2">
                <span class="text-2xl">🔥</span>
                <div>
                    <span class="text-lg font-bold text-duo-orange">{{ auth()->user()->current_streak ?? 0 }}</span>
                    <span class="text-sm text-duo-gray-200">day streak</span>
                </div>
            </div>

            <!-- XP -->
            <div class="flex items-center space-x-2">
                <span class="text-2xl">⚡</span>
                <div>
                    <span class="text-lg font-bold text-duo-yellow">{{ number_format(auth()->user()->total_xp ?? 0) }}</span>
                    <span class="text-sm text-duo-gray-200">XP</span>
                </div>
            </div>

            <!-- Level -->
            <div class="flex items-center space-x-2">
                <span class="text-2xl">👑</span>
                <div>
                    <span class="text-lg font-bold text-duo-purple">Level {{ auth()->user()->level ?? 1 }}</span>
                </div>
            </div>
        </div>

        <!-- Right Side -->
        <div class="flex items-center space-x-4">
            <!-- Mobile Stats -->
            <div class="flex items-center space-x-3 lg:hidden">
                <div class="flex items-center">
                    <span class="text-lg">🔥</span>
                    <span class="text-sm font-bold text-duo-orange ml-1">{{ auth()->user()->current_streak ?? 0 }}</span>
                </div>
                <div class="flex items-center">
                    <span class="text-lg">⚡</span>
                    <span class="text-sm font-bold text-duo-yellow ml-1">{{ auth()->user()->total_xp ?? 0 }}</span>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-duo hover:bg-duo-gray-50">
                    <div class="w-8 h-8 rounded-full bg-duo-green flex items-center justify-center text-white font-bold text-sm">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <svg class="w-4 h-4 text-duo-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open"
                     @click.away="open = false"
                     x-transition
                     class="absolute right-0 mt-2 w-48 bg-white rounded-duo shadow-duo-lg border-2 border-duo-gray-100 py-2">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-duo-gray-400 hover:bg-duo-gray-50">
                        Profile Settings
                    </a>
                    <a href="{{ route('achievements.index') }}" class="block px-4 py-2 text-sm text-duo-gray-400 hover:bg-duo-gray-50">
                        My Achievements
                    </a>
                    <hr class="my-2 border-duo-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-duo-red hover:bg-duo-gray-50">
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Level Progress Bar -->
    @auth
        <div class="px-4 pb-2 lg:px-8">
            <div class="flex items-center space-x-2">
                <span class="text-xs font-bold text-duo-gray-300">Level {{ auth()->user()->level }}</span>
                <div class="flex-1 h-2 bg-duo-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-duo-green to-duo-green-light rounded-full transition-all duration-500"
                         style="width: {{ auth()->user()->level_progress ?? 0 }}%"></div>
                </div>
                <span class="text-xs font-bold text-duo-gray-300">{{ auth()->user()->xp_to_next_level ?? 0 }} XP to next</span>
            </div>
        </div>
    @endauth
</header>
