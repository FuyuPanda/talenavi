<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;

class TaskController extends Controller
{
    //
    public function store(TaskRequest $request)
    {
        
         $task=Task::create($request->validated());  


            return response()->json([
            'message' => 'To do list has been successfuly created!',
            'todo' => $task,
            ],200);
    }


}
