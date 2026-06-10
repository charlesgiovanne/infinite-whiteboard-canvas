<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BoardController extends Controller
{
    /**
     * Display a listing of boards (Web view or JSON list).
     */
    public function index(Request $request)
    {
        $boards = Board::orderBy('updated_at', 'desc')->get();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($boards);
        }

        return view('boards.index', compact('boards'));
    }

    /**
     * Display a specific board (Web canvas or JSON details).
     */
    public function show(Request $request, $id)
    {
        $board = Board::findOrFail($id);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($board);
        }

        return view('whiteboard', compact('board'));
    }

    /**
     * Store a newly created board (API or Web).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:boards,name',
            'canvas_data' => 'nullable|string'
        ]);

        $board = Board::create([
            'name' => $validated['name'],
            'canvas_data' => $validated['canvas_data'] ?? null,
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($board, 201);
        }

        return redirect()->route('boards.show', $board->id)->with('success', 'Board created successfully!');
    }

    /**
     * Update a specific board (API or Web).
     */
    public function update(Request $request, $id)
    {
        $board = Board::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('boards', 'name')->ignore($board->id),
            ],
            'canvas_data' => 'nullable|string'
        ]);

        $board->update($validated);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($board);
        }

        return redirect()->back()->with('success', 'Board updated successfully!');
    }

    /**
     * Remove a specific board (API or Web).
     */
    public function destroy(Request $request, $id)
    {
        $board = Board::findOrFail($id);
        $board->delete();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Board deleted successfully.']);
        }

        return redirect()->route('boards.index')->with('success', 'Board deleted successfully!');
    }

    /**
     * Show the landing page.
     */
    public function home()
    {
        return view('landing');
    }
}
