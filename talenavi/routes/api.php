<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;

//create todo
Route::post('/todo', [TaskController::class, 'store']);
