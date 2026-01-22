<?php

namespace App\Http\Controllers;

use App\Models\BucketListCategory;
use App\Models\BucketListItem;
use App\Models\BucketListMilestone;
use Illuminate\Http\Request;

class BucketListController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->bucketListItems()->with('category', 'milestones');

        if ($request->filled('category')) {
            $query->where('bucket_list_category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $items = $query->orderBy('status')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = BucketListCategory::forUser(auth()->id())->orderBy('sort_order')->get();

        $stats = [
            'total' => auth()->user()->bucketListItems()->count(),
            'completed' => auth()->user()->bucketListItems()->completed()->count(),
            'in_progress' => auth()->user()->bucketListItems()->inProgress()->count(),
        ];

        return view('bucket-list.index', compact('items', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = BucketListCategory::forUser(auth()->id())->orderBy('sort_order')->get();

        return view('bucket-list.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bucket_list_category_id' => 'required|exists:bucket_list_categories,id',
            'target_date' => 'nullable|date|after:today',
            'priority' => 'required|in:low,medium,high',
            'milestones' => 'nullable|array',
            'milestones.*' => 'string|max:255',
        ]);

        $item = auth()->user()->bucketListItems()->create([
            'bucket_list_category_id' => $request->bucket_list_category_id,
            'title' => $request->title,
            'description' => $request->description,
            'target_date' => $request->target_date,
            'priority' => $request->priority,
            'status' => 'pending',
            'xp_reward' => match ($request->priority) {
                'high' => 150,
                'medium' => 100,
                'low' => 75,
            },
        ]);

        if ($request->filled('milestones')) {
            foreach (array_filter($request->milestones) as $index => $milestone) {
                $item->milestones()->create([
                    'title' => $milestone,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('bucket-list.show', $item)
            ->with('success', 'Bucket list item created!');
    }

    public function show(BucketListItem $bucketList)
    {
        if ($bucketList->user_id !== auth()->id()) {
            abort(403);
        }

        $bucketList->load('category', 'milestones');

        return view('bucket-list.show', ['item' => $bucketList]);
    }

    public function edit(BucketListItem $bucketList)
    {
        if ($bucketList->user_id !== auth()->id()) {
            abort(403);
        }

        $categories = BucketListCategory::forUser(auth()->id())->orderBy('sort_order')->get();

        return view('bucket-list.edit', ['item' => $bucketList, 'categories' => $categories]);
    }

    public function update(Request $request, BucketListItem $bucketList)
    {
        if ($bucketList->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bucket_list_category_id' => 'required|exists:bucket_list_categories,id',
            'target_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $bucketList->update($request->only([
            'title',
            'description',
            'bucket_list_category_id',
            'target_date',
            'priority',
            'progress',
        ]));

        if ($request->progress == 100 && $bucketList->status !== 'completed') {
            $bucketList->markAsCompleted();
            return redirect()->route('bucket-list.show', $bucketList)
                ->with('success', 'Congratulations! You completed this goal!')
                ->with('xp_earned', $bucketList->xp_reward);
        }

        return redirect()->route('bucket-list.show', $bucketList)
            ->with('success', 'Bucket list item updated!');
    }

    public function destroy(BucketListItem $bucketList)
    {
        if ($bucketList->user_id !== auth()->id()) {
            abort(403);
        }

        $bucketList->delete();

        return redirect()->route('bucket-list.index')
            ->with('success', 'Bucket list item deleted!');
    }

    public function complete(BucketListItem $bucketList)
    {
        if ($bucketList->user_id !== auth()->id()) {
            abort(403);
        }

        $bucketList->markAsCompleted();

        return redirect()->route('bucket-list.show', $bucketList)
            ->with('success', 'Congratulations! You completed this goal!')
            ->with('xp_earned', $bucketList->xp_reward);
    }

    public function toggleMilestone(BucketListMilestone $milestone)
    {
        $item = $milestone->item;

        if ($item->user_id !== auth()->id()) {
            abort(403);
        }

        $milestone->toggle();

        $xpEarned = null;
        if ($item->fresh()->status === 'completed') {
            $xpEarned = $item->xp_reward;
        }

        return back()
            ->with('success', $milestone->is_completed ? 'Milestone completed!' : 'Milestone uncompleted')
            ->with('xp_earned', $xpEarned);
    }

    public function addMilestone(Request $request, BucketListItem $bucketList)
    {
        if ($bucketList->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $maxOrder = $bucketList->milestones()->max('sort_order') ?? 0;

        $bucketList->milestones()->create([
            'title' => $request->title,
            'sort_order' => $maxOrder + 1,
        ]);

        return back()->with('success', 'Milestone added!');
    }
}
