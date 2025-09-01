<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quiz_id' => 'required|exists:quizzes,id',
            'question_text' => 'required|string',
            'options' => 'required|array|size:4',
            'options.*' => 'required|string|distinct',
            'correct_answer' => 'required|in:A,B,C,D'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Convert options array to indexed array A, B, C, D
        $options = array_combine(['A', 'B', 'C', 'D'], $request->options);

        $question = Question::create([
            'quiz_id' => $request->quiz_id,
            'question_text' => $request->question_text,
            'options' => $options,
            'correct_answer' => $request->correct_answer
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Question created successfully',
            'question' => $question
        ]);
    }

    public function update(Request $request, Question $question)
    {
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'options' => 'required|array|size:4',
            'options.*' => 'required|string|distinct',
            'correct_answer' => 'required|in:A,B,C,D'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Convert options array to indexed array A, B, C, D
        $options = array_combine(['A', 'B', 'C', 'D'], $request->options);

        $question->update([
            'question_text' => $request->question_text,
            'options' => $options,
            'correct_answer' => $request->correct_answer
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Question updated successfully',
            'question' => $question
        ]);
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return response()->json([
            'success' => true,
            'message' => 'Question deleted successfully'
        ]);
    }
}
