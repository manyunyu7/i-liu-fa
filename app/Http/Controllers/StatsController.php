<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $period = $request->get('period', '30'); // days

        // Overall stats
        $overallStats = [
            'total_xp' => $user->total_xp,
            'current_level' => $user->level,
            'current_streak' => $user->current_streak,
            'longest_streak' => $user->longest_streak,
            'gems' => $user->gems,
            'member_since' => $user->created_at->diffForHumans(),
        ];

        // Activity stats
        $activityStats = $this->getActivityStats($user, (int) $period);

        // Feature usage stats
        $featureStats = $this->getFeatureStats($user);

        // XP breakdown
        $xpBreakdown = $this->getXpBreakdown($user, (int) $period);

        // Mood trends (from reflections)
        $moodTrends = $this->getMoodTrends($user, (int) $period);

        // Weekly activity heatmap
        $weeklyActivity = $this->getWeeklyActivity($user);

        // Achievement progress
        $achievementStats = $this->getAchievementStats($user);

        return view('stats.index', compact(
            'overallStats',
            'activityStats',
            'featureStats',
            'xpBreakdown',
            'moodTrends',
            'weeklyActivity',
            'achievementStats',
            'period'
        ));
    }

    private function getActivityStats($user, int $days): array
    {
        $startDate = now()->subDays($days);

        return [
            'affirmations_completed' => $user->affirmationSessions()
                ->where('created_at', '>=', $startDate)
                ->count(),
            'habits_logged' => $user->habits()
                ->withCount(['logs' => fn($q) => $q->where('log_date', '>=', $startDate)])
                ->get()
                ->sum('logs_count'),
            'tasks_completed' => $user->plannerTasks()
                ->where('completed_at', '>=', $startDate)
                ->count(),
            'reflections_written' => $user->reflections()
                ->where('created_at', '>=', $startDate)
                ->count(),
            'dreams_manifested' => $user->dreams()
                ->where('manifestation_date', '>=', $startDate)
                ->count(),
            'bucket_items_completed' => $user->bucketListItems()
                ->where('completed_at', '>=', $startDate)
                ->count(),
        ];
    }

    private function getFeatureStats($user): array
    {
        return [
            'total_affirmations' => $user->affirmations()->count(),
            'total_habits' => $user->habits()->count(),
            'active_habits' => $user->habits()->where('is_active', true)->count(),
            'total_dreams' => $user->dreams()->count(),
            'manifested_dreams' => $user->dreams()->where('status', 'manifested')->count(),
            'bucket_list_items' => $user->bucketListItems()->count(),
            'bucket_list_completed' => $user->bucketListItems()->whereNotNull('completed_at')->count(),
            'vision_boards' => $user->visionBoards()->count(),
            'favorite_quotes' => $user->favoriteQuotes()->count(),
        ];
    }

    private function getXpBreakdown($user, int $days): array
    {
        $startDate = now()->subDays($days);

        $transactions = $user->xpTransactions()
            ->where('created_at', '>=', $startDate)
            ->select('source_type', DB::raw('SUM(amount) as total'))
            ->groupBy('source_type')
            ->get()
            ->pluck('total', 'source_type')
            ->toArray();

        $labels = [
            'affirmation' => 'Affirmations',
            'habit' => 'Habits',
            'planner' => 'Tasks',
            'dream' => 'Dreams',
            'bucket_list' => 'Bucket List',
            'reflection' => 'Reflections',
            'achievement' => 'Achievements',
            'reward' => 'Rewards',
        ];

        $breakdown = [];
        foreach ($labels as $key => $label) {
            $breakdown[$label] = $transactions[$key] ?? 0;
        }

        return $breakdown;
    }

    private function getMoodTrends($user, int $days): array
    {
        $startDate = now()->subDays($days);

        $reflections = $user->reflections()
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('mood_score')
            ->orderBy('reflection_date')
            ->get(['reflection_date', 'mood_score', 'mood']);

        $dailyScores = $reflections->groupBy(fn($r) => $r->reflection_date->format('Y-m-d'))
            ->map(fn($group) => round($group->avg('mood_score'), 1));

        $moodCounts = $reflections->groupBy('mood')
            ->map(fn($group) => $group->count())
            ->toArray();

        return [
            'daily_scores' => $dailyScores->toArray(),
            'mood_distribution' => $moodCounts,
            'average_score' => $reflections->avg('mood_score'),
        ];
    }

    private function getWeeklyActivity($user): array
    {
        $activity = [];
        $startOfWeek = now()->startOfWeek();

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $dayName = $date->format('D');

            $count = 0;
            $count += $user->affirmationSessions()->whereDate('created_at', $date)->count();
            $count += $user->plannerTasks()->whereDate('completed_at', $date)->count();
            $count += $user->reflections()->whereDate('reflection_date', $date)->count();

            foreach ($user->habits as $habit) {
                $count += $habit->logs()->whereDate('log_date', $date)->count();
            }

            $activity[$dayName] = $count;
        }

        return $activity;
    }

    private function getAchievementStats($user): array
    {
        $totalAchievements = \App\Models\Achievement::count();
        $unlockedCount = $user->achievements()->count();

        return [
            'total' => $totalAchievements,
            'unlocked' => $unlockedCount,
            'percentage' => $totalAchievements > 0 ? round(($unlockedCount / $totalAchievements) * 100) : 0,
            'recent' => $user->achievements()
                ->with('achievement')
                ->orderBy('unlocked_at', 'desc')
                ->limit(5)
                ->get(),
        ];
    }
}
