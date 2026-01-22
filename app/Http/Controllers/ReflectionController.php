<?php

namespace App\Http\Controllers;

use App\Models\Reflection;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReflectionController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->filled('date')
            ? Carbon::parse($request->date)
            : today();

        $reflections = auth()->user()->reflections()
            ->whereMonth('reflection_date', $date->month)
            ->whereYear('reflection_date', $date->year)
            ->orderBy('reflection_date', 'desc')
            ->get()
            ->groupBy(fn($r) => $r->reflection_date->format('Y-m-d'));

        $todayReflections = auth()->user()->reflections()->today()->get();
        $hasMorning = $todayReflections->where('type', 'morning')->isNotEmpty();
        $hasEvening = $todayReflections->where('type', 'evening')->isNotEmpty();
        $hasGratitude = $todayReflections->where('type', 'gratitude')->isNotEmpty();

        $stats = [
            'total_reflections' => auth()->user()->reflections()->count(),
            'this_month' => auth()->user()->reflections()
                ->whereMonth('reflection_date', now()->month)
                ->count(),
            'streak' => $this->calculateReflectionStreak(),
            'avg_mood' => auth()->user()->reflections()
                ->whereNotNull('mood_score')
                ->avg('mood_score') ?? 0,
        ];

        $moods = [
            'happy' => '😊',
            'grateful' => '🙏',
            'calm' => '😌',
            'energized' => '⚡',
            'motivated' => '💪',
            'peaceful' => '☮️',
            'anxious' => '😰',
            'tired' => '😴',
            'sad' => '😢',
            'stressed' => '😫',
            'neutral' => '😐',
        ];

        return view('reflections.index', compact(
            'reflections',
            'date',
            'hasMorning',
            'hasEvening',
            'hasGratitude',
            'stats',
            'moods'
        ));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'gratitude');
        $date = $request->filled('date') ? Carbon::parse($request->date) : today();

        $moods = [
            'happy' => '😊 Happy',
            'grateful' => '🙏 Grateful',
            'calm' => '😌 Calm',
            'energized' => '⚡ Energized',
            'motivated' => '💪 Motivated',
            'peaceful' => '☮️ Peaceful',
            'anxious' => '😰 Anxious',
            'tired' => '😴 Tired',
            'sad' => '😢 Sad',
            'stressed' => '😫 Stressed',
            'neutral' => '😐 Neutral',
        ];

        return view('reflections.create', compact('type', 'date', 'moods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:morning,evening,gratitude,general',
            'reflection_date' => 'required|date',
            'mood' => 'nullable|string',
            'mood_score' => 'nullable|integer|min:1|max:10',
            'gratitude_items' => 'nullable|array',
            'gratitude_items.*' => 'string|max:500',
            'highlights' => 'nullable|string|max:2000',
            'challenges' => 'nullable|string|max:2000',
            'lessons' => 'nullable|string|max:2000',
            'intentions' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:5000',
        ]);

        // Check if reflection already exists for this date and type
        $existing = auth()->user()->reflections()
            ->whereDate('reflection_date', $request->reflection_date)
            ->where('type', $request->type)
            ->first();

        if ($existing) {
            return redirect()->route('reflections.edit', $existing)
                ->with('info', 'You already have a reflection for this. Edit it instead.');
        }

        $xpReward = match($request->type) {
            'morning' => 15,
            'evening' => 20,
            'gratitude' => 10,
            default => 10,
        };

        $reflection = auth()->user()->reflections()->create([
            'reflection_date' => $request->reflection_date,
            'type' => $request->type,
            'mood' => $request->mood,
            'mood_score' => $request->mood_score,
            'gratitude_items' => array_filter($request->gratitude_items ?? []),
            'highlights' => $request->highlights,
            'challenges' => $request->challenges,
            'lessons' => $request->lessons,
            'intentions' => $request->intentions,
            'notes' => $request->notes,
            'xp_earned' => $xpReward,
        ]);

        auth()->user()->addXp($xpReward, 'reflection', $reflection->id, "Completed {$request->type} reflection");
        auth()->user()->updateStreak('reflection');

        return redirect()->route('reflections.index')
            ->with('success', 'Reflection saved! Keep up the great work!')
            ->with('xp_earned', $xpReward);
    }

    public function show(Reflection $reflection)
    {
        if ($reflection->user_id !== auth()->id()) {
            abort(403);
        }

        return view('reflections.show', compact('reflection'));
    }

    public function edit(Reflection $reflection)
    {
        if ($reflection->user_id !== auth()->id()) {
            abort(403);
        }

        $moods = [
            'happy' => '😊 Happy',
            'grateful' => '🙏 Grateful',
            'calm' => '😌 Calm',
            'energized' => '⚡ Energized',
            'motivated' => '💪 Motivated',
            'peaceful' => '☮️ Peaceful',
            'anxious' => '😰 Anxious',
            'tired' => '😴 Tired',
            'sad' => '😢 Sad',
            'stressed' => '😫 Stressed',
            'neutral' => '😐 Neutral',
        ];

        return view('reflections.edit', compact('reflection', 'moods'));
    }

    public function update(Request $request, Reflection $reflection)
    {
        if ($reflection->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'mood' => 'nullable|string',
            'mood_score' => 'nullable|integer|min:1|max:10',
            'gratitude_items' => 'nullable|array',
            'gratitude_items.*' => 'string|max:500',
            'highlights' => 'nullable|string|max:2000',
            'challenges' => 'nullable|string|max:2000',
            'lessons' => 'nullable|string|max:2000',
            'intentions' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:5000',
        ]);

        $reflection->update([
            'mood' => $request->mood,
            'mood_score' => $request->mood_score,
            'gratitude_items' => array_filter($request->gratitude_items ?? []),
            'highlights' => $request->highlights,
            'challenges' => $request->challenges,
            'lessons' => $request->lessons,
            'intentions' => $request->intentions,
            'notes' => $request->notes,
        ]);

        return redirect()->route('reflections.show', $reflection)
            ->with('success', 'Reflection updated!');
    }

    public function destroy(Reflection $reflection)
    {
        if ($reflection->user_id !== auth()->id()) {
            abort(403);
        }

        $reflection->delete();

        return redirect()->route('reflections.index')
            ->with('success', 'Reflection deleted.');
    }

    private function calculateReflectionStreak(): int
    {
        $streak = 0;
        $date = today();

        while (true) {
            $hasReflection = auth()->user()->reflections()
                ->whereDate('reflection_date', $date)
                ->exists();

            if (!$hasReflection) {
                break;
            }

            $streak++;
            $date = $date->subDay();
        }

        return $streak;
    }
}
