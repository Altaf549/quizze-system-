<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $questions = Question::with('quiz');
            return datatables()
                ->eloquent($questions)
                ->addColumn('actions', function($question) {
                    return '
                        <button class="btn btn-sm btn-primary edit-question" data-id="'.$question->id.'">Edit</button>
                        <button class="btn btn-sm btn-danger delete-question" data-id="'.$question->id.'">Delete</button>
                    ';
                })
                ->editColumn('options', function($question) {
                    $options = collect($question->options)->map(function($option, $key) {
                        $isCorrect = $key === $question->correct_answer ? ' (✓)' : '';
                        return $key . ': ' . $option . $isCorrect;
                    })->join('<br>');
                    return '<div class="question-options">' . $options . '</div>';
                })
                ->editColumn('quiz.title', function($question) {
                    return $question->quiz->title ?? 'N/A';
                })
                ->rawColumns(['actions', 'options'])
                ->toJson();
        }

        $quizzes = \App\Models\Quiz::all();
        return view('admin.questions.index', compact('quizzes'));
    }
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
