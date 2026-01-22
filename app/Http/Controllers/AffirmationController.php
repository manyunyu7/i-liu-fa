<?php

namespace App\Http\Controllers;

use App\Models\Affirmation;
use App\Models\AffirmationCategory;
use App\Models\AffirmationSession;
use Illuminate\Http\Request;

class AffirmationController extends Controller
{
    public function index()
    {
        $categories = AffirmationCategory::withCount(['affirmations' => function ($query) {
            $query->where('is_active', true)
                  ->where(function ($q) {
                      $q->where('is_system', true)
                        ->orWhere('user_id', auth()->id());
                  });
        }])->orderBy('sort_order')->get();

        $todaySessions = auth()->user()->affirmationSessions()
            ->whereDate('completed_at', today())
            ->count();

        $streak = auth()->user()->getStreak('affirmation');

        return view('affirmations.index', compact('categories', 'todaySessions', 'streak'));
    }

    public function practice(AffirmationCategory $category)
    {
        $affirmations = Affirmation::where('affirmation_category_id', $category->id)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('is_system', true)
                  ->orWhere('user_id', auth()->id());
            })
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('affirmations.practice', compact('category', 'affirmations'));
    }

    public function complete(Request $request, Affirmation $affirmation)
    {
        $request->validate([
            'duration' => 'required|integer|min:1',
        ]);

        $xpEarned = 10;

        AffirmationSession::create([
            'user_id' => auth()->id(),
            'affirmation_id' => $affirmation->id,
            'completed_at' => now(),
            'xp_earned' => $xpEarned,
            'session_duration' => $request->duration,
        ]);

        $user = auth()->user();
        $user->addXp($xpEarned, 'affirmation', $affirmation->id, "Practiced: {$affirmation->content}");
        $user->updateStreak('affirmation');

        return response()->json([
            'success' => true,
            'xp_earned' => $xpEarned,
            'total_xp' => $user->fresh()->total_xp,
            'streak' => $user->fresh()->current_streak,
        ]);
    }

    public function create()
    {
        $categories = AffirmationCategory::orderBy('sort_order')->get();

        return view('affirmations.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:500',
            'affirmation_category_id' => 'required|exists:affirmation_categories,id',
        ]);

        auth()->user()->affirmations()->create([
            'affirmation_category_id' => $request->affirmation_category_id,
            'content' => $request->content,
            'is_system' => false,
            'is_active' => true,
        ]);

        return redirect()->route('affirmations.index')
            ->with('success', 'Affirmation created successfully!');
    }

    public function toggleFavorite(Affirmation $affirmation)
    {
        if ($affirmation->user_id !== auth()->id() && !$affirmation->is_system) {
            abort(403);
        }

        $affirmation->update(['is_favorite' => !$affirmation->is_favorite]);

        return back()->with('success', 'Affirmation updated!');
    }

    public function destroy(Affirmation $affirmation)
    {
        if ($affirmation->user_id !== auth()->id()) {
            abort(403);
        }

        $affirmation->delete();

        return back()->with('success', 'Affirmation deleted!');
    }
}
