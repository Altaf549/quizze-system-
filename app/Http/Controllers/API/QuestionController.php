<?php

namespace App\Http\Controllers\API;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends ApiController
{
    /**
     * Display a listing of the questions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $questions = Question::with('quiz')->get();
        return $this->sendResponse($questions, 'Questions retrieved successfully.');
    }

    /**
     * Display the specified question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $question = Question::with('quiz')->find($id);
        
        if (is_null($question)) {
            return $this->sendError('Question not found.');
        }

        return $this->sendResponse($question, 'Question retrieved successfully.');
    }
}
