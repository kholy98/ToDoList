<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->apiResource('tasks', TaskController::class)
    ->except(['create', 'edit']);
Route::middleware(['auth:sanctum'])->get('/summary', [TaskController::class, 'summary']);
Route::middleware(['auth:sanctum'])->patch('/tasks/{id}/restore', [TaskController::class, 'restore']);



