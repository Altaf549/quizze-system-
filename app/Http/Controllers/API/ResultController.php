<?php

namespace App\Http\Controllers\API;

use App\Models\Result;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResultController extends ApiController
{
    /**
     * Submit quiz answers and store result
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'quiz_id' => 'required|integer|exists:quizzes,id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|integer|exists:questions,id',
            'answers.*.selected_answer' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        try {
            $quiz = Quiz::with('questions')->findOrFail($request->quiz_id);
            $answers = $request->answers;
            
            // Calculate score
            $score = 0;
            $totalQuestions = $quiz->questions->count();
            
            foreach ($quiz->questions as $question) {
                $userAnswer = collect($answers)->firstWhere('question_id', $question->id);
                
                if ($userAnswer && $userAnswer['selected_answer'] === $question->correct_answer) {
                    $score++;
                }
            }

            // Store result
            $result = Result::create([
                'device_id' => $request->device_id,
                'quiz_id' => $request->quiz_id,
                'score' => $score,
                'total_questions' => $totalQuestions,
                'completed_at' => now()
            ]);

            $responseData = [
                'result_id' => $result->id,
                'quiz_id' => $result->quiz_id,
                'score' => $result->score,
                'total_questions' => $result->total_questions,
                'percentage' => $totalQuestions > 0 ? round(($score / $totalQuestions) * 100, 2) : 0,
                'completed_at' => $result->completed_at
            ];

            return $this->sendResponse($responseData, 'Quiz submitted successfully!');

        } catch (\Exception $e) {
            return $this->sendError('Failed to submit quiz: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Get completed quizzes for a device
     *
     * @param  string  $device_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompletedQuizzes($device_id)
    {
        try {
            $results = Result::with(['quiz.category'])
                ->where('device_id', $device_id)
                ->orderBy('completed_at', 'desc')
                ->get();

            $responseData = $results->map(function ($result) {
                return [
                    'result_id' => $result->id,
                    'quiz' => [
                        'id' => $result->quiz->id,
                        'title' => $result->quiz->title,
                        'category' => [
                            'id' => $result->quiz->category->id,
                            'name' => $result->quiz->category->name
                        ]
                    ],
                    'score' => $result->score,
                    'total_questions' => $result->total_questions,
                    'percentage' => $result->total_questions > 0 ? round(($result->score / $result->total_questions) * 100, 2) : 0,
                    'completed_at' => $result->completed_at
                ];
            });

            return $this->sendResponse($responseData, 'Completed quizzes retrieved successfully.');

        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve completed quizzes: ' . $e->getMessage(), [], 500);
        }
    }

    /**
     * Get quiz result details
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $result = Result::with(['quiz.category', 'quiz.questions'])
                ->findOrFail($id);

            $responseData = [
                'result_id' => $result->id,
                'quiz' => [
                    'id' => $result->quiz->id,
                    'title' => $result->quiz->title,
                    'time_limit' => $result->quiz->time_limit,
                    'category' => [
                        'id' => $result->quiz->category->id,
                        'name' => $result->quiz->category->name
                    ]
                ],
                'score' => $result->score,
                'total_questions' => $result->total_questions,
                'percentage' => $result->total_questions > 0 ? round(($result->score / $result->total_questions) * 100, 2) : 0,
                'completed_at' => $result->completed_at
            ];

            return $this->sendResponse($responseData, 'Result retrieved successfully.');

        } catch (\Exception $e) {
            return $this->sendError('Result not found.', [], 404);
        }
    }

    /**
     * Get quiz statistics for a device
     *
     * @param  string  $device_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistics($device_id)
    {
        try {
            $results = Result::where('device_id', $device_id)->get();
            
            $totalQuizzes = $results->count();
            $totalScore = $results->sum('score');
            $totalQuestions = $results->sum('total_questions');
            $averageScore = $totalQuestions > 0 ? round(($totalScore / $totalQuestions) * 100, 2) : 0;
            
            // Category-wise performance
            $categoryStats = Result::with('quiz.category')
                ->where('device_id', $device_id)
                ->get()
                ->groupBy('quiz.category.name')
                ->map(function ($categoryResults) {
                    $totalQuestions = $categoryResults->sum('total_questions');
                    $totalScore = $categoryResults->sum('score');
                    return [
                        'quizzes_taken' => $categoryResults->count(),
                        'average_percentage' => $totalQuestions > 0 ? round(($totalScore / $totalQuestions) * 100, 2) : 0
                    ];
                });

            $responseData = [
                'total_quizzes_taken' => $totalQuizzes,
                'overall_average_score' => $averageScore,
                'total_questions_answered' => $totalQuestions,
                'correct_answers' => $totalScore,
                'category_performance' => $categoryStats
            ];

            return $this->sendResponse($responseData, 'Statistics retrieved successfully.');

        } catch (\Exception $e) {
            return $this->sendError('Failed to retrieve statistics: ' . $e->getMessage(), [], 500);
        }
    }
}
