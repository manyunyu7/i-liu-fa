<x-app-layout>
    <x-slot name="title">Settings</x-slot>

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-duo-gray-500">Settings</h1>
                <p class="text-duo-gray-300 mt-1">Customize your DuoManifest experience</p>
            </div>
        </div>

        <!-- Settings Form -->
        <form action="{{ route('preferences.update') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Sound & Haptics -->
            <div class="bg-white rounded-duo shadow-duo p-6">
                <h2 class="text-lg font-bold text-duo-gray-500 flex items-center mb-4">
                    <span class="mr-2">🔊</span> Sound & Haptics
                </h2>

                <div class="space-y-4">
                    <!-- Sound Toggle -->
                    <div class="flex items-center justify-between py-3 border-b border-duo-gray-100"
                         x-data="{ enabled: {{ $preferences['sound_enabled'] ? 'true' : 'false' }} }">
                        <div>
                            <p class="font-bold text-duo-gray-500">Sound Effects</p>
                            <p class="text-sm text-duo-gray-300">Play sounds for interactions and achievements</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="sound_enabled" value="1" x-model="enabled"
                                   class="sr-only peer" {{ $preferences['sound_enabled'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-duo-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-duo-green"></div>
                        </label>
                    </div>

                    <!-- Volume Slider -->
                    <div class="py-3 border-b border-duo-gray-100"
                         x-data="{ volume: {{ $preferences['volume'] }} }">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <p class="font-bold text-duo-gray-500">Volume</p>
                                <p class="text-sm text-duo-gray-300">Adjust sound effect volume</p>
                            </div>
                            <span class="text-sm font-bold text-duo-green" x-text="Math.round(volume * 100) + '%'"></span>
                        </div>
                        <input type="range" name="volume" min="0" max="1" step="0.1" x-model="volume"
                               class="w-full h-2 bg-duo-gray-100 rounded-lg appearance-none cursor-pointer accent-duo-green">
                    </div>

                    <!-- Haptic Toggle -->
                    <div class="flex items-center justify-between py-3"
                         x-data="{ enabled: {{ $preferences['haptic_enabled'] ? 'true' : 'false' }} }">
                        <div>
                            <p class="font-bold text-duo-gray-500">Haptic Feedback</p>
                            <p class="text-sm text-duo-gray-300">Vibration feedback on mobile devices</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="haptic_enabled" value="1" x-model="enabled"
                                   class="sr-only peer" {{ $preferences['haptic_enabled'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-duo-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-duo-green"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Appearance -->
            <div class="bg-white rounded-duo shadow-duo p-6">
                <h2 class="text-lg font-bold text-duo-gray-500 flex items-center mb-4">
                    <span class="mr-2">🎨</span> Appearance
                </h2>

                <div class="space-y-4">
                    <!-- Theme -->
                    <div class="py-3 border-b border-duo-gray-100">
                        <p class="font-bold text-duo-gray-500 mb-2">Theme</p>
                        <p class="text-sm text-duo-gray-300 mb-3">Choose your preferred color scheme</p>
                        <div class="flex space-x-3">
                            @foreach(['light' => 'Light', 'dark' => 'Dark', 'auto' => 'Auto'] as $value => $label)
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="theme" value="{{ $value }}" class="sr-only peer"
                                           {{ $preferences['theme'] === $value ? 'checked' : '' }}>
                                    <div class="px-4 py-3 text-center rounded-duo border-2 border-duo-gray-100 peer-checked:border-duo-green peer-checked:bg-duo-green/5 transition-all">
                                        <span class="text-xl mb-1 block">
                                            @if($value === 'light') ☀️
                                            @elseif($value === 'dark') 🌙
                                            @else 🔄
                                            @endif
                                        </span>
                                        <span class="text-sm font-bold text-duo-gray-400">{{ $label }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Animations Toggle -->
                    <div class="flex items-center justify-between py-3"
                         x-data="{ enabled: {{ $preferences['animations_enabled'] ? 'true' : 'false' }} }">
                        <div>
                            <p class="font-bold text-duo-gray-500">Animations</p>
                            <p class="text-sm text-duo-gray-300">Enable UI animations and transitions</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="animations_enabled" value="1" x-model="enabled"
                                   class="sr-only peer" {{ $preferences['animations_enabled'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-duo-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-duo-green"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="bg-white rounded-duo shadow-duo p-6">
                <h2 class="text-lg font-bold text-duo-gray-500 flex items-center mb-4">
                    <span class="mr-2">🔔</span> Notifications
                </h2>

                <div class="space-y-4">
                    <!-- Daily Reminders -->
                    <div class="flex items-center justify-between py-3 border-b border-duo-gray-100"
                         x-data="{ enabled: {{ $preferences['daily_reminders'] ? 'true' : 'false' }} }">
                        <div>
                            <p class="font-bold text-duo-gray-500">Daily Reminders</p>
                            <p class="text-sm text-duo-gray-300">Get reminded to complete your daily tasks</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="daily_reminders" value="1" x-model="enabled"
                                   class="sr-only peer" {{ $preferences['daily_reminders'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-duo-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-duo-green"></div>
                        </label>
                    </div>

                    <!-- Streak Reminders -->
                    <div class="flex items-center justify-between py-3 border-b border-duo-gray-100"
                         x-data="{ enabled: {{ $preferences['streak_reminders'] ? 'true' : 'false' }} }">
                        <div>
                            <p class="font-bold text-duo-gray-500">Streak Reminders</p>
                            <p class="text-sm text-duo-gray-300">Get notified when your streak is at risk</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="streak_reminders" value="1" x-model="enabled"
                                   class="sr-only peer" {{ $preferences['streak_reminders'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-duo-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-duo-green"></div>
                        </label>
                    </div>

                    <!-- Achievement Notifications -->
                    <div class="flex items-center justify-between py-3"
                         x-data="{ enabled: {{ $preferences['achievement_notifications'] ? 'true' : 'false' }} }">
                        <div>
                            <p class="font-bold text-duo-gray-500">Achievement Notifications</p>
                            <p class="text-sm text-duo-gray-300">Get notified when you unlock achievements</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="achievement_notifications" value="1" x-model="enabled"
                                   class="sr-only peer" {{ $preferences['achievement_notifications'] ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-duo-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-duo-green"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Sound Test -->
            <div class="bg-white rounded-duo shadow-duo p-6">
                <h2 class="text-lg font-bold text-duo-gray-500 flex items-center mb-4">
                    <span class="mr-2">🎵</span> Sound Preview
                </h2>

                <p class="text-sm text-duo-gray-300 mb-4">Test the different sound effects</p>

                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="playSound('click')"
                            class="px-3 py-2 bg-duo-gray-100 hover:bg-duo-gray-200 rounded-duo text-sm font-bold text-duo-gray-400 transition-colors">
                        Click
                    </button>
                    <button type="button" onclick="playSound('success')"
                            class="px-3 py-2 bg-duo-green/10 hover:bg-duo-green/20 rounded-duo text-sm font-bold text-duo-green transition-colors">
                        Success
                    </button>
                    <button type="button" onclick="playSound('complete')"
                            class="px-3 py-2 bg-duo-blue/10 hover:bg-duo-blue/20 rounded-duo text-sm font-bold text-duo-blue transition-colors">
                        Complete
                    </button>
                    <button type="button" onclick="playSound('levelUp')"
                            class="px-3 py-2 bg-duo-purple/10 hover:bg-duo-purple/20 rounded-duo text-sm font-bold text-duo-purple transition-colors">
                        Level Up
                    </button>
                    <button type="button" onclick="playSound('reward')"
                            class="px-3 py-2 bg-duo-yellow/10 hover:bg-duo-yellow/20 rounded-duo text-sm font-bold text-duo-yellow transition-colors">
                        Reward
                    </button>
                    <button type="button" onclick="playSound('streak')"
                            class="px-3 py-2 bg-duo-orange/10 hover:bg-duo-orange/20 rounded-duo text-sm font-bold text-duo-orange transition-colors">
                        Streak
                    </button>
                    <button type="button" onclick="playSound('xp')"
                            class="px-3 py-2 bg-duo-yellow/10 hover:bg-duo-yellow/20 rounded-duo text-sm font-bold text-duo-yellow transition-colors">
                        XP
                    </button>
                    <button type="button" onclick="playSound('error')"
                            class="px-3 py-2 bg-duo-red/10 hover:bg-duo-red/20 rounded-duo text-sm font-bold text-duo-red transition-colors">
                        Error
                    </button>
                    <button type="button" onclick="playSound('notification')"
                            class="px-3 py-2 bg-duo-blue/10 hover:bg-duo-blue/20 rounded-duo text-sm font-bold text-duo-blue transition-colors">
                        Notification
                    </button>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-3 bg-duo-green hover:bg-duo-green-dark text-white font-bold rounded-duo shadow-duo transition-all transform hover:scale-105">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
