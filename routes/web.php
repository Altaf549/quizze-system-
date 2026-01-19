<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\PageController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Login Routes (with auth check)
Route::get('/login', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }
    return app(App\Http\Controllers\AdminController::class)->showLoginForm();
})->name('login');

Route::post('/login', [AdminController::class, 'login'])->middleware('guest');

Route::post('/logout', [AdminController::class, 'logout'])->name('logout')->middleware('auth');

// Public Content Routes
Route::get('/about-us', [PageController::class, 'aboutUs'])->name('pages.about');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('pages.privacy');
Route::get('/terms-conditions', [PageController::class, 'termsConditions'])->name('pages.terms');

// Admin Routes
Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('categories', CategoryController::class);
    Route::post('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    Route::resource('quizzes', QuizController::class);
    Route::post('quizzes/{quiz}/toggle-status', [QuizController::class, 'toggleStatus'])->name('quizzes.toggle-status');
    Route::resource('questions', QuestionController::class);
    Route::post('questions/{question}/toggle-status', [QuestionController::class, 'toggleStatus'])->name('questions.toggle-status');
    Route::get('results', [ResultController::class, 'index'])->name('results.index');
    Route::resource('contents', ContentController::class);
});