<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController as ApiTaskController;

Route::apiResource('tasks', ApiTaskController::class);
Route::patch('tasks/{task}/toggle-status', [ApiTaskController::class, 'toggleStatus']);
