<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

// Public API routes
Route::post('/login', [ApiController::class, 'login']);
Route::post('/register', [ApiController::class, 'register']);

// Authenticated API routes
Route::middleware('api.token')->group(function () {
    Route::get('/users', [ApiController::class, 'users']);
    Route::get('/courses', [ApiController::class, 'courses']);
    Route::post('/enroll', [ApiController::class, 'enroll']);
    Route::get('/grades', [ApiController::class, 'grades']);
});