<?php

namespace App\Http\Controllers;

use App\Models\WeeklyGoal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WeeklyGoalController extends Controller
{
    public function index(Request $request)
    {
        $weekOffset = (int) $request->get('week', 0);
        $currentWeekStart = now()->startOfWeek()->addWeeks($weekOffset);
        $currentWeekEnd = $currentWeekStart->copy()->endOfWeek();

        $goals = auth()->user()->weeklyGoals()
            ->where('week_start_date', $currentWeekStart)
            ->orderBy('is_completed')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total' => $goals->count(),
            'completed' => $goals->where('is_completed', true)->count(),
            'pending' => $goals->where('is_completed', false)->count(),
            'xp_earned' => $goals->where('is_completed', true)->sum('xp_reward'),
        ];

        $categories = WeeklyGoal::getCategories();

        return view('weekly-goals.index', compact('goals', 'stats', 'categories', 'currentWeekStart', 'currentWeekEnd', 'weekOffset'));
    }

    public function create()
    {
        $categories = WeeklyGoal::getCategories();
        $weekStart = now()->startOfWeek();

        return view('weekly-goals.create', compact('categories', 'weekStart'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|in:' . implode(',', array_keys(WeeklyGoal::getCategories())),
            'target_count' => 'required|integer|min:1|max:100',
            'week_start_date' => 'nullable|date',
        ]);

        $weekStart = isset($validated['week_start_date']) && $validated['week_start_date']
            ? Carbon::parse($validated['week_start_date'])->startOfWeek()
            : now()->startOfWeek();

        $goal = auth()->user()->weeklyGoals()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'target_count' => $validated['target_count'],
            'week_start_date' => $weekStart,
            'xp_reward' => $this->calculateXpReward($validated['target_count']),
        ]);

        return redirect()->route('weekly-goals.index')
            ->with('success', 'Weekly goal created!');
    }

    public function edit(WeeklyGoal $weeklyGoal)
    {
        $this->authorizeGoal($weeklyGoal);

        $categories = WeeklyGoal::getCategories();

        return view('weekly-goals.edit', compact('weeklyGoal', 'categories'));
    }

    public function update(Request $request, WeeklyGoal $weeklyGoal)
    {
        $this->authorizeGoal($weeklyGoal);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|string|in:' . implode(',', array_keys(WeeklyGoal::getCategories())),
            'target_count' => 'required|integer|min:1|max:100',
        ]);

        $weeklyGoal->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'target_count' => $validated['target_count'],
            'xp_reward' => $this->calculateXpReward($validated['target_count']),
        ]);

        return redirect()->route('weekly-goals.index')
            ->with('success', 'Weekly goal updated!');
    }

    public function destroy(WeeklyGoal $weeklyGoal)
    {
        $this->authorizeGoal($weeklyGoal);

        $weeklyGoal->delete();

        return redirect()->route('weekly-goals.index')
            ->with('success', 'Weekly goal deleted.');
    }

    public function incrementProgress(WeeklyGoal $weeklyGoal)
    {
        $this->authorizeGoal($weeklyGoal);

        if ($weeklyGoal->is_completed) {
            return back()->with('error', 'This goal is already completed.');
        }

        $weeklyGoal->incrementProgress();

        if ($weeklyGoal->is_completed) {
            return back()
                ->with('success', 'Congratulations! Weekly goal completed!')
                ->with('xp_earned', $weeklyGoal->xp_reward);
        }

        return back()->with('success', 'Progress updated!');
    }

    public function decrementProgress(WeeklyGoal $weeklyGoal)
    {
        $this->authorizeGoal($weeklyGoal);

        if ($weeklyGoal->current_count > 0) {
            $weeklyGoal->decrement('current_count');
        }

        return back()->with('success', 'Progress updated.');
    }

    private function authorizeGoal(WeeklyGoal $weeklyGoal): void
    {
        if ($weeklyGoal->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    private function calculateXpReward(int $targetCount): int
    {
        // Base XP is 30, plus 10 XP per target unit
        return 30 + ($targetCount * 10);
    }
}
