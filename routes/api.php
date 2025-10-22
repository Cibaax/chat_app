<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    // Users
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/search', [UserController::class, 'search']);
    Route::get('users/{user}', [UserController::class, 'show']);

    // Chats
    Route::get('chats', [ChatController::class, 'index']);
    Route::post('chats', [ChatController::class, 'store']);
    Route::get('chats/{chat}', [ChatController::class, 'show']);
    Route::put('chats/{chat}', [ChatController::class, 'update']);
    Route::post('chats/{chat}/participants', [ChatController::class, 'addParticipant']);
    Route::delete('chats/{chat}/participants/{user}', [ChatController::class, 'removeParticipant']);

    // Messages
    Route::get('chats/{chat}/messages', [MessageController::class, 'index']);
    Route::post('chats/{chat}/messages', [MessageController::class, 'store']);
    Route::put('messages/{message}', [MessageController::class, 'update']);
    Route::delete('messages/{message}', [MessageController::class, 'destroy']);
    Route::post('chats/{chat}/mark-read', [MessageController::class, 'markAsRead']);
});