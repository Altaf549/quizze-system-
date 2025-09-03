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
        $categories = Category::all();
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
}
