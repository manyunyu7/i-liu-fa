<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AffirmationController;
use App\Http\Controllers\BucketListController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DreamController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\PlannerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisionBoardController;
use App\Http\Controllers\ReflectionController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\StreakFreezeController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\PreferencesController;
use App\Http\Controllers\WeeklyGoalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('welcome');

// Public share routes (no auth required)
Route::get('/share/achievement/{achievement}/{userId}', [AchievementController::class, 'shareCard'])->name('achievements.share-card');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Affirmations
    Route::prefix('affirmations')->name('affirmations.')->group(function () {
        Route::get('/', [AffirmationController::class, 'index'])->name('index');
        Route::get('/create', [AffirmationController::class, 'create'])->name('create');
        Route::post('/', [AffirmationController::class, 'store'])->name('store');
        Route::get('/practice/{category}', [AffirmationController::class, 'practice'])->name('practice');
        Route::post('/{affirmation}/complete', [AffirmationController::class, 'complete'])->name('complete');
        Route::post('/{affirmation}/favorite', [AffirmationController::class, 'toggleFavorite'])->name('favorite');
        Route::delete('/{affirmation}', [AffirmationController::class, 'destroy'])->name('destroy');
    });

    // Bucket List
    Route::prefix('bucket-list')->name('bucket-list.')->group(function () {
        Route::get('/', [BucketListController::class, 'index'])->name('index');
        Route::get('/create', [BucketListController::class, 'create'])->name('create');
        Route::post('/', [BucketListController::class, 'store'])->name('store');
        Route::get('/{bucketList}', [BucketListController::class, 'show'])->name('show');
        Route::get('/{bucketList}/edit', [BucketListController::class, 'edit'])->name('edit');
        Route::put('/{bucketList}', [BucketListController::class, 'update'])->name('update');
        Route::delete('/{bucketList}', [BucketListController::class, 'destroy'])->name('destroy');
        Route::post('/{bucketList}/complete', [BucketListController::class, 'complete'])->name('complete');
        Route::post('/{bucketList}/milestones', [BucketListController::class, 'addMilestone'])->name('milestones.store');
        Route::post('/milestones/{milestone}/toggle', [BucketListController::class, 'toggleMilestone'])->name('milestones.toggle');
    });

    // Dreams
    Route::prefix('dreams')->name('dreams.')->group(function () {
        Route::get('/', [DreamController::class, 'index'])->name('index');
        Route::get('/create', [DreamController::class, 'create'])->name('create');
        Route::post('/', [DreamController::class, 'store'])->name('store');
        Route::get('/{dream}', [DreamController::class, 'show'])->name('show');
        Route::get('/{dream}/edit', [DreamController::class, 'edit'])->name('edit');
        Route::put('/{dream}', [DreamController::class, 'update'])->name('update');
        Route::delete('/{dream}', [DreamController::class, 'destroy'])->name('destroy');
        Route::post('/{dream}/manifest', [DreamController::class, 'manifest'])->name('manifest');
        Route::post('/{dream}/journal', [DreamController::class, 'addJournalEntry'])->name('journal.store');
        Route::delete('/journal/{entry}', [DreamController::class, 'deleteJournalEntry'])->name('journal.destroy');
    });

    // Planner
    Route::prefix('planner')->name('planner.')->group(function () {
        Route::get('/', [PlannerController::class, 'index'])->name('index');
        Route::get('/create', [PlannerController::class, 'create'])->name('create');
        Route::post('/', [PlannerController::class, 'store'])->name('store');
        Route::get('/{planner}/edit', [PlannerController::class, 'edit'])->name('edit');
        Route::put('/{planner}', [PlannerController::class, 'update'])->name('update');
        Route::delete('/{planner}', [PlannerController::class, 'destroy'])->name('destroy');
        Route::post('/{task}/toggle', [PlannerController::class, 'toggle'])->name('toggle');
    });

    // Habits
    Route::prefix('habits')->name('habits.')->group(function () {
        Route::get('/', [HabitController::class, 'index'])->name('index');
        Route::get('/create', [HabitController::class, 'create'])->name('create');
        Route::post('/', [HabitController::class, 'store'])->name('store');
        Route::get('/{habit}/edit', [HabitController::class, 'edit'])->name('edit');
        Route::put('/{habit}', [HabitController::class, 'update'])->name('update');
        Route::delete('/{habit}', [HabitController::class, 'destroy'])->name('destroy');
        Route::post('/{habit}/log', [HabitController::class, 'log'])->name('log');
    });

    // Achievements
    Route::prefix('achievements')->name('achievements.')->group(function () {
        Route::get('/', [AchievementController::class, 'index'])->name('index');
        Route::get('/{achievement}', [AchievementController::class, 'show'])->name('show');
        Route::get('/{achievement}/share', [AchievementController::class, 'share'])->name('share');
    });

    // Vision Board
    Route::prefix('vision-board')->name('vision-board.')->group(function () {
        Route::get('/', [VisionBoardController::class, 'index'])->name('index');
        Route::get('/create', [VisionBoardController::class, 'create'])->name('create');
        Route::post('/', [VisionBoardController::class, 'store'])->name('store');
        Route::get('/{visionBoard}', [VisionBoardController::class, 'show'])->name('show');
        Route::get('/{visionBoard}/edit', [VisionBoardController::class, 'edit'])->name('edit');
        Route::put('/{visionBoard}', [VisionBoardController::class, 'update'])->name('update');
        Route::delete('/{visionBoard}', [VisionBoardController::class, 'destroy'])->name('destroy');
        Route::post('/{visionBoard}/items', [VisionBoardController::class, 'addItem'])->name('items.store');
        Route::put('/items/{item}', [VisionBoardController::class, 'updateItem'])->name('items.update');
        Route::delete('/items/{item}', [VisionBoardController::class, 'deleteItem'])->name('items.destroy');
    });

    // Reflections / Gratitude Journal
    Route::prefix('reflections')->name('reflections.')->group(function () {
        Route::get('/', [ReflectionController::class, 'index'])->name('index');
        Route::get('/create', [ReflectionController::class, 'create'])->name('create');
        Route::post('/', [ReflectionController::class, 'store'])->name('store');
        Route::get('/{reflection}', [ReflectionController::class, 'show'])->name('show');
        Route::get('/{reflection}/edit', [ReflectionController::class, 'edit'])->name('edit');
        Route::put('/{reflection}', [ReflectionController::class, 'update'])->name('update');
        Route::delete('/{reflection}', [ReflectionController::class, 'destroy'])->name('destroy');
    });

    // Rewards Shop
    Route::prefix('rewards')->name('rewards.')->group(function () {
        Route::get('/', [RewardController::class, 'index'])->name('index');
        Route::post('/{reward}/purchase', [RewardController::class, 'purchase'])->name('purchase');
        Route::post('/use/{userReward}', [RewardController::class, 'use'])->name('use');
    });

    // Streak Freeze
    Route::prefix('streak-freeze')->name('streak-freeze.')->group(function () {
        Route::get('/', [StreakFreezeController::class, 'index'])->name('index');
        Route::post('/use', [StreakFreezeController::class, 'use'])->name('use');
        Route::post('/purchase', [StreakFreezeController::class, 'purchase'])->name('purchase');
    });

    // Quotes
    Route::prefix('quotes')->name('quotes.')->group(function () {
        Route::get('/', [QuoteController::class, 'index'])->name('index');
        Route::get('/daily', [QuoteController::class, 'daily'])->name('daily');
        Route::get('/random', [QuoteController::class, 'random'])->name('random');
        Route::get('/favorites', [QuoteController::class, 'favorites'])->name('favorites');
        Route::post('/{quote}/favorite', [QuoteController::class, 'toggleFavorite'])->name('favorite');
    });

    // Weekly Goals
    Route::prefix('weekly-goals')->name('weekly-goals.')->group(function () {
        Route::get('/', [WeeklyGoalController::class, 'index'])->name('index');
        Route::get('/create', [WeeklyGoalController::class, 'create'])->name('create');
        Route::post('/', [WeeklyGoalController::class, 'store'])->name('store');
        Route::get('/{weeklyGoal}/edit', [WeeklyGoalController::class, 'edit'])->name('edit');
        Route::put('/{weeklyGoal}', [WeeklyGoalController::class, 'update'])->name('update');
        Route::delete('/{weeklyGoal}', [WeeklyGoalController::class, 'destroy'])->name('destroy');
        Route::post('/{weeklyGoal}/increment', [WeeklyGoalController::class, 'incrementProgress'])->name('increment');
        Route::post('/{weeklyGoal}/decrement', [WeeklyGoalController::class, 'decrementProgress'])->name('decrement');
    });

    // Statistics / Analytics
    Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');

    // Preferences / Settings
    Route::prefix('preferences')->name('preferences.')->group(function () {
        Route::get('/', [PreferencesController::class, 'index'])->name('index');
        Route::post('/', [PreferencesController::class, 'update'])->name('update');
    });

    // API endpoint for preferences (AJAX)
    Route::post('/api/preferences', [PreferencesController::class, 'api'])->name('api.preferences');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
