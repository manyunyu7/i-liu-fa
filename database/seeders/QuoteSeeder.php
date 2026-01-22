<?php

namespace Database\Seeders;

use App\Models\Quote;
use Illuminate\Database\Seeder;

class QuoteSeeder extends Seeder
{
    public function run(): void
    {
        $quotes = [
            // Motivation
            ['content' => 'The only way to do great work is to love what you do.', 'author' => 'Steve Jobs', 'category' => 'motivation'],
            ['content' => 'Believe you can and you\'re halfway there.', 'author' => 'Theodore Roosevelt', 'category' => 'motivation'],
            ['content' => 'It does not matter how slowly you go as long as you do not stop.', 'author' => 'Confucius', 'category' => 'motivation'],
            ['content' => 'Success is not final, failure is not fatal: it is the courage to continue that counts.', 'author' => 'Winston Churchill', 'category' => 'motivation'],
            ['content' => 'The future belongs to those who believe in the beauty of their dreams.', 'author' => 'Eleanor Roosevelt', 'category' => 'motivation'],

            // Success
            ['content' => 'Success is not the key to happiness. Happiness is the key to success.', 'author' => 'Albert Schweitzer', 'category' => 'success'],
            ['content' => 'The secret of success is to do the common thing uncommonly well.', 'author' => 'John D. Rockefeller Jr.', 'category' => 'success'],
            ['content' => 'Success usually comes to those who are too busy to be looking for it.', 'author' => 'Henry David Thoreau', 'category' => 'success'],
            ['content' => 'Don\'t be afraid to give up the good to go for the great.', 'author' => 'John D. Rockefeller', 'category' => 'success'],
            ['content' => 'I find that the harder I work, the more luck I seem to have.', 'author' => 'Thomas Jefferson', 'category' => 'success'],

            // Happiness
            ['content' => 'Happiness is not something ready made. It comes from your own actions.', 'author' => 'Dalai Lama', 'category' => 'happiness'],
            ['content' => 'The happiness of your life depends upon the quality of your thoughts.', 'author' => 'Marcus Aurelius', 'category' => 'happiness'],
            ['content' => 'For every minute you are angry you lose sixty seconds of happiness.', 'author' => 'Ralph Waldo Emerson', 'category' => 'happiness'],
            ['content' => 'Happiness is when what you think, what you say, and what you do are in harmony.', 'author' => 'Mahatma Gandhi', 'category' => 'happiness'],
            ['content' => 'The most important thing is to enjoy your life—to be happy—it\'s all that matters.', 'author' => 'Audrey Hepburn', 'category' => 'happiness'],

            // Gratitude
            ['content' => 'Gratitude turns what we have into enough.', 'author' => 'Anonymous', 'category' => 'gratitude'],
            ['content' => 'When you are grateful, fear disappears and abundance appears.', 'author' => 'Tony Robbins', 'category' => 'gratitude'],
            ['content' => 'Gratitude is not only the greatest of virtues, but the parent of all others.', 'author' => 'Cicero', 'category' => 'gratitude'],
            ['content' => 'Enjoy the little things, for one day you may look back and realize they were the big things.', 'author' => 'Robert Brault', 'category' => 'gratitude'],
            ['content' => 'The more grateful I am, the more beauty I see.', 'author' => 'Mary Davis', 'category' => 'gratitude'],

            // Mindfulness
            ['content' => 'The present moment is filled with joy and happiness. If you are attentive, you will see it.', 'author' => 'Thich Nhat Hanh', 'category' => 'mindfulness'],
            ['content' => 'Be where you are, not where you think you should be.', 'author' => 'Anonymous', 'category' => 'mindfulness'],
            ['content' => 'The best way to capture moments is to pay attention. This is how we cultivate mindfulness.', 'author' => 'Jon Kabat-Zinn', 'category' => 'mindfulness'],
            ['content' => 'Mindfulness is a way of befriending ourselves and our experience.', 'author' => 'Jon Kabat-Zinn', 'category' => 'mindfulness'],
            ['content' => 'In today\'s rush, we all think too much, seek too much, want too much, and forget about the joy of just being.', 'author' => 'Eckhart Tolle', 'category' => 'mindfulness'],

            // Courage
            ['content' => 'Courage is not the absence of fear, but rather the judgment that something else is more important than fear.', 'author' => 'Ambrose Redmoon', 'category' => 'courage'],
            ['content' => 'You gain strength, courage, and confidence by every experience in which you really stop to look fear in the face.', 'author' => 'Eleanor Roosevelt', 'category' => 'courage'],
            ['content' => 'Life shrinks or expands in proportion to one\'s courage.', 'author' => 'Anaïs Nin', 'category' => 'courage'],
            ['content' => 'It takes courage to grow up and become who you really are.', 'author' => 'E.E. Cummings', 'category' => 'courage'],
            ['content' => 'Have the courage to follow your heart and intuition.', 'author' => 'Steve Jobs', 'category' => 'courage'],

            // Perseverance
            ['content' => 'Perseverance is not a long race; it is many short races one after the other.', 'author' => 'Walter Elliot', 'category' => 'perseverance'],
            ['content' => 'Our greatest glory is not in never falling, but in rising every time we fall.', 'author' => 'Confucius', 'category' => 'perseverance'],
            ['content' => 'The difference between a successful person and others is not a lack of strength, but rather a lack of will.', 'author' => 'Vince Lombardi', 'category' => 'perseverance'],
            ['content' => 'Fall seven times, stand up eight.', 'author' => 'Japanese Proverb', 'category' => 'perseverance'],
            ['content' => 'Rivers know this: there is no hurry. We shall get there some day.', 'author' => 'A.A. Milne', 'category' => 'perseverance'],

            // Wisdom
            ['content' => 'The only true wisdom is in knowing you know nothing.', 'author' => 'Socrates', 'category' => 'wisdom'],
            ['content' => 'In the middle of difficulty lies opportunity.', 'author' => 'Albert Einstein', 'category' => 'wisdom'],
            ['content' => 'We do not see things as they are, we see them as we are.', 'author' => 'Anaïs Nin', 'category' => 'wisdom'],
            ['content' => 'The mind is everything. What you think you become.', 'author' => 'Buddha', 'category' => 'wisdom'],
            ['content' => 'Knowledge speaks, but wisdom listens.', 'author' => 'Jimi Hendrix', 'category' => 'wisdom'],

            // General
            ['content' => 'Be the change you wish to see in the world.', 'author' => 'Mahatma Gandhi', 'category' => 'general'],
            ['content' => 'What lies behind us and what lies before us are tiny matters compared to what lies within us.', 'author' => 'Ralph Waldo Emerson', 'category' => 'general'],
            ['content' => 'The journey of a thousand miles begins with one step.', 'author' => 'Lao Tzu', 'category' => 'general'],
            ['content' => 'Your time is limited, don\'t waste it living someone else\'s life.', 'author' => 'Steve Jobs', 'category' => 'general'],
            ['content' => 'In the end, it\'s not the years in your life that count. It\'s the life in your years.', 'author' => 'Abraham Lincoln', 'category' => 'general'],
        ];

        foreach ($quotes as $quote) {
            Quote::updateOrCreate(
                ['content' => $quote['content']],
                array_merge($quote, [
                    'is_active' => true,
                    'likes_count' => rand(0, 50),
                ])
            );
        }
    }
}
