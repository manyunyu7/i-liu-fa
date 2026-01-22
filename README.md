# DuoManifest

A Duolingo-inspired personal growth web application built with Laravel 12. Track your affirmations, bucket list, dreams, daily plans, habits, and more - all with gamification to keep you motivated!

## Features

### Core Modules

- **Affirmations** - Daily affirmation practice with categories and favorites
- **Bucket List** - Goal tracking with milestones and progress visualization
- **Dreams** - Dream manifestation with journaling
- **Daily Planner** - Task management (intentions, goals, habits, tasks)
- **Habit Tracker** - Daily/weekly habit tracking with streaks
- **Vision Board** - Visual dream boards with images, text, quotes, and goals
- **Reflections** - Daily gratitude journal with mood tracking
- **Motivational Quotes** - Daily quotes with favorites

### Gamification

- XP system for completing activities
- 10 levels progression (Dreamer to Legend)
- Streak tracking with streak freeze protection
- 20+ achievements to unlock
- Gems currency for rewards shop
- Social sharing for achievements

### User Experience

- Duolingo-inspired colorful UI
- Sound effects and haptic feedback
- Animated progress indicators (Beads)
- Statistics and analytics dashboard
- Customizable preferences

## Tech Stack

- **Backend**: Laravel 12 (PHP 8.3+)
- **Frontend**: Blade, Tailwind CSS, Alpine.js
- **Authentication**: Laravel Breeze
- **Database**: SQLite/MySQL/PostgreSQL
- **Build Tool**: Vite

## Installation

```bash
# Clone the repository
git clone https://github.com/manyunyu7/i-liu-fa.git
cd duo_manifest

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed the database
php artisan db:seed

# Start development servers
php artisan serve
npm run dev
```

## Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=HabitTest
```

The project includes 216+ tests covering all features.

## Screenshots

The app features a clean, gamified interface inspired by Duolingo:

- Dashboard with daily progress and stats
- Achievement badges with progress tracking
- Streak tracking with freeze protection
- Interactive habit logging
- Vision board creation

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
