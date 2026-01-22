<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;

class HabitController extends Controller
{
    public function index()
    {
        $habits = auth()->user()->habits()
            ->with(['logs' => function ($query) {
                $query->whereBetween('log_date', [now()->subDays(7), now()])
                    ->orderBy('log_date', 'desc');
            }])
            ->orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total' => $habits->count(),
            'active' => $habits->where('is_active', true)->count(),
            'completed_today' => $habits->where('completed_today', true)->count(),
        ];

        return view('habits.index', compact('habits', 'stats'));
    }

    public function create()
    {
        return view('habits.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency' => 'required|in:daily,weekly',
            'target_count' => 'required|integer|min:1|max:100',
            'icon' => 'nullable|string|max:10',
            'color' => 'required|string|max:20',
        ]);

        auth()->user()->habits()->create([
            'name' => $request->name,
            'description' => $request->description,
            'frequency' => $request->frequency,
            'target_count' => $request->target_count,
            'icon' => $request->icon ?? '✓',
            'color' => $request->color,
            'xp_per_completion' => 10,
            'is_active' => true,
        ]);

        return redirect()->route('habits.index')
            ->with('success', 'Habit created! Start tracking!');
    }

    public function edit(Habit $habit)
    {
        if ($habit->user_id !== auth()->id()) {
            abort(403);
        }

        return view('habits.edit', compact('habit'));
    }

    public function update(Request $request, Habit $habit)
    {
        if ($habit->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'frequency' => 'required|in:daily,weekly',
            'target_count' => 'required|integer|min:1|max:100',
            'icon' => 'nullable|string|max:10',
            'color' => 'required|string|max:20',
            'is_active' => 'boolean',
        ]);

        $habit->update([
            'name' => $request->name,
            'description' => $request->description,
            'frequency' => $request->frequency,
            'target_count' => $request->target_count,
            'icon' => $request->icon ?? '✓',
            'color' => $request->color,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('habits.index')
            ->with('success', 'Habit updated!');
    }

    public function destroy(Habit $habit)
    {
        if ($habit->user_id !== auth()->id()) {
            abort(403);
        }

        $habit->delete();

        return redirect()->route('habits.index')
            ->with('success', 'Habit deleted!');
    }

    public function log(Habit $habit)
    {
        if ($habit->user_id !== auth()->id()) {
            abort(403);
        }

        $log = $habit->log();

        $xpEarned = null;
        if ($log->count === $habit->target_count) {
            $xpEarned = $habit->xp_per_completion;
        }

        return back()
            ->with('success', "Progress: {$log->count}/{$habit->target_count}")
            ->with('xp_earned', $xpEarned);
    }
}
