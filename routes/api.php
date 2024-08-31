<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestController;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user/profile-picture', [UserController::class, 'getProfilePicture']);
Route::get('/email/verify/{id}/{token}', [AuthController::class, 'verifyEmail']);
Route::post('/test-upload', [TestController::class, 'upload']);
Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('verification.verify');

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('admin')->group(function () {
        Route::post('/users', [AdminController::class, 'createUser']);
        Route::put('/users/{user}', [AdminController::class, 'updateUser']);
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/user/profile-picture', [UserController::class, 'updateProfilePicture']);
        Route::put('/user/password', [UserController::class, 'updatePassword']);
    });
    
});
