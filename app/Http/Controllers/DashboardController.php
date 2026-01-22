<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'streak' => $user->current_streak,
            'total_xp' => $user->total_xp,
            'level' => $user->level,
            'affirmations_today' => $user->affirmationSessions()
                ->whereDate('completed_at', today())
                ->count(),
            'tasks_completed_today' => $user->plannerTasks()
                ->whereDate('completed_at', today())
                ->where('is_completed', true)
                ->count(),
            'tasks_total_today' => $user->plannerTasks()
                ->whereDate('task_date', today())
                ->count(),
            'bucket_list_completed' => $user->bucketListItems()
                ->whereNotNull('completed_at')
                ->count(),
            'bucket_list_total' => $user->bucketListItems()->count(),
            'dreams_manifested' => $user->dreams()
                ->where('status', 'manifested')
                ->count(),
            'dreams_total' => $user->dreams()->count(),
        ];

        $todayTasks = $user->plannerTasks()
            ->whereDate('task_date', today())
            ->orderBy('is_completed')
            ->orderBy('priority', 'desc')
            ->take(5)
            ->get();

        $activeHabits = $user->habits()
            ->active()
            ->with(['logs' => function ($query) {
                $query->whereDate('log_date', today());
            }])
            ->take(5)
            ->get();

        $recentAchievements = $user->achievements()
            ->with('achievement')
            ->latest('unlocked_at')
            ->take(3)
            ->get();

        // Check for new achievements
        Achievement::all()->each(fn ($achievement) => $achievement->checkAndUnlock($user));

        return view('dashboard', compact(
            'stats',
            'todayTasks',
            'activeHabits',
            'recentAchievements'
        ));
    }
}
