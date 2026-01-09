<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::orderBy('created_at', 'desc')->get();
        return view('todos', compact('todos'));
    }

    public function store(Request $request)
    {
        $request->validate(['title' => 'required|max:255']);

        $todo = Todo::create([
            'title' => $request->title
        ]);

        return response()->json($todo);
    }

    public function update(Todo $todo)
    {
        $todo->update([
            'is_completed' => !$todo->is_completed
        ]);

        return response()->json([
            'success' => true,
            'is_completed' => $todo->is_completed
        ]);
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response()->json(['success' => true]);
    }
}
