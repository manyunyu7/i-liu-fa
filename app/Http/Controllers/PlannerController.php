<?php

namespace App\Http\Controllers;

use App\Models\PlannerTask;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlannerController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->filled('date')
            ? Carbon::parse($request->date)
            : today();

        $view = $request->get('view', 'day');

        switch ($view) {
            case 'week':
                $startDate = $date->copy()->startOfWeek();
                $endDate = $date->copy()->endOfWeek();
                break;
            case 'month':
                $startDate = $date->copy()->startOfMonth();
                $endDate = $date->copy()->endOfMonth();
                break;
            default:
                $startDate = $date->copy()->startOfDay();
                $endDate = $date->copy()->endOfDay();
        }

        $tasks = auth()->user()->plannerTasks()
            ->whereBetween('task_date', [$startDate, $endDate])
            ->orderBy('task_date')
            ->orderBy('is_completed')
            ->orderBy('priority', 'desc')
            ->get()
            ->groupBy(fn ($task) => $task->task_date->format('Y-m-d'));

        $stats = [
            'total_today' => auth()->user()->plannerTasks()->today()->count(),
            'completed_today' => auth()->user()->plannerTasks()->today()->completed()->count(),
            'pending_today' => auth()->user()->plannerTasks()->today()->pending()->count(),
        ];

        return view('planner.index', compact('tasks', 'date', 'view', 'startDate', 'endDate', 'stats'));
    }

    public function create(Request $request)
    {
        $date = $request->filled('date') ? Carbon::parse($request->date) : today();

        return view('planner.create', compact('date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'task_date' => 'required|date',
            'task_type' => 'required|in:intention,goal,habit,task',
            'priority' => 'required|in:low,medium,high',
            'is_recurring' => 'boolean',
            'recurrence_pattern' => 'nullable|array',
        ]);

        $xpReward = match ($request->task_type) {
            'intention' => 20,
            'goal' => 25,
            'habit' => 10,
            'task' => 15,
        };

        auth()->user()->plannerTasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'task_date' => $request->task_date,
            'task_type' => $request->task_type,
            'priority' => $request->priority,
            'is_recurring' => $request->boolean('is_recurring'),
            'recurrence_pattern' => $request->recurrence_pattern,
            'xp_reward' => $xpReward,
        ]);

        return redirect()->route('planner.index', ['date' => $request->task_date])
            ->with('success', 'Task created!');
    }

    public function edit(PlannerTask $planner)
    {
        if ($planner->user_id !== auth()->id()) {
            abort(403);
        }

        return view('planner.edit', ['task' => $planner]);
    }

    public function update(Request $request, PlannerTask $planner)
    {
        if ($planner->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'task_date' => 'required|date',
            'task_type' => 'required|in:intention,goal,habit,task',
            'priority' => 'required|in:low,medium,high',
        ]);

        $planner->update($request->only([
            'title',
            'description',
            'task_date',
            'task_type',
            'priority',
        ]));

        return redirect()->route('planner.index', ['date' => $planner->task_date->format('Y-m-d')])
            ->with('success', 'Task updated!');
    }

    public function destroy(PlannerTask $planner)
    {
        if ($planner->user_id !== auth()->id()) {
            abort(403);
        }

        $date = $planner->task_date->format('Y-m-d');
        $planner->delete();

        return redirect()->route('planner.index', ['date' => $date])
            ->with('success', 'Task deleted!');
    }

    public function toggle(PlannerTask $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }

        if ($task->is_completed) {
            $task->uncomplete();
            return back()->with('success', 'Task marked as incomplete');
        }

        $task->complete();

        return back()
            ->with('success', 'Task completed! Great job!')
            ->with('xp_earned', $task->xp_reward);
    }
}
