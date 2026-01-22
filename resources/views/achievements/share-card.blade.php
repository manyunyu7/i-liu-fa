<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $user->name }} unlocked {{ $achievement->name }} - DuoManifest</title>

    <!-- Open Graph / Social Media Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $user->name }} unlocked {{ $achievement->name }}!">
    <meta property="og:description" content="{{ $achievement->description }} - Join them on their journey of personal growth with DuoManifest!">
    <meta property="og:image" content="{{ url('/images/share-preview.png') }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $user->name }} unlocked {{ $achievement->name }}!">
    <meta name="twitter:description" content="{{ $achievement->description }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-duo-purple/10 via-duo-blue/10 to-duo-green/10 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full">
        <!-- Share Card -->
        <div class="bg-white rounded-duo shadow-duo-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-duo-yellow via-duo-orange to-duo-red p-6 text-white text-center">
                <div class="text-5xl mb-3">{{ $achievement->icon }}</div>
                <h1 class="text-2xl font-extrabold">Achievement Unlocked!</h1>
            </div>

            <!-- Content -->
            <div class="p-6 text-center">
                <!-- User Info -->
                <div class="flex items-center justify-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-duo-green flex items-center justify-center text-white font-bold text-lg">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                </div>
                <p class="text-duo-gray-400 mb-4">
                    <span class="font-bold text-duo-gray-500">{{ $user->name }}</span> unlocked
                </p>

                <!-- Achievement -->
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
                     style="background-color: {{ $achievement->badge_color }}20">
                    <span class="text-5xl">{{ $achievement->icon }}</span>
                </div>

                <h2 class="text-xl font-extrabold text-duo-gray-500 mb-2">{{ $achievement->name }}</h2>
                <p class="text-duo-gray-300 mb-4">{{ $achievement->description }}</p>

                <!-- Stats -->
                <div class="flex justify-center space-x-6 py-4 border-y border-duo-gray-100">
                    <div>
                        <p class="text-xl font-bold text-duo-yellow">+{{ $achievement->xp_reward }}</p>
                        <p class="text-xs text-duo-gray-300">XP Earned</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-duo-purple">Level {{ $user->level }}</p>
                        <p class="text-xs text-duo-gray-300">Current Level</p>
                    </div>
                    <div>
                        <p class="text-xl font-bold text-duo-orange">{{ $user->current_streak }}</p>
                        <p class="text-xs text-duo-gray-300">Day Streak</p>
                    </div>
                </div>

                <!-- Unlocked Date -->
                <p class="text-sm text-duo-gray-300 mt-4">
                    Unlocked on {{ $userAchievement->unlocked_at->format('F j, Y') }}
                </p>
            </div>

            <!-- Footer -->
            <div class="bg-duo-gray-50 p-4 text-center">
                <p class="text-sm text-duo-gray-400 mb-3">Start your own journey of personal growth</p>
                <a href="{{ route('welcome') }}"
                   class="inline-flex items-center px-6 py-3 bg-duo-green hover:bg-duo-green-dark text-white font-bold rounded-duo transition-colors">
                    <span class="mr-2">✨</span>
                    Join DuoManifest
                </a>
            </div>
        </div>

        <!-- Branding -->
        <div class="text-center mt-6">
            <a href="{{ url('/') }}" class="inline-flex items-center text-duo-gray-400 hover:text-duo-green">
                <span class="text-2xl mr-2">✨</span>
                <span class="font-bold">DuoManifest</span>
            </a>
            <p class="text-xs text-duo-gray-300 mt-1">Manifest Your Dreams, One Step at a Time</p>
        </div>
    </div>
</body>
</html>
