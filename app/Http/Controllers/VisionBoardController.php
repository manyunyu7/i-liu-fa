<?php

namespace App\Http\Controllers;

use App\Models\VisionBoard;
use App\Models\VisionBoardItem;
use Illuminate\Http\Request;

class VisionBoardController extends Controller
{
    public function index()
    {
        $boards = auth()->user()->visionBoards()
            ->withCount('items')
            ->orderBy('is_primary', 'desc')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('vision-board.index', compact('boards'));
    }

    public function create()
    {
        $themes = [
            'default' => ['name' => 'Default', 'bg' => '#f8fafc', 'preview' => 'bg-gray-50'],
            'cosmic' => ['name' => 'Cosmic Dreams', 'bg' => '#1a1a2e', 'preview' => 'bg-indigo-950'],
            'nature' => ['name' => 'Nature', 'bg' => '#ecfdf5', 'preview' => 'bg-emerald-50'],
            'sunset' => ['name' => 'Sunset Glow', 'bg' => '#fff7ed', 'preview' => 'bg-orange-50'],
            'ocean' => ['name' => 'Ocean Blue', 'bg' => '#eff6ff', 'preview' => 'bg-blue-50'],
        ];

        return view('vision-board.create', compact('themes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'theme' => 'required|string|in:default,cosmic,nature,sunset,ocean',
            'is_primary' => 'boolean',
        ]);

        $backgroundColors = [
            'default' => '#f8fafc',
            'cosmic' => '#1a1a2e',
            'nature' => '#ecfdf5',
            'sunset' => '#fff7ed',
            'ocean' => '#eff6ff',
        ];

        $board = auth()->user()->visionBoards()->create([
            'title' => $request->title,
            'description' => $request->description,
            'theme' => $request->theme,
            'background_color' => $backgroundColors[$request->theme] ?? '#f8fafc',
            'is_primary' => $request->boolean('is_primary'),
        ]);

        if ($request->boolean('is_primary')) {
            $board->makePrimary();
        }

        return redirect()->route('vision-board.show', $board)
            ->with('success', 'Vision board created! Start adding your dreams!');
    }

    public function show(VisionBoard $visionBoard)
    {
        if ($visionBoard->user_id !== auth()->id() && !$visionBoard->is_public) {
            abort(403);
        }

        $visionBoard->load('items.dream');
        $dreams = auth()->user()->dreams()->dreaming()->get();
        $affirmations = auth()->user()->affirmations()->get();

        return view('vision-board.show', compact('visionBoard', 'dreams', 'affirmations'));
    }

    public function edit(VisionBoard $visionBoard)
    {
        if ($visionBoard->user_id !== auth()->id()) {
            abort(403);
        }

        $themes = [
            'default' => ['name' => 'Default', 'bg' => '#f8fafc', 'preview' => 'bg-gray-50'],
            'cosmic' => ['name' => 'Cosmic Dreams', 'bg' => '#1a1a2e', 'preview' => 'bg-indigo-950'],
            'nature' => ['name' => 'Nature', 'bg' => '#ecfdf5', 'preview' => 'bg-emerald-50'],
            'sunset' => ['name' => 'Sunset Glow', 'bg' => '#fff7ed', 'preview' => 'bg-orange-50'],
            'ocean' => ['name' => 'Ocean Blue', 'bg' => '#eff6ff', 'preview' => 'bg-blue-50'],
        ];

        return view('vision-board.edit', compact('visionBoard', 'themes'));
    }

    public function update(Request $request, VisionBoard $visionBoard)
    {
        if ($visionBoard->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'theme' => 'required|string|in:default,cosmic,nature,sunset,ocean',
            'is_public' => 'boolean',
            'is_primary' => 'boolean',
        ]);

        $backgroundColors = [
            'default' => '#f8fafc',
            'cosmic' => '#1a1a2e',
            'nature' => '#ecfdf5',
            'sunset' => '#fff7ed',
            'ocean' => '#eff6ff',
        ];

        $visionBoard->update([
            'title' => $request->title,
            'description' => $request->description,
            'theme' => $request->theme,
            'background_color' => $backgroundColors[$request->theme] ?? $visionBoard->background_color,
            'is_public' => $request->boolean('is_public'),
        ]);

        if ($request->boolean('is_primary')) {
            $visionBoard->makePrimary();
        }

        return redirect()->route('vision-board.show', $visionBoard)
            ->with('success', 'Vision board updated!');
    }

    public function destroy(VisionBoard $visionBoard)
    {
        if ($visionBoard->user_id !== auth()->id()) {
            abort(403);
        }

        $visionBoard->delete();

        return redirect()->route('vision-board.index')
            ->with('success', 'Vision board deleted!');
    }

    public function addItem(Request $request, VisionBoard $visionBoard)
    {
        if ($visionBoard->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'type' => 'required|in:image,text,quote,goal,affirmation',
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image_url' => 'nullable|url',
            'dream_id' => 'nullable|exists:dreams,id',
            'position_x' => 'integer',
            'position_y' => 'integer',
            'width' => 'integer|min:50',
            'height' => 'integer|min:50',
        ]);

        $maxZIndex = $visionBoard->items()->max('z_index') ?? 0;

        $item = $visionBoard->items()->create([
            'type' => $request->type,
            'title' => $request->title,
            'content' => $request->content,
            'image_url' => $request->image_url,
            'dream_id' => $request->dream_id,
            'position_x' => $request->position_x ?? 50,
            'position_y' => $request->position_y ?? 50,
            'width' => $request->width ?? 200,
            'height' => $request->height ?? 200,
            'z_index' => $maxZIndex + 1,
            'text_color' => $request->text_color ?? '#1f2937',
            'background_color' => $request->background_color ?? '#ffffff',
        ]);

        return response()->json([
            'success' => true,
            'item' => $item,
        ]);
    }

    public function updateItem(Request $request, VisionBoardItem $item)
    {
        if ($item->visionBoard->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'position_x' => 'integer',
            'position_y' => 'integer',
            'width' => 'integer|min:50',
            'height' => 'integer|min:50',
            'rotation' => 'integer|min:-180|max:180',
            'content' => 'nullable|string',
            'title' => 'nullable|string|max:255',
        ]);

        $item->update($request->only([
            'position_x',
            'position_y',
            'width',
            'height',
            'rotation',
            'content',
            'title',
            'text_color',
            'background_color',
            'z_index',
        ]));

        return response()->json([
            'success' => true,
            'item' => $item->fresh(),
        ]);
    }

    public function deleteItem(VisionBoardItem $item)
    {
        if ($item->visionBoard->user_id !== auth()->id()) {
            abort(403);
        }

        $item->delete();

        return response()->json(['success' => true]);
    }
}
