# DuoManifest - Database Schema

## Users Table (Extended)
```sql
users
- id
- name
- email
- password
- avatar
- level (default: 1)
- total_xp (default: 0)
- current_streak (default: 0)
- longest_streak (default: 0)
- last_activity_date
- timezone
- notification_preferences (json)
- created_at
- updated_at
```

## Affirmations Module

### affirmations
```sql
- id
- user_id (nullable - null for system affirmations)
- category_id
- content (text)
- is_favorite (boolean)
- is_system (boolean)
- created_at
- updated_at
```

### affirmation_categories
```sql
- id
- name (wealth, health, love, success, confidence, gratitude)
- icon
- color
- created_at
- updated_at
```

### affirmation_sessions
```sql
- id
- user_id
- affirmation_id
- completed_at
- xp_earned
- session_duration (seconds)
- created_at
```

## Bucket List Module

### bucket_list_items
```sql
- id
- user_id
- category_id
- title
- description (text)
- target_date (nullable)
- progress (0-100)
- priority (low, medium, high)
- status (pending, in_progress, completed)
- completed_at (nullable)
- cover_image (nullable)
- xp_reward (default: 100)
- created_at
- updated_at
```

### bucket_list_categories
```sql
- id
- user_id (nullable - null for default categories)
- name
- icon
- color
- created_at
- updated_at
```

### bucket_list_milestones
```sql
- id
- bucket_list_item_id
- title
- is_completed
- completed_at
- created_at
- updated_at
```

### bucket_list_photos
```sql
- id
- bucket_list_item_id
- path
- caption
- created_at
```

## Dreams Module

### dreams
```sql
- id
- user_id
- category_id
- title
- description (text)
- visualization_image
- status (dreaming, manifesting, manifested)
- manifestation_date (nullable)
- affirmation (related affirmation text)
- xp_reward
- created_at
- updated_at
```

### dream_categories
```sql
- id
- name
- icon
- color
- created_at
- updated_at
```

### dream_journal_entries
```sql
- id
- dream_id
- content (text)
- mood
- created_at
- updated_at
```

## Planner Module

### planner_tasks
```sql
- id
- user_id
- title
- description
- task_date
- task_type (intention, goal, habit, task)
- priority
- is_recurring
- recurrence_pattern (json)
- is_completed
- completed_at
- xp_reward
- created_at
- updated_at
```

### habits
```sql
- id
- user_id
- name
- description
- frequency (daily, weekly)
- target_count
- icon
- color
- xp_per_completion
- created_at
- updated_at
```

### habit_logs
```sql
- id
- habit_id
- log_date
- count
- created_at
```

## Gamification Module

### achievements
```sql
- id
- name
- description
- icon
- category (streak, completion, milestone, special)
- requirement_type
- requirement_value
- xp_reward
- badge_color
- created_at
- updated_at
```

### user_achievements
```sql
- id
- user_id
- achievement_id
- unlocked_at
- created_at
```

### xp_transactions
```sql
- id
- user_id
- amount
- source_type (affirmation, bucket_list, dream, planner, achievement, streak)
- source_id
- description
- created_at
```

### streaks
```sql
- id
- user_id
- streak_type (daily_login, affirmation, planner)
- current_count
- longest_count
- last_activity_date
- created_at
- updated_at
```

### levels
```sql
- id
- level_number
- xp_required
- title (Beginner, Novice, Apprentice, etc.)
- badge_icon
- created_at
```

## Relationships Summary

- User hasMany Affirmations
- User hasMany BucketListItems
- User hasMany Dreams
- User hasMany PlannerTasks
- User hasMany Habits
- User hasMany Achievements (through user_achievements)
- User hasMany XpTransactions
- User hasMany Streaks

- BucketListItem hasMany Milestones
- BucketListItem hasMany Photos
- Dream hasMany JournalEntries
- Habit hasMany HabitLogs
