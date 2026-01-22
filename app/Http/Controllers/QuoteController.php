<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category');

        $query = Quote::active();

        if ($category) {
            $query->byCategory($category);
        }

        $quotes = $query->orderBy('likes_count', 'desc')
            ->paginate(20);

        $categories = Quote::getCategories();
        $favoriteIds = auth()->user()->favoriteQuotes()->pluck('quotes.id')->toArray();

        return view('quotes.index', compact('quotes', 'categories', 'category', 'favoriteIds'));
    }

    public function daily()
    {
        $quote = Quote::getDailyQuote(auth()->user());

        return view('quotes.daily', compact('quote'));
    }

    public function random(Request $request)
    {
        $category = $request->get('category');
        $quote = Quote::random($category);

        if ($request->wantsJson()) {
            return response()->json([
                'quote' => $quote,
                'is_favorite' => auth()->user()->favoriteQuotes()->where('quote_id', $quote?->id)->exists(),
            ]);
        }

        return view('quotes.show', compact('quote'));
    }

    public function toggleFavorite(Quote $quote)
    {
        $user = auth()->user();

        if ($user->favoriteQuotes()->where('quote_id', $quote->id)->exists()) {
            $user->favoriteQuotes()->detach($quote->id);
            $quote->decrement('likes_count');
            $message = 'Quote removed from favorites.';
        } else {
            $user->favoriteQuotes()->attach($quote->id);
            $quote->increment('likes_count');
            $message = 'Quote added to favorites!';
        }

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_favorite' => $user->favoriteQuotes()->where('quote_id', $quote->id)->exists(),
                'likes_count' => $quote->fresh()->likes_count,
            ]);
        }

        return back()->with('success', $message);
    }

    public function favorites()
    {
        $quotes = auth()->user()->favoriteQuotes()
            ->orderBy('quote_user.created_at', 'desc')
            ->paginate(20);

        return view('quotes.favorites', compact('quotes'));
    }
}
