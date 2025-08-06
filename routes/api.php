<?php

use App\Http\Controllers\Api\KeywordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;

Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/{id}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
});

Route::prefix('keywords')->group(function () {
    Route::get('/', [KeywordController::class, 'index']);
    Route::post('/', [KeywordController::class, 'store']);
});
