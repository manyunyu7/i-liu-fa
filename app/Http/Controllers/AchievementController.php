<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\UserAchievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Check for new achievements
        Achievement::all()->each(fn ($achievement) => $achievement->checkAndUnlock($user));

        $unlockedIds = $user->achievements()->pluck('achievement_id');

        $achievements = Achievement::all()->map(function ($achievement) use ($unlockedIds, $user) {
            $achievement->is_unlocked = $unlockedIds->contains($achievement->id);
            $achievement->unlocked_at = $user->achievements()
                ->where('achievement_id', $achievement->id)
                ->first()
                ?->unlocked_at;

            // Calculate progress
            $achievement->progress = match ($achievement->requirement_type) {
                'streak_days', 'streak' => min(100, ($user->current_streak / $achievement->requirement_value) * 100),
                'total_xp', 'xp_total' => min(100, ($user->total_xp / $achievement->requirement_value) * 100),
                'level' => min(100, ($user->level / $achievement->requirement_value) * 100),
                'affirmations_completed' => min(100, ($user->affirmationSessions()->count() / $achievement->requirement_value) * 100),
                'bucket_list_completed' => min(100, ($user->bucketListItems()->completed()->count() / $achievement->requirement_value) * 100),
                'dreams_manifested' => min(100, ($user->dreams()->manifested()->count() / $achievement->requirement_value) * 100),
                'planner_tasks_completed' => min(100, ($user->plannerTasks()->completed()->count() / $achievement->requirement_value) * 100),
                default => 0,
            };

            return $achievement;
        });

        $stats = [
            'total' => $achievements->count(),
            'unlocked' => $achievements->where('is_unlocked', true)->count(),
            'total_xp_from_achievements' => $user->xpTransactions()
                ->where('source_type', 'achievement')
                ->sum('amount'),
        ];

        $groupedAchievements = $achievements->groupBy('category');

        return view('achievements.index', compact('groupedAchievements', 'stats'));
    }

    public function show(Achievement $achievement)
    {
        $user = auth()->user();
        $userAchievement = $user->achievements()
            ->where('achievement_id', $achievement->id)
            ->first();

        $achievement->is_unlocked = $userAchievement !== null;
        $achievement->unlocked_at = $userAchievement?->unlocked_at;

        // Calculate progress
        $achievement->progress = match ($achievement->requirement_type) {
            'streak_days', 'streak' => min(100, ($user->current_streak / $achievement->requirement_value) * 100),
            'total_xp', 'xp_total' => min(100, ($user->total_xp / $achievement->requirement_value) * 100),
            'level' => min(100, ($user->level / $achievement->requirement_value) * 100),
            'affirmations_completed' => min(100, ($user->affirmationSessions()->count() / $achievement->requirement_value) * 100),
            'bucket_list_completed' => min(100, ($user->bucketListItems()->completed()->count() / $achievement->requirement_value) * 100),
            'dreams_manifested' => min(100, ($user->dreams()->manifested()->count() / $achievement->requirement_value) * 100),
            'planner_tasks_completed' => min(100, ($user->plannerTasks()->completed()->count() / $achievement->requirement_value) * 100),
            default => 0,
        };

        return view('achievements.show', compact('achievement', 'user'));
    }

    public function share(Achievement $achievement)
    {
        $user = auth()->user();
        $userAchievement = $user->achievements()
            ->where('achievement_id', $achievement->id)
            ->first();

        if (!$userAchievement) {
            return redirect()->route('achievements.index')
                ->with('error', 'You can only share achievements you have unlocked.');
        }

        return view('achievements.share', compact('achievement', 'userAchievement', 'user'));
    }

    public function shareCard(Achievement $achievement, ?int $userId = null)
    {
        // Public share card - can be viewed by anyone
        $user = $userId ? \App\Models\User::find($userId) : auth()->user();

        if (!$user) {
            abort(404);
        }

        $userAchievement = UserAchievement::where('user_id', $user->id)
            ->where('achievement_id', $achievement->id)
            ->first();

        if (!$userAchievement) {
            abort(404, 'Achievement not unlocked');
        }

        return view('achievements.share-card', compact('achievement', 'userAchievement', 'user'));
    }
}
