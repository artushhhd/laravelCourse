<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', fn (Request $request) => $request->user());
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/logout', [UserController::class, 'logout']);

    Route::apiResource('courses', CourseController::class)
        ->only(['index', 'show']);

    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{course}', [CourseController::class, 'update']);
    Route::patch('/courses/{course}', [CourseController::class, 'update']);
    Route::delete('/courses/{course}', [CourseController::class, 'destroy']);

    Route::post('/courses/{course}/like', [CourseController::class, 'toggleLike']);
    Route::post('/courses/{course}/comment', [CourseController::class, 'storeComment']);

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/courses', [AdminController::class, 'index']);
        Route::put('/courses/{course}', [AdminController::class, 'updateCourse']);
        Route::delete('/courses/{course}', [AdminController::class, 'destroyCourse']);

        Route::get('/users', [AdminController::class, 'users']);
        Route::post('/users/{user}/toggle-block', [AdminController::class, 'toggleBlock']);
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser']);
    });
});
