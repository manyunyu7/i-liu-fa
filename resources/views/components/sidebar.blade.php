<!-- Desktop Sidebar -->
<aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 bg-white border-r-2 border-duo-gray-100">
    <!-- Logo -->
    <div class="flex items-center h-16 px-6 border-b-2 border-duo-gray-100">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <span class="text-3xl">✨</span>
            <span class="text-xl font-extrabold text-duo-green">DuoManifest</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="🏠">
            Dashboard
        </x-nav-link>

        <x-nav-link href="{{ route('affirmations.index') }}" :active="request()->routeIs('affirmations.*')" icon="💫">
            Affirmations
        </x-nav-link>

        <x-nav-link href="{{ route('bucket-list.index') }}" :active="request()->routeIs('bucket-list.*')" icon="🎯">
            Bucket List
        </x-nav-link>

        <x-nav-link href="{{ route('dreams.index') }}" :active="request()->routeIs('dreams.*')" icon="🌟">
            Dreams
        </x-nav-link>

        <x-nav-link href="{{ route('planner.index') }}" :active="request()->routeIs('planner.*')" icon="📅">
            Planner
        </x-nav-link>

        <x-nav-link href="{{ route('habits.index') }}" :active="request()->routeIs('habits.*')" icon="🔄">
            Habits
        </x-nav-link>

        <x-nav-link href="{{ route('vision-board.index') }}" :active="request()->routeIs('vision-board.*')" icon="🎨">
            Vision Board
        </x-nav-link>

        <x-nav-link href="{{ route('reflections.index') }}" :active="request()->routeIs('reflections.*')" icon="📝">
            Reflections
        </x-nav-link>

        <x-nav-link href="{{ route('weekly-goals.index') }}" :active="request()->routeIs('weekly-goals.*')" icon="📆">
            Weekly Goals
        </x-nav-link>

        <x-nav-link href="{{ route('quotes.index') }}" :active="request()->routeIs('quotes.*')" icon="💬">
            Quotes
        </x-nav-link>

        <div class="pt-4 mt-4 border-t-2 border-duo-gray-100">
            <x-nav-link href="{{ route('achievements.index') }}" :active="request()->routeIs('achievements.*')" icon="🏆">
                Achievements
            </x-nav-link>

            <x-nav-link href="{{ route('stats.index') }}" :active="request()->routeIs('stats.*')" icon="📊">
                Statistics
            </x-nav-link>

            <x-nav-link href="{{ route('rewards.index') }}" :active="request()->routeIs('rewards.*')" icon="🎁">
                Shop
            </x-nav-link>

            <x-nav-link href="{{ route('streak-freeze.index') }}" :active="request()->routeIs('streak-freeze.*')" icon="🧊">
                Streak Freeze
            </x-nav-link>

            <x-nav-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.*')" icon="👤">
                Profile
            </x-nav-link>

            <x-nav-link href="{{ route('preferences.index') }}" :active="request()->routeIs('preferences.*')" icon="⚙️">
                Settings
            </x-nav-link>
        </div>
    </nav>

    <!-- User Info -->
    <div class="p-4 border-t-2 border-duo-gray-100">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-duo-green flex items-center justify-center text-white font-bold">
                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-duo-gray-500 truncate">{{ auth()->user()->name ?? 'Guest' }}</p>
                <p class="text-xs text-duo-gray-200">Level {{ auth()->user()->level ?? 1 }}</p>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar -->
<aside x-show="sidebarOpen"
       x-transition:enter="transition ease-in-out duration-300 transform"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in-out duration-300 transform"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r-2 border-duo-gray-100 lg:hidden">
    <!-- Logo -->
    <div class="flex items-center justify-between h-16 px-6 border-b-2 border-duo-gray-100">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
            <span class="text-3xl">✨</span>
            <span class="text-xl font-extrabold text-duo-green">DuoManifest</span>
        </a>
        <button @click="sidebarOpen = false" class="text-duo-gray-300 hover:text-duo-gray-500">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="🏠">
            Dashboard
        </x-nav-link>

        <x-nav-link href="{{ route('affirmations.index') }}" :active="request()->routeIs('affirmations.*')" icon="💫">
            Affirmations
        </x-nav-link>

        <x-nav-link href="{{ route('bucket-list.index') }}" :active="request()->routeIs('bucket-list.*')" icon="🎯">
            Bucket List
        </x-nav-link>

        <x-nav-link href="{{ route('dreams.index') }}" :active="request()->routeIs('dreams.*')" icon="🌟">
            Dreams
        </x-nav-link>

        <x-nav-link href="{{ route('planner.index') }}" :active="request()->routeIs('planner.*')" icon="📅">
            Planner
        </x-nav-link>

        <x-nav-link href="{{ route('habits.index') }}" :active="request()->routeIs('habits.*')" icon="🔄">
            Habits
        </x-nav-link>

        <x-nav-link href="{{ route('vision-board.index') }}" :active="request()->routeIs('vision-board.*')" icon="🎨">
            Vision Board
        </x-nav-link>

        <x-nav-link href="{{ route('reflections.index') }}" :active="request()->routeIs('reflections.*')" icon="📝">
            Reflections
        </x-nav-link>

        <x-nav-link href="{{ route('weekly-goals.index') }}" :active="request()->routeIs('weekly-goals.*')" icon="📆">
            Weekly Goals
        </x-nav-link>

        <x-nav-link href="{{ route('quotes.index') }}" :active="request()->routeIs('quotes.*')" icon="💬">
            Quotes
        </x-nav-link>

        <div class="pt-4 mt-4 border-t-2 border-duo-gray-100">
            <x-nav-link href="{{ route('achievements.index') }}" :active="request()->routeIs('achievements.*')" icon="🏆">
                Achievements
            </x-nav-link>

            <x-nav-link href="{{ route('stats.index') }}" :active="request()->routeIs('stats.*')" icon="📊">
                Statistics
            </x-nav-link>

            <x-nav-link href="{{ route('rewards.index') }}" :active="request()->routeIs('rewards.*')" icon="🎁">
                Shop
            </x-nav-link>

            <x-nav-link href="{{ route('streak-freeze.index') }}" :active="request()->routeIs('streak-freeze.*')" icon="🧊">
                Streak Freeze
            </x-nav-link>

            <x-nav-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.*')" icon="👤">
                Profile
            </x-nav-link>

            <x-nav-link href="{{ route('preferences.index') }}" :active="request()->routeIs('preferences.*')" icon="⚙️">
                Settings
            </x-nav-link>
        </div>
    </nav>
</aside>
