<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DuoManifest - Manifest Your Dreams</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-duo-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b-2 border-duo-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-3xl mr-2">✨</span>
                        <span class="text-2xl font-extrabold text-duo-green">DuoManifest</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="font-bold text-duo-gray-400 hover:text-duo-green transition-colors">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2 bg-duo-green text-white font-bold rounded-duo border-b-4 border-duo-green-dark hover:bg-duo-green-dark transition-colors">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="text-center">
                    <h1 class="text-5xl lg:text-7xl font-extrabold text-duo-gray-500 mb-6">
                        Manifest Your
                        <span class="text-duo-green">Dreams</span>
                    </h1>
                    <p class="text-xl text-duo-gray-300 max-w-2xl mx-auto mb-8">
                        A Duolingo-inspired app for affirmations, bucket lists, dreams, and daily planning.
                        Make personal growth fun and rewarding!
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-duo-green text-white font-bold text-lg rounded-duo border-b-4 border-duo-green-dark hover:bg-duo-green-dark transition-all transform hover:-translate-y-1">
                            Start Your Journey - It's Free!
                        </a>
                    </div>
                </div>

                <!-- Stats Preview -->
                <div class="mt-16 grid grid-cols-2 lg:grid-cols-4 gap-4 max-w-4xl mx-auto">
                    <div class="bg-white rounded-duo p-6 text-center border-2 border-duo-gray-100">
                        <span class="text-4xl block mb-2">💫</span>
                        <span class="text-3xl font-extrabold text-duo-green">48</span>
                        <span class="block text-duo-gray-300 font-bold">Affirmations</span>
                    </div>
                    <div class="bg-white rounded-duo p-6 text-center border-2 border-duo-gray-100">
                        <span class="text-4xl block mb-2">🎯</span>
                        <span class="text-3xl font-extrabold text-duo-blue">8</span>
                        <span class="block text-duo-gray-300 font-bold">Bucket List Categories</span>
                    </div>
                    <div class="bg-white rounded-duo p-6 text-center border-2 border-duo-gray-100">
                        <span class="text-4xl block mb-2">🏆</span>
                        <span class="text-3xl font-extrabold text-duo-yellow">20</span>
                        <span class="block text-duo-gray-300 font-bold">Achievements</span>
                    </div>
                    <div class="bg-white rounded-duo p-6 text-center border-2 border-duo-gray-100">
                        <span class="text-4xl block mb-2">🔥</span>
                        <span class="text-3xl font-extrabold text-duo-orange">∞</span>
                        <span class="block text-duo-gray-300 font-bold">Streak Days</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="bg-white py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl font-extrabold text-duo-gray-500 text-center mb-16">
                    Everything You Need to <span class="text-duo-purple">Manifest</span>
                </h2>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Affirmations -->
                    <div class="bg-duo-gray-50 rounded-duo-xl p-8">
                        <span class="text-5xl block mb-4">💫</span>
                        <h3 class="text-xl font-bold text-duo-gray-500 mb-2">Daily Affirmations</h3>
                        <p class="text-duo-gray-300">
                            Practice powerful affirmations daily. Build confidence, attract wealth, improve health, and manifest love.
                        </p>
                    </div>

                    <!-- Bucket List -->
                    <div class="bg-duo-gray-50 rounded-duo-xl p-8">
                        <span class="text-5xl block mb-4">🎯</span>
                        <h3 class="text-xl font-bold text-duo-gray-500 mb-2">Bucket List Tracker</h3>
                        <p class="text-duo-gray-300">
                            Track your life goals with milestones, progress tracking, and celebration when you complete them.
                        </p>
                    </div>

                    <!-- Dreams -->
                    <div class="bg-duo-gray-50 rounded-duo-xl p-8">
                        <span class="text-5xl block mb-4">🌟</span>
                        <h3 class="text-xl font-bold text-duo-gray-500 mb-2">Dream Manifestation</h3>
                        <p class="text-duo-gray-300">
                            Visualize your dreams, journal your progress, and mark them as manifested when they come true.
                        </p>
                    </div>

                    <!-- Planner -->
                    <div class="bg-duo-gray-50 rounded-duo-xl p-8">
                        <span class="text-5xl block mb-4">📅</span>
                        <h3 class="text-xl font-bold text-duo-gray-500 mb-2">Daily Planner</h3>
                        <p class="text-duo-gray-300">
                            Plan your days with intentions, goals, and tasks. Stay organized and earn XP for completion.
                        </p>
                    </div>

                    <!-- Habits -->
                    <div class="bg-duo-gray-50 rounded-duo-xl p-8">
                        <span class="text-5xl block mb-4">🔄</span>
                        <h3 class="text-xl font-bold text-duo-gray-500 mb-2">Habit Tracker</h3>
                        <p class="text-duo-gray-300">
                            Build positive habits with daily tracking, streaks, and progress visualization.
                        </p>
                    </div>

                    <!-- Gamification -->
                    <div class="bg-duo-gray-50 rounded-duo-xl p-8">
                        <span class="text-5xl block mb-4">🏆</span>
                        <h3 class="text-xl font-bold text-duo-gray-500 mb-2">Gamification</h3>
                        <p class="text-duo-gray-300">
                            Earn XP, unlock achievements, build streaks, and level up as you progress on your journey.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-duo-green to-duo-green-light py-24">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <h2 class="text-4xl font-extrabold text-white mb-6">
                    Ready to Start Manifesting?
                </h2>
                <p class="text-xl text-white/80 mb-8">
                    Join thousands of others who are transforming their lives one affirmation at a time.
                </p>
                <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-white text-duo-green font-bold text-lg rounded-duo border-b-4 border-duo-gray-100 hover:bg-duo-gray-50 transition-all transform hover:-translate-y-1">
                    Create Your Free Account
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t-2 border-duo-gray-100 py-12">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <div class="flex items-center justify-center mb-4">
                    <span class="text-2xl mr-2">✨</span>
                    <span class="text-xl font-extrabold text-duo-green">DuoManifest</span>
                </div>
                <p class="text-duo-gray-300">
                    Made with love for dreamers everywhere.
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
