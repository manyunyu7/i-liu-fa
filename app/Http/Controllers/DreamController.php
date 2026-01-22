<?php

namespace App\Http\Controllers;

use App\Models\Dream;
use App\Models\DreamCategory;
use App\Models\DreamJournalEntry;
use Illuminate\Http\Request;

class DreamController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->dreams()->with('category', 'journalEntries');

        if ($request->filled('category')) {
            $query->where('dream_category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $dreams = $query->orderByRaw("FIELD(status, 'manifesting', 'dreaming', 'manifested')")
            ->latest()
            ->paginate(12);

        $categories = DreamCategory::orderBy('sort_order')->get();

        $stats = [
            'total' => auth()->user()->dreams()->count(),
            'dreaming' => auth()->user()->dreams()->dreaming()->count(),
            'manifesting' => auth()->user()->dreams()->manifesting()->count(),
            'manifested' => auth()->user()->dreams()->manifested()->count(),
        ];

        return view('dreams.index', compact('dreams', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = DreamCategory::orderBy('sort_order')->get();

        return view('dreams.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'dream_category_id' => 'required|exists:dream_categories,id',
            'affirmation' => 'nullable|string|max:500',
        ]);

        $dream = auth()->user()->dreams()->create([
            'dream_category_id' => $request->dream_category_id,
            'title' => $request->title,
            'description' => $request->description,
            'affirmation' => $request->affirmation,
            'status' => 'dreaming',
            'xp_reward' => 200,
        ]);

        return redirect()->route('dreams.show', $dream)
            ->with('success', 'Dream created! Start manifesting!');
    }

    public function show(Dream $dream)
    {
        if ($dream->user_id !== auth()->id()) {
            abort(403);
        }

        $dream->load('category', 'journalEntries');

        return view('dreams.show', compact('dream'));
    }

    public function edit(Dream $dream)
    {
        if ($dream->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = DreamCategory::orderBy('sort_order')->get();

        return view('dreams.edit', compact('dream', 'categories'));
    }

    public function update(Request $request, Dream $dream)
    {
        if ($dream->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'dream_category_id' => 'required|exists:dream_categories,id',
            'affirmation' => 'nullable|string|max:500',
            'status' => 'required|in:dreaming,manifesting,manifested',
        ]);

        $oldStatus = $dream->status;

        $dream->update($request->only([
            'title',
            'description',
            'dream_category_id',
            'affirmation',
            'status',
        ]));

        if ($request->status === 'manifested' && $oldStatus !== 'manifested') {
            $dream->manifest();
            return redirect()->route('dreams.show', $dream)
                ->with('success', 'Congratulations! Your dream has manifested!')
                ->with('xp_earned', $dream->xp_reward);
        }

        return redirect()->route('dreams.show', $dream)
            ->with('success', 'Dream updated!');
    }

    public function destroy(Dream $dream)
    {
        if ($dream->user_id !== auth()->id()) {
            abort(403);
        }

        $dream->delete();

        return redirect()->route('dreams.index')
            ->with('success', 'Dream deleted!');
    }

    public function manifest(Dream $dream)
    {
        if ($dream->user_id !== auth()->id()) {
            abort(403);
        }

        if ($dream->status === 'manifested') {
            return back()->with('error', 'This dream is already manifested!');
        }

        $dream->manifest();

        return redirect()->route('dreams.show', $dream)
            ->with('success', 'Congratulations! Your dream has manifested!')
            ->with('xp_earned', $dream->xp_reward);
    }

    public function addJournalEntry(Request $request, Dream $dream)
    {
        if ($dream->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|string',
            'mood' => 'nullable|string|max:50',
        ]);

        $dream->journalEntries()->create([
            'content' => $request->content,
            'mood' => $request->mood,
        ]);

        // Give XP for journaling
        auth()->user()->addXp(5, 'dream', $dream->id, "Journaled about: {$dream->title}");

        return back()
            ->with('success', 'Journal entry added!')
            ->with('xp_earned', 5);
    }

    public function deleteJournalEntry(DreamJournalEntry $entry)
    {
        $dream = $entry->dream;

        if ($dream->user_id !== auth()->id()) {
            abort(403);
        }

        $entry->delete();

        return back()->with('success', 'Journal entry deleted!');
    }
}
