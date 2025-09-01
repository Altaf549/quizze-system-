<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $results = \App\Models\Result::with(['user', 'quiz']);
            
            return datatables()
                ->eloquent($results)
                ->addColumn('user_name', function($result) {
                    return $result->user->name;
                })
                ->addColumn('quiz_title', function($result) {
                    return $result->quiz->title;
                })
                ->addColumn('percentage', function($result) {
                    return number_format(($result->score / $result->total_questions) * 100, 1) . '%';
                })
                ->editColumn('completed_at', function($result) {
                    return $result->completed_at->format('Y-m-d H:i:s');
                })
                ->make(true);
        }

        return view('admin.results.index');
    }
}
