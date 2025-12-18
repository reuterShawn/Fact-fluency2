<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\SessionController;

// Public routes (no authentication needed)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Problem generation
    Route::get('/problems/generate', [ProblemController::class, 'generate']);
    
    // Session management
    Route::post('/sessions/start', [SessionController::class, 'start']);
    Route::post('/sessions/{session}/answer', [SessionController::class, 'submitAnswer']);
    Route::post('/sessions/{session}/complete', [SessionController::class, 'complete']);
    Route::get('/student/sessions', [SessionController::class, 'index']);

    // Get student's frequently missed problems
    Route::get('/student/missed-problems', [SessionController::class, 'getMissedProblems']);
    Route::post('/sessions/start-missed', [SessionController::class, 'startMissedProblemsSession']);
    Route::get('/student/stats', [SessionController::class, 'getStudentStats']);
});