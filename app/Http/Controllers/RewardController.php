<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::active()
            ->purchasable()
            ->orderBy('sort_order')
            ->get()
            ->groupBy('type');

        $userRewards = auth()->user()->userRewards()
            ->with('reward')
            ->whereNull('used_at')
            ->get();

        return view('rewards.index', compact('rewards', 'userRewards'));
    }

    public function purchase(Reward $reward)
    {
        if (!$reward->is_active || !$reward->is_purchasable) {
            return back()->with('error', 'This reward is not available.');
        }

        if (!auth()->user()->canAfford($reward)) {
            return back()->with('error', 'You don\'t have enough gems to purchase this reward.');
        }

        $userReward = auth()->user()->purchaseReward($reward);

        if (!$userReward) {
            return back()->with('error', 'Failed to purchase reward.');
        }

        // Apply immediate effects based on reward type
        $this->applyRewardEffect($reward, $userReward);

        return back()->with('success', "You purchased {$reward->name}!");
    }

    public function use(int $userRewardId)
    {
        $userReward = auth()->user()->userRewards()->findOrFail($userRewardId);

        if ($userReward->used_at) {
            return back()->with('error', 'This reward has already been used.');
        }

        if ($userReward->isExpired()) {
            return back()->with('error', 'This reward has expired.');
        }

        $this->applyRewardEffect($userReward->reward, $userReward);
        $userReward->use();

        return back()->with('success', "You used {$userReward->reward->name}!");
    }

    private function applyRewardEffect(Reward $reward, $userReward): void
    {
        switch ($reward->type) {
            case 'streak_freeze':
                auth()->user()->increment('streak_freezes_available');
                $userReward->update(['used_at' => now()]);
                break;

            case 'xp_boost':
                $amount = $reward->metadata['amount'] ?? 50;
                auth()->user()->addXp($amount, 'reward', $reward->id, "XP Boost reward");
                $userReward->update(['used_at' => now()]);
                break;

            case 'gems':
                $amount = $reward->metadata['amount'] ?? 10;
                auth()->user()->addGems($amount);
                $userReward->update(['used_at' => now()]);
                break;
        }
    }
}
