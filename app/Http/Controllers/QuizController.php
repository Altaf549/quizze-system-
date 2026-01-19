<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('admin.quizzes.create', compact('categories'));
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $quizzes = Quiz::with('category');
            return datatables()
                ->eloquent($quizzes)
                ->addColumn('actions', function($quiz) {
                    return '
                        <button class="btn btn-sm btn-primary edit-quiz" data-id="'.$quiz->id.'">Edit</button>
                        <button class="btn btn-sm btn-danger delete-quiz" data-id="'.$quiz->id.'">Delete</button>
                    ';
                })
                ->rawColumns(['actions'])
                ->toJson();
        }

        $categories = Category::all();
        return view('admin.quizzes.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'required|integer|min:1',
            'difficulty' => 'string|in:easy,medium,hard',
            'total_points' => 'integer|min:0',
            'image' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'time_limit' => $request->time_limit,
            'difficulty' => $request->difficulty ?? 'medium',
            'total_points' => $request->total_points ?? 0,
            'image' => $request->image,
            'category_id' => $request->category_id,
            'is_active' => $request->is_active ?? true,
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
            'description' => 'nullable|string',
            'time_limit' => 'required|integer|min:1',
            'difficulty' => 'string|in:easy,medium,hard',
            'total_points' => 'integer|min:0',
            'image' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $quiz->update([
            'title' => $request->title,
            'description' => $request->description,
            'time_limit' => $request->time_limit,
            'difficulty' => $request->difficulty ?? $quiz->difficulty,
            'total_points' => $request->total_points ?? $quiz->total_points,
            'image' => $request->image,
            'category_id' => $request->category_id,
            'is_active' => $request->is_active ?? $quiz->is_active,
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

    public function toggleStatus(Quiz $quiz)
    {
        // Check if quiz can be activated (parent category must be active)
        if (!$quiz->is_active && !$quiz->canBeActivated()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot activate quiz because its category is inactive',
                'is_active' => $quiz->is_active
            ], 422);
        }

        $newStatus = !$quiz->is_active;
        
        // Update quiz status
        $quiz->update([
            'is_active' => $newStatus
        ]);

        // Cascade deactivation to all questions in this quiz
        if (!$newStatus) {
            $quiz->questions()->update(['is_active' => false]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Quiz status updated successfully',
            'is_active' => $quiz->is_active,
            'cascaded_questions' => !$newStatus ? $quiz->questions()->count() : 0
        ]);
    }
}
