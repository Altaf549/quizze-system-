<?php

use Illuminate\Support\Facades\Route;

// Debug: Log that the API routes file is being loaded
if (!function_exists('debug_api_routes')) {
    function debug_api_routes($message) {
        $logFile = storage_path('logs/api_routes_debug.log');
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
    }
}

debug_api_routes('API routes file loaded');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API v1 Routes
Route::prefix('v1')->group(function () {
    // Categories
    Route::get('categories', 'App\Http\Controllers\API\CategoryController@index');
    Route::get('categories/{id}', 'App\Http\Controllers\API\CategoryController@show');

    // Quizzes
    Route::get('quizzes', 'App\Http\Controllers\API\QuizController@index');
    Route::get('quizzes/{id}', 'App\Http\Controllers\API\QuizController@show');

    // Questions
    Route::get('questions', 'App\Http\Controllers\API\QuestionController@index');
    Route::get('questions/{id}', 'App\Http\Controllers\API\QuestionController@show');
});
