# DuoManifest - Personal Growth App

A Duolingo-inspired web application for personal growth, featuring affirmations, bucket lists, dreams, daily planning, habit tracking, vision boards, reflections, and more - all with gamification.

## Tech Stack

- **Framework**: Laravel 12 (PHP 8.3+)
- **Frontend**: Blade templates with Tailwind CSS
- **JavaScript**: Alpine.js for interactivity
- **Authentication**: Laravel Breeze
- **Database**: SQLite (default) / MySQL / PostgreSQL
- **Build**: Vite

## Project Structure

```
app/
├── Http/Controllers/       # Feature controllers
├── Models/                 # Eloquent models
└── View/Components/        # Blade components

resources/views/
├── achievements/           # Achievement views
├── affirmations/           # Affirmation views
├── bucket-list/            # Bucket list views
├── components/             # Reusable UI components
├── dreams/                 # Dream views
├── habits/                 # Habit views
├── layouts/                # App layouts
├── planner/                # Planner views
├── quotes/                 # Motivational quotes views
├── reflections/            # Daily reflection views
├── rewards/                # Shop/rewards views
├── stats/                  # Statistics/analytics views
├── streak-freezes/         # Streak freeze views
├── vision-board/           # Vision board views
└── welcome.blade.php       # Landing page

database/
├── factories/              # Model factories
├── migrations/             # Database migrations
└── seeders/                # Data seeders

docs/planning/              # Project documentation
└── *.md                    # Planning docs

tests/
├── Feature/                # Feature tests (229+ tests)
└── Unit/                   # Unit tests
```

## Key Features

### Modules
1. **Affirmations** - Daily affirmation practice with categories, favorites, and session tracking
2. **Bucket List** - Goal tracking with milestones and progress
3. **Dreams** - Dream manifestation with journaling
4. **Planner** - Daily task management (intentions, goals, habits, tasks)
5. **Habits** - Daily/weekly habit tracking with streaks
6. **Vision Board** - Visual dream boards with images, text, quotes, and goals
7. **Reflections** - Daily gratitude journal with morning/evening reflections and mood tracking
8. **Quotes** - Motivational quote library with daily quotes and favorites
9. **Statistics** - Analytics dashboard with activity tracking and XP breakdown
10. **Weekly Goals** - Weekly goal tracking with progress and categories

### Gamification
- XP system for completing activities
- 10 levels (Dreamer → Legend)
- Streak tracking with streak freeze protection
- 20+ achievements
- Gems currency for rewards shop
- Streak freeze system

## Development Commands

```bash
# Install dependencies
composer install
npm install

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Run development server
php artisan serve
npm run dev

# Run tests
php artisan test

# Build for production
npm run build
```

## Database Schema

Key tables:
- `users` - Extended with gamification fields (level, total_xp, current_streak, gems, streak_freezes_available)
- `affirmations`, `affirmation_categories`, `affirmation_sessions`
- `bucket_list_items`, `bucket_list_categories`, `bucket_list_milestones`
- `dreams`, `dream_categories`, `dream_journal_entries`
- `planner_tasks`
- `habits`, `habit_logs`
- `achievements`, `user_achievements`
- `levels`, `streaks`, `xp_transactions`
- `vision_boards`, `vision_board_items`
- `reflections`
- `quotes`, `quote_user`, `daily_quotes`
- `rewards`, `user_rewards`, `streak_freezes`
- `weekly_goals`

## UI Design

Duolingo-inspired color palette in `tailwind.config.js`:
- Primary Green: `#58CC02`
- Blue: `#1CB0F6`
- Purple: `#CE82FF`
- Yellow/Gold: `#FFC800`
- Orange: `#FF9600`
- Red: `#FF4B4B`

## Routes

| Route | Description |
|-------|-------------|
| `/` | Landing page |
| `/dashboard` | Main dashboard |
| `/affirmations` | Affirmations module |
| `/bucket-list` | Bucket list module |
| `/dreams` | Dreams module |
| `/planner` | Daily planner |
| `/habits` | Habit tracker |
| `/vision-board` | Vision boards |
| `/reflections` | Daily reflections/gratitude |
| `/quotes` | Motivational quotes |
| `/achievements` | Achievements page |
| `/rewards` | Shop/rewards |
| `/streak-freeze` | Streak freeze management |
| `/stats` | Statistics & analytics |
| `/preferences` | Settings (sound, theme, notifications) |
| `/weekly-goals` | Weekly goal tracking |
| `/share/achievement/{id}/{userId}` | Public achievement share cards |

## Testing

Tests are located in `tests/` directory:
- 229+ Feature tests for all controllers
- Unit tests for models (User, Habit, BucketListItem, Dream, PlannerTask, Achievement)

Run specific test groups:
```bash
php artisan test --filter=HabitTest
php artisan test --filter=UserTest
php artisan test --filter=VisionBoardTest
php artisan test --filter=ReflectionTest
```

## Code Style

- PSR-12 coding standard
- Blade components for reusable UI
- Alpine.js for client-side interactivity
- Form validation in controllers
- Authorization checks via policies

## Environment Setup

Copy `.env.example` to `.env` and configure:
```
APP_NAME=DuoManifest
DB_CONNECTION=sqlite  # or mysql/pgsql
```

Generate app key:
```bash
php artisan key:generate
```

## Recent Updates (January 2026)

- Added Vision Board feature with customizable themes and item types
- Added Daily Reflections/Gratitude Journal with mood tracking
- Implemented Rewards Shop with gems currency
- Added Streak Freeze system to protect streaks
- Created Motivational Quotes library with daily quotes
- Built comprehensive Statistics/Analytics dashboard
- Enhanced Beads progress tracker with animations and visual effects
- Added Sound Effects & Haptic Feedback system (Web Audio API)
- Added User Preferences/Settings page (sound, haptics, theme, notifications)
- Implemented Social Sharing for achievements with share cards
- Added Weekly Goals feature with progress tracking
- 229+ passing tests covering all features
