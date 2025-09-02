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
            $query = Question::with('quiz');
            
            return datatables()
                ->eloquent($query)
                ->addColumn('options_display', function($question) {
                    if (!is_array($question->options)) {
                        try {
                            $options = json_decode($question->options, true);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                return 'Invalid options format';
                            }
                            $question->options = $options;
                        } catch (\Exception $e) {
                            return 'Error parsing options';
                        }
                    }
                    
                    $options = collect($question->options)->map(function($option, $key) use ($question) {
                        $isCorrect = $key === $question->correct_answer ? ' (✓)' : '';
                        return htmlspecialchars($key . ': ' . $option . $isCorrect, ENT_QUOTES);
                    })->join('<br>');
                    
                    return '<div class="question-options">' . $options . '</div>';
                })
                ->addColumn('actions', function($question) {
                    $options = is_string($question->options) ? $question->options : json_encode($question->options);
                    return '
                        <button class="btn btn-action btn-edit edit-question" 
                                data-id="'.$question->id.'"
                                data-question-text="'.htmlspecialchars($question->question_text, ENT_QUOTES).'"
                                data-options="'.htmlspecialchars($options, ENT_QUOTES).'"
                                data-correct-answer="'.htmlspecialchars($question->correct_answer, ENT_QUOTES).'"
                                data-bs-toggle="tooltip"
                                title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-action btn-delete delete-question ms-1" 
                                data-id="'.$question->id.'"
                                data-bs-toggle="tooltip"
                                title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>';
                })
                ->editColumn('quiz.title', function($question) {
                    return $question->quiz ? htmlspecialchars($question->quiz->title, ENT_QUOTES) : 'N/A';
                })
                ->rawColumns(['actions', 'options_display'])
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
