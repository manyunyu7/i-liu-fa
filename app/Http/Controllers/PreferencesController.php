<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    public function index()
    {
        $preferences = auth()->user()->preferences ?? [];

        $defaults = [
            'sound_enabled' => true,
            'haptic_enabled' => true,
            'volume' => 0.5,
            'theme' => 'light',
            'animations_enabled' => true,
            'daily_reminders' => true,
            'streak_reminders' => true,
            'achievement_notifications' => true,
        ];

        $preferences = array_merge($defaults, $preferences);

        return view('preferences.index', compact('preferences'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'sound_enabled' => 'nullable|boolean',
            'haptic_enabled' => 'nullable|boolean',
            'volume' => 'nullable|numeric|min:0|max:1',
            'theme' => 'nullable|in:light,dark,auto',
            'animations_enabled' => 'nullable|boolean',
            'daily_reminders' => 'nullable|boolean',
            'streak_reminders' => 'nullable|boolean',
            'achievement_notifications' => 'nullable|boolean',
        ]);

        $user = auth()->user();
        $currentPreferences = $user->preferences ?? [];

        $newPreferences = array_merge($currentPreferences, $request->only([
            'sound_enabled',
            'haptic_enabled',
            'volume',
            'theme',
            'animations_enabled',
            'daily_reminders',
            'streak_reminders',
            'achievement_notifications',
        ]));

        $user->update(['preferences' => $newPreferences]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'preferences' => $newPreferences]);
        }

        return back()->with('success', 'Preferences updated!');
    }

    public function api(Request $request)
    {
        $user = auth()->user();
        $currentPreferences = $user->preferences ?? [];

        $newPreferences = array_merge($currentPreferences, $request->all());
        $user->update(['preferences' => $newPreferences]);

        return response()->json(['success' => true]);
    }
}
