<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WeeklyGoal;

class WeeklyGoalPolicy
{
    public function view(User $user, WeeklyGoal $weeklyGoal): bool
    {
        return $user->id === $weeklyGoal->user_id;
    }

    public function update(User $user, WeeklyGoal $weeklyGoal): bool
    {
        return $user->id === $weeklyGoal->user_id;
    }

    public function delete(User $user, WeeklyGoal $weeklyGoal): bool
    {
        return $user->id === $weeklyGoal->user_id;
    }
}
