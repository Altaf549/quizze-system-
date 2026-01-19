<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function create()
    {
        return view('admin.categories.create');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Category::query();
            return datatables()
                ->eloquent($categories)
                ->addColumn('actions', function($category) {
                    return '';  // The actions column is rendered client-side in the DataTable
                })
                ->toJson();
        }

        return view('admin.categories.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'order' => $request->order ?? 0,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'category' => $category
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon,
            'order' => $request->order ?? $category->order,
            'is_active' => $request->is_active ?? $category->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    public function toggleStatus(Category $category)
    {
        $newStatus = !$category->is_active;
        
        // Update category status
        $category->update([
            'is_active' => $newStatus
        ]);

        // Cascade deactivation to all quizzes in this category
        if (!$newStatus) {
            // Deactivate all quizzes in this category
            $category->quizzes()->update(['is_active' => false]);
            
            // Deactivate all questions in those quizzes
            $quizIds = $category->quizzes()->pluck('id');
            \App\Models\Question::whereIn('quiz_id', $quizIds)->update(['is_active' => false]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Category status updated successfully',
            'is_active' => $category->is_active,
            'cascaded_quizzes' => !$newStatus ? $category->quizzes()->count() : 0,
            'cascaded_questions' => !$newStatus ? \App\Models\Question::whereIn('quiz_id', $category->quizzes()->pluck('id'))->count() : 0
        ]);
    }
}
