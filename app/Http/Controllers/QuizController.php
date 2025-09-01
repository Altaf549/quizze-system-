<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('category')->get();
        $categories = Category::all();
        return view('admin.quizzes.index', compact('quizzes', 'categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'time_limit' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $quiz = Quiz::create([
            'title' => $request->title,
            'time_limit' => $request->time_limit,
            'category_id' => $request->category_id,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quiz created successfully',
            'quiz' => $quiz->load('category')
        ]);
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'time_limit' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $quiz->update([
            'title' => $request->title,
            'time_limit' => $request->time_limit,
            'category_id' => $request->category_id,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quiz updated successfully',
            'quiz' => $quiz->load('category')
        ]);
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return response()->json([
            'success' => true,
            'message' => 'Quiz deleted successfully'
        ]);
    }

    public function show(Quiz $quiz)
    {
        $quiz->load(['questions', 'category']);
        return view('admin.quizzes.show', compact('quiz'));
    }
}
