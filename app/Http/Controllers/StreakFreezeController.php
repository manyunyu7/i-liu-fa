<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StreakFreezeController extends Controller
{
    public function index()
    {
        $freezes = auth()->user()->streakFreezes()
            ->orderBy('freeze_date', 'desc')
            ->paginate(20);

        $available = auth()->user()->streak_freezes_available;
        $used = auth()->user()->streakFreezes()->count();

        return view('streak-freezes.index', compact('freezes', 'available', 'used'));
    }

    public function use(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
        ]);

        $date = $request->date ?? today()->format('Y-m-d');

        if (!auth()->user()->useStreakFreeze($date)) {
            return back()->with('error', 'Unable to use streak freeze. You may not have any available or already used one for this date.');
        }

        return back()->with('success', 'Streak freeze applied successfully!');
    }

    public function purchase()
    {
        if (!auth()->user()->purchaseStreakFreeze()) {
            return back()->with('error', 'Unable to purchase streak freeze. You may not have enough gems.');
        }

        return back()->with('success', 'Streak freeze purchased!');
    }
}
