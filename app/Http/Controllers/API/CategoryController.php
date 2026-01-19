<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
        return $this->sendResponse($categories, 'Categories retrieved successfully.');
    }

    /**
     * Display the specified category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $category = Category::find($id);
        
        if (is_null($category)) {
            return $this->sendError('Category not found.');
        }

        return $this->sendResponse($category, 'Category retrieved successfully.');
    }

    /**
     * Display all quizzes for a specific category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function quizzes($id)
    {
        $category = Category::with(['quizzes' => function($query) {
            $query->where('is_active', true)
                  ->orderBy('created_at', 'desc');
        }])->find($id);
        
        if (is_null($category)) {
            return $this->sendError('Category not found.');
        }

        return $this->sendResponse($category->quizzes, 'Quizzes retrieved successfully.');
    }
}
