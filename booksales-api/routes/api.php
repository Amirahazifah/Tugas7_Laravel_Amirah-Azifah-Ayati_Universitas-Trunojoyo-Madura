<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');


Route::apiResource('authors', AuthorsController::class)->only(['index', 'show']);
Route::apiResource('genres', GenreController::class)->only(['index', 'show']);
Route::apiResource('books', BookController::class)->only(['index', 'show']);

Route::middleware(['auth:api', 'role:customer'])->group(function () {
    Route::apiResource('transactions', TransactionController::class)->only(['store', 'update', 'show']);
});

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::apiResource('authors', AuthorsController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('genres', GenreController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('books', BookController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('transactions', TransactionController::class)->only(['index', 'destroy']);
});
