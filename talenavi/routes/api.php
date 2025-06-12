<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;

//create todo
Route::post('/todo', [TaskController::class, 'store']);
Route::get('/todo/export',[TaskController::class, 'export']);
