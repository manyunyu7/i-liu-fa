<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AffirmationController;
use App\Http\Controllers\BucketListController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DreamController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\PlannerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

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
    Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
