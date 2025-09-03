<?php

namespace App\Http\Controllers\API;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends ApiController
{
    /**
     * Display a listing of the quizzes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $quizzes = Quiz::with('category')->get();
        return $this->sendResponse($quizzes, 'Quizzes retrieved successfully.');
    }

    /**
     * Display the specified quiz with its questions.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $quiz = Quiz::with(['questions', 'category'])->find($id);
        
        if (is_null($quiz)) {
            return $this->sendError('Quiz not found.');
        }

        return $this->sendResponse($quiz, 'Quiz retrieved successfully.');
    }
}
